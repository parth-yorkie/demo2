<?php
/**
 * Archive Posts widget
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
 * Archive_Posts widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Archive_Posts
 * @since 1.0.0
 */
final class Archive_Posts extends Base {

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
			'isMainQuery'  => true,
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

		add_filter( 'elementor/theme/posts_archive/query_posts/query_vars', [ $this, 'query_args' ] );
		add_filter( 'get_pagenum_link', [ $this, 'paginate_links' ] );

	}

	/**
	 * Remove filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		remove_filter( 'elementor/theme/posts_archive/query_posts/query_vars', [ $this, 'query_args' ] );
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

		if ( ! wpgb_doing_ajax() ) {
			return $args;
		}

		// If main query and not in edit mode.
		if ( ! empty( $this->settings['main_query'] ) && empty( $this->settings['widgets'] ) ) {
			$args = $this->settings['main_query'];
		} else {
			$args = $this->default_query();
		}

		if ( ! empty( $this->settings['lang'] ) ) {
			$args['lang'] = $this->settings['lang'];
		}

		$args['paged'] = 1;

		if ( ! empty( $this->settings['paged'] ) ) {
			$args['paged'] = (int) $this->settings['paged'];
		}

		// To override widget pagination.
		set_query_var( 'paged', $args['paged'] );

		$args['wp_grid_builder'] = $this->get_id();

		return $args;

	}

	/**
	 * Get default query from editor.
	 * We query any post type to handle any facet in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function default_query() {

		$post_types = get_post_types( [ 'public' => true ] );
		unset( $post_types['attachment'] );

		return [
			'post_type'      => $post_types,
			'posts_per_page' => get_option( 'posts_per_page' ),
		];
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
