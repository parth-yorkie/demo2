<?php
/**
 * Elementor Editor
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes;

use WP_Grid_Builder_Elementor\Includes\Widgets\Grid;
use WP_Grid_Builder_Elementor\Includes\Widgets\facet;
use Elementor\Plugin as Elementor;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editor
 *
 * @class WP_Grid_Builder_Elementor\Includes\Editor
 * @since 1.0.0
 */
final class Editor {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '<' ) ) {
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
		} else {
			add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		}

		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
		add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'dequeue_assets' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_assets' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'preview_assets' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'localize_script' ] );
		add_filter( 'wp_grid_builder/frontend/enqueue_scripts', [ $this, 'unset_assets' ] );
		add_filter( 'wp_grid_builder/frontend/enqueue_styles', [ $this, 'unset_assets' ] );
		add_filter( 'wp_grid_builder_map/has_facet', [ $this, 'has_facet' ] );

	}

	/**
	 * Register Elementor widget category
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param ElementorElements_Manager $manager Elements manager.
	 */
	public function register_category( $manager ) {

		$manager->add_category(
			'wp-grid-builder',
			[
				'title' => 'WP Grid Builder',
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 * Register Elementor widgets
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widgets_Manager $widgets_manager The widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {

		if ( 'elementor/widgets/widgets_registered' === current_filter() ) {

			Elementor::instance()->widgets_manager->register_widget_type( new Grid() );
			Elementor::instance()->widgets_manager->register_widget_type( new Facet() );

		} else {

			$widgets_manager->register( new Grid() );
			$widgets_manager->register( new Facet() );

		}
	}

	/**
	 * Dequeue WP Grid Builder assets in editor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function dequeue_assets() {

		foreach ( wpgb_get_styles() as $args ) {
			! empty( $args['handle'] ) && wp_dequeue_style( $args['handle'] );
		}

		foreach ( wpgb_get_scripts() as $args ) {
			! empty( $args['handle'] ) && wp_dequeue_script( $args['handle'] );
		}
	}

	/**
	 * Unset WP Grid Builder assets in preview mode tp prevent conflict with grid shortcodes
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $assets Holds assets to enqueue.
	 * @return array
	 */
	public function unset_assets( $assets ) {

		if ( Elementor::instance()->preview->is_preview_mode() ) {
			$assets = [];
		}

		return $assets;

	}

	/**
	 * Enqueue editor assets
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_assets() {

		wp_enqueue_style(
			'wpgb-elementor-editor',
			WPGB_ELEMENTOR_URL . 'assets/css/editor.css',
			[],
			WPGB_ELEMENTOR_VERSION
		);
	}

	/**
	 * Enqueue preview assets
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function preview_assets() {

		if ( ! Elementor::instance()->preview->is_preview_mode() ) {
			return;
		}

		wp_enqueue_style(
			'wpgb-elementor',
			WPGB_ELEMENTOR_URL . 'assets/css/preview.css',
			[],
			WPGB_ELEMENTOR_VERSION
		);

		wp_enqueue_script(
			'wpgb-elementor',
			WPGB_ELEMENTOR_URL . 'assets/js/preview.js',
			[],
			WPGB_ELEMENTOR_VERSION,
			true
		);
	}

	/**
	 * Localize editor script
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function localize_script() {

		if ( ! Elementor::instance()->preview->is_preview_mode() ) {
			return;
		}

		$post_types = get_post_types( [ 'public' => true ] );
		unset( $post_types['attachment'] );

		$data = array_merge(
			apply_filters( 'wp_grid_builder/frontend/localize_script', [] ),
			[
				'adminUrl'       => admin_url( 'admin.php' ),
				'frontStyles'    => wpgb_get_styles(),
				'frontScripts'   => wpgb_get_scripts(),
				'renderBlocks'   => (bool) wpgb_get_option( 'render_blocks' ),
				'templates'      => array_keys( apply_filters( 'wp_grid_builder/templates', [] ) ),
				'providers'      => array_keys( apply_filters( 'wp_grid_builder_elementor/providers', [] ) ),
				'facetTypes'     => $this->facet_types(),
				'shadowGrids'    => [],
				'history'        => false,
				'hasGrids'       => true,
				'hasFacets'      => true,
				'hasLightbox'    => true,
				'loadingContent' => esc_html__( 'Please wait, loading content...', 'wpgb-elementor' ),
				'noFacetContent' => esc_html__( 'No content found in facet.', 'wpgb-elementor' ),
				'templatesGroup' => esc_html__( 'Templates', 'wpgb-elementor' ),
				'gridsGroup'     => esc_html__( 'Grids', 'wpgb-elementor' ),
				'mainQuery'      => [
					'post_type'   => $post_types,
					'post_status' => 'publish',
				],
			]
		);

		wp_localize_script( 'wpgb-elementor', 'wpgb_settings', $data );

	}

	/**
	 * Get facet types
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function facet_types() {

		global $wpdb;

		$facets = $wpdb->get_results( "SELECT id, type FROM {$wpdb->prefix}wpgb_facets" );

		return array_column( $facets, 'type', 'id' );

	}

	/**
	 * Localize map facet assets in editor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $has_facet Whether there is a map facet or not.
	 * @return boolean
	 */
	public function has_facet( $has_facet ) {

		if ( Elementor::instance()->preview->is_preview_mode() ) {
			$has_facet = true;
		}

		return $has_facet;

	}
}
