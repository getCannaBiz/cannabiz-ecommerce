<?php

/**
 * The file that defines the custom functions and filters
 *
 * @link       https://www.wpdispensary.com
 * @since      1.2
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/admin
 */

/**
 * Add heavyweights to Flowers products.
 * 
 * @since 1.2
 */
function wpd_heavyweights_weights_array( $flowers_weights ) {
    $flowers_weights['2 oz']   = '_twoounces';
    $flowers_weights['1/4 lb'] = '_quarterpound';
    $flowers_weights['1/2 lb'] = '_halfpound';
    $flowers_weights['1 lb']   = '_onepound';
    $flowers_weights['2 lbs']  = '_twopounds';
    $flowers_weights['3 lbs']  = '_threepounds';
    $flowers_weights['4 lbs']  = '_fourpounds';
    $flowers_weights['5 lbs']  = '_fivepounds';
    $flowers_weights['6 lbs']  = '_sixpounds';
    $flowers_weights['7 lbs']  = '_sevenpounds';
    $flowers_weights['8 lbs']  = '_eightpounds';
    $flowers_weights['9 lbs']  = '_ninepounds';
    $flowers_weights['10 lbs'] = '_tenpounds';
    $flowers_weights['11 lbs'] = '_elevenpounds';
    $flowers_weights['12 lbs'] = '_twelvepounds';
    $flowers_weights['13 lbs'] = '_thirteenpounds';
    $flowers_weights['14 lbs'] = '_fourteenpounds';
    $flowers_weights['15 lbs'] = '_fifteenpounds';
    $flowers_weights['20 lbs'] = '_twentypounds';
    $flowers_weights['25 lbs'] = '_twentyfivepounds';
    $flowers_weights['50 lbs'] = '_fiftypounds';

    return  apply_filters( 'wpd_heavyweights_flowers_weights', $flowers_weights );
}
add_filter( 'wpd_flowers_weights_array', 'wpd_heavyweights_weights_array' );

/**
 * Add heavyweights to Concentrates products.
 * 
 * @since 1.3
 */
function wpd_heavyweights_concentrates_weights_array( $concentrates_weights ) {
    $concentrates_weights['3 g']  = '_threegrams';
    $concentrates_weights['4 g']  = '_fourgrams';
    $concentrates_weights['5 g']  = '_fivegrams';
    $concentrates_weights['6 g']  = '_sixgrams';
    $concentrates_weights['7 g']  = '_sevengrams';
    $concentrates_weights['8 g']  = '_eightgrams';
    $concentrates_weights['9 g']  = '_ninegrams';
    $concentrates_weights['10 g'] = '_tengrams';

    return  apply_filters( 'wpd_heavyweights_concentrates_weights', $concentrates_weights );

}
add_filter( 'wpd_concentrates_weights_array', 'wpd_heavyweights_concentrates_weights_array' );

/**
 * Add heavyweights to flowers prices low.
 * 
 * @since 1.3
 */
function wpd_heavyweights_flowers_weights_low() {

    // Currency code.
	$currency_code = wpd_currency_code();

	$pricinglow = '';

    if ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_gram', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_two_grams', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_eighth', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_eighth', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_five_grams', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_five_grams', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_quarter_ounce', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_quarter_ounce', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_half_ounce', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_half_ounce', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_ounce', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), 'price_ounce', true );
	} elseif ( get_post_meta( get_the_ID(), '_twoounces', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_twoounces', true );
	} elseif ( get_post_meta( get_the_ID(), '_threeounces', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_threeounces', true );
	} elseif ( get_post_meta( get_the_ID(), '_quarterpound', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_quarterpound', true );
	} elseif ( get_post_meta( get_the_ID(), '_halfpound', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_halfpound', true );
	} elseif ( get_post_meta( get_the_ID(), '_onepound', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_onepound', true );
	} elseif ( get_post_meta( get_the_ID(), '_twopounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_twopounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_threepounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_threepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fourpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_fourpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fivepounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_fivepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_sixpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_sixpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_sevenpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_sevenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_eightpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_eightpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_ninepounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_ninepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_tenpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_tenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_elevenpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_elevenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twelvepounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_twelvepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_thirteenpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_thirteenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fourteenpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_fourteenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fifteenpounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_fifteenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twentypounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_twentypounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twentyfivepounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_twentyfivepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fiftypounds', true ) ) {
		$pricinglow = get_post_meta( get_the_ID(), '_fiftypounds', true );
	} else {
        // Do nothing.
    }

	setlocale( LC_MONETARY, 'en_US' );

	$pricinglow = $currency_code . str_ireplace( '.00', '', money_format( '%!n', $pricinglow ) );

	return $pricinglow;
}
add_filter( 'wpd_flowers_pricing_low', 'wpd_heavyweights_flowers_weights_low' );

/**
 * Add heavyweights to flowers prices high.
 * 
 * @since 1.3
 */
function wpd_heavyweights_flowers_weights_high() {

	/**
	 * Set locale for money format.
	 * 
	 * @todo change this based on the choice in the WPD Settings.
	 * @todo figure out if this should be used throughout ALL plugin spots, not just the High number for flowers.
	 */
	setlocale( LC_MONETARY, 'en_US' );

    // Currency code.
	$currency_code = wpd_currency_code();

	$pricinghigh = '';

    if ( get_post_meta( get_the_ID(), '_fiftypounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_fiftypounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twentyfivepounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_twentyfivepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twentypounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_twentypounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fifteenpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_fifteenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fourteenpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_fourteenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_thirteenpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_thirteenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twelvepounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_twelvepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_elevenpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_elevenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_tenpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_tenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_ninepounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_ninepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_eightpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_eightpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_sevenpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_sevenpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_sixpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_sixpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fivepounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_fivepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_fourpounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_fourpounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_threepounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_threepounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_twopounds', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_twopounds', true );
	} elseif ( get_post_meta( get_the_ID(), '_onepound', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_onepound', true );
	} elseif ( get_post_meta( get_the_ID(), '_halfpound', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_halfpound', true );
	} elseif ( get_post_meta( get_the_ID(), '_quarterpound', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_quarterpound', true );
	} elseif ( get_post_meta( get_the_ID(), '_threeounces', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_threeounces', true );
	} elseif ( get_post_meta( get_the_ID(), '_twoounces', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), '_twoounces', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_ounce', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_ounce', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_half_ounce', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_half_ounce', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_quarter_ounce', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_quarter_ounce', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_five_grams', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_five_grams', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_eighth', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_eigth', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_two_grams', true );
	} elseif ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
		$pricinghigh = get_post_meta( get_the_ID(), 'price_gram', true );
	} else {
        // Do nothing.
	}

	$pricinghigh = wpd_currency_code() . str_ireplace( '.00', '', money_format( '%!n', $pricinghigh ) );

    return $pricinghigh;
}
add_filter( 'wpd_flowers_pricing_high', 'wpd_heavyweights_flowers_weights_high' );

/**
 * Add heavyweights to concentrates prices low.
 * 
 * @since 1.3
 */
function wpd_heavyweights_concentrates_weights_low() {

    // Currency code.
	$currency_code = wpd_currency_code();

	$pricinglow = '';

    if ( get_post_meta( get_the_ID(), 'price_half_gram', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), 'price_half_gram', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), 'price_gram', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), 'price_two_grams', true );
    } elseif ( get_post_meta( get_the_ID(), '_threegrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_threegrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_fourgrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_fourgrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_fivegrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_fivegrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_sixgrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_sixgrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_sevengrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_sevengrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_eightgrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_eightgrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_ninegrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_ninegrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_tengrams', true ) ) {
        $pricinglow = $currency_code . get_post_meta( get_the_ID(), '_tengrams', true );
    } else {
        // Do nothing.
    }

    return $pricinglow;
}
add_filter( 'wpd_concentrates_pricing_low', 'wpd_heavyweights_concentrates_weights_low' );

/**
 * Add heavyweights to concentrates prices high.
 * 
 * @since 1.3
 */
function wpd_heavyweights_concentrates_weights_high() {

    // Currency code.
    $currency_code = wpd_currency_code();

	$pricinghigh = '';

    if ( get_post_meta( get_the_ID(), '_tengrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_tengrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_ninegrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_ninegrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_eightgrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_eightgrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_sevengrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_sevengrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_sixgrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_sixgrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_fivegrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_fivegrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_fourgrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_fourgrams', true );
    } elseif ( get_post_meta( get_the_ID(), '_threegrams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), '_threegrams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), 'price_two_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
        $pricinghigh = $currency_code . get_post_meta( get_the_ID(), 'price_gram', true );
    } else {
        // Do nothing.
    }

    return $pricinghigh;
}
add_filter( 'wpd_concentrates_pricing_high', 'wpd_heavyweights_concentrates_weights_high' );
