<?php
/*
Plugin Name: Mikes Woocommerce account generator
Plugin URI: http://michaelwing.co.uk/
Description: This plugin is a user account generator upon sale and auto-enrols users to a specific course
Version: 1.0.0
Author: Mike Wing
Author URI: http://michaelwing.co.uk/
License: GPLv2
*/
include "classes/confinedspaces.php";

add_filter('user_profile_update_errors','allow_no_email',10,3);
	function allow_no_email($errors, $update, $users) {
		if (isset($errors->errors[‘invalid_email’]) &&
			$users->user_email=’a’) { unset ($errors->errors);
									 $users->user_email=”;
									}
	}

add_action('init','do_confined');

add_action('wp_head', 'confinedcheck');
