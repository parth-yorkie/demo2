<?php
/**
 * Map controls
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
	'section_map_style',
	[
		'label'     => __( 'Map', 'wpgb-elementor' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'type' => 'map',
		],
	]
);

$this->add_responsive_control(
	'map_width',
	[
		'label'      => __( 'Map Width', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min' => 1,
				'max' => 2000,
			],
			'%'  => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet fieldset'        => 'position: relative;width: {{SIZE}}{{UNIT}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-map-facet' => 'padding:0 !important;width: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_responsive_control(
	'map_height',
	[
		'label'      => __( 'Map Height', 'wpgb-elementor' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min' => 1,
				'max' => 2000,
			],
			'%'  => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .wpgb-facet fieldset'        => 'position: relative;height: {{SIZE}}{{UNIT}}',
			'{{WRAPPER}} .wpgb-facet .wpgb-map-facet' => 'padding:0 !important;height: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->end_controls_section();
