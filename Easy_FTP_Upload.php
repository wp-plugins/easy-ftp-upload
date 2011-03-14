<?php  
/* 
Plugin Name: Easy FTP Upload 
Plugin URI: http://www.bucketofwombats.com/easy-ftp-upload-for-wordpress
Description: Allows direct uploading via FTP from a page or post - good for larger files such as those needed by print shops and graphic designers
Version: 2.5 
Author: Jenny Chalek 
Author URI: http://www.bucketofwombats.com/
*/

/*
Easy FTP Upload plugin for WordPress
Copyright 2011 Jenny Chalek (email:pandorawombat@gmail.com)

This file is part of the Easy FTP Upload WordPress plugin.

Easy FTP Upload is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or 
any later version.

Easy FTP Upload is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Easy FTP Upload.  If not, see <http://www.gnu.org/licenses/>.
*/

include_once 'Easy_FTP_Admin.php'; //code for the options menu

error_reporting(E_ERROR); //avoid displaying "warnings" that are irrelevant to end user

add_action('wp_print_styles', 'efu_add_stylesheet');
add_action('wp_print_scripts', 'efu_add_script');

function efu_add_stylesheet () {
	//load the .css file
	wp_register_style ('Easy_FTP_Upload_css', '/wp-content/plugins/easy-ftp-upload/Easy_FTP_Upload.css');
	wp_enqueue_style ('Easy_FTP_Upload_css', '/wp-content/plugins/easy-ftp-upload/Easy_FTP_Upload.css');
}

function efu_add_script() {
	//load the .js file
	wp_register_script('easy_ftp_upload_js', '/wp-content/plugins/easy-ftp-upload/Easy_FTP_Upload.js');
	wp_enqueue_script('easy_ftp_upload_js', '/wp-content/plugins/easy-ftp-upload/Easy_FTP_Upload.js');
}

//register wp shortcode
add_shortcode("easy_ftp_upload", "efu_easy_ftp_upload_handler");

//Main functions of the plugin
//parse data from shortcode and assign defaults if necessary
function efu_easy_ftp_upload_handler($atts) {
	shortcode_atts(array(
		'server' => '',
		'ftp_user_name' => '',
		'ftp_user_pass' => '',
		'notify_email' => ''
	), $atts); //leave this loop here for later use possibly to make versions or something
		
	return efu_easy_ftp_upload_function();
	//send back data to replace shortcode in post
}

function efu_upload_file_FTP ($server, $ftp_user_name, $ftp_user_pass, $dest, $source, $client_dir)  {
	$ret_val = ''; //default return starting value assumes the worst
	//establish connection and login
	
	//connection moved to its own function to allow for multiple-format attempts
	$connection = efu_connection($server);
	if (!$connection) return 'Connection attempt failed!'; //end on failure
	
	//if no failure, then continue
	//login moved to its own function to allow for multiple-format attempts
	$login = efu_login($connection, $ftp_user_name, $ftp_user_pass);
	//$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);

	if (!$login) $ret_val = 'Login attempt failed!';  //if failed to login both with and without domain name

	else {
		$success = ftp_chdir($connection, $client_dir);
		if (!$success) { //if it fails to change dir, it probably doesn't exist, so create it
			$success_make = ftp_mkdir($connection, $client_dir);
			if (!$success_make) $ret_val = 'Could not create directory!'; //if directory creation fails, program gracefully dies
		}
		if ($ret_val == "") {
			$success = ftp_chdir($connection, $client_dir); //if success, now try to access the directory just made
			if (!$success) $ret_val = 'Could not access directory!'; //if directory access fails, program gracefully dies
		}
	}
	
	if ($ret_val == "") {//if no failure, then continue
		// turn passive mode on
		ftp_pasv($connection, true);
		
		//set the permissions on the newly created directory, to avoid permission-based problems
		chmod($client_dir, 0777);
		
		//upload the file to the default directory
		$upload = ftp_put($connection, $dest, $source, FTP_BINARY);
		if (!$upload) {
			$ret_val .= 'FTP upload failed! Sorry for any inconvenience - please contact us for assistance.';
		} else {
			$ret_val .= 'FTP upload succeeded!';
		}
	}
	
	//close the connection
	ftp_close($connection);
	
	return $ret_val;
}

function efu_filename_safe($filename) {
	$temp = $filename;
	
	// Lower case
	$temp = strtolower($temp);
	
	// Replace spaces with a '_'
	$temp = str_replace(" ", "_", $temp);
	
	// Loop through string, salvage only "safe" alphanumeric characters
	$result = '';
	for ($i=0; $i<strlen($temp); $i++) {
		if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) {
			$result = $result . $temp[$i];
		}
	}

	// Return filename
	return $result;
}

function efu_connection($server) {
	$connection = ftp_connect($server, '21'); //attempt #1, root domain only
	if ($connection) return $connection; //return ftp stream if it worked
	
	//keep going otherwise
	$tryThis = "ftp.".$connection;
	$connection = ftp_connect($tryThis, '21');
	if ($connection) return $connection; //return ftp stream if it worked
	
	//keep going otherwise
	$tryThis = "www.".$connection; //our last attempt
	$connection = ftp_connect($tryThis, '21');
	return $connection; //returns ftp stream if it worked or false if not
}

function efu_login($connection, $ftp_user_name, $ftp_user_pass) {
	$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
	if (!$login) { //if the login fails initially, try again after stripping off any domain name at the end
		$login = ftp_login($connection, efu_username_scrub($ftp_user_name), $ftp_user_pass);
	}
	return $login; //return the final result whether successful or not
}

function efu_domain_scrub($server) {
	$strWork = trim(strtolower($server)); //convert to lowercase
	
	if (substr($strWork, 0, 4) == "http") {
		$strWork = substr($strWork,4); //strip http if it's there
	}
	$strWork = trim($strWork,"/"); //trim off any leading or training slash marks
	if (substr($strWork, 0, 4) == "www.") {
		$strWork = substr($strWork,4); //strip www. if it's there
	}
	if (substr($strWork, 0, 4) == "ftp.") {
		$strWork = substr($strWork,4); //strip ftp. if it's there
	}
	// Return stripped-down domain name
	return $strWork;
}

function efu_username_scrub($username) {
	$whereAt = stripos($username, "@");
	if ($whereAt) { //if the character @ is found, strip off domain name
		$username = substr($username, 0, $whereAt);
	}
	return $username;
}

function efu_easy_ftp_upload_function() {
	//if this is AFTER the POST action, proceed to actual data parse, upload, etc.
	if (!empty($_POST)) {
		return efu_easy_ftp_upload_post();
	} else { //otherwise, link to and load the html form
		ob_start();
		include('Easy_FTP_Upload.html');
		$web_form = ob_get_clean();
		return $web_form;
	}
}

function efu_easy_ftp_upload_post() {
	//gather data from saved settings and assign to variables	
	$atts = get_option('efu_plugin_options');
	$server = $atts['efu_server'];
	$ftp_user_name = $atts['efu_username'];
	$ftp_user_pass = $atts['efu_user_pass'];
	$notify_email = $atts['efu_notify'];
	
	//gather data from form post and assign to variables
	$company = $_POST['company_name'];
	$contact = $_POST['contact_name'];
	$email_add = $_POST['email_add'];
	$purpose = $_POST['purpose']; //radio button to choose whether file is for quote or live job or other
	$notes = $_POST['notes'];
	
	//parse name for client directory - favor company name, but use contact name if none
	if (!$company) $who_is = $contact;
	else $who_is = $company;
	//must convert the client name to something filename safe to create directory
	$client_dir = efu_filename_safe($who_is);
	
	$source = $_FILES['file']['tmp_name']; // retrieve name of the source file to be uploaded
	$dest = $_FILES['file']['name'];
	// call the upload function itself , gather back success or failure message
	$successful = efu_upload_file_FTP($server, $ftp_user_name, $ftp_user_pass, $dest, $source, $client_dir);
	
	// construct the email notification data
	$notifymessage = 'Contact Name: '.$contact.PHP_EOL;
	$notifymessage .= 'Email Address: '.$email_add.PHP_EOL;
	$notifymessage .= 'Purpose of File: '.$purpose.PHP_EOL;
	$notifymessage .= 'Other Notes or Instructions: '.$notes;
	$notifysubject = 'FTP Upload Notification';
	$notifyheader = 'From: Uploads <'.$email_add.'>'.PHP_EOL;
	
	// call the email notification function with required arguments
	$mail_sent = wp_mail($notify_email, $notifysubject, $notifymessage, $notifyheader);
	If ($mail_sent) {
		$successful .= '<br/>Notification email sent.';
	} else {
		$successful .= '<br/>Notification email failed to send!'.PHP_EOL;
		$successful .= 'Even if your file uploaded successfully, '.PHP_EOL;
		$successful .= 'for the sake of expediency you may wish '.PHP_EOL;
		$successful .= 'to contact us and alert us to your upload.'.PHP_EOL;
	}
	return '<br/><p class="EFU_notify">'.$successful.'</p>';
}
?>