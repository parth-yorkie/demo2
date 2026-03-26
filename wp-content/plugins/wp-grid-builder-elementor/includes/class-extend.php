<?php
/**
 * Extend widget controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes;

use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extend
 *
 * @class WP_Grid_Builder_Elementor\Includes\WidExtendgets
 * @since 1.0.0
 */
class Extend {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'elementor/element/wc-archive-products/section_content/after_section_end', [ $this, 'pagination_control' ] );
		add_action( 'elementor/element/wc-archive-products/section_advanced/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/element/woocommerce-products/section_query/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/element/jet-listing-grid/section_carousel/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/element/archive-posts/section_advanced/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/element/loop-grid/section_pagination/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/element/posts/section_pagination/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/element/portfolio/filter_bar/after_section_end', [ $this, 'fading_control' ] );
		add_action( 'elementor/widget/before_render_content', [ $this, 'apply_controls' ] );

	}

	/**
	 * Add pagination control
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget Hold widget instance.
	 */
	public function pagination_control( $widget ) {

		$widget->start_injection(
			[
				'at' => 'after',
				'of' => 'show_result_count',
			]
		);

		$widget->add_control(
			'wpgb_pagination',
			[
				'label'   => __( 'Pagination', 'wpgb-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$widget->end_injection();

	}

	/**
	 * Add fading control
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget Hold widget instance.
	 */
	public function fading_control( $widget ) {

		$widget->start_controls_section(
			'wp_grid_builder_section',
			[
				'label' => 'WP Grid Builder',
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'wpgb_widget_id',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					// translators: %s: Widget ID.
					__( 'Widget ID: <strong>%s</strong>', 'wpgb-elementor' ),
					'%s'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$widget->add_control(
			'wpgb_fading',
			[
				'label'     => __( 'Fading Animation', 'wpgb-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}.wpgb-enabled' => 'transition: opacity 0.35s ease',
					'{{WRAPPER}}.wpgb-loading' => 'opacity: 0.6',
				],
			]
		);

		$this->noresults_control( $widget );

		$widget->end_controls_section();

	}

	/**
	 * Add noresults message control
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget Hold widget instance.
	 */
	public function noresults_control( $widget ) {

		if ( ! in_array( $widget->get_name(), [ 'posts', 'portfolio', 'loop-grid', 'woocommerce-products' ], true ) ) {
			return;
		}

		$widget->add_control(
			'wpgb_noresults',
			[
				'label'       => __( 'No Results Message', 'wpgb-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => __( 'Sorry, no results match your search criteria.', 'wpgb-elementor' ),
				'placeholder' => __( 'Sorry, no results match your search criteria.', 'wpgb-elementor' ),
			]
		);
	}

	/**
	 * Apply widget control settings
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget Hold widget instance.
	 */
	public function apply_controls( $widget ) {

		if ( 'wc-archive-products' !== $widget->get_name() ) {
			return;
		}

		if ( 'yes' === $widget->get_settings( 'wpgb_pagination' ) ) {
			return;
		}

		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

	}
}
