<?php
/**
 * Select controls
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
	'section_select_style',
	[
		'label'     => __( 'Select', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'select', 'per_page', 'sort' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'select_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-select, {{WRAPPER}} .wpgb-select *',
	]
);

$this->start_controls_tabs( 'select_tabs' );

$this->start_controls_tab(
	'select_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-select, {{WRAPPER}} .wpgb-select .wpgb-select-search, {{WRAPPER}} .wpgb-select input' => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select input::placeholder'                                                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select input::-webkit-input-placeholder'                                              => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select input::-moz-placeholder'                                                       => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select input:-ms-input-placeholder'                                                   => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select input:-moz-placeholder'                                                        => 'color: {{VALUE}}',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
	]
);

$this->add_control(
	'select_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'select_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-select',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'select_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select:hover, {{WRAPPER}} .wpgb-select:hover .wpgb-select-search, {{WRAPPER}} .wpgb-select:hover input' => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select:hover input::placeholder'                                                                        => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select:hover input::-webkit-input-placeholder'                                                          => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select:hover input::-moz-placeholder'                                                                   => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select:hover input:-ms-input-placeholder'                                                               => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select:hover input:-moz-placeholder'                                                                    => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'select_hover_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select:hover' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'select_hover_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select:hover' => 'border-color: {{VALUE}}',
		],
		'condition' => [
			'select_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'select_hover_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-select:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'select_focused_tab',
	[
		'label' => __( 'Focused', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_focused_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select:focus, {{WRAPPER}} .wpgb-select.wpgb-select-focused .wpgb-select-search, {{WRAPPER}} .wpgb-select.wpgb-select-focused input' => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select.wpgb-select-focused input::placeholder'                                                                                      => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select.wpgb-select-focused input::-webkit-input-placeholder'                                                                        => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select.wpgb-select-focused input::-moz-placeholder'                                                                                 => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select.wpgb-select-focused input:-ms-input-placeholder'                                                                             => 'color: {{VALUE}}',
			'{{WRAPPER}} .wpgb-select.wpgb-select-focused input:-moz-placeholder'                                                                                  => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'select_focused_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select:focus, {{WRAPPER}} .wpgb-select.wpgb-select-focused' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'select_focused_border_color',
	[
		'label'     => __( 'Border Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select:focus, {{WRAPPER}} .wpgb-select.wpgb-select-focused' => 'border-color: {{VALUE}}',
		],
		'condition' => [
			'select_border_border!' => '',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'select_focused_box_shadow',
		'label'    => __( 'Box Shadow', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-select:focus',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'select_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-select',
	]
);

$this->add_control(
	'select_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'select_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'select_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_select_icon_style',
	[
		'label'     => __( 'Select Icon', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'select', 'per_page', 'sort' ],
		],
	]
);

$this->add_control(
	'select_icon',
	[
		'label'     => __( 'Hide Icon', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-toggle, {{WRAPPER}} .wpgb-facet .wpgb-select-separator' => 'display: none',
		],
	]
);

$this->add_control(
	'select_separator',
	[
		'label'     => __( 'Hide Separator', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-separator' => 'display: none',
		],
		'condition' => [
			'select_icon!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'select_icon_size',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-select-toggle' => '--wpgb-select-icon-scale: calc({{SIZE}}/20);transform: scale(var(--wpgb-select-icon-scale));',
		],
		'condition'  => [
			'select_icon!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'select_icon_horizontal_offset',
	[
		'label'      => __( 'Icon Horizontal Offset', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => -50,
				'max' => 50,
			],
		],
		'selectors'  => [
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-select + .wpgb-select-controls' => 'right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-select + .wpgb-select-controls'      => 'left: {{SIZE}}{{UNIT}};',
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-select .wpgb-select-controls'   => 'margin-right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-select .wpgb-select-controls'        => 'margin-left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'select_icon!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'select_separator_horizontal_offset',
	[
		'label'      => __( 'Separator Horizontal Offset', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => -50,
				'max' => 50,
			],
		],
		'selectors'  => [
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-select-separator' => 'margin-right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-select-separator'       => 'margin-left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'select_separator!' => 'yes',
			'select_icon!'      => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'select_icon_tabs',
	[
		'condition' => [
			'select_icon!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'select_icon_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_icon_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-toggle' => 'color: {{VALUE}};fill: {{VALUE}}',
		],
	]
);

$this->add_control(
	'select_separator_color',
	[
		'label'     => __( 'Separator Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-separator' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'select_separator!' => 'yes',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'select_icon_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_icon_hover_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet select.wpgb-select:hover + .wpgb-select-controls .wpgb-select-toggle, {{WRAPPER}} .wpgb-facet .wpgb-select-toggle:hover' => 'color: {{VALUE}};fill: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'section_select_loader_style',
	[
		'label'     => __( 'Select Loader', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'select',
		],
	]
);

$this->add_control(
	'select_loader_info',
	[
		'type'            => Controls_Manager::RAW_HTML,
		'raw'             => __( 'These settings are only applied for the asynchronous combobox (facet settings).', 'wpgb-elementor' ),
		'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		'html'            => '',
	]
);

$this->add_control(
	'select_loader',
	[
		'label'     => __( 'Hide Loader', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-loader' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'select_loader_size',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-select-loader' => '--wpgb-select-loader-scale: calc({{SIZE}}/30);transform: translateZ(0) scale(var(--wpgb-select-loader-scale));',
		],
		'condition'  => [
			'select_loader!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'select_loader_horizontal_offset',
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
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-select-loader' => 'margin-right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-select-loader'       => 'margin-left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'select_loader!' => 'yes',
		],
	]
);

$this->add_control(
	'select_loader_color',
	[
		'label'     => __( 'Loader Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-loader span' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'select_loader!' => 'yes',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_selected_values_style',
	[
		'label'     => __( 'Selected Values', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'select' ],
		],
	]
);

$this->add_control(
	'selected_values_info',
	[
		'type'            => Controls_Manager::RAW_HTML,
		'raw'             => __( 'These settings are only applied for the combobox (facet settings).', 'wpgb-elementor' ),
		'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
	]
);

$this->start_controls_tabs( 'selected_values_tabs' );

$this->start_controls_tab(
	'selected_values_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'selected_values_color',
	[
		'label'     => __( 'Label Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'selected_values_background_color',
	[
		'label'     => __( 'Label Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'selected_values_cross_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value .wpgb-select-remove' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'selected_values_cross_background_color',
	[
		'label'     => __( 'Icon Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value .wpgb-select-remove' => 'background-color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'selected_values_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'selected_values_hover_color',
	[
		'label'     => __( 'Label Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value:hover' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'selected_values_hover_background_color',
	[
		'label'     => __( 'Label Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value:hover' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'selected_values_hover_cross_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value .wpgb-select-remove:hover' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'selected_values_hover_cross_background_color',
	[
		'label'     => __( 'Icon Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-select-value .wpgb-select-remove:hover' => 'background-color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'section_select_clear_button_style',
	[
		'label'     => __( 'Clear Button', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'select',
		],
	]
);

$this->add_control(
	'select_clear_button_info',
	[
		'type'            => Controls_Manager::RAW_HTML,
		'raw'             => __( 'These settings are only applied for the combobox (facet settings).', 'wpgb-elementor' ),
		'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
	]
);

$this->add_control(
	'select_clear_button',
	[
		'label'     => __( 'Hide Button', 'wpgb-elementor' ),
		'type'      => Controls_Manager::SWITCHER,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-clear' => 'display: none',
		],
	]
);

$this->add_responsive_control(
	'select_clear_button_size',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-select-clear' => '--wpgb-select-clear-scale: calc({{SIZE}}/20);transform: scale(var(--wpgb-select-clear-scale));',
		],
		'condition'  => [
			'select_clear_button!' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'select_clear_button_horizontal_offset',
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
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet .wpgb-select-clear' => 'margin-right: {{SIZE}}{{UNIT}};',
			'body.rtl {{WRAPPER}} .wpgb-facet .wpgb-select-clear'       => 'margin-left: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'select_clear_button!' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'select_clear_button_tabs',
	[
		'condition' => [
			'select_clear_button!' => 'yes',
		],
	]
);

$this->start_controls_tab(
	'select_clear_button_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_clear_button_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-clear' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'select_clear_button_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'select_clear_button_hover_color',
	[
		'label'     => __( 'Icon Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-select-clear:hover, {{WRAPPER}} .wpgb-facet .wpgb-select-clear:focus' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
