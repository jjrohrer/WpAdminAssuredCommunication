<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.kajoom.ca/
 * @since      1.0.0
 *
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/admin
 */
if ( ! defined( 'WPINC' ) ) {
	die;	// If this file is called directly, abort.
}

if ( ! class_exists( 'Kjm_Admin_Notices_Admin' ) 
&& class_exists( 'Kjm_Plugin_Admin_Base_1_0' ) ) : 

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/admin
 * @author     Marc-Antoine Minville <support@kajoom.ca>
 */
final class Kjm_Admin_Notices_Admin extends Kjm_Plugin_Admin_Base_1_0  {
	
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;
	
	/**
	 * Local instance of plugin shared class
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      object    $shared    Shared class.
	 */
	protected $shared;
	
	
	/**
	 * Local instance of Tools class
	 *
	 * @since		1.0.0
	 * @access	protected
	 * @see			Kajoom_Tools
	 * @var			object 	$tools 	Tools class.
	 */
	protected $tools;
	
	
	/**
	 * Plugin settings
	 *
	 * @since		1.0.0
	 * @access	protected
	 * @var			array 	$_settings 	Plugin settings.
	 */
	protected $_settings;
	
	
	/**
	 * Plugin admin options page name
	 *
	 * @since		1.0.0
	 * @access	protected
	 * @var			string 	$_options_pagename 	Name without ".php" extension.
	 */
	protected $_options_pagename = 'kjm-admin-notices-settings';
	
	
	/**
	 * Plugin update name.
	 *
	 * @since		1.0.0
	 * @access	protected
	 * @var			string 	$update_name 	Unique update name.
	 */
	protected $update_name = 'Kjm_Admin_Notices/plugin.php';
	
	/**
	 * Error messages to diplay
	 *
	 * @var array
	 */
	private $_messages = array();
	
	protected $_options = array(
		'kjm-admin-notices-selected-types' => array()      
	);
	
	/**
	 * Holds current variables to display as debug info
	 *
	 * @since		1.0.0
	 * @access	protected
	 * @var			array 	$debug 	List of variables to debug.
	 */
	protected $debug = array();
	
	
	/**
	 * Holds admin IPs to whitelist for debug display
	 *
	 * @since		1.0.0
	 * @access	protected
	 * @var			array 	$debug_ips 	List of admin IPs.
	 */
	protected $debug_ips = array();
	
	/**
	 * The Settings menu page of the plugin.
	 *
	 * @since    1.2.0
	 * @access   protected
	 * @var      string    $version    The current menu page of the plugin.
	 */
	protected $menu_page;
	
	
	public $custom_post_types = array(
		'kjm_notice'	=>	'notice',
	);
	
	
	public $custom_taxonomies = array(
		'kjm_notice_cat'	=>	'notice_cat',
		'kjm_notice_tag'	=>	'notice_tag',
	);
	
	/**
	 * List of settings fields definitions
	 *
	 * @since		1.0.0
	 * @access	public
	 * @var			array 	$settings_fields 	Settings and definitions in an array.
	 */
	public $settings_fields = array(
	
		'kjm-admin-notices_active'	=>	array(
			'parent'	=>	'',
			'type'	=>	'checkbox',
			'default_value'	=>	0,
		),
		'kjm-admin-notices_kjm_notice_active'	=>	array(
			'parent'	=>	'kjm-admin-notices_active',
			'type'	=>	'checkbox',
			'default_value'	=>	0,
		),
		'kjm-admin-notices_send_email_active'	=>	array(
			'parent'	=>	'kjm-admin-notices_active',
			'type'	=>	'checkbox',
			'default_value'	=>	0,
		),
		'kjm-admin-notices_from_email_active'	=>	array(
			'parent'	=>	'kjm-admin-notices_active',
			'type'	=>	'text',
			'default_value'	=>	"",
		),
		'kjm-admin-notices_from_name_active'	=>	array(
			'parent'	=>	'kjm-admin-notices_active',
			'type'	=>	'text',
			'default_value'	=>	"",
		),
		'kjm-admin-notices_comments_active'	=>	array(
			'parent'	=>	'kjm-admin-notices_active',
			'type'	=>	'checkbox',
			'default_value'	=>	0,
		),
	);
	
	
	/**
	 * List of custom fields definitions
	 *
	 * @since		1.0.2
	 * @access	public
	 * @var			array 	$custom_fields 	Custom fields definitions in an array.
	 */
	public $custom_fields = array(
		'kjm_notice'	=>	array(
			'kjm_admin_notices_show_notice_to',
			'kjm_admin_notices_send_email',
			'kjm_admin_notices_send_copy_admin',
			'kjm_admin_notices_hide_title',
			'kjm_admin_notices_hide_metas',
			'kjm_admin_notices_hide_dismiss_link',
			'kjm_admin_notices_global_params',
		),
	);
	
	
	/**
	 * List of messages statuses definitions
	 *
	 * @since		1.0.0
	 * @access	public
	 * @var			array 	$messages_statuses 	Colors are keys and statuses are values.
	 */
	public	$messages_statuses = array(
		'blue' => 'info',
		'green' => 'success',
		'orange' => 'warning',
		'red' => 'error',
	);
	
	
	/**
	 * List of shortcodes
	 *
	 * @since		1.0.0
	 * @access	public
	 * @var			array 	$shortcodes
	 */
	public	$shortcodes = array(
		'website_domain',
		#'display_name',
		'admin_login',
	);
	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $shared = null ) {
		
		//$this->seed = 'notices';
		
		// Initialize parent Base Class.
		parent::__construct( $plugin_name, $version, $shared );
		
		$this->_settings_url = 'options-general.php?page=' . $this->plugin_name.'-settings';
		
		// Auto-create instance.
		self::$instance = $this;
		
		// Run `kjm_admin_notices_admin_loaded` actions.
		do_action( 'kjm_admin_notices_admin_loaded' );
	}
	
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		
		// Super admin only.
		if( ! is_super_admin() ) {
			return;
		}

		// If the single instance hasn't been set, set it now.
		//if ( null == self::$instance ) {
		//	self::$instance = new self;
		//}

		return self::$instance;
	}
	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kjm_Admin_Notices_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kjm_Admin_Notices_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kjm-admin-notices-admin.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kjm_Admin_Notices_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kjm_Admin_Notices_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		//wp_enqueue_script('jquery-ui-core');
		//wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( $this->plugin_name.'-scripts', plugin_dir_url( __FILE__ ) . 'js/kjm-admin-notices-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script($this->plugin_name.'-scripts', 'kjm_admin_notices_ajax', array(
				'ajax_nonce' => wp_create_nonce('kjm_admin_notices_ajax'),
			)
		);
	}
	
	
	public function set_settings_fields() {
		
		$this->settings_fields = apply_filters($this->plugin_name.'_set_settings_fields', $this->settings_fields);
	}
	
	
	public function request_controller() {
		
		//$allowed_options =$this->options;
		//$allowed_values = get_post_types(array(), "names");
		
		/*
		if(array_key_exists('option_name', $_GET) && array_key_exists('option_value', $_GET)
		&& array_key_exists($_GET['option_name'], $allowed_options)) {
			
			update_option($_GET['option_name'], $_GET['option_value']);
			
			header("Location: " . $this->_settings_url);
			die();	
		
		}
		*/
		
		// REQUEST.
		if (isset($_REQUEST['saved'])) {
			
			if ($_REQUEST['saved'] == '1') {
				
				$this->message = '<div id="message" class="updated fade"><p><strong>'.__("KJM Admin Notices settings saved.", "kjm-admin-notices").'</strong></p></div>';
			} else {
				
				$this->message = '<div id="message" class="updated fade"><p><strong>'.__("KJM Admin Notices settings saving failed!.", "kjm-admin-notices").'</strong></p></div>';
			}
		}
		
		// POST REQUEST.
		if (isset($_POST['kjm_admin_notices_settings_saved'])) {
			
			$this->_save_settings_todb($_POST);
			wp_redirect( admin_url('options-general.php?page=kjm-admin-notices-settings&saved=1') ); exit;
		}
		
	}
	
	
	/*
	 * Save settings to options table
	 * 
	 * name: 			_save_settings_todb
	 * @since     1.0.0
	 * 
	 * @param 		array $form_settings		Form settings array.
	 * @return
	 * 
	 */
	public function _save_settings_todb($form_settings = '') {
	
		if (!wp_verify_nonce($_REQUEST['_wpnonce'], $this->_options_pagename)) 
				wp_die(	__('Failed security check on KJM Admin Notices Settings save action.', 'kjm-admin-notices'), 
								__('Failed security check!', 'kjm-admin-notices'), 
								'back_link=true');
	
		if ( $form_settings <> '' ) {
			
				unset($form_settings['kjm_admin_notices_settings_saved']);
				$this->_settings = $form_settings;

				#set standart values in case we have empty fields
				$this->_set_standart_values();
		}
		
		update_option('kjm_admin_notices_settings', $this->_settings);
	}
	
	
	/**
	 * Add plugin admin settings page link
	 *
	 * @since    1.0.0
	 * 
	 */
	public function add_settings_link($links) {
		
		$settings = '<a href="'.admin_url('options-general.php?page='.$this->plugin_name).'">' . __('Settings', 'kjm-admin-notices') . '</a>';
		array_unshift( $links, $settings );
		return $links;
	}
	
	
	/**
	 * Add links on installed plugin list
	 */
	public function add_plugin_links($links, $file) {
		
		return $links;
	}
	
	
	/**
	 * Add menu entry 
	 */
	public function add_menu_page() {
		
		// add option in admin menu, for setting options
		$this->menu_page = add_options_page('KJM Admin Notices', 'KJM Admin Notices', 'update_core', $this->plugin_name.'-settings', array($this, 'page_admin_settings'));
	}
	
	
	/**
	 * Include the plugin admin settings template page file
	 *
	 * @since    1.0.0
	 * 
	 */
	public function page_admin_settings() {
			
			require KJM_ADMIN_NOTICES_PLUGIN_PATH . 'admin/partials/kjm-admin-notices-admin-settings-display.php';
	}
	
	
	/**
	 * Include and get plugin settings strings file
	 *
	 * @since    1.0.0
	 * 
	 */
	public function get_settings_fields_strings() {
		
		$strings = null;
		$path = KJM_ADMIN_NOTICES_PLUGIN_PATH . 'includes/models/strings-settings.php';
		require($path);

		return $strings;
	}
	
	
	public function get_custom_post_types() {
		
		return $this->custom_post_types;
	}
	
	
	public function remove_menu_items() {
		
		foreach($this->get_custom_post_types() as $post_type => $seed) {
			if( !current_user_can( 'administrator' ) ):
					remove_menu_page( 'edit.php?post_type='.$post_type );
			endif;
		}
	}
	
	
	/* Load Custom Post Types. */

	public function custom_post_types_init() {
		
		// Return early if plugin is not activated.
		if ($this->_settings[$this->plugin_name.'_active'] != 1) return;
		
		foreach($this->get_custom_post_types() as $post_type => $seed) {
			
			// Skip if CPT is not activated.
			if ($this->_settings[$this->plugin_name.'_'.$post_type.'_active'] != 1) continue;
			
			$args = array(); // Should be redefined in the model file.
			$path = KJM_ADMIN_NOTICES_PLUGIN_PATH . 'includes/models/post-type-'.$post_type.'.php';
			if (file_exists($path)) {
				
				require($path);
				$args = apply_filters('kjm_admin_notices_post_type_init', $args, $post_type);
				register_post_type($post_type, $args);
			}
		}
	}
	
	
	public function custom_post_type_init_filter($args, $post_type) {
		
		if (('kjm_notice' === $post_type) 
		&& 1 == $this->get_option('kjm-admin-notices_comments_active')) {
			array_push($args['supports'], 'comments');
			$args['supports'] = array_unique($args['supports']);
		}
		
		return $args;
	}
	
	
	/* Load Taxonomies. */
	public function custom_taxonomies_init() {

		// Return early if plugin is not activated.
		if ($this->_settings[$this->plugin_name.'_active'] != 1) return;
		
		foreach($this->custom_taxonomies as $taxonomy => $seed) {
			
			$args = array(); // Should be redefined in the model file.
			$path = KJM_ADMIN_NOTICES_PLUGIN_PATH . 'includes/models/taxonomy-'.$taxonomy.'.php';
			
			if (file_exists($path)) {
				require($path);
				register_taxonomy($taxonomy, $post_types, $args);
			}
		}
	}
	
	// See : http://www.geekpress.fr/recuperer-liste-roles-wordpress/
	public function get_roles($translated=true) {

			$wp_roles = new WP_Roles(); 
			$roles = $wp_roles->get_names();
			if (true === $translated) $roles = array_map( 'translate_user_role', $roles );

			return $roles;
	}
	
	
	// See : https://daveismyname.com/comparing-multiple-values-against-in-array-bp
	public function array_val_in_array($needle, $haystack) 
	{
			
			foreach ($needle as $stack) {
					if (in_array($stack, $haystack)) {
						return true;
					}
			}
			return false;
	}
	
	
	/* Admin notices */
	public function show_admin_notice(){
		
		global $current_user;
		
		$user_id = $current_user->ID;
		
		if (!current_user_can( 'read' )) return false;
		
		// Create default categories. TODO : run once on activation.
		$this->create_default_categories();
		
		/* Dismiss notice for current user. */
		#if(isset($_REQUEST['kjm_normal_notice_ignore']) == '1'){
			#$this->kjm_dismiss_notice('normal');
		#}
		
		$args = array(
			'post_type' => 'kjm_notice', 
			'post_status' => array('publish', 'private'), 
			'taxonomy' => 'kjm_notice_cat',
		);
		$notices = $this->get_items($args, 'all');
		
		foreach ($notices as $notice_id => $notice) {
			
			$show_to_roles = get_post_meta($notice_id, "kjm_admin_notices_show_notice_to", true);
			$hide_dismiss_link = get_post_meta($notice_id,'kjm_admin_notices_hide_dismiss_link',true);
			
			$show_to_roles = is_array($show_to_roles) ? $show_to_roles: array($show_to_roles);
			
			/* Check that the user hasn't already clicked to ignore the message */
			if ( ! get_user_meta($user_id, 'kjm_'.$notice_id.'_notice_ignore') 
			/* Check that the user role is in roles targeted. */
			&& ($this->array_val_in_array($current_user->roles, $show_to_roles) 
			/* If show to "all" roles, display it. */
			|| in_array('all', $show_to_roles))) {
				
				$output = $this->format_notice_content($notice_id, $notice, $current_user);
				$notice_cat = !empty($notice['taxonomies']['kjm_notice_cat'][0]) ? $notice['taxonomies']['kjm_notice_cat'][0]: 'success';
				
				echo '<div class="notice notice-'.$notice_cat.' is-dismissible kjm-admin-notice" id="message" data-notice-id="'.$notice_id.'"><p>';
				printf(__('%1$s'), $output);
				
				echo '</p>';
				if (empty($hide_dismiss_link)) echo '<button type="button" class="notice-dismiss kjm-notice-dismiss">'.__('Thanks, Got it.', 'kjm-admin-notices').'</button>';
				echo '</div>';
			}
			
		}
		
	}
	
	
	public function format_notice_content($notice_id, $notice, $current_user) {
		
		$hide_title = get_post_meta($notice_id,'kjm_admin_notices_hide_title',true);
		$hide_metas = get_post_meta($notice_id,'kjm_admin_notices_hide_metas',true);
				
		$output = $date_alone = '';
		$date_alone = empty($hide_title) ? '': 'alone'; 
		$output .= (current_user_can( 'install_plugins' )) ? '<a class="edit-link" href="'.admin_url('post.php?post='.$notice_id.'&action=edit').'">'.__('Edit Notice', 'kjm-admin-notices').'</a>': '';
		if (empty($hide_title)) $output .= '<b class="notice-title">'.do_shortcode($notice['post']->post_title).'</b> ';
		if (empty($hide_metas)) $output .= '<i class="date small '.$date_alone.'">'.sprintf( _x( '%s ago', '%s = human-readable time difference', 'kjm-admin-notices' ), human_time_diff( get_the_time( 'U', $notice_id ), current_time( 'timestamp' ) ) ).' '.sprintf( _x( 'by %s', '%s = author display name', 'kjm-admin-notices' ), get_the_author_meta('display_name', $notice['post']->post_author) ).'</i>';
		if (empty($hide_title) || empty($hide_metas)) $output .= '<br>';
		$output .= nl2br(do_shortcode($notice['post']->post_content));
		$output = $this->display_name_placeholder($output, $current_user->display_name);
		
		return $output;
	}
	

	/* Accepted notices values. */
	public function kjm_notices_accept_values() {
		
		return array("success", "info", "warning", "error");
	}
	

	/* Accepted notices values. */
	public function kjm_get_notice_dismiss_string($notice) {
		
		return 'kjm_'.(int) $notice.'_notice_ignore';
	}


	/* Create default categories */
	public function create_default_categories() {
		
		if (!current_user_can( 'update_core' )) return false;
		
		foreach ($this->kjm_notices_accept_values() as $term) {
			
			$term_exists = term_exists($term, 'kjm_notice_cat');
			
			if (empty($term_exists)) wp_insert_term($term, 'kjm_notice_cat');
		}
	}


	/**
	 * Dismiss an admin notice through ajax.
	 */
	public function kjm_dismiss_notice_ajax(){
			
			if(!isset($_REQUEST['notice']))
					die('Notice ID expected as "notice" parameter.');
			
			check_ajax_referer('kjm_admin_notices_ajax', '_wpnonce');
			
			$this->kjm_dismiss_notice($_REQUEST['notice']);
	}

	/**
	 * Dismiss an admin notice.
	 */
	protected function kjm_dismiss_notice($notice) {
		
		if (!current_user_can( 'read' )) return;
		
		global $current_user;
		
		$user_id = $current_user->ID;
		//if (!in_array($notice, $this->kjm_notices_accept_values())) return false;
		$meta_key = $this->kjm_get_notice_dismiss_string($notice);
		update_user_meta($user_id, $meta_key, 'on');
	}
	
	
	public function trigger_on_update_post( $post_ID, $post_after ) {
		
		if (array_key_exists($post_after->post_type, $this->get_custom_post_types())
		&& current_user_can( 'update_core' )) {
			$return = $this->kjm_dismiss_notice_reset($post_ID);
			
			if ($this->_settings[$this->plugin_name.'_send_email_active'] == 1) {
				$this->send_email_notice($post_ID);
			}
		}
		
	}


	/**
	 * Dismissed admin notices reset.
	 */
	public function kjm_dismiss_notice_reset($notice) {
		
		global $wpdb;
		
		/* Minimal security check... */
		//if (!in_array($notice, $this->kjm_notices_accept_values())) return false;
		if (!current_user_can( 'update_core' )) return false;
		
		$meta_key = $this->kjm_get_notice_dismiss_string((int) $notice);
		
		$wpdb->query( 
				$wpdb->prepare( 
						"
						DELETE FROM $wpdb->usermeta
						WHERE meta_key = %s
						",
						$meta_key
						)
		);
		
		return true;
	}
	
	
	public function send_email_notice($notice_id) {
		
		$sent = get_post_meta($notice_id, "kjm-admin-notice-sent", true);
		if (!empty($sent)) return false;
		
		$send_email_option = $this->get_option("kjm-admin-notices_send_email_active");
		if (empty($send_email_option)) return false;
		
		$send_email = get_post_meta($notice_id, "kjm_admin_notices_send_email", true);
		if (empty($send_email)) return false;
		
		$from_email = $this->get_option("kjm-admin-notices_from_email_active");
		$from_email = empty($from_email) || (filter_var($from_email, FILTER_VALIDATE_EMAIL) === false) ? 'wordpress@'.$this->get_website_domain(): $from_email;
		
		$from_name = $this->get_option("kjm-admin-notices_from_name_active");
		$from_name = empty($from_name) ? _('WordPress', 'kjm-admin-notices') : $from_name;
		$from_name = str_ireplace(array("\r", "\n", '%0A', '%0D'), '', $from_name);
		
		$send_to_roles = get_post_meta($notice_id, "kjm_admin_notices_show_notice_to", true);
		$send_to_roles = is_array($send_to_roles) ? $send_to_roles: array($send_to_roles);
		
		$notice = get_post($notice_id);
		if (empty($notice)) return false;
		if ("publish" !== $notice->post_status && "private" !== $notice->post_status) return false;
		
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: '.$from_name.' <'.$from_email.'>',
		);
		$send_copy_admin = get_post_meta($notice_id, "kjm_admin_notices_send_copy_admin", true);
		if (!empty($send_copy_admin)) $headers[] = 'Bcc: '.get_option('admin_email');
		$login_text = '<br><br><i class="small">'.__('This notification has been sent by [kjm_website_domain]. ', 'kjm-admin-notices');
		$login_text .= __('Address to connect to your site:', 'kjm-admin-notices').' <a href="[kjm_admin_login]" title="'.__('Login', 'kjm-admin-notices').'" target="_new">[kjm_admin_login]</a></i>';
		$body = nl2br(do_shortcode($notice->post_content.$login_text));
		$args = in_array('all', $send_to_roles) ? array(): array('role__in' => $send_to_roles);
		$users = get_users($args);
		$sent_to = array();
		
		// No users to send to.
		if (empty($users)) return false;
		
		// Send each email.
		foreach ($users as $user) {
			
			// Reset variables.
			$to = $subject = '';
			
			$subject = do_shortcode($notice->post_title);
			$subject = $this->display_name_placeholder($subject, $user->data->display_name);
			$body_processed = $this->display_name_placeholder($body, $user->data->display_name);
			
			$to = $sent_to[] = $user->data->display_name.' <'.$user->data->user_email.'>';
			
			wp_mail( $to, $subject, $body_processed, $headers );
		}
		
		// Write info about this sent notice.
		update_post_meta($notice_id, "kjm-admin-notice-sent", 1);
		update_post_meta($notice_id, "kjm-admin-notice-sent-to", $sent_to);
		update_post_meta($notice_id, "kjm-admin-notice-sent-to-roles", $send_to_roles);
		update_post_meta($notice_id, "kjm-admin-notice-sent-time", current_time('mysql'));
	}
	
	
	public function get_website_domain() {
		
		return preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	}
	
	
	// Shortcodes.
	public function register_shortcodes() {
		
		// Generate Shortcodes.
		foreach ($this->shortcodes as $shortcode) {
			
			$function_name = $shortcode.'_shortcode';
			
			if (method_exists($this, $function_name)) {
				add_shortcode('kjm_'.$shortcode, array($this, $function_name));
			}
		}
	}
	
	
	/* Shortcodes */
	/*
			'website_domain',
	*/
	
	// [kjm_website_domain]
	public function website_domain_shortcode($atts, $content='', $tag='name') {
		
    // $atts - array of attributes passed from shortcode
    // $content - content between shortcodes that have enclosing tags eg: [tag]content[/tag]
    // $tag - shortcode name
    
		extract(shortcode_atts(array(
			'demo' => 0,
		), $atts));
			
		return $this->get_website_domain();
	}
	
	// [kjm_display_name]
	public function display_name_placeholder($content, $replace) {
			
		return str_replace('[kjm_display_name]', $replace, $content);
	}
	
	// [kjm_admin_login]
	public function admin_login_shortcode($atts, $content='', $tag='name') {
		
    // $atts - array of attributes passed from shortcode
    // $content - content between shortcodes that have enclosing tags eg: [tag]content[/tag]
    // $tag - shortcode name
    
		extract(shortcode_atts(array(
			'demo' => 0,
		), $atts));
			
		return wp_login_url();
	}
	
	
		#####################
		#### ADMIN VIEWS ####
		#####################


		// Add new column
		public function columns_head($defaults) {
			
			$custom_fields = array(
				'kjm-admin-notice-sent' => __('Sent', 'kjm-admin-notices'),
				#'kjm-admin-notice-sent-to' => __('Sent To', 'kjm-admin-notices'),
				#'kjm-admin-notice-sent-time' => __('Sent Time', 'kjm-admin-notices'),
			);
			$defaults = array_merge($defaults, $custom_fields);
			#$defaults = $this->custom_columns_remove($defaults);
			
			return $defaults;
		}
		
		
		// Render column content
		public function columns_content($column_name, $post_ID) {
			
			//global $wpdb;
			
			if ($column_name == 'kjm-admin-notice-sent') {
				
				$sent = get_post_meta($post_ID, 'kjm-admin-notice-sent');
				$sent = empty($sent[0]) ? 'no': 'yes';
				$status = $sent === 'no' ? 'closed': 'open';
				
				
				if ($sent === 'yes') {
					$sent_time = get_post_meta($post_ID, 'kjm-admin-notice-sent-time');
					$sent_time = empty($sent_time[0]) ? '': $sent_time[0];
									
					$sent_to = get_post_meta($post_ID, 'kjm-admin-notice-sent-to');
					$sent_to_count = count((array) $sent_to[0]);
					$sent_to = empty($sent_to[0]) ? ' - ': implode(', ', $sent_to[0]);
					
					echo '<span class="status_tag '.$status.'" title="'.esc_attr($sent_to).'">'.$sent_to_count.' adresse(s). </span>';
					echo '<span class="'.$sent_time.'">' . $sent_time . '</span>';
				} else {
					
					echo '<span class="">' . $sent . '</span>';
				}
				
				
			}
		}
		
		// resize columns in post listing screen
		public function columns_resize() {
			
			if (true) {
			?>
			<style>
				.edit-php .fixed .column-taxonomy-kjm_notice_cat,
				.edit-php .fixed .column-taxonomy-kjm_notice_tag,
				.edit-php .fixed .column-date {
					width: 15%;
				}
				.edit-php .fixed .column-kjm-admin-notice-sent {
					width: 10%;
				}
			</style>
			<?php 
			}
		}
		
		
		
		/* METABOXES */


		/* Meta box setup function. */
		public function metaboxes_setup() {

			/* Add meta boxes on the 'add_meta_boxes' hook. */
			add_action( 'add_meta_boxes', array($this, 'metaboxes_add'));
		}
		
		
		/* Create one or more meta boxes to be displayed on the post editor screen. */
		public function metaboxes_add() {
			if (is_admin()) {
				
					add_meta_box(
						'kjm_admin_notices_show_notice_to',      // Unique ID
						esc_html__( 'Show Notice To Roles', 'kjm-admin-notices' ),    // Title
						array($this, 'metaboxes_display'),   // Callback function
						'kjm_notice',         // Admin page (or post type)
						'normal',         // Context : side or normal
						'default'         // Priority
					);
					
				//if ($this->get_option('kjm_admin_notices_send_email_active') != 0) {
					add_meta_box(
						'kjm_admin_notices_send_email',      // Unique ID
						esc_html__( 'Send Email', 'kjm-admin-notices' ),    // Title
						array($this, 'metaboxes_display'),   // Callback function
						'kjm_notice',         // Admin page (or post type)
						'normal',         // Context
						'default'         // Priority
					);
				//}
				
					add_meta_box(
						'kjm_admin_notices_display',      // Unique ID
						esc_html__( 'Notice Display', 'kjm-admin-notices' ),    // Title
						array($this, 'metaboxes_display'),   // Callback function
						'kjm_notice',         // Admin page (or post type)
						'normal',         // Context
						'default'         // Priority
					);
				
					add_meta_box(
						'kjm_admin_notices_global_params',      // Unique ID
						esc_html__( 'Global Params', 'kjm-admin-notices' ),    // Title
						array($this, 'metaboxes_display'),   // Callback function
						'kjm_notice',         // Admin page (or post type)
						'normal',         // Context : side or normal
						'default'         // Priority
					);
			}
		}
		
		
		public function kjm_table_array_display($object, $field) {
			
				$output = '';
				
				$post_meta = get_post_meta($object->ID, $field, true);
				
				if (is_array($post_meta)) {
					
					$output .= '<table style="width: 100%;">';
					foreach($post_meta as $name => $data) {
						$data = is_array($data) ? implode(', ', $data): $data;
						$output .= '<tr><th style="width: 20%; text-align: left;">'.$name.'</th><td>'.strip_tags(urldecode($data)).'</td></tr>';
					}
					$output .= '</table>';
				}
				echo $output;
		}
		
		
		/* Display the post meta box. */
		public function metaboxes_display( $object, $box, $return = false ) { 
			
			//wp_nonce_field( basename( __FILE__ ), 'kjm_admin_notices_nonce' ); 
			
			if ($box['id'] == 'kjm_admin_notices_show_notice_to') {
				
				$available_roles = array_merge(array('all' => __('All', 'kjm-admin-notices')), $this->get_roles());
				$show_to_roles = get_post_meta($object->ID,'kjm_admin_notices_show_notice_to',true);
				$count_users = count_users();
				$sent = get_post_meta($object->ID,'kjm-admin-notice-sent',true);
				$disabled = empty($sent) ? '': ' disabled=""';
				
				if (!empty($show_to_roles)) { 
					$show_to_roles = !is_array($show_to_roles) ? array($show_to_roles): $show_to_roles;
					$all_roles = array_unique(array_merge(array_keys($available_roles), array_values($show_to_roles)));
				} else {
					$show_to_roles = array();
					$all_roles = array_keys($available_roles);
				}
				
				echo '<ul>';
				foreach($all_roles as $role) {
					$count = isset($count_users['avail_roles'][$role]) ? $count_users['avail_roles'][$role]: 0;
					$count = 'all' === $role ? $count_users['total_users']: $count;
					$name = isset($available_roles[$role]) ? $available_roles[$role]: ucfirst($role);
					$class = 'all' === $role ? 'id="kjm_admin_notices_show_notice_to_all"': 'class="others"';
					echo '<li><label class="selectit">';
					echo '<input '.$class.' name="kjm_admin_notices_show_notice_to[]"'.$disabled.' type="checkbox" ' . checked( $role, in_array($role, $show_to_roles) ? $role:'', false ) . ' value="'.$role.'" /> '.$name.' ('.$count.')';
					echo '</label></li>';
				}
				echo '</ul>';
				
			} elseif ($box['id'] == 'kjm_admin_notices_send_email') {
				
				$sent = get_post_meta($object->ID,'kjm-admin-notice-sent',true);
				$send_email = get_post_meta($object->ID,'kjm_admin_notices_send_email',true);
				$send_copy_admin = get_post_meta($object->ID,'kjm_admin_notices_send_copy_admin',true);
				$disabled = empty($sent) ? '': ' disabled=""';
				
				echo '<ul>';
				
				echo '<li><label class="selectit">';
				echo '<input name="kjm_admin_notices_send_email"'.$disabled.' type="checkbox" value="1" '.checked($send_email, 1, false).' />';
				echo __('Send Email', 'kjm-admin-notices').'</label></li>';
				
				echo '<li><label class="selectit">';
				echo '<input name="kjm_admin_notices_send_copy_admin"'.$disabled.' type="checkbox" value="1" '.checked($send_copy_admin, 1, false).' />';
				echo __('Send a copy to Admin', 'kjm-admin-notices').'</label></li>';
				
				echo '</ul>';
				
				if (!empty($sent)) $this->columns_content('kjm-admin-notice-sent', $object->ID);
				
			} elseif ($box['id'] == 'kjm_admin_notices_display') {
				
				$hide_title = get_post_meta($object->ID,'kjm_admin_notices_hide_title',true);
				$hide_metas = get_post_meta($object->ID,'kjm_admin_notices_hide_metas',true);
				$hide_dismiss_link = get_post_meta($object->ID,'kjm_admin_notices_hide_dismiss_link',true);
				
				echo '<ul>';
				
				echo '<li><label class="selectit">';
				echo '<input name="kjm_admin_notices_hide_title" type="checkbox" value="1" '.checked($hide_title, 1, false).' />';
				echo __('Hide Title', 'kjm-admin-notices').'</label></li>';
				
				echo '<li><label class="selectit">';
				echo '<input name="kjm_admin_notices_hide_metas" type="checkbox" value="1" '.checked($hide_metas, 1, false).' />';
				echo __('Hide Metas (author and date)', 'kjm-admin-notices').'</label></li>';
				
				echo '<li><label class="selectit">';
				echo '<input name="kjm_admin_notices_hide_dismiss_link" type="checkbox" value="1" '.checked($hide_dismiss_link, 1, false).' />';
				echo __('Hide Dismiss Link', 'kjm-admin-notices').'</label></li>';
				
				echo '</ul>';
				
			} else {
				$this->kjm_table_array_display($object, $box['id']);
			}
		}
		
		
		public function active_plugins($show_version=false) {
			
			$plugins = get_option('active_plugins');
			$active_plugins = array();
			
			foreach($plugins as $path) {
				$plugin_version = '';
				
				if ($show_version) {
					$plugin_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$path);
					$plugin_version = ' ('.$plugin_data['Version'].')';
				}
				$plugin_parts = explode('/', $path);
				$active_plugins[] = $plugin_parts[0].$plugin_version;
			}
			
			return $active_plugins;
		}
		
		
		public function save_metaboxes($post_id, $post) {
			
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
			
			// Only fire on the right CPT.
			if (!isset($post->post_type) || "kjm_notice" !== $post->post_type) return;
			
			foreach($this->custom_fields['kjm_notice'] as $custom_field) {
				if ('kjm_admin_notices_global_params' === $custom_field) {
					if (!get_post_meta($post_id, 'kjm_admin_notices_global_params',true)) {
						
						$template = get_option('template');
						$stylesheet = get_option('stylesheet');
						$theme = wp_get_theme($template);
						$child_theme = wp_get_theme($stylesheet);
						
						$params = array(
							'wordpress_version' => get_bloginfo('version'),
							'active_plugins' => $this->active_plugins(true),
							'theme' => $template.' ('.$theme->get('Version').')',
							'child_theme' => $template !== $template ? $stylesheet.' ('.$child_theme->get('Version').')': __('no'),
						);
						update_post_meta($post_id, "kjm_admin_notices_global_params", $params);
					}
				} else { 
					if (!isset($_POST[$custom_field]) || empty($_POST[$custom_field])) :
						delete_post_meta($post_id, $custom_field);
					else :
						update_post_meta($post_id, $custom_field, $_POST[$custom_field]);
					endif;
				}
			}
			
		}
		
		
		// See : http://wordpress.stackexchange.com/a/118976
		// See : http://wpsnipp.com/index.php/functions-php/force-custom-post-type-to-be-private/
		public function force_type_private( $new_status, $old_status, $post ) { 
				if ( $post->post_type == 'kjm_notice' && $new_status == 'publish' && $old_status  != $new_status ) {
						$post->post_status = 'private';
						wp_update_post( $post );
				}
		}
	

} // End of Class

endif; // Endif class exists.
