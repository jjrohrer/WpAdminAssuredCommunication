<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.kajoom.ca/
 * @since      1.0.0
 *
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/includes
 * @author     Marc-Antoine Minville <support@kajoom.ca>
 */
class Kjm_Admin_Notices_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		

		
		if ( version_compare( phpversion(), '5.0', '<' ) ) {
			trigger_error('', E_USER_ERROR);
		}
		
		
		/* Create default categories */
		$kjm_notices_accept_values = array("success", "info", "warning", "error");
		foreach ($kjm_notices_accept_values as $term) {
			
			$term_exists = term_exists($term, 'kjm_notice_cat');
			if (empty($term_exists)) wp_insert_term($term, 'kjm_notice_cat');
		}
		
		
	}
}

// display error message to users
if ($_GET['action'] == 'error_scrape') {                                                                                                   
		die("Sorry,  Plugin requires PHP 5.0 or higher. Please deactivate Plugin.");                                 
}





