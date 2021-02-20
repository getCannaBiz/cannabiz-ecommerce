<?php
/**
 * WP Dispensary eCommerce session related functions
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Destroy Session
 * 
 * @since       1.0.0
 */
function wpd_ecommerce_destroy_session( $eat_cookies = NULL ) {
	// Unset all of the session variables.
	$_SESSION = array();

	/**
	 * Destroy cookies
	 * 
	 * If it's desired to kill the session, also delete the session cookie.
	 */
	if ( TRUE == $eat_cookies ) {
		if ( ini_get("session.use_cookies" ) ) {
			$params = session_get_cookie_params();
			setcookie( session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
	}

	// Finally, destroy the session.	
    session_destroy();
}


/**
 * Destroy Session Logout
 *
 * @since 1.0
 */
function wpd_ecommerce_logout() {
	wpd_ecommerce_destroy_session( TRUE );
}
add_action( 'wp_logout', 'wpd_ecommerce_logout' );

/**
 * Load Session
 * 
 * @since       1.0
 */
function wpd_ecommerce_load_session() {
	if ( ! isset( $_SESSION ) ) {
		$_SESSION = null;
	}

	if ( ( ! is_array( $_SESSION ) ) xor ( ! isset( $_SESSION['wpd_ecommerce'] ) ) xor ( ! $_SESSION ) ) {

		// Set session name.
		session_name( 'wpd_ecommerce' );

		// Start session and set cookie time from WPD Settings.
		session_start( [
			'cookie_lifetime' => apply_filters( 'wpd_ecommerce_cookie_lifetime', get_wpd_cookie_lifetime() ),
		] );

	}
}
wpd_ecommerce_load_session();

/**
 * initialise the cart session, if it exist, unserialize it, otherwise make it.
 */
if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
	if ( is_object( $_SESSION['wpd_ecommerce'] ) ) {
		$GLOBALS['wpd_ecommerce'] = $_SESSION['wpd_ecommerce'];
	} else {
		$GLOBALS['wpd_ecommerce'] = unserialize( $_SESSION['wpd_ecommerce'] );
	}
	if ( ! is_object( $GLOBALS['wpd_ecommerce'] ) || ( get_class( $GLOBALS['wpd_ecommerce'] ) != "wpsc_cart" ) ) {
		$GLOBALS['wpd_ecommerce'] = new Cart;
	}
} else {
	$GLOBALS['wpd_ecommerce'] = new Cart;
}

/**
 * Calculate cart sum
 * 
 * This calculates the cart total on page load.
 * 
 * @since 1.0
 */
if ( ! empty( $_SESSION ) ) {
	$_SESSION['wpd_ecommerce']->calculate_cart_sum();
}
