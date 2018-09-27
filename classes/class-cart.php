<?php
/**
 * Class CART
 */
class Cart {
	
	//Total cart cost
	var $sum;
	//Order Lines - Counts the lines in the cart
	var $lines;
	//Item count - Counts single items in the cart
	var $items; 
	//The cart lines themselves
	var $item_array;
	//The vat calculation
	var $vat;
	
	
	function __construct() {		
		$this->sum        = 0;
		$this->vat        = 0;
		$this->lines      = 0;
		$this->items      = 0;
		$this->item_array = array();		
	}
	
	private $method = '_POST';

	/*
	 * 	The function gets an item id (which is a post id) and 
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
		if ( isset ( ${$this->method}['wpd_ecommerce_flowers_prices'] ) ) {
			$new_id = $item_id . '' . ${$this->method}['wpd_ecommerce_flowers_prices'];
		} else {
			$new_id = $item_id;
		}
		$this->items -= $this->item_array[$new_id];
		unset( $this->item_array["$new_id"] );
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

			if ( 'edibles' === get_post_type( $i->product_id ) ) {
				$regular_price  = esc_html( get_post_meta( $i->product_id, '_priceeach', true ) );
			} elseif ( 'topicals' === get_post_type( $i->product_id ) ) {
				$regular_price = esc_html( get_post_meta( $i->product_id, '_pricetopical', true ) );
			} elseif ( 'prerolls' === get_post_type( $i->product_id ) ) {
				$regular_price = esc_html( get_post_meta( $i->product_id, '_priceeach', true ) );
			} elseif ( 'tinctures' === get_post_type( $i->product_id ) ) {
				$regular_price = esc_html( get_post_meta( $i->product_id, '_priceeach', true ) );
			} elseif ( 'gear' === get_post_type( $i->product_id ) ) {
				$regular_price = esc_html( get_post_meta( $i->product_id, '_priceeach', true ) );
			} elseif ( 'growers' === get_post_type( $i->product_id ) ) {
				$regular_price = esc_html( get_post_meta( $i->product_id, '_priceeach', true ) );
			} elseif ( 'flowers' === get_post_type( $i->product_id ) ) {
				$regular_price = $i->price_flower;
			} else {
				$regular_price = '';
			}

			print_r( $i );

			$this->sum += ( $regular_price ) * $item_count;
		endforeach;
		$this->calculate_vat();
	}
	
	/**
	 * Calculate VAT
	 */
	function calculate_vat() {
		$this->vat = ( $this->sum ) * VAT;
	}
	
	/**
	 * String
	 */
	public function __toString() {
		$str = '<table class="wpd-cart">';

		//$str .= '<thead><tr><td>Product</td><td>Price</td><td>Qty</td></tr></thead>';
		$str .= '<tbody>';
		foreach( $this->item_array as $id=>$amount ):
			$i    = new Item( $id, '', '', '' );
			if ( 'edibles' === get_post_type( $i->id ) ) {
				$regular_price  = esc_html( get_post_meta( $i->id, '_priceeach', true ) );
			} elseif ( 'topicals' === get_post_type( $i->id ) ) {
				$regular_price = esc_html( get_post_meta( $i->id, '_pricetopical', true ) );
			} elseif ( 'prerolls' === get_post_type( $i->id ) ) {
				$regular_price = esc_html( get_post_meta( $i->id, '_priceeach', true ) );
			} elseif ( 'tinctures' === get_post_type( $i->id ) ) {
				$regular_price = esc_html( get_post_meta( $i->id, '_priceeach', true ) );
			} elseif ( 'gear' === get_post_type( $i->id ) ) {
				$regular_price = esc_html( get_post_meta( $i->id, '_priceeach', true ) );
			} elseif ( 'growers' === get_post_type( $i->id ) ) {
				$regular_price = esc_html( get_post_meta( $i->id, '_priceeach', true ) );
			} else {
				$regular_price = '';
			}
			$str .=	"<tr><td><a href='" . $i->permalink . "'>" . $i->title . "</a></td><td>" . CURRENCY . $regular_price . "</td><td>" . $amount . "</td></tr>";
		endforeach;
		$str .= "</tbody>";
		$str .= "</table>";

		$str .= "<ul id='cart_totals'>";
		$str .= "<li class='cart_sum'>Sum: " . CURRENCY . $this->sum . "</li>";
		$str .= "<li class='cart_vat'>Vat: " . CURRENCY . $this->vat . "</li>";
		$str .= "<li class='cart_total'><span class='caption'>Total:</span>". CURRENCY . ( $this->vat + $this->sum ) . "</li>";
		$str .= "</ul>";

		return $str;	
	}

	/**
	 * Display shopping cart on CART page.
	 */
	public function wpd_ecommerce_page() {
		$str = '<table class="wpd-cart">';

		$str .= '<thead><tr><td>Product</td><td>Price</td><td>Qty</td></tr></thead>';
		$str .= '<tbody>';
		foreach( $this->item_array as $id=>$amount ):
			$i    = new Item( $id, '', '', '' );
			if ( in_array( get_post_type( $i->id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
				$regular_price  = esc_html( get_post_meta( $i->id, '_priceeach', true ) );
			} elseif ( 'topicals' === get_post_type() ) {
				$regular_price = esc_html( get_post_meta( $i->id, '_pricetopical', true ) );
			} elseif ( 'flowers' === get_post_type() ) {
				$regular_price = $i->price_flower;
			} else {
				$regular_price = '';
			}
			$str .=	"<tr><td><a href='" . $i->permalink . "'>" . $i->title . "</a></td><td>" . CURRENCY . $regular_price . "</td><td>" . $amount . "</td></tr>";
		endforeach;
		$str .= "</tbody>";
		$str .= "</table>";

		$str .= "<ul id='cart_totals'>";
		$str .= "<li class='cart_sum'>Sum: " . CURRENCY . $this->sum . "</li>";
		$str .= "<li class='cart_vat'>Vat: " . CURRENCY . $this->vat . "</li>";
		$str .= "<li class='cart_total'><span class='caption'>Total:</span>". CURRENCY . ( $this->vat + $this->sum ) . "</li>";
		$str .= "</ul>";

		return $str;
	}
}
/*
$c = new Cart();
print_r($c);
$c->add_item(32,2);
$c->add_item(32,2);
print_r($c);
$c->update_item(32,5);
print_r($c);

echo (string)$c;
*/
