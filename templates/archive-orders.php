<?php
/*
Copy this file into your theme to customize
*/
get_header(); ?>
<section id="primary" class="site-content">
    <div id="content" role="main">
        <?php
            if ( is_user_logged_in() ) {
                $user              = wp_get_current_user();
                $role              = ( array ) $user->roles;
                $order_customer_id = get_post_meta( get_the_ID(), 'wpd_order_customer_id', true );

                // Redirect patient.
                if ( 'patient' === $role[0] ) {
                    wp_redirect( home_url() . '/account/' );
                } else {
                    wp_redirect( home_url() . '/dispensary-menu/' );
                }
            } else {
                // Redirect non-logged in users to menu page.
                wp_redirect( home_url() . '/dispensary-menu/' );
            }
        ?>
    </div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
