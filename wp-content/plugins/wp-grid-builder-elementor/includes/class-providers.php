<?php
/**
 * Elementor providers
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
 * Providers
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers
 * @since 1.0.0
 */
final class Providers {

	/**
	 * Holds default Elementor providers
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $defaults = [
		'posts'                => __NAMESPACE__ . '\Providers\Posts',
		'portfolio'            => __NAMESPACE__ . '\Providers\Portfolio',
		'loop-grid'            => __NAMESPACE__ . '\Providers\Loop_Grid',
		'archive-posts'        => __NAMESPACE__ . '\Providers\Archive_Posts',
		'woocommerce-products' => __NAMESPACE__ . '\Providers\Products',
		'wc-archive-products'  => __NAMESPACE__ . '\Providers\Archive_Products',
		'jet-listing-grid'     => __NAMESPACE__ . '\Providers\Jet_Listing_Grid',
	];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'wp_grid_builder_elementor/providers', [ $this, 'default_providers' ] );

	}

	/**
	 * Add default providers
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $providers Holds providers.
	 * @return array
	 */
	public function default_providers( $providers ) {

		return array_merge(
			$this->defaults,
			$providers
		);
	}

	/**
	 * Get provider instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget   Elementor widget instance.
	 * @param integer     $post_id  The post ID (Elementor template).
	 * @param array       $settings Holds template settings.
	 * @return boolean|Widget_Base
	 */
	public function get( $widget, $post_id = 0, $settings = [] ) {

		$providers = apply_filters( 'wp_grid_builder_elementor/providers', [] );
		$provider  = $widget->get_name();

		if ( empty( $providers[ $provider ] ) ) {
			return false;
		}

		return new $providers[ $provider ]( $widget, $post_id, $settings );

	}
}
