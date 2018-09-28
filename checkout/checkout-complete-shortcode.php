<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_complete_shortcode() {

    global $current_user;

    if ( !isset( $_GET['id'] ) ) {
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    } elseif ( get_post_meta( $_GET['id'], 'wpd_order_customer_id', true ) == $current_user->ID ) {
        echo "<h3>Order Complete (#" . $_GET['id'] . ")</h3>";
        echo "<p>Thank you. Your order has been received.</p>";

        echo "<h3 class='wpd-ecommerce patient-order'>Your Order</h3>";
        echo get_post_meta( $_GET['id'], 'wpd_order_details', true );
    } else {
        echo "<h2>Unauthorized Access</h2>";
        echo "<p>You're unable to access this shit!</p>";
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    }

}
add_shortcode( 'wpd_checkout_complete', 'wpd_ecommerce_checkout_complete_shortcode' );
