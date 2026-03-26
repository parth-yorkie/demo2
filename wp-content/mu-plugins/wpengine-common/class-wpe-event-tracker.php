<?php
/**
 * Wpe_Event_Tracker class file.
 *
 * @package wpengine/common-mu-plugin
 * @owner: wpengine/golden
 */

namespace wpe\plugin;

require_once __DIR__ . '/class-wpe-event-utils.php';

/**
 * Class Wpe_Event_Tracker
 *
 * This class handles events related to plugin and theme updates, activations,
 * deactivations, deletions, theme switches, and WordPress core upgrades.
 */
class Wpe_Event_Tracker {

	/**
	 * Singleton instance of this class
	 *
	 * @var Wpe_Event_Tracker
	 */
	private static $instance = null;

	/**
	 * Stores the version of plugins or themes before they are updated as a slug => version pair.
	 * It's used in the 'shutdown' hook to verify, if a version actually changed after an update.
	 *
	 * @var array
	 */
	private $upgraded_extensions = array(
		'plugins' => array(),
		'themes'  => array(),
	);

	/**
	 * Keep track of the package URL for the plugin or theme being installed or updated.
	 *
	 * @var array
	 */
	private $package_data = array(
		'plugins' => array(),
		'themes'  => array(),
	);

	/**
	 * The action that triggered the upgrader_process_complete hook
	 *
	 * @var string
	 */
	private $upgrader_process_complete_action = '';

	/**
	 * Get the instance of the class.
	 *
	 * @return Wpe_Event_Tracker
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Wpe_Event_Tracker constructor.
	 */
	private function __construct() {
		// Return early if we are executing in the WP-CLI context.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return;
		}
		// Return early if PWP_NAME or WPE_CLUSTER_ID is not defined.
		if ( ! defined( 'PWP_NAME' ) || ! defined( 'WPE_CLUSTER_ID' ) ) {
			return;
		}

		add_action( 'upgrader_process_complete', array( $this, 'on_upgrader_process_complete' ), 10, 2 );
		add_action( '_core_updated_successfully', array( $this, 'on_core_updated_successfully' ) );
		add_action( 'activated_plugin', array( $this, 'on_activated_plugin' ), 10, 1 );
		add_action( 'deactivated_plugin', array( $this, 'on_deactivated_plugin' ), 10, 1 );
		add_action( 'delete_plugin', array( $this, 'on_delete_plugin' ), 10, 1 );
		add_action( 'delete_theme', array( $this, 'on_delete_theme' ), 10, 1 );
		add_action( 'switch_theme', array( $this, 'on_switch_theme' ), 10, 2 );

		add_filter( 'upgrader_pre_install', array( $this, 'on_upgrader_pre_install' ), 10, 2 );
		add_filter( 'upgrader_post_install', array( $this, 'on_upgrader_post_install' ), 10, 3 );
		add_filter( 'upgrader_package_options', array( $this, 'on_upgrader_process_init' ), 10, 1 );
		add_filter( 'pre_http_request', array( $this, 'on_pre_http_request' ), PHP_INT_MAX, 3 );
	}

	/**
	 * When a plugin or theme is about to be installed or updated, update the internal state with
	 * package type and package URL.
	 *
	 * @param array $options Options used by the upgrader.
	 *
	 * @return array
	 */
	public function on_upgrader_process_init( $options ) {
		// Return early if the parameters are invalid.
		if ( ! is_array( $options ) ) {
			return $options;
		}

		// Catch the type of the upgrade operation, also for WP-CLI driven updates.
		$type = $options['hook_extra']['type'] ?? 'unknown';
		$type = 'unknown' === $type && isset( $options['hook_extra']['plugin'] ) ? 'plugin' : $type;
		$type = 'unknown' === $type && isset( $options['hook_extra']['theme'] ) ? 'theme' : $type;

		// New upgrade operation in progress.
		$this->package_data['current_package'] = $options['package'] ?? '';
		$this->package_data['type']            = $type;

		return $options;
	}

	/**
	 * Log on upgrader_process_complete hook
	 *
	 * @param WP_Upgrader $upgrader WP_Upgrader instance. Might be a Theme_Upgrader, Plugin_Upgrader, Core_Upgrader, or Language_Pack_Upgrader instance.
	 * @param array       $hook_extra Array of bulk item update data.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/
	 */
	public function on_upgrader_process_complete( $upgrader, $hook_extra = array() ) {
		// Return early if this is a multisite installation and the current site is not the main site.
		if ( ! Wpe_Event_Utils::is_main_site() ) {
			return;
		}

		// Return early if the parameters are invalid.
		if ( ! $upgrader instanceof \WP_Upgrader || ! is_array( $hook_extra ) || ! isset( $hook_extra['type'] ) || ! isset( $hook_extra['action'] ) ) {
			return;
		}

		$type   = $hook_extra['type'];
		$action = $hook_extra['action'];

		// Return early if this is not a plugin or theme install or update.
		if ( ! in_array( $type, array( 'plugin', 'theme' ), true ) || ! in_array( $action, array( 'install', 'update' ), true ) ) {
			return;
		}

		// Extract slugs of upgraded extensions from $upgrader or $hook_extra and store them in the upgraded_extensions array.
		$this->store_slugs_after_upgrade( $upgrader, $type, $hook_extra );

		// The action will be used in the shutdown hook to distinguish between install and update events.
		$this->upgrader_process_complete_action = $action;

		// Attach to the 'shutdown' hook to delay processing of installed extensions. Purposefully it's not done
		// in the 'upgrader_process_complete' hook, because an update can be rolled back yet.
		add_action( 'shutdown', array( $this, 'on_shutdown' ), 10, 0 );
	}

	/**
	 * Store slugs of installed or updated plugins or themes
	 *
	 * When an extension is being updated, its slug is passed to 'upgrader_pre_install' in $hook_extra so it can be stored
	 * in the upgraded_extensions array. But when an extension is installed (or updated via install from zip), $hook_extra
	 * in 'upgrader_pre_install' doesn't contain its slug. That's why store_slugs_after_upgrade() is needed to store the
	 * slugs of installed extensions.
	 *
	 * @param WP_Upgrader $upgrader Plugin_Upgrader instance.
	 * @param string      $type Type of the upgrader process. Either 'plugin' or 'theme'.
	 * @param array       $hook_extra Array of bulk item update data.
	 */
	private function store_slugs_after_upgrade( $upgrader, $type, $hook_extra ) {
		$slugs = array();
		$key   = $type; // $type can be 'plugin' or 'theme'
		if ( ! empty( $hook_extra[ $key ] ) ) {
			$slugs[] = $hook_extra[ $key ];
		} else {
			$key = $type . 's'; // Changes 'plugin' to 'plugins' and 'theme' to 'themes'.
			if ( isset( $hook_extra[ $key ] ) ) {
				$slugs = (array) $hook_extra[ $key ];
			} elseif ( is_array( $upgrader->result ) && ! empty( $upgrader->result['destination_name'] ) ) {
				$slugs[] = $upgrader->result['destination_name'];
			}
		}

		// Store the slugs of installed or updated extensions in the upgraded_extensions array.
		foreach ( $slugs as $slug ) {
			if ( ! array_key_exists( $slug, $this->upgraded_extensions[ $type . 's' ] ) ) {
				// Saves the slug in the 'plugins' or 'themes' array.
				$this->upgraded_extensions[ $type . 's' ][ $slug ] = null;
			}
		}
	}

	/**
	 * Callback function for the 'shutdown' hook.
	 *
	 * This method is called at the end of the script execution. It processes the plugins and themes
	 * that might have been installed or updated.
	 *
	 * @return void
	 */
	public function on_shutdown() {
		if ( ! empty( $this->upgraded_extensions['plugins'] ) ) {
			$this->process_plugin_updates();
		}
		if ( ! empty( $this->upgraded_extensions['themes'] ) ) {
			$this->process_theme_updates();
		}
	}

	/**
	 * Handle plugin installed or updated
	 *
	 * This method is called in the 'shutdown' hook and checks which plugins have been installed
	 * or updated and emmits a Telegraf message.
	 */
	private function process_plugin_updates() {
		$plugins = array();

		// Iterate through the slugs to get plugin details.
		foreach ( $this->upgraded_extensions['plugins'] as $slug => $version ) {
			$plugin_details = $this->get_plugin_details( $slug );

			// Skip if the plugin details could not be retrieved.
			if ( null === $plugin_details ) {
				continue;
			}

			// Check if the version has changed.
			if ( $version !== $plugin_details['Version'] ) {
				$plugins[] = $plugin_details;
			}
		}

		if ( ! empty( $plugins ) ) {
			// Send the details of updated or installed plugins.
			$this->prepare_and_send( 'plugins', $plugins );
		}
	}

	/**
	 * Handle theme installed or updated
	 *
	 * This method is called in the 'shutdown' hook and checks which themes have been installed
	 * or updated and emmits a Telegraf message.
	 */
	private function process_theme_updates() {
		$themes = array();

		// Iterate through the slugs to get theme details.
		foreach ( $this->upgraded_extensions['themes'] as $slug => $version ) {
			$theme_details = $this->get_theme_details( $slug );

			// Skip if the theme details could not be retrieved.
			if ( null === $theme_details ) {
				continue;
			}

			// Check if the version has changed.
			if ( $version !== $theme_details['Version'] ) {
				$themes[] = $theme_details;
			}
		}

		if ( ! empty( $themes ) ) {
			// Send the details of updated or installed themes.
			$this->prepare_and_send( 'themes', $themes );
		}
	}

	/**
	 * Get plugin details
	 *
	 * @param string $slug Plugin slug.
	 * @return array|null Plugin details or null if not found.
	 */
	private function get_plugin_details( $slug ) {
		// Get the full path to the main plugin file using the provided slug.
		$plugin_path = Wpe_Event_Utils::get_plugin_path( $slug );

		// Return early if the plugin path could not be determined.
		if ( null === $plugin_path ) {
			return null;
		}

		// Get the plugin data from the main plugin file.
		$data = get_file_data(
			$plugin_path,
			array(
				'Name'    => 'Plugin Name',
				'Version' => 'Version',
			)
		);

		// Check if the required fields are present and not empty.
		if ( empty( $data['Name'] ) || empty( $data['Version'] ) ) {
			return null;
		}

		// Get the plugin file path relative to the plugins directory.
		$plugin_file = plugin_basename( $plugin_path );

		// Extract the slug from the plugin file.
		$plugin_slug = Wpe_Event_Utils::get_slug_from_filename( $plugin_file );

		// If a plugin package was stored during the upgrade process, use it.
		$plugin_package = isset( $this->package_data['plugins'][ $plugin_slug ] ) ?
			$this->package_data['plugins'][ $plugin_slug ]['package'] : '';

		// Is the plugin active?
		$active = is_plugin_active( $plugin_file );

		// Create an array with the plugin details.
		return array(
			'Slug'    => $plugin_slug,     // The slug of the plugin.
			'File'    => $plugin_file,     // The path to the main plugin file relative to the plugins directory.
			'Name'    => $data['Name'],    // The name of the plugin.
			'Version' => $data['Version'], // The version of the plugin.
			'Active'  => $active,          // Whether the plugin is active.
			'Package' => $plugin_package,  // The package URL of the plugin.
		);
	}

	/**
	 * Get theme details
	 *
	 * @param string $slug Theme slug.
	 * @return array|null Theme details or null if not found.
	 */
	private function get_theme_details( $slug ) {
		// Get the main theme file path using the provided slug.
		$theme_path = Wpe_Event_Utils::get_theme_path( $slug );

		// Return early if the theme path could not be determined.
		if ( null === $theme_path ) {
			return null;
		}

		// Get the theme data from the style.css file.
		$data = get_file_data(
			$theme_path,
			array(
				'Name'    => 'Theme Name',
				'Version' => 'Version',
				'Parent'  => 'Template',
			)
		);

		// Check if the required fields are present and not empty.
		if ( empty( $data['Name'] ) || empty( $data['Version'] ) ) {
			return null;
		}

		// Get style.css path relative to the themes directory.
		$theme_file = str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_path );

		// If a theme package was stored during the upgrade process, use it.
		$theme_package = isset( $this->package_data['themes'][ $slug ] ) ?
			$this->package_data['themes'][ $slug ]['package'] : '';

		// Is the theme active?
		$active = ( get_stylesheet() === $slug );

		// Create an array with the theme details.
		$theme_details = array(
			'Slug'    => $slug,            // The slug of the theme.
			'File'    => $theme_file,      // The path to style.css relative to the themes directory.
			'Name'    => $data['Name'],    // The name of the theme.
			'Version' => $data['Version'], // The version of the theme.
			'Active'  => $active,          // Whether the theme is active.
			'Package' => $theme_package,   // The package URL of the theme.
		);

		// Add the parent theme if it exists.
		if ( ! empty( $data['Parent'] ) ) {
			$theme_details['Parent'] = $data['Parent'];
		}

		return $theme_details;
	}

	/**
	 * Handle core version change
	 */
	public function on_core_updated_successfully() {
		$new_versions = Wpe_Event_Utils::get_current_core_version();

		$wordpress = array(
			'Version' => $new_versions,
		);

		// Send the details of the updated WordPress core.
		// phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled -- The key 'wordpress' is used intentionally for data structure consistency.
		$this->prepare_and_send( 'wordpress', $wordpress );
	}

	/**
	 * Handle plugin active state change
	 *
	 * @param string $slug Plugin slug.
	 * @param bool   $active Whether the plugin is active.
	 */
	private function on_plugin_active_state_change( $slug, $active ) {
		$plugin_details = $this->get_plugin_details( $slug );

		if ( null !== $plugin_details ) {
			$plugin_details['Active'] = $active;

			// Plugins are always sent as an array.
			$plugins = array( $plugin_details );
			$this->prepare_and_send( 'plugins', $plugins );
		}
	}

	/**
	 * Log on activated_plugin hook
	 *
	 * @param string $slug The plugin being activated.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/activated_plugin/
	 */
	public function on_activated_plugin( $slug ) {
		// Return early if this is a multisite installation and the current site is not the main site.
		if ( ! Wpe_Event_Utils::is_main_site() ) {
			return;
		}

		$this->on_plugin_active_state_change( $slug, true );
	}

	/**
	 * Log on deactivated_plugin hook
	 *
	 * @param string $slug The plugin being deactivated.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/deactivated_plugin/
	 */
	public function on_deactivated_plugin( $slug ) {
		// Return early if this is a multisite installation and the current site is not the main site.
		if ( ! Wpe_Event_Utils::is_main_site() ) {
			return;
		}

		$this->on_plugin_active_state_change( $slug, false );
	}

	/**
	 * Log on delete_plugin hook
	 *
	 * @param string $plugin_file The path to the plugin's main file relative to the plugins directory.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/delete_plugin/
	 */
	public function on_delete_plugin( $plugin_file ) {
		// Return early if this is a multisite installation and the current site is not the main site.
		if ( ! Wpe_Event_Utils::is_main_site() ) {
			return;
		}

		// Extract the slug from the plugin file.
		$slug = Wpe_Event_Utils::get_slug_from_filename( $plugin_file );

		$deleted_plugin = array(
			'Slug'    => $slug,
			'File'    => $plugin_file,
			'Deleted' => true,
		);

		// Plugins are always sent as an array.
		$plugins = array( $deleted_plugin );
		$this->prepare_and_send( 'plugins', $plugins );
	}

	/**
	 * Log on delete_theme hook
	 *
	 * @param string $theme_slug The slug of the theme.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/delete_theme/
	 */
	public function on_delete_theme( $theme_slug ) {
		// Return early if this is a multisite installation and the current site is not the main site.
		if ( ! Wpe_Event_Utils::is_main_site() ) {
			return;
		}

		$deleted_theme = array(
			'Slug'    => $theme_slug,
			'Deleted' => true,
		);

		// Themes are always sent as an array.
		$themes = array( $deleted_theme );
		$this->prepare_and_send( 'themes', $themes );
	}

	/**
	 * Log on switch_theme hook
	 *
	 * @param string   $new_name The name of the new theme.
	 * @param WP_Theme $new_theme WP_Theme instance of the new theme.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/switch_theme/
	 */
	public function on_switch_theme( $new_name, $new_theme ) {
		// Suppress unused variable warning ($new_name is unused, but WordPress passes it to the callback anyway).
		$new_name;

		// Return early if this is a multisite installation and the current site is not the main site.
		if ( ! Wpe_Event_Utils::is_main_site() ) {
			return;
		}

		$slug          = $new_theme->get_stylesheet();
		$theme_details = $this->get_theme_details( $slug );

		if ( null === $theme_details ) {
			return;
		}

		// Themes are always sent as an array.
		$themes = array( $theme_details );
		$this->prepare_and_send( 'themes', $themes );
	}

	/**
	 * Function to store the version of the plugin or theme before it is updated.
	 *
	 * @param mixed $response The installation response.
	 * @param array $hook_extra Array of bulk item update data.
	 * @return mixed The installation response.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/upgrader_pre_install/
	 */
	public function on_upgrader_pre_install( $response, $hook_extra ) {
		// Ensure $hook_extra is an array.
		if ( ! is_array( $hook_extra ) ) {
			return $response;
		}

		$plugin_slugs = array();

		// Check if the update is for a single plugin.
		if ( isset( $hook_extra['plugin'] ) ) {
			$plugin_slugs[] = $hook_extra['plugin'];
		}

		// Check if the update is for multiple plugins.
		if ( isset( $hook_extra['plugins'] ) && is_array( $hook_extra['plugins'] ) ) {
			$plugin_slugs = array_merge( $plugin_slugs, $hook_extra['plugins'] );
		}

		// Iterate through the slugs and store the versions of plugins before they are updated.
		foreach ( $plugin_slugs as $slug ) {
			$details = $this->get_plugin_details( $slug );
			if ( null !== $details ) {
				$this->upgraded_extensions['plugins'][ $slug ] = $details['Version'];
			} else {
				// If the plugin details could not be retrieved, it means that a new plugin will be installed.
				$this->upgraded_extensions['plugins'][ $slug ] = null;
			}
		}

		$theme_slugs = array();

		// Check if the update is for a single theme.
		if ( isset( $hook_extra['theme'] ) ) {
			$theme_slugs[] = $hook_extra['theme'];
		}

		// Check if the update is for multiple themes.
		if ( isset( $hook_extra['themes'] ) && is_array( $hook_extra['themes'] ) ) {
			$theme_slugs = array_merge( $theme_slugs, $hook_extra['themes'] );
		}

		// Iterate through the slugs and store the versions of themes before they are updated.
		foreach ( $theme_slugs as $slug ) {
			$details = $this->get_theme_details( $slug );
			if ( null !== $details ) {
				$this->upgraded_extensions['themes'][ $slug ] = $details['Version'];
			} else {
				// If the theme details could not be retrieved, it means that a new theme will be installed.
				$this->upgraded_extensions['themes'][ $slug ] = null;
			}
		}

		return $response;
	}

	/**
	 * Function to store the package URL once the plugin or theme is installed or updated.
	 *
	 * @param bool  $response   Installation response.
	 * @param array $hook_extra Extra arguments passed to hooked filters.
	 * @param array $result     Installation result data.
	 *
	 * @return bool
	 */
	public function on_upgrader_post_install( $response, $hook_extra, $result ) {
		// If no upgrade operation is in progress, do nothing.
		if ( ! isset( $this->package_data['type'] ) ) {
			return $response;
		}

		$slug = $result['destination_name'];
		$type = $this->package_data['type'] . 's';

		$this->package_data[ $type ][ $slug ] = array(
			'slug'    => $slug,
			'package' => $this->package_data['current_package'],
		);

		// Reset the current package data.
		unset( $this->package_data['type'] );
		unset( $this->package_data['current_package'] );

		return $response;
	}


	/**
	 * If 3rd party code is changing the download URL, we need to store the new URL as the package source.
	 *
	 * @param false|array|WP_Error $response A preemptive return value of an HTTP request. Default false.
	 * @param array                $args     HTTP request arguments.
	 * @param string               $url      The request URL.
	 */
	public function on_pre_http_request( $response, $args, $url ) {
		// If no upgrade operation is in progress, do nothing.
		if ( ! isset( $this->package_data['type'] ) ) {
			return $response;
		}

		// Store the final package URL.
		$this->package_data['package'] = $url;

		return $response;
	}

	/**
	 * Get current WordPress User
	 *
	 * @return WP_User|null
	 */
	private function get_current_user() {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			return null;
		}
		return wp_get_current_user();
	}

	/**
	 * Prepare event data in the Telegraf format and send it
	 *
	 * @param array $key The kind of the data to be sent (plugins, themes, WordPress).
	 * @param array $value The data to be sent.
	 */
	private function prepare_and_send( $key, $value ) {
		// Prepare tags for the Telegraf message.
		$tags = array(
			// Add common tags.
			'install_name' => PWP_NAME,
			'cluster_id'   => (int) WPE_CLUSTER_ID,
			'sync_time'    => time(),
		);

		// Add plugins, themes, or WordPress data.
		$tags[ $key ] = $value;

		// Prepare context of the message.
		$context = array();

		// Add user ID if available.
		$user = $this->get_current_user();
		if ( $user instanceof \WP_User ) {
			$context['wp_user_id'] = $user->ID;

			// Add user login if available.
			if ( ! empty( $user->user_login ) ) {
				$context['wp_user_login'] = $user->user_login;
			} else {
				// If user is unavailable, determine if the event was triggered from WP-CLI or by the system.
				$is_wp_cli                = defined( 'WP_CLI' ) && WP_CLI && class_exists( 'WP_CLI' );
				$context['wp_user_login'] = $is_wp_cli ? 'wpcli' : 'system';
			}
		}

		// Flag autoupdater events.
		if ( defined( 'AUTOUPDATER_IN_PROGRESS' ) ) {
			$context['autoupdater'] = true;
		}

		// Add user preference for updates source.
		$update_source = get_option( 'wpe_uss_site_preference' );
		if ( in_array( $update_source, array( 'wpengine', 'wordpress' ), true ) ) {
			$context['primary_update_source'] = $update_source;
		}

		// Add the context tag if not empty.
		if ( ! empty( $context ) ) {
			$tags['context'] = $context;
		}

		// Sort the tags to ensure consistent order.
		Wpe_Event_Utils::recursive_ksort( $tags );

		// Generate the signature.
		$tags['signature'] = Wpe_Event_Utils::generate_hmac_signature( $tags, WPE_CLUSTER_ID . PWP_NAME );

		// Encode and escape complex tags for compatibility.
		$tags[ $key ] = Wpe_Event_Utils::statsd_encode_and_escape( $value );
		if ( ! empty( $context ) ) {
			$tags['context'] = Wpe_Event_Utils::statsd_encode_and_escape( $context );
		}

		// Send the event data to the Telegraf agent.
		$this->send( $tags );
	}

	/**
	 * Returns true if install is on an Evolve cluster, false otherwise.
	 */
	private function on_evolve() {
		$cluster_id = intval( WPE_CLUSTER_ID );

		return $cluster_id >= 200000;
	}

	/**
	 * Get the Telegraf host address
	 */
	private function get_telegraf_host() {
		if ( defined( 'WPE_TELEGRAF_HOST' ) ) {
			return WPE_TELEGRAF_HOST;
		}

		if ( $this->on_evolve() ) {
			$host = 'pod-' . WPE_CLUSTER_ID . '.pod-' . WPE_CLUSTER_ID . '.svc.cluster.local';
		} else {
			$host = 'localhost';
		}

		return $host;
	}

	/**
	 * Send data to the Telegraf agent
	 *
	 * @param array $tags The tags to be sent.
	 * @return bool True if the data was sent successfully, false otherwise.
	 */
	protected function send( $tags ) {
		// Get the Telegraf URL and port.
		$host = $this->get_telegraf_host();
		$port = defined( 'WPE_TELEGRAF_PORT' ) ? WPE_TELEGRAF_PORT : 8127;

		// Encode tags to JSON format.
		$json_data = wp_json_encode( $tags );

		// Send the data to the Telegraf agent.
		require_once __DIR__ . '/plugin.php';
		return \WpeCommon::http_request_async( 'POST', $host, $port, null, '/telegraf', array( 'Content-Type: application/json' ), 100, $json_data );
	}
}
