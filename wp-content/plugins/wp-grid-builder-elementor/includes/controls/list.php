<?php
/**
 * List controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Stack;
use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$desktop = Controls_Stack::RESPONSIVE_DESKTOP;
$tablet  = Controls_Stack::RESPONSIVE_TABLET;
$mobile  = Controls_Stack::RESPONSIVE_MOBILE;

$this->start_controls_section(
	'section_choices_style',
	[
		'label'     => __( 'List', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'checkbox', 'radio', 'button', 'hierarchy', 'rating', 'color', 'selection', 'az_index' ],
		],
	]
);

$this->add_responsive_control(
	'choices_direction',
	[
		'label'       => __( 'Layout', 'wpgb-elementor' ),
		'type'        => Controls_Manager::CHOOSE,
		'label_block' => false,
		'default'     => '',
		'options'     => [
			'row'    => [
				'title' => _x( 'Horizontal', 'Layout control value', 'wpgb-elementor' ),
				'icon'  => 'fa fa-ellipsis-h',
			],
			'column' => [
				'title' => _x( 'Vertical', 'Layout control value', 'wpgb-elementor' ),
				'icon'  => 'fa fa-bars',
			],
		],
		'selectors'   => [
			'{{WRAPPER}} .wpgb-facet ul:first-child, {{WRAPPER}} .wpgb-facet ul:first-child + ul, {{WRAPPER}} .wpgb-facet li[aria-expanded="true"] > ul, {{WRAPPER}} .wpgb-facet li:not([aria-expanded]) > ul' => 'display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;flex-direction: {{VALUE}}',
			'{{WRAPPER}} .wpgb-facet ul.wpgb-expanded li[hidden]'                                                                                                                                              => 'display: block',
		],
		'condition'   => [
			'type!' => 'color',
		],
	]
);

$this->add_responsive_control(
	'choices_alignment',
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
		],
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet ul:first-child, {{WRAPPER}} .wpgb-facet ul:first-child + ul' => 'justify-content: {{VALUE}};',
		],
		'condition' => [
			'choices_direction' => 'row',
		],
	]
);

$this->add_responsive_control(
	'choices_no_vertical_spacing',
	[
		'label'           => '',
		'type'            => Controls_Manager::HIDDEN,
		'desktop_default' => 12,
		'tablet_default'  => 12,
		'mobile_default'  => 12,
		'selectors'       => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-facet ul li'                                                       => 'margin: 0 0 12px 0',
			'{{WRAPPER}} .wpgb-facet ul ul'                                                       => 'margin-top: 12px',
			'{{WRAPPER}} .wpgb-facet ul ul li:last-child'                                         => 'margin-bottom: 0',
			'{{WRAPPER}} .wpgb-facet ul:first-child + ul'                                         => 'margin-top: 12px',
			'{{WRAPPER}} .wpgb-facet ul:first-child, {{WRAPPER}} .wpgb-facet ul:first-child + ul' => 'margin-bottom: -12px',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
		'device_args'     => [
			$desktop => [
				'condition' => [
					'type!'                          => 'button',
					'choices_direction'              => 'row',
					'choices_vertical_spacing[size]' => '',
				],
			],
			$tablet  => [
				'condition' => [
					// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
					'type!'                                          => 'button',
					'choices_direction!'                             => 'row',
					'choices_direction_' . $tablet                   => 'row',
					'choices_vertical_spacing_' . $tablet . '[size]' => '',
					// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
				],
			],
			$mobile  => [
				'condition' => [
					// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
					'type!'                                          => 'button',
					'choices_direction!'                             => 'row',
					'choices_direction_' . $tablet . '!'             => 'row',
					'choices_direction_' . $mobile                   => 'row',
					'choices_vertical_spacing_' . $mobile . '[size]' => '',
					// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
				],
			],
		],
	]
);

$this->add_responsive_control(
	'choices_vertical_spacing',
	[
		'label'      => __( 'Vertical Spacing', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 50,
			],
		],
		'selectors'  => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'{{WRAPPER}} .wpgb-facet ul li'                                                       => 'margin: 0 0 {{SIZE}}{{UNIT}} 0',
			'{{WRAPPER}} .wpgb-facet ul ul'                                                       => 'margin-top: {{SIZE}}{{UNIT}}',
			'{{WRAPPER}} .wpgb-facet ul ul li:last-child'                                         => 'margin-bottom: 0',
			'{{WRAPPER}} .wpgb-facet ul:first-child + ul'                                         => 'margin-top: {{SIZE}}{{UNIT}}',
			'{{WRAPPER}} .wpgb-facet ul:first-child, {{WRAPPER}} .wpgb-facet ul:first-child + ul' => 'margin-bottom: -{{SIZE}}{{UNIT}}',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
	]
);

$this->add_responsive_control(
	'choices_no_horizontal_spacing',
	[
		'label'           => '',
		'type'            => Controls_Manager::HIDDEN,
		'desktop_default' => 12,
		'tablet_default'  => 12,
		'mobile_default'  => 12,
		'selectors'       => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet ul li'                              => 'margin-right: 12px',
			'body.rtl {{WRAPPER}} .wpgb-facet ul li'                                    => 'margin-left: 12px',
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet ul li[aria-expanded] li:last-child' => 'margin-right: 0',
			'body.rtl {{WRAPPER}} .wpgb-facet ul li[aria-expanded] li:last-child'       => 'margin-left: 0',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
		'device_args'     => [
			$desktop => [
				'condition' => [
					'type!'                            => 'button',
					'choices_direction'                => 'row',
					'choices_horizontal_spacing[size]' => '',
				],
			],
			$tablet  => [
				'condition' => [
					// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
					'type!'                                            => 'button',
					'choices_direction!'                               => 'row',
					'choices_direction_' . $tablet                     => 'row',
					'choices_horizontal_spacing_' . $tablet . '[size]' => '',
					// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
				],
			],
			$mobile  => [
				'condition' => [
					// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
					'type!'                                            => 'button',
					'choices_direction!'                               => 'row',
					'choices_direction_' . $tablet . '!'               => 'row',
					'choices_direction_' . $mobile                     => 'row',
					'choices_horizontal_spacing_' . $mobile . '[size]' => '',
					// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
				],
			],
		],
	]
);

$this->add_responsive_control(
	'choices_horizontal_spacing',
	[
		'label'       => __( 'Horizontal Spacing', 'wpgb-elementor' ),
		'type'        => Controls_Manager::SLIDER,
		'size_units'  => [ 'px' ],
		'range'       => [
			'px' => [
				'min' => 0,
				'max' => 50,
			],
		],
		'selectors'   => [
			// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet ul li'                              => 'margin-right: {{SIZE}}{{UNIT}}',
			'body.rtl {{WRAPPER}} .wpgb-facet ul li'                                    => 'margin-left: {{SIZE}}{{UNIT}}',
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet ul li[aria-expanded] li:last-child' => 'margin-right: 0',
			'body.rtl {{WRAPPER}} .wpgb-facet ul li[aria-expanded] li:last-child'       => 'margin-left: 0',
			// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned, WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		],
		'device_args' => [
			$desktop => [
				'condition' => [
					'choices_direction' => 'row',
				],
			],
			$tablet  => [
				'condition' => [
					'choices_direction_' . $tablet => 'row',
				],
			],
			$mobile  => [
				'condition' => [
					'choices_direction_' . $mobile => 'row',
				],
			],
		],
	]
);

$this->add_responsive_control(
	'choices_children_offset',
	[
		'label'      => __( 'Children Offset', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 50,
			],
		],
		'condition'  => [
			'type' => 'checkbox',
		],
		'selectors'  => [
			'body:not(.rtl) {{WRAPPER}} .wpgb-facet ul ul' => 'margin-left: {{SIZE}}{{UNIT}}',
			'body.rtl {{WRAPPER}} .wpgb-facet ul ul'       => 'margin-right: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->end_controls_section();
