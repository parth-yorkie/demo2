<?php
/**
 * Input controls
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
	'section_input_style',
	[
		'label'     => __( 'Input', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'date', 'search', 'number', 'autocomplete', 'geolocation' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'input_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-facet .wpgb-input',
	]
);

$this->start_controls_tabs( 'input_tabs' );

$this->start_controls_tab(
	'input_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-facet .wpgb-input'                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input::placeholder'               => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input::-webkit-input-placeholder' => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input::-moz-placeholder'          => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:-ms-input-placeholder'      => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:-moz-placeholder'           => 'color: {{VALUE}}',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
	]
);

$this->add_control(
	'input_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'input_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-facet .wpgb-input',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'input_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover'                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover::placeholder'               => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover::-webkit-input-placeholder' => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover::-moz-placeholder'          => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover:-ms-input-placeholder'      => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover:-moz-placeholder'           => 'color: {{VALUE}}',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
	]
);

$this->add_control(
	'input_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'input_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input:hover' => 'border-color: {{VALUE}}',
		],
		'condition' => [
			'input_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'input_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-facet .wpgb-input:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'input_focused_tab',
	[
		'label' => __( 'Focused', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_focused_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus'                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus::placeholder'               => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus::-moz-placeholder'          => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus:-ms-input-placeholder'      => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus:-moz-placeholder'           => 'color: {{VALUE}}',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
	]
);

$this->add_control(
	'input_focused_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'input_focused_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input:focus' => 'border-color: {{VALUE}}',
		],
		'condition' => [
			'input_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'input_focused_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-facet .wpgb-input:focus',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'input_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-facet .wpgb-input',
	]
);

$this->add_control(
	'input_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'input_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'input_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_input_icon_style',
	[
		'label'     => __( 'Input Icon', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'date', 'search', 'autocomplete', 'geolocation' ],
		],
	]
);

$this->add_control(
	'input_icon',
	[
		'label'     => __( 'Hide Icon', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input-icon' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'input_icon_size',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-input-icon' => '--wpgb-input-icon-scale: calc({{SIZE}}/20);transform: scale(var(--wpgb-input-icon-scale));',
		],
		'condition'  => [
			'input_icon!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'input_icon_horizontal_offset',
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
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-input-icon' => 'left: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-input-icon'      => 'right: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'input_icon!' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'input_icon_tabs',
	[
		'condition' => [
			'input_icon!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'input_icon_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_icon_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-input-icon' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'input_icon_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_icon_hover_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet label:hover .wpgb-input-icon' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'section_input_loader_style',
	[
		'label'     => __( 'Input Loader', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'autocomplete', 'geolocation' ],
		],
	]
);

$this->add_control(
	'input_loader',
	[
		'label'     => __( 'Hide Loader', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .acplt-loader' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'input_loader_size',
	[
		'label'      => __( 'Loader Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 50,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .acplt-loader' => '--wpgb-input-loader-scale: calc({{SIZE}}/30);transform: translateZ(0) scale(var(--wpgb-input-loader-scale));',
		],
		'condition'  => [
			'input_loader!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'input_loader_horizontal_offset',
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
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .acplt-loader' => 'right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .acplt-loader'       => 'left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'input_loader!' => 'yes',
		],
	]
);

$this->add_control(
	'input_loader_color',
	[
		'label'     => __( 'Loader Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .acplt-loader span' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'input_loader!' => 'yes',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_input_clear_button_style',
	[
		'label'     => __( 'Clear Button', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'date', 'search', 'autocomplete', 'geolocation' ],
		],
	]
);

$this->add_control(
	'input_clear_button',
	[
		'label'     => __( 'Hide Button', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-clear-button, {{WRAPPER}} .wpgb-facet .acplt-clear' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'input_clear_button_size',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-clear-button, {{WRAPPER}} .wpgb-facet .acplt-clear' => '--wpgb-input-clear-scale: calc({{SIZE}}/20);transform: scale(var(--wpgb-input-clear-scale));',
		],
		'condition'  => [
			'input_clear_button!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'input_clear_button_horizontal_offset',
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
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-clear-button, body:not(.rtl) {{WRAPPER}} .wpgb-facet .acplt-clear' => 'right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-clear-button, body.rtl {{WRAPPER}} .wpgb-facet .acplt-clear'       => 'left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'input_clear_button!' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'input_clear_button_tabs',
	[
		'condition' => [
			'input_clear_button!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'input_clear_button_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_clear_button_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-clear-button, {{WRAPPER}} .wpgb-facet .acplt-clear' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'input_clear_button_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'input_clear_button_hover_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-clear-button:hover, {{WRAPPER}} .wpgb-facet .wpgb-clear-button:focus, {{WRAPPER}} .wpgb-facet .acplt-clear:hover, {{WRAPPER}} .wpgb-facet .acplt-clear:focus' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
