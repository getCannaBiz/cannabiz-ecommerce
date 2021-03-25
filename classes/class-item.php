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

		$item_ids = array();

        if ( isset( $_SESSION['wpd_ecommerce'] ) ) {
			// Create array of item ID's.
            foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$value ) {
                $item_ids[] = $id;
            }

			// Loop through items ID's.
            foreach( $item_ids as $item=>$value ) {
				// item ID's match.
                if ( $value == $item_id ) {
					// Set the product price.
                    $product_price = get_post_meta( $item_old_id, $item_meta_key, true );
                }
            }
        } else {
			// Do something.
		}

		// Get prices.
		if ( in_array( get_post_meta( $my_post->ID, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {
			$regular_price        = esc_html( get_post_meta( $my_post->ID, 'price_each', true ) );
			$pack_price           = esc_html( get_post_meta( $my_post->ID, 'price_per_pack', true ) );
			$flower_prices        = '';
			$concentrate_prices   = '';
		} elseif ( 'flowers' === get_post_meta( $my_post->ID, 'product_type', true ) ) {
			$regular_price        = '';
			$pack_price           = '';
			$flower_prices        = wpd_flowers_prices_array( $my_post->ID );
		} elseif ( 'concentrates' === get_post_meta( $my_post->ID, 'product_type', true ) ) {
			$regular_price        = esc_html( get_post_meta( $my_post->ID, 'price_each', true ) );
			$pack_price           = '';
			$concentrate_prices   = wpd_concentrates_prices_array( $my_post->ID );
		} else {
			// Do nothing.
		}

		// Create.
		$this->id         = $item_id;
		$this->product_id = $item_old_id;
		$this->title      = $my_post->post_title;
		$this->permalink  = get_the_permalink( $item_id );
		$this->thumbnail  = get_the_post_thumbnail( $item_id, array( 30, 30 ), '' );
		// Set prices.
		$this->price              = $regular_price;
		$this->price_per_pack     = $pack_price;
		$this->price_quantity     = $product_price;
		$this->price_flowers      = $flower_prices;
		$this->price_concentrates = $concentrate_prices;
 	}

}
