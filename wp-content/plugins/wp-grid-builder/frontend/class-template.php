<?php
/**
 * Template
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder\FrontEnd;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle custom template
 *
 * @class WP_Grid_Builder\FrontEnd\Template
 * @since 1.0.0
 */
final class Template {

	/**
	 * Holds template settings
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * WordPress query
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var \WP_Query instance
	 */
	protected $query;

	/**
	 * Holds defaults settings
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var array
	 */
	protected $defaults = [
		'id'                 => 'template',
		'lang'               => '',
		'class'              => '',
		'className'          => '',
		'source_type'        => 'post_type',
		'is_template'        => true,
		'is_main_query'      => false,
		'query_args'         => [],
		'render_callback'    => null,
		'noresults_callback' => null,
	];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string|array $settings Holds template settings.
	 */
	public function __construct( $settings = [] ) {

		$this->settings = $this->parse_settings( $settings );

	}

	/**
	 * Parse template settings
	 *
	 * @since 1.7.9 Unset callable arguments from injected template settings.
	 * @since 1.4.0
	 * @access public
	 *
	 * @param string|array $settings Holds template settings.
	 * @return array
	 */
	public function parse_settings( $settings ) {

		$templates = apply_filters( 'wp_grid_builder/templates', [] );

		// Template can accept an ID as settings.
		if ( is_scalar( $settings ) ) {
			$settings = [ 'id' => $settings ];
		}

		// If no template is found.
		if ( ! isset( $settings['id'], $templates[ $settings['id'] ] ) ) {
			return [];
		}

		// Unset callable arguments for security reasons.
		unset(
			$settings['query_args'],
			$settings['render_callback'],
			$settings['noresults_callback']
		);

		// Merge template settings.
		$settings = wp_parse_args( (array) $templates[ $settings['id'] ], $settings );
		$settings = wp_parse_args( $settings, $this->defaults );
		$settings = apply_filters( 'wp_grid_builder/template/args', $settings );

		// To properly detect and filter template.
		$settings['id'] = trim( $settings['id'] );

		return (object) $settings;

	}

	/**
	 * Render template
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {

		if ( ! $this->maybe_handle() ) {
			return;
		}

		if ( ! $this->can_render() ) {
			return;
		}

		$this->query();
		$this->output();

		add_filter( 'wp_grid_builder/frontend/add_inline_script', [ $this, 'inline_script' ] );

	}

	/**
	 * Refresh template
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function refresh() {

		if ( ! $this->maybe_handle() ) {
			return;
		}

		$this->query();

		// We do not render if it's a shadow template.
		if ( ! empty( $this->settings->is_shadow ) ) {
			return;
		}

		if ( ! $this->can_render() ) {
			return;
		}

		$this->loop();

	}

	/**
	 * Check if we can hanlde template
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function maybe_handle() {

		if ( empty( $this->settings ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Check if we can render template
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function can_render() {

		if ( is_callable( $this->settings->render_callback ) ) {
			return true;
		}

		printf(
			'<pre class="wpgb-error">%s</pre>',
			esc_html_e( "Invalid \"render_callback\". The callback must be a function or a class method name.\nThe callback must be declared in your functions.php file of your theme or in a plugin.", 'wp-grid-builder' )
		);

		return false;

	}

	/**
	 * Run query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function query() {

		if ( ! $this->maybe_handle() ) {
			return;
		}

		$query_args = $this->get_query_args();

		switch ( $this->settings->source_type ) {
			case 'user':
				$this->query = new \WP_User_Query( $query_args );
				break;
			case 'term':
				$this->query = new \WP_Term_Query( $query_args );
				break;
			default:
				if ( $this->settings->is_main_query ) {

					$this->query = $this->main_query();
					// Prevent to inject hidden template in archive pages.
					$this->query->set( 'wpgb_inject', false );

				} else {
					$this->query = new \WP_Query( $query_args );
				}
		}
	}

	/**
	 * Get query args
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array Holds query arguments.
	 */
	public function get_query_args() {

		$query_args = $this->settings->query_args;

		// Query args can be returned from a function/method.
		// It prevents performance issues if some query args are dynamics.
		if ( is_callable( $query_args ) ) {
			$query_args = call_user_func( $query_args );
		}

		if ( ! is_array( $query_args ) ) {
			return [];
		}

		$query_args['wp_grid_builder'] = $this->settings->id;

		return $query_args;

	}

	/**
	 * Get/run main WP query
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return object
	 */
	public function main_query() {

		global $wp_query;

		if ( wpgb_doing_ajax() && ! empty( $this->settings->main_query ) ) {

			// Turns off SQL_CALC_FOUND_ROWS even when limits are present.
			$this->settings->main_query['no_found_rows'] = true;
			// Add language to prevent issue when querying asynchronously.
			$this->settings->main_query['lang'] = $this->settings->lang;
			// Add WP Grid Builder to query args.
			$this->settings->main_query['wp_grid_builder'] = $this->settings->id;

			return new \WP_Query( $this->settings->main_query );

		} elseif ( is_main_query() && ! is_admin() ) {
			return $wp_query;
		}
	}

	/**
	 * Loop through queried posts/users/temrs
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function loop() {

		switch ( $this->settings->source_type ) {
			case 'user':
				$results = $this->user_loop();
				break;
			case 'term':
				$results = $this->term_loop();
				break;
			default:
				$results = $this->post_loop();
		}

		if ( ! $results ) {
			$this->no_results();
		}
	}

	/**
	 * Display no results message
	 *
	 * @since 1.1.5 Disable no results if callback set to false.
	 * @since 1.0.0
	 * @access public
	 */
	public function no_results() {

		$callback = $this->settings->noresults_callback;

		if ( false === $callback ) {
			return;
		}

		if ( is_callable( $callback ) ) {
			call_user_func( $callback );
		} else {
			printf(
				'<p class="wpgb-noresults">%s</p>',
				esc_html__( 'Sorry, no results match your search criteria.', 'wp-grid-builder' )
			);
		}
	}

	/**
	 * Loop through posts
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function post_loop() {

		if ( ! $this->query->have_posts() ) {
			return false;
		}

		while ( $this->query->have_posts() ) {

			global $post;

			$this->query->the_post();
			call_user_func( $this->settings->render_callback, $post );

		}

		wp_reset_postdata();

		return true;

	}

	/**
	 * Loop through users
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function user_loop() {

		$users = $this->query->get_results();

		if ( empty( $users ) ) {
			return false;
		}

		foreach ( $users as $user ) {
			call_user_func( $this->settings->render_callback, $user );
		}

		return true;

	}

	/**
	 * Loop through terms
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function term_loop() {

		$terms = $this->query->get_terms();

		if ( empty( $terms ) ) {
			return false;
		}

		foreach ( $terms as $term ) {
			call_user_func( $this->settings->render_callback, $term );
		}

		return true;

	}

	/**
	 * Output template
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function output() {

		$tag_name = apply_filters( 'wp_grid_builder/layout/wrapper_tag', 'div', $this->settings );

		echo '<!-- Gridbuilder ᵂᴾ Plugin (https://wpgridbuilder.com) -->';
		echo '<' . tag_escape( $tag_name ) . ' class="' . esc_attr( $this->get_classes() ) . '" data-options="' . esc_attr( $this->get_options() ) . '">';
		$this->loop();
		echo '</' . tag_escape( $tag_name ) . '>';

	}

	/**
	 * Get class names
	 *
	 * @since 1.0.4 Allow multiple custom class names.
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_classes() {

		$classes  = 'wp-grid-builder wpgb-template';
		$classes .= ' ' . sanitize_html_class( 'wpgb-grid-' . $this->settings->id );
		$classes .= ' ' . Helpers::sanitize_html_classes( $this->settings->className );
		$classes .= ' ' . Helpers::sanitize_html_classes( $this->settings->class );

		return $classes;

	}

	/**
	 * Get JS options.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_options() {

		$options = [];

		foreach ( $this->defaults as $key => $val ) {

			// To be compliant with JS syntax.
			$js_key = ucwords( str_replace( '_', ' ', $key ) );
			$js_key = lcfirst( str_replace( ' ', '', $js_key ) );

			$options[ $js_key ] = $this->settings->{$key};

		}

		return $this->format_options( $options );

	}

	/**
	 * Format JS options to localize in HTML attribute.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param array $options Hold template settings.
	 * @return string
	 */
	public function format_options( $options ) {

		$options = array_filter( $options );
		$allowed = [ 'id', 'isTemplate', 'isMainQuery' ];
		$options = array_intersect_key( $options, array_flip( $allowed ) );

		return wp_json_encode( $options );

	}

	/**
	 * Add inline javascript code to instantiate template
	 *
	 * @since 1.1.5 Added wpgb.loaded event to support defer and async scripts.
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $script Inline script.
	 * @return string
	 */
	public function inline_script( $script ) {

		$class = sanitize_html_class( 'wpgb-grid-' . $this->settings->id );

		$script .= 'window.addEventListener(\'wpgb.loaded\',function(){';
		$script .= 'var template = document.querySelector(' . wp_json_encode( '.wpgb-template.' . $class . ':not([data-instance])' ) . ');';
		$script .= 'if(template){';
		$script .= 'var wpgb = WP_Grid_Builder.instantiate(template);';
		$script .= 'wpgb.init && wpgb.init()';
		$script .= '}});';

		return apply_filters( 'wp_grid_builder/template/inline_script', $script );

	}
}
