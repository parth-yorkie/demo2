<?php
/**
 * Grid controls
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
	'content_section',
	[
		'label' => __( 'Content', 'wpgb-elementor' ),
		'tab'   => Controls_Manager::TAB_CONTENT,
	]
);

$this->add_control(
	'grid',
	[
		'label'       => __( 'Select a grid', 'wpgb-elementor' ),
		'label_block' => true,
		'type'        => Controls_Manager::SELECT2,
		'options'     => $this->get_grids(),
		'default'     => '',
	]
);

if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {

	$this->add_control(
		'is_main_query',
		[
			'label' => __( 'Archive Template', 'wpgb-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
		]
	);


	$this->add_control(
		'archive_notice',
		[
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'The editor preview might look different from the frontend. Please make sure to check the frontend.', 'wpgb-elementor' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'is_main_query' => 'yes',
			],
		]
	);

}

$this->end_controls_section();
