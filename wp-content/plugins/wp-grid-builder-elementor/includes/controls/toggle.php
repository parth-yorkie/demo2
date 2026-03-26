<?php
/**
 * Toggle button controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_toggle_button_style',
	[
		'label'     => __( 'Toggle Button', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'checkbox', 'radio', 'button', 'hierarchy' ],
		],
	]
);

$this->add_control(
	'toggle_button_alignment',
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
			'{{WRAPPER}} div[class^="wpgb-"][class$="-facet"]' => 'display: -ms-flexbox;display: flex;-ms-flex-direction: column;flex-direction: column;',
			'{{WRAPPER}} .wpgb-toggle-hidden' => '-ms-flex-item-align:{{VALUE}}; align-self:{{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'toggle_button_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-toggle-hidden',
	]
);

$this->start_controls_tabs( 'toggle_button_tabs' );

$this->start_controls_tab(
	'toggle_button_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'toggle_button_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-toggle-hidden' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'toggle_button_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-toggle-hidden' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'toggle_button_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-toggle-hidden',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'toggle_button_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'toggle_button_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-toggle-hidden:hover' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'toggle_button_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-toggle-hidden:hover' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'toggle_button_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-toggle-hidden:hover' => 'border-color: {{VALUE}}',
		],
		'condition' => [
			'toggle_button_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'toggle_button_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-toggle-hidden:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'toggle_button_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-toggle-hidden',
	]
);

$this->add_control(
	'toggle_button_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-toggle-hidden' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'toggle_button_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-toggle-hidden' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'toggle_button_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-toggle-hidden' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
