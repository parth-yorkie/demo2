<?php
/**
 * Choice label controls
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
	'section_choice_label_style',
	[
		'label'     => __( 'Choice Label', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'checkbox', 'radio', 'button', 'hierarchy', 'rating', 'selection', 'color', 'az_index' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'choice_label_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} li [class^="wpgb-"][class$="-label"]',
	]
);

$this->start_controls_tabs( 'choice_label_tabs' );

$this->start_controls_tab(
	'choice_label_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'choice_label_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"]' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'choice_label_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"]' => 'background-color: {{VALUE}}',
		],
	]
);

// Facet color tooltip triangle.
$this->add_control(
	'choice_label_triangle_color',
	[
		'label'     => '',
		'type'      => Controls_Manager::HIDDEN,
		'default'   => 1,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-color-facet .wpgb-color-label:after' => 'border-top-color: {{choice_label_background_color.VALUE}}',
		],
		'condition' => [
			'type' => 'color',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'choice_label_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'choice_label_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} [aria-pressed]:hover [class^="wpgb-"][class$="-label"]' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'choice_label_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} [aria-pressed]:hover [class^="wpgb-"][class$="-label"]' => 'background-color: {{VALUE}}',
		],
	]
);

// Facet color tooltip triangle.
$this->add_control(
	'choice_label_hover_triangle_color',
	[
		'label'     => '',
		'type'      => Controls_Manager::HIDDEN,
		'default'   => 1,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-color-facet [aria-pressed]:hover .wpgb-color-label:after' => 'border-top-color: {{choice_label_hover_background_color.VALUE}}',
		],
		'condition' => [
			'type' => 'color',
		],
	]
);

$this->add_control(
	'choice_label_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'choice_label_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} [aria-pressed]:hover [class^="wpgb-"][class$="-label"]' => 'border-color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'choice_label_selected_tab',
	[
		'label' => __( 'Selected', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'choice_label_selected_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} [aria-pressed="true"] [class^="wpgb-"][class$="-label"]' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'choice_label_selected_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} [aria-pressed="true"] [class^="wpgb-"][class$="-label"]' => 'background-color: {{VALUE}}',
		],
	]
);

// Facet color tooltip triangle.
$this->add_control(
	'choice_label_selected_triangle_color',
	[
		'label'     => '',
		'type'      => Controls_Manager::HIDDEN,
		'default'   => 1,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-color-facet [aria-pressed="true"] .wpgb-color-label:after' => 'border-top-color: {{choice_label_selected_background_color.VALUE}}',
		],
		'condition' => [
			'type' => 'color',
		],
	]
);

$this->add_control(
	'choice_label_selected_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'choice_label_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} [aria-pressed="true"] [class^="wpgb-"][class$="-label"]' => 'border-color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'choice_label_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} li [class^="wpgb-"][class$="-label"]',
	]
);

$this->add_control(
	'choice_label_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'choice_label_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'choice_label_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
