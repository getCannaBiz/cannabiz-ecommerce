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

    // Only run for logged in users.
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        $role = ( array ) $user->roles;

        // Hide admin bar for patients.
        if ( 'patient' === $role[0] ) {
            show_admin_bar( FALSE );
        } else {
            show_admin_bar( TRUE );
        }
    }
}
add_action( 'after_setup_theme', 'wpd_ecommerce_disable_admin_bar' );
 
/**
 * Disable wp-admin for patients.
 *
 * @since 1.0.0
 */
function wpd_ecommerce_disable_admin_dashboard() {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    // Redirect patients from any wp-admin page to account page.
    if ( is_admin() && 'patient' === $role[0] && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() . '/account/' );
        exit;
    } elseif ( is_user_logged_in() ) {
        // Do nothing for any other logged in user.
    } else {
        /**
         * @todo additional checks for settings, so admin can totally disable wp-admin for non-logged in users,
         * redirecting them to the account page to log in.
         * 
         * wrap the below if statement in another check for the setting
         */

        // Redirect admin for non-logged in users.
        if ( ! is_user_logged_in() && is_admin() ) {
            wp_redirect( home_url() . '/account/' );
            exit;    
        }
    }

}
add_action( 'init', 'wpd_ecommerce_disable_admin_dashboard' );

/**
 * Prevent wp-login.php for patients
 */
function wpd_ecommerce_prevent_wp_login() {

    global $pagenow;

    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    /**
     * This locks down wp-login.php for patients only
     */
    if ( 'patient' === $role[0] ) {

        // Check if a $_GET['action'] is set, and if so, load it into $action variable
        $action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';

        // Check if we're on the login page, and ensure the action is not 'logout'
        if ( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array( $action, array( 'logout', 'lostpassword', 'rp', 'resetpass' ) ) ) ) ) {

            $page = home_url() . '/account/';

            wp_redirect( $page );
            exit;
        }
    }

}
add_action( 'init', 'wpd_ecommerce_prevent_wp_login' );

/**
 * Login failed redirect
 * 
 * @since 1.0
 */
function wpd_ecommerce_login_failed() {
    $login_page = home_url() . '/account/';
    $ref_link   = $_SERVER['HTTP_REFERER'];

    // Redirect logins from account page.
    if ( $login_page === $ref_link ) {
        wp_redirect( $login_page . '?login=failed' );
        exit;
    }
}
add_action( 'wp_login_failed', 'wpd_ecommerce_login_failed' );

/**
 * Login success redirect
 * 
 * @since 1.0
 */
function wpd_ecommerce_login_redirect( $redirect_to, $request, $user ) {
    $user       = wp_get_current_user();
    $role       = ( array ) $user->roles;
    $login_page = home_url() . '/account/';
    $ref_link   = $_SERVER['HTTP_REFERER'];

    // If user is patient.
    if ( 'patient' === $role[0] ) {
        // redirect them to another URL, in this case, the homepage 
        $redirect_to =  $login_page;
    }

    // Redirect logins from account page.
    if ( $ref_link === $login_page . '?login=failed' ) {
        $redirect_to =  $login_page;
    }

    return $redirect_to;
}
add_filter( 'login_redirect', 'wpd_ecommerce_login_redirect', 10, 3 );

/**
 * Disable author archives for patients.
 *
 * @since 1.0
 */
function wpd_ecommerce_disable_author_archives_for_customers() {
	global $author;
	if ( is_author() ) {
        $user = get_user_by( 'id', $author );
        $role = ( array ) $user->roles;
    
		if ( 'patient' === $role[0] ) {
			wp_redirect( home_url() . '/dispensary-menu/' );
		}
	}
}
add_action( 'template_redirect', 'wpd_ecommerce_disable_author_archives_for_customers' );
