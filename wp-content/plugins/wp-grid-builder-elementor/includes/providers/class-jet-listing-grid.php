<?php
/**
 * Jet Listing Grid widget
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
 * Jet_Listing_Grid widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Jet_Listing_Grid
 * @since 1.0.0
 */
final class Jet_Listing_Grid extends Base {

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
			'itemSelector' => '.jet-listing-grid__item',
			'isMainQuery'  => 'yes' === $this->widget->get_settings( 'is_archive_template' ),
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

		$script  = 'wpgb.facets.on(\'appended\',function(e,i){';
		$script .= 'var t=template.querySelector(\'[data-slider_options]\'),n=t&&t.querySelector(\'.jet-listing-grid__items\');';
		$script .= 't&&\'append\'===i?window.jQuery&&window.JetEngine&&(n.classList.contains(\'slick-initialized\')?';
		$script .= 'e.forEach(function(e){jQuery(n).slick(\'slickAdd\',e)}):(t.classList.add(\'jet-listing-grid__slider\'),';
		$script .= 'JetEngine.initSlider(jQuery(t),{itemsCount:parseInt(n.childElementCount,10)}))):elementorFrontend.elementsHandler.runReadyTrigger(template)});';

		return $script;

	}

	/**
	 * Apply filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {

		add_filter( 'jet-engine/listing/grid/posts-query-args', [ $this, 'query_args' ] );

	}

	/**
	 * Remove filters for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		remove_filter( 'jet-engine/listing/grid/posts-query-args', [ $this, 'query_args' ] );

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

		$is_main_query = (
			wpgb_doing_ajax() &&
			! empty( $this->settings['main_query'] ) &&
			! empty( $this->settings['is_main_query'] )
		);

		if ( $is_main_query ) {
			$args = $this->settings['main_query'];
		}

		if ( ! empty( $this->settings['lang'] ) ) {
			$args['lang'] = $this->settings['lang'];
		}

		$args['wp_grid_builder'] = $this->get_id();

		if ( $is_main_query ) {
			$GLOBALS['wp_query'] = new \WP_Query( $args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		return $args;

	}
}
