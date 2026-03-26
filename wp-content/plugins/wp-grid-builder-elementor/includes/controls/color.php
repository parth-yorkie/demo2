<?php
/**
 * Color controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_color_swatch_style',
	[
		'label'     => __( 'Color Swatch', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'color',
		],
	]
);

$this->add_responsive_control(
	'color_swatch_size',
	[
		'label'      => __( 'Swatch Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-color-facet .wpgb-color-control' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
		],
	]
);


$this->start_controls_tabs( 'color_swatch_tabs' );

$this->start_controls_tab(
	'color_swatch_idle_tab',
	[
		'label' => _x( 'Normal', 'Color swatch state', 'wpgb-elementor' ),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'color_swatch_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-color-facet .wpgb-color-control',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'color_swatch_hover_tab',
	[
		'label' => _x( 'Hover', 'Color swatch state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'color_swatch_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-color:hover .wpgb-color-control:after' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'color_swatch_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-color:not([aria-pressed="true"]):hover .wpgb-color-control',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'color_swatch_selected_tab',
	[
		'label' => _x( 'Selected', 'Color swatch state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'color_swatch_selected_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-color[aria-pressed="true"] .wpgb-color-control:after' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'color_swatch_selected_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-color[aria-pressed="true"] .wpgb-color-control',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'color_swatch_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-color .wpgb-color-control:after',
	]
);

$this->add_control(
	'color_swatch_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-color .wpgb-color-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
