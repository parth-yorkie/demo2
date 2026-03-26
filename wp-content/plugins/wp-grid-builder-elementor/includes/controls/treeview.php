<?php
/**
 * Treeview button controls
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
	'section_treeview_button_style',
	[
		'label'     => __( 'Treeview Buttons', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'checkbox',
		],
	]
);

$this->add_responsive_control(
	'treeview_button_size',
	[
		'label'      => __( 'Button Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} ul li[aria-expanded]:after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'treeview_button_icon_size',
	[
		'label'      => __( 'Icon Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} ul li[aria-expanded="true"]:after'  => 'background-size: calc({{SIZE}}{{UNIT}} * 0.5) calc({{SIZE}}{{UNIT}} * 0.1);',
			'{{WRAPPER}} ul li[aria-expanded="false"]:after' => 'background-size: calc({{SIZE}}{{UNIT}} * 0.1) calc({{SIZE}}{{UNIT}} * 0.5), calc({{SIZE}}{{UNIT}} * 0.5) calc({{SIZE}}{{UNIT}} * 0.1)',
		],
	]
);

$this->start_controls_tabs( 'treeview_button_tabs' );

$this->start_controls_tab(
	'treeview_button_collapsed_tab',
	[
		'label' => __( 'Collapsed', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'treeview_button_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} ul li[aria-expanded="false"]:after' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'treeview_button_icon_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} ul li[aria-expanded="false"]:after' => 'background-image: linear-gradient({{VALUE}}, {{VALUE}}),linear-gradient({{VALUE}}, {{VALUE}})',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'treeview_button_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} ul li[aria-expanded]:after',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'treeview_button_expanded_tab',
	[
		'label' => __( 'Expanded', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'treeview_button_expanded_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} ul li[aria-expanded="true"]:after' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'treeview_button_expanded_icon_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} ul li[aria-expanded="true"]:after' => 'background-image: linear-gradient({{VALUE}}, {{VALUE}}),linear-gradient({{VALUE}}, {{VALUE}})',
		],
	]
);

$this->add_control(
	'treeview_button_expanded_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'treeview_button_border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} ul li[aria-expanded="true"]:after' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'treeview_button_expanded_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} ul li[aria-expanded="true"]:after',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'treeview_button_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} ul li[aria-expanded]:after',
	]
);

$this->add_control(
	'treeview_button_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} ul li[aria-expanded]:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'treeview_button_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} ul li[aria-expanded]:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
