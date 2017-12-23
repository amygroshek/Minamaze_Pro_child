<?php

/**
 * Auto Complete all WooCommerce orders.
 * See https://docs.woocommerce.com/document/automatically-complete-orders/
 */
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) {
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}

/**
 * Add custom JS
 */
function mpc_get_scripts() {
    wp_enqueue_script(
        'custom',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array('jquery')
    );
}
add_action('wp_enqueue_scripts', 'mpc_get_scripts');

add_action( 'switch_theme', 'jt_switch_theme_update_mods' );
