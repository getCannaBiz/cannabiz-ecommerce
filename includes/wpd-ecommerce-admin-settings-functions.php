<?php
/**
 * WP Dispensary eCommerce admin settings helper functions
 *
 * @since 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Checkout minimum order
 * 
 * @since  2.0
 * @return string
 */
function wpd_ecommerce_checkout_minimum_order() {
    // Get minimum order checkout requirement from settings.
    $wpdas_general  = get_option( 'wpdas_general' );
    if ( isset( $wpdas_general['wpd_ecommerce_checkout_minimum_order'] ) ) {
        $min_checkout = $wpdas_general['wpd_ecommerce_checkout_minimum_order'];
    } else {
        $min_checkout = '';
    }
    // Filter minimum checkout requirement.
    $min_checkout = apply_filters( 'wpd_ecommerce_checkout_minimum_order', $min_checkout );

    return $min_checkout;
}

/**
 * Require login to shop
 * 
 * @since  2.0
 * @return string
 */
function wpd_ecommerce_require_login_to_shop() {
    // Get WPD settings from General tab.
    $wpdas_general = get_option( 'wpdas_general' );

    // Check if user is required to be logged in to shop.
    if ( isset( $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'] ) ) {
        $login_to_shop = $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'];
    } else {
        $login_to_shop = null;
    }
    // Filter login to shop requirement.
    $login_to_shop = apply_filters( 'wpd_ecommerce_require_login_to_shop', $login_to_shop );

    return $login_to_shop;
}
