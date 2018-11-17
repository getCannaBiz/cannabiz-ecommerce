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
        $order_sales_tax   = get_post_meta( $get_id, 'wpd_order_sales_tax', true );
        $order_excise_tax  = get_post_meta( $get_id, 'wpd_order_excise_tax', true );
        $order_total       = get_post_meta( $get_id, 'wpd_order_total_price', true );
        $order_items       = get_post_meta( $get_id, 'wpd_order_items', true );


        do_action( 'wpd_ecommerce_checkout_order_complete_before' );

        echo "<div class='wpd-ecommerce order-complete'>";

            do_action( 'wpd_ecommerce_checkout_order_complete_inside_before' );

            /**
             * @todo
             * Make this text filterable, so others can add/change text through it,
             * and not have to use all of the action hooks.
             * 
             * actually, make each section (order-info, patient-address, etc) filterable
             * so everything here can be coded elsewhere in the plugin files and 
             */
            echo "<h3>" . __( 'Order Complete', 'wpd-ecommerce' ) . " (#" . $get_id . ")</h3>";
            echo "<p>" . __( 'Thank you. Your order has been received.', 'wpd-ecommerce' ) . "</p>";

            do_action( 'wpd_ecommerce_checkout_order_info_before' );

            echo '<div class="order-info">';
            echo '<table class="completed-order-details"><tbody>';
            echo "<tr><td><strong>Subtotal:</strong></td><td>" . CURRENCY . $order_subtotal . "</td></tr>";
            echo "<tr><td><strong>Coupon:</strong></td><td>- " . CURRENCY . $order_coupon_amount . "</td></tr>";
            echo "<tr><td><strong>Sales tax:</strong></td><td>" . CURRENCY . $order_sales_tax . "</td></tr>";
            echo "<tr><td><strong>Excise tax:</strong></td><td>" . CURRENCY . $order_excise_tax . "</td></tr>";
            echo "<tr><td><strong>Total:</strong></td><td>" . CURRENCY . $order_total . "</td></tr>";
            echo '</tbody></table>';
            echo '</div>';

            do_action( 'wpd_ecommerce_checkout_order_info_after' );

            do_action( 'wpd_ecommerce_checkout_patient_address_before' );

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

            do_action( 'wpd_ecommerce_checkout_patient_address_after' );

            do_action( 'wpd_ecommerce_checkout_patient_contact_before' );

            echo '<div class="patient-contact">';
            echo "<p><strong>Contact:</strong></p>";
            if ( '' != $user_info->user_email ) {
                echo "<a class='email-address' href='mailto:" . $user_info->user_email . "'>" . $user_info->user_email . "</a><br />";
            }

            if ( '' != $user_info->phone_number ) {
                echo "<a class='phone-number' href='tel:" . $user_info->phone_number . "'>" . $user_info->phone_number . "</a>";
            }
            echo '</div>';

            do_action( 'wpd_ecommerce_checkout_patient_contact_after' );

            do_action( 'wpd_ecommerce_checkout_order_complete_inside_after' );

        echo '</div>';

        do_action( 'wpd_ecommerce_checkout_order_complete_after' );

        do_action( 'wpd_ecommerce_checkout_complete_before_your_order' );

        echo "<h3 class='wpd-ecommerce patient-order'>" . __( 'Your Order', 'wpd-ecommerce' ) . "</h3>";

        do_action( 'wpd_ecommerce_checkout_table_order_data_before' );

        echo wpd_ecommerce_table_order_data( $get_id, $current_user->ID );

        do_action( 'wpd_ecommerce_checkout_table_order_data_after' );

    } else {
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    }

}
add_shortcode( 'wpd_checkout_complete', 'wpd_ecommerce_checkout_complete_shortcode' );
