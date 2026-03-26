<?php
/**
 * Widget
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes;

use Elementor\Plugin as Elementor;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Widget
 * @since 1.0.0
 */
final class Widget {

	/**
	 * Holds template attributes
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $template = [];

	/**
	 * Holds Elementor widget settings
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $widget = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $template Holds template attributes.
	 */
	public function __construct( $template ) {

		$this->template = $template;

	}

	/**
	 * Get Elementor widget instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean|Widget_Base
	 */
	public function get_instance() {

		if ( ! $this->has_widget() ) {
			return false;
		}

		return Elementor::instance()->elements_manager->create_element_instance( $this->widget );

	}

	/**
	 * Check if it has filterable widget
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function has_widget() {

		$widgets = $this->get_widgets();
		$this->parse_widgets( $widgets );

		return ! empty( $this->widget );

	}

	/**
	 * Get widgets from Elementor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_widgets() {

		// If is edit mode.
		if ( ! empty( $this->template['widgets'] ) ) {
			return $this->template['widgets'];
		}

		if ( empty( $this->template['post_id'] ) ) {
			return [];
		}

		$document = Elementor::instance()->documents->get( $this->template['post_id'] );

		if ( ! $document ) {
			return [];
		}

		// Prevent issue with Elementor v3.8.0.
		if ( method_exists( '\Elementor\Core\Editor\Editor', 'set_edit_mode' ) ) {
			Elementor::instance()->editor->set_edit_mode( false );
		}

		return $document->get_elements_data();

	}

	/**
	 * Parse Elementor widgets to match the one filterable
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $widgets Holds Elementor widgets.
	 */
	public function parse_widgets( $widgets = [] ) {

		foreach ( $widgets as $widget ) {

			if ( 'elementor-element-' . $widget['id'] === $this->template['id'] ) {
				$this->widget = $widget;
			} elseif ( ! empty( $widget['elements'] ) ) {
				$this->parse_widgets( $widget['elements'] );
			}
		}
	}
}
