<?php
/**
 * WP Dispensary eCommerce patient helper functions
 *
 * @package WPD_eCommerce/functions
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Disable admin bar for patients.
 *
 * @since 1.0.0
 */
function wpd_ecommerce_disable_admin_bar() {
    if ( current_user_can( 'patient' ) ) {
        // user can view admin bar
        show_admin_bar( FALSE ); // this line isn't essentially needed by default...
    } else {
        // Do nothing.
    }
}
add_action( 'after_setup_theme', 'wpd_ecommerce_disable_admin_bar' );
 
/**
 * Disable wp-admin for patients.
 *
 * @since 1.0.0
 */
function wpd_ecommerce_disable_admin_dashboard() {
    if ( is_admin() && current_user_can( 'patient' ) && 
       ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() . '/account/' );
        exit;
    }
}
add_action( 'init', 'wpd_ecommerce_disable_admin_dashboard' );

/**
 * Disable author archives for patients.
 *
 * @since 1.0
 */
function wpd_ecommerce_disable_author_archives_for_customers() {
	global $author;
	if ( is_author() ) {
		$user = get_user_by( 'id', $author );
		if ( user_can( $user, 'patient' ) && ! user_can( $user, 'edit_posts' ) ) {
			wp_redirect( home_url() . '/dispensary-menu/' );
		}
	}
}
add_action( 'template_redirect', 'wpd_ecommerce_disable_author_archives_for_customers' );

/**
 * Prevent wp-login.php
 */
function wpd_ecommerce_prevent_wp_login() {

    global $pagenow;

    /**
     * @todo check and only run this for patients.
     */

    // Check if a $_GET['action'] is set, and if so, load it into $action variable
    $action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';

    // Check if we're on the login page, and ensure the action is not 'logout'
    if ( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array( $action, array( 'logout', 'lostpassword', 'rp', 'resetpass' ) ) ) ) ) {

        $page = home_url() . '/account/?login=failed';

        wp_redirect( $page );
    }
}
add_action( 'init', 'wpd_ecommerce_prevent_wp_login' );

/**
 * Login failed redirect
 */
function login_failed() {
    //$login_page = home_url() . '/account/';
    //wp_redirect( $login_page . '?login=failed' );
    //exit;
}
add_action( 'wp_login_failed', 'login_failed' );
