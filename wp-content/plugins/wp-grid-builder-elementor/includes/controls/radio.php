<?php
/**
 * Radio controls
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
	'section_radio_style',
	[
		'label'     => __( 'Radio', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'radio',
		],
	]
);

$this->add_responsive_control(
	'radio_size',
	[
		'label'      => __( 'Radio Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-radio .wpgb-radio-control' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'radio_circle_size',
	[
		'label'      => __( 'Cirlce Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control:after'  => 'min-width: calc({{SIZE}}px - 4px);min-height: calc({{SIZE}}px - 4px)',
		],
	]
);

$this->start_controls_tabs( 'radio_tabs' );

$this->start_controls_tab(
	'radio_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'radio_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-radio .wpgb-radio-control' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'radio_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-radio .wpgb-radio-control',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'radio_pressed_tab',
	[
		'label' => __( 'Pressed', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'radio_pressed_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'radio_pressed_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'radio_pressed_circle_color',
	[
		'label'     => __( 'Circle Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control:after' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'radio_pressed_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'radio_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-radio .wpgb-radio-control',
	]
);

$this->add_control(
	'radio_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-radio .wpgb-radio-control'       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .wpgb-radio .wpgb-radio-control:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
	]
);

$this->add_responsive_control(
	'radio_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-radio[aria-pressed] .wpgb-radio-control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
