<?php
/*
Copy this file into your theme to customize
*/
get_header(); ?>
<?php

do_action( 'wpd_ecommerce_templates_single_orders_wrap_before' );

// Get the order details.
$order_details = wpd_ecommerce_get_order_details( get_the_ID() );
?>
    <?php while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="item_name"><?php the_title(); ?><?php echo $order_details['status']['status_display']; ?></h1>
            <?php
                // Include notifications.
                echo wpd_ecommerce_notifications();
            ?>
        </header>
        <div class="entry-content order-details">
            <?php
                // Get user info.
                $user_info = get_userdata( $order_details['customer_id'] );

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
                    echo $user_info->address_line_1 . '<br />';
                }

                if ( '' != $user_info->address_line_2 ) {
                    echo $user_info->address_line_2 . '<br />';
                }
                echo $user_info->city . ', ' . $user_info->state_county . ' ' . $user_info->postcode_zip . '<br />';
                echo '<p>';
                if ( '' != $user_info->user_email ) {
                    echo '<a class="email-address" href="mailto:' . $user_info->user_email . '">' . $user_info->user_email . '</a><br />';
                }

                if ( '' != $user_info->phone_number ) {
                    echo '<a class="phone-number" href="tel:' . $user_info->phone_number . '">' . $user_info->phone_number . '</a>';
                }
                echo '</p>';
                echo '</div>';

                echo '<div class="patient-contact">';
                echo '<table class="wpd-ecommerce order-details"><tbody>';
                echo '<tr><td><strong>' . __( 'Subtotal', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_details['subtotal'] . '</td></tr>';
                if ( '0.00' !== $order_details['coupon_amount'] ) {
                    echo '<tr><td><strong>' . __( 'Coupon', 'wpd-ecommerce' ) . ':</strong></td><td>-' . CURRENCY . $order_details['coupon_amount'] . '</td></tr>';
                }
                if ( '0.00' !== $order_details['sales_tax'] ) {
                    echo '<tr><td><strong>' . __( 'Sales tax', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_details['sales_tax'] . '</td></tr>';
                }
                if ( '0.00' !== $order_details['excise_tax'] ) {
                    echo '<tr><td><strong>' . __( 'Excise tax', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_details['excise_tax'] . '</td></tr>';
                }
                if ( '0.00' !== $order_details['payment_type']['amount'] ) {
                    echo '<tr><td><strong>' . $order_details['payment_type']['name'] . ':</strong></td><td>' . CURRENCY . $order_details['payment_type']['amount'] . '</td></tr>';
                }
                echo '<tr><td><strong>' . __( 'Total', 'wpd-ecommerce' ) . ':</strong></td><td>' . CURRENCY . $order_details['total'] . '</td></tr>';
                echo '</tbody></table>';
                echo '</div>';
            ?>
        <?php
            $user = wp_get_current_user();
            $role = ( array ) $user->roles;

            echo '<h3>' . __( 'Order items', 'wpd-ecommerce' ) . '</h3>';
            if ( 'patient' === $role[0] ) {
                echo wpd_ecommerce_table_order_data( get_the_ID(), $user->ID );
            } elseif ( 'administrator' === $role[0] ) {
                echo wpd_ecommerce_table_order_data( get_the_ID(), $order_details['customer_id'] );
            } else {
                wp_redirect( wpd_ecommerce_account_url() );
            }
        ?>
        </div>
    </div>
    <?php endwhile; ?>
<?php

do_action( 'wpd_ecommerce_templates_single_orders_wrap_after' );

wp_reset_query();

get_sidebar();
get_footer();
