<?php
/**
 * Selection controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_selection_clear_icon_style',
	[
		'label'     => __( 'Clear Icon', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'selection',
		],
	]
);

$this->add_control(
	'selection_clear_icon',
	[
		'label'     => __( 'Hide Button', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-button-control' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'selection_clear_icon_size',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-button-control' => '--wpgb-selection-clear-scale: calc({{SIZE}}/15);transform: scale(var(--wpgb-selection-clear-scale));',
		],
		'condition'  => [
			'selection_clear_icon!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'selection_clear_icon_horizontal_offset',
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
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-button-control' => 'margin-left: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-button-control'       => 'margin-right: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'selection_clear_icon!' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'selection_clear_icon_tabs',
	[
		'condition' => [
			'selection_clear_icon!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'selection_clear_icon_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'selection_clear_icon_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-button-control:before, {{WRAPPER}} .wpgb-facet .wpgb-button-control:after' => 'background-color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'selection_clear_icon_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'selection_clear_icon_hover_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-button-control:hover:before, {{WRAPPER}} .wpgb-facet .wpgb-button-control:hover:after' => 'background-color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
