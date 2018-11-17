<?php
/**
 * The plugin bootstrap file
 *
 * @link         https://www.wpdispensary.com
 * @since        1.0.0
 * @package      WPD_eCommerce
 *
 * Plugin Name:  WP Dispensary's eCommerce
 * Plugin URI:   https://www.wpdispensary.com/product/ecommerce
 * Description:  Adds shopping cart capabilities to the WP Dispensary menu management plugin.
 * Version:      0.1
 * Author:       WP Dispenary
 * Author URI:   https://www.wpdispensary.com
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:  wpd-ecommerce
 * Domain Path:  /languages
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Development mode.
define( 'DEV', FALSE );

/**
 * @todo make these number filterable
 * and customizable in settings by admin
 */

/**
 * WP Dispensary eCommerce
 * 
 * @since 1.0
 */
function wpd_ecommerce() {

	/**
	 * Access all settings
	 */
	$wpd_settings = get_option( 'wpdas_general' );

	//print_r( $wpd_settings );	

	// Check if WP Dispensary setting is set.
	if ( ! isset( $wpd_settings['wpd_ecommerce_sales_tax'] ) ) {
		define( 'SALES_TAX', NULL );
	} else {
		// Create sales tax.
		$sales_tax = $wpd_settings['wpd_ecommerce_sales_tax'] * 0.01;

		// Define sales tax.
		define( 'SALES_TAX', $sales_tax );
	}

	if ( ! isset( $wpd_settings['wpd_ecommerce_excise_tax'] ) ) {
		define( 'EXCISE_TAX', NULL );
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

// Includes for Helper Functions.
include_once( dirname(__FILE__).'/includes/wpd-ecommerce-core-functions.php' );
include_once( dirname(__FILE__).'/includes/wpd-ecommerce-cart-functions.php' );
include_once( dirname(__FILE__).'/includes/wpd-ecommerce-orders-functions.php' );
include_once( dirname(__FILE__).'/includes/wpd-ecommerce-patients-functions.php' );

// Includes for Classes.
include_once( dirname(__FILE__).'/classes/class-cart.php' );
include_once( dirname(__FILE__).'/classes/class-item.php' );

// Includes for Cart.
include_once( dirname(__FILE__).'/cart/cart-widget.php' );
include_once( dirname(__FILE__).'/cart/cart-shortcode.php' );

// Includes for Checkout.
include_once( dirname(__FILE__).'/checkout/checkout-shortcode.php' );
include_once( dirname(__FILE__).'/checkout/checkout-complete-shortcode.php' );

// Includes for Patients.
include_once( dirname(__FILE__).'/patients/patient-account-details.php' );
include_once( dirname(__FILE__).'/patients/patient-account-shortcode.php' );

// Includes for Orders.
include_once( dirname(__FILE__).'/orders/orders-database.php' );
include_once( dirname(__FILE__).'/orders/orders-post-type.php' );
include_once( dirname(__FILE__).'/orders/orders-metaboxes.php' );

// Add output buffering to help with checkout redirect.
function wpd_ecommerce_output_buffer() {
	ob_start();
}
add_action( 'init', 'wpd_ecommerce_output_buffer' );

/**
 * @todo look over caps options for users and 
 * set up the patient user role to work exactly how
 * I want it and need it to
 */

 // Add Patient User Role on Plugin Activation.
function wpd_ecommerce_add_patient_user_role_activation() {
	add_role( 'patient', 'Patient', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );
}
register_activation_hook( __FILE__, 'wpd_ecommerce_add_patient_user_role_activation' );

/**
 * Add custom pages on plugin activation.
 * 
 * @since 1.0
 */
function wpd_ecommerce_add_ecommerce_pages_activation() {
	/**
	 * Create Required Pages
	 *
	 * @since 1.0
	 */
	if ( ! current_user_can( 'activate_plugins' ) ) return;

	global $wpdb;

	// Get current user.
	$current_user = wp_get_current_user();

	if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'dispensary-menu'", 'ARRAY_A' ) ) {

		// create checkout page.
		$page_checkout = array(
			'post_title'   => __( 'Checkout' ),
			'post_status'  => 'publish',
			'post_author'  => $current_user->ID,
			'post_type'    => 'page',
			'post_content' => '[wpd_checkout]',
		);
		wp_insert_post( $page_checkout );

		// create checkout complete page.
		$page_checkout_complete = array(
			'post_title'   => __( 'Order Complete' ),
			'post_status'  => 'publish',
			'post_author'  => $current_user->ID,
			'post_type'    => 'page',
			'post_content' => '[wpd_checkout_complete]',
		);
		wp_insert_post( $page_checkout_complete );

		// create cart page.
		$page_cart = array(
			'post_title'   => __( 'Cart' ),
			'post_status'  => 'publish',
			'post_author'  => $current_user->ID,
			'post_type'    => 'page',
			'post_content' => '[wpd_cart]',
		);
		wp_insert_post( $page_cart );

		// create account page.
		$page_account = array(
			'post_title'   => __( 'My Account' ),
			'post_status'  => 'publish',
			'post_author'  => $current_user->ID,
			'post_type'    => 'page',
			'post_content' => '[wpd_account]',
		);
		wp_insert_post( $page_account );

	}

}
register_activation_hook( __FILE__, 'wpd_ecommerce_add_ecommerce_pages_activation' );

/**
 * Create orders database table on install
 * 
 * @since       1.0.0
 */
function wpd_ecommerce_db_install() {
	// Run function.
	wpd_ecommerce_orders_database_install();
}
register_activation_hook( __FILE__, 'wpd_ecommerce_db_install' );

/**
 * Load admin scripts and styles
 *
 * @since       1.0.0
 * @return      void
 */
function wpd_ecommerce_load_admin_scripts() {
	wp_enqueue_style( 'wpd-ecommerce-admin', plugin_dir_url( __FILE__ ) . 'assets/css/wpd-ecommerce-admin.min.css' );
}
add_action( 'admin_enqueue_scripts', 'wpd_ecommerce_load_admin_scripts' );

/**
 * Load public scripts and styles
 *
 * @since       1.0.0
 * @return      void
 */
function wpd_ecommerce_load_public_scripts() {
	wp_enqueue_style( 'wpd-ecommerce-public', plugin_dir_url( __FILE__ ) . 'assets/css/wpd-ecommerce-public.min.css' );
}
add_action( 'wp_enqueue_scripts', 'wpd_ecommerce_load_public_scripts' );

/**
 * Load Session
 *
 * @todo move this to another file and allow setting for cookie time.
 * 
 * possible move to the activation file where all of those codes can run
 * 
 * @since       1.0.0
 */
function wpd_ecommerce_load_session() {
	if ( ! isset( $_SESSION ) ) {
		$_SESSION = null;
	}
	if ( ( ! is_array( $_SESSION ) ) xor ( ! isset( $_SESSION['wpd_ecommerce'] ) ) xor ( !$_SESSION ) ) {
		session_name( 'wpd_ecommerce' );
		session_start( [
			'cookie_lifetime' => 86400,
		] );
	}
}
wpd_ecommerce_load_session();

/**
 * Destroy Session Logout
 *
 * @since       1.0
 */
function wpd_ecommerce_logout() {
	wpd_ecommerce_destroy_session( TRUE );
}
add_action( 'wp_logout', 'wpd_ecommerce_logout' );

/**
 * initialise the cart session, if it exist, unserialize it, otherwise make it.
 * 
 * @todo see if this can be removed
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
 * @todo make sure this is fast/loading properly.
 */
if ( ! empty( $_SESSION ) ) {
	$_SESSION['wpd_ecommerce']->calculate_cart_sum();

	echo "<pre>";
	print_r( $_SESSION );
	echo "</pre>";
	
}

/**
 * Custom Templates
 * 
 * @since 1.0
 */
function wpd_ecommerce_include_template_function( $template_path ) {

	 // WP Dispensary products.
	if ( in_array( get_post_type(), apply_filters( 'wpd_ecommerce_post_type_templates', array( 'flowers', 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-item.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/templates/single-item.php';
            }
        } elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array ( 'archive-items.php' ) ) ) {
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

/**
 * Custom database options
 * 
 * @todo move this to the registration functions file.
 * 
 * @since 1.0
 */
function wpd_ecommerce_add_options() {
	// wpd_ecommerce_add_options: add options to DB for this plugin
	add_option( 'wpd_ecommerce_account_page', home_url() . '/account/' );
}
wpd_ecommerce_add_options();

