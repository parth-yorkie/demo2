<?php
/**
 * Checkbox controls
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
	'section_checkbox_style',
	[
		'label'     => __( 'Checkbox', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'checkbox',
		],
	]
);

$this->add_responsive_control(
	'checkbox_size',
	[
		'label'      => __( 'Checkbox Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-checkbox .wpgb-checkbox-control' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'checkbox_checkmark_size',
	[
		'label'      => __( 'Checkmark Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed] .wpgb-checkbox-control'                => '--wpgb-checkbox-scale: calc({{SIZE}}/20);',
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control:after'   => 'top: calc(-{{SIZE}}px/20);transform: rotate(45deg) scale(var(--wpgb-checkbox-scale))',
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control:before' => 'transform: scale(var(--wpgb-checkbox-scale))',
		],
	]
);

$this->start_controls_tabs( 'checkbox_tabs' );

$this->start_controls_tab(
	'checkbox_idle_tab',
	[
		'label' => _x( 'Normal', 'Checkbox state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'checkbox_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox .wpgb-checkbox-control' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'checkbox_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-checkbox .wpgb-checkbox-control',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'checkbox_checked_tab',
	[
		'label' => _x( 'Checked', 'Checkbox state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'checkbox_checked_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'checkbox_checked_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'checkbox_checked_checkmark_color',
	[
		'label'     => __( 'Checkmark Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control:after' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'checkbox_checked_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'checkbox_mixed_tab',
	[
		'label' => _x( 'Mixed', 'Checkbox state', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'checkbox_mixed_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'checkbox_mixed_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'checkbox_mixed_checkmark_color',
	[
		'label'     => __( 'Checkmark Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control:before' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'checkbox_mixed_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'checkbox_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-checkbox .wpgb-checkbox-control',
	]
);

$this->add_control(
	'checkbox_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-checkbox .wpgb-checkbox-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'checkbox_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-checkbox[aria-pressed] .wpgb-checkbox-control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
