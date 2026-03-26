<?php
/**
 * Facet controls
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
	'facet',
	[
		'label'       => __( 'Select a facet', 'wpgb-elementor' ),
		'label_block' => true,
		'type'        => Controls_Manager::SELECT2,
		'options'     => $this->get_facets(),
		'default'     => '',
	]
);

$this->add_control(
	'grid',
	[
		'label'       => __( 'Select a grid or widget to filter', 'wpgb-elementor' ),
		'label_block' => true,
		'type'        => Controls_Manager::SELECT2,
		'options'     => $this->get_grids(),
		'default'     => '',
	]
);

$this->add_control(
	'widget_id',
	[
		'label'       => __( 'Or enter a Widget ID to filter', 'wpgb-elementor' ),
		'label_block' => true,
		'type'        => Controls_Manager::TEXT,
		'default'     => '',
		'condition'   => [
			'facet!'   => '',
			'document' => 'popup',
		],
	]
);

$this->add_control(
	'widget_id_notice',
	[
		'type'            => Controls_Manager::RAW_HTML,
		'raw'             => __( 'The widget ID can be found when editing a widget under WP Grid Builder tab. It allows to filter a widget that is not present in the editor but on the frontend.', 'wpgb-elementor' ),
		'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		'condition'       => [
			'facet!'   => '',
			'document' => 'popup',
		],
	]
);

$this->add_control(
	'type',
	[
		'type'    => Controls_Manager::HIDDEN,
		'default' => '',
	]
);

$this->add_control(
	'document',
	[
		'type'    => Controls_Manager::HIDDEN,
		'default' => '',
	]
);

$this->add_control(
	'fieldset',
	[
		'type'      => Controls_Manager::HIDDEN,
		'default'   => 0,
		'selectors' => [
			'{{WRAPPER}} .wpgb-facet > fieldset:last-child' => 'margin-bottom:0',
		],
	]
);

$this->end_controls_section();
