<?php
// Add Cart Shortcode.
function wpd_ecommerce_shortcode() {
	if ( '0' != $_SESSION['wpd_ecommerce']->items ) {

		// Include notifications.
		echo wpd_ecommerce_notifications();

		$str  = '';
		$str .= do_action( 'wpd_ecommerce_cart_table_before' );
		$str .= '<table class="wpd-ecommerce cart">';
		$str .= '<thead><tr><td></td><td></td><td>' . __( 'Product', 'wpd-ecommerce' ) . '</td><td>' . __( 'Price', 'wpd-ecommerce' ) . '</td><td>' . __( 'Qty', 'wpd-ecommerce' ) . '</td><td>' . __( 'Total', 'wpd-ecommerce' ) . '</td></tr></thead>';
		$str .= '<tbody>';
		$str .= do_action( 'wpd_ecommerce_cart_table_inside_body_before' );
		foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
			$i             = new Item( $id, '', '', '' );
			$item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
			$item_meta_key = preg_replace( '/[0-9]+/', '', $id );

			if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
				$units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );
				$regular_price  = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );
				if ( '_priceperpack' === $item_meta_key ) {
					$weightname = ' - ' . $units_per_pack . ' pack';
				} else {
					$weightname = '';
				}
			} elseif ( 'topicals' === get_post_type( $item_old_id ) ) {
				$units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );
				$regular_price  = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );
				if ( '_priceperpack' === $item_meta_key ) {
					$weightname = ' - ' . $units_per_pack . ' pack';
				} else {
					$weightname = '';
				}
			} elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
				$regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

				/**
				 * @todo make flower_names through the entire plugin filterable.
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
			} elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
				$regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

				/**
				 * @todo make concentrate_names through the entire plugin filterable.
				 */
				$concentrates_names = array(
					'1/2 g'  => '_halfgram',
					'1 g'    => '_gram',
					'2 g'    => '_twograms',
				);

				$item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
				$concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

				foreach ( $concentrates_names as $value=>$key ) {
					if ( $key == $concentrate_weight_cart ) {
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

			$str .=	"<tr><td><a href='" . get_the_permalink() . "?remove_item=" . $id . "' class='remove'>x</a></td><td>" . $i->thumbnail . "</td><td><a href='" . $i->permalink . "'>" . $i->title . "" . $weightname . "</a></td><td>" . CURRENCY . number_format( $regular_price, 2, '.', ',' ) . "</td><td><input id='quantity' name='quantity' class='wpd-ecommerce-quantity' type='number' value='" . $amount . "' /></td><td>" . CURRENCY . number_format((float)$total_price, 2, '.', ',' ) . "</td></tr>";
		endforeach;
		$str .= do_action( 'wpd_ecommerce_cart_table_inside_body_after' );
		/**
		 * Access all settings
		 */
		$wpd_general = get_option( 'wpdas_general' );

		// Check if WP Dispensary setting is set.
		if ( 'on' === $wpd_payments['wpd_ecommerce_checkout_coupons'] ) {
			$str .= "<tr><td colspan='6'>
			<form class='wpd-ecommerce-apply-coupon' name='apply_coupon' method='post'>
			<input type='text' name='coupon_code' value='' placeholder='Coupon code' />
			<input type='submit' class='button' name='add_coupon' value='Apply coupon' />"
			. wp_nonce_field( 'wpd-ecommerce-coupon-code' ) . 
			"</form>
			</td></tr>";
		}
		$str .= "</tbody>";
		$str .= "</table>";
		$str .= do_action( 'wpd_ecommerce_cart_table_after' );

		$total_price = ( number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) + $_SESSION['wpd_ecommerce']->sum );

		$str .= do_action( 'wpd_ecommerce_cart_wrap_before' );

		$str .= "<div class='wpd-ecommerce-wrap'>";
		$str .= do_action( 'wpd_ecommerce_cart_totals_before' );
		$str .= "<div class='cart-totals'>";
		$str .= do_action( 'wpd_ecommerce_cart_totals_inside_before' );
		$str .= "<h2>Cart Totals</h2>";
		$str .= "<table class='wpd-ecommerce totals'>";
		$str .= "<tbody>";
		$str .= "<tr><th class='cart_sum'><span class='subtotal'>Subtotal</span></th><td>" . CURRENCY . number_format( $_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . "</td></tr>";
		if ( 0 !== $_SESSION['wpd_ecommerce']->coupon_code ) {
			$str .= "<tr><th class='cart_coupon'><span class='coupon_code'>Coupon:<br />" . $_SESSION['wpd_ecommerce']->coupon_code . "</span></th><td>-" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->coupon_amount, 2, '.', ',' ) . " (<a href='" . get_the_permalink() . "?remove_coupon=". $_SESSION['wpd_ecommerce']->coupon_code . "'>Remove?</a>)</td></tr>";
		}
		if ( NULL !== SALES_TAX ) {
			$str .= "<tr><th class='cart_sales_tax'><span class='sales_tax'>Sales tax</span></th><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) . "</td></tr>";
		}
		if ( NULL !== EXCISE_TAX ) {
			$str .= "<tr><th class='cart_excise_tax'><span class='excise_tax'>Excise tax</span></th><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) . "</td></tr>";
		}
		if ( NULL !== PAYMENT_TYPE_AMOUNT ) {
			$str .= "<tr><th class='cart_payment_type'><span class='payment_type_amount'>" . PAYMENT_TYPE_NAME . "</span></th><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) . "</td></tr>";
		}
		$str .= "<tr><th class='cart_total'><span class='total'>Total</span></th><td>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</td></tr>";
		$str .= "</tbody>";
		$str .= "</table>";
		$str .= "<p class='wpd-ecommerce buttons'><a href='" . get_bloginfo( 'url' ) . "/checkout' class='button'>Proceed to Checkout</a></p>";
		$str .= do_action( 'wpd_ecommerce_cart_totals_inside_after' );
		$str .= "</div>";
		$str .= do_action( 'wpd_ecommerce_cart_totals_after' );
		$str .= "</div>";
		$str .= do_action( 'wpd_ecommerce_cart_wrap_after' );

		return $str;

	} else {
		echo '<p>There\'s nothing in your cart.</p>';

		$wpdas_pages   = get_option( 'wpdas_pages' );
		$menu_page     = $wpdas_pages['wpd_pages_setup_menu_page'];

		echo '<p><a href="' . get_bloginfo( 'url' ) . '/' . $menu_page . '" class="button wpd-ecommerce return">Return to Menu</a></p>';
	}
}
add_shortcode( 'wpd_cart', 'wpd_ecommerce_shortcode' );
