<?php
/**
 * Geolocation controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_locate_button_style',
	[
		'label'     => __( 'Locate Me Button', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'geolocation',
		],
	]
);

$this->add_control(
	'locate_button',
	[
		'label'     => __( 'Hide Button', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-locate-button' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'locate_button_size',
	[
		'label'      => __( 'Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-locate-button' => '--wpgb-input-locate-scale: calc({{SIZE}}/20);transform: scale(var(--wpgb-input-locate-scale));',
		],
		'condition'  => [
			'locate_button!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'locate_button_horizontal_offset',
	[
		'label'      => __( 'Horizontal Offset', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => -50,
				'max' => 50,
			],
		],
		'selectors'  => [
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-locate-button' => 'right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-locate-button'       => 'left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'locate_button!' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'locate_button_tabs',
	[
		'condition' => [
			'locate_button!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'locate_button_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'locate_button_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-locate-button' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'locate_button_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'locate_button_hover_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-locate-button:hover, {{WRAPPER}} .wpgb-facet .wpgb-locate-button:focus' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'section_radius_control_style',
	[
		'label'     => __( 'Radius Control', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'geolocation',
		],
	]
);

$this->add_control(
	'radius_control_alignment',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-geo-radius' => 'justify-content: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'radius_control_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-facet .wpgb-geo-radius, {{WRAPPER}} .wpgb-facet .wpgb-geo-radius input',
	]
);

$this->add_control(
	'radius_control_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-geo-radius, {{WRAPPER}} .wpgb-facet .wpgb-geo-radius input' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'radius_control_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-geo-radius, {{WRAPPER}} .wpgb-facet .wpgb-geo-radius input' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'radius_control_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-facet .wpgb-geo-radius',
	]
);

$this->add_control(
	'radius_control_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-geo-radius' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'radius_control_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-geo-radius' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'radius_control_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-geo-radius' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
