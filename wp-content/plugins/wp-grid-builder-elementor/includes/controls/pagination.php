<?php
/**
 * Pagination controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_pagination_style',
	[
		'label'     => __( 'Pagination', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'pagination',
		],
	]
);

$this->add_responsive_control(
	'pagination_alignment',
	[
		'label'     => __( 'Alignment', 'wpgb-elementor' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'flex-start' => [
				'title' => __( 'Left', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-left',
			],
			'center'     => [
				'title' => __( 'Center', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-center',
			],
			'flex-end'   => [
				'title' => __( 'Right', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-right',
			],
		],
		'selectors' => [
			'{{WRAPPER}} .wpgb-pagination' => 'display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;justify-content: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'pagination_horizontal_spacing',
	[
		'label'      => __( 'Horizontal Spacing', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-pagination' => 'margin: 0 calc(-{{SIZE}}{{UNIT}} / 2);',
			'{{WRAPPER}} .wpgb-page'       => 'height: auto;min-width: auto;margin: 0 calc({{SIZE}}{{UNIT}} / 2);',
		],
	]
);

$this->add_responsive_control(
	'pagination_vertical_spacing',
	[
		'label'      => __( 'Vertical Spacing', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-pagination' => 'display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-bottom: -{{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .wpgb-page'       => 'height: auto;min-width: auto;margin-top: 0;margin-bottom: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'pagination_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-page > a, {{WRAPPER}} .wpgb-page > span',
	]
);

$this->start_controls_tabs( 'pagination_tabs' );

$this->start_controls_tab(
	'pagination_idle_tab',
	[
		'label' => _x( 'Normal', 'Pagination state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'pagination_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'pagination_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-page > a',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'pagination_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'pagination_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a:hover' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a:hover' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'pagination_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a:hover' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'pagination_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-page > a:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'pagination_current_tab',
	[
		'label' => _x( 'Current', 'Pagination state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'pagination_current_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a[aria-current="true"]' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_current_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a[aria-current="true"]' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_current_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'pagination_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > a[aria-current="true"]' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'pagination_current_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-page > a[aria-current="true"]',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'pagination_dots_tab',
	[
		'label' => __( 'Dots', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'pagination_dots_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > span' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_dots_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-page > span' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'pagination_dots_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'pagination_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} ul .wpgb-page > span' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'pagination_dots_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-page > span',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'pagination_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-page > a, {{WRAPPER}} .wpgb-page > span',
	]
);

$this->add_control(
	'pagination_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-page > a, {{WRAPPER}} .wpgb-page > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'pagination_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-page > a, {{WRAPPER}} .wpgb-page > span' => 'height: auto;min-width: auto;padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
