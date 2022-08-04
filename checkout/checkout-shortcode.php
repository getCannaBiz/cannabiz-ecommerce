<?php
/**
 * WP Dispensary eCommerce checkout shortcode
 *
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Checkout Shortcode.
 * 
 * @return string
 */
function wpd_ecommerce_checkout_shortcode() {

    // Register button.
    $register_button = '';

    if ( ! is_user_logged_in() && 'on' == wpd_ecommerce_require_login_to_shop() ) {
        if ( get_option( 'users_can_register' ) ) {
            // Register button.
            $register_button = ' <a href="' . apply_filters( 'wpd_ecommerce_login_to_checkout_register_button_url', wpd_ecommerce_account_url() ) . '" class="button wpd-ecommerce register">' . esc_attr__( 'Login', 'wpd-ecommerce' ) . '</a>';
        }
        echo '<p>' . esc_attr__( 'You must be logged in to checkout.', 'wpd-ecommerce' ) . '</p>';
        echo '<p><a href="' . wpd_ecommerce_account_url() . '" class="button wpd-ecommerce login">' . esc_attr__( 'Login', 'wpd-ecommerce' ) . '</a>' . $register_button . '</p>';
    } else {
        // Verify that there's an active session.
        if ( isset( $_SESSION['wpd_ecommerce'] ) ) {

            // Include notifications.
            echo wpd_ecommerce_notifications();

            global $current_user, $wp_roles;

            $error = array();

            /* If checkout is submitted, do something specific . */
            if ( 'POST' == filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) && null !== filter_input( INPUT_POST, 'action' ) && filter_input( INPUT_POST, 'action' ) == 'wpd-ecommerce-checkout' ) {

                /* Update Email Address */
                if ( null !== filter_input( INPUT_POST, 'email' ) ) {
                    if ( ! is_email( filter_input( INPUT_POST, 'email' ) ) )
                        $error[] = esc_attr__( 'The Email you entered is not valid. Please try again.', 'wpd-ecommerce' );
                    elseif( email_exists( filter_input( INPUT_POST, 'email' ) ) != $current_user->ID )
                        $error[] = esc_attr__( 'This email is already used by another user. Try a different one.', 'wpd-ecommerce' );
                    else {
                        wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => filter_input( INPUT_POST, 'email' ) ) );
                    }
                }

                // Update First Name.
                if ( null !== filter_input( INPUT_POST, 'first-name' ) ) {
                    update_user_meta( $current_user->ID, 'first_name', esc_attr( filter_input( INPUT_POST, 'first-name' ) ) );
                }
                // Update Last Name.
                if ( null !== filter_input( INPUT_POST, 'last-name' ) ) {
                    update_user_meta( $current_user->ID, 'last_name', esc_attr( filter_input( INPUT_POST, 'last-name' ) ) );
                }
                // Update Phone Number.
                if ( null !== filter_input( INPUT_POST, 'phone_number' ) ) {
                    update_user_meta( $current_user->ID, 'phone_number', esc_attr( filter_input( INPUT_POST, 'phone_number' ) ) );
                }
                // Update Address Line 1.
                if ( null !== filter_input( INPUT_POST, 'address_line_1' ) ) {
                    update_user_meta( $current_user->ID, 'address_line_1', esc_attr( filter_input( INPUT_POST, 'address_line_1' ) ) );
                }
                // Update Address Line 2.
                if ( null !== filter_input( INPUT_POST, 'address_line_2' ) ) {
                    update_user_meta( $current_user->ID, 'address_line_2', esc_attr( filter_input( INPUT_POST, 'address_line_2' ) ) );
                }
                // Update City.
                if ( null !== filter_input( INPUT_POST, 'city' ) ) {
                    update_user_meta( $current_user->ID, 'city', esc_attr( filter_input( INPUT_POST, 'city' ) ) );
                }
                // Update State/County.
                if ( null !== filter_input( INPUT_POST, 'state_country' ) ) {
                    update_user_meta( $current_user->ID, 'state_county', esc_attr( filter_input( INPUT_POST, 'state_county' ) ) );
                }
                // Update Postcode/Zip.
                if ( null !== filter_input( INPUT_POST, 'postcode_zip' ) ) {
                    update_user_meta( $current_user->ID, 'postcode_zip', esc_attr( filter_input( INPUT_POST, 'postcode_zip' ) ) );
                }
                // Update Country.
                if ( null !== filter_input( INPUT_POST, 'country' ) ) {
                    update_user_meta( $current_user->ID, 'country', esc_attr( filter_input( INPUT_POST, 'country' ) ) );
                }

                /**
                 * Redirect so the page will show updated info.
                 */
                if ( 0 == count( $error ) ) {
                    //action hook for plugins and extra fields saving
                    do_action( 'edit_user_profile_update', $current_user->ID );
                }

                // Minimum checkout check.
                if ( '' !== wpd_ecommerce_checkout_minimum_order() ) {
                    if ( $_SESSION['wpd_ecommerce']->sum >= wpd_ecommerce_checkout_minimum_order() ) {
                        if ( null == filter_input( INPUT_POST, 'payment-type' ) ) {
                            $str = '<div class="wpd-ecommerce-notifications failed"><strong>' . esc_attr__( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . esc_attr__( 'Please select a payment type.', 'wpd-ecommerce' ) . '</div>';
                            echo $str;
                        } else {
                            wpd_ecommerce_checkout_success();
                        }
                    } else {
                        $str = '<div class="wpd-ecommerce-notifications failed"><strong>' . esc_attr__( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . esc_attr__( 'The minimum order amount required to checkout is', 'wpd-ecommerce' ) . ' ' . wpd_currency_code() . wpd_ecommerce_checkout_minimum_order() . '</div>';
                        echo $str;
                    }
                } else {
                    if ( filter_input( INPUT_POST, 'payment-type' ) ) {
                        $str = '<div class="wpd-ecommerce-notifications failed"><strong>' . esc_attr__( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . esc_attr__( 'Please select a payment type.', 'wpd-ecommerce' ) . '</div>';
                        echo $str;
                    } else {
                        wpd_ecommerce_checkout_success();
                    }
                }
            }
            ?>

            <?php do_action( 'wpd_ecommerce_checkout_billing_details_form_before' ); ?>

            <form method="post" id="checkout" class="wpd-ecommerce form checkout" action="<?php the_permalink(); ?>">

            <?php do_action( 'wpd_ecommerce_checkout_billing_details_form_inside_before' ); ?>

            <h3 class='wpd-ecommerce customer-title'><?php esc_html_e( 'Billing details', 'wpd-ecommerce' ); ?></h3>

            <?php do_action( 'wpd_ecommerce_checkout_billing_details_form_after_billing_details_title' ); ?>

            <p class="form-row first form-first-name">
                <label for="first-name"><?php esc_html_e( 'First Name', 'wpd-ecommerce' ); ?>
                    <span class="required">*</span>
                </label>
                <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
            </p><!-- .form-first-name -->
            <p class="form-row last form-last-name">
                <label for="last-name"><?php esc_html_e( 'Last Name', 'wpd-ecommerce' ); ?>
                    <span class="required">*</span>
                </label>
                <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
            </p><!-- .form-last-name -->

            <p class="form-row form-email">
                <label for="email"><?php esc_html_e( 'E-mail', 'wpd-ecommerce' ); ?>
                    <span class="required">*</span>
                </label>
                <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
            </p><!-- .form-email -->

            <p class="form-row form-phone-number">
                <label for="phone-number"><?php esc_html_e( 'Phone number', 'wpd-ecommerce' ); ?></label>
                <input class="text-input" name="phone_number" type="text" id="phone_number" value="<?php the_author_meta( 'phone_number', $current_user->ID ); ?>" />
            </p><!-- .form-phone-number -->

            <p class="form-row form-address-line">
                <label for="address-line"><?php esc_html_e( 'Street address', 'wpd-ecommerce' ); ?></label>
                <input class="text-input" name="address_line_1" type="text" id="address_line_1" value="<?php the_author_meta( 'address_line_1', $current_user->ID ); ?>" placeholder="<?php esc_html_e( 'House number and street name', 'wpd-ecommerce' ); ?>" />
                <input class="text-input" name="address_line_2" type="text" id="address_line_2" value="<?php the_author_meta( 'address_line_2', $current_user->ID ); ?>" placeholder="<?php esc_html_e( 'Apartment, unit, etc. (optional)', 'wpd-ecommerce' ); ?>" />
            </p><!-- .form-address-line -->

            <p class="form-row form-city">
                <label for="city"><?php esc_html_e( 'City', 'wpd-ecommerce' ); ?></label>
                <input class="text-input" name="city" type="text" id="city" value="<?php the_author_meta( 'city', $current_user->ID ); ?>" />
            </p><!-- .form-city -->

            <p class="form-row form-state-county">
                <label for="state-county"><?php esc_html_e( 'State / County', 'wpd-ecommerce' ); ?></label>
                <input class="text-input" name="state_county" type="text" id="state_county" value="<?php the_author_meta( 'state_county', $current_user->ID ); ?>" />
            </p><!-- .form-state-county -->

            <p class="form-row form-postcode-zip">
                <label for="email"><?php esc_html_e( 'Postcode / ZIP', 'wpd-ecommerce' ); ?></label>
                <input class="text-input" name="postcode_zip" type="text" id="postcode_zip" value="<?php the_author_meta( 'postcode_zip', $current_user->ID ); ?>" />
            </p><!-- .form-postcode-zip -->

            <p class="form-row form-country">
                <label for="email"><?php esc_html_e( 'Country', 'wpd-ecommerce' ); ?></label>
                <select id="country" name="country" class="form-control">
                    <?php
                    // Current user's country.
                    $current_user_country = get_the_author_meta( 'country', $current_user->ID );

                    // Countries output as select fields.
                    $options = array( 'Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegowina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, the Democratic Republic of the', 'Cook Islands', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France Metropolitan', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard and Mc Donald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran (Islamic Republic of)', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao, People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia, The Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia (Slovak Republic)', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'Spain', 'Sri Lanka', 'St. Helena', 'St. Pierre and Miquelon', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen Islands', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan, Province of China', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela', 'Vietnam', 'Virgin Islands (British)', 'Virgin Islands (U.S.)', 'Wallis and Futuna Islands', 'Western Sahara', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe' );

                    // Filter countries options.
                    $options = apply_filters( 'wpd_ecommerce_checkout_billing_details_form_countries', $options );

                    // Create country select list.
                    foreach ( $options as $value=>$name ) {
                        if ( $name == $current_user_country ) {
                            echo '<option selected="selected" value="' . $name . '">' . $name . '</option>';
                        } else {
                            echo '<option value="' . $name . '">' . $name . '</option>';
                        }
                    }
                    ?>
                </select>
            </p><!-- .form-phone-country -->

            <?php do_action( 'wpd_ecommerce_checkout_billing_details_form_after_billing_details' ); ?>

            <h3 class='wpd-ecommerce customer-order'><?php esc_html_e( 'Your order', 'wpd-ecommerce' ); ?></h3>

            <?php do_action( 'wpd_ecommerce_checkout_billing_details_form_after_your_order_title' ); ?>

            <?php
            $str  = '<table class="wpd-ecommerce widget checkout">';
            $str .= '<thead>';
            $str .= '<tr><td>' . esc_attr__( 'Product', 'wpd-ecommerce' ) . '</td><td>' . esc_attr__( 'Total', 'wpd-ecommerce' ) . '</td></tr>';
            $str .= '</thead>';
            $str .= '<tbody>';

            foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
                $i             = new Item( $id, '', '', '' );
                $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
                $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

                if ( in_array( get_post_meta( $item_old_id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {

                    $units_per_pack = esc_html( get_post_meta( $item_old_id, 'units_per_pack', true ) );

                    $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
                    $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

                    if ( 'price_per_pack' === $item_meta_key ) {
                        $regular_price = esc_html( get_post_meta( $item_old_id, 'price_per_pack', true ) );
                        $weight_name   = $units_per_pack . ' pack';
                    } else {
                        $regular_price = esc_html( get_post_meta( $item_old_id, 'price_each', true ) );
                        $weight_name = '';
                    }

                } elseif ( 'flowers' === get_post_meta( $item_old_id, 'product_type', true ) ) {
                    $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
                    $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                    foreach ( wpd_flowers_weights_array() as $key=>$value ) {
                        if ( $value == $flower_weight_cart ) {
                            $weight_name   = ' - ' . $key;
                            $regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
                        }
                    }

                } elseif ( 'concentrates' === get_post_meta( $item_old_id, 'product_type', true ) ) {
                    $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
                    $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                    foreach ( wpd_concentrates_weights_array() as $key=>$value ) {
                        if ( $value == $concentrate_weight_cart ) {
                            $weight_name   = ' - ' . $key;
                            $regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
                        }
                    }
                    if ( 'price_each' === $concentrate_weight_cart ) {
                        $weight_name   = '';
                        $regular_price = esc_html( get_post_meta( $item_old_id, 'price_each', true ) );
                    }
                } else {
                    // Do nothing.
                }

                // print_r( $i );

                $total_price = $amount * $regular_price;

                $str .=    '<tr><td>' . $i->thumbnail . '<a href="' . $i->permalink . '" class="wpd-ecommerce-widget title">' . $i->title . $weight_name . '</a> x <strong>' . $amount . '</strong></td><td><span class="wpd-ecommerce-widget amount">' . CURRENCY . number_format( $total_price, 2, '.', ',' ) . '</span></td></tr>';

            endforeach;

            // Get taxes (if any).
            $wpd_sales_tax  = number_format( (float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' );
            $wpd_excise_tax = number_format( (float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' );

            // Set total price.
            $total_price = ( str_replace( ',', '', $wpd_sales_tax ) + str_replace( ',', '', $wpd_excise_tax ) + number_format( (float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) + $_SESSION['wpd_ecommerce']->sum );

            // Reduce coupon cost from total price.
            if ( $_SESSION['wpd_ecommerce']->coupon_amount ) {
                $total_price = $total_price - number_format( (float)$_SESSION['wpd_ecommerce']->coupon_amount, 2, '.', ',' );
            }

            // Subtotal.
            $str .= '<tr><td><strong>' . esc_attr__( 'Subtotal', 'wpd-ecommerce' ) . '</strong></td><td>' . CURRENCY . number_format( (float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . '</td></tr>';
            // Coupon code.
            if ( 0 !== $_SESSION['wpd_ecommerce']->coupon_code ) {
                $str .= '<tr><td><strong>' . esc_attr__( 'Coupon', 'wpd-ecommerce' ) . ':<br />' . $_SESSION['wpd_ecommerce']->coupon_code . '</strong></td><td>-' . CURRENCY . number_format( (float)$_SESSION['wpd_ecommerce']->coupon_amount, 2, '.', ',' ) . " (<a href='" . get_the_permalink() . "?remove_coupon=". $_SESSION['wpd_ecommerce']->coupon_code . "'>" . esc_attr__( 'Remove', 'wpd-ecommerce' ) . "?</a>)</td></tr>";
            }
            // Sales tax.
            if ( null !== $wpd_sales_tax && '0.00' !== $wpd_sales_tax ) {
                $str .= '<tr><td><strong>' . esc_attr__( 'Sales tax', 'wpd-ecommerce' ) . '</strong></td><td>' . CURRENCY . number_format( (float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) . '</td></tr>';
            }
            // Excise tax.
            if ( null !== $wpd_sales_tax && '0.00' !== $wpd_excise_tax ) {
                $str .= '<tr><td><strong>' . esc_attr__( 'Excise tax', 'wpd-ecommerce' ) . '</strong></td><td>' . CURRENCY . number_format( (float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) . '</td></tr>';
            }

            // Payment type.
            foreach ( wpd_ecommerce_payment_types() as $name => $value ) {
                $str .= '<tr class="payment-type"><td><input type="radio" name="payment-type" id="' . $name . '" value="' . $value . '"/> <strong>' . $name . '</strong></td><td>' . CURRENCY . number_format((float)$value, 2, '.', ',' ) . '</td></tr>';
            }

            // Total.
            $str .= '<tr class="total"><td><strong>' . esc_attr__( 'Total', 'wpd-ecommerce' ) . '</strong></td><td class="total-price"><span class="total-price">' . CURRENCY . number_format( $total_price, 2, '.', ',' ) . '</span></td></tr>';

            $str .= '</tbody>';
            $str .= '</table>';

            echo $str;

            do_action( 'wpd_ecommerce_checkout_after_order_details' );

            $str2  = '<p class="form-submit"><input name="checkout-submit" type="submit" id="checkoutsubmit" class="submit button" value="' . esc_attr__( 'Place Order', 'wpd-ecommerce' ) . '" />' . wp_nonce_field( 'wpd-ecommerce-checkout' ) . '<input name="action" type="hidden" id="action" value="wpd-ecommerce-checkout" /></p>';
            $str2 .= '</form>';

            echo $str2;

        } else {
            echo '<p>' . esc_attr__( 'You can check out after adding some products to your cart', 'wpd-ecommerce' ) . '</p>';
            echo '<p><a href="' . apply_filters( 'wpd_ecommerce_checkout_return_to_menu_url', wpd_ecommerce_menu_url() ) . '" class="button wpd-ecommerce return">' . apply_filters( 'wpd_ecommerce_checkout_return_to_menu_text', esc_attr__( 'Return to menu', 'wpd-ecommerce' ) ) . '</a></p>';
        }
    } // is user logged in
}
add_shortcode( 'wpd_checkout', 'wpd_ecommerce_checkout_shortcode' );

/**
 * Fire this off when the order is a success.
 * 
 * @return string
 */
function wpd_ecommerce_checkout_success() {

    do_action( 'wpd_ecommerce_checkout_success_before' );

    $page_date    = date( 'Y-m-d H:i:s' );
    $current_user = wp_get_current_user();

    $customer_details  = '';
    $customer_details .= '<p><strong>' . esc_attr__( 'Name', 'wpd-ecommerce' ) . ':</strong> ' . $current_user->first_name . ' ' . $current_user->last_name . '</p>';
    $customer_details .= '<p><strong>' . esc_attr__( 'Email', 'wpd-ecommerce' ) . ':</strong> ' . $current_user->user_email . '</p>';

    $customer_id = $current_user->ID;

    // Order database variables.
    $wpd_orders_data      = array();
    $wpd_orders_item_data = array();

    echo '<h3 class="wpd-ecommerce customer-order">' . esc_attr__( 'Your order', 'wpd-ecommerce' ) . '</h3>';

    do_action( 'wpd_ecommerce_checkout_success_your_order_table_before' );

    $str  = '';
    $str  = '<table style="border-collapse: collapse;width: 100%;max-width: 600px;margin: 0 auto;" class="wpd-ecommerce widget checkout">';
    $str .= '<thead style="border: 1px solid #DDD;">';
    $str .= '<tr style="font-weight: 700;"><td style="text-align: left; padding: 10px;">' . esc_attr__( 'Product', 'wpd-ecommerce' ) . '</td><td style="text-align: left;">' . esc_attr__( 'Total', 'wpd-ecommerce' ) . '</td></tr>';
    $str .= '</thead>';
    $str .= '<tbody style="border-bottom: 1px solid #DDD;">';

    /**
     * Loop through each item in the cart
     */
    foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
        $i             = new Item( $id, '', '', '' );
        $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
        $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

        if ( in_array( get_post_meta( $item_old_id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {

            $units_per_pack = esc_html( get_post_meta( $item_old_id, 'units_per_pack', true ) );

            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

            if ( 'price_per_pack' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, 'price_per_pack', true ) );
                $weight_name   = $units_per_pack . ' pack';
            } else {
                $regular_price = esc_html( get_post_meta( $item_old_id, 'price_each', true ) );
                $weight_name   = '';
            }

        } elseif ( 'flowers' === get_post_meta( $item_old_id, 'product_type', true ) ) {
            $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
            $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

            foreach ( wpd_flowers_weights_array() as $key=>$value ) {
                if ( $value == $flower_weight_cart ) {
                    $weight_name   = $key;
                    $regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
                }
            }

        } elseif ( 'concentrates' === get_post_meta( $item_old_id, 'product_type', true ) ) {
            $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
            $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

            foreach ( wpd_concentrates_weights_array() as $key=>$value ) {
                if ( $value == $concentrate_weight_cart ) {
                    $weight_name   = $key;
                    $regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
                }
            }
            if ( 'price_each' === $concentrate_weight_cart ) {
                $weight_name   = '';
                $regular_price = esc_html( get_post_meta( $item_old_id, 'price_each', true ) );
            }
        } else {
            // Do nothing.
        }

        // print_r( $i );

        // Total price.
        $total_price = $amount * $regular_price;

        // Order item name.
        $order_item_name = $i->title . ' - ' . $weight_name;

        // Add order details to array.
        $wpd_orders_data[] = array(
            $i->id => $order_item_name
        );

        // Get cart item data.
        $orders_meta_insert[] = array(
            'order_item_id'        => $i->id,
            'order_item_name'      => $i->title,
            'item_id'              => $item_old_id,
            'item_url'             => $i->permalink,
            'item_image_url'       => get_the_post_thumbnail_url( $i->id, 'full' ),
            'item_image_url_thumb' => get_the_post_thumbnail_url( $i->id, 'thumbnail' ),
            'item_variation'       => $item_meta_key,
            'item_variation_name'  => $weight_name,
            'quantity'             => $amount,
            'single_price'         => $regular_price,
            'total_price'          => $total_price
        );

        // Add item quantity to array.
        $total_items[] = $amount;

        $str .= '<tr style="border-bottom: 1px solid #DDD; border-left: 1px solid #DDD; border-right: 1px solid #DDD;"><td style="padding: 12px 12px; vertical-align: middle;">' . $i->thumbnail . '<a href="' . $i->permalink . '" class="wpd-ecommerce-widget title">' . $i->title . $weight_name . '</a> x <strong>' . $amount . '</strong></td><td style="padding: 12px 12px; vertical-align: middle;"><span class="wpd-ecommerce-widget amount">' . CURRENCY . number_format( $total_price, 2, '.', ',' ) . '</span></td></tr>';

    endforeach;

    $str .= '</tbody>';
    $str .= '</table>';

    do_action( 'wpd_ecommerce_checkout_success_your_order_table_after' );

    // Total price.
    $total_price = ( number_format( (float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) + number_format( (float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) + number_format( (float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) + $_SESSION['wpd_ecommerce']->sum );

    // Coupon total.
    $coupon_total = $_SESSION['wpd_ecommerce']->coupon_amount;

    // Reduce coupon cost from total price.
    if ( $_SESSION['wpd_ecommerce']->coupon_amount ) {
        $total_price = $total_price - number_format( (float)$_SESSION['wpd_ecommerce']->coupon_amount, 2, '.', ',' );
    }

    // Create orders array.
    $orders_insert   = array();
    $orders_insert[] = array(
        'order_subtotal'            => number_format( (float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ),
        'order_coupon_code'         => $_SESSION['wpd_ecommerce']->coupon_code,
        'order_coupon_amount'       => number_format( (float)$coupon_total, 2, '.', ',' ),
        'order_payment_type_name'   => $_SESSION['wpd_ecommerce']->payment_type_name,
        'order_payment_type_amount' => number_format( (float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ),
        'order_sales_tax'           => number_format( (float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ),
        'order_excise_tax'          => number_format( (float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ),
        'order_total'               => number_format( (float)$total_price, 2, '.', ',' ),
        'order_items'               => array_sum( $total_items )
    );

    /**
     * Create new ORDER in WordPress
     */
    $wpd_order = array(
        'post_type'   => 'wpd_orders',
        'post_status' => 'publish',
        'post_author' => 1,
        'date'        => $page_date, // YYYY-MM-DDTHH:MM:SS
        'meta_input'  => array(
            'wpd_order_customer_details' => $customer_details,
            'wpd_order_customer_id'      => $customer_id,
            'wpd_order_status'           => 'wpd-processing',
            'wpd_order_total_price'      => number_format( (float)$total_price, 2, '.', ',' ),
            'wpd_order_sales_tax'        => number_format( (float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ),
            'wpd_order_excise_tax'       => number_format( (float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ),
            'wpd_order_subtotal_price'   => number_format( (float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ),
            'wpd_order_items'            => array_sum( $total_items )
        ),
    );

    // Insert the order into WordPress.
    $wpd_order_id = wp_insert_post( $wpd_order );

    global $wpdb;

    /**
     * Insert order details into wpd_orders table.
     *
     * @since 1.0.0
     */

    // Get orders data.
    $orders_data    = array_values( $wpd_orders_data );
    $orders_details = array_values( $orders_insert );

    $od = -1;

    // loop through cart.
    foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
        $od++;

        // Loop through order items.
        foreach ( $orders_data[$od] as $id=>$name ) {
            // Insert data into database.
            $wpdb->insert( $wpdb->prefix . 'wpd_orders', array(
                'order_id'    => $wpd_order_id,
                'order_type'  => 'product',
                'order_key'   => $id,
                'order_value' => $name,
            ) );
        }

    endforeach;

    // Get order details.
    foreach ( $orders_details[0] as $id=>$name ) {
        $wpdb->insert( $wpdb->prefix . 'wpd_orders', array(
            'order_id'    => $wpd_order_id,
            'order_type'  => 'details',
            'order_key'   => $id,
            'order_value' => $name,
        ) );
    }

    // Get row's from database with current $wpd_order_id.
    $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$wpd_order_id} AND order_type = 'product'", ARRAY_A );

    $od++;

    /**
     * Add order meta to each order item in database
     */
    $i = -1;

    // Loop through each product in the database.
    foreach( $get_order_data as $order_value ) {
        $i++;
        $order_id_key = $order_value['item_id'];
        $array        = array_values( $orders_meta_insert );

        // Get key/value of each array result.
        foreach( $array[$i] as $key => $value ) {
            // Does this 4 times.
            $wpdb->insert( $wpdb->prefix . 'wpd_orders_meta', array(
                'item_id'    => $order_id_key,
                'meta_key'   => $key,
                'meta_value' => $value,
            ));
        }
    
    }

    /**
     * Inventory updates
     * 
     * @since 1.0
     */
    wpd_ecommerce_inventory_management_updates( $wpd_order_id );

    // This updates the new order with custom title, etc.
    $updated_post = array(
        'ID'            => $wpd_order_id,
        'post_title'    => 'Order #' . $wpd_order_id,
        'post_status'   => 'publish', // Now it's public
        'post_type'     => 'wpd_orders'
    );
    wp_update_post( $updated_post );

    /**
     * @todo set up options for SMS (twilio) to be sent instead of email.
     */

    /**
     * Email order details to Administrator.
     * 
     * @since 1.0
     */
    $order             = $wpd_order_id;
    $order_customer_id = get_post_meta( $wpd_order_id, 'wpd_order_customer_id', true );
    $user_info         = get_userdata( $order_customer_id );
    $to                = get_option( 'admin_email' );
    $subject           = esc_attr__( 'New order: #', 'wpd-ecommerce' ) . $order;

    // Create message for the email.
    $message  = '<p>' . esc_attr__( 'Hello Administrator', 'wpd-ecommerce' ) . ',</p>';
    $message .= '<p>' . sprintf( esc_attr__( '#%1$s just received a new order from #%2$s #%3$s', 'wpd-ecommerce' ), bloginfo( 'name' ), $user_info->first_name, $user_info->last_name ) . '</p>';

    // Add order details to message.
    $message .= $str;

    // Create headers for the email.
    $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';

    wp_mail( apply_filters( 'wpd_ecommerce_checkout_email_to_admin', $to ), apply_filters( 'wpd_ecommerce_checkout_email_subject_admin', $subject ), apply_filters( 'wpd_ecommerce_checkout_email_message_admin', $message ), apply_filters( 'wpd_ecommerce_checkout_email_headers_admin', $headers ), '' );

    /**
     * Email order details to Customer.
     * 
     * @since 1.0
     */
    $order_customer_id = get_post_meta( $wpd_order_id, 'wpd_order_customer_id', true );
    $user_info         = get_userdata( $order_customer_id );
    $to_customer       = $user_info->user_email;
    $subject_customer  = esc_attr__( 'Thank you for your order', 'wpd-ecommerce' ) . ': #' . $wpd_order_id;

    // Create message for the email.
    $message  = '<p>Hello ' . $user_info->first_name . ',</p>';
    $message .= '<p>' . esc_attr__( 'Thank you for your order. You can see details of your order below as well as in your account on our website.', 'wpd-ecommerce' ) . '</p>';
    $message .= '<p>- ' . get_bloginfo( 'name' ) . '<br />' . get_bloginfo( 'url' ) . '</p>';

    // Add order details to message.
    $message .= $str;

    // Create headers for the email.
    $headers_customer[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>';
    $headers_customer[] = 'Content-Type: text/html';
    $headers_customer[] = 'charset=UTF-8';

    wp_mail( $to_customer, apply_filters( 'wpd_ecommerce_checkout_email_subject_customer', $subject_customer ), apply_filters( 'wpd_ecommerce_checkout_email_message_customer', $message ), apply_filters( 'wpd_ecommerce_checkout_email_headers_customer', $headers_customer ), '' );

    do_action( 'wpd_ecommerce_checkout_success_after', $wpd_order_id );

    /**
     * Destroy session
     * 
     * @since 1.0
     */
    wpd_ecommerce_destroy_session();

    // Redirect to the order page.
    wp_safe_redirect( get_bloginfo( 'url' ) . '/order/' . $wpd_order_id . '?order=thank-you' );

    exit;
}
