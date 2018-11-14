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
