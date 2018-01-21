<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.kajoom.ca/
 * @since      1.0.0
 *
 * @package    Kjm_Admin_Notices
 * @subpackage Kjm_Admin_Notices/admin/partials
 */
if ( ! defined( 'WPINC' ) ) {
	die;	// If this file is called directly, abort.
}

if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.', 'kjm-admin-notices') );
}

global $wpdb;

$Kjm_Admin_Notices_Admin = Kjm_Admin_Notices_Admin::get_instance();

if (!empty($Kjm_Admin_Notices_Admin->message)) echo $Kjm_Admin_Notices_Admin->message;

$settings_section = $Kjm_Admin_Notices_Admin->settings_fields_display();

$post_type_url = admin_url('edit.php?post_type=kjm_notice');

$cpt_active = $Kjm_Admin_Notices_Admin->get_option($Kjm_Admin_Notices_Admin->plugin_name.'_kjm_notice_active');
?>
<div class="wrap">
	
    <h2><?php _e('KJM Admin Notices Settings', 'kjm-admin-notices'); ?></h2>
 
		<div class="tablenav">
				<div class="tablenav-pages" style="float: left;">
					<span><?php if (!empty($cpt_active)) echo '<a href="'.$post_type_url.'">'.__('Manage KJM Admin Notices', 'kjm-admin-notices').'</a>'; ?></span>
					<?php //echo $log_export_link; ?>
				</div>
				<div class="tablenav-pages">
					 
				</div>
		</div>


		<form name="kjm_admin_notices_form" method="post">
				<table class="form-table">
					
						<?php echo $settings_section; ?>
					
				</table>
				<p class="submit">
						<input type="submit" name="kjm_admin_notices_settings_saved" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'kjm-admin-notices'); ?>" />
				</p>
		</form>
		
		
</div> <!-- end .wrap -->
<?php
