<?php
/**
 * Copy this file into your theme to customize
 * 
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 */
get_header(); ?>
<section id="primary" class="site-content">
    <div id="content" role="main">
        <?php
        // Redirect visitors.
        if ( is_user_logged_in() ) {
            // Get user info.
            $user = wp_get_current_user();
            $role = ( array ) $user->roles;
            // Redirect customer.
            if ( 'customer' === $role[0] ) {
                wp_safe_redirect( wpd_ecommerce_account_url() );
            } else {
                wp_safe_redirect( wpd_ecommerce_menu_url() );
            }
        } else {
            // Redirect non-logged in users to menu page.
            wp_safe_redirect( wpd_ecommerce_menu_url() );
        }
        ?>
    </div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
