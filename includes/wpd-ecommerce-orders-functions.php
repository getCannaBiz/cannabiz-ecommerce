<?php
/**
 * WP Dispensary eCommerce order helper functions
 *
 * @package WPD_eCommerce/functions
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get all order statuses.
 *
 * @since 1.0
 * @return array
 */
function wpd_ecommerce_get_order_statuses() {
	$order_statuses = array(
		'wpd-pending'    => _x( 'Pending', 'Order status', 'wpd-ecommerce' ),
		'wpd-processing' => _x( 'Processing', 'Order status', 'wpd-ecommerce' ),
		'wpd-on-hold'    => _x( 'On hold', 'Order status', 'wpd-ecommerce' ),
		'wpd-completed'  => _x( 'Completed', 'Order status', 'wpd-ecommerce' ),
		'wpd-cancelled'  => _x( 'Cancelled', 'Order status', 'wpd-ecommerce' ),
		'wpd-refunded'   => _x( 'Refunded', 'Order status', 'wpd-ecommerce' ),
		'wpd-failed'     => _x( 'Failed', 'Order status', 'wpd-ecommerce' ),
	);
	return apply_filters( 'wpd_ecommerce_order_statuses', $order_statuses );
}

/**
 * Helper function - display order item details in table format
 * 
 * @since 1.0
 */
function wpd_ecommerce_table_order_data( $order_id, $user_id ) {
    // Get customer ID from order.
    $customer_id = get_post_meta( $order_id, 'wpd_order_customer_id', true );

    // Return false if they don't match.
    if ( $user_id != $customer_id ) {
        return false;
    }

    // Return false if current user does not have admin capabilities.
    if ( ! current_user_can( 'manage_options' ) ) {
        // return false;
    }

    global $wpdb;

    // Get row's from database with current $wpd_order_id.
    $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'product'", ARRAY_A );

    $str  = '<table class="wpd-ecommerce">';
    $str .= '<thead><tr><td></td><td>' . __( 'Product', 'wpd-ecommerce' ) . '</td><td>' . __( 'Price', 'wpd-ecommerce' ) . '</td><td>' . __( 'Qty', 'wpd-ecommerce' ) . '</td><td>' . __( 'Total', 'wpd-ecommerce' ) . '</td></tr></thead>';
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

/**
 * Customize the filter for orders updated messages.
 * 
 * @since 1.0
 */
function wpd_ecommerce_order_updated_messages( $messages ) {
    global $post;
    if ( 'wpd_orders' === get_post_type() ) {
        $messages['post'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __( 'Order updated. <a href="%s">View order</a>'), esc_url( get_permalink( $post->ID ) ) ),
            2 => __( 'Custom field updated.' ),
            3 => __( 'Custom field deleted.' ),
            4 => __( 'Order updated.' ),
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'Order restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __( 'Order published. <a href="%s">View order</a>' ), esc_url( get_permalink( $post->ID ) ) ),
            7 => __( 'Order saved.' ),
            8 => sprintf( __( 'Order submitted. <a target="_blank" href="%s">Preview order</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
            9 => sprintf( __( 'Order scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview order</a>' ),
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
            10 => sprintf( __( 'Order draft updated. <a target="_blank" href="%s">Preview order</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
        );
    }
    return $messages;
}
add_filter( 'post_updated_messages', 'wpd_ecommerce_order_updated_messages' );

/**
 * Display order statuses in a variety of ways
 * 
 * If passing 'span' to $display, make sure to add an $order_id
 * 
 * @since 1.0
 * @return string
 */
function wpd_ecommerce_order_statuses( $order_id, $display = NULL, $classes = NULL ) {

    // Order ID.
    if ( NULL == $order_id ) {
        //$order_id = $post->ID;
    }

    $wpd_order_status = get_post_meta( $order_id, 'wpd_order_status', TRUE );
    $status_names     = wpd_ecommerce_get_order_statuses();
    //$classes          = '';

    // Default style.
    if ( NULL === $display ) {
        $display = 'span';
    }

    /**
     * Span style.
     * 
     * @since 1.0
     * @return string
     */
    if ( 'span' === $display ) {
        $str = "<span class='wpd-ecommerce status" . " " . $wpd_order_status . "'>" . $status_names[$wpd_order_status] . "</span>";
        return $str;
    }

    /**
     * Select style.
     * 
     * @since 1.0
     * @return string
     */
    if ( 'select' === $display ) {
        if ( $status_names ) {
            $str .= '<select name="wpd_order_status classes' . $classes . '" id="wpd_order_status" class="widefat">';
            foreach ( $status_names as $key=>$value ) {
                if ( esc_html( $key ) != $wpd_order_status ) {
                    $order_status_selected = '';
                } else {
                    $order_status_selected = 'selected="selected"';
                }
                $str .= '<option value="' . esc_html( $key ) .'" ' . esc_html( $order_status_selected ) . '>' . esc_html( $value ) .'</option>';
            }
            $str .= '</select>';
        }
        return $str;
    }

}
