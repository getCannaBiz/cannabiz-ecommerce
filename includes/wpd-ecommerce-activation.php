<?php
/**
 * CannaBiz eCommerce activation functions
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
 * Add Customer user role.
 * 
 * @return void
 */
function wpd_ecommerce_add_customer_user_role_activation() {
    $role_set = get_option( 'wpd_ecommerce_customer_role_set' );
    if ( ! $role_set ) {
        add_role( 'customer', 'Customer', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );
        update_option( 'wpd_ecommerce_customer_role_set', true );
    }
}
add_action( 'plugins_loaded', 'wpd_ecommerce_add_customer_user_role_activation' );

/**
 * Add custom pages on plugin activation.
 * 
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_add_ecommerce_pages_activation() {
    /**
     * Create Required Pages
     *
     * @since 1.0
     */
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    global $wpdb;

    // Get current user.
    $current_user = wp_get_current_user();

    // create checkout page.
    $page_checkout = array(
        'post_title'   => esc_attr__( 'Checkout', 'wpd-ecommerce' ),
        'post_status'  => 'publish',
        'post_author'  => $current_user->ID,
        'post_type'    => 'page',
        'post_content' => '[wpd_checkout]',
    );
    wp_insert_post( $page_checkout );

    // create cart page.
    $page_cart = array(
        'post_title'   => esc_attr__( 'Cart', 'wpd-ecommerce' ),
        'post_status'  => 'publish',
        'post_author'  => $current_user->ID,
        'post_type'    => 'page',
        'post_content' => '[wpd_cart]',
    );
    wp_insert_post( $page_cart );

    // create account page.
    $page_account = array(
        'post_title'   => esc_attr__( 'Account', 'wpd-ecommerce' ),
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
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_db_install() {
    // Run function.
    wpd_ecommerce_orders_database_install();
}
register_activation_hook( __FILE__, 'wpd_ecommerce_db_install' );

/**
 * Custom database options
 * 
 * @since  1.0
 * @return void
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
        'wpd_ecommerce_checkout_payments_cod_checkbox'      => 'off',
        'wpd_ecommerce_checkout_payments_cod'               => '',
        'wpd_ecommerce_checkout_payments_pop_checkbox'      => 'on',
        'wpd_ecommerce_checkout_payments_pop'               => '',
        'wpd_ecommerce_checkout_payments_curbside_checkbox' => 'on',
        'wpd_ecommerce_checkout_payments_curbside'          => '',
        'wpd_ecommerce_checkout_payments_ground_checkbox'   => 'off',
        'wpd_ecommerce_checkout_payments_ground'            => '',
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
