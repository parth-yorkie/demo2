<?php
/**
 * Range controls
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
	'section_range_style',
	[
		'label'     => __( 'Range', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'range',
		],
	]
);

$this->start_controls_tabs( 'range_tabs' );

$this->start_controls_tab(
	'range_track_tab',
	[
		'label' => __( 'Track', 'wpgb-elementor' ),
	]
);

$this->add_responsive_control(
	'range_track_height',
	[
		'label'      => __( 'Height', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 1,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider' => 'height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'range_track_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-slider' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'range_track_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-range-slider',
	]
);

$this->add_control(
	'range_track_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'range_track_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-slider',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'range_progress_tab',
	[
		'label' => __( 'Progress', 'wpgb-elementor' ),
	]
);

$this->add_responsive_control(
	'range_progress_height',
	[
		'label'      => __( 'Height', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 1,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-progress' => 'height: {{SIZE}}{{UNIT}};top: calc(50% - ( {{SIZE}}{{UNIT}} / 2 ) );',
		],
	]
);

$this->add_control(
	'range_progress_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-progress' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'range_progress_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-range-slider .wpgb-range-progress',
	]
);

$this->add_control(
	'range_progress_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-progress' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'range_progress_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-slider .wpgb-range-progress',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'section_range_thumb_style',
	[
		'label'     => __( 'Range Thumb', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'range',
		],
	]
);

$this->add_responsive_control(
	'range_thumb_width',
	[
		'label'      => __( 'Width', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 1,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-progress, {{WRAPPER}} .wpgb-range-slider .wpgb-range-thumbs' => 'left: calc({{SIZE}}{{UNIT}} / 2);right: calc({{SIZE}}{{UNIT}} / 2);',
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-thumb'                                                       => 'width: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_responsive_control(
	'range_thumb_height',
	[
		'label'      => __( 'Height', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 1,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-thumb' => 'height: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_control(
	'range_thumb_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-thumb' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'range_thumb_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-range-slider .wpgb-range-thumb',
	]
);

$this->add_control(
	'range_thumb_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-slider .wpgb-range-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'range_thumb_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-slider .wpgb-range-thumb',
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_range_values_style',
	[
		'label'     => __( 'Range Values', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'range',
		],
	]
);

$this->add_responsive_control(
	'range_values_alignment',
	[
		'label'     => __( 'Text Alignment', 'wpgb-elementor' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'left'   => [
				'title' => __( 'Left', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-left',
			],
			'center' => [
				'title' => __( 'Center', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-center',
			],
			'right'  => [
				'title' => __( 'Right', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-right',
			],
		],
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-values' => 'text-align: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'range_values_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-facet .wpgb-range-values',
	]
);

$this->add_control(
	'range_values_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-values' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'range_values_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-values' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'range_values_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-range-facet .wpgb-range-values',
	]
);

$this->add_control(
	'range_values_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-values' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'range_values_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-values' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'range_values_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-values' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_range_clear_button_style',
	[
		'label'     => __( 'Clear Button', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'range',
		],
	]
);

$this->add_control(
	'range_clear_button',
	[
		'label'     => __( 'Hide Button', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear' => 'display: none',
		],
	]
);

$this->start_controls_tabs(
	'range_clear_button_tabs',
	[
		'condition' => [
			'range_clear_button!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'range_clear_button_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'range_clear_button_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'range_clear_button_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear' => 'background-color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'range_clear_button_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'range_clear_button_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'range_clear_button_hover_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear:not([disabled]):hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'range_clear_button_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear:not([disabled]):hover' => 'background-color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'range_clear_button_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear:not([disabled]):hover' => 'border-color: {{VALUE}};',
		],
		'condition' => [
			'range_clear_button_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'range_clear_button_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear:not([disabled]):hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'range_clear_button_disabled_tab',
	[
		'label' => __( 'Disabled', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'range_clear_button_disabled_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear[disabled]' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'range_clear_button_disabled_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear[disabled]' => 'opacity:1;background-color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'range_clear_button_disabled_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear[disabled]' => 'border-color: {{VALUE}};',
		],
		'condition' => [
			'range_clear_button_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'range_clear_button_disabled_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear[disabled]',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'range_clear_button_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear',
		'condition' => [
			'range_clear_button!' => 'yes',
		],
	]
);

$this->add_control(
	'range_clear_button_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'range_clear_button!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'range_clear_button_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'range_clear_button!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'range_clear_button_margin',
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
			'{{WRAPPER}} .wpgb-range-facet .wpgb-range-clear' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'range_clear_button!' => 'yes',
		],
	]
);

$this->end_controls_section();
