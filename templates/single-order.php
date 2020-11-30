<?php
/*
Copy this file into your theme to customize
*/
get_header(); ?>
<?php

do_action( 'wpd_ecommerce_templates_single_orders_wrap_before' );

if ( ! is_user_logged_in() ) {
    // Redirect non-logged in users.
    wp_redirect( wpd_ecommerce_account_url() );
} else {

    $user              = wp_get_current_user();
    $role              = ( array ) $user->roles;
    $order_customer_id = get_post_meta( get_the_ID(), 'wpd_order_customer_id', true );
    $order_subtotal    = get_post_meta( get_the_ID(), 'wpd_order_subtotal_price', true );
    $order_total       = get_post_meta( get_the_ID(), 'wpd_order_total_price', true );
    $order_items       = get_post_meta( get_the_ID(), 'wpd_order_items', true );
    $status_names      = wpd_ecommerce_get_order_statuses();
    $status            = get_post_meta( get_the_ID(), 'wpd_order_status', TRUE );
    $status_display    = wpd_ecommerce_order_statuses( get_the_ID(), NULL, NULL );
    $get_id            = get_the_ID();

    $get_order_amount    = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_coupon_amount'", ARRAY_A );
    $order_coupon_amount = $get_order_amount[0]['order_value'];

    //print_r( $get_order_amount );

    $get_sales_tax   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_sales_tax'", ARRAY_A );
    $order_sales_tax = $get_sales_tax[0]['order_value'];

    //print_r( $get_sales_tax );

    $get_excise_tax   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_excise_tax'", ARRAY_A );
    $order_excise_tax = $get_excise_tax[0]['order_value'];

    //print_r( $get_sales_tax );

    $get_payment_type_amount   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_payment_type_amount'", ARRAY_A );
    $order_payment_type_amount = $get_payment_type_amount[0]['order_value'];

    $get_payment_type_name   = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'details' AND order_key = 'order_payment_type_name'", ARRAY_A );
    $order_payment_type_name = $get_payment_type_name[0]['order_value'];

    //print_r( $get_sales_tax );

    if ( $user->ID != $order_customer_id && 'administrator' === $role[0] ) {
        // Administrators who are NOT the customer
    } elseif ( $user->ID === $order_customer_id && 'administrator' === $role[0] ) {
        // Administrators who ARE the customer
    } elseif ( $user->ID != $order_customer_id && 'patient' === $role[0] ) {
        // Patients who are ARE NOT the customer.
        wp_redirect( wpd_ecommerce_account_url() );
    } elseif ( $user->ID == $order_customer_id ) {
        // If current user IS the customer.
    } else {
        // If not patient or admin, redirect to account page.
        if ( 'patient' != $role[0] && 'administrator' != $role[0] ) {
            wp_redirect( wpd_ecommerce_account_url() );
        }
    }
?>
    <?php while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="item_name"><?php the_title(); ?><?php echo $status_display; ?></h1>
            <?php
                // Include notifications.
                echo wpd_ecommerce_notifications();
            ?>
        </header>
        <div class="entry-content order-details">
            <?php
                // Get user info.
                $user_info = get_userdata( $order_customer_id );

                echo '<div class="order-info">';
                echo '<p><strong>' . __( 'Date', 'wpd-ecommerce' ) . ':</strong></p>';
                echo '<p>' . get_the_date() . '</p>';
                if ( isset( $user_info ) ) {
                    if ( '' != $user_info->first_name ) {
                        $first_name = $user_info->first_name;
                    } else {
                        $first_name = '';
                    }
                    if ( '' != $user_info->last_name ) {
                        $last_name = $user_info->last_name;
                    } else {
                        $last_name = '';
                    }
                } else {
                    $first_name = '';
                    $last_name  = '';
                }
                echo '<p><strong>' . __( 'Name', 'wpd-ecommerce' ) . ':</strong></p>';
                echo '<p>' . $first_name . ' ' . $last_name . '</p>';
                echo '</div>';
                echo '<div class="patient-address">';
                echo '<p><strong>' . __( 'Address', 'wpd-ecommerce' ) . ':</strong></p>';

                if ( '' != $user_info->address_line_1 ) {
                    echo $user_info->address_line_1 . "<br />";
                }

                if ( '' != $user_info->address_line_2 ) {
                    echo $user_info->address_line_2 . "<br />";
                }
                echo $user_info->city . ", " . $user_info->state_county . " " . $user_info->postcode_zip . "<br />";
                echo '<p>';
                if ( '' != $user_info->user_email ) {
                    echo "<a class='email-address' href='mailto:" . $user_info->user_email . "'>" . $user_info->user_email . "</a><br />";
                }

                if ( '' != $user_info->phone_number ) {
                    echo "<a class='phone-number' href='tel:" . $user_info->phone_number . "'>" . $user_info->phone_number . "</a>";
                }
                echo '</p>';
                echo '</div>';

                echo '<div class="patient-contact">';
                echo '<table class="wpd-ecommerce order-details"><tbody>';
                echo '<tr><td><strong>' . __( 'Subtotal', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_subtotal . '</td></tr>';
                if ( '0.00' !== $order_coupon_amount ) {
                    echo '<tr><td><strong>' . __( 'Coupon', 'wpd-ecommerce' ) . ':</strong></td><td>-' . CURRENCY . $order_coupon_amount . '</td></tr>';
                }
                if ( '0.00' !== $order_sales_tax ) {
                    echo '<tr><td><strong>' . __( 'Sales tax', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_sales_tax . '</td></tr>';
                }
                if ( '0.00' !== $order_excise_tax ) {
                    echo '<tr><td><strong>' . __( 'Excise tax', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_excise_tax . '</td></tr>';
                }
                if ( '0.00' !== $order_payment_type_amount ) {
                    echo "<tr><td><strong>" . $order_payment_type_name . ":</strong></td><td>" . CURRENCY . $order_payment_type_amount . "</td></tr>";
                }
                echo '<tr><td><strong>' . __( 'Total', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_total . '</td></tr>';
                echo '</tbody></table>';
                echo '</div>';
            ?>
        <?php
            if ( 'patient' === $role[0] ) {
                echo '<h3>' . __( 'Order items', 'wpd-ecommerce' ) . '</h3>';
                echo wpd_ecommerce_table_order_data( get_the_ID(), $user->ID );
            } elseif ( 'administrator' === $role[0] ) {
                echo wpd_ecommerce_table_order_data( get_the_ID(), $order_customer_id );
            } else {
                wp_redirect( wpd_ecommerce_account_url() );
            }
        ?>
        </div>
    </div>
    <?php endwhile; ?>
<?php do_action( 'wpd_ecommerce_templates_single_orders_wrap_after' ); ?>
<?php wp_reset_query(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php } ?>