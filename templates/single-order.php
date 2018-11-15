<?php
/*
Copy this file into your theme to customize
*/
get_header(); ?>
<?php
if ( is_user_logged_in() ) {
    $user              = wp_get_current_user();
    $role              = ( array ) $user->roles;
    $order_customer_id = get_post_meta( get_the_ID(), 'wpd_order_customer_id', true );
    $order_subtotal    = get_post_meta( get_the_ID(), 'wpd_order_subtotal_price', true );
    $order_total       = get_post_meta( get_the_ID(), 'wpd_order_total_price', true );
    $order_items       = get_post_meta( get_the_ID(), 'wpd_order_items', true );
    $status_names      = wpd_ecommerce_get_order_statuses();
    $status            = get_post_meta( get_the_ID(), 'wpd_order_status', TRUE );
    $status_display    = wpd_ecommerce_order_statuses( get_the_ID(), NULL, NULL );

    if ( $user->ID != $order_customer_id && 'administrator' === $role[0] ) {
        // Administrators who are NOT the customer
    } elseif ( $user->ID === $order_customer_id && 'administrator' === $role[0] ) {
        // Administrators who ARE the customer
    } elseif ( $user->ID != $order_customer_id && 'patient' === $role[0] ) {
        // Patients who are ARE NOT the customer.
        wp_redirect( home_url() . '/account/' );
    } elseif ( $user->ID == $order_customer_id ) {
        // If current user IS the customer.
    } else {
        // If not patient or admin, redirect to account page.
        if ( 'patient' != $role[0] && 'administrator' != $role[0] ) {
            wp_redirect( home_url() . '/account/' );
        }
    }
?>

<div id="primary" class="col-lg-8 content-area">
    <main id="main" class="site-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="item_name"><?php the_title(); ?><?php echo $status_display; ?></h1>
            </header>
            <div class="entry-content order-details">
                <?php
                    echo '<div class="order-info">';
                    echo '<p><strong>' . __( 'Details', 'wpd-ecommerce' ) . ':</strong></p>';
                    echo "<p>" . get_the_date() . "<br />";
                    echo "Subtotal: " . CURRENCY . $order_subtotal . "<br />";
                    echo "Total: " . CURRENCY . $order_total . "</p>";
                    echo '</div>';
                    echo '<div class="patient-address">';
                    echo '<p><strong>' . __( 'Address', 'wpd-ecommerce' ) . ':</strong></p>';

                    $user_info = get_userdata( $order_customer_id );

                    if ( '' != $user_info->first_name ) {
                        echo $user_info->first_name . " ";
                    }

                    if ( '' != $user_info->last_name ) {
                        echo $user_info->last_name . "<br />";
                    }

                    if ( '' != $user_info->address_line_1 ) {
                        echo $user_info->address_line_1 . "<br />";
                    }

                    if ( '' != $user_info->address_line_2 ) {
                        echo $user_info->address_line_2 . "<br />";
                    }
                    echo $user_info->city . ", " . $user_info->state_county . " " . $user_info->postcode_zip . "<br />";
                    echo '</div>';

                    echo '<div class="patient-contact">';
                    echo "<p><strong>Contact:</strong></p>";
                    if ( '' != $user_info->user_email ) {
                        echo "<a class='email-address' href='mailto:" . $user_info->user_email . "'>" . $user_info->user_email . "</a><br />";
                    }

                    if ( '' != $user_info->phone_number ) {
                        echo "<a class='phone-number' href='tel:" . $user_info->phone_number . "'>" . $user_info->phone_number . "</a>";
                    }
                    echo '</div>';
                ?>
            <?php
                if ( 'patient' === $role[0] ) {
                    echo wpd_ecommerce_table_order_data( get_the_ID(), $user->ID );
                } elseif ( 'administrator' === $role[0] ) {
                    echo wpd_ecommerce_table_order_data( get_the_ID(), $order_customer_id );
                } else {
                    wp_redirect( home_url() . '/account/' );
                }
            ?>
            </div>
        </div>
        <?php endwhile; ?>
    </main>
</div>
<?php
} else {
    wp_redirect( home_url() . '/account/' );
}
?>

<?php wp_reset_query(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
