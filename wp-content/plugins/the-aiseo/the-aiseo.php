<?php
/**
 * Plugin Name: The AISEO
 * Description: Identify missing meta titles/descriptions and generate them using OpenRouter AI.
 * Version: 1.0.0
 * Author: York IE
 */

if (!defined('ABSPATH')) {
    exit;
}

define('THE_AISEO_PATH', plugin_dir_path(__FILE__));
define('THE_AISEO_URL', plugin_dir_url(__FILE__));
define('THE_AISEO_LOG_PATH', wp_upload_dir()['basedir'] . '/the-aiseo-updates.log');

require_once THE_AISEO_PATH . 'inc/class-aiseo-admin.php';
require_once THE_AISEO_PATH . 'inc/class-aiseo-scanner.php';
require_once THE_AISEO_PATH . 'inc/class-aiseo-ai.php';

// Initialize the plugin.
function the_aiseo_init()
{
    new AISEO_Admin();
    new AISEO_AI();
}
add_action('plugins_loaded', 'the_aiseo_init');
