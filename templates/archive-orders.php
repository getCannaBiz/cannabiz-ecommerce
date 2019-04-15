<?php
/*
Copy this file into your theme to customize
*/
get_header(); ?>
<section id="primary" class="site-content">
    <div id="content" role="main">
        <?php
            // Get pages data from settings page.
            $wpdas_pages = get_option( 'wpdas_pages' );

            // Get menu page selected by website owner.
            $account_page = $wpdas_pages['wpd_pages_setup_account_page'];

            // Get menu page selected by website owner.
            $menu_page = $wpdas_pages['wpd_pages_setup_menu_page'];

            // Redirect visitors.
            if ( is_user_logged_in() ) {

                // Get user info.
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;

                // Redirect patient.
                if ( 'patient' === $role[0] ) {
                    wp_redirect( $account_page );
                } else {
                    wp_redirect( $menu_page );
                }
            } else {
                // Redirect non-logged in users to menu page.
                wp_redirect( $menu_page );
            }
        ?>
    </div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
