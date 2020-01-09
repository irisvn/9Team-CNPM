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
require_once( get_stylesheet_directory() . '/TCDM/thucudoimoi.php' );

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

/*Sắp xếp lại thứ tự các field*/
add_filter("woocommerce_checkout_fields", "order_fields");
function order_fields($fields) {
 
  //Shipping
  $order_shipping = array(
    "shipping_last_name",
    "shipping_phone",
    "shipping_address_1"
  );
  foreach($order_shipping as $field_shipping)
  {
    $ordered_fields2[$field_shipping] = $fields["shipping"][$field_shipping];
  }
  $fields["shipping"] = $ordered_fields2;
  return $fields;
}
 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields',99 );
function custom_override_checkout_fields( $fields ) {
  unset($fields['billing']['billing_company']);
  unset($fields['billing']['billing_first_name']);
  unset($fields['billing']['billing_postcode']);
  unset($fields['billing']['billing_country']);
  unset($fields['billing']['billing_city']);
  unset($fields['billing']['billing_state']);
  unset($fields['billing']['billing_address_2']);
  $fields['billing']['billing_last_name'] = array(
    'label' => __('Họ và tên', 'devvn'),
    'placeholder' => _x('Nhập đầy đủ họ và tên của bạn', 'placeholder', 'devvn'),
    'required' => true,
    'class' => array('form-row-wide'),
    'clear' => true
  );
  $fields['billing']['billing_address_1']['placeholder'] = 'Ví dụ: Số xx Ngõ xx Phú Kiều, Bắc Từ Liêm, Hà Nội';
 
  unset($fields['shipping']['shipping_company']);
  unset($fields['shipping']['shipping_postcode']);
  unset($fields['shipping']['shipping_country']);
  unset($fields['shipping']['shipping_city']);
  unset($fields['shipping']['shipping_state']);
  unset($fields['shipping']['shipping_address_2']);
 
  $fields['shipping']['shipping_phone'] = array(
    'label' => __('Điện thoại', 'devvn'),
    'placeholder' => _x('Số điện thoại người nhận hàng', 'placeholder', 'devvn'),
    'required' => true,
    'class' => array('form-row-wide'),
    'clear' => true
  );
  $fields['shipping']['shipping_last_name'] = array(
    'label' => __('Họ và tên', 'devvn'),
    'placeholder' => _x('Nhập đầy đủ họ và tên của người nhận', 'placeholder', 'devvn'),
    'required' => true,
    'class' => array('form-row-wide'),
    'clear' => true
  );
  $fields['shipping']['shipping_address_1']['placeholder'] = 'Ví dụ: Số xx Ngõ xx Phú Kiều, Bắc Từ Liêm, Hà Nội';
 
  return $fields;
}
 

function test_wp() {
 
    $args = array(
        'post_type' => 'tcdm',
        'meta_query' => array(
            array(
                'key' => 'on_off',
                'value' => '1',
                'compare' => '=',
            )
        )
    );
    $the_query = new WP_Query( $args );

    echo "<pre>";
    // print_r($the_query);
    // echo "</pre>";
    // exit();
    // // Reset Post Data

    if ( $the_query->have_posts() ) :
        while ( $the_query->have_posts() ) : $the_query->the_post();
            // echo "<pre>";
            echo "ID: ".get_the_ID()."\n";
            echo "Name: ".get_the_title()."\n";
            echo "Img: ".get_the_post_thumbnail_url()."\n";
            echo "Giá 1: ".get_field( "gia_loai_1" )."\n";
            echo "Giá 1: ".get_field( "gia_loai_2" )."\n";
            echo "Giá 1: ".get_field( "gia_loai_3" )."\n";
            echo "Giá 1: ".get_field( "gia_loai_4" )."\n";
            echo "Giá 1: ".get_field( "gia_loai_5" )."\n";
            // var_dump(get_the_terms( (int)get_the_ID(), 'category-tcdm' ));
            // exit();
            $_terms = get_the_terms( (int)get_the_ID(), 'category-tcdm' );
            foreach ( $_terms as $_term ) {
                echo "Term: ". $_term->name."</br>";
                // exit();
            }
            // exit();
        endwhile;
    endif;
        
    // foreach($the_query as $_item) {
    //     echo $_item->ID;
    // }

    wp_reset_postdata();

    // return $the_query;
}
add_shortcode('tcdm', 'test_wp');