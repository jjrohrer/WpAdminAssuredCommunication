<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.kajoom.ca/
 * @since      1.0.0
 *
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/includes
 */
if ( ! defined( 'WPINC' ) ) {
	die;	// If this file is called directly, abort.
}

if ( ! class_exists( 'Kjm_Admin_Notices' ) ) :
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/includes
 * @author     Marc-Antoine Minville <support@kajoom.ca>
 */
final class Kjm_Admin_Notices {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Kjm_Admin_Notices_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * The options of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $version    The options of the plugin.
	 */
	protected $options;
	
	
	/**
	 * Shared class.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      object    $shared    Shared class.
	 */
	protected $shared;
	
	
	/**
	 * Extras class.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      object    $extras    Extras class.
	 */
	protected $extras;
	

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'kjm-admin-notices';
		$this->version = '1.0.5';
		$this->options = get_option('kjm_admin_notices_settings') ? get_option('kjm_admin_notices_settings') : array();
		
		if (!defined('KJM_ADMIN_NOTICES_VERSION')) {
			define('KJM_ADMIN_NOTICES_VERSION', $this->version);
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_constants();
		
		$this->define_admin_hooks();
		$this->define_extras_hooks();
		
		// Run `kjm_admin_notices_loaded` actions.
		do_action( 'kjm_admin_notices_loaded' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Kjm_Admin_Notices_Loader. Orchestrates the hooks of the plugin.
	 * - Kjm_Admin_Notices_i18n. Defines internationalization functionality.
	 * - Kjm_Admin_Notices_Admin. Defines all hooks for the admin area.
	 * - Kjm_Admin_Notices_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kjm-admin-notices-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kjm-admin-notices-i18n.php';
		
		/**
		 * The class responsible for defining all Base actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kjm-plugin-admin-base.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kjm-admin-notices-admin.php';
		
		/**
		 * The class responsible for defining all extras actions.
		 */
		$extras_file = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kjm-admin-notices-extras.php';
		if (file_exists($extras_file)) {
				require_once $extras_file;
		}

		$this->loader = new Kjm_Admin_Notices_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Kjm_Admin_Notices_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Kjm_Admin_Notices_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Kjm_Admin_Notices_Admin( $this->get_plugin_name(), $this->get_version(), $this->shared );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'init', $plugin_admin, 'check_version' );
		$this->loader->add_action( 'init', $plugin_admin, 'set_settings_fields' );
		$this->loader->add_action( 'init', $plugin_admin, 'custom_post_types_init' );
		$this->loader->add_action( 'init', $plugin_admin, 'custom_taxonomies_init' );
		
		/* Metaboxes */
		$this->loader->add_action( 'load-post.php', $plugin_admin, 'metaboxes_setup' );
		$this->loader->add_action( 'load-post-new.php', $plugin_admin, 'metaboxes_setup' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_metaboxes', 10, 2 );
		
		$this->loader->add_action( 'transition_post_status', $plugin_admin, 'force_type_private', 10, 3 );
		$this->loader->add_action( 'kjm_admin_notices_post_type_init', $plugin_admin, 'custom_post_type_init_filter', 10, 2 );
		
		// Columns for package post type
		$this->loader->add_filter( 'manage_kjm_notice_posts_columns', $plugin_admin, 'columns_head', 10 );
		$this->loader->add_action( 'manage_kjm_notice_posts_custom_column', $plugin_admin, 'columns_content', 10, 2 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'columns_resize' );
		
		// Handle ajax admin notices.
		$this->loader->add_action( 'wp_ajax_kjm_dismiss_notice_ajax', $plugin_admin, 'kjm_dismiss_notice_ajax' );
		
		// Register hook to save the related blocks when saving the post
		#$this->loader->add_action( 'save_post', $plugin_admin, 'save' );
		
		// Start the plugin
		#$this->loader->add_action( 'admin_menu', $plugin_admin, 'start' );
		
		$this->loader->add_action( 'init', $plugin_admin, 'request_controller' );
		
		$this->loader->add_filter( 'kjm_debug_ip_whitelist', $plugin_admin, 'debug_ip_whitelist', 10, 2 );
		
		// add plugin "Settings" action on plugin list
		$this->loader->add_filter( 'plugin_action_links_' . KJM_ADMINNOTICES_FILE, $plugin_admin, 'add_settings_link', 10, 2 );
		
		// push options page link, when generating admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_menu_items' );
		
		// Plugin specific.
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'show_admin_notice' );
		//$this->loader->add_action( 'init', $plugin_admin, 'create_default_categories' );
		$this->loader->add_action( 'post_updated', $plugin_admin, 'trigger_on_update_post', 10, 3 );
		
		$this->loader->add_action( 'init', $plugin_admin, 'register_shortcodes' );
	}
	
	
	/**
	 * Register all of the extras hooks.
	 *
	 * @since    1.0.4
	 * @access   private
	 */
	private function define_extras_hooks() {
		
		// Load extra features if exists.
		if (class_exists( 'Kjm_Admin_Notices_Extras' )) {
			
			$this->extras = new Kjm_Admin_Notices_Extras($this->plugin_name, $this->version, $this->options);
			
			foreach($this->extras->get_filters() as $filter) {
				$this->loader->add_filter( $filter['hook'], $filter['component'], $filter['callback'], $filter['priority'], $filter['accepted_args'] );
			}
			foreach($this->extras->get_actions() as $action) {
				$this->loader->add_action( $action['hook'], $filter['component'], $action['callback'], $action['priority'], $action['accepted_args'] );
			}
			
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Kjm_Admin_Notices_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Retrieve the options of the plugin.
	 *
	 * @since     1.0.0
	 * @return    array    The options of the plugin.
	 */
	public function get_options() {
		return $this->options;
	}
	
	// Defines a few static helper values we might need
	protected function define_constants() {

		define('KJM_ADMINNOTICES_VERSION', $this->version);
		define('KJM_ADMINNOTICES_HOME', 'https://www.kajoom.ca');
		define('KJM_ADMINNOTICES_FILE', plugin_basename(dirname(__FILE__)));
		define('KJM_ADMINNOTICES_ABSPATH', str_replace('\\', '/', WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__))));
		define('KJM_ADMINNOTICES_URLPATH', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)));
	}

} // End of Class

endif; // Endif class exists.
