<?php
/**
 * WP Dispensary eCommerce order helper functions
 *
 * @package WPD_eCommerce/functions
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Dispensary eCommerce notifications.
 *
 * @since 1.0
 * @return string
 */
function wpd_ecommerce_notifications() {
	if ( in_array( get_post_type(), apply_filters( 'wpd_ecommerce_box_notifications_array', array( 'flowers', 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) ) {

		// Check if cart widget is active.
		if ( ! is_active_widget( false, false, 'wpd_cart_widget', true ) ) {
			$view_cart_button = '<a href="' . get_bloginfo( 'url' ) . '/cart" class="button">View Cart</a>';
		} else {
			$view_cart_button = '';
		}

		// Successfully added item to cart.
		if ( is_singular( 'flowers' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && NULL !== $_POST['wpd_ecommerce_flowers_prices'] ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications success">This product has been successfully added to your cart ' . $view_cart_button . '<a href="' . get_bloginfo( 'url' ) . '/dispensary-menu" class="button">Continue Shopping</a></div>';
			$str .= '</div>';
		} elseif ( is_singular( 'concentrates' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && NULL !== $_POST['wpd_ecommerce_concentrates_prices'] ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications success">This product has been successfully added to your cart ' . $view_cart_button . '<a href="' . get_bloginfo( 'url' ) . '/dispensary-menu" class="button">Continue Shopping</a></div>';
			$str .= '</div>';
		} elseif ( is_singular( array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && NULL !== $_POST['wpd_ecommerce_product_prices'] ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications success">This product has been successfully added to your cart ' . $view_cart_button . '<a href="' . get_bloginfo( 'url' ) . '/dispensary-menu" class="button">Continue Shopping</a></div>';
			$str .= '</div>';
		} elseif ( isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) ) {
			// ID.
			$old_id = $post->ID;

			// Setup ID if SOMETHING is not done.
			// This is where the check for adding to cart should come into play.
			if ( empty( $new_id ) ) {
				if ( 'topicals' === get_post_type() ) {
					$new_id = $post->ID . '_pricetopical';
				} else {
					$new_id = $post->ID . '_priceeach';
				}
			} else {
				$new_id = $post->ID . $wpd_product_meta_key;
			}

			// Pricing.
			$new_price           = $_POST['wpd_ecommerce_product_prices'];
			$concentrates_prices = $_POST['wpd_ecommerce_concentrates_prices'];

			if ( empty( $new_price ) ) {
				if ( 'topicals' === get_post_type() ) {
					$old_price    = get_post_meta( $old_id, '_pricetopical', true );
					$single_price = get_post_meta( $old_id, '_pricetopical', true );
					$pack_price   = get_post_meta( $old_id, '_priceperpack', true );

					if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price ) {
						// Begin wrapper around notifications.
						$str .= '<div class="wpd-ecommerce-single-notifications">';
						$str .= '<div class="wpd-ecommerce-notifications success">This product has been successfully added to your cart ' . $view_cart_button . '<a href="' . get_bloginfo( 'url' ) . '/dispensary-menu" class="button">Continue Shopping</a></div>';
						$str .= '</div>';
					}
				} elseif ( is_singular( array( 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {
					$single_price = get_post_meta( $old_id, '_priceeach', true );
					$pack_price   = get_post_meta( $old_id, '_priceperpack', true );

					if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price && NULL == $concentrates_prices ) {
						// Begin wrapper around notifications.
						$str .= '<div class="wpd-ecommerce-single-notifications">';
						$str .= '<div class="wpd-ecommerce-notifications success">This product has been successfully added to your cart ' . $view_cart_button . '<a href="' . get_bloginfo( 'url' ) . '/dispensary-menu" class="button">Continue Shopping</a></div>';
						$str .= '</div>';
					}
				}
			} else {
				$old_price = get_post_meta( $old_id, $wpd_product_meta_key, true );
				// add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
			}

		} else {
			// Do nothing.
		}

	}
	return $str;
}
//add_filter( 'the_content', 'wpd_ecommerce_notifications' );

/**
 * Inventory Management
 * 
 * Updated inventory count when an order is placed.
 * 
 * @since 1.0
 */
function wpd_ecommerce_inventory_management_updates( $get_id ) {
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
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_twograms' == $item_meta_key ) {
				$total = 2 * $value;
				echo "<p>2 g x " . $value . "</p>";
				echo "<p>Total: " . $total . "g</p>";
				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_eighth' == $item_meta_key ) {
				$total = 3.5 * $value;
				echo "<p>1/8 oz x " . $value . "</p>";
				echo "<p>Total: " . $total . "g</p>";
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
				// insert update_post_meta here with old inventory - $total 
			} elseif ( '_fivegrams' == $item_meta_key ) {
				$total = 5 * $value;
				echo "<p>5 g x " . $value . "</p>";
				echo "<p>Total: " . $total . "g</p>";
				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_quarter' == $item_meta_key ) {
				$total = 7 * $value;
				echo "<p>1/4 oz x " . $value . "</p>";
				echo "<p>Total: " . $total . "g</p>";
				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_halfounce' == $item_meta_key ) {
				$total = 14 * $value;
				echo "<p>1/2 oz x " . $value . "</p>";
				echo "<p>Total: " . $total . "</p>";
				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_ounce' == $item_meta_key ) {
				$total = 28 * $value;
				echo "<p>1 oz x " . $value . "</p>";
				echo "<p>Total: " . $total . "</p>";
				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
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
	
}