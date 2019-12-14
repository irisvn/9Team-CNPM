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

function create_tcdm() {
 
    $label = array(
        'name' => 'Thu cũ đổi mới',
        'singular_name' => 'Sản phẩm cũ'
    );

    $args = array(
        'labels' => $label,
        'description' => 'Post type thu cũ đổi mới điện thoại',
        'supports' => array(
            'title',
            'author',
            'thumbnail',
        ),
        'taxonomies' => array( 'category-tcmd' ),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => '',
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'tcdm' , $args );

}

add_action( 'init', 'create_tcdm' );

function create_taxonomy_tcdm() {
 
    $labels = array(
        'name' => 'Brands',
        'singular' => 'Brand',
        'menu_name' => 'Brand'
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );

    register_taxonomy('category-tcdm', 'tcdm', $args);

}

// Hook into the 'init' action
add_action( 'init', 'create_taxonomy_tcdm', 0 );



function test_wp() {
 
    $args = array(
        'post_type' => 'tcdm',
    );
    $the_query = new WP_Query( $args );

    // echo "<pre>";
    // print_r($the_query);
    // echo "</pre>";
    // exit();
    // // Reset Post Data

    if ( $the_query->have_posts() ) :
        while ( $the_query->have_posts() ) : $the_query->the_post();
            echo "ID: ".get_the_ID()."\n";
            // echo "<pre>";
            // var_dump(get_the_terms( (int)get_the_ID(), 'category-tcdm' ));
            // exit();
            $_terms = get_the_terms( (int)get_the_ID(), 'category-tcdm' );
            foreach ( $_terms as $_term ) {
                echo "Term: ". $_term->name."\n";
            }
        endwhile;
    endif;
        
    // foreach($the_query as $_item) {
    //     echo $_item->ID;
    // }

    wp_reset_postdata();

    // return $the_query;
}
add_shortcode('tcdm', 'test_wp');