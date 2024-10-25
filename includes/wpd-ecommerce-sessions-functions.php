<?php
/**
 * WP Dispensary eCommerce session related functions
 *
 * @package    WPD_eCommerce
 * @subpackage WPD_eCommerce/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @license    GPL-2.0+ 
 * @link       https://cannabizsoftware.com
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Destroy Session
 * 
 * @param bool $eat_cookies 
 * 
 * @since  1.0.0
 * @return void
 */
function wpd_ecommerce_destroy_session( $eat_cookies = null ) {
    // Unset all of the session variables.
    $_SESSION = [];

    /**
     * Destroy cookies
     * 
     * If it's desired to kill the session, also delete the session cookie.
     */
    if ( true == $eat_cookies ) {
        if ( ini_get( 'session.use_cookies' ) ) {
            $params = session_get_cookie_params();
            setcookie( session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
    }

    // Finally, destroy the session.    
    session_destroy();
}


/**
 * Destroy Session Logout
 *
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_logout() {
    wpd_ecommerce_destroy_session( true );
}
add_action( 'wp_logout', 'wpd_ecommerce_logout' );


/**
 * WPD Admin Settings - Cookie lifetime
 *
 * @since  1.8
 * @return int
 */
function get_wpd_cookie_lifetime() {
    // Access all CannaBiz Menu Advanced Settings.
    $wpd_settings = get_option( 'wpdas_advanced' );

    // Check cookie lifetime settings.
    if ( isset ( $wpd_settings['wpd_settings_cookie_lifetime'] ) && '' !== $wpd_settings['wpd_settings_cookie_lifetime'] ) {
        $wpd_cookie_lifetime = $wpd_settings['wpd_settings_cookie_lifetime'];
    } else {
        // Default cookie lifetime.
        $wpd_cookie_lifetime = 'one_hour';
    }

    // Cookie times.
    $half_hour    = 86400 / 48;
    $one_hour     = 86400 / 24;
    $three_hours  = 86400 / 8;
    $six_hours    = 86400 / 4;
    $twelve_hours = 86400 / 2;
    $one_day      = 86400;

    if ( 'half_hour' == $wpd_cookie_lifetime ) {
        $wpd_cookie_lifetime = $half_hour;
    } elseif ( 'one_hour' == $wpd_cookie_lifetime ) {
        $wpd_cookie_lifetime = $one_hour;
    } elseif ( 'three_hours' == $wpd_cookie_lifetime ) {
        $wpd_cookie_lifetime = $three_hours;
    } elseif ( 'six_hours' == $wpd_cookie_lifetime ) {
        $wpd_cookie_lifetime = $six_hours;
    } elseif ( 'twelve_hours' == $wpd_cookie_lifetime ) {
        $wpd_cookie_lifetime = $twelve_hours;
    } elseif ( 'one_day' == $wpd_cookie_lifetime ) {
        $wpd_cookie_lifetime = $one_day;
    } else {
        // Do nothing.
    }

    // Create filterable cookie lifetime.
    $wpd_cookie_lifetime = apply_filters( 'wpd_cookie_lifetime', $wpd_cookie_lifetime );

    // Return the cookie lifetime.
    return $wpd_cookie_lifetime;
}


/**
 * Load Session
 * 
 * @since  1.0
 * @return void
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
    } else {
        // Close the session to avoid interference with REST API and loopback requests.
        if ( session_status() === PHP_SESSION_ACTIVE ) {
            session_write_close();
        }
    }
}


/**
 * Cart session
 * 
 * @since  2.0
 * @return void
 */
function wpd_ecommerce_cart_session() {
    wpd_ecommerce_load_session();

    /**
     * Initialise the cart session, if it exist, unserialize it, otherwise make it.
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
    
}
add_action( 'init', 'wpd_ecommerce_cart_session' );
