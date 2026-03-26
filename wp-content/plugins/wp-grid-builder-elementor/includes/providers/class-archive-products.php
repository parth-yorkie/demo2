<?php
/**
 * Archive Products widget
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
 * Archive_Products widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Archive_Products
 * @since 1.0.0
 */
final class Archive_Products extends Base {

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

		$script  = 'var p=1,o=\'\';template.addEventListener(\'click\',function(e){';
		$script .= 'var t=e.target;p=(t.href||\'\').match(/\/(\d+)/);';
		$script .= '(t.classList.contains(\'page-numbers\')&&t.tagName===\'A\')&&(e.preventDefault()||wpgb.facets.refresh())});';
		$script .= 'window.jQuery&&jQuery(\'.woocommerce-ordering\').off(\'change\',\'select.orderby\');';
		$script .= 'template.addEventListener(\'change\',function(e){';
		$script .= 'var t=e.target;(t.classList.contains(\'orderby\')&&t.tagName===\'SELECT\')&&(o=t.value);(e.preventDefault()||wpgb.facets.refresh())});';
		$script .= 'wpgb.facets.on(\'fetch\',function(d){d.paged=(p&&p[1]||1);d.orderby=o;p=1});';
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

		add_filter( 'paginate_links', [ $this, 'paginate_links' ] );
		add_filter( 'get_terms', [ $this, 'remove_categories' ] );
		$this->wp_query();

	}

	/**
	 * Remove filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		remove_filter( 'paginate_links', [ $this, 'paginate_links' ] );
		remove_filter( 'get_terms', [ $this, 'remove_categories' ] );

	}

	/**
	 * Set and run WordPress query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_query() {

		global $wp_query;

		if ( ! wpgb_doing_ajax() ) {
			return;
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
	 * Get default query from editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function default_query() {

		$cols = wc_get_default_products_per_row();
		$rows = wc_get_default_product_rows_per_page();

		// If is Elementor edit mode (ajax request holds widgets).
		if ( ! empty( $this->settings['widgets'] ) ) {
			return $this->editor_query( $cols, $rows );
		}

		return [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'wc_query'            => 'product_query',
			'posts_per_page'      => apply_filters( 'loop_shop_per_page', $cols * $rows ),
			'meta_query'          => WC()->query->get_meta_query(),
			'ignore_sticky_posts' => true,
		];
	}

	/**
	 * Get query from WooCommerce shortcode in edit mode
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param integer $cols Number of columns.
	 * @param integer $rows Number of rows.
	 * @return array
	 */
	public function editor_query( $cols, $rows ) {

		return (
			new \WC_Shortcode_Products(
				[
					'paginate' => true,
					'columns'  => $cols,
					'rows'     => $rows,
				]
			)
		)->get_query_args();
	}

	/**
	 * Remove categories from shop list if grid is filtered.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $terms Array of found terms.
	 * @return array
	 */
	public function remove_categories( $terms ) {

		return function_exists( 'is_shop' ) && is_shop() && wpgb_has_selected_facets() ? [] : $terms;

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
