<?php
/**
 * Order Details metabox
 *
 * Adds the order details metabox.
 *
 * @since    1.0
 */
function wpd_ecommerce_order_details_metaboxes() {
	add_meta_box(
		'wpdispensary_compounds',
		__( 'Order Details', 'wp-dispensary' ),
		'wpd_ecommerce_order_details_build',
		'wpd_orders',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wpd_ecommerce_order_details_metaboxes' );

/**
 * Building the metabox
 */
function wpd_ecommerce_order_details_build() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="wpd_ecommerce_order_details_meta_noncename" id="wpd_ecommerce_order_details_meta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the thccbd data if its already been entered */
	$order_status           = get_post_meta( $post->ID, 'wpd_order_status', true );
	$order_details          = get_post_meta( $post->ID, 'wpd_order_details', true );
	$order_customer_address = get_post_meta( $post->ID, 'wpd_order_customer_address', true );
	$order_customer_id      = get_post_meta( $post->ID, 'wpd_order_customer_id', true );


	echo '<div class="order-details-box">';
	echo '<p><strong>Order Status:</strong></p>';

	$terms = array( 'Pending', 'Processing', 'Completed', 'Cancelled', 'Refunded' );

	if ( $terms ) {
		printf( '<select name="wpd_order_status" id="wpd_order_status" class="widefat">' );
		foreach ( $terms as $term ) {
			if ( esc_html( $term ) != $wpd_coupon_type ) {
				$order_status_selected = '';
			} else {
				$order_status_selected = 'selected="selected"';
			}
			printf( '<option value="%s" ' . esc_html( $order_status_selected ) . '>%s</option>', esc_html( $term ), esc_html( $term ) );
		}
		print( '</select>' );
	}
	echo '</div>';

	echo '<div class="order-details-box">';
	echo '<p><strong>Customer:</strong> (<a href="' . get_bloginfo( 'url' ) . '/wp-admin/user-edit.php?user_id=' . $order_customer_id . '">profile</a>) <a href="' . get_bloginfo( 'url' ) . '/wp-admin/edit.php?post_status=all&post_type=wpd_orders&wpd_order_customer_id=' . $order_customer_id . '">view orders &rarr;</a></p>';
	wp_dropdown_users( array( 'name' => 'customer', 'id' => 'wpd_order_customer_id', 'selected' => $order_customer_id, 'class' => 'widefat', 'show' => 'display_name_with_login' ) );
	echo '</div>';

	echo '<div class="order-details-box">';
	echo '<p><strong>Delivery Address:</strong></p>';
	echo '<p>Address will display here</p>';
	echo '</div>';


	echo '<div class="order-details-box wide-box">';
	echo '<p><strong>Order Details:</strong></p>';
	echo $order_details;
	echo '</div>';

}

/**
 * Save the Metabox Data
 */
function wpd_ecommerce_save_order_details( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if (
		! isset( $_POST['wpd_ecommerce_order_details_meta_noncename' ] ) ||
		! wp_verify_nonce( $_POST['wpd_ecommerce_order_details_meta_noncename'], plugin_basename( __FILE__ ) )
	) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$wpd_ecommerce_order_meta['wpd_order_status']           = $_POST['wpd_order_status'];
	$wpd_ecommerce_order_meta['wpd_order_details']          = $_POST['wpd_order_details'];
	$wpd_ecommerce_order_meta['wpd_order_customer_id']      = $_POST['wpd_order_customer_id'];
	$wpd_ecommerce_order_meta['wpd_order_customer_address'] = $_POST['wpd_order_customer_address'];

	/** Add values of $wpd_ecommerce_order_meta as custom fields */

	foreach ( $wpd_ecommerce_order_meta as $key => $value ) { /** Cycle through the $thccbd_meta array! */
		if ( 'revision' === $post->post_type ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value.
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value.
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpd_ecommerce_save_order_details', 1, 2 ); // Save the custom fields.
