<?php
/**
 * Button controls
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
	'section_button_style',
	[
		'label'     => __( 'Button', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'number', 'load_more', 'apply', 'reset' ],
		],
	]
);

$this->add_responsive_control(
	'button_alignment',
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
			'stretch'    => [
				'title' => __( 'Stretch', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-justify',
			],
		],
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet' => 'display: -ms-flexbox;display: flex;-ms-flex-direction: column;flex-direction: column;-ms-flex-align:{{VALUE}}; align-items:{{VALUE}};',
		],
		'condition' => [
			'type!' => 'number',
		],
	]
);

$this->add_responsive_control(
	'button_width',
	[
		'label'      => __( 'Button width', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 2000,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} button.wpgb-button' => 'width: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'button_alignment!' => 'stretch',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'button_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} button.wpgb-button',
	]
);

$this->start_controls_tabs( 'button_tabs' );

$this->start_controls_tab(
	'button_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'button_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'button_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'button_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} button.wpgb-button',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'button_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'button_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button:not([disabled]):hover, {{WRAPPER}} button.wpgb-button:not([disabled]):focus' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'button_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button:not([disabled]):hover, {{WRAPPER}} button.wpgb-button:not([disabled]):focus' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'button_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'button_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button:not([disabled]):hover, {{WRAPPER}} button.wpgb-button:not([disabled]):focus' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'button_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} button.wpgb-button:not([disabled]):hover, {{WRAPPER}} button.wpgb-button:not([disabled]):focus',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'button_disabled_tab',
	[
		'label' => __( 'Disabled', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'button_disabled_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button[disabled]' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'button_disabled_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button[disabled]' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'button_disabled_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'button_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} button.wpgb-button[disabled]' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'button_disabled_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} button.wpgb-button[disabled]',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'button_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} button.wpgb-button',
	]
);

$this->add_control(
	'button_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} button.wpgb-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'button_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} button.wpgb-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'button_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'default'    => [
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		],
		'selectors'  => [
			'{{WRAPPER}} button.wpgb-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
