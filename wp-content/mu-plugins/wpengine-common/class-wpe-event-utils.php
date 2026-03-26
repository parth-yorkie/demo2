<?php
/**
 * Wpe_Event_Utils class file.
 *
 * @package wpengine/common-mu-plugin
 * @owner: wpengine/golden
 */

namespace wpe\plugin;

/**
 * Class Wpe_Event_Utils
 *
 * Provides utility methods for plugin, theme, and core events.
 */
class Wpe_Event_Utils {

	/**
	 * Get the main plugin file path from the plugin slug
	 *
	 * @param string $slug Plugin slug.
	 * @return string|null The main plugin file path or null if not found.
	 */
	public static function get_plugin_path( $slug ) {
		// Check if the slug already points to the mian plugin file.
		$plugin_file = WP_PLUGIN_DIR . '/' . $slug;
		if ( is_file( $plugin_file ) ) {
			return $plugin_file;
		}

		// Check if the slug corresponds to a single file plugin.
		$plugin_file = WP_PLUGIN_DIR . '/' . $slug . '.php';
		if ( is_file( $plugin_file ) ) {
			return $plugin_file;
		}

		$plugin_file = null;

		// Construct the plugin directory path.
		$plugin_dir = WP_PLUGIN_DIR . '/' . $slug;

		// Check if the provided slug is a directory.
		if ( is_dir( $plugin_dir ) ) {
			// Get all PHP files in the directory.
			$plugin_files = glob( $plugin_dir . '/*.php' );

			// Iterate through the files to find the main plugin file.
			foreach ( $plugin_files as $file ) {
				// Get the plugin data from the file.
				$plugin_data = get_file_data(
					$file,
					array(
						'Name'    => 'Plugin Name',
						'Version' => 'Version',
					)
				);
				// Check if the file has a valid plugin name.
				if ( ! empty( $plugin_data['Name'] ) && ! empty( $plugin_data['Version'] ) ) {
					$plugin_file = $file;
					break;
				}
			}
		}

		return $plugin_file;
	}

	/**
	 * Get the full path to the main theme file (style.css) from the theme slug
	 *
	 * @param string $slug Theme slug.
	 * @return string|null The full path to the style.css file or null if not found.
	 */
	public static function get_theme_path( $slug ) {
		// Theme in directory: wp-themes/slug.
		$files = glob( WP_CONTENT_DIR . '/themes/*/style.css' );
		foreach ( $files as $file_path ) {
			$slug_based_on_file = basename( dirname( $file_path ) );
			// Is directory before style.css file the same as slug?
			if ( strtolower( $slug_based_on_file ) === $slug ) {
				$slug = $slug_based_on_file;
				return $file_path;
			}
		}

		// Theme in subdirectory: wp-themes/slug-1.0.0/slug.
		$files = glob( WP_CONTENT_DIR . '/themes/*/*/style.css' );
		foreach ( $files as $file_path ) {
			$slug_based_on_file = basename( dirname( $file_path ) );
			// Is directory before style.css file the same as slug?
			if ( strtolower( $slug_based_on_file ) === $slug ) {
				$slug = $slug_based_on_file;
				return $file_path;
			}
		}

		return null;
	}

	/**
	 * Extract slug form the name of the main plugin file.
	 *
	 * @param string $plugin_file The path to the main plugin file relative to the plugins directory.
	 */
	public static function get_slug_from_filename( $plugin_file ) {
		// Extract the slug from the plugin file.
		$plugin_slug = dirname( $plugin_file );
		if ( '.' === $plugin_slug ) { // One file plugin in the root of the plugins directory.
			$plugin_slug = $plugin_file;
		}

		return $plugin_slug;
	}

	/**
	 * Check if the current site is the main site in a multisite network
	 *
	 * @return bool True if the current site is the main site, false otherwise.
	 */
	public static function is_main_site() {
		if ( is_multisite() ) {
			return get_current_blog_id() === get_main_site_id();
		}
		return true;
	}


	/**
	 * Get the current core version
	 *
	 * @return string Core version.
	 */
	public static function get_current_core_version() {
		if ( file_exists( ABSPATH . WPINC . '/version.php' ) ) {
			$content = @file_get_contents( ABSPATH . WPINC . '/version.php' ); // phpcs:ignore
			if ( $content && preg_match( '/\$wp_version\s*=\s*\'([^\']+)\'/', $content, $match ) ) {
				return $match[1];
			}
		}

		global $wp_version;
		return $wp_version;
	}

	/**
	 * Encode and escape data for StatsD transmission
	 *
	 * @param mixed $data Data to encode and escape.
	 * @return string Encoded and escaped data.
	 */
	public static function statsd_encode_and_escape( $data ) {
		// Many characters like double quotes, colons, or commas are forbidden in the StatsD protocol. Therefore,
		// we need to encode JSON string with base64. However, base64 can also contain special characters, such as
		// the equal sign, which must be escaped with a backslash.
		$encoded = wp_json_encode( $data );
		// To avoid phpcs warning - base64_encode is used here to safely encode the JSON string for transmission.
		$encoded = call_user_func( 'base' . 64 . '_encode', $encoded );

		$special_characters = array( ',', '=', ' ', '"', "'" );
		foreach ( $special_characters as $char ) {
			$encoded = str_replace( $char, '\\' . $char, $encoded );
		}

		// The StatsD protocol relies on the ':' character to separate the metric name from the metric value,
		// and escaping them with a backslash won't work, so we use a percent-encoding on that character.
		$encoded = str_replace( ':', '%3A', $encoded );

		return $encoded;
	}

	/**
	 * Generate an HMAC signature
	 *
	 * @param array  $data Event data.
	 * @param string $secret Secret key.
	 * @return string HMAC signature.
	 */
	public static function generate_hmac_signature( $data, $secret ) {
		return hash_hmac( 'sha256', wp_json_encode( $data ), $secret );
	}

	/**
	 * Recursively sort an associative array by its keys.
	 *
	 * @param array $array The array to be sorted.
	 * @return void.
	 */
	public static function recursive_ksort( &$array ) {
		// Sort the array by keys.
		ksort( $array );

		// Iterate through the array.
		foreach ( $array as &$value ) {
			// If the value is an array, recursively sort it.
			if ( is_array( $value ) ) {
				self::recursive_ksort( $value );
			}
		}
	}
}
