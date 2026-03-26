<?php
/**
 * Posts widget
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
 * Posts widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Posts
 * @since 1.0.0
 */
final class Posts extends Base {

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

		$script  = 'var p=1;template.addEventListener(\'click\',function(e){';
		$script .= 'var t=e.target;p=(t.href||\'\').match(/\/(\d+)/);';
		$script .= '(t.classList.contains(\'page-numbers\')&&t.tagName===\'A\')&&(e.preventDefault()||wpgb.facets.refresh())});';
		$script .= 'wpgb.facets.on(\'fetch\',function(d){d.paged=(p&&p[1]||1);p=1});';
		$script .= 'wpgb.facets.on(\'appended\',function(){el&&el.elementsHandler.runReadyTrigger(template)});';

		return $script;

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
		add_filter( 'get_pagenum_link', [ $this, 'paginate_links' ] );

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
		remove_filter( 'get_pagenum_link', [ $this, 'paginate_links' ] );

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

		$args['paged'] = 1;
		$post__not_in  = $this->get_post__not_in( $args );

		if ( ! empty( $this->settings['lang'] ) ) {
			$args['lang'] = $this->settings['lang'];
		}

		if ( ! empty( $this->settings['paged'] ) ) {
			$args['paged'] = (int) $this->settings['paged'];
		}

		if ( ! empty( $post__not_in ) ) {
			$args['post__not_in'] = $post__not_in;
		}

		$args['wp_grid_builder'] = $this->get_id();
		// To override widget pagination.
		set_query_var( 'paged', $args['paged'] );

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

	/**
	 * Replace paginated link base with current permalink.
	 *
	 * @since 1.0.0
	 *
	 * @param string $link The paginated link URL.
	 * @return string
	 */
	public function replace_paginated_link( $link ) {

		if ( wpgb_doing_ajax() && ! empty( $this->settings['permalink'] ) ) {

			$link = str_replace(
				trailingslashit( home_url() ),
				trailingslashit( $this->settings['permalink'] ),
				$link
			);
		}

		return $link;

	}

	/**
	 * Filters the paginated links from Elementor pagination.
	 *
	 * @since 1.0.0
	 *
	 * @param string $link The paginated link URL.
	 * @return string
	 */
	public function paginate_links( $link ) {

		$link = $this->replace_paginated_link( $link );
		$keys = preg_filter( '/^/', '_', array_keys( wpgb_get_query_string() ) );
		$keys = array_merge( [ 'wpgb-ajax', 'action' ], $keys );

		// Remove query string from facet parameters.
		return remove_query_arg( $keys, $link );

	}
}
