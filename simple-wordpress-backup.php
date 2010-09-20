<?php

/*

Plugin Name: Simple Wordpress Backup
Plugin URI: http://www.bannerweb.ch/
Description: Simple Wordpress Backup allows you to back up your Wordpress Database with just one click!
Version: 0.1
Author: Bannerweb GmbH
Author URI: http://www.bannerweb.ch/

*/

# Functions
# -----------------------------------------------------

// Display incompatibility notification
function swb_incompatibility_notification(){
	
	echo '<div id="message" class="error">
	
	<p><b>The &quot;Simple Wordpress Backup&quot; plugin does not work on this wordpress installation!</b></p>
	<p>Please check your installation for following minimum requirements:</p>
	
	<p>
	- Wordpress version 2.7 or higer<br />
	- PHP version 5.2 or higher<br />
	- PHP extension CURL 7.0 or higher
	</p>
	
	<p>Do you need help? Contact us on twitter <a href="http://twitter.com/bannerweb">@bannerweb</a></p>
	
	</div>';
}

# Compatibilty check / plugin initialization
# -----------------------------------------------------

// get wordpress version number and fill it up to 9 digits
$int_wp_version = preg_replace('#[^0-9]#', '', get_bloginfo('version'));
while(strlen($int_wp_version) < 9){
	
	$int_wp_version .= '0'; 
}

// get php version number and fill it up to 9 digits
$int_php_version = preg_replace('#[^0-9]#', '', phpversion());
while(strlen($int_php_version) < 9){
	
	$int_php_version .= '0'; 
}

// Check if CURL is loaded, get version number and fill it up to 9 digits
if(extension_loaded('curl') === true){
	
	$arr_curl_version = curl_version();
	$int_curl_version = preg_replace('#[^0-9]#', '', $arr_curl_version['version']);
	while(strlen($int_curl_version) < 9){
		
		$int_curl_version .= '0'; 
	}
}

// Check overall plugin compatibility
if(	$int_wp_version >= 270000000 and 	// Wordpress version > 2.7
	$int_php_version >= 520000000 and 	// PHP version > 5.2
	$int_curl_version >= 700000000 and 	// CURL version > 7.0
	defined('ABSPATH') and 				// Plugin is not loaded directly
	defined('WPINC')){					// Plugin is not loaded directl

	// Load class file
	require_once(dirname(__FILE__).'/swb.class.php');
	
	// Build admin menu
	add_action('admin_menu', array('SWB', 'buildAdminMenu'), 1);
	
}

// Plugin is not compatible with current configuration
else{
	
	// Display incompatibility information
	add_action('admin_notices', 'swb_incompatibility_notification');
}

?>