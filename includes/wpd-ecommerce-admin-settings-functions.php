<?php
/**
 * WP Dispensary eCommerce admin settings helper functions
 *
 * @package    WPD_eCommerce
 * @subpackage WPD_eCommerce/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @license    GPL-2.0+ 
 * @link       https://cannabizsoftware.com
 * @since      2.0.0
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
    $wpdas_general = get_option( 'wpdas_general' );
    $min_checkout  = '';

    if ( isset( $wpdas_general['wpd_ecommerce_checkout_minimum_order'] ) ) {
        $min_checkout = $wpdas_general['wpd_ecommerce_checkout_minimum_order'];
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
    $login_to_shop = null;

    // Check if user is required to be logged in to shop.
    if ( isset( $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'] ) ) {
        $login_to_shop = $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'];
    }

    // Filter login to shop requirement.
    $login_to_shop = apply_filters( 'wpd_ecommerce_require_login_to_shop', $login_to_shop );

    return $login_to_shop;
}
