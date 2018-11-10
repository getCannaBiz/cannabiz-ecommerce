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

		// Get item details.
		$item_old_id   = preg_replace( '/\D/', '', $item_id );
		$my_post       = get_post( $item_old_id );
		$item_meta_key = preg_replace( '/[0-9]+/', '', $item_id );

		$item_names = array();

		if ( empty( $new_price ) ) {

			if ( 'flowers' === get_post_type( $item_old_id ) ) {
				if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
					foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ) {
						$item_names[] = $id;
					}

					foreach( $item_names as $item=>$value ) {
						if ( $value == $item_id ) {
							$flower_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
						}
					}
				} else {
					$flower_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
				}
			} elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
				if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
					foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ) {
						$item_names[] = $id;
					}

					foreach( $item_names as $item=>$value ) {
						if ( $value == $item_id ) {
							$concentrate_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
						}
					}
				} else {
					$concentrate_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
				}
			} else {
				if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
					foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ) {
						$item_names[] = $id;
					}

					foreach( $item_names as $item=>$value ) {
						if ( $value == $item_id ) {
							$product_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
						}
					}
				} else {
					$product_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
				}
			}

		} else {

			foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ) {
				$item_names[] = $id;
			}

			if ( 'flowers' === get_post_type( $item_old_id ) ) {
				foreach( $item_names as $item=>$value ) {
					if ( $value == $item_id ) {
						$flower_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
					}
				}
			} elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
				foreach( $item_names as $item=>$value ) {
					if ( $value == $item_id ) {
						$concentrate_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
					}
				}
			} else {
				foreach( $item_names as $item=>$value ) {
					if ( $value == $item_id ) {
						$product_price_choice = get_post_meta( $item_old_id, $item_meta_key, true );
					}
				}
			}
		}

		// Get prices.
		if ( in_array( get_post_type( $my_post->ID ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
			$regular_price        = esc_html( get_post_meta( $my_post->ID, '_priceeach', true ) );
			$pack_price           = esc_html( get_post_meta( $my_post->ID, '_priceperpack', true ) );
			$flower_prices        = '';
		} elseif ( 'topicals' === get_post_type( $my_post->ID ) ) {
			$regular_price        = esc_html( get_post_meta( $my_post->ID, '_pricetopical', true ) );
			$pack_price           = esc_html( get_post_meta( $my_post->ID, '_priceperpack', true ) );
			$flower_prices        = '';
		} elseif ( 'flowers' === get_post_type( $my_post->ID ) ) {
			$regular_price = '';
			$pack_price    = '';
			$flower_prices = array(
				'1 g'    => esc_html( get_post_meta( $my_post->ID, '_gram', true ) ),
				'2 g'    => esc_html( get_post_meta( $my_post->ID, '_twograms', true ) ),
				'1/8 oz' => esc_html( get_post_meta( $my_post->ID, '_eighth', true ) ),
				'5 g'    => esc_html( get_post_meta( $my_post->ID, '_fivegrams', true ) ),
				'1/4 oz' => esc_html( get_post_meta( $my_post->ID, '_quarter', true ) ),
				'1/2 oz' => esc_html( get_post_meta( $my_post->ID, '_halfounce', true ) ),
				'1 oz'   => esc_html( get_post_meta( $my_post->ID, '_ounce', true ) ),
			);
			$product_price_choice = '';
		} elseif ( 'concentrates' === get_post_type( $my_post->ID ) ) {
			$regular_price      = esc_html( get_post_meta( $my_post->ID, '_priceeach', true ) );
			$pack_price         = '';
			$concentrate_prices = array(
				'1/2 g' => esc_html( get_post_meta( $my_post->ID, '_halfgram', true ) ),
				'1 g'   => esc_html( get_post_meta( $my_post->ID, '_gram', true ) ),
				'2 g'   => esc_html( get_post_meta( $my_post->ID, '_twograms', true ) ),
			);
		} else {
			// Do nothing.
		}

		// Create.
		$this->id             = $item_id;
		$this->product_id     = $item_old_id;
		$this->title          = $my_post->post_title;
		$this->permalink      = get_the_permalink( $item_id );

		$this->price              = $regular_price;
		$this->price_per_pack     = $pack_price;
		$this->price_flowers      = $flower_prices;
		$this->price_flower       = $flower_price_choice;
		$this->price_concentrates = $concentrate_prices;
		$this->price_concentrate  = $concentrate_price_choice;
		$this->price_quantity     = $product_price_choice;
		$this->thumbnail          = get_the_post_thumbnail( $item_id, array( 30, 30 ), '' );
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
