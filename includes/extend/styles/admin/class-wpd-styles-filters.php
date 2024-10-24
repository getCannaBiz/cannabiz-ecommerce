<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cannabizsoftware.com
 * @since      1.0.0
 *
 * @package    WPD_Styles
 * @subpackage WPD_Styles/admin
 */

// Create custom featured image size for the widget.
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'wpd-styles-list-image', 90, 50, true );
}

/**
 * Function to add top stuff to the shortcodes
 * 
 * @return string
 */
function add_wpd_styles_list_categories() {
	global $post;

    // Categories.
    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'flowers', 'edibles', 'concentrates', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {
		$terms      = get_the_terms( $post->ID, 'wpd_categories' );
		$first_term = $terms[0];
		if ( '' != $first_term ) {
			echo '<span class="wpd-styles-category">' . $first_term->name . '</span>';
		} else {
			echo '<span class="wpd-styles-category">&nbsp;</span>';
		}
	}

	echo '<div class="wpd-styles-item-details">'; // STARTS THE DIV WRAP AROUND THE TITLE AND INFO
}

/**
 * Add image to shortcode.
 * 
 * @return string
 */
function add_wpd_styles_list_images() {

	global $post;

	echo '<span class="wpd-styles-list-images">';
	echo get_wpd_product_image( $post->ID, 'wpd-styles-list-image' );
	echo '</span>';

	echo '<div class="wpd-styles-item-details">';
}

/** 
 * Function to add bottom stuff to the shortcodes
 * 
 * @return string
 */
function add_wpd_bottom_stuff() {

	global $post;

    $wpdpriceeach    = '';
    $wpdpriceperunit = '';
    $wpdpriceperpack = '';
    $wpdpricetopical = '';
    $wpdhalfgram     = '';
    $wpdgram         = '';
    $wpdtwograms     = '';
    $wpdeighth       = '';
    $wpdquarter      = '';
    $wpdhalfounce    = '';
    $wpdounce        = '';

	/**
	 * Setting up WP Dispensary menu pricing data
	 */
	if ( get_post_meta( $post->ID, 'price_each', true ) ) {
		$wpdpriceeach = '<div class="wpd-styles-price">'. wpd_currency_code() . get_post_meta( $post->ID, 'price_each', true ) . '<span>each</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_per_unit', true ) ) {
		$wpdpriceperunit = '<div class="wpd-styles-price">'. wpd_currency_code() . get_post_meta( $post->ID, 'price_per_unit', true ) . '<span>per unit</span></div>';
	}

	if ( get_post_meta( get_the_ID(), 'price_per_pack', true ) ) {
		$wpdpriceperpack = '<div class="wpd-styles-price">'. wpd_currency_code() . get_post_meta( $post->ID, 'price_per_pack', true ) . '<span>for ' . get_post_meta( $post->ID, 'units_per_pack', true ) . '</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_each', true ) ) {
		$wpdpricetopical = '<div class="wpd-styles-price">'. wpd_currency_code() .'' . get_post_meta( $post->ID, 'price_each', true ) . '<span>each</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_half_gram', true ) ) {
		$wpdhalfgram = '<div class="wpd-styles-price">'. wpd_currency_code() .'' . get_post_meta( $post->ID, 'price_half_gram', true ) .'<span>1/2 g</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_gram', true ) ) {
		$wpdgram = '<div class="wpd-styles-price">'. wpd_currency_code() .'' . get_post_meta( $post->ID, 'price_gram', true ) .'<span>1 g</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_two_grams', true ) ) {
		$wpdtwograms = '<div class="wpd-styles-price">'. wpd_currency_code() .'' . get_post_meta( $post->ID, 'price_two_grams', true ) .'<span>2 g</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_eighth', true ) ) {
		$wpdeighth = '<div class="wpd-styles-price">'. wpd_currency_code() .'' . get_post_meta( $post->ID, 'price_eighth', true ) .'<span>1/8 oz</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_quarter_ounce', true ) ) {
		$wpdquarter = '<div class="wpd-styles-price">'. wpd_currency_code() .'' . get_post_meta( $post->ID, 'price_quarter_ounce', true ) .'<span>1/4 oz</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_half_ounce', true ) ) {
		$wpdhalfounce = '<div class="wpd-styles-price">'. wpd_currency_code() . get_post_meta( $post->ID, 'price_half_ounce', true ) .'<span>1/2 oz</span></div>';
	}

	if ( get_post_meta( $post->ID, 'price_ounce', true ) ) {
		$wpdounce = '<div class="wpd-styles-price">'. wpd_currency_code() . get_post_meta( $post->ID, 'price_ounce', true ) .'<span>1 oz</span></div>';
	}

	// Extra details

	echo '<p>';

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'prerolls' ) ) ) {
		if ( get_post_meta( get_the_ID(), 'product_flowers', true ) ) {
			$prerollflower = get_post_meta( get_the_id(), 'product_flowers', true );
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Flower', 'wpd-styles' ) . ':</strong> <a href='. get_permalink( $prerollflower ) .'>'. get_the_title( $prerollflower ) .'</a></span>';
		} else {
			$wpdpreroll = '';
		}
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'topicals' ) ) ) {
		if ( get_post_meta( get_the_ID(), 'compounds_thc', true ) ) {
			echo '<span class="wpd-productinfo thc"><strong>' . esc_html__( 'THC', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'compounds_thc', true ) .'mg</span>';
		} else {
			$wpdthctopical = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cbd', true ) ) {
			echo '<span class="wpd-productinfo cbd"><strong>' . esc_html__( 'CBD', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'compounds_cbd', true ) .'mg</span>';
		} else {
			$wpdcbdtopical = '';
		}

		if ( get_post_meta( get_the_ID(), 'product_size', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Size', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'product_size', true ) .' (oz)</span>';
		} else {
			$wpdsizetopical = '';
		}
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'growers' ) ) ) {
		if ( get_post_meta( get_the_ID(), 'seed_count', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Seeds per unit', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'seed_count', true ) .'</span>';
		} else {
			$wpdseedcount = '';
		}

		if ( get_post_meta( get_the_ID(), 'clone_count', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Clones per unit', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'clone_count', true ) .'</span>';
		} else {
			$wpdclonecount = '';
		}

		if ( get_post_meta( get_the_ID(), 'product_origin', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Origin', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'product_origin', true ) .'</span>';
		} else {
			$wpdcloneorigin = '';
		}

		if ( get_post_meta( get_the_ID(), 'product_time', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Grow time', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'product_time', true ) .'</span>';
		} else {
			$wpdclonetime = '';
		}

		if ( get_post_meta( get_the_ID(), 'product_flowers', true ) ) {
			$growerflower = get_post_meta( get_the_id(), 'product_flowers', true );
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Flower', 'wpd-styles' ) . ':</strong> <a href='. get_permalink( $growerflower ) .'>'. get_the_title( $growerflower ) .'</a></span>';
		} else {
			$wpdgrower = '';
		}
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'flowers', 'concentrates' ) ) ) {
		if ( get_post_meta( get_the_ID(), 'compounds_thc', true ) ) {
			echo '<span class="wpd-productinfo thc"><strong>' . esc_html__( 'THC', 'wpd-styles' ) . ':</strong>' . get_post_meta( get_the_id(), 'compounds_thc', true ) .'%</span>';
		} else {
			$wpdthc = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cbd', true ) ) {
			echo '<span class="wpd-productinfo cbd"><strong>' . esc_html__( 'CBD', 'wpd-styles' ) . ':</strong>' . get_post_meta( get_the_id(), 'compounds_cbd', true ) .'%</span>';
		} else {
			$wpdcbd = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_thca', true ) ) {
			echo '<span class="wpd-productinfo thca"><strong>' . esc_html__( 'THCA', 'wpd-styles' ) . ':</strong>' . get_post_meta( get_the_id(), 'compounds_thca', true ) .'%</span>';
		} else {
			$wpdthca = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cba', true ) ) {
			echo '<span class="wpd-productinfo cba"><strong>' . esc_html__( 'CBA', 'wpd-styles' ) . ':</strong>' . get_post_meta( get_the_id(), 'compounds_cba', true ) .'%</span>';
		} else {
			$wpdcba = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cbn', true ) ) {
			echo '<span class="wpd-productinfo cbn"><strong>' . esc_html__( 'CBN', 'wpd-styles' ) . ':</strong>' . get_post_meta( get_the_id(), 'compounds_cbn', true ) .'%</span>';
		} else {
			$wpdcbn = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cbg', true ) ) {
			echo '<span class="wpd-productinfo cbg"><strong>' . esc_html__( 'CBG', 'wpd-styles' ) . ':</strong>' . get_post_meta( get_the_id(), 'compounds_cbg', true ) .'%</span>';
		} else {
			$wpdcbg = '';
		}
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'gear' ) ) ) {
		$terms      = get_the_terms( $post->ID, 'wpd_categories' );
		$first_term = $terms[0];
		if ( '' != $first_term ) {
			echo '<span class="wpd-productinfo">' . $first_term->name . '</span>';
		} else {
            // Do nothing.
		}
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'edibles', 'tinctures' ) ) ) {
		if ( get_post_meta( get_the_ID(), 'compounds_thc', true ) ) {
			echo '<span class="wpd-productinfo thc"><strong>' . esc_html__( 'THC', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'compounds_thc', true ) .'mg</span>';
		} else {
            // Do nothing.
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cbd', true ) ) {
			echo '<span class="wpd-productinfo cbd"><strong>' . esc_html__( 'CBD', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'compounds_cbd', true ) .'mg</span>';
		} else {
            // Do nothing.
		}

		if ( get_post_meta( get_the_ID(), 'product_servings', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Servings', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'product_servings', true ) .'</span>';
		} else {
            // Do nothing.
		}

		if ( get_post_meta( get_the_ID(), 'product_net_weight', true ) ) {
			echo '<span class="wpd-productinfo"><strong>' . esc_html__( 'Net weight', 'wpd-styles' ) . ':</strong> ' . get_post_meta( get_the_id(), 'product_net_weight', true ) .'g</span>';
		} else {
            // Do nothing.
		}
	}

	echo '</p>';

	echo '</div>'; // ENDS THE TITLE AND INFO DIV WRAPPER

	// This is the pricing

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'flowers' ) ) ) {
		echo '<div class="wpd-styles-price-wrap">' . $wpdgram . $wpdeighth . $wpdquarter . $wpdhalfounce . $wpdounce . '</div>';
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'concentrates' ) ) ) {
		if ( empty( $wpdpriceeach ) ) {
			echo '<div class="wpd-styles-price-wrap">' . $wpdhalfgram . $wpdgram . $wpdtwograms . '</div>';
		} else {
			echo '<div class="wpd-styles-price-wrap">' . $wpdpriceeach . '</div>';
		}
	}

    if ( in_array( get_post_meta( $post->ID, 'product_type', true ), array( 'gear', 'tinctures', 'growers', 'topicals', 'edibles', 'prerolls' ) ) ) {
		echo '<div class="wpd-styles-price-wrap">'. $wpdpriceeach . $wpdpriceperpack . '</div>';
	}

}

/**
 * Function to hook into shortcode and add new attributes
 */
function wpd_styles_shortcode_menu( $out, $pairs, $atts ) {

	$out['display'] = ''; // new display option

	if ( array_key_exists( 'display', $atts ) && $atts['display'] === 'list' ) {
		$out['class'] = 'display-list'; // added CSS class
		$out['cbd']   = 'hide'; // force CBD showing
		$out['cbn']   = 'hide'; // force CBN showing
		$out['cba']   = 'hide'; // force CBA showing
		$out['thc']   = 'show'; // force THC showing
		$out['thca']  = 'hide'; // force THCA showing
		$out['info']  = 'hide'; // force INFO hiding
		$out['price'] = 'hide'; // force PRICE hiding
		$out['image'] = 'hide'; // force IMAGE hiding

		add_action( 'wpd_shortcode_bottom_menu', 'add_wpd_bottom_stuff' );
		add_action( 'wpd_shortcode_top_menu', 'add_wpd_styles_list_images' );
		//add_action( 'wpd_shortcode_top_menu', 'add_wpd_styles_list_categories' );
		remove_action( 'wpd_shortcode_inside_bottom', 'wpd_styles_wooconnect_shortcode_buttons' ); // removes the Styles buttons
		remove_action( 'wpd_shortcode_inside_bottom', 'wpd_wooconnect_buttons' ); // removes the original Connect for WooCommerce buttons
		remove_action( 'wpd_shortcode_inside_top', 'wpd_topsellers_icon' ); // removes the Top Sellers icon
		remove_action( 'wpd_shortcode_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
	}

  return $out;
}
add_filter( 'shortcode_atts_wpd_menu', 'wpd_styles_shortcode_menu', 10, 3 );
