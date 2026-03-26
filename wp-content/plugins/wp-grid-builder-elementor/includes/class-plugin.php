<?php
/**
 * Plugin
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Instance of the plugin
 *
 * @class WP_Grid_Builder_Elementor\Includes\Plugin
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'elementor/init', [ $this, 'init' ] );
		add_filter( 'wp_grid_builder/register', [ $this, 'register' ] );
		add_filter( 'wp_grid_builder/plugin_info', [ $this, 'plugin_info' ], 10, 2 );
		add_filter( 'wp_grid_builder/templates', [ $this, 'preview_template' ] );

	}

	/**
	 * Init instances
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		$providers = new Providers();

		new Editor();
		new Extend();
		new Render( $providers );
		new Filter( $providers );

	}

	/**
	 * Register add-on
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $addons Holds registered add-ons.
	 * @return array
	 */
	public function register( $addons ) {

		$addons[] = [
			'name'    => 'Elementor',
			'slug'    => WPGB_ELEMENTOR_BASE,
			'option'  => 'wpgb_elementor',
			'version' => WPGB_ELEMENTOR_VERSION,
		];

		return $addons;

	}

	/**
	 * Set plugin info
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $info Holds plugin info.
	 * @param string $name Current plugin name.
	 * @return array
	 */
	public function plugin_info( $info, $name ) {

		if ( 'Elementor' !== $name ) {
			return $info;
		}

		$info['icons'] = [
			'1x' => WPGB_ELEMENTOR_URL . 'assets/imgs/icon.png',
			'2x' => WPGB_ELEMENTOR_URL . 'assets/imgs/icon.png',
		];

		if ( ! empty( $info['info'] ) ) {

			$info['info']->banners = [
				'low'  => WPGB_ELEMENTOR_URL . 'assets/imgs/banner.png',
				'high' => WPGB_ELEMENTOR_URL . 'assets/imgs/banner.png',
			];
		}

		return $info;

	}

	/**
	 * Elementor shadow template for standalone facets in preview mode (popup)
	 *
	 * @since 1.0.0
	 *
	 * @param array $templates Holds registered templates.
	 * @return array
	 */
	public function preview_template( $templates ) {

		if ( wpgb_doing_ajax() ) {

			$post_types = get_post_types( [ 'public' => true ] );
			unset( $post_types['attachment'] );

			$templates['_elementor_preview_template'] = [
				'query_args'         => [
					'post_type'      => $post_types,
					'posts_per_page' => 1,
				],
				'render_callback'    => function() {},
				'noresults_callback' => function() {},
			];
		}

		return $templates;

	}
}
