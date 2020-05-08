<?php
/**
 * WP Dispensary eCommerce item class
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

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

				// Set concentrate price to NULL to avoid `Notice`.
				$concentrate_prices = NULL;
				// Set concentrate price to NULL to avoid `Notice`.
				$concentrate_price_choice = NULL;
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

				// Set flower price to NULL to avoid `Notice`.
				$flower_price_choice = NULL;
				// Set concentrate price to NULL to avoid `Notice`.
				$concentrate_prices = NULL;
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

				// Set flower price to NULL to avoid `Notice`.
				$flower_price_choice = NULL;
				// Set concentrate price to NULL to avoid `Notice`.
				$concentrate_price_choice = NULL;
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
			$concentrate_prices   = '';
		} elseif ( 'topicals' === get_post_type( $my_post->ID ) ) {
			$regular_price        = esc_html( get_post_meta( $my_post->ID, '_pricetopical', true ) );
			$pack_price           = esc_html( get_post_meta( $my_post->ID, '_priceperpack', true ) );
			$flower_prices        = '';
			$concentrate_prices   = '';
		} elseif ( 'flowers' === get_post_type( $my_post->ID ) ) {
			$regular_price        = '';
			$pack_price           = '';
			$flower_prices        = wpd_flowers_prices_array( $my_post->ID );
			$product_price_choice = '';
			$concentrate_prices   = '';
		} elseif ( 'concentrates' === get_post_type( $my_post->ID ) ) {
			$regular_price        = esc_html( get_post_meta( $my_post->ID, '_priceeach', true ) );
			$pack_price           = '';
			$flower_prices        = '';
			$product_price_choice = '';
			$concentrate_prices   = wpd_concentrates_prices_array( $my_post->ID );
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
