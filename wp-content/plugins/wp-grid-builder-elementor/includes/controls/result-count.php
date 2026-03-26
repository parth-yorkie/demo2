<?php
/**
 * Result count controls
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->start_controls_section(
	'section_result_count_style',
	[
		'label'     => __( 'Content', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'result_count',
		],
	]
);

$this->add_control(
	'result_count_display',
	[
		'label'     => '',
		'type'      => Controls_Manager::HIDDEN,
		'default'   => 'yes',
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'display: block',
		],
	]
);

$this->add_control(
	'result_count_alignment',
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
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'text-align: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'result_count_typography',
		'label'    => __( 'Typography', 'wpgb-elementor' ),
		'selector' => '{{WRAPPER}} .wpgb-facet .wpgb-result-count',
	]
);

$this->add_control(
	'result_count_color',
	[
		'label'     => __( 'Text Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'result_count_background_color',
	[
		'label'     => __( 'Background Color', 'wpgb-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'result_count_border',
		'label'     => __( 'Border', 'wpgb-elementor' ),
		'separator' => 'before',
		'selector'  => '{{WRAPPER}} .wpgb-facet .wpgb-result-count',
	]
);

$this->add_control(
	'result_count_border_radius',
	[
		'label'      => __( 'Border Radius', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'result_count_padding',
	[
		'label'      => __( 'Padding', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'result_count_margin',
	[
		'label'      => __( 'Margin', 'wpgb-elementor' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet .wpgb-result-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
