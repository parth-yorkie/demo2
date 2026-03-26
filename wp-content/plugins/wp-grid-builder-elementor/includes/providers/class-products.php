<?php
/**
 * Products widget
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
 * Products widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Products
 * @since 1.0.0
 */
final class Products extends Base {

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
			'itemSelector' => '.products > .product',
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
		$script .= 'var t=e.target;p=(t.href||\'\').match(/product-page=(\d+)/);';
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

		add_filter( 'woocommerce_shortcode_products_query', [ $this, 'query_args' ] );
		add_filter( 'woocommerce_shortcode_products_query_results', [ $this, 'no_results' ] );
		add_filter( 'paginate_links', [ $this, 'paginate_links' ] );
		$this->is_current_query() && $this->wp_query();

	}

	/**
	 * Remove filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		remove_filter( 'woocommerce_shortcode_products_query', [ $this, 'query_args' ] );
		remove_filter( 'woocommerce_shortcode_products_query_results', [ $this, 'no_results' ] );
		remove_filter( 'paginate_links', [ $this, 'paginate_links' ] );

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

		$args['wp_grid_builder'] = $this->get_id();

		if ( ! empty( $this->settings['lang'] ) ) {
			$args['lang'] = $this->settings['lang'];
		}

		if ( ! empty( $this->settings['paged'] ) ) {
			$args['paged'] = (int) $this->settings['paged'];
		}

		return $args;

	}

	/**
	 * Set and run WordPress query
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function wp_query() {

		global $wp_query;

		if (
			! wpgb_doing_ajax() ||
			! empty( $this->settings['widgets'] ) ||
			empty( $this->settings['main_query'] )
		) {
			return;
		}

		$args = $this->settings['main_query'];

		if ( ! empty( $this->settings['lang'] ) ) {
			$args['lang'] = $this->settings['lang'];
		}

		if ( ! empty( $this->settings['paged'] ) ) {
			$args['paged'] = (int) $this->settings['paged'];
		}

		if ( ! empty( $this->settings['orderby'] ) ) {

			// To keep order from WooCommerce sort list.
			$_GET['orderby'] = $this->settings['orderby'];
			( new \WC_Query() )->get_catalog_ordering_args();

		}

		$args['wp_grid_builder'] = $this->get_id();

		// We override the main query when filtering.
		$wp_query = new \WP_Query( $args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	}

	/**
	 * Display no results message if no content found
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param stdClass $results Query results.
	 * @return array
	 */
	public function no_results( $results ) {

		if ( $results->total > 0 ) {
			return $results;
		}

		echo '<div class="products">' . wp_kses_post( $this->widget->get_settings( 'wpgb_noresults' ) ) . '</div>';

		return $results;

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
				trailingslashit( home_url( '', 'relative' ) ),
				trailingslashit( $this->settings['permalink'] ),
				$link
			);
		}

		return $link;

	}

	/**
	 * Filters the paginated links from WooCommerce pagination.
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
