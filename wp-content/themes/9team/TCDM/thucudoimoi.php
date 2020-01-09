<?php

class ThuCuDoiMoi 
{
    public function __construct() {
        add_action( 'init', array( $this, 'postTypeTCDM'));
        add_action( 'init', array( $this, 'taxonomyTCDM'));
        add_action( 'rest_api_init', array( $this, 'apiTCDM' ));
    }

    public function postTypeTCDM() {
 
        $label = array(
            'name'          => 'Thu cũ đổi mới',
            'singular_name' => 'Sản phẩm cũ',
            'add_new'       => 'Thêm sản phẩm thu cũ',
            'add_new_item'  => 'Thêm sản phẩm thu cũ'
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
            'menu_position' => 3,
            'menu_icon' => 'dashicons-update',
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post'
        );
    
        register_post_type( 'tcdm' , $args );
    
    }
    
    public function taxonomyTCDM() {
     
        $labels = array(
            'name' => 'Hãng TCDM',
            'singular' => 'Hãng TCDM',
            'menu_name' => 'Hãng TCDM'
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
        register_taxonomy('category-tcdm-product', 'product', $args);
    
    }

    public function apiTCDM() {
        register_rest_route( 
            'api-tcdm/v1', 
            'newProducts', 
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'getNewProducts'),
            )
        );
        register_rest_route( 
            'api-tcdm/v1', 
            'oldProducts', 
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'getOldProducts'),
            )
        );
    }

    /*
        New Products Json Format:
        - Products:
            -- 0:
                --- ProductID: "18373"
                --- DefaultID: "18374"
                --- ProductName: "iPhone 11 Pro Max 512GB Chính hãng"
                --- Url: "https://cellphones.com.vn/iphone-11-pro-max-512gb.html"
                --- Price: 43990000
                --- SalePrice: 39990000
                --- Group: "apple"
                --- Discount: 0
                --- Discount_2: 1000000
                --- Smember: 0
                --- Img: ""
        - Groups:
            -- 0: "apple"
            -- 1: "apple ipad"
            -- 2: "apple watch"
            -- 3: "apple airpods"
    */

    public function getNewProducts() {
        header('Content-Type: application/json');
        $data = array();
        $i = 0;

        $args = array( // Get sản phẩm có Post_type = 'tcdm' và hiện tại đang bật
            'post_type' => 'product',
            'meta_query' => array(
                array(
                    'key' => 'thuoc_thu_cu_doi_moi',
                    'value' => '1',
                    'compare' => '=',
                )
            )
        );
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) :

            while ( $the_query->have_posts() ) : $the_query->the_post();

                $product = wc_get_product( get_the_ID() );
                $data['products'][$i] = array(
                    'ProductID'     => get_the_ID(),
                    'DefaultID'     => get_the_ID(),
                    'ProductName'   => get_the_title(),
                    'Price'         => (int)$product->get_regular_price(),
                    'SalePrice'     => $product->get_sale_price() ? (int)$product->get_sale_price() : 0,
                    'Group'         => '',
                    'Img'           => get_the_post_thumbnail_url()
                );
                
                $_termsOfId = get_the_terms( (int)get_the_ID(), 'category-tcdm-product' );
                foreach ( $_termsOfId as $_termOfId ) {
                    $data['products'][$i]['Group'] = strtolower($_termOfId->name);
                    break;
                }

                $i++;

            endwhile;
        endif;

        wp_reset_postdata();

        // Get all Term in TCDM

        $_terms = get_terms('category-tcdm-product');
        foreach($_terms as $_term) {
            $data['groups'][] = strtolower($_term->name);
        }

        echo json_encode($data);
    }

    /*
        Old Products Json Format:
        - Products:
            -- 0:
                ---ProductID: 3290
                ---ProductName: "APPLE iPhone 6_16G"
                ---Thuloai_1: 1500000
                ---Thuloai_2: 1100000
                ---Thuloai_3: 600000
                ---Thuloai_4: 0
                ---Thuloai_5: 0
                ---A80: 0
                ---Note10_s10: 0
                ---Excluded_groups: [""]
                ---Group: "apple"
                ---Img: ".jpg"
        - Groups:
            -- 0: "apple"
            -- 1: "apple ipad"
            -- 2: "apple watch"
            -- 3: "apple airpods"
    */

    public function getOldProducts() {
        header('Content-Type: application/json');
        $data = array();
        $i = 0;

        $args = array( // Get sản phẩm có Post_type = 'tcdm' và hiện tại đang bật
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

        if ( $the_query->have_posts() ) :

            while ( $the_query->have_posts() ) : $the_query->the_post();
                $data['products'][$i] = array(
                    'ProductID'     => get_the_ID(),
                    'ProductName'   => get_the_title(),
                    'Thuloai_1'     => get_field( "gia_loai_1" ),
                    'Thuloai_2'     => get_field( "gia_loai_2" ),
                    'Thuloai_3'     => get_field( "gia_loai_3" ),
                    'Thuloai_4'     => get_field( "gia_loai_4" ),
                    'Thuloai_5'     => get_field( "gia_loai_5" ),
                    'Group'         => '',
                    'Img'           => get_the_post_thumbnail_url()
                );
                
                $_termsOfId = get_the_terms( (int)get_the_ID(), 'category-tcdm' );
                foreach ( $_termsOfId as $_termOfId ) {
                    $data['products'][$i]['Group'] = strtolower($_termOfId->name);
                    break;
                }

                $i++;

            endwhile;
        endif;

        wp_reset_postdata();

        // Get all Term in TCDM

        $_terms = get_terms('category-tcdm');
        foreach($_terms as $_term) {
            $data['groups'][] = strtolower($_term->name);
        }

        echo json_encode($data);
    }
    
}

$tcdm = new ThuCuDoiMoi();
