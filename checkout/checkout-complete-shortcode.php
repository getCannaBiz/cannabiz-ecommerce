<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_complete_shortcode() {

    // Get current user.
    global $current_user;

    // Order ID number.
    $get_id = $_GET['id'];

    if ( get_post_meta( $get_id, 'wpd_order_customer_id', true ) == $current_user->ID ) {

        $order_customer_id = get_post_meta( $get_id, 'wpd_order_customer_id', true );
        $order_subtotal    = get_post_meta( $get_id, 'wpd_order_subtotal_price', true );
        $order_total       = get_post_meta( $get_id, 'wpd_order_total_price', true );
        $order_items       = get_post_meta( $get_id, 'wpd_order_items', true );

        /**
         * @todo add an action hook for developers to customize this page
         */
        echo "<div class='wpd-ecommerce order-complete'>";

            echo "<h3>Order Complete (#" . $get_id . ")</h3>";
            echo "<p>" . __( 'Thank you. Your order has been received.', 'wpd-ecommerce' ) . "</p>";

            echo '<div class="order-info">';
            echo '<p><strong>' . __( 'Details', 'wpd-ecommerce' ) . ':</strong></p>';
            echo "<p>" . get_the_date() . "<br />";
            echo "Subtotal: " . CURRENCY . $order_subtotal . "<br />";
            echo "Total: " . CURRENCY . $order_total . "</p>";
            echo '</div>';

            echo '<div class="patient-address">';
            echo '<p><strong>' . __( 'Address', 'wpd-ecommerce' ) . ':</strong></p>';

            $user_info = get_userdata( $order_customer_id );

            if ( '' != $user_info->first_name ) {
                echo $user_info->first_name . " ";
            }

            if ( '' != $user_info->last_name ) {
                echo $user_info->last_name . "<br />";
            }

            if ( '' != $user_info->address_line_1 ) {
                echo $user_info->address_line_1 . "<br />";
            }

            if ( '' != $user_info->address_line_2 ) {
                echo $user_info->address_line_2 . "<br />";
            }
            echo $user_info->city . ", " . $user_info->state_county . " " . $user_info->postcode_zip . "<br />";
            echo '</div>';

            echo '<div class="patient-contact">';
            echo "<p><strong>Contact:</strong></p>";
            if ( '' != $user_info->user_email ) {
                echo "<a class='email-address' href='mailto:" . $user_info->user_email . "'>" . $user_info->user_email . "</a><br />";
            }

            if ( '' != $user_info->phone_number ) {
                echo "<a class='phone-number' href='tel:" . $user_info->phone_number . "'>" . $user_info->phone_number . "</a>";
            }
            echo '</div>';

        echo '</div>';

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
