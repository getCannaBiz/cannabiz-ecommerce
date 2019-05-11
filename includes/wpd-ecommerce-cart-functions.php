<?php

/**
 * Clear the cart
 * 
 * @since 1.0
 */
function wpd_ecommerce_clear_cart() {
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if ( ini_get( "session.use_cookies" ) ) {
        $params = session_get_cookie_params();
        setcookie( session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
}

/**
 * Add Items to Cart
 * 
 * @since 1.0
 */
function wpd_ecommerce_add_items_to_cart( $item_id, $count, $old_id, $new_price, $old_price ) {
	if ( empty( $_SESSION['wpd_ecommerce'] ) || ! isset( $_SESSION['wpd_ecommerce'] ) ):
		$c = new Cart;
		$c->add_item( $item_id, $count, $old_id, $new_price, $old_price );
		$_SESSION['wpd_ecommerce'] = $c;
	else:
		$_SESSION['wpd_ecommerce']->add_item( $item_id, $count, $old_id, $new_price, $old_price );
	endif;
}

/**
 * Ground shipping checkout text
 * 
 * @since 1.4
 */
function wpd_ecommerce_checkout_ground_shipping() {
    // Get Payments Settings. 
    $wpd_payments = get_option( 'wpdas_payments' );

    // Set ground shipping instructions.
    if ( ! empty( $wpd_payments['wpd_ecommerce_checkout_payments_ground_textarea'] ) ) {
        $ground_shipping = esc_html( $wpd_payments['wpd_ecommerce_checkout_payments_ground_textarea'] );
        echo '<p class="form-directions">' . $ground_shipping . '</p>';
    } else {
        $ground_shipping = NULL;
    }

}
add_action( 'wpd_ecommerce_checkout_after_order_details', 'wpd_ecommerce_checkout_ground_shipping' );
