<?php
/**
 * WP Dispensary eCommerce customer account shortcode
 *
 * @package    WPD_eCommerce
 * @subpackage WPD_eCommerce/customers
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @license    GPL-2.0+ 
 * @link       https://cannabizsoftware.com
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Customer Account Shortcode.
 *
 * @return string
 */
function wpd_customer_account_shortcode() {

    global $current_user, $wp_roles;

    echo wpd_ecommerce_notifications();

    // Display login/register forms
    if ( ! is_user_logged_in() ) {

        // Login form.
        wpd_ecommerce_login_form();

        // Registration form.
        if ( get_option( 'users_can_register' ) ) {
            wpd_ecommerce_register_form();
        }
    } else {
        ?>

        <div class="wpd-ecommerce customer-account">
            <?php
            // Variable.
            $tab_number = 1;
            // Create tabs.
            $account_tabs = array(
                esc_attr__( 'Dashboard', 'cannabiz-menu' ),
                esc_attr__( 'Orders', 'cannabiz-menu' ),
                esc_attr__( 'Details', 'cannabiz-menu' ),
            );

            $account_tabs = apply_filters( 'wpd_ecommerce_customer_account_shortcode_tabs', $account_tabs );

            // Loop through account tabs.
            foreach ( $account_tabs as $tab ) {
                // Output the tab.
                echo '<input class="account-links" id="tab' . $tab_number . '" type="radio" name="tabs" checked>
                <label class="account-links" for="tab' . $tab_number . '">' . esc_html__( $tab, 'cannabiz-menu' ) . '</label>';
                $tab_number++;
            }

            $section_number = 1;

            // Create sections.
            $account_sections = array(
                wpd_ecommerce_customer_account_shortcode_section1(),
                wpd_ecommerce_customer_account_shortcode_section2(),
                wpd_ecommerce_customer_account_shortcode_section3(),
            );

            // Create sections filter.
            $account_sections = apply_filters( 'wpd_ecommerce_customer_account_shortcode_sections', $account_sections );

            // Loop through account sections.
            foreach ( $account_sections as $section ) {
                echo '<section id="content' . $section_number . '">' . $section . '</section>';
                $section_number++;
            }
            ?>
        </div>
        <?php
    }
}
add_shortcode( 'wpd_account', 'wpd_customer_account_shortcode' );

/**
 * Account shortcode: Section 1
 * 
 * @return string
 */
function wpd_ecommerce_customer_account_shortcode_section1() {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    // Customer name based on specific profile info.
    if ( '' !== $user->first_name && '' !== $user->last_name ) {
        $customer_name = $user->first_name . '  ' . $user->last_name;
    } elseif ( '' !== $user->display_name ) {
        $customer_name = $user->display_name;
    } else {
        $customer_name = $user->user_nicename;
    }
    $string = '<p>' . esc_html__( 'Hello', 'cannabiz-menu' ) . ' <strong>' . $customer_name . '</strong> (<a href="' . wp_logout_url( get_permalink() ) . '">' . esc_html__( 'Log out', 'cannabiz-menu' ) . '</a>)</p>';

    // If user is administrator.
    if ( 'administrator' !== $role[0] ) {
        $string .= '<h3 class="wpd-ecommerce customer-title">' . esc_html__( 'Account dashboard', 'cannabiz-menu' ) . '</h3>
        <p>' . esc_html__( 'From your account dashboard you can view your order history and account details.', 'cannabiz-menu' ) . '</p>';
    }

    // If user is administrator.
    if ( 'administrator' === $role[0] ) {
        // Today's date.
        $today = getdate();

        // Daily orders.
        $date_query_daily = array(
            array(
                'year'  => $today['year'],
                'month' => $today['mon'],
                'day'   => $today['mday'],
            ),
        );

        // Yesterday's orders.
        $date_query_yesterday = date( 'd', strtotime( '-1 days' ) );

        // Weekly orders.
        $date_query_weekly = array(
            array(
                'year' => date( 'Y' ),
                'week' => date( 'W' ),
            ),
        );

        // WP_Query args.
        $args = array(
            'nopaging'    => true,
            'post_status' => 'publish',
            'post_type'   => 'wpd_orders',
            'order'       => 'ASC',
            'date_query'  => $date_query_weekly,
            'meta_query'  => array(
                array(
                    'key' => 'wpd_order_total_price',
                ),
            ),
        );
        $the_query = new WP_Query( $args );
        ?>

        <?php
        if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) : $the_query->the_post();
                // Add item's total price to the total_earnings array.
                $total_earnings[] = get_post_meta( get_the_ID(), 'wpd_order_total_price', true );
            endwhile;
            wp_reset_postdata();
        else : endif;

        // Get total order count.
        $order_count = $the_query->post_count;
        // Get total customer count.
        $customer_count = $users_count = count( get_users( array( 'fields' => array( 'ID' ), 'role' => 'customer' ) ) );

        if ( isset( $total_earnings ) ) {
            $total_final = array_sum( $total_earnings );
        } else {
            $total_final = '0';
        }

        $string .= '<div class="wpd-ecommerce account-administrator customers">' . $customer_count . '<span>' . apply_filters( 'wpd_ecommerce_account_admin_customers_text', 'Customers' ) . '</span></div>';
        $string .= '<div class="wpd-ecommerce account-administrator orders">' . $order_count . '<span>' . esc_attr__( 'Orders', 'cannabiz-menu' ) . '</span></div>';
        $string .= '<div class="wpd-ecommerce account-administrator earnings">' . CURRENCY . number_format( (float)$total_final, 2, '.', ',' ) . '<span>' . esc_attr__( 'This Week', 'cannabiz-menu' ) . '</span></div>';
        $string .= '<h3 class="wpd-ecommerce customer-title">' . esc_html__( 'Recent Store Orders', 'cannabiz-menu' ) . '</h3>
        <table class="wpd-ecommerce customer-orders">
            <thead>
                <td>' . esc_html__( 'ID', 'cannabiz-menu' ) . '</td>
                <td>' . esc_html__( 'Name', 'cannabiz-menu' ) . '</td>
                <td>' . esc_html__( 'Date', 'cannabiz-menu' ) . '</td>
                <td>' . esc_html__( 'Status', 'cannabiz-menu' ) . '</td>
                <td>' . esc_html__( 'Total', 'cannabiz-menu' ) .'</td>
            </thead>
            <tbody>';
                $user = wp_get_current_user();
                $args = array(
                    'post_type' => 'wpd_orders',
                );
                $the_query = new WP_Query( $args );
                // Create empty table_admin variable.
                $table_admin = '';
                ?>
            <?php if ( $the_query->have_posts() ) : ?>
        
                <!-- the loop -->
                <?php
                while ( $the_query->have_posts() ) : $the_query->the_post();

                    $total             = get_post_meta( get_the_ID(), 'wpd_order_total_price', true );
                    $status_names      = wpd_ecommerce_get_order_statuses();
                    $status            = get_post_meta( get_the_ID(), 'wpd_order_status', true );
                    $status_display    = wpd_ecommerce_order_statuses( get_the_ID(), null, null );
                    $order_customer_id = get_post_meta( get_the_ID(), 'wpd_order_customer_id', true );
                    $customer          = get_userdata( $order_customer_id );

                    if ( isset( $customer ) ) {
                        $customer_name = $customer->first_name . ' ' . $customer->last_name;
                    } else {
                        $customer_name = '';
                    }

                    if ( empty( $status ) ) {
                        $status = 'wpd-pending';
                    }

                    $table_admin .= '<tr>
                        <td><a href="' . get_the_permalink() . '">#' . get_the_ID() . '</a></td>
                        <td>' . $customer_name . '</td>
                        <td>' . get_the_date() . '<td>' . $status_display . '</td>
                        <td>' . CURRENCY . number_format( (float)$total, 2, '.', ',' ) . '</td>
                    </tr>';

                endwhile;
            endif;
            ?>
            <!-- end of the loop -->

            <?php wp_reset_postdata(); ?>

            <?php $string .= $table_admin . '
            </tbody>
        </table>';

    } // end if is administrator.

    return $string;
}

/**
 * Account shortcode: Section 2
 * 
 * @return string
 */
function wpd_ecommerce_customer_account_shortcode_section2() {
    $string = '<h3 class="wpd-ecommerce customer-title">' . esc_html__( 'Order history', 'cannabiz-menu' ) . '</h3>
    <table class="wpd-ecommerce customer-orders">
        <thead>
            <td>' . esc_html__( 'ID', 'cannabiz-menu' ) . '</td>
            <td>' . esc_html__( 'Date', 'cannabiz-menu' ) . '</td>
            <td>' . esc_html__( 'Status', 'cannabiz-menu' ) . '</td>
            <td>' . esc_html__( 'Total', 'cannabiz-menu' ) . '</td>
        </thead>
        <tbody>';

        $table = '';
        $user  = wp_get_current_user();
        $args  = array(
            'post_type'  => 'wpd_orders',
            'meta_query' => array(
                array(
                    'key'   => 'wpd_order_customer_id',
                    'value' => $user->ID,
                ),
            ),
        );
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) :

            while ( $the_query->have_posts() ) : $the_query->the_post();

                $total          = get_post_meta( get_the_ID(), 'wpd_order_total_price', true );
                $status_names   = wpd_ecommerce_get_order_statuses();
                $status         = get_post_meta( get_the_ID(), 'wpd_order_status', true );
                $status_display = wpd_ecommerce_order_statuses( get_the_ID(), null, null );

                if ( empty( $status ) ) {
                    $status = 'wpd-pending';
                }

                $table .= '<tr>
                    <td><a href="' . get_the_permalink() . '">#' . get_the_ID() . '</a></td>
                    <td>' . get_the_date() . '<td>' . $status_display . '</td>
                    <td>' . CURRENCY . number_format( (float)$total, 2, '.', ',' ) . '</td>
                </tr>';

            endwhile;
        endif;

        wp_reset_postdata();
        $string .= $table . '</tbody></table>';

        return $string;
}

/**
 * Account shortcode: Section 3
 * 
 * @return string
 */
function wpd_ecommerce_customer_account_shortcode_section3() {
    global $current_user;
    $string = do_action( 'wpd_ecommerce_customer_account_form_before' );

    $string .= '<form method="post" id="customers" class="wpd-ecommerce form customer-account" action="' . get_the_permalink() . '" enctype="multipart/form-data">' .

    do_action( 'wpd_ecommerce_customer_account_form_before_inside' ) . '

    <h3 class="wpd-ecommerce customer-title">' . esc_html__( 'Contact information', 'cannabiz-menu' ) . '</h3>' .

    do_action( 'wpd_ecommerce_customer_account_contact_information_before' ) . '

    <p class="form-row first form-first-name">
        <label for="first-name">' . esc_html__( 'First Name', 'cannabiz-menu' ) . '<span class="required">*</span></label>
        <input class="text-input" name="first-name" type="text" id="first-name" value="' . get_the_author_meta( 'first_name', $current_user->ID ) . '" />
    </p><!-- .form-first-name -->
    <p class="form-row last form-last-name">
        <label for="last-name">' . esc_html__( 'Last Name', 'cannabiz-menu' ) . '<span class="required">*</span></label>
        <input class="text-input" name="last-name" type="text" id="last-name" value="' . get_the_author_meta( 'last_name', $current_user->ID ) . '" />
    </p><!-- .form-last-name -->

    <p class="form-row form-email">
        <label for="email">' . esc_html__( 'E-mail', 'cannabiz-menu' ) . '<span class="required">*</span></label>
        <input class="text-input" name="email" type="text" id="email" value="' . get_the_author_meta( 'user_email', $current_user->ID ) . '" />
    </p><!-- .form-email -->

    <p class="form-row form-phone-number">
        <label for="email">' . esc_html__( 'Phone number', 'cannabiz-menu' ) . '</label>
        <input class="text-input" name="phone_number" type="text" id="phone_number" value="' . get_the_author_meta( 'phone_number', $current_user->ID ) . '" />
    </p><!-- .form-phone-number -->

    <p class="form-row form-address-line-1">
        <label for="email">' . esc_html__( 'Address line 1', 'cannabiz-menu' ) . '</label>
        <input class="text-input" name="address_line_1" type="text" id="address_line_1" value="' . get_the_author_meta( 'address_line_1', $current_user->ID ) . '" />
    </p><!-- .form-address-line-1 -->

    <p class="form-row form-address-line-2">
        <label for="email">' . esc_html__( 'Address line 2', 'cannabiz-menu' ) . '</label>
        <input class="text-input" name="address_line_2" type="text" id="address_line_2" value="' . get_the_author_meta( 'address_line_2', $current_user->ID ) . '" />
    </p><!-- .form-address-line-2 -->

    <p class="form-row form-city">
        <label for="email">' . esc_html__( 'City', 'cannabiz-menu' ) . '</label>
        <input class="text-input" name="city" type="text" id="city" value="' . get_the_author_meta( 'city', $current_user->ID ) . '" />
    </p><!-- .form-city -->

    <p class="form-row form-state-county">
        <label for="email">' . esc_html__( 'State / County', 'cannabiz-menu' ) . '</label>
        <input class="text-input" name="state-county" type="text" id="state_county" value="' . get_the_author_meta( 'state_county', $current_user->ID ) . '" />
    </p><!-- .form-state-county -->

    <p class="form-row form-postcode-zip">
        <label for="email">' . esc_html__( 'Postcode / ZIP', 'cannabiz-menu' ) . '</label>
        <input class="text-input" name="postcode_zip" type="text" id="postcode_zip" value="' . get_the_author_meta( 'postcode_zip', $current_user->ID ) . '" />
    </p><!-- .form-postcode-zip -->

    <p class="form-row form-country">
        <label for="email">' . esc_html__( 'Country', 'cannabiz-menu' ) . '</label>
        <select id="country" name="country" class="form-control">';
        // Current user's country.
        $current_user_country = get_the_author_meta( 'country', $current_user->ID );

        // Filter countries options.
        $options = apply_filters( 'wpd_ecommerce_customer_account_details_form_countries', wpd_ecommerce_countries_list() );

        // Create country select list.
        foreach ( $options as $value=>$name ) {
            if ( $name == $current_user_country ) {
                $string .= '<option selected="selected" value="' . $name . '">' . $name . '</option>';
            } else {
                $string .= '<option value="' . $name . '">' . $name . '</option>';
            }
        }
        $string .= '</select>
    </p><!-- .form-phone-country -->' .

    do_action( 'wpd_ecommerce_customer_account_contact_information_after' );

    $wpd_customers = get_option( 'wpdas_customers' );

    if ( empty( $wpd_customers ) ) {
        // Do nothing.
    } elseif (
        'on' == $wpd_customers['wpd_settings_customers_verification_drivers_license'] &&
        'on' == $wpd_customers['wpd_settings_customers_verification_recommendation_doc'] && 
        'on' == $wpd_customers['wpd_settings_customers_verification_recommendation_num'] && 
        'on' == $wpd_customers['wpd_settings_customers_verification_recommendation_exp'] ) {
    } else {
        $string .= '<h3 class="wpd-ecommerce customer-title">' . esc_html__( 'Verification', 'cannabiz-menu' ) . '</h3>' .

        do_action( 'wpd_ecommerce_customer_account_verification_before' );

        if ( 'on' == $wpd_customers['wpd_settings_customers_verification_drivers_license'] ) {
            // Do nothing.
        } else {

            $string .= '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_wpd_ecommerce_customer_valid_id">' . esc_html__( 'Drivers License or Valid ID', 'cannabiz-menu' ) . '</label>';

            if ( get_user_meta( $current_user->ID, 'wpd_ecommerce_customer_valid_id', true ) ) {
                $string .= '<div class="valid-id">';
                $valid_id = get_user_meta( $current_user->ID, 'wpd_ecommerce_customer_valid_id', true );
                if ( ! isset( $valid_id['error'] ) ) {
                    if ( ! empty( $valid_id ) ) {
                        $valid_id = $valid_id['url'];
                        $string .= '<a href="' . $valid_id . '" target="_blank"><img src="' . $valid_id . '" width="100" height="100" class="wpd-ecommerce-valid-id" /></a><br />';
                    }
                } else {
                    $valid_id = $valid_id['error'];
                    $string .= $valid_id. '<br />';
                }
                $string .= '<input type="submit" class="remove-valid-id" name="remove_valid_id" value="' . esc_html__( 'x', 'cannabiz-menu' ) .'" />
            </div><!-- /.valid-id -->';
            }
            $string .= '<input type="file" name="wpd_ecommerce_customer_valid_id" id="reg_wpd_ecommerce_customer_valid_id" value="" />
        </p>';

        }

        if ( 'on' == $wpd_customers['wpd_settings_customers_verification_recommendation_doc'] ) {
            // Do nothing.
        } else {

            $string .= '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_wpd_ecommerce_customer_recommendation_doc">' . esc_html__( 'Doctor recommendation', 'cannabiz-menu' ) . '</label>';
            if ( get_user_meta( $current_user->ID, 'wpd_ecommerce_customer_recommendation_doc', true ) ) {
                $string .= '<div class="recommendation-doc">';
                $doc_rec = get_user_meta( $current_user->ID, 'wpd_ecommerce_customer_recommendation_doc', true );
                if ( ! isset( $doc_rec['error'] ) ) {
                    if ( ! empty( $doc_rec ) ) {
                        $doc_rec = $doc_rec['url'];
                        $string .= '<a href="' . $doc_rec . '" target="_blank"><img src="' . $doc_rec . '" width="100" height="100" class="wpd-ecommerce-recommendation-doc" /></a><br />';
                    }
                } else {
                    $doc_rec = $doc_rec['error'];
                    $string .= $doc_rec . '<br />';
                }
                $string .= '<input type="submit" class="remove-recommendation-doc" name="remove_recommendation_doc" value="' . esc_html__( 'x', 'cannabiz-menu' ) . '" />
                </div><!-- /.recommendation-doc -->';
            }
            $string .= '<input type="file" name="wpd_ecommerce_customer_recommendation_doc" id="reg_wpd_ecommerce_customer_recommendation_doc" value="" />
        </p>';
        }

        if ( 'on' == $wpd_customers['wpd_settings_customers_verification_recommendation_num'] ) {
            // Do nothing.
        } else {

            $string .= '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_wpd_ecommerce_customer_recommendation_num">' . esc_html__( 'Recommendation number', 'cannabiz-menu' ) .'</label>
                <input type="text" class="input-text" name="wpd_ecommerce_customer_recommendation_num" id="reg_wpd_ecommerce_customer_recommendation_num" value="' . get_user_meta( $current_user->ID, 'wpd_ecommerce_customer_recommendation_num', true ) . '" />
            </p>';

        }

        if ( 'on' == $wpd_customers['wpd_settings_customers_verification_recommendation_exp'] ) {
            // Do nothing.
        } else {

            $string .= '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_wpd_ecommerce_customer_recommendation_exp">' . esc_html__( 'Expiration Date', 'cannabiz-menu' ) . '</label>
                <input type="date" class="input-date" name="wpd_ecommerce_customer_recommendation_exp" id="reg_wpd_ecommerce_customer_recommendation_exp" value="' . get_user_meta( $current_user->ID, 'wpd_ecommerce_customer_recommendation_exp', true ) . '" />
            </p>';

        } // recommendation_exp.

        $string .= do_action( 'wpd_ecommerce_customer_account_verification_after' );

    } // if all verifications are hidden.

    $string .= '<h3 class="wpd-ecommerce customer-title">' . esc_html__( 'Password change', 'cannabiz-menu' ) . '</h3>';

    $string .= do_action( 'wpd_ecommerce_customer_account_password_change_before' );

    $string .= '<p class="form-row form-first form-password">
        <label for="pass1">' . esc_html__( 'Password', 'cannabiz-menu' ) . '<span class="required">*</span> <em>' . esc_html__( 'Leave blank to keep unchanged', 'cannabiz-menu' ) . '</em></label>
        <input class="text-input" name="pass1" type="password" id="pass1" />
    </p><!-- .form-password -->
    <p class="form-row form-last form-password">
        <label for="pass2">' . esc_html__( 'Repeat Password', 'cannabiz-menu' ) .'<span class="required">*</span> <em>' . esc_html__( 'Leave blank to keep unchanged', 'cannabiz-menu' ) . '</em></label>
        <input class="text-input" name="pass2" type="password" id="pass2" />
    </p><!-- .form-password -->

    <p class="form-submit">
        <input name="updateuser" type="submit" id="updateuser" class="submit button" value=" ' . esc_html__( 'Update', 'cannabiz-menu' ) . '" />' .
        wp_nonce_field( 'update-user' ) . '
        <input name="action" type="hidden" id="action" value="update-user" />
    </p><!-- .form-submit -->';

    $string .= do_action( 'wpd_ecommerce_customer_account_form_inside_after' );

    $string .= '</form><!-- #customers -->';

    $string .= do_action( 'wpd_ecommerce_customer_account_form_after' );

    return $string;
}
