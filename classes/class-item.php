<?php
/**
 * Class Item
 */
class Item {

	var $id;
	var $price;
	var $title;

    private $method = '_POST';

	public function __construct( $item_id, $old_id, $new_price, $old_price ) {

		// Get item.
		$item_old_id = preg_replace( '/[^0-9.]+/', '', $item_id );
		$my_post     = get_post( $item_old_id );

		if ( empty( $new_price ) ) {

			$item_names = array();

			if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
				foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ) {
					$item_names[] = $id;
				}

				foreach( $item_names as $item=>$value ) {
					if ( $value == $item_id ) {
						$weight_option2 = preg_replace( '/[0-9]+/', '', $item_id );
						$flower_price_choice = get_post_meta( $item_old_id, $weight_option2, true );
					}
				}
			} else {
				$flower_price_choice = '';
			}

		} else {

			$item_names = array();

			foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ) {
				$item_names[] = $id;
			}

			foreach( $item_names as $item=>$value ) {
				if ( $value == $item_id ) {
					$weight_option = preg_replace('/[0-9]+/', '', $item_id );
					//echo $weight_option;
					$flower_price_choice = get_post_meta( $item_old_id, $weight_option, true );
				}
			}

		}

		// Get prices.
		if ( in_array( get_post_type( $my_post->ID ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
			$regular_price  = esc_html( get_post_meta( $my_post->ID, '_priceeach', true ) );
			$flower_prices = '';
		} elseif ( 'topicals' === get_post_type( $my_post->ID ) ) {
			$regular_price = esc_html( get_post_meta( $my_post->ID, '_pricetopical', true ) );
			$flower_prices = '';
		} elseif ( 'flowers' === get_post_type( $my_post->ID ) ) {
			$regular_price = '';
			$flower_prices = array(
				'1 g'    => esc_html( get_post_meta( $my_post->ID, '_gram', true ) ),
				'1/8 oz' => esc_html( get_post_meta( $my_post->ID, '_eighth', true ) ),
				'1/4 oz' => esc_html( get_post_meta( $my_post->ID, '_quarter', true ) ),
				'1/2 oz' => esc_html( get_post_meta( $my_post->ID, '_halfounce', true ) ),
				'1 oz'   => esc_html( get_post_meta( $my_post->ID, '_ounce', true ) ),
			);

		} else {
			$regular_price = '';
			$flower_prices = '';
		}

		// Create.
		$this->id            = $item_id;
		$this->product_id    = $item_old_id;
		$this->title         = $my_post->post_title;
		$this->permalink     = get_the_permalink( $item_id );
		$this->price         = $regular_price;
		$this->price_flowers = $flower_prices;
		$this->price_flower  = $flower_price_choice;
		$this->thumbnail     = get_the_post_thumbnail( $item_id, array( 30, 30 ), '' );
 	}

	/*
	public function __toString() {
		$str = '<ul class="item_box">';
		$str .= '<li>Item ID: ' . $this->id . '</li>';		
		$str .= '<li>Item name: ' . $this->title . '</li>';
		$str .= '<li>Item Price: ' . $this->price . '</li>';					
		$str .= '<li>Permalink: ' . $this->permalink . '</li>';					
		$str .= '</ul>';
		return $str;
	}
	*/

}
