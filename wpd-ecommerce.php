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
 * Version:      1.5
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

	// Define sales tax (if any).
	if ( ! isset( $wpd_settings['wpd_ecommerce_sales_tax'] ) ) {
		define( 'SALES_TAX', NULL );
	} else {
		// Create sales tax.
		$sales_tax = $wpd_settings['wpd_ecommerce_sales_tax'] * 0.01;

		// Define sales tax.
		define( 'SALES_TAX', $sales_tax );
	}

	// Define excise tax (if any).
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
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-core-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-admin-settings-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-cart-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-orders-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-patients-functions.php' );
include_once( dirname( __FILE__ ) . '/includes/wpd-ecommerce-archive-items-functions.php' );

// Includes for Classes.
include_once( dirname( __FILE__ ) . '/classes/class-cart.php' );
include_once( dirname( __FILE__ ) . '/classes/class-item.php' );

// Includes for Cart.
include_once( dirname( __FILE__ ) . '/cart/cart-widget.php' );
include_once( dirname( __FILE__ ) . '/cart/cart-shortcode.php' );

// Includes for Checkout.
include_once( dirname( __FILE__ ) . '/checkout/checkout-shortcode.php' );

// Includes for Patients.
include_once( dirname( __FILE__) . '/patients/patient-account-details.php' );
include_once( dirname( __FILE__) . '/patients/patient-account-shortcode.php' );

// Includes for Orders.
include_once( dirname( __FILE__ ) . '/orders/orders-database.php' );
include_once( dirname( __FILE__ ) . '/orders/orders-post-type.php' );
include_once( dirname( __FILE__ ) . '/orders/orders-metaboxes.php' );

// Add output buffering to help with checkout redirect.
function wpd_ecommerce_output_buffer() {
	ob_start();
}
add_action( 'init', 'wpd_ecommerce_output_buffer' );

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

	// create checkout page.
	$page_checkout = array(
		'post_title'   => __( 'Checkout', 'wpd-ecommerce' ),
		'post_status'  => 'publish',
		'post_author'  => $current_user->ID,
		'post_type'    => 'page',
		'post_content' => '[wpd_checkout]',
	);
	wp_insert_post( $page_checkout );

	// create cart page.
	$page_cart = array(
		'post_title'   => __( 'Cart', 'wpd-ecommerce' ),
		'post_status'  => 'publish',
		'post_author'  => $current_user->ID,
		'post_type'    => 'page',
		'post_content' => '[wpd_cart]',
	);
	wp_insert_post( $page_cart );

	// create account page.
	$page_account = array(
		'post_title'   => __( 'Account', 'wpd-ecommerce' ),
		'post_status'  => 'publish',
		'post_author'  => $current_user->ID,
		'post_type'    => 'page',
		'post_content' => '[wpd_account]',
	);
	wp_insert_post( $page_account );

}
register_activation_hook( __FILE__, 'wpd_ecommerce_add_ecommerce_pages_activation' );

/**
 * Create orders database table on install
 * 
 * @since 1.0
 */
function wpd_ecommerce_db_install() {
	// Run function.
	wpd_ecommerce_orders_database_install();
}
register_activation_hook( __FILE__, 'wpd_ecommerce_db_install' );

/**
 * Custom database options
 * 
 * @since 1.0
 */
function wpd_ecommerce_add_options() {
	// Add page link options.
	add_option( 'wpd_ecommerce_page_account', home_url() . '/account/' );
	add_option( 'wpd_ecommerce_page_shop', home_url() . '/dispensary-menu/' );
	add_option( 'wpd_ecommerce_page_cart', home_url() . '/cart/' );
	add_option( 'wpd_ecommerce_page_checkout', home_url() . '/checkout/' );

	// Add flower product weight options.
	add_option( 'wpd_ecommerce_weight_flowers_gram', '1' );
	add_option( 'wpd_ecommerce_weight_flowers_twograms', '2' );
	add_option( 'wpd_ecommerce_weight_flowers_eighth', '3.5' );
	add_option( 'wpd_ecommerce_weight_flowers_fivegrams', '5' );
	add_option( 'wpd_ecommerce_weight_flowers_quarter', '7' );
	add_option( 'wpd_ecommerce_weight_flowers_half', '14' );
	add_option( 'wpd_ecommerce_weight_flowers_ounce', '28' );
	add_option( 'wpd_ecommerce_weight_flowers_twoounces', '56' );
	add_option( 'wpd_ecommerce_weight_flowers_quarterpound', '112' );
	add_option( 'wpd_ecommerce_weight_flowers_halfpound', '224' );
	add_option( 'wpd_ecommerce_weight_flowers_onepound', '448' );
	add_option( 'wpd_ecommerce_weight_flowers_twopounds', '896' );
	add_option( 'wpd_ecommerce_weight_flowers_threepounds', '1344' );
	add_option( 'wpd_ecommerce_weight_flowers_fourpounds', '1792' );
	add_option( 'wpd_ecommerce_weight_flowers_fivepounds', '2240' );
	add_option( 'wpd_ecommerce_weight_flowers_sixpounds', '2688' );
	add_option( 'wpd_ecommerce_weight_flowers_sevenpounds', '3136' );
	add_option( 'wpd_ecommerce_weight_flowers_eightpounds', '3584' );
	add_option( 'wpd_ecommerce_weight_flowers_ninepounds', '4032' );
	add_option( 'wpd_ecommerce_weight_flowers_tenpounds', '4480' );
	add_option( 'wpd_ecommerce_weight_flowers_elevenpounds', '4928' );
	add_option( 'wpd_ecommerce_weight_flowers_twelvepounds', '5376' );
	add_option( 'wpd_ecommerce_weight_flowers_thirteenpounds', '5824' );
	add_option( 'wpd_ecommerce_weight_flowers_fourteenpounds', '6272' );
	add_option( 'wpd_ecommerce_weight_flowers_fifteenpounds', '6720' );
	add_option( 'wpd_ecommerce_weight_flowers_twentypounds', '8960' );
	add_option( 'wpd_ecommerce_weight_flowers_twentyfivepounds', '11200' );
	add_option( 'wpd_ecommerce_weight_flowers_fiftypounds', '22400' );

	// Add concentrate product weight options.
	add_option( 'wpd_ecommerce_weight_concentrates_halfgram', '0.5' );
	add_option( 'wpd_ecommerce_weight_concentrates_gram', '1' );
	add_option( 'wpd_ecommerce_weight_concentrates_twograms', '2' );
	add_option( 'wpd_ecommerce_weight_concentrates_threegrams', '3' );
	add_option( 'wpd_ecommerce_weight_concentrates_fourgrams', '4' );
	add_option( 'wpd_ecommerce_weight_concentrates_fivegrams', '5' );
	add_option( 'wpd_ecommerce_weight_concentrates_sixgrams', '6' );
	add_option( 'wpd_ecommerce_weight_concentrates_sevengrams', '7' );
	add_option( 'wpd_ecommerce_weight_concentrates_eightgrams', '8' );
	add_option( 'wpd_ecommerce_weight_concentrates_ninegrams', '9' );
	add_option( 'wpd_ecommerce_weight_concentrates_tengrams', '10' );

	$wpdas_payments = array(
		'wpd_ecommerce_checkout_payments_cod_checkbox'    => 'off',
		'wpd_ecommerce_checkout_payments_cod'             => '',
		'wpd_ecommerce_checkout_payments_pop_checkbox'    => 'off',
		'wpd_ecommerce_checkout_payments_pop'             => '',
		'wpd_ecommerce_checkout_payments_ground_checkbox' => 'off',
		'wpd_ecommerce_checkout_payments_ground'          => '',
	);

	update_option( 'wpdas_payments', $wpdas_payments );

	$wpdas_pages = array(
		'wpd_pages_setup_menu_page'     => 'menu',
		'wpd_pages_setup_cart_page'     => 'cart',
		'wpd_pages_setup_checkout_page' => 'checkout',
		'wpd_pages_setup_account_page'  => 'account',
	);

	// Add admin settings default options.
	update_option( 'wpdas_pages', $wpdas_pages );

	$wpdas_display = array(
		'wpd_hide_pricing' => 'on'
	);

	// Add admin settings default options.
	update_option( 'wpdas_display', $wpdas_display );

}
register_activation_hook( __FILE__, 'wpd_ecommerce_add_options' );

//wpd_ecommerce_add_options();


/**
 * Load admin scripts and styles
 *
 * @since       1.0
 * @return      void
 */
function wpd_ecommerce_load_admin_scripts() {
	wp_enqueue_style( 'wpd-ecommerce-fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'wpd-ecommerce-admin', plugin_dir_url( __FILE__ ) . 'assets/css/wpd-ecommerce-admin.min.css' );
}
add_action( 'admin_enqueue_scripts', 'wpd_ecommerce_load_admin_scripts' );

/**
 * Load public scripts and styles
 *
 * @since       1.0
 * @return      void
 */
function wpd_ecommerce_load_public_scripts() {
	wp_enqueue_style( 'wpd-ecommerce-public', plugin_dir_url( __FILE__ ) . 'assets/css/wpd-ecommerce-public.min.css' );
	wp_enqueue_script( 'wpd-ecommerce-public', plugin_dir_url( __FILE__ ) . 'assets/js/wpd-ecommerce-public.js', array( 'jquery' ), '1.6', false );

	// Translation array data.
	$translation_array = array(
		'pageURL'             => get_the_permalink(),
		'ajaxurl'             => admin_url( 'admin-ajax.php' ),
		'PAYMENT_TYPE_AMOUNT' => $_SESSION['wpd_ecommerce']->payment_type_amount,
		'session_data'        => $_SESSION['wpd_ecommerce']
	);

	// Translation array filter.
	$translation_array = apply_filters( 'wpd_ecommerce_localize_script_translation_array', $translation_array );

	// Localize script.
	wp_localize_script( 'wpd-ecommerce-public', 'object_name', $translation_array );

}
add_action( 'wp_enqueue_scripts', 'wpd_ecommerce_load_public_scripts' );

/**
 * AJAX function to update payment type amount on checkout page.
 * 
 * @since 1.6
 * @return void
 */
function wpd_ecommerce_checkout_settings() {
	// Get metavalue (payment type cost).
	$metavalue = $_POST['metavalue'];
	$metaname  = $_POST['metaname'];
	$_SESSION['wpd_ecommerce']->payment_type_amount = $metavalue;
	$_SESSION['wpd_ecommerce']->payment_type_name   = $metaname;
    exit;
}
add_action( 'wp_ajax_wpd_ecommerce_checkout_settings', 'wpd_ecommerce_checkout_settings' );
add_action('wp_ajax_nopriv_wpd_ecommerce_checkout_settings', 'wpd_ecommerce_checkout_settings');

/**
 * Load Session
 * 
 * @since       1.0
 */
function wpd_ecommerce_load_session() {
	if ( ! isset( $_SESSION ) ) {
		$_SESSION = null;
	}

	/**
	 * @todo add Settings select options for admin to choose theirs for the cookie lifetime below
	 */
	$half_hour    = 86400 / 48;
	$one_hour     = 86400 / 24;
	$three_hours  = 86400 / 8;
	$six_hours    = 86400 / 4;
	$twelve_hours = 86400 / 2;
	$one_day      = 86400;

	//echo $twelve_hours;
	//echo ' --- ' . $three_hours;

	if ( ( ! is_array( $_SESSION ) ) xor ( ! isset( $_SESSION['wpd_ecommerce'] ) ) xor ( ! $_SESSION ) ) {

		// Set session name.
		session_name( 'wpd_ecommerce' );

		// session_set_cookie_params( $one_hour, get_bloginfo('home' ) );

		// Start session and set cookie for 1 day.
		session_start( [
			'cookie_lifetime' => apply_filters( 'wpd_ecommerce_cookie_lifetime', $half_hour ),
		] );

	}
}
wpd_ecommerce_load_session();

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

/**
 * Custom Templates
 * 
 * @since 1.0
 */
function wpd_ecommerce_include_template_function( $template_path ) {

	 // WP Dispensary products.
	if ( in_array( get_post_type(), apply_filters( 'wpd_ecommerce_post_type_templates', wpd_menu_types_simple( TRUE ) ) ) ) {
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
