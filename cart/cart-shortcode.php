<?php
// Add Cart Shortcode.
function wpd_shopping_cart_shortcode() {
	if ( ! empty( $_SESSION['wpd_shopping_cart'] ) ) {
		$str  = '<table class="wpd-cart">';
		$str .= '<thead><tr><td></td><td></td><td>Product</td><td>Price</td><td>Quantity</td><td>Total</td></tr></thead>';
		$str .= '<tbody>';
		foreach( $_SESSION['wpd_shopping_cart']->item_array as $id=>$amount ):
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
					'1/8 oz' => '_eighth',
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

			$str .=	"<tr><td><a href='" . get_the_permalink() . "?remove_item=" . $id . "' class='remove'>x</a></td><td>" . $i->thumbnail . "</td><td><a href='" . $i->permalink . "'>" . $i->title . "" . $weightname . "</a></td><td>" . CURRENCY . number_format( $regular_price, 2, '.', ',' ) . "</td><td><input id='quantity' name='quantity' class='wpd-cart-quantity' type='number' value='" . $amount . "' /></td><td>" . CURRENCY . number_format((float)$total_price, 2, '.', ',' ) . "</td></tr>";
		endforeach;
		$str .= "</tbody>";
		$str .= "</table>";

		$total_price = ( number_format((float)$_SESSION['wpd_shopping_cart']->vat, 2, '.', ',' ) + $_SESSION['wpd_shopping_cart']->sum );

		$str .= "<div class='wpd-cart-wrap'>";
		$str .= "<div class='cart-totals'>";
		$str .= "<h2>Cart Totals</h2>";
		$str .= "<table class='wpd-cart totals'>";
		$str .= "<tbody>";
		$str .= "<tr><th class='cart_sum'><span class='subtotal'>Subtotal</span></th><td>" . CURRENCY . number_format( $_SESSION['wpd_shopping_cart']->sum, 2, '.', ',' ) . "</td></tr>";
		$str .= "<tr><th class='cart_vat'><span class='vat'>VAT</span></th><td>" . CURRENCY . number_format((float)$_SESSION['wpd_shopping_cart']->vat, 2, '.', ',' ) . "</td></tr>";
		$str .= "<tr><th class='cart_total'><span class='total'>Total</span></th><td>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</td></tr>";
		$str .= "</tbody>";
		$str .= "</table>";
		$str .= "<p class='wpd-cart buttons'><a href='" . get_bloginfo( 'url' ) . "/checkout' class='button'>Proceed to Checkout</a></p>";
		$str .= "</div>";
		$str .= "</div>";

		return $str;

	} else {
		echo '<p>There\'s nothing in your cart.</p>';
		echo '<p><a href="' . get_bloginfo( 'url' ) . '/dispensary-menu/" class="button wpd-cart return">Return to Menu</a></p>';
	}
}
add_shortcode( 'wpd_cart', 'wpd_shopping_cart_shortcode' );
