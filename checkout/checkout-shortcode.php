<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_shortcode() {

    // Verify that there's an active session.
	if ( ! empty( $_SESSION['wpd_ecommerce'] ) ) {
        global $current_user, $wp_roles;

        /**
         * Checkout Delivery Address
         * 
         * @todo Make text filterable for developers
         */
        echo "<h2 class='wpd-ecommerce patient-title'>Delivery Address</h2>";

        $error = array();

        /* If checkout is submitted, do something specific . */
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'wpd-ecommerce-checkout' ) {

            /** Update Email Address */
            if ( ! empty( $_POST['email'] ) ) {
                if ( ! is_email( esc_attr( $_POST['email'] ) ) )
                    $error[] = __( 'The Email you entered is not valid. Please try again.', 'wp-dispensary' );
                elseif( email_exists( esc_attr( $_POST['email'] ) ) != $current_user->ID )
                    $error[] = __( 'This email is already used by another user. Try a different one.', 'wp-dispensary' );
                else {
                    wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] ) ) );
                }
            }

            /** Update First Name */
            if ( ! empty( $_POST['first-name'] ) )
                update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
            /** Update Last Name */
            if ( ! empty( $_POST['last-name'] ) )
                update_user_meta( $current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );

            /**
             * Redirect so the page will show updated info.
             * I am not Author of this Code- i dont know why but it worked for me after changing below line
             * to if ( count($error) == 0 ){
             */
            if ( count( $error ) == 0 ) {
                //action hook for plugins and extra fields saving
                do_action( 'edit_user_profile_update', $current_user->ID );
            }

        } ?>
        
        <?php wpd_ecommerce_checkout_success(); ?>

        <form method="post" id="checkout" class="wpd-ecommerce form checkout" action="<?php the_permalink(); ?>">

            <p class="form-row first form-first-name">
                <label for="first-name"><?php _e('First Name', 'wp-dispensary' ); ?><span class="required">*</span></label>
                <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
            </p><!-- .form-first-name -->
            <p class="form-row last form-last-name">
                <label for="last-name"><?php _e('Last Name', 'wp-dispensary' ); ?><span class="required">*</span></label>
                <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
            </p><!-- .form-last-name -->

            <p class="form-row form-email">
                <label for="email"><?php _e( 'E-mail', 'wp-dispensary' ); ?><span class="required">*</span></label>
                <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
            </p><!-- .form-email -->

        <?php

        echo "<h3 class='wpd-ecommerce patient-order'>Your Order</h3>";

        $str  = '<table class="wpd-ecommerce widget checkout">';
        $str .= '<thead>';
        $str .= '<tr><td>Product</td><td>Total</td></tr>';
        $str .= '</thead>';
        $str .= '<tbody>';

        foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
            $i    = new Item( $id, '', '', '' );
            $item_old_id = preg_replace( '/[^0-9.]+/', '', $id );
            $weight_option2 = preg_replace( '/[0-9]+/', '', $id );
            if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
                $regular_price  = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
                $weightname = '';
            } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
                $weightname = '';
            } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, $weight_option2, true ) );

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
                        $weightname = " - " . $value;
                    }
                }
            } else {
                $regular_price = '';
                $weightname = '';
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
        $str .= "<tr><td><strong>Total</strong></td><td>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</td></tr>";
        $str .= "</tbody>";
        $str .= "</table>";

        $str .= "<p class='form-submit'><input name='checkout-submit' type='submit' id='checkoutsubmit' class='submit button' value='" . __( 'Place Order', 'wp-dispensary' ) . "' />" . wp_nonce_field( 'wpd-ecommerce-checkout' ) . "<input name='action' type='hidden' id='action' value='wpd-ecommerce-checkout' /></p>";
        $str .= "</form>";

        echo $str;

	} else {
		echo '<p>You can checkout after adding some products to your cart.</p>';
		echo '<p><a href="' . get_bloginfo( 'url' ) . '/dispensary-menu/" class="button wpd-ecommerce return">Return to Menu</a></p>';
	}
}
add_shortcode( 'wpd_checkout', 'wpd_ecommerce_checkout_shortcode' );

/**
 * Fire this off when the order is a success.
 */
function wpd_ecommerce_checkout_success() {

    $page_date        = date( 'Y-m-d H:i:s' );
    $current_user     = wp_get_current_user();

    $customer_details = '';
    $customer_details .= '<p><strong>Name:</strong> ' . $current_user->first_name . ' ' . $current_user->last_name . '</p>';
    $customer_details .= '<p><strong>Email:</strong> ' . $current_user->user_email . '</p>';

    $customer_id = $current_user->ID;

    /* If checkout is submitted, do something specific . */
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'wpd-ecommerce-checkout' ) {

        $str  = '';

        echo "<h3 class='wpd-ecommerce patient-order'>Your Order</h3>";

        $str  = '<table class="wpd-ecommerce widget checkout">';
        $str .= '<thead>';
        $str .= '<tr><td>Product</td><td>Total</td></tr>';
        $str .= '</thead>';
        $str .= '<tbody>';

        foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
            $i    = new Item( $id, '', '', '' );
            $item_old_id = preg_replace( '/[^0-9.]+/', '', $id );
            $weight_option2 = preg_replace( '/[0-9]+/', '', $id );
            if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
                $regular_price  = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
                $weightname = '';
            } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
                $weightname = '';
            } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, $weight_option2, true ) );

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
                        $weightname = " - " . $value;
                    }
                }
            } else {
                $regular_price = '';
                $weightname = '';
            }

            // print_r( $i );

            $total_price = $amount * $regular_price;

            $str .=	"<tr><td>" . $i->thumbnail . "<a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> x <strong>" . $amount . "</strong></td><td><span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</span></td></tr>";

        endforeach;

        $str .= "</tbody>";
        $str .= "</table>";

        // Create order object.
        $wpd_order = array(
            'post_type'     => 'wpd_orders',
            'post_status'   => 'publish',
            'post_author'   => 1,
			'date'          => $page_date, // YYYY-MM-DDTHH:MM:SS
            'meta_input'    => array(
				'wpd_order_details'          => $str,
                'wpd_order_customer_details' => $customer_details,
                'wpd_order_customer_id'      => $customer_id
			),
        );

        // Insert the order into the database.
        $wpd_order_id = wp_insert_post( $wpd_order );

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
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
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
}