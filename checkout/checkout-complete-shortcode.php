<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_complete_shortcode() {

    global $current_user;

    $get_id = $_GET['id'];

    if ( ! isset( $get_id ) ) {
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    } elseif ( get_post_meta( $get_id, 'wpd_order_customer_id', true ) == $current_user->ID ) {
        echo "<h3>Order Complete (#" . $get_id . ")</h3>";
        echo "<p>Thank you. Your order has been received.</p>";

        global $wpdb;

        // Get cart item data.
        $array_insert[] = array(
            'order_item_id'       => 'order_item_id_new',
            'order_item_name'     => 'order_item_name_new',
            'item_id'             => 'the_id',
            'item_variation'      => 'item_var',
            'item_variation_name' => 'variation_title',
            'quantity'            => 'how_much',
            'single_price'        => 'normal_price',
            'total_price'         => 'some_price',
        );
        $array_insert[] = array(
            'order_item_id'       => 'test_order_item_id_new',
            'order_item_name'     => 'test_order_item_name_new',
            'item_id'             => 'test_the_id',
            'item_variation'      => 'test_item_var',
            'item_variation_name' => 'test_variation_title',
            'quantity'            => '_testhow_much',
            'single_price'        => 'tet_normal_price',
            'total_price'         => 'this_some_price',
        );


        // Get row's from database with current $wpd_order_id.
        $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$get_id}", ARRAY_A );

        $i = -1;

        // Loop through each product in the database.
        foreach( $get_order_data as $order_item ) {
            $i++;

            $order_item_meta_id = $order_item['item_id'];

            echo "<h4>Item #" . $order_item_meta_id . " - i " . $i . "</h4>";

            // Get row's from database with current $wpd_order_id.
            $get_order_item_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders_meta WHERE item_id = {$order_item_meta_id}", ARRAY_A );
            $array               = array_values( $get_order_item_data );

            //echo "<strong>number in loop: " . $i . "</strong><br />";

            //print_r( $get_order_item_data );

            /**
             * This newArray combines all of the meta data for each product
             * 
             * Used to loop through and output the new array value's by KEY name
             */
            $newArray = array();

            foreach ($get_order_item_data as $entry) {
                $newArray[$entry['meta_key']] = $entry['meta_value'];
            }

            echo "<strong>newArray:</strong>";

            echo "<pre>";
            print_r( $newArray );
            echo "</pre>";

            echo "<strong>New Array Output:</strong> " . $newArray['total_price'] . "</strong>";
            

            //echo "<br /><br />";

            echo "<pre>";
            print_r( $array );
            echo "</pre>";

            //echo "<br /><br />";

            //echo key( $get_order_item_data[$i] );

            //echo $get_order_item_data[$i];

            echo "<strong>TEST: " . $array[$i]['meta_key'] . "</strong>";

            $number = -1;

            // Loop through each product in the database.
            foreach( $get_order_item_data as $order_key=>$order_value ) {
                $number++;
/*
                $get_order_item_data_new = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}wpd_orders_meta WHERE item_id = {$order_value}", ARRAY_A );
                echo "<h6><strong>Get Order Item Array</strong></h6>";
                echo "<pre>";
                print_r( $get_order_item_data_new );
                echo "</pre>";
    
                $num = -1;
    
                // Loop through each product in the database.
                foreach( $get_order_item_data[$i] as $order_key=>$order_value ) {
                    $num++;
                    echo $orderkey;
                }
    
                echo "<h6><strong>Item Array</strong></h6>";
                echo "<pre>";
                print_r( $get_order_item_data[$number] );
                echo "</pre>";

                echo "<h6><strong>Get Order Item Array</strong></h6>";
                //echo "<pre>";
                print_r( $get_order_item_data );

                //echo "</pre>";
                echo "<p><strong>Order Key:</strong> " . $order_value[$i]['meta_value'] . "</p>";

                //echo "<strong>Test:</strong> " . $order_key['order_item_name'] . "<br />";

                if ( 'order_item_id' == $order_data['meta_key'] ) {
                    $order_item_id = $order_data['meta_value'];
                    echo "<strong>Order Item ID:</strong> " . $order_data['meta_value'] . "<br />";
                }

                if ( 'order_item_name' == $order_data['meta_key'] ) {
                    $order_item_name = $order_data['meta_value'];
                    echo "<strong>Name:</strong> " . $order_data['meta_value'] . "<br />";
                }

                if ( 'order_item_variation_name' == $order_data['meta_key'] ) {
                    $order_item_variation_name = $order_data['meta_value'];
                    echo " - " . $order_data['meta_value'];
                }


                if ( 'item_id' == $order_data['meta_key'] ) {
                    $item_id = $order_data['meta_value'];
                    echo "<strong>ID:</strong> " . $order_data['meta_value'] . "<br />";
                }

                if ( 'item_variation' == $order_data['meta_key'] ) {
                    $item_variation = $order_data['meta_value'];
                    echo "<strong>Item Variation:</strong> " . $order_data['meta_value'] . "<br />";
                }

                if ( 'quantity' == $order_data['meta_key'] ) {
                    $quantity = $order_data['meta_value'];
                    echo "<strong>Quantity:</strong> " . $order_data['meta_value'] . "<br />";
                }

                if ( 'single_price' == $order_data['meta_key'] ) {
                    $single_price = $order_data['meta_value'];
                    echo "<strong>Single:</strong> " . $order_data['meta_value'] . "<br />";
                }

                if ( 'total_price' == $order_data['meta_key'] ) {
                    $total_price = $order_data['meta_value'];
                    echo "<strong>Total:</strong> " . wpd_currency_code() . $order_data['meta_value'] . "<br />";
                }
                */
                /*
                echo "<h3>" . $order_item_name . "<span>" . $order_item_variation_name . "</span></h3>";
                echo "<p>";
                echo "<strong>Price:</strong> " . wpd_currency_code() . $single_price . " x " . $quantity . " = " . wpd_currency_code() . $total_price;
                echo "</p>";
                */

                /*
                echo "<p>Order Key and Order Data: <strong>" . $order_key . "</strong> - ";
                echo "<strong>" . $order_data . "</strong></p>";
*/
                /*
                foreach ( $get_order_item_data[$i] as $order=>$data ) {
                    echo "<p>Order Data: <strong>" . $order . "</strong> - ";
                    echo "<strong>" . $data . "</strong></p>";
                }
                echo "<p>";
                echo "<strong>item_id:</strong> " . $get_order_item_data[$i]['item_id'] . "<br />";
                echo "<strong>order_item_id:</strong> " . $get_order_item_data[$i]['order_item_id'] . "<br />";
                echo "<strong>order_item_name:</strong> " . $order_data[$i]['order_item_name'] . "<br />";
                echo "<strong>item_variation:</strong> " . $order_data[$i]['item_variation'] . "<br />";
                echo "<strong>order_item_variation_name:</strong> " . $order_data[$i]['order_item_variation_name'] . "<br />";
                echo "<strong>quntity:</strong> " . $order_data[$i]['quantity'] . "<br />";
                echo "<strong>single_price:</strong> " . $order_data[$i]['single_price'] . "<br />";
                echo "<strong>total_price:</strong> " . $order_data[$i]['total_price'] . "<br />";
                echo "</p>";
                */
            }
        }

        echo "<h3 class='wpd-ecommerce patient-order'>Your Order</h3>";
        echo get_post_meta( $get_id, 'wpd_order_details', true );
    } else {
        echo "<h2>Unauthorized Access</h2>";
        echo "<p>You're unable to access this shit!</p>";
        /**
         * @todo add an option to Settings for user to choose this link and/or use their Menu page.
         */
        wp_redirect( get_bloginfo( 'url' ) );
        exit;
    }

}
add_shortcode( 'wpd_checkout_complete', 'wpd_ecommerce_checkout_complete_shortcode' );
