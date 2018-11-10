<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_complete_shortcode() {

    global $current_user;

    if ( ! isset( $_GET['id'] ) ) {
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    } elseif ( get_post_meta( $_GET['id'], 'wpd_order_customer_id', true ) == $current_user->ID ) {
        echo "<h3>Order Complete (#" . $_GET['id'] . ")</h3>";
        echo "<p>Thank you. Your order has been received.</p>";

        global $wpdb;

        $get_id = $_GET['id'];

        // Get row's from database with current $wpd_order_id.
        $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id}", ARRAY_A );
        print_r( $get_order_data );

        foreach ( $get_order_data as $item_id ) {
            $get_order_details = get_post( $item_id['order_item_id'] );

            //var_dump( $get_order_details );
        }    

        echo "<br /><br /><strong>Order Item Data</strong>";

        $get_order_item_data = $wpdb->get_row(
            "SELECT item_id FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id}", ARRAY_A
        );
        print_r( $get_order_item_data );

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
