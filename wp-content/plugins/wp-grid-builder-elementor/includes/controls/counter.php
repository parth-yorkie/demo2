<?php
/**
 * Choice counter controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_choice_counter_style',
	[
		'label'     => __( 'Choice Counter', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => [ 'checkbox', 'radio', 'button', 'hierarchy', 'rating', 'color', 'az_index' ],
		],
	]
);

$this->add_control(
	'choice_counter_alignment',
	[
		'label'      => __( 'Alignment', 'wpgb-elementor' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => [
			''              => [
				'title' => __( 'Left', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-left',
			],
			'space-between' => [
				'title' => __( 'Right', 'wpgb-elementor' ),
				'icon'  => 'fa fa-align-right',
			],
		],
		'selectors'  => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"]' => 'display: -ms-flexbox;display: flex;justify-content:{{VALUE}}',
		],
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'type',
							'operator' => 'in',
							'value'    => [ 'checkbox', 'radio', 'hierarchy', 'rating' ],
						],
						[
							'name'     => 'choices_direction',
							'operator' => '!==',
							'value'    => 'row',
						],
					],
				],
				[
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'type',
							'operator' => '===',
							'value'    => 'button',
						],
						[
							'name'     => 'choices_direction',
							'operator' => '===',
							'value'    => 'column',
						],
					],
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'choice_counter_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} li [class^="wpgb-"][class$="-label"] span',
	]
);

$this->start_controls_tabs( 'choice_counter_tabs' );

$this->start_controls_tab(
	'choice_counter_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'choice_counter_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"] span' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'choice_counter_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);


$this->add_control(
	'choice_counter_hover_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} [role="button"][aria-pressed]:hover [class^="wpgb-"][class$="-label"] span' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'choice_counter_selected_tab',
	[
		'label' => __( 'Selected', 'wpgb-elementor' ),
	]
);


$this->add_control(
	'choice_counter_selected_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} [role="button"][aria-pressed="true"] [class^="wpgb-"][class$="-label"] span' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_responsive_control(
	'choice_counter_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} li [class^="wpgb-"][class$="-label"] span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
