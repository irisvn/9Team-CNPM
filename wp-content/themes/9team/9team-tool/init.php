<?php 

// Require
// we'll use the dbDelta function in wp-admin/includes/upgrade.php (we'll have to load this file, as it is not loaded by default)
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// Load Feature in ĐTV
require_once( get_stylesheet_directory() . '/9team-tool/admin-page.php');

