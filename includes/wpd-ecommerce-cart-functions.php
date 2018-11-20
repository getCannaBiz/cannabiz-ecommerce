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
