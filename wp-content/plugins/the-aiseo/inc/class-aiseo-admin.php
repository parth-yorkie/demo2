<?php
/**
 * AISEO_Admin class.
 */

if (!defined('ABSPATH')) {
    exit;
}

class AISEO_Admin
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'ensure_logs_table_exists'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    /**
     * Ensure the logs database table exists.
     */
    public function ensure_logs_table_exists()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aiseo_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            post_type varchar(50) NOT NULL,
            post_title text NOT NULL,
            meta_title text,
            meta_desc text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Register admin menu.
     */
    public function register_admin_menu()
    {
        add_menu_page(
            'The AISEO',
            'The AISEO',
            'manage_options',
            'the-aiseo',
            array($this, 'render_admin_page'),
            'dashicons-chart-bar',
            30
        );

        add_submenu_page(
            'the-aiseo',
            'Scanner',
            'Scanner',
            'manage_options',
            'the-aiseo',
            array($this, 'render_admin_page')
        );

        add_submenu_page(
            'the-aiseo',
            'Settings',
            'Settings',
            'manage_options',
            'the-aiseo-settings',
            array($this, 'render_settings_page')
        );

        add_submenu_page(
            'the-aiseo',
            'Logs',
            'Logs',
            'manage_options',
            'the-aiseo-logs',
            array($this, 'render_logs_page')
        );
    }

    /**
     * Register settings.
     */
    public function register_settings()
    {
        register_setting('the_aiseo_settings', 'the_aiseo_openrouter_api_key');
        register_setting('the_aiseo_settings', 'the_aiseo_model', array('default' => 'openai/gpt-3.5-turbo'));
        register_setting('the_aiseo_settings', 'the_aiseo_prompt');
    }

    /**
     * Enqueue assets.
     */
    public function enqueue_assets($hook)
    {
        if ('toplevel_page_the-aiseo' !== $hook) {
            return;
        }

        wp_enqueue_style('the-aiseo-admin', THE_AISEO_URL . 'assets/css/admin.css');
        wp_enqueue_script('the-aiseo-admin', THE_AISEO_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', true);

        wp_localize_script('the-aiseo-admin', 'the_aiseo_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('the_aiseo_nonce'),
        ));
    }

    /**
     * Render admin page.
     */
    public function render_admin_page()
    {
        $scanner = new AISEO_Scanner();
        $current_post_type = isset($_GET['post_type_filter']) ? sanitize_text_field($_GET['post_type_filter']) : '';
        $current_missing_filter = isset($_GET['missing_filter']) ? sanitize_text_field($_GET['missing_filter']) : 'all';
        $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = 20;

        $results = $scanner->get_missing_meta_items($current_post_type, $paged, $per_page, $current_missing_filter);
        $missing_items = $results['items'];
        $total_items = $results['total'];
        $total_pages = ceil($total_items / $per_page);

        $post_types = get_post_types(array('public' => true), 'objects');
        unset($post_types['attachment']);
        ?>
        <div class="wrap the-aiseo-wrap">
            <h1>The AISEO - Missing Meta Scanner</h1>
            <p>List of pages, posts, and custom posts missing meta titles or descriptions.</p>

            <div class="aiseo-filter-container">
                <form method="get" action="" class="aiseo-filter-row">
                    <input type="hidden" name="page" value="the-aiseo">
                    <div class="aiseo-filter-item">
                        <label for="post_type_filter">Filter by Post Type:</label>
                        <select name="post_type_filter" id="post_type_filter">
                            <option value="">All Post Types</option>
                            <?php foreach ($post_types as $pt): ?>
                                <option value="<?php echo esc_attr($pt->name); ?>" <?php selected($current_post_type, $pt->name); ?>>
                                    <?php echo esc_html($pt->label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="aiseo-filter-item">
                        <label for="missing_filter">Missing Field:</label>
                        <select name="missing_filter" id="missing_filter">
                            <option value="all" <?php selected($current_missing_filter, 'all'); ?>>Title or Description</option>
                            <option value="title" <?php selected($current_missing_filter, 'title'); ?>>Title Only</option>
                            <option value="description" <?php selected($current_missing_filter, 'description'); ?>>Description Only</option>
                        </select>
                    </div>

                    <div class="aiseo-filter-item">
                        <button type="submit" class="button button-primary">Filter</button>
                        <?php if (!empty($current_post_type) || $current_missing_filter !== 'all'): ?>
                            <a href="?page=the-aiseo" class="button">Clear</a>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($current_post_type)): ?>
                        <?php
                        $total_missing_titles = $scanner->get_missing_meta_count($current_post_type, 'title');
                        $total_missing_descs = $scanner->get_missing_meta_count($current_post_type, 'description');
                        ?>
                        <div class="aiseo-bulk-actions">
                            <button type="button" class="button aiseo-bulk-btn" data-type="title"
                                data-post-type="<?php echo esc_attr($current_post_type); ?>"
                                data-total="<?php echo esc_attr($total_missing_titles); ?>">Bulk Update Titles</button>
                            <button type="button" class="button aiseo-bulk-btn" data-type="description"
                                data-post-type="<?php echo esc_attr($current_post_type); ?>"
                                data-total="<?php echo esc_attr($total_missing_descs); ?>">Bulk Update Descriptions</button>
                        </div>
                    <?php endif; ?>
                </form>
                <div id="aiseo-bulk-progress" style="display:none;">
                    <div class="aiseo-progress-bar">
                        <div class="aiseo-progress-inner"></div>
                    </div>
                    <span class="aiseo-progress-text">Processing...</span>
                </div>
            </div>

            <?php if (empty($missing_items)): ?>
                <div class="notice notice-success">
                    <p>Excellent! All your public content has meta titles and descriptions.</p>
                </div>
            <?php else: ?>
                <div class="tablenav top" style="display:none;">
                    <div class="aiseo-pagination-info">
                        <?php echo esc_html($total_items); ?> items
                    </div>
                    <div class="tablenav-pages">
                        <?php
                        echo paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => __('PREV'),
                            'next_text' => __('NEXT'),
                            'total' => $total_pages,
                            'current' => $paged,
                        ));
                        ?>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th class="column-title">Title</th>
                            <th class="column-type">Type</th>
                            <th class="column-meta-title">Meta Title</th>
                            <th class="column-meta-desc">Meta Description</th>
                            <th class="column-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($missing_items as $item): ?>
                            <tr id="aiseo-item-<?php echo esc_attr($item['ID']); ?>">
                                <td>
                                    <strong>
                                        <?php echo esc_html($item['title']); ?>
                                    </strong>
                                    <div class="row-actions">
                                        <span class="edit"><a href="<?php echo esc_url(get_edit_post_link($item['ID'])); ?>">Edit
                                                Post</a></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="aiseo-type-badge aiseo-type-<?php echo esc_attr($item['post_type']); ?>">
                                        <?php echo esc_html($item['post_type_label']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo !empty($item['meta_title']) ? esc_html($item['meta_title']) : '-'; ?>
                                </td>
                                <td>
                                    <?php echo !empty($item['meta_desc']) ? esc_html($item['meta_desc']) : '-'; ?>
                                </td>
                                <td>
                                    <button class="button button-primary aiseo-generate" data-id="<?php echo esc_attr($item['ID']); ?>">
                                        Generate with AI
                                    </button>
                                    <span class="spinner"></span>
                                </td>
                            </tr>
                            <tr class="aiseo-suggestion-row" style="display:none;"
                                id="aiseo-suggestion-<?php echo esc_attr($item['ID']); ?>">
                                <td colspan="5">
                                    <div class="aiseo-suggestion-container">
                                        <div class="aiseo-field">
                                            <label>Suggested Title:</label>
                                            <div class="aiseo-input-group">
                                                <input type="text" class="widefat aiseo-suggested-title" value="">
                                                <button class="button aiseo-save-single" data-type="title"
                                                    data-id="<?php echo esc_attr($item['ID']); ?>" title="Save Title">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="aiseo-icon-save">
                                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z">
                                                        </path>
                                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                        <polyline points="7 3 7 8 15 8"></polyline>
                                                    </svg>
                                                </button>
                                                <button class="button aiseo-regenerate-single" data-type="title"
                                                    data-id="<?php echo esc_attr($item['ID']); ?>" title="Regenerate"><span
                                                        class="dashicons dashicons-update"></span></button>
                                            </div>
                                        </div>
                                        <div class="aiseo-field">
                                            <label>Suggested Description:</label>
                                            <div class="aiseo-input-group">
                                                <textarea class="widefat aiseo-suggested-desc" rows="3"></textarea>
                                                <button class="button aiseo-save-single" data-type="description"
                                                    data-id="<?php echo esc_attr($item['ID']); ?>" title="Save Description">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="aiseo-icon-save">
                                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z">
                                                        </path>
                                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                        <polyline points="7 3 7 8 15 8"></polyline>
                                                    </svg>
                                                </button>
                                                <button class="button aiseo-regenerate-single" data-type="description"
                                                    data-id="<?php echo esc_attr($item['ID']); ?>" title="Regenerate"><span
                                                        class="dashicons dashicons-update"></span></button>
                                            </div>
                                        </div>
                                        <div class="aiseo-actions">
                                            <button class="button button-primary aiseo-save"
                                                data-id="<?php echo esc_attr($item['ID']); ?>">Apply & Save</button>
                                            <button class="button aiseo-regenerate"
                                                data-id="<?php echo esc_attr($item['ID']); ?>">Regenerate</button>
                                            <button class="button aiseo-cancel"
                                                data-id="<?php echo esc_attr($item['ID']); ?>">Cancel</button>
                                            <span class="spinner aiseo-regenerate-spinner"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="tablenav bottom">
                    <div class="aiseo-pagination-info">
                        <?php echo esc_html($total_items); ?> items
                    </div>
                    <div class="tablenav-pages">
                        <?php
                        echo paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => __('PREV'),
                            'next_text' => __('NEXT'),
                            'total' => $total_pages,
                            'current' => $paged,
                        ));
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render settings page.
     */
    public function render_settings_page()
    {
        ?>
        <div class="wrap">
            <h1>The AISEO - Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('the_aiseo_settings');
                do_settings_sections('the_aiseo_settings');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">OpenRouter API Key</th>
                        <td>
                            <input type="password" name="the_aiseo_openrouter_api_key"
                                value="<?php echo esc_attr(get_option('the_aiseo_openrouter_api_key')); ?>"
                                class="regular-text" />
                            <p class="description">Get your API key at <a href="https://openrouter.ai/"
                                    target="_blank">openrouter.ai</a></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">AI Model</th>
                        <td>
                            <input type="text" name="the_aiseo_model"
                                value="<?php echo esc_attr(get_option('the_aiseo_model', 'openai/gpt-3.5-turbo')); ?>"
                                class="regular-text" />
                            <p class="description">Example: <code>openai/gpt-3.5-turbo</code> or
                                <code>anthropic/claude-instant-v1</code>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Custom AI Prompt</th>
                        <td>
                            <?php
                            $default_prompt = "You are an Elite SEO Copywriter. Generate a JSON object {'title': '...', 'description': '...'} for this content:\n\nTitle: {post_title}\nContent: {content}\n\nExecutive Constraints:\n1. Title: 50-60 chars, front-load primary keyword, no exclamation marks.\n2. Description: 140-155 chars. No title repetition.\n3. Zero-Tolerance List: No 'Learn', 'Discover', 'Explore', 'Unlock', 'Unleash', or 'This article'.\n4. No Superlatives: Ban 'ultimate', 'best', 'comprehensive', 'amazing'. Use hard facts instead.\n5. Entity Integration: Include at least one secondary semantic entity/keyword from the text.\n6. The Hook: Start with a punchy, 3-5 word statement or a specific data point/stat.\n7. Rhythm: Use a short first sentence followed by a descriptive second sentence.\n8. Active Voice: Use high-impact verbs. No passive 'is discussed' or 'are featured' phrasing.\n9. Benefit-First: Focus on the reader's outcome, not the content's features.\n10. Specificity: Include a proper noun, date, or number found in the content to ground the summary.\n11. Intent Match: Mirror the user's search intent (Informational vs. Transactional).\n12. Scannability: Use a pipe (|) or dash (—) to separate distinct thoughts if it improves clarity.\n13. No Self-Reference: Never mention 'this page' or 'below'.\n14. Audience Mirroring: Match the technical level of the vocabulary to the source content.\n15. Clean JSON: Return ONLY the raw JSON object. No markdown, no 'Here is your JSON'.";
                            $current_prompt = get_option('the_aiseo_prompt', $default_prompt);
                            if (empty(trim($current_prompt))) {
                                $current_prompt = $default_prompt; // Fallback if deliberately emptied
                            }
                            ?>
                            <textarea name="the_aiseo_prompt" id="the_aiseo_prompt" rows="12" class="large-text code"><?php echo esc_textarea($current_prompt); ?></textarea>
                            <p class="description">Modify the instructions sent to the AI. You MUST include instructions to return a JSON object with 'title' and 'description' keys.<br><strong>Available Dynamic Tags:</strong> <code>{post_title}</code>, <code>{content}</code>.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render logs page.
     */
    public function render_logs_page()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aiseo_logs';

        // Handle Clear Logs
        if (isset($_POST['the_aiseo_empty_logs'])) {
            check_admin_referer('the_aiseo_empty_logs');
            $wpdb->query("TRUNCATE TABLE $table_name");
            echo '<div class="notice notice-success is-dismissible"><p>Logs cleared successfully.</p></div>';
        }

        // Filters
        $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = 20;
        $offset = ($paged - 1) * $per_page;

        $post_type_filter = isset($_GET['post_type_filter']) ? sanitize_text_field($_GET['post_type_filter']) : '';
        $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
        $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

        $where = array('1=1');
        if (!empty($post_type_filter)) {
            $where[] = $wpdb->prepare("post_type = %s", $post_type_filter);
        }
        if (!empty($date_from)) {
            $where[] = $wpdb->prepare("created_at >= %s", $date_from . ' 00:00:00');
        }
        if (!empty($date_to)) {
            $where[] = $wpdb->prepare("created_at <= %s", $date_to . ' 23:59:59');
        }
        if (!empty($search)) {
            $where[] = $wpdb->prepare("post_title LIKE %s", '%' . $wpdb->esc_like($search) . '%');
        }

        $where_sql = implode(' AND ', $where);

        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $where_sql");
        $total_pages = ceil($total_items / $per_page);

        $logs = $wpdb->get_results("
            SELECT * FROM $table_name 
            WHERE $where_sql 
            ORDER BY created_at DESC 
            LIMIT $offset, $per_page
        ");

        $post_types = get_post_types(array('public' => true), 'objects');
        unset($post_types['attachment']);
        ?>
        <div class="wrap the-aiseo-wrap">
            <h1>The AISEO - Update Logs</h1>
            <p>Below are the most recent meta updates made by the plugin.</p>
            <style>.aiseo-filter-item { display: inline-block; } .aiseo-filter-container { margin-bottom: 15px; } .aiseo-filter-item label { display: block; margin-bottom: 5px; font-weight: 600; }</style>
            <div class="aiseo-filter-container">
                <form method="get" action="" class="aiseo-filter-row">
                    <input type="hidden" name="page" value="the-aiseo-logs">
                    
                    <div class="aiseo-filter-item">
                        <label for="post_type_filter">Post Type:</label>
                        <select name="post_type_filter" id="post_type_filter">
                            <option value="">All Types</option>
                            <?php foreach ($post_types as $pt): ?>
                                <option value="<?php echo esc_attr($pt->name); ?>" <?php selected($post_type_filter, $pt->name); ?>>
                                    <?php echo esc_html($pt->label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="aiseo-filter-item">
                        <label for="date_from">From:</label>
                        <input type="date" name="date_from" id="date_from" value="<?php echo esc_attr($date_from); ?>">
                    </div>

                    <div class="aiseo-filter-item">
                        <label for="date_to">To:</label>
                        <input type="date" name="date_to" id="date_to" value="<?php echo esc_attr($date_to); ?>">
                    </div>

                    <div class="aiseo-filter-item">
                        <label for="s">Search:</label>
                        <input type="text" name="s" id="s" value="<?php echo esc_attr($search); ?>" placeholder="Post title...">
                    </div>

                    <div class="aiseo-filter-item">
                        <div style="display:flex; gap:10px;">
                            <button type="submit" class="button button-primary">Filter</button>
                            <a href="?page=the-aiseo-logs" class="button">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 150px;">Date</th>
                        <th style="width: 80px;">Post ID</th>
                        <th>Post Title</th>
                        <th style="width: 120px;">Post Type</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($logs): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?php echo esc_html($log->created_at); ?></td>
                                <td><?php echo esc_html($log->post_id); ?></td>
                                <td><strong><?php echo esc_html($log->post_title); ?></strong></td>
                                <td>
                                    <span class="aiseo-type-badge aiseo-type-<?php echo esc_attr($log->post_type); ?>">
                                        <?php 
                                        $pt_obj = get_post_type_object($log->post_type);
                                        echo esc_html($pt_obj ? $pt_obj->labels->singular_name : $log->post_type); 
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo $log->meta_title ? esc_html($log->meta_title) : '<span class="description">-</span>'; ?></td>
                                <td><?php echo $log->meta_desc ? esc_html($log->meta_desc) : '<span class="description">-</span>'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No logs found matching your criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="tablenav bottom">
                <div class="aiseo-pagination-info">
                    <?php echo esc_html($total_items); ?> entries
                </div>
                <div class="tablenav-pages">
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => __('PREV'),
                        'next_text' => __('NEXT'),
                        'total' => $total_pages,
                        'current' => $paged,
                    ));
                    ?>
                </div>
                
                <form method="post" style="display:inline-block; margin-top: 20px;">
                    <?php wp_nonce_field('the_aiseo_empty_logs'); ?>
                    <input type="submit" name="the_aiseo_empty_logs" class="button button-link-delete" value="Clear All Logs"
                        onclick="return confirm('Are you sure you want to permanently clear all logs from the database?');">
                </form>
            </div>
        </div>
        <?php
    }
}
