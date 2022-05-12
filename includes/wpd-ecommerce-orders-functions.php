<?php
/**
 * WP Dispensary eCommerce order helper functions
 *
 * @since 1.0
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

    // Table head.
    $thead = array(
        '',
        esc_attr__( 'Product', 'wpd-ecommerce' ),
        esc_attr__( 'Price', 'wpd-ecommerce' ),
        esc_attr__( 'Qty', 'wpd-ecommerce' ),
        esc_attr__( 'Total', 'wpd-ecommerce' ),
    );

    // Filter table head.
    $thead = apply_filters( 'wpd_ecommerce_table_order_data_thead', $thead );

    $str  = '<table class="wpd-ecommerce">';
    $str .= '<thead><tr>';
    foreach ( $thead as $thead ) {
        $str .= '<td>' . $thead . '</td>';
    }
    $str .= '</tr></thead>';
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

        // Table body rows.
        $tbody = array(
            //'<img src="' . $newArray['item_image_url_thumb'] . '" alt ="' . $newArray['order_item_name'] . '" class="wpd-ecommerce-orders-table-image" / >',
            get_wpd_product_image( $newArray['item_id'], 'wpd-thumbnail' ),
            '<a href="' . $newArray['item_url'] . '">' . $newArray['order_item_name'] . $var_name . '</a>',
            CURRENCY . number_format( (float)$newArray['single_price'], 2, '.', ',' ),
            $newArray['quantity'],
            CURRENCY . number_format( (float)$newArray['total_price'], 2, '.', ',' )
        );

        // Filter table body row data.
        $tbody = apply_filters( 'wpd_ecommerce_table_order_data_tbody', $tbody, $newArray );

        // Table rows for tbody.
        $str .=	'<tr>';
        foreach ( $tbody as $tbody ) {
            $str .= '<td>' . $tbody . '</td>';
        }
        $str .= '</tr>';
    }

    $str .= '</tbody></table>';

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
            1 => sprintf( esc_attr__( 'Order updated. <a href="%s">View order</a>'), esc_url( get_permalink( $post->ID ) ) ),
            2 => esc_attr__( 'Custom field updated.' ),
            3 => esc_attr__( 'Custom field deleted.' ),
            4 => esc_attr__( 'Order updated.' ),
            5 => null !== filter_input( INPUT_GET, 'revision' ) ? sprintf( esc_attr__( 'Order restored to revision from %s' ), wp_post_revision_title( (int) filter_input( INPUT_GET, 'revision' ), false ) ) : false,
            6 => sprintf( esc_attr__( 'Order published. <a href="%s">View order</a>' ), esc_url( get_permalink( $post->ID ) ) ),
            7 => esc_attr__( 'Order saved.' ),
            8 => sprintf( esc_attr__( 'Order submitted. <a target="_blank" href="%s">Preview order</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
            9 => sprintf( esc_attr__( 'Order scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview order</a>' ),
            date_i18n( esc_attr__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
            10 => sprintf( esc_attr__( 'Order draft updated. <a target="_blank" href="%s">Preview order</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
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
        $str = '<span class="wpd-ecommerce status' . ' ' . $wpd_order_status . '">' . $status_names[$wpd_order_status] . '</span>';
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

/**
 * Get order details
 * 
 * @param  int $order_id
 * @since  1.0
 * @return array
 */
function wpd_ecommerce_get_order_details( $order_id ) {
    global $wpdb;
    $order_customer_id = get_post_meta( $order_id, 'wpd_order_customer_id', true );
    $order_subtotal    = get_post_meta( $order_id, 'wpd_order_subtotal_price', true );
    $order_total       = get_post_meta( $order_id, 'wpd_order_total_price', true );
    $order_items       = get_post_meta( $order_id, 'wpd_order_items', true );
    $order_items_table = wpd_ecommerce_table_order_data( $order_id, $order_customer_id );

    $status_names      = wpd_ecommerce_get_order_statuses();
    $status            = get_post_meta( $order_id, 'wpd_order_status', TRUE );
    $status_display    = wpd_ecommerce_order_statuses( $order_id, NULL, NULL );

    $get_order_amount    = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'details' AND order_key = 'order_coupon_amount'", ARRAY_A );
    $order_coupon_amount = $get_order_amount[0]['order_value'];

    $get_sales_tax   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'details' AND order_key = 'order_sales_tax'", ARRAY_A );
    $order_sales_tax = $get_sales_tax[0]['order_value'];

    $get_excise_tax   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'details' AND order_key = 'order_excise_tax'", ARRAY_A );
    $order_excise_tax = $get_excise_tax[0]['order_value'];

    $get_payment_type_amount   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'details' AND order_key = 'order_payment_type_amount'", ARRAY_A );
    $order_payment_type_amount = $get_payment_type_amount[0]['order_value'];

    $get_payment_type_name   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'details' AND order_key = 'order_payment_type_name'", ARRAY_A );
    $order_payment_type_name = $get_payment_type_name[0]['order_value'];

    // Get row's from database with current $order_id.
    $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$order_id} AND order_type = 'product'", ARRAY_A );

    // Order products.
    $order_products = array();

    // Loop through each product from $get_order_data.
    foreach( $get_order_data as $order_item ) {

        // Get item number.
        $order_item_meta_id = $order_item['item_id'];

        // Get row's from database with current order number.
        $get_order_item_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders_meta WHERE item_id = {$order_item_meta_id}", ARRAY_A );

        // Loop through each order item's data, creating a new array.
        foreach ( $get_order_item_data as $entry ) {
            $newArray[$entry['meta_key']] = $entry['meta_value'];
        }

        // Add product data to array.
        $order_products[] = $newArray;

    }

    // Order details array.
    $details = array(
        'customer_id'   => $order_customer_id,
        'order_items'   => array( 'count' => $order_items, 'products' => $order_products, 'display_table' => $order_items_table ),
        'status'        => array( 'status' => $status, 'status_display' => $status_display ),
        'payment_type'  => array( 'amount' => $order_payment_type_amount, 'name' => $order_payment_type_name ),
        'coupon_amount' => $order_coupon_amount,
        'sales_tax'     => $order_sales_tax,
        'excise_tax'    => $order_excise_tax,
        'subtotal'      => $order_subtotal,
        'total'         => $order_total,
    );

    return apply_filters( 'wpd_ecommerce_get_order_details', $details );
}

/**
 * Single order template redirects
 * 
 * @return void
 */
function wpd_ecommerce_templates_single_order_redirects() {
    if ( ! is_user_logged_in() ) {
        // Redirect non-logged in users.
        wp_safe_redirect( wpd_ecommerce_account_url() );
    } else {
        // Get user details.
        $user = wp_get_current_user();
        $role = ( array ) $user->roles;

        // Get the order details.
        $order_details = wpd_ecommerce_get_order_details( get_the_ID() );
    
        if ( $user->ID != $order_details['customer_id'] && 'administrator' === $role[0] ) {
            // Administrators who are NOT the customer
        } elseif ( $user->ID === $order_details['customer_id'] && 'administrator' === $role[0] ) {
            // Administrators who ARE the customer
        } elseif ( $user->ID != $order_details['customer_id'] && 'customer' === $role[0] ) {
            // Customers who are ARE NOT the customer.
            wp_safe_redirect( wpd_ecommerce_account_url() );
        } elseif ( $user->ID == $order_details['customer_id'] ) {
            // If current user IS the customer.
        } else {
            // If not customer or admin, redirect to account page.
            if ( 'customer' != $role[0] && 'administrator' != $role[0] ) {
                wp_safe_redirect( wpd_ecommerce_account_url() );
            }
        }
    }
}
add_action( 'wpd_ecommerce_templates_single_orders_wrap_before', 'wpd_ecommerce_templates_single_order_redirects' );

/**
 * Get total orders per customer
 * 
 * @param  $customer_id 
 * @since  2.0
 * @return int
 */
function wpd_ecommerce_customer_total_order_count( $customer_id = '' ) {
    // Bail early?
    if ( ! $customer_id ) { return ''; }

    $args = array(
		'post_type' => 'wpd_orders',
	);
	$the_query = new WP_Query( $args );

    // Order count.
	$count = 0;

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
            // Get customer ID from order.
            $order_customer_id = get_post_meta( get_the_ID(), 'wpd_order_customer_id', true );
            // Check if customer ID from order is the same as the arg passed through.
			if ( $customer_id == $order_customer_id ) {
                // Add to the count.
				$count++;
			}
		endwhile;
	endif;

    return $count;
}

/**
 * Get order details
 * 
 * @param  int    $customer_id
 * @param  string $start_date
 * @param  string $end_date
 * @since  2.0
 * @return array
 */
function wpd_ecommerce_get_orders_details( $customer_id = '', $start_date = '', $end_date = '' ) {

	// Return data.
	$return = array();

	// Loop args.
	$args = array(
		'post_type'      => 'wpd_orders',
		'posts_per_page' => -1,
    );

    if ( $customer_id ) {
        $args['meta_query'] = array(
            array(
                'key'     => 'wpd_order_customer_id',
                'value'   => $customer_id,
                'compare' => '=',
            )
        );
    }

    $loop = new WP_Query( $args );
    $loop = $loop->get_posts();

	// Order totals.
	$order_totals = array();
	// Product count.
	$product_count = array();
	// Product data array.
	$product_data = array();
    // Product types.
    $product_types = array();
	// Product vendors array.
    $product_taxonomies = array();
    // Customer counts array.
    $order_customers = array();
    // Customer sales array.
    $order_customers_sales = array();
    // Product taxonomies.
    $taxonomies = array( 'vendors', 'shelf_types', 'strain_types', 'wpd_categories' );

	// Loop through each product.
	foreach ( $loop as $item ) {
        $order_details = wpd_ecommerce_get_order_details( $item->ID );
		// Add order total to array.
		$order_totals[] = $order_details['total'];
		// Add order product count to array.
        $product_count[] = $order_details['order_items']['count'];
        // Add customer to array.
        $order_customers[] = $order_details['customer_id'];
        // Add customer sales totals to array.
        $order_customers_sales[$order_details['customer_id']] += $order_details['total'];

		// Loop products.
		foreach( $order_details['order_items']['products'] as $product ) {
			// Add item quantity to array.
            $product_data[] = array( 'id' => $product['item_id'], 'qty' => $product['quantity'] );
            // Add product type to array.
            $product_types[] = get_post_meta( $product['item_id'], 'product_type', true );

            foreach ( $taxonomies as $tax ) {
                // Get the taxonomies added to this product.
                $taxonomy = get_the_terms( $product['item_id'], $tax );
                // Check if taxonomy is added to product.
                if ( $taxonomy ) {
                    foreach ( $taxonomy as $taxonomy ) {
                        // Get taxonomy ID.
                        $the_tax = $taxonomy->term_id;
                        // Add taxonomy to array.
                        $product_taxonomies[$tax][] = $the_tax;
                    }
                }

            }

        }
    }

    // Item counts.
    $item_counts = array();

    // Loop through product data.
	foreach ( $product_data as $item ) {
		if ( ! isset( $item_counts[$item['id']] ) ) {
			$item_counts[$item['id']] = intval( $item['qty'] );
		} else {
			$item_counts[$item['id']] += intval( $item['qty'] );
		}
	}

	// Descending order.
	//arsort( $item_counts );

	// Get total orders.
	$orders = count( $order_totals );
	// Get total spent in all orders.
	$total_spent = array_sum( $order_totals );
	// Get average spent in all orders.
	$avg_spent = $total_spent / $orders;
	// Total product count.
	$total_products = array_sum( $product_count );

    // Add order data to the return variable.
	$return['orders_count']                 = $orders;
	$return['orders_total_spent']           = $total_spent;
	$return['orders_average_spent']         = number_format( $total_spent / $orders, 2, '.', '' );
    $return['orders_average_product_count'] = round( $total_products / $orders );
    $return['orders_customers']             = $order_customers;
    $return['orders_customers_sales']       = $order_customers_sales;
    $return['product_counts']               = $item_counts;
    $return['product_types']                = $product_types;

    foreach ( $product_taxonomies as $key=>$val ) {
        // Count how many times each taxonomy was purchased.
        $product_taxonomies = array_count_values( $val );

        // Sort taxonomies by count (desc).
        //arsort( $product_taxonomies );
        // Return taxonomy count.
        $return[$key] = $product_taxonomies;
    }

    //var_dump( $order_customers_sales );

    return apply_filters( 'wpd_ecommerce_get_orders_details', $return );
}
