<?php
/**
 * Portfolio widget
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes\Providers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portfolio widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Portfolio
 * @since 1.0.0
 */
final class Portfolio extends Base {

	/**
	 * Get widget options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_options() {

		return [
			'itemSelector' => '.elementor-posts-container > *',
			'isMainQuery'  => $this->is_current_query(),
		];
	}

	/**
	 * Get inline script
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_script() {

		return 'wpgb.facets.on(\'appended\',function(){el&&el.elementsHandler.runReadyTrigger(template)});';

	}

	/**
	 * Apply filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {

		add_filter( 'elementor/query/get_query_args/current_query', [ $this, 'query_args' ] );
		add_filter( 'elementor/query/query_args', [ $this, 'query_args' ] );
		add_action( 'elementor/query/query_results', [ $this, 'no_results' ], 10, 2 );

	}

	/**
	 * Remove filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		remove_filter( 'elementor/query/get_query_args/current_query', [ $this, 'query_args' ] );
		remove_filter( 'elementor/query/query_args', [ $this, 'query_args' ] );
		remove_action( 'elementor/query/query_results', [ $this, 'no_results' ] );

	}

	/**
	 * Add custom properties in query args to allows filtering
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args Holds query arguments.
	 * @return array
	 */
	public function query_args( $args ) {

		if ( ! wpgb_doing_ajax() && $this->is_current_query() ) {
			return $args;
		}

		// If main query and not in edit mode.
		if (
			$this->is_current_query() &&
			empty( $this->settings['widgets'] ) &&
			! empty( $this->settings['main_query'] )
		) {
			$args = $this->settings['main_query'];
		}

		$post__not_in  = $this->get_post__not_in( $args );

		if ( ! empty( $this->settings['lang'] ) ) {
			$args['lang'] = $this->settings['lang'];
		}

		if ( ! empty( $post__not_in ) ) {
			$args['post__not_in'] = $post__not_in;

		}

		$args['wp_grid_builder'] = $this->get_id();

		return $args;

	}

	/**
	 * Display no results message if no content found
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array       $query  Holds query arguments.
	 * @param Widget_Base $widget Elementor widget instance.
	 */
	public function no_results( $query, $widget ) {

		if ( (int) $query->found_posts > 0 ) {
			return;
		}

		echo '<div class="elementor-posts-container elementor-posts">' . wp_kses_post( $widget->get_settings( 'wpgb_noresults' ) ) . '</div>';

	}
}
