<?php
/**
 * AISEO_Scanner class.
 */

if (!defined('ABSPATH')) {
    exit;
}

class AISEO_Scanner
{

    /**
     * Get all missing meta items with pagination.
     *
     * @param string $filter_post_type Optional post type to filter by.
     * @param int    $paged            Current page number.
     * @param int    $per_page         Items per page.
     * @param string $missing_filter   'all', 'title', 'description'.
     * @return array Array containing 'items' and 'total'.
     */
    public function get_missing_meta_items($filter_post_type = '', $paged = 1, $per_page = 20, $missing_filter = 'all')
    {
        global $wpdb;

        if (!empty($filter_post_type)) {
            $post_types = array($filter_post_type);
        } else {
            $post_types = get_post_types(array('public' => true), 'names');
            unset($post_types['attachment']);
        }

        $post_types_placeholder = implode("','", array_map('esc_sql', $post_types));
        $offset = ($paged - 1) * $per_page;

        // Determine the missingness condition.
        $condition = "(pm_title.meta_value IS NULL OR pm_title.meta_value = '' OR pm_desc.meta_value IS NULL OR pm_desc.meta_value = '')";
        if ($missing_filter === 'title') {
            $condition = "(pm_title.meta_value IS NULL OR pm_title.meta_value = '')";
        } elseif ($missing_filter === 'description') {
            $condition = "(pm_desc.meta_value IS NULL OR pm_desc.meta_value = '')";
        }

        // Base SQL query to find posts missing Meta Title OR Meta Description.
        $query_base = "
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_title ON (p.ID = pm_title.post_id AND pm_title.meta_key = '_yoast_wpseo_title')
            LEFT JOIN {$wpdb->postmeta} pm_desc ON (p.ID = pm_desc.post_id AND pm_desc.meta_key = '_yoast_wpseo_metadesc')
            WHERE p.post_status = 'publish' 
            AND p.post_type IN ('{$post_types_placeholder}')
            AND {$condition}
        ";

        // Get total count for pagination.
        $total_items = $wpdb->get_var("SELECT COUNT(DISTINCT p.ID) {$query_base}");

        // Get paged items.
        $results = $wpdb->get_results("
            SELECT DISTINCT p.ID, p.post_title, p.post_type, pm_title.meta_value as meta_title, pm_desc.meta_value as meta_desc
            {$query_base}
            ORDER BY p.post_date DESC
            LIMIT {$offset}, {$per_page}
        ");

        $items = array();
        foreach ($results as $row) {
            $yoast_title = $row->meta_title;
            $yoast_desc = $row->meta_desc;

            // Title is considered "present" if not empty, OR if we assume defaults are fine.
            // Description is strictly checked since it's the primary target now.
            $has_title = !empty($yoast_title);
            $has_desc = !empty($yoast_desc);

            $post_type_obj = get_post_type_object($row->post_type);
            $post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : $row->post_type;

            $items[] = array(
                'ID' => $row->ID,
                'title' => $row->post_title,
                'post_type' => $row->post_type,
                'post_type_label' => $post_type_label,
                'has_title' => $has_title,
                'has_desc' => $has_desc,
                'meta_title' => $yoast_title,
                'meta_desc' => $yoast_desc,
            );
        }

        return array(
            'items' => $items,
            'total' => (int) $total_items,
        );
    }

    /**
     * Get items missing a specific meta field for bulk processing.
     *
     * @param string $post_type  The post type to scan.
     * @param string $field_type 'title' or 'description'.
     * @param int    $limit      Max items to return.
     * @return array List of post IDs and titles.
     */
    public function get_items_missing_meta($post_type, $field_type, $limit = 10)
    {
        global $wpdb;

        $meta_key = ($field_type === 'title') ? '_yoast_wpseo_title' : '_yoast_wpseo_metadesc';

        return $wpdb->get_results($wpdb->prepare("
            SELECT DISTINCT p.ID, p.post_title
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON (p.ID = pm.post_id AND pm.meta_key = %s)
            WHERE p.post_type = %s
            AND p.post_status = 'publish'
            AND (pm.meta_value IS NULL OR pm.meta_value = '')
            LIMIT %d
        ", $meta_key, $post_type, $limit));
    }

    /**
     * Get the count of items missing a specific meta field.
     *
     * @param string $post_type  The post type to scan.
     * @param string $field_type 'title' or 'description'.
     * @return int Total count.
     */
    public function get_missing_meta_count($post_type, $field_type)
    {
        global $wpdb;

        $meta_key = ($field_type === 'title') ? '_yoast_wpseo_title' : '_yoast_wpseo_metadesc';

        return (int) $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT p.ID)
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON (p.ID = pm.post_id AND pm.meta_key = %s)
            WHERE p.post_type = %s
            AND p.post_status = 'publish'
            AND (pm.meta_value IS NULL OR pm.meta_value = '')
        ", $meta_key, $post_type));
    }
}
