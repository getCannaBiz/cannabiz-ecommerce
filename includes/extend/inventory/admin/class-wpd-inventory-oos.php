<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 */

/**
 * Out of Stock message.
 *
 * @param string $content
 * @return string
 */
function wpd_out_of_stock_content( $content ) {
	global $post;

	// Set default content.
	$default_content = $content;

	/**
	 * Defining variables
	 */
	$original  = '';
	$post_type = get_post_type_object( get_post_type( $post ) );

	/**
	 * Adding the WP Dispensary out of stock data
	 */
	if ( in_array( get_post_type(), wpd_menu_types_simple( TRUE ) ) ) {
		$original = $content;
	} else {
		$original = '';
	}

	if ( in_array( get_post_type(), wpd_menu_types_simple( TRUE ) ) ) {
		$content = '';
	} else {
		$content = $content;
	}

	// Flowers.
	if ( is_singular( 'flowers' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_flowers', true ) ) {
			$content .= '<div class="wpd-inventory flowers warning">';
			$content .= '<p>' . __( 'Sorry, this flower is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	// Edibles.
	if ( is_singular( 'edibles' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_edibles', true ) ) {
			$content .= '<div class="wpd-inventory edibles warning">';
			$content .= '<p>' . __( 'Sorry, this edible is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	// Concentrates.
	if ( is_singular( 'concentrates' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_concentrates', true ) && ! get_post_meta( get_the_ID(), '_inventory_concentrates_each', true ) ) {
			$content .= '<div class="wpd-inventory concentrates warning">';
			$content .= '<p>' . __( 'Sorry, this concentrate is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	// Pre-rolls.
	if ( is_singular( 'prerolls' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_prerolls', true ) ) {
			$content .= '<div class="wpd-inventory prerolls warning">';
			$content .= '<p>' . __( 'Sorry, this pre-roll is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	// Topicals.
	if ( is_singular( 'topicals' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_topicals', true ) ) {
			$content .= '<div class="wpd-inventory topicals warning">';
			$content .= '<p>' . __( 'Sorry, this topical is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	// Growers.
	if ( is_singular( 'growers' ) ) {
		// Out of stock message.
		if ( get_post_meta( get_the_ID(), '_clonecount', true ) ) {
			if ( ! get_post_meta( get_the_ID(), '_inventory_clones', true ) ) {
				$content .= '<div class="wpd-inventory growers clones warning">';
				$content .= '<p>' . __( 'Sorry, this clone is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
				$content .= '</div>';
			}
		} elseif ( get_post_meta( get_the_ID(), '_seedcount', true ) ) {
			if ( ! get_post_meta( get_the_ID(), '_inventory_seeds', true ) ) {
				$content .= '<div class="wpd-inventory growers seeds warning">';
				$content .= '<p>' . __( 'Sorry, this seed is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
				$content .= '</div>';
			}
		}
	}

	// Tinctures.
	if ( is_singular( 'tinctures' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_tinctures', true ) ) {
			$content .= '<div class="wpd-inventory tinctures warning">';
			$content .= '<p>' . __( 'Sorry, this tincture is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	// Gear.
	if ( is_singular( 'gear' ) ) {
		// Out of stock message.
		if ( ! get_post_meta( get_the_ID(), '_inventory_gear', true ) ) {
			$content .= '<div class="wpd-inventory gear warning">';
			$content .= '<p>' . __( 'Sorry, this gear is currently out of stock.', 'wpd-ecommerce' ) . '</p>';
			$content .= '</div>';
		}
	}

	/**
	 * Display the out of stock message
	 */
	$content .= $original;

	// eCommerce plugin check.
	if ( ! is_plugin_active( 'wpd-ecommerce/wpd-ecommerce.php' ) ) {
		$content = $content;
	} else {
		$content = $default_content;
	}

	return $content;
}
add_filter( 'the_content', 'wpd_out_of_stock_content' );
