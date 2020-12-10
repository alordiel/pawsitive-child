<?php

define( 'PAW_ABS', __DIR__ );
define( 'PAW_URL', get_stylesheet_directory_uri() );

include_once 'woocommerce.php';
include_once 'checkout-page/cpt-organizations.php';

function my_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_parent_theme_file_uri( 'style.css' ) );
    wp_enqueue_style( 'child-style',
        get_theme_file_uri( 'style.css' ),
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

