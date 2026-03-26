<?php
/**
 * Base
 *
 * @package   WP Grid Builder - Elementor
 * @author    Loïc Blascos
 * @copyright 2019-2023 Loïc Blascos
 */

namespace WP_Grid_Builder_Elementor\Includes\Providers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Providers base
 *
 * @class WP_Grid_Builder_Elementor\Includes\Providers\Base
 * @since 1.0.0
 */
abstract class Base {

	/**
	 * Widget to handle
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $widget;

	/**
	 * Widget settings
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $settings;

	/**
	 * Current post ID
	 *
	 * @since 1.0.0
	 * @access public
	 * @var integer
	 */
	public $post_id;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget  Elementor raw content data.
	 * @param integer     $post_id  The post id.
	 * @param array       $settings Holds template settings.
	 */
	public function __construct( $widget, $post_id = 0, $settings = [] ) {

		$this->widget   = $widget;
		$this->post_id  = $post_id;
		$this->settings = $settings;

	}

	/**
	 * Render provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {

		$this->inline_options();
		$this->apply_hooks();

	}

	/**
	 * Refresh provider
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function refresh() {

		ob_start();

		$this->single_post();
		$this->unset_offset();
		$this->before_render();
		$this->widget->render_content();

		return ob_get_clean();

	}

	/**
	 * Apply hooks
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function apply_hooks() {

		$this->unset_offset();
		$this->before_render();

		add_filter( 'wp_grid_builder/frontend/add_inline_script', [ $this, 'inline_script' ] );
		add_filter( 'elementor/widget/render_content', [ $this, 'render_content' ] );

	}

	/**
	 * Render widget content
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $content Widget content.
	 * @return string
	 */
	public function render_content( $content ) {

		remove_filter( 'elementor/widget/render_content', [ $this, 'render_content' ] );
		$this->after_render();

		return $content;

	}

	/**
	 * Get widget id
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_id() {

		return 'elementor-element-' . $this->widget->get_id();

	}

	/**
	 * Get post id
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_post_id() {

		return $this->post_id;

	}

	/**
	 * Get CSS selector
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_selector() {

		return wp_json_encode( '.wpgb-template.' . sanitize_html_class( $this->get_id() ) . ':not([data-instance])' );

	}

	/**
	 * Inline widget options to wrapper
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function inline_options() {

		$this->widget->add_render_attribute(
			'_wrapper',
			[
				'class'        => [ 'wp-grid-builder', 'wpgb-template' ],
				'data-options' => wp_json_encode(
					wp_parse_args(
						[
							'id'         => $this->get_id(),
							'postId'     => $this->get_post_id(),
							'isTemplate' => 'Elementor',
						],
						$this->get_options()
					)
				),
			]
		);
	}

	/**
	 * Add inline javascript code to instantiate element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $script Inline script.
	 * @return string
	 */
	public function inline_script( $script ) {

		$script .= 'window.addEventListener(\'wpgb.loaded\',function(){';
		$script .= 'var template=document.querySelector(' . $this->get_selector() . ');';
		$script .= 'if(template){';
		$script .= 'var wpgb=WP_Grid_Builder.instantiate(template);';
		$script .= 'wpgb.init&&wpgb.init();';
		$script .= 'var el=window.elementorFrontend;';
		$script .= $this->get_script();
		$script .= '}});';

		return apply_filters( 'wp_grid_builder/template/inline_script', $script );

	}

	/**
	 * Simulate single post page object
	 *
	 * @since 1.1.1
	 * @access public
	 */
	public function single_post() {

		global $post;

		if ( ! wpgb_doing_ajax() ) {
			return;
		}

		if ( ! empty( $this->settings['main_query'] ) ) {
			return;
		}

		$post_id = url_to_postid( wp_get_referer() );

		if ( empty( $post_id ) ) {
			return;
		}

		$post = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	}

	/**
	 * Fix offset conflict with Elementor query
	 *
	 * @since 1.0.3
	 * @access public
	 */
	public function unset_offset() {

		$offset = $this->widget->get_settings( 'posts_offset' );

		if ( empty( $offset ) ) {
			return;
		}

		$this->widget->set_settings( 'wpgb_offset', $offset );
		$this->widget->set_settings( 'posts_offset', 0 );

	}

	/**
	 * Get post IDs not in
	 *
	 * @since 1.0.3
	 * @access public
	 *
	 * @param array $query_vars Holds query arguments.
	 * @return array
	 */
	public function get_post__not_in( $query_vars ) {

		$offset = $this->widget->get_settings( 'wpgb_offset' );

		if ( empty( $offset ) ) {
			return [];
		}

		return array_merge(
			(array) $this->widget->get_settings( 'posts_exclude_ids' ),
			$this->query_offset_posts( $query_vars, $offset )
		);
	}

	/**
	 * Check if this is the current query
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function is_current_query() {

		return (
			'current_query' === $this->widget->get_settings( 'query_post_type' ) ||
			'current_query' === $this->widget->get_settings( 'posts_post_type' ) ||
			'current_query' === $this->widget->get_settings( 'post_query_post_type' ) ||
			'current_query' === $this->widget->get_settings( 'product_query_post_type' ) ||
			'current_query' === $this->widget->get_settings( $this->widget->get_settings( '_skin' ) . '_query_post_type' )
		);
	}

	/**
	 * Query offset post IDs
	 *
	 * @since 1.0.3
	 * @access public
	 *
	 * @param array   $query_vars Holds query arguments.
	 * @param integer $number     Number to query.
	 * @return array
	 */
	public function query_offset_posts( $query_vars, $number ) {

		$query_vars = array_merge(
			$query_vars,
			[
				'paged'                  => 1,
				'posts_per_page'         => $number,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'cache_results'          => false,
				'no_found_rows'          => true,
				'fields'                 => 'ids',
			]
		);

		$post_ids = (array) ( new \WP_Query( $query_vars ) )->posts;

		wp_reset_postdata();

		return $post_ids;

	}

	/**
	 * Get options to render localize widget wrapper
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function get_options();

	/**
	 * Get options to render localize widget wrapper
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function get_script();

	/**
	 * Apply filters
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function before_render();

	/**
	 * Add inline script
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function after_render();
}
