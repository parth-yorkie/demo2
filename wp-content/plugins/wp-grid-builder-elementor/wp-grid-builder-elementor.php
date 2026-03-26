<?php
/**
 * WP Grid Builder Elementor Add-on
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @link      https://www.wpgridbuilder.com
 * @copyright 2019-2023 Loïc Blascos
 *
 * @wordpress-plugin
 * Plugin Name:  WP Grid Builder - Elementor
 * Plugin URI:   https://www.wpgridbuilder.com
 * Description:  Integrate WP Grid Builder with Elementor plugin.
 * Version:      1.2.6
 * Author:       Loïc Blascos
 * Author URI:   https://www.wpgridbuilder.com
 * License:      GPL-3.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:  wpgb-elementor
 * Domain Path:  /languages
 */

namespace WP_Grid_Builder_Elementor;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPGB_ELEMENTOR_VERSION', '1.2.6' );
define( 'WPGB_ELEMENTOR_FILE', __FILE__ );
define( 'WPGB_ELEMENTOR_BASE', plugin_basename( WPGB_ELEMENTOR_FILE ) );
define( 'WPGB_ELEMENTOR_PATH', plugin_dir_path( WPGB_ELEMENTOR_FILE ) );
define( 'WPGB_ELEMENTOR_URL', plugin_dir_url( WPGB_ELEMENTOR_FILE ) );

require_once WPGB_ELEMENTOR_PATH . 'includes/class-autoload.php';

/**
 * Load plugin text domain.
 *
 * @since 1.0.0
 */
function textdomain() {

	load_plugin_textdomain(
		'wpgb-elementor',
		false,
		basename( dirname( WPGB_ELEMENTOR_FILE ) ) . '/languages'
	);

}
add_action( 'plugins_loaded', __NAMESPACE__ . '\textdomain' );

/**
 * Plugin compatibility notice.
 *
 * @since 1.0.0
 */
function plugin_notice() {

	$notice = __( '<strong>Gridbuilder ᵂᴾ - Elementor</strong> add-on requires at least <code>Gridbuilder ᵂᴾ v1.4.2</code>. Please update Gridbuilder ᵂᴾ to use Elementor add-on.', 'wpgb-elementor' );

	echo '<div class="error">' . wp_kses_post( wpautop( $notice ) ) . '</div>';

}

/**
 * Elementor compatibility notice.
 *
 * @since 1.0.0
 */
function elementor_notice() {

	$notice = __( '<strong>Gridbuilder ᵂᴾ - Elementor</strong> add-on requires at least <code>Elementor v3.0.0</code>. Please update and activate Elementor.', 'wpgb-elementor' );

	echo '<div class="error">' . wp_kses_post( wpautop( $notice ) ) . '</div>';

}

/**
 * Initialize plugin
 *
 * @since 1.0.0
 */
function loaded() {

	if ( version_compare( WPGB_VERSION, '1.4.2', '<' ) ) {

		add_action( 'admin_notices', __NAMESPACE__ . '\plugin_notice' );
		return;

	}

	if ( ! defined( 'ELEMENTOR_VERSION' ) || version_compare( ELEMENTOR_VERSION, '3.0.0', '<' ) ) {

		add_action( 'admin_notices', __NAMESPACE__ . '\elementor_notice' );
		return;

	}

	new Includes\Plugin();

}
add_action( 'wp_grid_builder/loaded', __NAMESPACE__ . '\loaded' );
