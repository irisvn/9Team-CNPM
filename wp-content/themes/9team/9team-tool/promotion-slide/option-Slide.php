<?php 
require_once(get_stylesheet_directory() . '/9team-tool/promotion-slide/function-Slide.php');

function option_PromotionSlide_in_admin() {
	$khoiTao = new PromotionSlide();
	$khoiTao->create_db();
	$khoiTao->delete_db();
	$khoiTao->delete_cache();
?>
    <form method="POST" class="ast-row" style="margin: 0;">
    	<div class="col-sm-4 col-xs-6">
	        <?php
	            submit_button( 'Khởi tạo Database','primary', 'creat-db-slide' );
	        ?>
    	</div>
    	<div class="col-sm-4 col-xs-6">
	        <?php
	            // submit_button( 'Xoá Database', 'primary', 'delete-db-slide' );
	        ?>
    	</div>
    	<div class="col-sm-4 col-xs-6">
	        <?php
	            submit_button( 'Xoá Cache', 'primary', 'delete-cache-slide' );
	        ?>
    	</div>
    </form>
<?php 
	
	$khoiTao->admin_slider_banner();

}

if ( isset($_GET['page']) && $_GET['page'] == "slide-promotion" ) {
	function load_admin_bootstrap_style() {
	    wp_register_style( 'load_admin_bootstrap_style' , get_stylesheet_directory_uri() . '/bootstrap/bootstrap.min.css');
	    wp_enqueue_style( 'load_admin_bootstrap_style' );
	    wp_register_style('custom_admin_style', get_stylesheet_directory_uri() . '/9team-tool/css/admin.css');
	    wp_enqueue_style( 'custom_admin_style' );
	}
	add_action( 'admin_enqueue_scripts', 'load_admin_bootstrap_style', 1 );
}

