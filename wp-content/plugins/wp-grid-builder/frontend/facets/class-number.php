<?php
/**
 * Number facet
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder\FrontEnd\Facets;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Number
 *
 * @class WP_Grid_Builder\FrontEnd\Facets\Number
 * @since 1.0.0
 */
class Number {

	/**
	 * Min/max range values
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $range;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'wp_grid_builder/facet/response', [ $this, 'get_settings' ], 10, 3 );

	}

	/**
	 * Filter facet response to set number settings
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @param array $response Holds facet response.
	 * @param array $facet    Holds facet settings.
	 * @param array $items    Holds facet items.
	 * @return array
	 */
	public function get_settings( $response, $facet, $items ) {

		// Skip other facets or if already set.
		if ( 'number' !== $facet['type'] || isset( $facet['settings']['min'] ) ) {
			return $response;
		}

		$response['settings']['min'] = isset( $this->range->min ) ? (float) $this->range->min : 0;
		$response['settings']['max'] = isset( $this->range->max ) ? (float) $this->range->max : 0;

		return $response;

	}

	/**
	 * Query facet choices (min & max values)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @return array
	 */
	public function query_facet( $facet ) {

		global $wpdb;

		$where_clause = wpgb_get_filtered_where_clause( $facet, 'OR' );

		$this->range = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT MIN(facet_value + 0) AS min, MAX(facet_name + 0) AS max
				FROM {$wpdb->prefix}wpgb_index
				WHERE slug = %s
				AND facet_value != ''
				AND $where_clause", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$facet['slug']
			)
		);

		return [];

	}

	/**
	 * Render facet
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $facet Holds facet settings.
	 * @param object $range Holds range min/max values.
	 * @return string Facet markup.
	 */
	public function render_facet( $facet, $range ) {

		$values = array_pad( $facet['selected'], 2, '' );

		if ( 'range' === $facet['number_inputs'] ) {

			$output  = $this->render_input( $facet, $values[0], 'min' );
			$output .= $this->render_input( $facet, $values[1], 'max' );

		} else {
			$output = $this->render_input( $facet, $values[0], $facet['number_inputs'] );
		}

		if ( ! empty( $facet['submit_label'] ) ) {
			$output .= '<button type="button" class="wpgb-button wpgb-number-submit">' . esc_html( $facet['submit_label'] ) . '</button>';
		}

		return sprintf( '<div class="wpgb-number-facet">%s</div>', $output );

	}

	/**
	 * Render input
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $facet Holds facet settings.
	 * @param mixed  $value Input value.
	 * @param string $type  Input type.
	 * @return string Radio markup.
	 */
	public function render_input( $facet, $value, $type ) {

		$output = sprintf(
			'<label class="wpgb-number-label">
				<span%1$s>%2$s</span>
				<input type="number" class="wpgb-number wpgb-input" name="%3$s" placeholder="%4$s" min="%5$s" max="%6$s" step="%7$s" value="%8$s">
			</label>',
			empty( $facet['number_labels'] ) ? ' class="wpgb-sr-only"' : '',
			esc_html( $facet[ $type . '_label' ] ),
			esc_attr( $facet['slug'] ),
			esc_attr( $this->format_placeholder( $facet, $type ) ),
			(float) $this->range->min,
			(float) $this->range->max,
			(float) $facet['step'],
			is_numeric( $value ) ? (float) $value : ''
		);

		return apply_filters( 'wp_grid_builder/facet/number', $output, $facet, $this->range );

	}

	/**
	 * Format placeholder
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $facet Holds facet settings.
	 * @param string $type  Input type.
	 * @return string
	 */
	public function format_placeholder( $facet, $type ) {

		if ( empty( $facet[ $type . '_placeholder' ] ) ) {
			return '';
		}

		$placeholder = $facet[ $type . '_placeholder' ];
		$placeholder = str_replace( '[min]', $this->format_value( $facet, $this->range->min ), $placeholder );
		$placeholder = str_replace( '[max]', $this->format_value( $facet, $this->range->max ), $placeholder );

		return $placeholder;

	}

	/**
	 * Format value
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @param mixed $value Input value.
	 * @return string
	 */
	public function format_value( $facet, $value ) {

		return (
			$facet['prefix'] .
			number_format(
				(float) $value,
				(float) $facet['decimal_places'],
				(string) $facet['decimal_separator'],
				(string) $facet['thousands_separator']
			) .
			$facet['suffix']
		);
	}

	/**
	 * Query object ids (post, user, term) for selected facet values
	 *
	 * @since 1.6.0 Added compare type.
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @return array Holds queried facet object ids.
	 */
	public function query_objects( $facet ) {

		global $wpdb;

		$val = array_pad( array_values( $facet['selected'] ), 2, '' );
		$min = is_numeric( $val[0] ) ? $val[0] : ~PHP_INT_MAX;
		$max = is_numeric( $val[1] ) ? $val[1] : PHP_INT_MAX;

		switch ( $facet['number_inputs'] ) {
			case 'min':
				$max = PHP_INT_MAX;
				break;
			case 'max':
				$max = $min;
				$min = ~PHP_INT_MAX;
				break;
			case 'exact':
				$max = $min;
				break;
		}

		switch ( $facet['compare_type'] ) {
			case 'surround':
				$where  = " AND (facet_value + 0) <= '$min'";
				$where .= " AND (facet_name + 0) >= '$max'";
				break;
			case 'intersect':
				$where  = " AND (facet_value + 0) <= '$max'";
				$where .= " AND (facet_name + 0) >= '$min'";
				break;
			default:
				$where  = " AND (facet_value + 0) >= '$min'";
				$where .= " AND (facet_name + 0) <= '$max'";
		}

		return $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT object_id
				FROM {$wpdb->prefix}wpgb_index
				WHERE slug = %s
				$where", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$facet['slug']
			)
		);
	}
}
