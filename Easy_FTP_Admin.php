<?php
add_action('admin_menu', 'efu_plugin_menu');
register_activation_hook(__FILE__, 'get_efu_defaults');

function efu_plugin_menu() {
	add_options_page('Easy FTP Upload options', 'Easy FTP Upload', 'manage_options', __FILE__, 'efu_plugin_options');
	add_action('admin_init', 'efu_register_settings');
}

// Define default option settings
function get_efu_defaults() {
	$tmp = get_option('efu_plugin_options');
    if(($tmp['chkbox1']=='on')||(!is_array($tmp))) {
		$arr = array("efu_server"=>"domain.com", "efu_username" => "FTP account username", "efu_user_pass" => "FTP acount password", "efu_notify" => "Notification email address");
		update_option('efu_plugin_options', $arr);
	}
}

function efu_plugin_options() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Easy FTP Upload Options</h2>
		Fill in the information below with your FTP server information and desired notification address.
		<form action="options.php" method="post">
		<?php settings_fields('efu_plugin_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" 
            value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

function efu_register_settings() {
	register_setting('efu_plugin_options', 'efu_plugin_options', 'efu_plugin_options_validate');
	add_settings_section('efu_section', 'Easy FTP Upload Settings', 'efu_plugin_section_text', __FILE__);
	add_settings_field('efu_server', 'FTP server:', 'efu_ftp_server_validate', __FILE__, 'efu_section');
	add_settings_field('efu_username', 'FTP username:', 'efu_ftp_username_validate', __FILE__, 'efu_section');
	add_settings_field('efu_user_pass', 'Password:', 'efu_ftp_password_validate', __FILE__, 'efu_section');
	add_settings_field('efu_notify', 'Notification email address:', 'efu_email_validate', __FILE__, 'efu_section');
}

function efu_ftp_server_validate() {
	$options = get_option('efu_plugin_options');
	echo "<input id='efu_server' name='efu_plugin_options[efu_server]' size='60' type='text' value='{$options['efu_server']}' />";
}

function efu_ftp_username_validate() {
	$options = get_option('efu_plugin_options');
	echo "<input id='efu_username' name='efu_plugin_options[efu_username]' size='60' type='text' value='{$options['efu_username']}' />";
}

function efu_ftp_password_validate() {
	$options = get_option('efu_plugin_options');
	echo "<input id='efu_user_pass' name='efu_plugin_options[efu_user_pass]' size='60' type='pass' value='{$options['efu_user_pass']}' />";
}

function efu_email_validate() {
	$options = get_option('efu_plugin_options');
	echo "<input id='efu_notify' name='efu_plugin_options[efu_notify]' size='60' type='text' value='{$options['efu_notify']}' />";
}

function efu_plugin_section_text() {
}

function efu_plugin_options_validate($input) {
	$efu_new_input['efu_server'] = $input['efu_server'];
	$efu_new_input['efu_username'] = $input['efu_username'];
	$efu_new_input['efu_user_pass'] = $input['efu_user_pass'];
	$efu_new_input['efu_notify'] = $input['efu_notify'];
	
	return $efu_new_input;
}
?>