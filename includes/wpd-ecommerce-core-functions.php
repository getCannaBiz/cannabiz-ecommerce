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

	global $post;

	// Get pages data from settings page.
	$wpdas_pages = get_option( 'wpdas_pages' );

	// Get menu page selected by website owner.
	$menu_page = $wpdas_pages['wpd_pages_setup_menu_page'];

	// Get WPD settings from General tab.
	$wpdas_general = get_option( 'wpdas_general' );

	// Check if user needs to be logged in to shop.
	if ( isset( $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'] ) ) {
		$login_to_shop = $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'];
	} else {
		$login_to_shop = NULL;
	}

	// Get WPD settings from Pages tab.
	$wpdas_pages = get_option( 'wpdas_pages' );

	// Create account page URL variable.
	$account_page = home_url() . '/' . $wpdas_pages['wpd_pages_setup_account_page'];

	// Create cart page URL variable.
	$cart_page = home_url() . '/' . $wpdas_pages['wpd_pages_setup_cart_page'];

	// Begin data.
	$str = '';

	if ( in_array( get_post_type(), apply_filters( 'wpd_ecommerce_box_notifications_array', wpd_menu_types_simple( TRUE ) ) ) ) {

		// Check if cart widget is active.
		if ( ! is_active_widget( false, false, 'wpd_cart_widget', true ) ) {
			$view_cart_button = '<a href="' . $cart_page . '" class="button">' . __( 'View Cart', 'wpd-ecommerce' ) . '</a>';
		} else {
			$view_cart_button = '';
		}

		// Please select a weight notification for flowers.
		if ( is_singular( 'flowers' ) && isset( $_POST['add_me'] ) && ! isset( $_POST['wpd_ecommerce_flowers_prices'] ) ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications failed">' . __( 'Please select a weight in order to add the product to your cart', 'wpd-ecommerce' ) . '</div>';
			$str .= '</div>';
		}

		// Please select a weight notification for concentrates.
		if ( is_singular( 'concentrates' ) && isset( $_POST['add_me'] ) && ! isset( $_POST['wpd_ecommerce_concentrates_prices'] ) ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications failed">' . __( 'Please select a weight in order to add the product to your cart', 'wpd-ecommerce' ) . '</div>';
			$str .= '</div>';
		}

		// Please select a weight notification for edibles.
		if ( is_singular( 'edibles' ) && isset( $_POST['add_me'] ) && get_post_meta( get_the_ID(), '_priceperpack', TRUE ) && ! isset( $_POST['wpd_ecommerce_product_prices'] ) ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications failed">' . __( 'Please select a quantity in order to add the product to your cart', 'wpd-ecommerce' ) . '</div>';
			$str .= '</div>';
		}	

		// Successfully added item to cart.
		if ( is_singular( 'flowers' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_flowers_prices'] ) ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications success">' . __( 'This product has been successfully added to your cart', 'wpd-ecommerce' ) . ' ' . $view_cart_button . '<a href="' . home_url() . '/' . $menu_page . '" class="button">' . __( 'Continue Shopping', 'wpd-ecommerce' ) . '</a></div>';
			$str .= '</div>';
		} elseif ( is_singular( 'concentrates' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_concentrates_prices'] ) ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications success">' . __( 'This product has been successfully added to your cart', 'wpd-ecommerce' ) . ' ' . $view_cart_button . '<a href="' . home_url() . '/' . $menu_page . '" class="button">' . __( 'Continue Shopping', 'wpd-ecommerce' ) . '</a></div>';
			$str .= '</div>';
		} elseif ( is_singular( array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_product_prices'] ) ) {
			// Begin wrapper around notifications.
			$str .= '<div class="wpd-ecommerce-single-notifications">';
			$str .= '<div class="wpd-ecommerce-notifications success">' . __( 'This product has been successfully added to your cart', 'wpd-ecommerce' ) . ' ' . $view_cart_button . '<a href="' . home_url() . '/' . $menu_page . '" class="button">' . __( 'Continue Shopping', 'wpd-ecommerce' ) . '</a></div>';
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
			if ( isset( $_POST['wpd_ecommerce_product_prices'] ) ) {
				$new_price = $_POST['wpd_ecommerce_product_prices'];
			} else {
				$new_price = NULL;
			}
			if ( isset( $_POST['wpd_ecommerce_concentrates_prices'] ) ) {
				$concentrates_prices = $_POST['wpd_ecommerce_concentrates_prices'];
			} else {
				$concentrates_prices = NULL;
			}

			if ( empty( $new_price ) ) {
				if ( 'topicals' === get_post_type() ) {
					$old_price    = get_post_meta( $old_id, '_pricetopical', true );
					$single_price = get_post_meta( $old_id, '_pricetopical', true );
					$pack_price   = get_post_meta( $old_id, '_priceperpack', true );

					if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price ) {
						// Begin wrapper around notifications.
						$str .= '<div class="wpd-ecommerce-single-notifications">';
						$str .= '<div class="wpd-ecommerce-notifications success">' . __( 'This product has been successfully added to your cart', 'wpd-ecommerce' ) . ' ' . $view_cart_button . '<a href="' . home_url() . '/' . $menu_page . '" class="button">' . __( 'Continue Shopping', 'wpd-ecommerce' ) . '</a></div>';
						$str .= '</div>';
					}
				} elseif ( is_singular( array( 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {
					$single_price = get_post_meta( $old_id, '_priceeach', true );
					$pack_price   = get_post_meta( $old_id, '_priceperpack', true );

					if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price && NULL == $concentrates_prices ) {
						// Begin wrapper around notifications.
						$str .= '<div class="wpd-ecommerce-single-notifications">';
						$str .= '<div class="wpd-ecommerce-notifications success">' . __( 'This product has been successfully added to your cart', 'wpd-ecommerce' ) . ' ' . $view_cart_button . '<a href="' . home_url() . '/' . $menu_page . '" class="button">' . __( 'Continue Shopping', 'wpd-ecommerce' ) . '</a></div>';
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
	if ( ! empty( $_GET['login'] ) ) {
		if ( 'failed' === $_GET['login'] ) {
			$str .= '<div class="wpd-ecommerce-notifications failed"><strong>' . __( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . __( 'The username or password you entered is incorrect.', 'wpd-ecommerce' ) . '</div>';
		}
	}

	// Display failed register message.
	if ( ! empty( $_GET['register'] ) ) {
		if ( 'failed' === $_GET['register'] ) {
			$str .= '<div class="wpd-ecommerce-notifications failed"><strong>' . __( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . __( 'The registration info you entered is incorrect.', 'wpd-ecommerce' ) . '</div>';
		}
	}

	// Display order thank you message.
	if ( ! empty( $_GET['order'] ) ) {
		if ( 'thank-you' === $_GET['order'] ) {
			$str .= '<div class="wpd-ecommerce-notifications success"><strong>' . __( 'Thank You', 'wpd-ecommerce' ) . ':</strong> Your order #' . get_the_ID() . ' has been submitted.</div>';
		}
	}

	// Remove an item from the cart
	if ( ! empty( $_GET['remove_item'] ) ) {
		$_SESSION['wpd_ecommerce']->remove_item( $_GET['remove_item'] );
		$str .= '<div class="wpd-ecommerce-notifications success"><strong>' . __( 'Item removed', 'wpd-ecommerce' ) . ':</strong> ' . __( 'The item has been successfully removed.', 'wpd-ecommerce' ) .'</div>';
	}

	// Add an item from the cart
	if ( ! empty( $_GET['add_item'] ) ) {
		if ( empty( $_SESSION['wpd_ecommerce'] ) || ! isset( $_SESSION['wpd_ecommerce'] ) ):
			$c = new Cart;
			$c->add_item( $_GET['add_item'], 1, '', '', '' );
			$_SESSION['wpd_ecommerce'] = $c;
		else:
			$_SESSION['wpd_ecommerce']->add_item( $_GET['add_item'], 1, '', '', '' );
		endif;
		$str .= '<div class="wpd-ecommerce-single-notifications">';
		$str .= '<div class="wpd-ecommerce-notifications success"><strong>' . __( 'Item added', 'wpd-ecommerce' ) . ':</strong> ' . __( 'The item has been successfully added to your cart.', 'wpd-ecommerce' ) . '</div>';
		$str .= '</div>';
	}

	/**
	 * If a user clicks to clear the cart.
	 */
	if ( isset( $_GET['clear_cart'] ) ) {
		wpd_ecommerce_clear_cart();
	}

	/**
	 * Coupon Codes
	 * 
	 * If coupon code is added to the cart, do something specific.
	 * 
	 * @since 1.0
	 */
	if ( isset( $_POST['coupon_code'] ) && ! empty( $_POST['coupon_code'] ) && isset( $_POST['add_coupon'] ) ) {

		 // Loop through coupons.
		$coupons_args = array(
			'numberposts' => 1,
			'meta_key'    => 'wpd_coupon_code',
			'meta_value'  => $_POST['coupon_code'],
			'post_type'   => 'coupons'
		);
		$coupons_loop = get_posts( $coupons_args );

		//print_r( $coupons_loop );

		if ( 0 == count( $coupons_loop ) ) {
			$str = '<div class="wpd-ecommerce-notifications failed"><strong>' . __( 'Error', 'wpd-ecommerce' ) . ':</strong> Coupon code "' . $_POST['coupon_code'] . '" does not exist</div>';
		}

		foreach( $coupons_loop as $coupon ) : setup_postdata( $coupon );

		$coupon_code   = get_post_meta( $coupon->ID, 'wpd_coupon_code', TRUE );
		$coupon_amount = get_post_meta( $coupon->ID, 'wpd_coupon_amount', TRUE );
		$coupon_type   = get_post_meta( $coupon->ID, 'wpd_coupon_type', TRUE );
		$coupon_exp    = get_post_meta( $coupon->ID, 'wpd_coupon_exp', TRUE );

		// Add coupon to the cart.
		$_SESSION['wpd_ecommerce']->add_coupon( $coupon_code, $coupon_amount, $coupon_type, $coupon_exp );
		
		// Display success notification.
		echo '<div class="wpd-ecommerce-notifications success"><strong>' . __( 'Success', 'wpd-ecommerce' ) . ':</strong> ' . __( 'Coupon code has been applied', 'wpd-ecommerce' ) . '</div>';

		endforeach;
	}

	// Remove the coupon from cart.
	if ( ! empty( $_SESSION['wpd_ecommerce']->coupon_code ) ) {

		// Get the coupon code to remove.
		if ( ! empty( $_GET['remove_coupon'] ) ) {
			$remove_coupon = $_GET['remove_coupon'];
		} else {
			$remove_coupon = '';
		}

		// Remove the code from the cart and redirect back.
		if ( $_SESSION['wpd_ecommerce']->coupon_code === $remove_coupon ) {
			// Remove coupon.
			$_SESSION['wpd_ecommerce']->remove_coupon( $coupon_code, $coupon_amount, $coupon_type, $coupon_exp );
			// Redirect back.
			wp_redirect( get_the_permalink() );
		} else {
			// Do nothing.
		}
	} else {
		// Do nothing.
	}

	// If the coupon code form is submitted but no code was input.
	if ( isset( $_POST['coupon_code'] ) && empty( $_POST['coupon_code'] ) && isset( $_POST['add_coupon'] ) ) {
		$str = '<div class="wpd-ecommerce-notifications failed"><strong>' . __( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . __( 'Please enter a coupon code', 'wpd-ecommerce' ) . '</div>';
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

/**
 * Template wrap - before
 * 
 * @since 1.0
 */
function wpd_ecommerce_wrap_before() {
	echo '<div id="primary" class="col-lg-8 content-area">
	<main id="main" class="site-main" role="main">';
}
add_action( 'wpd_ecommerce_templates_archive_items_wrap_before', 'wpd_ecommerce_wrap_before', 10 );
add_action( 'wpd_ecommerce_templates_single_items_wrap_before', 'wpd_ecommerce_wrap_before', 10 );
add_action( 'wpd_ecommerce_templates_single_orders_wrap_before', 'wpd_ecommerce_wrap_before', 10 );

/**
 * Template wrap - after
 * 
 * @since 1.0
 */
function wpd_ecommerce_wrap_after() {
	echo '</main>
	</div>';
}
add_action( 'wpd_ecommerce_templates_archive_items_wrap_after', 'wpd_ecommerce_wrap_after', 10 );
add_action( 'wpd_ecommerce_templates_single_items_wrap_after', 'wpd_ecommerce_wrap_after', 10 );
add_action( 'wpd_ecommerce_templates_single_orders_wrap_after', 'wpd_ecommerce_wrap_after', 10 );


$my_theme = wp_get_theme();
/*
echo "<pre>";
print_r( $my_theme );
echo "</pre>";

echo $my_theme->get( 'Template' );
*/
$template_name = $my_theme->get( 'TextDomain' );

if ( 'twentyseventeen' == $template_name ) {
	remove_action( 'wpd_ecommerce_templates_archive_items_wrap_before', 'wpd_ecommerce_wrap_before', 10 );
	remove_action( 'wpd_ecommerce_templates_single_items_wrap_before', 'wpd_ecommerce_wrap_before', 10 );
	remove_action( 'wpd_ecommerce_templates_single_orders_wrap_before', 'wpd_ecommerce_wrap_before', 10 );
	remove_action( 'wpd_ecommerce_templates_archive_items_wrap_after', 'wpd_ecommerce_wrap_after', 10 );
	remove_action( 'wpd_ecommerce_templates_single_items_wrap_after', 'wpd_ecommerce_wrap_after', 10 );

	/**
	 * Template wrap - before
	 * 
	 * @since 1.0
	 */
	function wpd_ecommerce_wrap_before_twentyseventeen() {
		echo '<div class="wrap"><div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">';
	}
	add_action( 'wpd_ecommerce_templates_archive_items_wrap_before', 'wpd_ecommerce_wrap_before_twentyseventeen', 10 );
	add_action( 'wpd_ecommerce_templates_single_items_wrap_before', 'wpd_ecommerce_wrap_before_twentyseventeen', 10 );
	add_action( 'wpd_ecommerce_templates_single_orders_wrap_before', 'wpd_ecommerce_wrap_before_twentyseventeen', 10 );

	/**
	 * Template wrap - after
	 * 
	 * @since 1.0
	 */
	function wpd_ecommerce_wrap_after_twentyseventeen() {
		echo '</main>
		</div>';
	}
	add_action( 'wpd_ecommerce_templates_archive_items_wrap_after', 'wpd_ecommerce_wrap_after_twentyseventeen', 10 );
	add_action( 'wpd_ecommerce_templates_single_items_wrap_after', 'wpd_ecommerce_wrap_after_twentyseventeen', 10 );

} // if twentyseventeen theme

function wpd_admin_settings_section_after() {
	// Section: Pages.
	$wpdas_object = $wpdas_obj->add_section(
		array(
			'id'    => 'wpdas_pages',
			'title' => __( 'Pages', 'wpd-ecommerce' ),
		)
	);

	return $wpdas_object;
}
add_action( 'wp_dispensary_admin_settings_sections_before', 'wpd_admin_settings_section_after' );

/**
 * Single items - Vendors
 * 
 * @since 1.0
 */
function wpd_ecommerce_single_items_vendors() {
	global $post;

	// Get vendors.
	if ( get_the_term_list( get_the_ID(), 'vendor', true ) ) {
		$wpdvendors = '<span class="wpd-ecommerce vendors">' . get_the_term_list( $post->ID, 'vendor', '', ', ', '' ) . '</span>';
	} else {
		$wpdvendors = '';
	}
	// Display vendors.
	echo $wpdvendors;

}
add_action( 'wpd_ecommerce_templates_single_items_entry_title_before', 'wpd_ecommerce_single_items_vendors' );

/**
 * Single items - Notifications
 * 
 * @since 1.0
 */
function wpd_ecommerce_single_items_notifications(){
	echo wpd_ecommerce_notifications();
}
add_action( 'wpd_ecommerce_templates_single_items_entry_header_before', 'wpd_ecommerce_single_items_notifications' );

/**
 * Single items - Add to cart form
 * 
 * @since 1.0
 */
function wpd_ecommerce_add_to_cart_form() {

	// Get WPD settings from General tab.
	$wpdas_general = get_option( 'wpdas_general' );

	// Check if user is required to be logged in to shop.
	if ( isset( $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'] ) ) {
		$login_to_shop = $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'];
	} else {
		$login_to_shop = NULL;
	}

	// Get WPD settings from Pages tab.
	$wpdas_pages = get_option( 'wpdas_pages' );

	// Create account page URL variable.
	$account_page = home_url() . '/' . $wpdas_pages['wpd_pages_setup_account_page'];

	// Set prices.
	if ( in_array( get_post_type( get_the_ID() ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
		$regular_price = esc_html( get_post_meta( get_the_ID(), '_priceeach', true ) );
		$pack_price    = esc_html( get_post_meta( get_the_ID(), '_priceperpack', true ) );
		$pack_units    = esc_html( get_post_meta( get_the_ID(), '_unitsperpack', true ) );
	} elseif ( 'topicals' === get_post_type() ) {
		$regular_price = esc_html( get_post_meta( get_the_ID(), '_pricetopical', true ) );
		$pack_price    = esc_html( get_post_meta( get_the_ID(), '_priceperpack', true ) );
		$pack_units    = esc_html( get_post_meta( get_the_ID(), '_unitsperpack', true ) );
	} elseif ( 'flowers' === get_post_type() ) {
		$flower_names = array();
		foreach ( wpd_flowers_weights_array() as $key=>$value ) {
			$flower_names[$key] = esc_html( get_post_meta( get_the_ID(), $value, true ) );
		}
		$regular_price = $flower_names;
		$pack_price    = '';
		$pack_units    = '';
	} elseif ( 'concentrates' === get_post_type() ) {
		$concentrate_names = array();
		foreach ( wpd_concentrates_weights_array() as $key=>$value ) {
			$concentrate_names[$key] = esc_html( get_post_meta( get_the_ID(), $value, true ) );
		}
		$regular_price = $concentrate_names;
		$pack_price    = '';
		$pack_units    = '';
	} else {
		$regular_price = '';
		$pack_price    = '';
		$pack_units    = '';
	}
	?>

	<!-- ADD TO CART -->
	<form name="add_to_cart" class="wpd-ecommerce" method="post">

	<?php
	// Generate display price HTML.
	$display_price = '<p class="wpd-ecommerce price">' . get_wpd_all_prices_simple( get_the_ID(), FALSE ) . '</p>';

	// Display filtered single price.
	echo apply_filters( 'wpd_ecommerce_single_item_price', $display_price );

	// Check if user is required to login to shop.
	if ( ! is_user_logged_in() && 'on' == $login_to_shop ) {
		$str_login  = '<div class="wpd-ecommerce-notifications">';
		$str_login .= '<div class="wpd-ecommerce-notifications failed">You must be <a href="' . $account_page . '">logged in</a> to place an order</div>';
		$str_login .= '</div>';

		// Display login notification.
		echo $str_login;
	} else { ?>

	<fieldset>

	<?php if ( ! empty( $regular_price ) ) { ?>

		<?php if ( 'flowers' === get_post_type() ) { ?>
			<?php

			// Select a weight.
			printf( '<select name="wpd_ecommerce_flowers_prices" id="wpd_ecommerce_flowers_prices" class="widefat">' );
			printf( '<option value="" disabled selected>' . __( 'Choose a weight', 'wpd-ecommerce' ) . '</option>' );
			foreach ( $regular_price as $name => $price ) {
				if ( '' != $price ) {
					printf( '<option value="'. esc_html( $price ) . '">' . CURRENCY . esc_html( $price ) . ' - ' . esc_html( $name ) . '</option>' );
				}
			}
			print( '</select>' );

			// Flower prices.
			$weight_gram      = get_post_meta( get_the_ID(), '_gram', true );
			$weight_twograms  = get_post_meta( get_the_ID(), '_twograms', true );
			$weight_eighth    = get_post_meta( get_the_ID(), '_eighth', true );
			$weight_fivegrams = get_post_meta( get_the_ID(), '_fivegrams', true );
			$weight_quarter   = get_post_meta( get_the_ID(), '_quarter', true );
			$weight_halfounce = get_post_meta( get_the_ID(), '_halfounce', true );
			$weight_ounce     = get_post_meta( get_the_ID(), '_ounce', true );

			// Heavyweight prices.
			$weight_twoounces    = get_post_meta( get_the_ID(), '_twoounces', true );
			$weight_quarterpound = get_post_meta( get_the_ID(), '_quarterpound', true );
			$weight_halfpound    = get_post_meta( get_the_ID(), '_halfpound', true );
			$weight_onepound     = get_post_meta( get_the_ID(), '_onepound', true );
			$weight_twopounds    = get_post_meta( get_the_ID(), '_twopounds', true );
			$weight_threepounds  = get_post_meta( get_the_ID(), '_threepounds', true );
			$weight_fourpounds   = get_post_meta( get_the_ID(), '_fourpounds', true );
			$weight_fivepounds   = get_post_meta( get_the_ID(), '_fivepounds', true );
			$weight_sixpounds    = get_post_meta( get_the_ID(), '_sixpounds', true );
			$weight_sevenpounds  = get_post_meta( get_the_ID(), '_sevenpounds', true );
			$weight_eightpounds  = get_post_meta( get_the_ID(), '_eightpounds', true );
			$weight_ninepounds   = get_post_meta( get_the_ID(), '_ninepounds', true );
			$weight_tenpounds    = get_post_meta( get_the_ID(), '_tenpounds', true );
			$weight_elevenpounds = get_post_meta( get_the_ID(), '_elevenpounds', true );
			$weight_twelvepounds = get_post_meta( get_the_ID(), '_twelvepounds', true );

			// Set Flower weight meta key.
			if ( ! empty( $_POST['wpd_ecommerce_flowers_prices'] ) ) {
				if ( $weight_gram === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_gram';
				} elseif ( $weight_twograms === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_twograms';
				} elseif ( $weight_eighth === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_eighth';
				} elseif ( $weight_fivegrams === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_fivegrams';
				} elseif ( $weight_quarter === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_quarter';
				} elseif ( $weight_halfounce === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_halfounce';
				} elseif ( $weight_ounce === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_ounce';
				} elseif ( $weight_twoounces === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_twoounces';
				} elseif ( $weight_quarterpound === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_quarterpound';
				} elseif ( $weight_halfpound === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_halfpound';
				} elseif ( $weight_onepound === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_onepound';
				} elseif ( $weight_twopounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_twopounds';
				} elseif ( $weight_threepounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_threepounds';
				} elseif ( $weight_fourpounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_fourpounds';
				} elseif ( $weight_fivepounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_fivepounds';
				} elseif ( $weight_sixpounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_sixpounds';
				} elseif ( $weight_sevenpounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_sevenpounds';
				} elseif ( $weight_eightpounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_eightpounds';
				} elseif ( $weight_ninepounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_ninepounds';
				} elseif ( $weight_tenpounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_tenpounds';
				} elseif ( $weight_elevenpounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_elevenpounds';
				} elseif ( $weight_twelvepounds === $_POST['wpd_ecommerce_flowers_prices'] ) {
					$wpd_flower_meta_key = '_twelvepounds';
				} else {
					$wpd_flower_meta_key = '';
				}
			} else {
				// Do nothing.
			}
			?>
		<?php } elseif ( 'concentrates' === get_post_type() ) { ?>
			<?php

			// Price each (not a weight based price).
			$price_each = get_post_meta( get_the_ID(), '_priceeach', true );

			// If price_each is empty.
			if ( '' === $price_each ) {
				// Select a weight.
				printf( '<select name="wpd_ecommerce_concentrates_prices" id="wpd_ecommerce_concentrates_prices" class="widefat">' );
				printf( '<option value="" disabled selected>Choose a weight</option>' );
				foreach ( $regular_price as $name => $price ) {
					if ( '' != $price ) {
						printf( '<option value="'. esc_html( $price ) . '">' . CURRENCY . esc_html( $price ) . ' - ' . esc_html( $name ) . '</option>' );
					}
				}
				print( '</select>' );

				$weight_halfgram  = get_post_meta( get_the_ID(), '_halfgram', true );
				$weight_gram      = get_post_meta( get_the_ID(), '_gram', true );
				$weight_twograms  = get_post_meta( get_the_ID(), '_twograms', true );

				if ( ! empty( $_POST['wpd_ecommerce_concentrates_prices'] ) ) {
					if ( $weight_halfgram === $_POST['wpd_ecommerce_concentrates_prices'] ) {
						$wpd_concentrate_meta_key = '_halfgram';
					} elseif ( $weight_gram === $_POST['wpd_ecommerce_concentrates_prices'] ) {
						$wpd_concentrate_meta_key = '_gram';
					} elseif ( $weight_twograms === $_POST['wpd_ecommerce_concentrates_prices'] ) {
						$wpd_concentrate_meta_key = '_twograms';
					} else {
						$wpd_concentrate_meta_key = '';
					}
				} else {
					// Do nothing.
				}
			} else {
				// Do nothing.
			}
			?>
		<?php } else {
			if ( ! empty( $pack_price ) ) {
				// Select a quantity.
				print( '<select name="wpd_ecommerce_product_prices" id="wpd_ecommerce_product_prices" class="widefat">' );
				printf( '<option value="" disabled selected>' . __( 'Choose a quantity', 'wpd-ecommerce' ) . '</option>' );
				printf( '<option value="'. esc_html( $regular_price ) . '">' . CURRENCY . esc_html( $regular_price ) . ' - ' . __( 'each', 'wpd-ecommerce' ) . '</option>' );
				printf( '<option value="'. esc_html( $pack_price ) . '">' . CURRENCY . esc_html( $pack_price ) . ' - ' . esc_html( $pack_units ) . ' ' . __( 'pack', 'wpd-ecommerce' ) . '</option>' );
				print( '</select>' );

				$price_each     = get_post_meta( get_the_ID(), '_priceeach', true );
				$price_topical  = get_post_meta( get_the_ID(), '_pricetopical', true );
				$price_per_pack = get_post_meta( get_the_ID(), '_priceperpack', true );

				if ( ! empty( $_POST['wpd_ecommerce_product_prices'] ) ) {
					if ( $price_each === $_POST['wpd_ecommerce_product_prices'] ) {
						$wpd_product_meta_key = '_priceeach';
					} elseif ( $price_topical === $_POST['wpd_ecommerce_product_prices'] ) {
						$wpd_product_meta_key = '_pricetopical';
					} elseif ( $price_per_pack === $_POST['wpd_ecommerce_product_prices'] ) {
						$wpd_product_meta_key = '_priceperpack';
					} else {
						$wpd_product_meta_key = '_priceeach';
					}
				}
			} else {
				// Do nothing.
			}
		} ?>

	<?php } ?>

	<?php

	global $post;
	/**
	 * Add Items to Cart
	 */
	if ( is_singular( 'flowers' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_flowers_prices'] ) ) {
		$qtty = $_POST['qtty'];

		/**
		 * ID's
		 */
		$old_id = $post->ID;
		$new_id = $post->ID . $wpd_flower_meta_key;

		/**
		 * Prices
		 */
		$new_price = $_POST['wpd_ecommerce_flowers_prices'];
		$old_price = get_post_meta( $old_id, $new_price, true );

		/**
		 * Add items to cart
		 */
		wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

	} elseif ( is_singular( 'concentrates' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_concentrates_prices'] ) ) {
		$qtty = $_POST['qtty'];

		/**
		 * ID's
		 */
		$old_id = $post->ID;
		$new_id = $post->ID . $wpd_concentrate_meta_key;

		/**
		 * Prices
		 */
		$new_price = $_POST['wpd_ecommerce_concentrates_prices'];
		$old_price = get_post_meta( $old_id, $new_price, true );

		/**
		 * Add items to cart
		 */
		wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

	} elseif ( is_singular( wpd_menu_types_simple() ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_product_prices'] ) ) {
		$qtty = $_POST['qtty'];

		/**
		 * ID's
		 */
		$old_id = $post->ID;
		$new_id = $post->ID . $wpd_product_meta_key;

		/**
		 * Prices
		 */
		$new_price = $_POST['wpd_ecommerce_product_prices'];
		$old_price = get_post_meta( $old_id, $wpd_product_meta_key, true );

		wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

	} elseif ( isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) ) {
		$qtty = $_POST['qtty'];

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
		if ( isset( $_POST['wpd_ecommerce_product_prices'] ) ) {
			$new_price = $_POST['wpd_ecommerce_product_prices'];
		} else {
			$new_price = NULL;
		}
		if ( isset( $_POST['wpd_ecommerce_concentrates_prices'] ) ) {
			$concentrates_prices = $_POST['wpd_ecommerce_concentrates_prices'];
		} else {
			$concentrates_prices = NULL;
		}

		if ( empty( $new_price ) ) {
			if ( 'topicals' === get_post_type() ) {

				$old_price    = get_post_meta( $old_id, '_pricetopical', true );
				$single_price = get_post_meta( $old_id, '_pricetopical', true );
				$pack_price   = get_post_meta( $old_id, '_priceperpack', true );

				/*
				var_dump( $new_price );
				echo "...<br />...";
				print_r( $new_price );
				*/

				if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price ) {
					wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
				}

			} elseif ( is_singular( array( 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {

				$old_price    = get_post_meta( $old_id, $wpd_product_meta_key, true );
				$single_price = get_post_meta( $old_id, '_priceeach', true );
				$pack_price   = get_post_meta( $old_id, '_priceperpack', true );

				/*
				var_dump( $new_price );
				echo "...<br />...";
				print_r( $new_price );
				*/

				if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price && NULL == $concentrates_prices ) {
					wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
				}

			}
		} else {
			$old_price = get_post_meta( $old_id, $wpd_product_meta_key, true );
			// wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
		}

	} else {
		$qtty = 1;
	}
	?>
		<input type="number" name="qtty" id="qtty" value="1" class="item_Quantity" />
		<input type="submit" class="item_add" id="add_item_btn" value="<?php echo __( 'Add to cart', 'wpd-ecommerce' ); ?>" name="add_me" />
	</fieldset>
	<?php } ?>
	</form>
<?php }
add_action( 'wpd_ecommerce_templates_single_items_entry_title_after', 'wpd_ecommerce_add_to_cart_form' );
