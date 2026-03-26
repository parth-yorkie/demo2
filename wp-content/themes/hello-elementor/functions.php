<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.9.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Admin notice
if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}
add_action( 'wp_head', 'hide_elementor_sections' );
function hide_elementor_sections() {
	global $post;
	
	if( !is_admin()){
		$sales_dwn_section = 0;
		if ( is_single() && get_post_type() == 'post') {
			$sales_dwn_section = get_field('sales_team_structure_template_enable', $post->ID);
		}

		if ($sales_dwn_section == 1) {
			?>
			<style type="text/css">
				.download_sales_team_structure{
					display: block;
				}
				.single-post.elementor-kit-1195 .related-post{
					margin-top: 35px;
				}
			</style>
			<?php
		} else{
			?>
			<style type="text/css">
				.download_sales_team_structure{
					display: none;
				}
				.single-post.elementor-kit-1195 .related-post{
					margin-top: 10px;
				}
			</style>
		<?php }
	}
	else if( is_admin() ){
		?>
		<style type="text/css">
			.download_sales_team_structure{
				display: block !important;
			}
			.single-post.elementor-kit-1195 .related-post{
				margin-top: 35px;
			}
		</style>
		<?php
	}
}

add_shortcode( 'yorkie_relatedposts', 'yorkie_relatedposts_callback' );
function yorkie_relatedposts_callback() {

	$related_args = array(
		'post_type' 		=> 'post',
		'posts_per_page' 	=> 3,
		'post_status' 		=> 'publish',
		'post__not_in' 		=> array( get_the_ID() ),
		'category__in'		=> wp_get_post_categories(get_the_ID()),
		'orderby' 			=> 'date',
		'orderby'			=> 'DESC',
	);
	$related = new WP_Query( $related_args );

	if( $related->have_posts() ) :
		?>
		<div class="elementor-widget-container custom-related-posts-listing">
			<div class="elementor-posts-container elementor-posts elementor-posts--skin-classic elementor-grid elementor-has-item-ratio">
				<?php while( $related->have_posts() ): $related->the_post(); ?>

					<article class="elementor-post elementor-grid-item <?php echo get_the_ID(); ?> post type-post status-publish format-standard has-post-thumbnail hentry category-entrepreneurship tag-business tag-business-growth tag-financial-models tag-fundraising tag-growth tag-metrics tag-profits tag-scale tag-scaling tag-silicon-valley tag-startups tag-vanity tag-vc tag-venture-capital">
						<a class="elementor-post__thumbnail__link" href="<?php echo get_the_permalink( get_the_ID() ); ?>">
							<div class="elementor-post__thumbnail elementor-fit-height"><img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'large' ); ?>" class="attachment-large size-large wp-image-<?php echo get_post_thumbnail_id( get_the_ID() ); ?>" alt="" loading="lazy" width="800" height="290"></div>
						</a>
						<div class="elementor-post__text">
							<h3 class="elementor-post__title">
								<a href="<?php echo get_the_permalink( get_the_ID() ); ?>">
								<?php echo get_the_title( get_the_ID() ); ?>			</a>
							</h3>
							<div class="elementor-post__excerpt">
								<p><?php echo get_field('homepage_summary') ?></p>
							</div>

							<a class="elementor-post__read-more" href="<?php echo get_the_permalink( get_the_ID() ); ?>" aria-label="Read more about <?php echo get_the_title( get_the_ID() ); ?>">
							Read More »		</a>

						</div>
					</article>
				<?php endwhile; ?>
			</div>

		</div>
		<?php
	endif;
	wp_reset_postdata();

}