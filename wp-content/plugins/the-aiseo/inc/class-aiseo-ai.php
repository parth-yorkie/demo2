<?php
/**
 * AISEO_AI class.
 */

if (!defined('ABSPATH')) {
    exit;
}

class AISEO_AI
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('wp_ajax_the_aiseo_generate', array($this, 'ajax_generate'));
        add_action('wp_ajax_the_aiseo_save', array($this, 'ajax_save'));
        add_action('wp_ajax_the_aiseo_bulk_process', array($this, 'ajax_bulk_process'));
    }

    /**
     * AJAX handler for generation.
     */
    public function ajax_generate()
    {
        check_ajax_referer('the_aiseo_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id) {
            wp_send_json_error('Invalid Post ID');
        }

        $suggestion = $this->get_ai_suggestions($post_id);

        if (is_wp_error($suggestion)) {
            wp_send_json_error($suggestion->get_error_message());
        }

        wp_send_json_success($suggestion);
    }

    /**
     * Get AI suggestions for a post.
     *
     * @param int $post_id Post ID.
     * @return array|WP_Error Suggestions array or WP_Error.
     */
    private function get_ai_suggestions($post_id)
    {
        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error('post_not_found', 'Post not found');
        }

        $api_key = get_option('the_aiseo_openrouter_api_key');
        if (!$api_key) {
            return new WP_Error('api_key_missing', 'API Key missing. Please go to Settings.');
        }

        $model = get_option('the_aiseo_model', 'openai/gpt-3.5-turbo');
        $content = wp_strip_all_tags($post->post_content);
        $content = mb_substr($content, 0, 1500);

        $default_prompt = "You are an Elite SEO Copywriter. Generate a JSON object {'title': '...', 'description': '...'} for this content:

Title: {post_title}
Content: {content}

Executive Constraints:
1. Title: 50-60 chars, front-load primary keyword, no exclamation marks.
2. Description: 140-155 chars. No title repetition.
3. Zero-Tolerance List: No 'Learn', 'Discover', 'Explore', 'Unlock', 'Unleash', or 'This article'.
4. No Superlatives: Ban 'ultimate', 'best', 'comprehensive', 'amazing'. Use hard facts instead.
5. Entity Integration: Include at least one secondary semantic entity/keyword from the text.
6. The Hook: Start with a punchy, 3-5 word statement or a specific data point/stat.
7. Rhythm: Use a short first sentence followed by a descriptive second sentence.
8. Active Voice: Use high-impact verbs. No passive 'is discussed' or 'are featured' phrasing.
9. Benefit-First: Focus on the reader's outcome, not the content's features.
10. Specificity: Include a proper noun, date, or number found in the content to ground the summary.
11. Intent Match: Mirror the user's search intent (Informational vs. Transactional).
12. Scannability: Use a pipe (|) or dash (—) to separate distinct thoughts if it improves clarity.
13. No Self-Reference: Never mention 'this page' or 'below'.
14. Audience Mirroring: Match the technical level of the vocabulary to the source content.
15. Clean JSON: Return ONLY the raw JSON object. No markdown, no 'Here is your JSON'.";

        $prompt = get_option('the_aiseo_prompt', $default_prompt);
        if (empty(trim($prompt))) {
            $prompt = $default_prompt;
        }

        $prompt = str_replace(
            array('{post_title}', '{content}'),
            array($post->post_title, $content),
            $prompt
        );

        $response = wp_remote_post('https://openrouter.ai/api/v1/chat/completions', array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'HTTP-Referer' => home_url(),
                'X-Title' => 'The AISEO WordPress Plugin',
                'Content-Type' => 'application/json',
            ),
            'body' => wp_json_encode(array(
                'model' => $model,
                'messages' => array(
                    array('role' => 'user', 'content' => $prompt),
                ),
            )),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['error'])) {
            return new WP_Error('api_error', $data['error']['message']);
        }

        $suggestion_json = isset($data['choices'][0]['message']['content']) ? $data['choices'][0]['message']['content'] : '';
        $suggestion_json = preg_replace('/^```json|```$/', '', trim($suggestion_json));
        $suggestion = json_decode($suggestion_json, true);

        if (!$suggestion || !isset($suggestion['title']) || !isset($suggestion['description'])) {
            return new WP_Error('parse_error', 'Failed to parse AI response');
        }

        return $suggestion;
    }

    /**
     * AJAX handler for bulk processing.
     */
    public function ajax_bulk_process()
    {
        check_ajax_referer('the_aiseo_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $field_type = isset($_POST['field_type']) ? sanitize_text_field($_POST['field_type']) : '';

        if (empty($post_type) || !in_array($field_type, array('title', 'description'))) {
            wp_send_json_error('Invalid parameters');
        }

        $scanner = new AISEO_Scanner();
        $posts = $scanner->get_items_missing_meta($post_type, $field_type, 5); // Limit 5 per batch

        if (empty($posts)) {
            wp_send_json_success(array('done' => true));
        }

        $processed = 0;
        $last_error = '';

        foreach ($posts as $post) {
            $suggestion = $this->get_ai_suggestions($post->ID);
            if (!is_wp_error($suggestion)) {
                $updated = false;
                if ($field_type === 'title' && !empty($suggestion['title'])) {
                    update_post_meta($post->ID, '_yoast_wpseo_title', $suggestion['title']);
                    $updated = true;
                } elseif ($field_type === 'description' && !empty($suggestion['description'])) {
                    update_post_meta($post->ID, '_yoast_wpseo_metadesc', $suggestion['description']);
                    $updated = true;
                }

                if ($updated) {
                    // Log the update
                    self::log_update($post->ID, get_the_title($post->ID), ($field_type === 'title' ? $suggestion['title'] : null), ($field_type === 'description' ? $suggestion['description'] : null));
                    $processed++;
                }
            } else {
                $last_error = $suggestion->get_error_message();
            }
        }

        // If even after trying to process we still got no "new" processed items, 
        // it means we are either stuck or genuinely done.
        if ($processed === 0 && !empty($posts)) {
            $error_msg = $last_error ? $last_error : 'API error or items could not be updated. Please check your API key and logs.';
            wp_send_json_error(array('message' => $error_msg));
        }

        wp_send_json_success(array(
            'done' => false,
            'processed' => $processed,
        ));
    }

    /**
     * AJAX handler for saving.
     */
    public function ajax_save()
    {
        check_ajax_referer('the_aiseo_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : null;
        $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : null;

        if (!$post_id) {
            wp_send_json_error('Invalid Post ID');
        }

        // Save as Yoast Meta if Yoast is active (standard meta keys anyway).
        if ($title !== null) {
            update_post_meta($post_id, '_yoast_wpseo_title', $title);
        }
        if ($description !== null) {
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $description);
        }

        self::log_update($post_id, get_the_title($post_id), $title, $description);

        wp_send_json_success('Successfully updated meta data.');
    }

    /**
     * Log the update to the database.
     */
    private static function log_update($post_id, $post_title, $meta_title = null, $meta_desc = null)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aiseo_logs';
        $post = get_post($post_id);

        $wpdb->insert(
            $table_name,
            array(
                'post_id' => $post_id,
                'post_type' => $post ? $post->post_type : '',
                'post_title' => $post_title,
                'meta_title' => $meta_title,
                'meta_desc' => $meta_desc,
                'created_at' => current_time('mysql'),
            )
        );
    }
}
