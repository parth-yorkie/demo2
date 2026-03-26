<?php
/**
 * Override filter requests of WP Grid Builder
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
 * Filter
 *
 * @class WP_Grid_Builder_Elementor\Includes\Filter
 * @since 1.0.0
 */
final class Filter {

	/**
	 * Provider instance
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var WP_Grid_Builder_Elementor\Includes\Providers
	 */
	protected $providers;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Providers $providers Providers instance.
	 */
	public function __construct( $providers ) {

		$this->providers = $providers;

		// We hook after caching add-on to allow caching.
		add_action( 'wp_grid_builder/async/render', [ $this, 'maybe_handle' ], 100 );
		add_action( 'wp_grid_builder/async/refresh', [ $this, 'maybe_handle' ], 100 );
		add_action( 'wp_grid_builder/async/search', [ $this, 'maybe_handle' ], 100 );

	}

	/**
	 * Check if it is an Elementor widget
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $atts Template attributes.
	 */
	public function maybe_handle( $atts ) {

		if ( ! isset( $atts['is_template'] ) || 'Elementor' !== $atts['is_template'] ) {
			return;
		}

		$widget = ( new Widget( $atts ) )->get_instance();

		if ( ! $widget ) {
			return;
		}

		$provider = $this->providers->get( $widget, $atts['post_id'], $atts );

		if ( ! $provider ) {
			return;
		}

		$action = explode( '/', current_filter() );

		$this->{end( $action )}( $provider, $atts );

	}

	/**
	 * Render facets on first load
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param Provider $provider Widget provider instance.
	 * @param array    $atts     Template attributes.
	 */
	protected function render( $provider, $atts ) {

		$provider->refresh();

		wp_send_json(
			apply_filters(
				'wp_grid_builder/async/render_response',
				[
					'facets' => wpgb_refresh_facets( $atts ),
				],
				$atts
			)
		);
	}

	/**
	 * Refresh facets and content
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param Provider $provider Widget provider instance.
	 * @param array    $atts     Template attributes.
	 */
	protected function refresh( $provider, $atts ) {

		wp_send_json(
			apply_filters(
				'wp_grid_builder/async/refresh_response',
				[
					'posts'  => $provider->refresh(),
					'facets' => wpgb_refresh_facets( $atts ),
				],
				$atts
			)
		);
	}

	/**
	 * Search for facet choices
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param Provider $provider Widget provider instance.
	 * @param array    $atts     Template attributes.
	 */
	protected function search( $provider, $atts ) {

		$provider->refresh();

		wp_send_json(
			apply_filters(
				'wp_grid_builder/async/search_response',
				wpgb_search_facet_choices( $atts ),
				$atts
			)
		);
	}
}
