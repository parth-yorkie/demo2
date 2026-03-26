<?php
/**
 * Settings
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder\FrontEnd;

use WP_Grid_Builder\Includes\Database;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Query grid settings
 *
 * @class WP_Grid_Builder\FrontEnd\Settings
 * @since 1.0.0
 */
final class Settings implements Models\Settings_Interface {

	/**
	 * Holds instance properties
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var object
	 */
	public $properties;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $grid Holds grid arguments.
	 */
	public function __construct( $grid ) {

		$this->properties = $grid;

	}

	/**
	 * Magic get method
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $key Key settings to retrieve.
	 * @return mixed.
	 */
	public function &__get( $key ) {

		return $this->properties->$key;

	}

	/**
	 * Magic set method
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $key Key to add.
	 * @param string $val Value to set.
	 * @return mixed.
	 */
	public function __set( $key, $val ) {

		$this->properties->$key = $val;

	}

	/**
	 * Magic isset method
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $key Key settings to check against.
	 * @return boolean.
	 */
	public function __isset( $key ) {

		return isset( $this->properties->$key );

	}

	/**
	 * Magic unset method
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $key Key settings to check against.
	 */
	public function __unset( $key ) {

		unset( $this->properties->$key );

	}

	/**
	 * Check grid id property
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @throws \Exception Error missing id.
	 */
	public function is_valid() {

		// Make it work with grid id passed as argument.
		if ( isset( $this->properties ) && is_scalar( $this->properties ) ) {
			$this->properties = [ 'id' => $this->properties ];
		}

		// If grid id is not valid.
		if ( empty( $this->properties['id'] ) || ! is_numeric( $this->properties['id'] ) ) {

			$error_msg = __( 'Sorry, no grids were found for the requested grid id.', 'wp-grid-builder' );
			throw new \Exception( $error_msg );

		}

		$this->properties['id'] = (int) $this->properties['id'];

	}

	/**
	 * Handle dynamic grid (like card overview)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function is_dynamic() {

		if ( ! isset( $this->properties['is_dynamic'], $this->properties['id'] ) ) {
			return false;
		}

		$this->properties['id'] = sanitize_html_class( $this->properties['id'] );

		// Mainly to correctly hook in preview mode from grid id (if grid is saved).
		if ( ! empty( $this->properties['is_preview'] ) && 'preview' !== $this->properties['id'] ) {
			$this->properties['id'] = (int) $this->properties['id'];
		}

		$this->filter_properties( $this->properties );

		return true;

	}

	/**
	 * Query grid settings
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @throws \Exception Error missing grid settings.
	 */
	public function query() {

		if ( $this->is_dynamic() ) {
			return;
		}

		$this->is_valid();

		$settings = Database::query_var(
			[
				'select' => 'settings',
				'from'   => 'grids',
				'id'     => $this->properties['id'],
			]
		);

		if ( empty( $settings ) ) {

			/* translators: %d: grid id */
			$error_msg = sprintf( __( 'No settings found for the grid #%d.', 'wp-grid-builder' ), $this->properties['id'] );
			throw new \Exception( $error_msg );

		}

		$settings = json_decode( $settings, true );
		$settings = wp_parse_args( $this->properties, $settings );

		$this->filter_properties( $settings );

	}

	/**
	 * Filter properties
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $settings Holds grid settings.
	 */
	public function filter_properties( $settings ) {

		$this->properties = apply_filters( 'wp_grid_builder/grid/settings', $settings );

	}
}
