<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_complete_shortcode() {

    // Get current user.
    global $current_user;

    // Order ID number.
    $get_id = $_GET['id'];

    if ( get_post_meta( $get_id, 'wpd_order_customer_id', true ) == $current_user->ID ) {

        /**
         * @todo add an action hook for developers to customize this page
         */
        echo "<h3>Order Complete (#" . $get_id . ")</h3>";
        echo "<p>" . __( 'Thank you. Your order has been received.', 'wpd-ecommerce' ) . "</p>";
        /**
         * @todo add an action hook for developers to customize this page
         */
        echo "<h3 class='wpd-ecommerce patient-order'>" . __( 'Your Order', 'wpd-ecommerce' ) . "</h3>";
        echo wpd_ecommerce_table_order_data( $get_id, $current_user->ID );
        /**
         * @todo add an action hook for developers to customize this page
         */

    } else {
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    }

}
add_shortcode( 'wpd_checkout_complete', 'wpd_ecommerce_checkout_complete_shortcode' );
