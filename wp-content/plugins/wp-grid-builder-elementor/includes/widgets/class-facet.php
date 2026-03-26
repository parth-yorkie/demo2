<?php
/**
 * Facet widget
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes\Widgets;

use Elementor\Widget_Base;
use Elementor\Plugin as Elementor;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Facet widget
 *
 * @class WP_Grid_Builder_Elementor\Includes\Widgets\Facet
 * @since 1.0.0
 */
final class Facet extends Widget_Base {

	/**
	 * Widget name
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {

		return 'wpgb-facet';

	}

	/**
	 * Widget title
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {

		return __( 'Facet', 'wpgb-elementor' );

	}

	/**
	 * Widget icon
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {

		return 'wpgb-icon-facet';

	}

	/**
	 * Widget categories
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {

		return [ 'wp-grid-builder' ];

	}

	/**
	 * Get available facets
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_facets() {

		global $wpdb;

		// We only query from Editor ajax request.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! wp_doing_ajax() || empty( $_REQUEST['editor_post_id'] ) ) {
			return [];
		}

		$facets = $wpdb->get_results( "SELECT id, name, type FROM {$wpdb->prefix}wpgb_facets" );

		return array_column( $facets, 'name', 'id' );

	}

	/**
	 * Get available grids
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_grids() {

		global $wpdb;

		// We only query from Editor ajax request.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! wp_doing_ajax() || empty( $_REQUEST['editor_post_id'] ) ) {
			return [];
		}

		$grids = $wpdb->get_results( "SELECT id, name, type FROM {$wpdb->prefix}wpgb_grids" );

		return array_column( $grids, 'name', 'id' );

	}

	/**
	 * Widget controls
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function register_controls() {

		include WPGB_ELEMENTOR_PATH . 'includes/controls/facet.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/title.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/list.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/choice.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/label.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/counter.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/select.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/radio.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/checkbox.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/rating.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/input.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/button.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/range.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/color.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/selection.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/pagination.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/result-count.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/map.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/geolocation.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/treeview.php';
		include WPGB_ELEMENTOR_PATH . 'includes/controls/toggle.php';

	}

	/**
	 * Widget render
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$grid_id  = $this->get_grid_id( $settings );

		if ( empty( $settings['facet'] ) || empty( $grid_id ) ) {
			return;
		}

		wpgb_render_facet(
			[
				'id'   => $settings['facet'],
				'grid' => $grid_id,
			]
		);

	}

	/**
	 * Get grid ID
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $settings Holds facet settings.
	 * @return array
	 */
	protected function get_grid_id( $settings ) {

		if ( empty( $settings['widget_id'] ) ) {
			return $settings['grid'];
		}

		if ( Elementor::instance()->editor->is_edit_mode() ) {
			return '_elementor_preview_template';
		}

		return 'elementor-element-' . $settings['widget_id'];

	}

	/**
	 * Widget render
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function content_template() {

		?>
		<# if ( ! settings.facet || ( ! settings.grid && ! settings.widget_id ) ) { #>
			<div class="wpgb-widget-placeholder">
				<div class="wpgb-widget-header">
					<svg viewBox="0 0 1000 1000"><path fill="#555d66" d="M838.071 0H161.928C72.496 0 0 72.495 0 161.928v676.143C0 927.504 72.496 1000 161.928 1000H838.07c89.433 0 161.929-72.496 161.929-161.929V161.928C999.999 72.495 927.503 0 838.071 0zM721.246 801.412c0 11.589-6.238 17.827-17.832 17.833H297.056c-11.588 0-17.833-6.244-17.833-17.833V696.188c0-11.594 6.246-17.833 17.833-17.833h406.358c11.588 0 17.832 6.239 17.832 17.833v105.224zM500.283 621.389c-126.023 0-226.131-92.06-226.131-213.619 0-121.554 99.824-213.347 225.848-213.347v127.544c-50.051 0-83.732 39.325-83.732 85.802s33.959 85.809 84.016 85.809c49.155 0 83.12-39.332 83.12-85.809l-.006-1.032h142.116l.006 1.032c-.001 121.561-100.11 213.62-225.237 213.62zm225.61-270.771H554.764V179.49h157.493c7.526.003 13.629 6.102 13.636 13.63v157.498z"></path></svg>
					<div class="wpgb-widget-label"><?php esc_html_e( 'Please Select a Facet and a Grid.', 'wpgb-elementor' ); ?></div>
				</div>
			</div>
		<# } else { #>
			<div class="wpgb-facet wpgb-facet-{{{settings.facet}}}" data-facet="{{{settings.facet}}}" data-grid="{{{settings.grid}}}">
				<div class="wpgb-widget-placeholder">
					<div class="wpgb-widget-header">
						<div class="wpgb-loading-spinner"></div>
						<div class="wpgb-widget-label"><?php esc_html_e( 'Please wait, loading content...', 'wpgb-elementor' ); ?></div>
					</div>
				</div>
			</div>
		<# } #>
		<?php

	}
}
