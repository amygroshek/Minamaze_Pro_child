<?php

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
add_filter('wpmenucart_menu_item_filter', 'laurelyoga_menu_item');

function set_instructor_cookie() {
    // If the instructor is set...
    // $instructor = get_query_var('instructor', 'empty');
    $instructor = isset( $_GET['instructor']) ? $_GET['instructor'] : 0;

    // echo 'set instructor cookie'.'<br>';
    // echo 'instructor is '.$instructor;

    if($instructor) {
        // echo 'it is set';
        if ( ! is_admin() && ! isset( $_COOKIE['instructor'] ) ) {
            setcookie( 'instructor', $instructor, time() + 3600 * 24 * 100, COOKIEPATH, COOKIE_DOMAIN, false );
        }
    }
}
add_action( 'init', 'set_instructor_cookie' );

/**
 * remove_instructor_cookie Removes cookie on logout
 * @return none
 */
function remove_instructor_cookie() {
  // error_log('remove_instructor_cookie()');
  unset($_COOKIE['instructor']);
  setcookie('instructor', '', time() - ( 15 * 60 ));
}
add_action('wp_logout', 'remove_instructor_cookie');

/**
 * run_init_cookie_check Force the logout remove cookie
 * @return none
 */
function run_init_cookie_check() {
    // error_log('run_init_cookie_check()');
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'logout') {
            remove_instructor_cookie();
        }
    }
}
add_action('init', 'run_init_cookie_check');

function add_query_vars_filter( $vars ){
    $vars[] = "instructor";
    return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

// Changes for custom thank you after Purchase
add_action( 'template_redirect', 'wc_custom_redirect_after_purchase' );
function wc_custom_redirect_after_purchase() {
    global $wp;

    if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) ) {
        $order_id  = absint( $wp->query_vars['order-received'] );
        $order_key = wc_clean( $_GET['key'] );

        /**
        * Replace {PAGE_ID} with the ID of your page
        */
        // $redirect  = get_permalink( 14639 ); // vvv vagrant localhost
        $redirect  = get_permalink(14643); // NOTE: update this to match page ID on live site, if it changes.
        $redirect .= get_option( 'permalink_structure' ) === '' ? '&' : '?';
        $redirect .= 'order=' . $order_id . '&key=' . $order_key;

        wp_redirect( $redirect );
        exit;
    }
}

add_filter( 'the_content', 'wc_custom_thankyou' );
function wc_custom_thankyou( $content ) {
    // Check if is the correct page
    // if ( ! is_page( 14639 ) ) { // vvv vagrant localhost
    if (!is_page(14643)) { // NOTE: update this to match page ID on live site, if it changes.
        return $content;
    }

    // check if the order ID exists
    if ( ! isset( $_GET['order'] ) ) {
        return $content;
    }

    // intval() ensures that we use an integer value for the order ID
    $order = wc_get_order( intval( $_GET['order'] ) );

    ob_start();

    // Check that the order is valid
    if ( ! $order ) {
        // The order can't be returned by WooCommerce - Just say thank you
        ?><p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p><?php
    } else {
        if ( $order->has_status( 'failed' ) ) {
            // Order failed - Print error messages and ask to pay again
            /**
            * @hooked wc_custom_thankyou_failed - 10
            */
            do_action( 'wc_custom_thankyou_failed', $order );
        } else {
            // The order is successfull - print the complete order review
            /**
            * @hooked wc_custom_thankyou_header - 10
            * @hooked wc_custom_thankyou_table - 20
            * @hooked wc_custom_thankyou_customer_details - 30
            */
            custom_woocommerce_auto_complete_order($order->get_id());
            do_action( 'wc_custom_thankyou_successful', $order );
        }
    }
    $content .= ob_get_contents();
    ob_end_clean();
    return $content;
}

add_action('wc_custom_thankyou_failed', 'wc_custom_thankyou_failed', 10);
function wc_custom_thankyou_failed($order) {
    wc_get_template('custom-thankyou/failed.php', array( 'order' => $order));
}

add_action('wc_custom_thankyou_successful', 'wc_custom_thankyou_header', 10);
function wc_custom_thankyou_header( $order ) {
    wc_get_template( 'custom-thankyou/header.php', array( 'order' => $order));
}

add_action( 'wc_custom_thankyou_successful', 'wc_custom_thankyou_table', 20 );
function wc_custom_thankyou_table( $order ) {
    wc_get_template( 'custom-thankyou/table.php',           array( 'order' => $order ) );
}

add_action( 'wc_custom_thankyou_successful', 'wc_custom_thankyou_customer_details', 30 );
function wc_custom_thankyou_customer_details( $order ) {
    wc_get_template( 'custom-thankyou/customer-details.php',           array( 'order' => $order ) );
}

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
* Remove related products output
*/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Enqueue parent and child theme styles
 * @return null
 */
function lb_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'lb_theme_enqueue_styles' );

/**
 * Show the product title in the product loop. By default this is an H2.
 */
function lb_woocommerce_template_loop_product_title() {
    echo '<a href="' . get_the_permalink() . '">'.
            '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>'.
        '</a>';
}
add_action('lb_woocommerce_shop_loop_item_title', 'lb_woocommerce_template_loop_product_title');

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
function lb_woocommerce_template_loop_product_thumbnail() {
    echo '<a href="' . get_the_permalink() . '" title="Access ' . get_the_title() . ' details">'.
            woocommerce_get_product_thumbnail()
        .'</a>';
}
add_action( 'lb_woocommerce_before_shop_loop_item_title', 'lb_woocommerce_template_loop_product_thumbnail', 11 );
