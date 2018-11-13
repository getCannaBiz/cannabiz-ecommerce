<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_shortcode() {

    // Verify that there's an active session.
	if ( ! empty( $_SESSION['wpd_ecommerce'] ) ) {
        global $current_user, $wp_roles;

        $error = array();

        /* If checkout is submitted, do something specific . */
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'wpd-ecommerce-checkout' ) {

            /** Update Email Address */
            if ( ! empty( $_POST['email'] ) ) {
                if ( ! is_email( esc_attr( $_POST['email'] ) ) )
                    $error[] = __( 'The Email you entered is not valid. Please try again.', 'wpd-ecommerce' );
                elseif( email_exists( esc_attr( $_POST['email'] ) ) != $current_user->ID )
                    $error[] = __( 'This email is already used by another user. Try a different one.', 'wpd-ecommerce' );
                else {
                    wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] ) ) );
                }
            }

            /**
             * @todo add other customer contact info here, like phone_number, address_line_1, etc.
             */

            /** Update First Name */
            if ( ! empty( $_POST['first-name'] ) )
                update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
            /** Update Last Name */
            if ( ! empty( $_POST['last-name'] ) )
                update_user_meta( $current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );

            /**
             * Redirect so the page will show updated info.
             */
            if ( count( $error ) == 0 ) {
                //action hook for plugins and extra fields saving
                do_action( 'edit_user_profile_update', $current_user->ID );
            }

            // Run success codes.
            wpd_ecommerce_checkout_success();
        }
        ?>

        <form method="post" id="checkout" class="wpd-ecommerce form checkout" action="<?php the_permalink(); ?>">

		<h3 class='wpd-ecommerce patient-title'><?php _e( 'Billing details', 'wpd-ecommerce' ); ?></h3>

        <?php
        /**
         * @todo add action_hook here
         */
        ?>
        <p class="form-row first form-first-name">
            <label for="first-name"><?php _e('First Name', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
            <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
        </p><!-- .form-first-name -->
        <p class="form-row last form-last-name">
            <label for="last-name"><?php _e('Last Name', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
            <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
        </p><!-- .form-last-name -->

        <p class="form-row form-email">
            <label for="email"><?php _e( 'E-mail', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
            <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
        </p><!-- .form-email -->

        <p class="form-row form-phone-number">
            <label for="phone-number"><?php _e( 'Phone number', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="phone_number" type="text" id="phone_number" value="<?php the_author_meta( 'phone_number', $current_user->ID ); ?>" />
        </p><!-- .form-phone-number -->

        <?php
        /**
         * @todo hide certain fields if owner turns off physical address
         */
        ?>

        <p class="form-row form-address-line">
            <label for="address-line"><?php _e( 'Street address', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="address_line_1" type="text" id="address_line_1" value="<?php the_author_meta( 'address_line_1', $current_user->ID ); ?>" placeholder="<?php _e( 'House number and street name', 'wpd-ecommerce' ); ?>" />
            <input class="text-input" name="address_line_2" type="text" id="address_line_2" value="<?php the_author_meta( 'address_line_2', $current_user->ID ); ?>" placeholder="<?php _e( 'Apartment, unit, etc. (optional)', 'wpd-ecommerce' ); ?>" />
        </p><!-- .form-address-line -->

        <p class="form-row form-city">
            <label for="email"><?php _e( 'City', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="city" type="text" id="city" value="<?php the_author_meta( 'city', $current_user->ID ); ?>" />
        </p><!-- .form-city -->

        <p class="form-row form-state-county">
            <label for="email"><?php _e( 'State / County', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="state-county" type="text" id="state_county" value="<?php the_author_meta( 'state_county', $current_user->ID ); ?>" />
        </p><!-- .form-state-county -->

        <p class="form-row form-postcode-zip">
            <label for="email"><?php _e( 'Postcode / ZIP', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="postcode_zip" type="text" id="postcode_zip" value="<?php the_author_meta( 'postcode_zip', $current_user->ID ); ?>" />
        </p><!-- .form-postcode-zip -->

        <p class="form-row form-country">
            <label for="email"><?php _e( 'Country', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="country" type="text" id="country" value="<?php the_author_meta( 'country', $current_user->ID ); ?>" />
        </p><!-- .form-phone-country -->

        <?php
        /**
        * @todo add action_hook here
        */
        ?>

		<h3 class='wpd-ecommerce patient-order'><?php _e( 'Your order', 'wpd-ecommerce' ); ?></h3>

        <?php
        /**
         * @todo create new way to save order product info, instead of $str.
         */
        $str  = '<table class="wpd-ecommerce widget checkout">';
        $str .= '<thead>';
        $str .= '<tr><td>Product</td><td>Total</td></tr>';
        $str .= '</thead>';
        $str .= '<tbody>';

        foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
            $i             = new Item( $id, '', '', '' );
            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

            if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {

                $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

                $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
                $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

                if ( '_priceperpack' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
                } else {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
                }

                if ( '_priceperpack' === $item_meta_key ) {
                    $weightname = $units_per_pack . ' pack';
                } else {
                    $weightname = '';
                }

            } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {

                $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

                $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
                $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

                if ( '_pricetopical' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
                } elseif ( '_priceperpack' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
                } elseif ( '_priceeach' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
                }

                if ( '_priceperpack' === $item_meta_key ) {
                    $weightname = $units_per_pack . ' pack';
                } else {
                    $weightname = '';
                }

            } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

                /**
                 * @todo make flower_names through the entier plugin filterable.
                 */
                $flower_names = array(
                    '1 g'    => '_gram',
                    '2 g'    => '_twograms',
                    '1/8 oz' => '_eighth',
                    '5 g'    => '_fivegrams',
                    '1/4 oz' => '_quarter',
                    '1/2 oz' => '_halfounce',
                    '1 oz'   => '_ounce',
                );

                $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
                $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                foreach ( $flower_names as $value=>$key ) {
                    if ( $key == $flower_weight_cart ) {
                        /**
                         * @todo change value to actual amount instead of just variable name
                         */
                        $weightname = " - " . $value;
                    }
                }

            } elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

                /**
                 * @todo make concentrate_names through the entier plugin filterable.
                 */
                $concentrates_names = array(
                    '1/2 g' => '_halfgram',
                    '1 g'   => '_gram',
                    '2 g'   => '_twograms',
                );

                $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
                $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                foreach ( $concentrates_names as $value=>$key ) {
                    if ( $key == $concentrate_weight_cart ) {
                        /**
                         * @todo change value to actual amount instead of just variable name
                         */
                        $weightname = " - " . $value;
                    }
                }
                if ( '_priceeach' === $concentrate_weight_cart ) {
                    $weightname = '';
                }
            } else {
                // Do nothing.
            }

            // print_r( $i );

            $total_price = $amount * $regular_price;

            $str .=	"<tr><td>" . $i->thumbnail . "<a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> x <strong>" . $amount . "</strong></td><td><span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</span></td></tr>";

        endforeach;

        $total_price = ( number_format((float)$_SESSION['wpd_ecommerce']->vat, 2, '.', ',' ) + $_SESSION['wpd_ecommerce']->sum );

        /**
         * @todo filter text
         */
        $str .= "<tr><td><strong>Subtotal</strong></td><td>" . CURRENCY . number_format( (float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . "</td></tr>";
        $str .= "<tr><td><strong>VAT</strong></td><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->vat, 2, '.', ',' ) . "</td></tr>";
        $str .= "<tr><td><strong>Total</strong></td><td>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</td></tr>";

        $str .= "</tbody>";
        $str .= "</table>";

        $str .= "<p class='form-submit'><input name='checkout-submit' type='submit' id='checkoutsubmit' class='submit button' value='" . __( 'Place Order', 'wpd-ecommerce' ) . "' />" . wp_nonce_field( 'wpd-ecommerce-checkout' ) . "<input name='action' type='hidden' id='action' value='wpd-ecommerce-checkout' /></p>";
        $str .= "</form>";

        echo $str;

	} else {
        echo '<p>You can checkout after adding some products to your cart.</p>';
        /**
         * @todo make this link filterable, both for URL and button text.
         */
		echo '<p><a href="' . get_bloginfo( 'url' ) . '/dispensary-menu/" class="button wpd-ecommerce return">Return to Menu</a></p>';
	}
}
add_shortcode( 'wpd_checkout', 'wpd_ecommerce_checkout_shortcode' );

/**
 * Fire this off when the order is a success.
 */
function wpd_ecommerce_checkout_success() {

    $page_date    = date( 'Y-m-d H:i:s' );
    $current_user = wp_get_current_user();

    $customer_details  = '';
    $customer_details .= '<p><strong>Name:</strong> ' . $current_user->first_name . ' ' . $current_user->last_name . '</p>';
    $customer_details .= '<p><strong>Email:</strong> ' . $current_user->user_email . '</p>';

    $customer_id = $current_user->ID;

    // Order database variables.
    $wpd_orders_data      = array();
    $wpd_orders_item_data = array();

    echo "<h3 class='wpd-ecommerce patient-order'>Your Order</h3>";

    $str  = '';
    $str  = '<table class="wpd-ecommerce widget checkout">';
    $str .= '<thead>';
    $str .= '<tr><td>Product</td><td>Total</td></tr>';
    $str .= '</thead>';
    $str .= '<tbody>';

    foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
        $i             = new Item( $id, '', '', '' );
        $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
        $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

        if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {

            $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

            if ( '_priceperpack' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
            } else {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
            }

            if ( '_priceperpack' === $item_meta_key ) {
                $weightname = $units_per_pack . ' pack';
            } else {
                $weightname = '';
            }

        } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {

            $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

            if ( '_pricetopical' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
            } elseif ( '_priceperpack' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
            } elseif ( '_priceeach' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
            }

            if ( '_priceperpack' === $item_meta_key ) {
                $weightname = $units_per_pack . ' pack';
            } else {
                $weightname = '';
            }

        } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
            $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

            /**
             * @todo make flower_names through the entier plugin filterable.
             */
            $flower_names = array(
                '1 g'    => '_gram',
                '2 g'    => '_twograms',
                '1/8 oz' => '_eighth',
                '5 g'    => '_fivegrams',
                '1/4 oz' => '_quarter',
                '1/2 oz' => '_halfounce',
                '1 oz'   => '_ounce',
            );

            $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
            $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

            foreach ( $flower_names as $value=>$key ) {
                if ( $key == $flower_weight_cart ) {
                    /**
                     * @todo change value to actual amount instead of just variable name
                     */
                    $weightname = $value;
                }
            }

        } elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
            $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

            /**
             * @todo make concentrate_names through the entier plugin filterable.
             */
            $concentrates_names = array(
                '1/2 g' => '_halfgram',
                '1 g'   => '_gram',
                '2 g'   => '_twograms',
            );

            $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
            $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

            foreach ( $concentrates_names as $value=>$key ) {
                if ( $key == $concentrate_weight_cart ) {
                    /**
                     * @todo change value to actual amount instead of just variable name
                     */
                    $weightname = $value;
                }
            }
            if ( '_priceeach' === $concentrate_weight_cart ) {
                $weightname = '';
            }
        } else {
            // Do nothing.
        }

        // print_r( $i );

        // Total price.
        $total_price = $amount * $regular_price;

        // Order name.
        $order_item_name = $i->title . $weightname;

        // Add order details to array.
        $wpd_orders_data[$i->id] = $order_item_name;

        // Get cart item data.
        $array_insert[] = array(
            'order_item_id'        => $i->id,
            'order_item_name'      => $i->title,
            'item_id'              => $item_old_id,
            'item_url'             => $i->permalink,
            'item_image_url'       => get_the_post_thumbnail_url( $i->id, 'full' ),
            'item_image_url_thumb' => get_the_post_thumbnail_url( $i->id, 'thumbnail' ),
            'item_variation'       => $item_meta_key,
            'item_variation_name'  => $weightname,
            'quantity'             => $amount,
            'single_price'         => $regular_price,
            'total_price'          => $total_price,
        );

        $str .=	"<tr><td>" . $i->thumbnail . "<a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> x <strong>" . $amount . "</strong></td><td><span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</span></td></tr>";

    endforeach;

    $str .= "</tbody>";
    $str .= "</table>";

    // Create order object.
    $wpd_order = array(
        'post_type'   => 'wpd_orders',
        'post_status' => 'publish',
        'post_author' => 1,
        'date'        => $page_date, // YYYY-MM-DDTHH:MM:SS
        'meta_input'  => array(
            'wpd_order_details'          => $str, // @todo get this to save all product data correctly when saving the order.
            'wpd_order_customer_details' => $customer_details,
            'wpd_order_customer_id'      => $customer_id
        ),
    );

    // Insert the order into the database.
    $wpd_order_id = wp_insert_post( $wpd_order );

    global $wpdb;

    /**
     * Insert order details into wpd_orders table.
     *
     * @since 1.0.0
     */
    foreach ( $wpd_orders_data as $id=>$name ) {
        $wpdb->insert( $wpdb->prefix . 'wpd_orders', array(
            'order_item_id'   => $id,
            'order_item_name' => $name,
            'order_id'        => $wpd_order_id,
        ));
    }

    // Get row's from database with current $wpd_order_id.
    $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$wpd_order_id}", ARRAY_A );

    /**
     * Loop through each item in the order
     */
    $i = -1;
    // Loop through each product in the database.
    foreach( $get_order_data as $order_value ) {
        $i++;
        $order_id_key = $order_value['item_id'];
        $array        = array_values( $array_insert );

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


    // This updates the new order with custom title, etc.
    $updated_post = array(
        'ID'            => $wpd_order_id,
        'post_title'    => 'Order #' . $wpd_order_id,
        'post_status'   => 'publish', // Now it's public
        'post_type'     => 'wpd_orders'
    );
    wp_update_post( $updated_post );

    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if ( ini_get("session.use_cookies" ) ) {
        $params = session_get_cookie_params();
        setcookie( session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    // Redirect to Thank You Page after order.
    wp_redirect( get_bloginfo( 'url' ) . '/order-complete/?id=' . $wpd_order_id );

    exit;
}