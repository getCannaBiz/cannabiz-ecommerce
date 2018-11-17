<?php
// Add Cart Shortcode.
function wpd_ecommerce_shortcode() {
	if ( ! empty( $_SESSION['wpd_ecommerce'] ) ) {
		$str  = '<table class="wpd-ecommerce">';
		$str .= '<thead><tr><td></td><td></td><td>Product</td><td>Price</td><td>Quantity</td><td>Total</td></tr></thead>';
		$str .= '<tbody>';
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
					'1/2 g'  => '_halfgram',
					'1 g'    => '_gram',
					'2 g'    => '_twograms',
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

			$str .=	"<tr><td><a href='" . get_the_permalink() . "?remove_item=" . $id . "' class='remove'>x</a></td><td>" . $i->thumbnail . "</td><td><a href='" . $i->permalink . "'>" . $i->title . "" . $weightname . "</a></td><td>" . CURRENCY . number_format( $regular_price, 2, '.', ',' ) . "</td><td><input id='quantity' name='quantity' class='wpd-ecommerce-quantity' type='number' value='" . $amount . "' /></td><td>" . CURRENCY . number_format((float)$total_price, 2, '.', ',' ) . "</td></tr>";
		endforeach;
		$str .= "</tbody>";
		$str .= "</table>";

		$total_price = ( number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' )  + $_SESSION['wpd_ecommerce']->sum );

		$str .= "<div class='wpd-ecommerce-wrap'>";
		$str .= "<div class='cart-totals'>";
		$str .= "<h2>Cart Totals</h2>";
		$str .= "<table class='wpd-ecommerce totals'>";
		$str .= "<tbody>";
		$str .= "<tr><th class='cart_sum'><span class='subtotal'>Subtotal</span></th><td>" . CURRENCY . number_format( $_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . "</td></tr>";
		if ( NULL != defined( 'SALES_TAX' ) ) {
			$str .= "<tr><th class='cart_sales_tax'><span class='sales_tax'>Sales tax</span></th><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) . "</td></tr>";
		}
		if ( NULL != defined( 'EXCISE_TAX' ) ) {
			$str .= "<tr><th class='cart_excise_tax'><span class='excise_tax'>Excise tax</span></th><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) . "</td></tr>";
		}
		$str .= "<tr><th class='cart_total'><span class='total'>Total</span></th><td>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</td></tr>";
		$str .= "</tbody>";
		$str .= "</table>";
		$str .= "<p class='wpd-ecommerce buttons'><a href='" . get_bloginfo( 'url' ) . "/checkout' class='button'>Proceed to Checkout</a></p>";
		$str .= "</div>";
		$str .= "</div>";

		return $str;

	} else {
		echo '<p>There\'s nothing in your cart.</p>';
		echo '<p><a href="' . get_bloginfo( 'url' ) . '/dispensary-menu/" class="button wpd-ecommerce return">Return to Menu</a></p>';
	}
}
add_shortcode( 'wpd_cart', 'wpd_ecommerce_shortcode' );
