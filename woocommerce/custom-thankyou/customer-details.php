<header>
    <h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
</header>
<dl class="customer_details">
<?php
    if ( $order->billing_email ) echo '<dt>' . __( 'Email:', 'woocommerce' ) . '</dt><dd>' . $order->billing_email . '</dd>';
    if ( $order->billing_phone ) echo '<dt>' . __( 'Telephone:', 'woocommerce' ) . '</dt><dd>' . $order->billing_phone . '</dd>';
    // Additional customer details hook
    do_action( 'woocommerce_order_details_after_customer_details', $order );
?>
</dl>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="col2-set addresses">

    <div class="col-1">

<?php endif; ?>

        <header class="title">
            <h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
        </header>
        <address>
            <?php
                if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_billing_address();
            ?>
        </address>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

    </div><!-- /.col-1 -->

    <div class="col-2">

        <header class="title">
            <h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
        </header>
        <address>
            <?php
                if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address();
            ?>
        </address>

    </div><!-- /.col-2 -->

</div><!-- /.col2-set -->

<?php endif; ?>

<p class="custom-order-features">
    <a class="btn button style1 purple medium" href="<?php echo get_site_url().'/learner-profile/'; ?>">
        Access My Courses
    </a>

<?php
// Send the user back to trina or sarah if the cookie is set.
if(isset($_COOKIE['instructor'])) {
    $instructor = $_COOKIE['instructor'];
    $return_to_instructor = null;
    $sarahhome = 'https://sarahcourtdpt.com/movement-logic/';
    $trinahome = 'https://trinaaltman.com/';
    $btn_classes = 'btn button style1 purple medium';
    if ($instructor=='trina') {
        $return_to_instructor = '<a href="'.$trinahome.'" class="'.$btn_classes.'">Return to Trina\'s Site</a>';
    }
    if ($instructor=='sarah') {
        $return_to_instructor = '<a href="'.$sarahhome.'" class="'.$btn_classes.'">Return to Sarah\'s Site</a>';
    }
    if ($return_to_instructor) {
        echo $return_to_instructor;
    }
}
?>

</p>

<div class="clear"></div>
