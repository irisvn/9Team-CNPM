<?php 
// Add Item to Admin Menu

add_action( 'admin_menu', 'iris_custom_add_menu_admin' );
function iris_custom_add_menu_admin() {
  add_menu_page( '9Team - Tools', '9Team - Tools', 'manage_options', '9team-setting', 'create_admin_page', 'dashicons-smiley', 3 );
  add_submenu_page('9team-setting', 'Các khuyến mại', 'Các khuyến mại', 'manage_options', 'slide-promotion', 'option_PromotionSlide_in_admin');
}

function create_admin_page() {
	return;
}

require_once( get_stylesheet_directory() . '/9team-tool/promotion-slide/option-Slide.php');




