<?php
/**
 * WP Dispensary eCommerce cart class
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

class Cart {
	
	//Total cart cost
	var $sum;
	//Order Lines - Counts the lines in the cart
	var $lines;
	//Item count - Counts single items in the cart
	var $items; 
	//The cart lines themselves
	var $item_array;
	//The payment type calculation
	var $payment_type_amount;
	//The sales calculation
	var $sales_tax;
	//The excise calculation
	var $excise_tax;	
	
	function __construct() {		
		$this->sum                 = 0;
		$this->payment_type_amount = 0;
		$this->sales_tax           = 0;
		$this->excise_tax          = 0;
		$this->coupon_code         = 0;
		$this->coupon_amount       = 0;
		$this->lines               = 0;
		$this->items               = 0;
		$this->item_array          = array();		
	}
	
	private $method = '_POST';

	/**
	 * Update subtotal with coupon.
	 */
	function add_coupon( $coupon_code, $coupon_amount, $coupon_type, $coupon_exp ) {
		$this->coupon_code = $coupon_code;
		if ( 'Flat Rate' == $coupon_type ) {
			$coupon_total = $coupon_amount;
		} elseif ( 'Percentage' == $coupon_type ) {
			$coupon_percentage = $coupon_amount * 0.01;
			$coupon_total      = $this->sum * $coupon_percentage;
		}
		$this->coupon_amount = $coupon_total;
		$this->sum           = $this->sum - $coupon_amount;
	}

	/**
	 * Update subtotal when removing coupon.
	 */
	function remove_coupon( $coupon_code, $coupon_amount, $coupon_type, $coupon_exp ) {
		$this->coupon_code   = 0;
		$this->coupon_amount = 0;
		$this->sum           = $this->sum + $coupon_amount;
	}

	/*
	 * 	The function gets an item id and 
	 * 	the amounts of items and adds it to the cart
	 */ 
	function add_item( $item_id, $count, $old_id, $new_price, $old_price ) {
		if ( isset( $this->item_array["$item_id"] ) ) {
			$this->update_item( $item_id, $count, $old_id, $new_price, $old_price );
		} else {
			$this->item_array["$item_id"] = $count;
			$this->lines++;
			$this->items += $count;
		}
		$this->calculate_cart_sum();
	}

	/**
	 * Remove Item
	 */
	function remove_item( $item_id ) {
		$this->items -= $this->item_array[$item_id];
		unset( $this->item_array["$item_id"] );
		$this->lines--;
		$this->calculate_cart_sum();
	}

	/**
	 * Update Item
	 */
	function update_item( $item_id, $diff, $old_id, $new_price, $old_price ) {
		if( $this->item_array[$item_id] + $diff == 0 ):
			$this->remove_item( $item_id );
		elseif( $this->item_array[$item_id] + $diff < 0 ):
			throw new Exception( 'You can not Substract more than you have' );
		else:
			$this->item_array["$item_id"] += $diff;
			$this->items += $diff;
		endif;
		$this->calculate_cart_sum();
	}

	/**
	 * Calculate Cart Sum
	 */
	function calculate_cart_sum() {
		$this->sum = 0;

		foreach( $this->item_array as $item_id=>$item_count ):

			$i = new Item( $item_id, '', '', '', '' );

			$regular_price = $i->price_quantity;

			if ( ! empty( $regular_price ) ) {
				$this->sum += ( $regular_price ) * $item_count;
			}

		endforeach;

		// Calculate sales tax.
		if ( defined( 'SALES_TAX' ) ) {
			$this->calculate_sales_tax();
		}
		// Calculate excise tax.
		if ( defined( 'EXCISE_TAX' ) ) {
			$this->calculate_excise_tax();
		}
		// Calculate payment type amount.
		if ( defined( 'PAYMENT_TYPE_AMOUNT' ) ) {
			$this->calculate_payment_type_amount();
		}
	}

	/**
	 * Calculate payment type amount.
	 */
	function calculate_payment_type_amount() {
		$this->payment_type_amount = PAYMENT_TYPE_AMOUNT;
	}

	/**
	 * Calculate sales tax.
	 */
	function calculate_sales_tax() {
		$this->sales_tax = ( $this->sum ) * SALES_TAX;
	}

	/**
	 * Calculate excise tax.
	 */
	function calculate_excise_tax() {
		$this->excise_tax = ( $this->sum ) * EXCISE_TAX;
	}

	/**
	 * String
	 */
	public function __toString() {
		$str = '<table class="wpd-ecommerce">';

		//$str .= '<thead><tr><td>Product</td><td>Price</td><td>Qty</td></tr></thead>';
		$str .= '<tbody>';
		foreach( $this->item_array as $id=>$amount ):
			// Get Item data.
			$i = new Item( $id, '', '', '' );
			// Price each.
			$product_price = esc_html( get_post_meta( $i->id, 'price_each', true ) );
			// Price per pack.
			$price_per_pack = esc_html( get_post_meta( $i->id, 'price_per_pack', true ) );
			// Update product price.
			if ( $price_per_pack ) {
				$product_price = $price_per_pack;
			}
			// Add item to table.
			$str .=	'<tr><td><a href="' . $i->permalink . '">' . $i->title . '</a></td><td>' . CURRENCY . $product_price . '</td><td>' . $amount . '</td></tr>';
		endforeach;
		$str .= '</tbody>';
		$str .= '</table>';

		$str .= '<ul id="cart_totals">';
		$str .= '<li class="cart_sum">Sum: ' . CURRENCY . $this->sum . '</li>';
		if ( ! defined( 'EXCISE_TAX' ) ) {
			// Do nothing.
		} else {
			$str .= '<li class="cart_sales_tax">Sales Tax: ' . CURRENCY . $this->sales_tax . '</li>';
		}
		if ( defined( 'EXCISE_TAX' ) ) {
			$str .= '<li class="cart_excise_tax">Excise Tax: ' . CURRENCY . $this->excise_tax . '</li>';
		}
		$str .= '<li class="cart_total"><span class="caption">Total:</span>' . CURRENCY . ( $this->sales_tax + $this->excise_tax + $this->payment_type_amount + $this->sum ) . '</li>';
		$str .= '</ul>';

		return $str;	
	}

	/**
	 * Display shopping cart on CART page.
	 */
	public function wpd_ecommerce_page() {
		$str = '<table class="wpd-ecommerce">';

		$str .= '<thead><tr><td>' . __( 'Product', 'wpd-ecommerce' ) . '</td><td>' . __( 'Price', 'wpd-ecommerce' ) . '</td><td>' . __( 'Qty', 'wpd-ecommerce' ) . '</td></tr></thead>';
		$str .= '<tbody>';
		foreach( $this->item_array as $id=>$amount ):
			$i = new Item( $id, '', '', '' );
			if ( in_array( get_post_meta( $i->id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {
				$regular_price  = esc_html( get_post_meta( $i->id, 'price_each', true ) );
			} elseif ( 'flowers' === get_post_meta( $i->id, 'product_type', true ) ) {
				$regular_price = $i->price_flower;
			} else {
				$regular_price = '';
			}
			$str .=	'<tr><td><a href="' . $i->permalink . '">' . $i->title . '</a></td><td>' . CURRENCY . $regular_price . '</td><td>' . $amount . '</td></tr>';
		endforeach;
		$str .= '</tbody>';
		$str .= '</table>';

		$str .= '<ul id="cart_totals">';
		$str .= '<li class="cart_sum">Sum: ' . CURRENCY . $this->sum . '</li>';
		$str .= '<li class="cart_sales_tax">' . __( 'Total', 'wpd-ecommerce' ) . ': ' . CURRENCY . $this->sales_tax . '</li>';
		$str .= '<li class="cart_total"><span class="caption">' . __( 'Total', 'wpd-ecommerce' ) . ':</span>' . CURRENCY . ( $this->sales_tax + $this->sum ) . '</li>';
		$str .= '</ul>';

		return $str;
	}
}
