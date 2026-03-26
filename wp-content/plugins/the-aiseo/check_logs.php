<?php
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

global $wpdb;
$logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}aiseo_logs ORDER BY id DESC LIMIT 10");
echo json_encode($logs, JSON_PRETTY_PRINT);
