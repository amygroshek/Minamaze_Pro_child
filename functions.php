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

/**
 * If newly selected theme is a child theme, copy settings over from parent.
 * @return null
 */
function jt_switch_theme_update_mods() {
	if ( is_child_theme() && false === get_theme_mods() ) {
		$mods = get_option( 'theme_mods_' . get_option( 'template' ) );
		if ( false !== $mods ) {
			foreach ( (array) $mods as $mod => $value ) {
				if ( 'sidebars_widgets' !== $mod )
					set_theme_mod( $mod, $value );
			}
		}
	}
}
add_action( 'switch_theme', 'jt_switch_theme_update_mods' );

/**
 * Overrides a very annoying and uncool implementation of thinkup_woo_wpMenuCart()
 * @return string HTML markup for additional cart item
 */
function laurelyoga_menu_item() {
    global $woocommerce;

    if ($woocommerce->cart->cart_contents_count == 0) {
        return;
    }

    // Set variables to avoid php non-object notice error
    // $menu_item = NULL;
    //
    // // $item_data = $this->shop->menu_item();
    // $cart_count = $woocommerce->cart->cart_contents_count;
    // $cart_url = $woocommerce->cart->get_cart_url();
    // $cart_title = 'Complete Purchase';
    //
    // //use regular WP i18n
    // $viewing_cart = __('View your shopping cart', 'minamaze');
    // $start_shopping = __('Start shopping', 'minamaze');
    // $cart_contents = sprintf(_n('<span class="woo-cart-count-before"> (</span>%d<span class="woo-cart-count-after"> Item)</span>', '<span class="woo-cart-count-before"> (</span>%d<span class="woo-cart-count-after"> Items)</span>', $cart_count, 'minamaze'), $cart_count);
    //
    // $menu_item .= '<a class="woo-cart-menu-item" href="'.$cart_url.'" title="'.$cart_title.'">';
    //
    // $menu_item_a_content = '';
    // $menu_item_a_content .= '<span class="woo-cart-menu-wrap">';
    // $menu_item_a_content .= '<span class="woo-cart-menu-wrap-inner">';
    // $menu_item_a_content .= '<span class="woo-cart-menu-icon"><span class="woo-cart-menu-icon-inner"></span></span>';
    // $menu_item_a_content .= '<span class="woo-cart-menu-content"><span class="woo-cart-menu-content-inner">'.$cart_contents.'</span></span>';
    // $menu_item_a_content .= '</span>';
    // $menu_item_a_content .= '</span>';
    //
    // $menu_item .= $menu_item_a_content . '</a>';
    //
    // if( !empty( $menu_item ) ) return $menu_item;
    //
    //

}

add_filter( 'wpmenucart_menu_item_filter', 'laurelyoga_menu_item' );
