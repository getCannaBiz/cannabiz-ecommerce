<?php
/**
 * WP Dispensary eCommerce order helper functions
 *
 * @package WPD_eCommerce/functions
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Destroy Session
 * 
 * @since       1.0.0
 */
function wpd_ecommerce_destroy_session( $eat_cookies = NULL ) {
	// Unset all of the session variables.
	$_SESSION = array();

	/**
	 * Destroy cookies
	 * 
	 * If it's desired to kill the session, also delete the session cookie.
	 */
	if ( TRUE == $eat_cookies ) {
		if ( ini_get("session.use_cookies" ) ) {
			$params = session_get_cookie_params();
			setcookie( session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
	}

	// Finally, destroy the session.	
    session_destroy();
}

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
				// wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
			}

		} else {
			// Do nothing.
		}

	}

	// Display failed login message.
	if ( 'failed' === $_GET['login'] ) {
		$str .= '<div class="wpd-ecommerce-notifications failed"><strong>Error:</strong> The username or password you entered is incorrect.</div>';
	}

	// Display failed login message.
	if ( 'failed' === $_GET['register'] ) {
		$str .= '<div class="wpd-ecommerce-notifications failed"><strong>Error:</strong> The registration info you entered is incorrect.</div>';
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
		
		// Loop through ID's.
		foreach ( $item_qty as $item=>$value ) {
			$quantities[] = $value;
		}
	}
	
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
	
			// Get current inventory count.
			$old_inventory = get_post_meta( $item_old_id, '_inventory_flowers', true );
	
			if ( '_gram' == $item_meta_key ) {
				$total = 1 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_twograms' == $item_meta_key ) {
				$total = 2 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_eighth' == $item_meta_key ) {
				$total = 3.5 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_fivegrams' == $item_meta_key ) {
				$total = 5 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_quarter' == $item_meta_key ) {
				$total = 7 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_halfounce' == $item_meta_key ) {
				$total = 14 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} elseif ( '_ounce' == $item_meta_key ) {
				$total = 28 * $value;

				// insert update_post_meta here with old inventory - $total 
				$new_inventory = $old_inventory - $total;
				update_post_meta( $item_old_id, '_inventory_flowers', $new_inventory, $old_inventory );
			} else {
				// Do nothing.
			}
		} elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {

			if ( '_priceeach' == $item_meta_key ) {
				$total = 1 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_concentrates_each', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_concentrates_each', $new_inventory, $old_inventory );

			} elseif ( '_halfgram' == $item_meta_key ) {
				$total = 0.5 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_concentrates', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_concentrates', $new_inventory, $old_inventory );

			} elseif ( '_gram' == $item_meta_key ) {
				$total = 1 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_concentrates', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_concentrates', $new_inventory, $old_inventory );

			} elseif ( '_twograms' == $item_meta_key ) {
				$total = 2 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_concentrates', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_concentrates', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		} elseif ( 'edibles' === get_post_type( $item_old_id ) ) {

			if ( '_priceeach' == $item_meta_key ) {
				$total = 1 * $value;
				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_edibles', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_edibles', $new_inventory, $old_inventory );

			} elseif ( '_priceperpack' == $item_meta_key ) {

				// Units per pack.
				if ( get_post_meta( $item_old_id, '_unitsperpack', true ) ) {
					$per_pack = get_post_meta( $item_old_id, '_unitsperpack', true );
				} else {
					$per_pack = 1;
				}

				// Multiple item quantity by _unitsperpack.
				$total = $per_pack * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_edibles', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_edibles', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		} elseif ( 'prerolls' === get_post_type( $item_old_id ) ) {

			if ( '_priceeach' == $item_meta_key ) {
				$total = 1 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_prerolls', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_prerolls', $new_inventory, $old_inventory );

			} elseif ( '_priceperpack' == $item_meta_key ) {

				// Units per pack.
				if ( get_post_meta( $item_old_id, '_unitsperpack', true ) ) {
					$per_pack = get_post_meta( $item_old_id, '_unitsperpack', true );
				} else {
					$per_pack = 1;
				}

				// Multiple item quantity by _unitsperpack.
				$total = $per_pack * $value;
				
				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_prerolls', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_prerolls', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		} elseif ( 'topicals' === get_post_type( $item_old_id ) ) {

			if ( '_pricetopical' == $item_meta_key ) {
				$total = 1 * $value;
				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_topicals', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_topicals', $new_inventory, $old_inventory );

			} elseif ( '_priceperpack' == $item_meta_key ) {

				// Units per pack.
				if ( get_post_meta( $item_old_id, '_unitsperpack', true ) ) {
					$per_pack = get_post_meta( $item_old_id, '_unitsperpack', true );
				} else {
					$per_pack = 1;
				}

				// Multiple item quantity by _unitsperpack.
				$total = $per_pack * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_topicals', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_topicals', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		} elseif ( 'growers' === get_post_type( $item_old_id ) ) {

			if ( '_priceeach' == $item_meta_key ) {
				$total = 1 * $value;

				// Get current inventory count.
				if ( get_post_meta( $item_old_id, 'inventory_seeds' ) ) {
					$old_inventory = get_post_meta( $item_old_id, '_inventory_seeds', true );
				} elseif ( get_post_meta( $item_old_id, 'inventory_clones' ) ) {
					$old_inventory = get_post_meta( $item_old_id, '_inventory_clones', true );
				} else {
					// Do nothing.
				}
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_growers', $new_inventory, $old_inventory );

			} elseif ( '_priceperpack' == $item_meta_key ) {

				// Units per pack.
				if ( get_post_meta( $item_old_id, '_unitsperpack', true ) ) {
					$per_pack = get_post_meta( $item_old_id, '_unitsperpack', true );
				} else {
					$per_pack = 1;
				}

				// Multiple item quantity by _unitsperpack.
				$total = $per_pack * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_growers', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_growers', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		} elseif ( 'gear' === get_post_type( $item_old_id ) ) {

			if ( '_priceeach' == $item_meta_key ) {
				// Multiple item quantity by 1.
				$total = 1 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_gear', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_gear', $new_inventory, $old_inventory );

			} elseif ( '_priceperpack' == $item_meta_key ) {

				// Units per pack.
				if ( get_post_meta( $item_old_id, '_unitsperpack', true ) ) {
					$per_pack = get_post_meta( $item_old_id, '_unitsperpack', true );
				} else {
					$per_pack = 1;
				}

				// Multiple item quantity by _unitsperpack.
				$total = $per_pack * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_gear', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_gear', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		} elseif ( 'tinctures' === get_post_type( $item_old_id ) ) {

			if ( '_priceeach' == $item_meta_key ) {
				// Multiply item quantity by one.
				$total = 1 * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_tinctures', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_tinctures', $new_inventory, $old_inventory );

			} elseif ( '_priceperpack' == $item_meta_key ) {

				// Units per pack.
				if ( get_post_meta( $item_old_id, '_unitsperpack', true ) ) {
					$per_pack = get_post_meta( $item_old_id, '_unitsperpack', true );
				} else {
					$per_pack = 1;
				}

				// Multiple item quantity by _unitsperpack.
				$total = $per_pack * $value;

				// Get current inventory count.
				$old_inventory = get_post_meta( $item_old_id, '_inventory_tinctures', true );
				$new_inventory = $old_inventory - $total;

				update_post_meta( $item_old_id, '_inventory_tinctures', $new_inventory, $old_inventory );

			} else {
				// Do nothing.
			}
		}
	}
	
}
