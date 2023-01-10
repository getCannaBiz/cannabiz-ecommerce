<?php
/**
 * The plugin bootstrap file
 *
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 *
 * Plugin Name:  WP Dispensary's eCommerce
 * Plugin URI:   https://www.wpdispensary.com/product/ecommerce
 * Description:  Adds eCommerce capabilities to the WP Dispensary menu management plugin.
 * Version:      2.2.0
 * Author:       WP Dispenary
 * Author URI:   https://www.wpdispensary.com
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:  wpd-ecommerce
 * Domain Path:  /languages
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
    wp_die();
}

// Development mode.
define( 'DEV', false );

/**
 * WP Dispensary eCommerce
 * 
 * @since  1.0
 * @return void
 */
function wpd_ecommerce() {

    /**
     * Access all settings
     */
    $wpd_settings = get_option( 'wpdas_general' );

    //print_r( $wpd_settings );    

    // Define sales tax (if any).
    if ( ! isset( $wpd_settings['wpd_ecommerce_sales_tax'] ) || '' == $wpd_settings['wpd_ecommerce_sales_tax'] ) {
        define( 'SALES_TAX', null );
    } else {
        // Create sales tax.
        $sales_tax = $wpd_settings['wpd_ecommerce_sales_tax'] * 0.01;

        // Define sales tax.
        define( 'SALES_TAX', $sales_tax );
    }

    // Define excise tax (if any).
    if ( ! isset( $wpd_settings['wpd_ecommerce_excise_tax'] ) || '' == $wpd_settings['wpd_ecommerce_excise_tax'] ) {
        define( 'EXCISE_TAX', null );
    } else {
        // Create excise tax.
        $excise_tax = $wpd_settings['wpd_ecommerce_excise_tax'] * 0.01;

        // Define excise tax.
        define( 'EXCISE_TAX', $excise_tax );
    }

    // Define currency code.
    define( 'CURRENCY', wpd_currency_code() );

}
wpd_ecommerce();

/**
 * Include add-ons directly
 * 
 * @return void
 */
function wpd_ecommerce_include_addons() {
    if ( ! function_exists( 'run_wpd_inventory' ) ) {
        // Include inventory management.
        include_once( dirname( __FILE__ ) . '/includes/extend/inventory/wpd-inventory.php' );
    }

    if ( ! function_exists( 'run_wpd_topsellers' ) ) {
        // Include top sellers management.
        include_once( dirname( __FILE__ ) . '/includes/extend/topsellers/wpd-topsellers.php' );
    }

    if ( ! function_exists( 'run_wpd_locations' ) ) {
        // Include location management.
        include_once( dirname( __FILE__ ) . '/includes/extend/locations/wpd-locations.php' );
    }

    if ( ! function_exists( 'run_wpd_heavyweights' ) ) {
        // Include heavyweights management.
        include_once( dirname( __FILE__ ) . '/includes/extend/heavyweights/wpd-heavyweights.php' );
    }
}
add_action( 'init', 'wpd_ecommerce_include_addons' );

// Includes for plugin activation.
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-activation.php' );

// Includes for Helper Functions.
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-sessions-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-core-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-admin-settings-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-cart-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-orders-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-customers-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-archive-items-functions.php' );

// Includes for Classes.
include_once( dirname( __FILE__ ) . '/classes/class-cart.php' );
include_once( dirname( __FILE__ ) . '/classes/class-item.php' );
include_once( dirname( __FILE__ ) . '/classes/class-csv-customer-export.php' );

// Includes for Cart.
include_once( dirname( __FILE__ ) . '/cart/cart-widget.php' );
include_once( dirname( __FILE__ ) . '/cart/cart-shortcode.php' );

// Includes for Checkout.
include_once( dirname( __FILE__ ) . '/checkout/checkout-shortcode.php' );

// Includes for Customers.
include_once( dirname( __FILE__) . '/customers/customer-account-details.php' );
include_once( dirname( __FILE__) . '/customers/customer-account-shortcode.php' );

// Includes for Orders.
include_once( dirname( __FILE__ ) . '/orders/orders-database.php' );
include_once( dirname( __FILE__ ) . '/orders/orders-post-type.php' );
include_once( dirname( __FILE__ ) . '/orders/orders-metaboxes.php' );

/**
 * Add output buffering to help with checkout redirect.
 * 
 * @return void
 */
function wpd_ecommerce_output_buffer() {
    ob_start();
}
add_action( 'init', 'wpd_ecommerce_output_buffer' );

/**
 * Load admin scripts and styles
 *
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_load_admin_scripts() {
    wp_enqueue_style( 'wpd-ecommerce-fontawesome', plugin_dir_url( __FILE__ ) . 'assets/css/fontawesome.min.css' );
    wp_enqueue_style( 'wpd-ecommerce-admin', plugin_dir_url( __FILE__ ) . 'assets/css/wpd-ecommerce-admin.min.css' );
}
add_action( 'admin_enqueue_scripts', 'wpd_ecommerce_load_admin_scripts' );

/**
 * Load public scripts and styles
 *
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_load_public_scripts() {
    wp_enqueue_style( 'wpd-ecommerce-public', plugin_dir_url( __FILE__ ) . 'assets/css/wpd-ecommerce-public.min.css' );
    wp_enqueue_style( 'wpd-ecommerce-fontawesome', plugin_dir_url( __FILE__ ) . 'assets/css/fontawesome.min.css' );
    wp_enqueue_script( 'wpd-ecommerce-public', plugin_dir_url( __FILE__ ) . 'assets/js/wpd-ecommerce-public.js', array( 'jquery' ), '2.2.0', false );

    if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
        // Translation array data.
        $translation_array = array(
            'pageURL'             => get_the_permalink(),
            'ajaxurl'             => admin_url( 'admin-ajax.php' ),
            'PAYMENT_TYPE_AMOUNT' => $_SESSION['wpd_ecommerce']->payment_type_amount,
            'session_data'        => $_SESSION['wpd_ecommerce']
        );
    } else {
        // Translation array data.
        $translation_array = array(
            'pageURL'             => get_the_permalink(),
            'ajaxurl'             => admin_url( 'admin-ajax.php' ),
            'PAYMENT_TYPE_AMOUNT' => '0',
            'session_data'        => ''
        );
        
    }

    // Translation array filter.
    $translation_array = apply_filters( 'wpd_ecommerce_localize_script_translation_array', $translation_array );

    // Localize script.
    wp_localize_script( 'wpd-ecommerce-public', 'object_name', $translation_array );

}
add_action( 'wp_enqueue_scripts', 'wpd_ecommerce_load_public_scripts' );

/**
 * Custom Templates
 * 
 * @param string $template_path 
 * 
 * @since  1.0
 * @return string
 */
function wpd_ecommerce_include_template_function( $template_path ) {

    // WP Dispensary products.
    if ( 'products' === get_post_type() ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array( 'single-item.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/templates/single-item.php';
            }
        } elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array( 'archive-items.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/templates/archive-items.php';
            }
        }
    }

    // Orders templates.
    if ( 'wpd_orders' === get_post_type() ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-order.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/templates/single-order.php';
            }
        } elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array ( 'archive-orders.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/templates/archive-orders.php';
            }
        }
    }

    return $template_path;
}
add_filter( 'template_include', 'wpd_ecommerce_include_template_function', 1 );
