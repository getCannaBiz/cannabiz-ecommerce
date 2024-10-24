<?php
/**
 * WP Dispensary eCommerce orders metaboxes
 *
 * @package WPD_eCommerce
 * @author  CannaBiz Software <contact@cannabizsoftware.com>
 * @license GPL-2.0+ 
 * @link    https://cannabizsoftware.com
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Order item details metabox
 *
 * Adds the order details metabox.
 *
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_order_item_details_metaboxes() {
    add_meta_box(
        'wpd_ecommerce_order_item_details',
        esc_attr__( 'Order details', 'wpd-ecommerce' ),
        'wpd_ecommerce_order_item_details_build',
        'wpd_orders',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpd_ecommerce_order_item_details_metaboxes' );

/**
 * Building the metabox
 * 
 * @return string
 */
function wpd_ecommerce_order_item_details_build() {
    global $post;
    global $wpdb;

    $user                   = wp_get_current_user();
    $role                   = ( array ) $user->roles;
    $order_customer_address = get_post_meta( $post->ID, 'wpd_order_customer_address', true );
    $order_customer_id      = get_post_meta( $post->ID, 'wpd_order_customer_id', true );
    $user_info              = get_userdata( $order_customer_id );
    $order_subtotal         = get_post_meta( $post->ID, 'wpd_order_subtotal_price', true );
    $order_total            = get_post_meta( $post->ID, 'wpd_order_total_price', true );
    $order_items            = get_post_meta( $post->ID, 'wpd_order_items', true );
    $status_names           = wpd_ecommerce_get_order_statuses();
    $status                 = get_post_meta( $post->ID, 'wpd_order_status', true );
    $status_display         = wpd_ecommerce_order_statuses( $post->ID, null, null );
    $get_id                 = $post->ID;
    $get_order_amount       = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_coupon_amount'", ARRAY_A );
    $order_coupon_amount    = $get_order_amount[0]['order_value'];

    // Sales tax.
    $get_sales_tax   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_sales_tax'", ARRAY_A );
    $order_sales_tax = $get_sales_tax[0]['order_value'];
    // Excise tax.
    $get_excise_tax   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_excise_tax'", ARRAY_A );
    $order_excise_tax = $get_excise_tax[0]['order_value'];
    // Payment type amount.
    $get_payment_type_amount   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_payment_type_amount'", ARRAY_A );
    $order_payment_type_amount = $get_payment_type_amount[0]['order_value'];
    // Payment type name.
    $get_payment_type_name = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_payment_type_name'", ARRAY_A );
    if ( $get_payment_type_name ) {
        $order_payment_type_name = $get_payment_type_name[0]['order_value'];
    } else {
        $order_payment_type_name = '';
    }

    // Noncename needed to verify where the data originated.
    echo '<input type="hidden" name="wpd_ecommerce_order_item_details_meta_noncename" id="wpd_ecommerce_order_item_details_meta_noncename" value="' .
    wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

    // @todo add filters to all content output here, before/after table, etc.

    echo '<div class="order-item-details-box first">';
    echo '<h1>' . sprintf( esc_attr__( 'Order #%1$s details', 'wpd-ecommerce' ), $post->ID ) . '</h1>';

    echo '<table class="wpd-ecommerce order-details"><tbody>';
    echo '<tr><td><strong>' . esc_attr__( 'Subtotal', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_subtotal . '</td></tr>';
    if ( isset( $order_coupon_amount ) && '0.00' !== $order_coupon_amount ) {
        echo '<tr><td><strong>' . esc_attr__( 'Coupon', 'wpd-ecommerce' ) . ':</strong></td><td>-' . CURRENCY . $order_coupon_amount . '</td></tr>';
    }
    if ( isset( $order_excise_tax ) && '0.00' !== $order_excise_tax ) {
        echo '<tr><td><strong>' . esc_attr__( 'Excise tax', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_excise_tax . '</td></tr>';
    }
    if ( isset( $order_sales_tax ) && '0.00' !== $order_sales_tax ) {
        echo '<tr><td><strong>' . esc_attr__( 'Sales tax', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_sales_tax . '</td></tr>';
    }
    echo '<tr><td><strong>' . $order_payment_type_name . ':</strong></td><td>' . CURRENCY . $order_payment_type_amount . '</td></tr>';
    echo '<tr><td><strong>' . esc_attr__( 'Total', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_total . '</td></tr>';
    echo '</tbody></table>';

    echo '</div>';

    // Get the data if its already been entered.
    $order_status      = get_post_meta( $post->ID, 'wpd_order_status', true );
    $order_customer_id = get_post_meta( $post->ID, 'wpd_order_customer_id', true );

    echo '<div class="order-item-details-box">';
    echo '<p><strong>' . esc_attr__( 'Status', 'wpd-ecommerce' ) . ':</strong></p>';

    // Get array of order statuses.
    $status_names = wpd_ecommerce_get_order_statuses();

    if ( $status_names ) {
        print( '<select name="wpd_order_status" id="wpd_order_status" class="widefat">' );
        foreach ( $status_names as $key=>$value ) {
            if ( esc_html( $key ) != $order_status ) {
                $order_status_selected = '';
            } else {
                $order_status_selected = 'selected="selected"';
            }
            printf( '<option value="%s" ' . esc_html( $order_status_selected ) . '>%s</option>', esc_html( $key ), esc_html( $value ) );
        }
        print( '</select>' );
    }

    echo '<p><strong>' . esc_attr__( 'Address', 'wpd-ecommerce' ) . ':</strong></p>';

    $user_info = get_userdata( $order_customer_id );

    if ( '' != $user_info->address_line_1 ) {
        echo $user_info->address_line_1 . '<br />';
    }
    if ( '' != $user_info->address_line_2 ) {
        echo $user_info->address_line_2 . '<br />';
    }

    echo $user_info->city . ', ' . $user_info->state_county . ' ' . $user_info->postcode_zip . '<br />';

    echo '</div>';

    echo '<div class="order-details-box">';
    echo '<p><strong>' . esc_attr__( 'Customer', 'wpd-ecommerce' ) . ':</strong> (<a href="' . get_bloginfo( 'url' ) . '/wp-admin/user-edit.php?user_id=' . $order_customer_id . '">' . esc_attr__( 'profile', 'wpd-ecommerce' ) . '</a>) <a href="' . get_bloginfo( 'url' ) . '/wp-admin/edit.php?post_status=all&post_type=wpd_orders&wpd_order_customer_id=' . $order_customer_id . '">' . esc_attr__( 'view orders', 'wpd-ecommerce' ) . ' &rarr;</a></p>';
    wp_dropdown_users( array( 'name' => 'wpd_order_customer_id', 'id' => 'wpd_order_customer_id', 'selected' => $order_customer_id, 'class' => 'widefat', 'show' => 'display_name_with_login' ) );

    if ( '' != $user_info->user_email ) {
        echo '<p><strong>' . esc_attr__( 'Email address', 'wpd-ecommerce' ) . ':</strong></p>';
        echo '<a href="mailto:' . $user_info->user_email . '">' . $user_info->user_email . '</a>';
    }

    if ( '' != $user_info->phone_number ) {
        echo '<p><strong>' . esc_attr__( 'Phone number', 'wpd-ecommerce' ) . ':</strong></p>';
        echo '<a href="tel:' . $user_info->phone_number . '">' . $user_info->phone_number . '</a>';
    }

    echo '</div>';

    echo '<div class="order-details-box wide-box">';
    echo wpd_ecommerce_table_order_data( $post->ID, $user_info->ID );
    echo '</div>';

}

/**
 * Save the Metabox Data
 * 
 * @param int    $post_id 
 * @param object $post 
 * 
 * @return int|bool
 */
function wpd_ecommerce_save_order_item_details( $post_id, $post ) {

    /**
     * Verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times
     */
    if (
        null == filter_input( INPUT_POST, 'wpd_ecommerce_order_item_details_meta_noncename' ) ||
        ! wp_verify_nonce( filter_input( INPUT_POST, 'wpd_ecommerce_order_item_details_meta_noncename' ), plugin_basename( __FILE__ ) )
    ) {
        return $post->ID;
    }

    // Is the user allowed to edit the post or page?.
    if ( ! current_user_can( 'edit_post', $post->ID ) ) {
        return $post->ID;
    }

    /**
     * OK, we're authenticated: we need to find and save the data
     * We'll put it into an array to make it easier to loop though.
     */
    $order_meta['wpd_order_status']           = filter_input( INPUT_POST, 'wpd_order_status' );
    $order_meta['wpd_order_customer_id']      = filter_input( INPUT_POST, 'wpd_order_customer_id' );
    $order_meta['wpd_order_customer_address'] = filter_input( INPUT_POST, 'wpd_order_customer_address' );

    // Add values of $order_meta as custom fields.
    foreach ( $order_meta as $key => $value ) {
        if ( 'revision' === $post->post_type ) {
            return;
        }
        $value = implode( ',', (array) $value );
        if ( get_post_meta( $post->ID, $key, false ) ) {
            update_post_meta( $post->ID, $key, $value );
        } else {
            add_post_meta( $post->ID, $key, $value );
        }
        if ( ! $value ) {
            delete_post_meta( $post->ID, $key );
        }
    }

}
add_action( 'save_post', 'wpd_ecommerce_save_order_item_details', 1, 2 );
