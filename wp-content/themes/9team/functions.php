<?php
/**
 * 9Team Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package 9Team
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_9TEAM_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( '9team-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_9TEAM_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 99999999 );

/*
Custom 9Team
*/ 

// CSS, JS

function bootstrap() {
	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/bootstrap.min.js', array( 'jquery' ));
}

function owl_carousel() {
	wp_enqueue_style( 'owl_carousel', get_stylesheet_directory_uri() . '/9team-tool/css/owl.carousel.min.css' );
	wp_enqueue_style( 'owl_carousel', get_stylesheet_directory_uri() . '/9team-tool/css/owl.theme.default.min.css' );
    wp_enqueue_script( 'owl_carousel', get_stylesheet_directory_uri() . '/9team-tool/js/owl.carousel.min.js', array( 'jquery' ));
}

add_action( 'wp_enqueue_scripts', 'bootstrap', 99 );
add_action( 'wp_enqueue_scripts', 'owl_carousel', 99 );

// Class, Function

require_once( get_stylesheet_directory() . '/9team-tool/init.php' );

add_theme_support( 'post-thumbnails' );
if ( function_exists('add_image_size') ) {
    add_image_size( 'homepage_slide_big_m', 800, 300, true ); //(cropped)
    add_image_size( 'homepage_slide_small_m', 385, 175, true ); //(cropped)
}
add_filter( 'image_size_names_choose', 'my_custom_sizes' );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'homepage_slide_big_m' => __('Ảnh Slide lớn cho mobile'),
        'homepage_slide_small_m' => __('Ảnh Slide nhỏ cho mobile'),
    ) );
}