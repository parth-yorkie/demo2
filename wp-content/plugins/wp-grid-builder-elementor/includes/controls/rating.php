<?php
/**
 * Rating controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_rating_style',
	[
		'label'     => __( 'Stars', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'rating',
		],
	]
);

$this->add_responsive_control(
	'rating_size',
	[
		'label'      => __( 'Stars Size', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 10,
				'max' => 250,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-rating:not(.wpgb-rating-reset) .wpgb-rating-control' => 'width: {{SIZE}}{{UNIT}};height: calc({{SIZE}}{{UNIT}} / 5 );',
		],
	]
);

$this->start_controls_tabs( 'rating_tabs' );

$this->start_controls_tab(
	'rating_idle_tab',
	[
		'label' => __( 'Normal', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'rating_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-rating:not(.wpgb-rating-reset) .wpgb-rating-control' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'rating_hover_tab',
	[
		'label' => __( 'Hover', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'rating_hover_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-rating[aria-pressed]:not([tabindex="-1"]):not(.wpgb-rating-reset):hover .wpgb-rating-svg' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'rating_pressed_tab',
	[
		'label' => __( 'Pressed', 'wpgb-elementor' ),
	]
);

$this->add_control(
	'rating_pressed_color',
	[
		'label'     => __( 'Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-rating[aria-pressed="true"]:not(.wpgb-rating-reset) .wpgb-rating-svg' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_responsive_control(
	'rating_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-rating:not(.wpgb-rating-reset) .wpgb-rating-control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
