<?php
/**
 * Helper function - display order item details in table format
 * 
 * @since 1.0
 * @todo move this to it's own folder/file structure.
 */
function wpd_ecommerce_table_order_data( $order_id ) {
    global $wpdb;

    // Get row's from database with current $wpd_order_id.
    $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id}", ARRAY_A );

    $str  = '<table class="wpd-ecommerce">';
    $str .= '<thead><tr><td></td><td>Product</td><td>Price</td><td>Quantity</td><td>Total</td></tr></thead>';
    $str .= '<tbody>';

    // Loop through each product in the database.
    foreach( $get_order_data as $order_item ) {

        // Get item number.
        $order_item_meta_id = $order_item['item_id'];

        // Get row's from database with current order number.
        $get_order_item_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders_meta WHERE item_id = {$order_item_meta_id}", ARRAY_A );

        // Loop through each order item's data, creating a new array.
        foreach ( $get_order_item_data as $entry ) {
            $newArray[$entry['meta_key']] = $entry['meta_value'];
        }

        // Set the item variation name.
        if ( '' != $newArray['item_variation_name'] ) {
            $var_name = ' - ' . $newArray['item_variation_name'];
        } else {
            $var_name = '';
        }

        $str .=	"<td><img src='" . $newArray['item_image_url_thumb'] . "' alt ='" . $newArray['order_item_name'] . "' class='wpd-ecommerce-orders-table-image' / ></td><td><a href='" . $newArray['item_url'] . "'>" . $newArray['order_item_name'] . $var_name . "</a></td><td>" . CURRENCY . number_format( (float)$newArray['single_price'], 2, '.', ',' ) . "</td><td>" . $newArray['quantity'] . "</td><td>" . CURRENCY . number_format( (float)$newArray['total_price'], 2, '.', ',' ) . "</td></tr>";
    }

    $str .= "</tbody></table>";

    return $str;
}

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
        echo wpd_ecommerce_table_order_data( $get_id );
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
