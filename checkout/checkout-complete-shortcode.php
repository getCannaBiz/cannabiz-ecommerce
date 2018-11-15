<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_complete_shortcode() {

    // Get current user.
    global $current_user;

    // Order ID number.
    $get_id = $_GET['id'];

    if ( get_post_meta( $get_id, 'wpd_order_customer_id', true ) == $current_user->ID ) {

        $order_customer_id = get_post_meta( $get_id, 'wpd_order_customer_id', true );
        $order_subtotal    = get_post_meta( $get_id, 'wpd_order_subtotal_price', true );
        $order_total       = get_post_meta( $get_id, 'wpd_order_total_price', true );
        $order_items       = get_post_meta( $get_id, 'wpd_order_items', true );

        global $wpdb;

        // Get row's from database with current $wpd_order_id.
        $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id} AND order_type = 'product'", ARRAY_A );

        $qty = -1;

        // Loop through each product in the database.
        foreach( $get_order_data as $order_item ) {
            $qty++;

            // Get item number.
            $order_item_meta_id = $order_item['item_id'];

            // Get row's from database with current order number.
            $get_order_item_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders_meta WHERE item_id = {$order_item_meta_id}", ARRAY_A );
    
            // Loop order item data, making array.
            foreach ( $get_order_item_data as $entry ) {
                $newArray[$entry['meta_key']] = $entry['meta_value'];
            }

            /*
            echo "<pre>";
            print_r( $newArray );
            echo "</pre>";
            */

            // Create quantity array.
            if ( '' != $newArray['quantity'] ) {
                $quantity[$newArray['order_item_id']] = $newArray['quantity'];
            } else {
                // Do nothing.
            }

            // Create new_quantity array.
            if ( '' != $newArray['quantity'] ) {
                $new_quantity[$newArray['item_id']] = $newArray['quantity'];
            } else {
                // Do nothing.
            }

            // Create item_id array.
            if ( '' != $newArray['item_id'] ) {
                $item_qty[$newArray['order_item_id']] = $newArray['quantity'];
            } else {
                // Do nothing.
            }

            // Create item_id array.
            if ( '' != $newArray['item_id'] ) {
                $item_ids[] = $newArray['item_id'];
            } else {
                // Do nothing.
            }

            // Create item_variation array.
            if ( '' != $newArray['item_variation'] ) {
                $item_variations[] = $newArray['item_variation'];
            } else {
                // Do nothing.
            }

            // Create order_item_id array.
            if ( '' != $newArray['order_item_id'] ) {
                $order_item_ids[] = $newArray['order_item_id'];
            } else {
                // Do nothing.
            }

            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $item );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $item );

            /*
            echo "<pre>";
            print_r( $item_qty );
            echo "</pre>";
            echo "<p><strong>ID:</strong> " . $item_old_id . " ... ";
            echo "<strong>Key:</strong> " . $item_meta_key . "</p>";
            */

            // Loop through ID's.
            foreach ( $item_qty as $item=>$value ) {
                $quantities[] = $value;
            }
        }

//        echo "<pre>";
//        print_r( $quanties );
//        echo "</pre>";

//        echo "<pre>";
//        print_r( $quantity );
//        echo "</pre>";

//        echo "<p><strong>Order Quantity:</strong> " . array_sum( $quantity ) . "</strong></p>";

        // Loop through quantity array.
        foreach ( $quantity as $key=>$value ) {

            // Get ID and weight metaname from quantity array key.
            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $key );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $key );

            /**
             * This is where I loop through the cart items
             * to multiple the quantity by what the meta_key is
             */
            if ( 'flowers' === get_post_type( $item_old_id ) ) {

                echo "<p><strong>FLOWERS: </strong></p>";

                // Get current inventory count.
                $old_inventory = get_post_meta( $item_old_id, '_inventory_flowers', true );

                //echo "<p><strong>FLOWERS: </strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_gram' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 g x " . $value . "</p>";
                    echo "<p>Total: " . $total . "g</p>";
                    // insert update_post_meta here with old inventory - $total 
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                } elseif ( '_twograms' == $item_meta_key ) {
                    $total = 2 * $value;
                    echo "<p>2 g x " . $value . "</p>";
                    echo "<p>Total: " . $total . "g</p>";
                    // insert update_post_meta here with old inventory - $total 
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                } elseif ( '_eighth' == $item_meta_key ) {
                    $total = 3.5 * $value;
                    echo "<p>1/8 oz x " . $value . "</p>";
                    echo "<p>Total: " . $total . "g</p>";
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_fivegrams' == $item_meta_key ) {
                    $total = 5 * $value;
                    echo "<p>5 g x " . $value . "</p>";
                    echo "<p>Total: " . $total . "g</p>";
                    // insert update_post_meta here with old inventory - $total 
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                } elseif ( '_quarter' == $item_meta_key ) {
                    $total = 7 * $value;
                    echo "<p>1/4 oz x " . $value . "</p>";
                    echo "<p>Total: " . $total . "g</p>";
                    // insert update_post_meta here with old inventory - $total 
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                } elseif ( '_halfounce' == $item_meta_key ) {
                    $total = 14 * $value;
                    echo "<p>1/2 oz x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                } elseif ( '_ounce' == $item_meta_key ) {
                    $total = 28 * $value;
                    echo "<p>1 oz x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                    $new_inventory = $old_inventory - $total;
                    //update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
                } else {
                    // Do nothing.
                }
            } elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
                echo "<strong>CONCENTRATES: </strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_priceeach' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_halfgram' == $item_meta_key ) {
                    $total = 0.5 * $value;
                    echo "<p>1/2 g</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_gram' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 g</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_twograms' == $item_meta_key ) {
                    $total = 2 * $value;
                    echo "<p>2 g</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            } elseif ( 'edibles' === get_post_type( $item_old_id ) ) {
                echo "<strong>EDIBLES: </strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_priceeach' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_priceperpack' == $item_meta_key ) {
                    $total = 2 * $value; // @todo get #1 changed for actual _unitsperpack
                    echo "<p>2 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            } elseif ( 'prerolls' === get_post_type( $item_old_id ) ) {
                echo "<strong>PRE-ROLLS: </strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_priceeach' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_priceperpack' == $item_meta_key ) {
                    $total = 2 * $value; // @todo get #1 changed for actual _unitsperpack
                    echo "<p>2 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {
                echo "<strong>TOPICALS: </strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_pricetopical' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_priceperpack' == $item_meta_key ) {
                    $total = 2 * $value; // @todo get #1 changed for actual _unitsperpack
                    echo "<p>2 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            } elseif ( 'growers' === get_post_type( $item_old_id ) ) {
                echo "<strong>GROWERS: </strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_priceeach' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_priceperpack' == $item_meta_key ) {
                    $total = 2 * $value; // @todo get #1 changed for actual _unitsperpack
                    echo "<p>2 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            } elseif ( 'gear' === get_post_type( $item_old_id ) ) {
                echo "<strong>GEAR:</strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_priceeach' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_priceperpack' == $item_meta_key ) {
                    $total = 2 * $value; // @todo get #1 changed for actual _unitsperpack
                    echo "<p>2 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            } elseif ( 'tinctures' === get_post_type( $item_old_id ) ) {
                echo "<strong>TINCTURES:</strong>" . $value . " - " . $item_meta_key . "</p>";
                if ( '_priceeach' == $item_meta_key ) {
                    $total = 1 * $value;
                    echo "<p>1 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } elseif ( '_priceperpack' == $item_meta_key ) {
                    $total = 2 * $value; // @todo get #1 changed for actual _unitsperpack
                    echo "<p>2 x " . $value . "</p>";
                    echo "<p>Total: " . $total . "</p>";
                    // insert update_post_meta here with old inventory - $total 
                } else {
                    // Do nothing.
                }
            }
        }

        /**
         * @todo add an action hook for developers to customize this page
         */
        echo "<div class='wpd-ecommerce order-complete'>";

            echo "<h3>Order Complete (#" . $get_id . ")</h3>";
            echo "<p>" . __( 'Thank you. Your order has been received.', 'wpd-ecommerce' ) . "</p>";

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

        echo '</div>';

        /**
         * @todo add an action hook for developers to customize this page
         */
        echo "<h3 class='wpd-ecommerce patient-order'>" . __( 'Your Order', 'wpd-ecommerce' ) . "</h3>";
        echo wpd_ecommerce_table_order_data( $get_id, $current_user->ID );
        /**
         * @todo add an action hook for developers to customize this page
         */

    } else {
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    }

}
add_shortcode( 'wpd_checkout_complete', 'wpd_ecommerce_checkout_complete_shortcode' );
