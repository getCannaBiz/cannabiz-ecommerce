<?php
/**
 * Order item details metabox
 *
 * Adds the order details metabox.
 *
 * @since    1.0
 */
function wpd_ecommerce_order_item_details_metaboxes() {
	add_meta_box(
		'wpd_ecommerce_order_item_details',
		__( 'Order details', 'wpd-ecommerce' ),
		'wpd_ecommerce_order_item_details_build',
		'wpd_orders',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wpd_ecommerce_order_item_details_metaboxes' );

/**
 * Building the metabox
 */
function wpd_ecommerce_order_item_details_build() {
	global $post;

	$order_customer_id = get_post_meta( $post->ID, 'wpd_order_customer_id', true );
	$user_info         = get_userdata( $order_customer_id );

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="wpd_ecommerce_order_item_details_meta_noncename" id="wpd_ecommerce_order_item_details_meta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	echo '<div class="order-item-details-box first">';
	echo '<h1>Order #' . $post->ID . '</h1>';
	echo '<h3>Total: $249.99</h3>';
	echo '</div>';

	/** Get the data if its already been entered */
	$order_status           = get_post_meta( $post->ID, 'wpd_order_status', true );
	$order_customer_id      = get_post_meta( $post->ID, 'wpd_order_customer_id', true );

	echo '<div class="order-item-details-box">';
	echo '<p><strong>' . __( 'Status', 'wpd-ecommerce' ) . ':</strong></p>';

	// Get array of order statuses.
	$statuses = wpd_ecommerce_get_order_statuses();

	if ( $statuses ) {
		print( '<select name="wpd_order_status" id="wpd_order_status" class="widefat">' );
		foreach ( $statuses as $key=>$value ) {
			if ( esc_html( $key ) != $order_status ) {
				$order_status_selected = '';
			} else {
				$order_status_selected = 'selected="selected"';
			}
			printf( '<option value="%s" ' . esc_html( $order_status_selected ) . '>%s</option>', esc_html( $key ), esc_html( $value ) );
		}
		print( '</select>' );
	}
	echo '</div>';

	echo '<div class="order-details-box">';
	echo '<p><strong>' . __( 'Customer', 'wpd-ecommerce' ) . ':</strong> (<a href="' . get_bloginfo( 'url' ) . '/wp-admin/user-edit.php?user_id=' . $order_customer_id . '">profile</a>) <a href="' . get_bloginfo( 'url' ) . '/wp-admin/edit.php?post_status=all&post_type=wpd_orders&wpd_order_customer_id=' . $order_customer_id . '">view orders &rarr;</a></p>';
	wp_dropdown_users( array( 'name' => 'wpd_order_customer_id', 'id' => 'wpd_order_customer_id', 'selected' => $order_customer_id, 'class' => 'widefat', 'show' => 'display_name_with_login' ) );
	echo '</div>';

	echo '<div class="order-details-box wide-box">';
	echo wpd_ecommerce_table_order_data( $post->ID, $user_info->ID );
	echo '</div>';

}

/**
 * Save the Metabox Data
 */
function wpd_ecommerce_save_order_item_details( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if (
		! isset( $_POST['wpd_ecommerce_order_item_details_meta_noncename' ] ) ||
		! wp_verify_nonce( $_POST['wpd_ecommerce_order_item_details_meta_noncename'], plugin_basename( __FILE__ ) )
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
	$wpd_ecommerce_order_meta['wpd_order_status']      = $_POST['wpd_order_status'];
	$wpd_ecommerce_order_meta['wpd_order_customer_id'] = $_POST['wpd_order_customer_id'];

	/** Add values of $wpd_ecommerce_order_meta as custom fields */

	foreach ( $wpd_ecommerce_order_meta as $key => $value ) { /** Cycle through the array! */
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

add_action( 'save_post', 'wpd_ecommerce_save_order_item_details', 1, 2 ); // Save the custom fields.


/**
 * Order patient details metabox
 *
 * Adds the order details metabox.
 *
 * @since    1.0
 */
function wpd_ecommerce_order_patient_details_metaboxes() {
	add_meta_box(
		'wpd_ecommerce_order_patient_details',
		__( 'Patient details', 'wpd-ecommerce' ),
		'wpd_ecommerce_order_patient_details_build',
		'wpd_orders',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wpd_ecommerce_order_patient_details_metaboxes' );

/**
 * Building the metabox
 */
function wpd_ecommerce_order_patient_details_build() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="wpd_ecommerce_order_patient_details_meta_noncename" id="wpd_ecommerce_order_patient_details_meta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the data if its already been entered */
	$order_customer_address = get_post_meta( $post->ID, 'wpd_order_customer_address', true );
	$order_customer_id      = get_post_meta( $post->ID, 'wpd_order_customer_id', true );

	echo '<div class="order-details-box">';
	echo '<p><strong>' . __( 'Address', 'wpd-ecommerce' ) . ':</strong></p>';

	$user_info = get_userdata( $order_customer_id );

	if ( '' != $user_info->address_line_1 ) {
		echo $user_info->address_line_1 . "<br />";
	}
	if ( '' != $user_info->address_line_2 ) {
		echo $user_info->address_line_2 . "<br />";
	}
	echo $user_info->city . ", " . $user_info->state_county . " " . $user_info->postcode_zip . "<br />";

	if ( '' != $user_info->user_email ) {
		echo "<p><strong>Email address:</strong></p>";
		echo "<a href='mailto:" . $user_info->user_email . "'>" . $user_info->user_email . "</a>";
	}

	if ( '' != $user_info->phone_number ) {
		echo "<p><strong>Phone number:</strong></p>";
		echo "<a href='tel:" . $user_info->phone_number . "'>" . $user_info->phone_number . "</a>";
	}

	echo '</div>';

}

/**
 * Save the Metabox Data
 */
function wpd_ecommerce_save_order_patient_details( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if (
		! isset( $_POST['wpd_ecommerce_order_patient_details_meta_noncename' ] ) ||
		! wp_verify_nonce( $_POST['wpd_ecommerce_order_patient_details_meta_noncename'], plugin_basename( __FILE__ ) )
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
	$wpd_ecommerce_order_meta['wpd_order_customer_address'] = $_POST['wpd_order_customer_address'];

	/** Add values of $wpd_ecommerce_order_meta as custom fields */

	foreach ( $wpd_ecommerce_order_meta as $key => $value ) { /** Cycle through the array! */
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

add_action( 'save_post', 'wpd_ecommerce_save_patient_details', 1, 2 ); // Save the custom fields.
