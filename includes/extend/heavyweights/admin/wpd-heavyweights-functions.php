<?php

/**
 * The file that defines the custom functions and filters
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/admin
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com
 * @since      1.2
 */

/**
 * Add heavyweights to Flowers products.
 * 
 * @param array $flowers_weights 
 * 
 * @since  1.2
 * @return array
 */
function wpd_heavyweights_weights_array( $flowers_weights ) {
    $flowers_weights['2 oz']   = 'price_two_ounces';
    $flowers_weights['1/4 lb'] = 'price_quarter_pound';
    $flowers_weights['1/2 lb'] = 'price_half_pound';
    $flowers_weights['1 lb']   = 'price_one_pound';
    $flowers_weights['2 lbs']  = 'price_two_pounds';
    $flowers_weights['3 lbs']  = 'price_three_pounds';
    $flowers_weights['4 lbs']  = 'price_four_pounds';
    $flowers_weights['5 lbs']  = 'price_five_pounds';
    $flowers_weights['6 lbs']  = 'price_six_pounds';
    $flowers_weights['7 lbs']  = 'price_seven_pounds';
    $flowers_weights['8 lbs']  = 'price_eight_pounds';
    $flowers_weights['9 lbs']  = 'price_nine_pounds';
    $flowers_weights['10 lbs'] = 'price_ten_pounds';
    $flowers_weights['11 lbs'] = 'price_eleven_pounds';
    $flowers_weights['12 lbs'] = 'price_twelve_pounds';
    $flowers_weights['13 lbs'] = 'price_thirteen_pounds';
    $flowers_weights['14 lbs'] = 'price_fourteen_pounds';
    $flowers_weights['15 lbs'] = 'price_fifteen_pounds';
    $flowers_weights['20 lbs'] = 'price_twenty_pounds';
    $flowers_weights['25 lbs'] = 'price_twenty_five_pounds';
    $flowers_weights['50 lbs'] = 'price_fifty_pounds';

    return apply_filters( 'wpd_heavyweights_flowers_weights', $flowers_weights );
}
add_filter( 'wpd_flowers_weights_array', 'wpd_heavyweights_weights_array' );

/**
 * Add heavyweights to Concentrates products.
 * 
 * @param array $concentrates_weights 
 * 
 * @since  1.3
 * @return array
 */
function wpd_heavyweights_concentrates_weights_array( $concentrates_weights ) {
    $concentrates_weights['3 g']  = 'price_three_grams';
    $concentrates_weights['4 g']  = 'price_four_grams';
    $concentrates_weights['5 g']  = 'price_five_grams';
    $concentrates_weights['6 g']  = 'price_six_grams';
    $concentrates_weights['7 g']  = 'price_seven_grams';
    $concentrates_weights['8 g']  = 'price_eight_grams';
    $concentrates_weights['9 g']  = 'price_nine_grams';
    $concentrates_weights['10 g'] = 'price_ten_grams';

    return apply_filters( 'wpd_heavyweights_concentrates_weights', $concentrates_weights );

}
add_filter( 'wpd_concentrates_weights_array', 'wpd_heavyweights_concentrates_weights_array' );

/**
 * Add heavyweights to flowers prices low.
 * 
 * @since  1.3
 * @return void
 */
function wpd_heavyweights_flowers_weights_low() {

    // Currency code.
    $currency_code = wpd_currency_code();

    $pricing_low = '';

    if ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_gram', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_two_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eighth', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_eighth', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_five_grams', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_five_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_quarter_ounce', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_quarter_ounce', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_half_ounce', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_half_ounce', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_ounce', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_ounce', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_ounces', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_two_ounces', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_three_ounces', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_three_ounces', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_quarter_pound', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_quarter_pound', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_half_pound', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_half_pound', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_one_pound', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_one_pound', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_two_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_three_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_three_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_four_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_four_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_five_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_five_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_six_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_six_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_seven_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_seven_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eight_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_eight_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_nine_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_nine_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_ten_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_ten_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eleven_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_eleven_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_twelve_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_twelve_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_thirteen_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_fourteen_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_fifteen_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_twenty_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_twenty_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_fifty_pounds', true ) ) {
        $pricing_low = get_post_meta( get_the_ID(), 'price_fifty_pounds', true );
    } else {
        // Do nothing.
    }

    setlocale( LC_MONETARY, 'en_US' );

    $pricing_low = $currency_code . str_ireplace( '.00', '', number_format( $pricing_low, 2, '.', '' ) );

    return $pricing_low;
}
add_filter( 'wpd_flowers_pricing_low', 'wpd_heavyweights_flowers_weights_low' );

/**
 * Add heavyweights to flowers prices high.
 * 
 * @since  1.3
 * @return string
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

    $pricing_high = '';

  if ( get_post_meta( get_the_ID(), 'price_fifty_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_fifty_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_twenty_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_twenty_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_fifteen_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_fourteen_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_thirteen_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_twelve_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_twelve_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eleven_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_eleven_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_ten_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_ten_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_nine_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_nine_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eight_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_eight_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_seven_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_seven_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_six_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_six_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_five_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_five_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_four_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_four_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_three_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_three_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_pounds', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_two_pounds', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_one_pound', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_one_pound', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_half_pound', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_half_pound', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_quarter_pound', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_quarter_pound', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_three_ounces', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_three_ounces', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_ounces', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_two_ounces', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_ounce', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_ounce', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_half_ounce', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_half_ounce', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_quarter_ounce', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_quarter_ounce', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_five_grams', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_five_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eighth', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_eigth', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_two_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
        $pricing_high = get_post_meta( get_the_ID(), 'price_gram', true );
    } else {
        // Do nothing.
    }

    $pricing_high = wpd_currency_code() . str_ireplace( '.00', '', number_format( (float)$pricing_high, 2, '.', '' ) );

    return $pricing_high;
}
add_filter( 'wpd_flowers_pricing_high', 'wpd_heavyweights_flowers_weights_high' );

/**
 * Add heavyweights to concentrates prices low.
 * 
 * @since  1.3
 * @return string
 */
function wpd_heavyweights_concentrates_weights_low() {

    // Currency code.
    $currency_code = wpd_currency_code();

    $pricing_low = '';

    if ( get_post_meta( get_the_ID(), 'price_half_gram', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_half_gram', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_gram', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_two_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_three_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_three_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_four_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_four_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_five_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_five_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_six_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_six_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_seven_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_seven_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eight_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_eight_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_nine_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_nine_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_ten_grams', true ) ) {
        $pricing_low = $currency_code . get_post_meta( get_the_ID(), 'price_ten_grams', true );
    } else {
        // Do nothing.
    }

    return $pricing_low;
}
add_filter( 'wpd_concentrates_pricing_low', 'wpd_heavyweights_concentrates_weights_low' );

/**
 * Add heavyweights to concentrates prices high.
 * 
 * @since  1.3
 * @return void
 */
function wpd_heavyweights_concentrates_weights_high() {

    // Currency code.
    $currency_code = wpd_currency_code();

    $pricing_high = '';

    if ( get_post_meta( get_the_ID(), 'price_ten_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_ten_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_nine_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_nine_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_eight_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_eight_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_seven_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_seven_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_six_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_six_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_five_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_five_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_four_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_four_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_three_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_three_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_two_grams', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_two_grams', true );
    } elseif ( get_post_meta( get_the_ID(), 'price_gram', true ) ) {
        $pricing_high = $currency_code . get_post_meta( get_the_ID(), 'price_gram', true );
    } else {
        // Do nothing.
    }

    return $pricing_high;
}
add_filter( 'wpd_concentrates_pricing_high', 'wpd_heavyweights_concentrates_weights_high' );

/**
 * Add flower product prices
 * 
 * @param array $product_prices 
 * 
 * @since  2.2
 * @return string
 */
function wpd_heavyweights_flowers_product_prices( $product_prices ) {
    $product_prices['price_two_ounces']         = esc_attr__( '2 oz', 'cannabiz-menu' );
    $product_prices['price_quarter_pound']      = esc_attr__( '1/4 lb', 'cannabiz-menu' );
    $product_prices['price_half_pound']         = esc_attr__( '1/2 lb', 'cannabiz-menu' );
    $product_prices['price_one_pound']          = esc_attr__( '1 lb', 'cannabiz-menu' );
    $product_prices['price_two_pounds']         = esc_attr__( '2 lb', 'cannabiz-menu' );
    $product_prices['price_three_pounds']       = esc_attr__( '3 lb', 'cannabiz-menu' );
    $product_prices['price_four_pounds']        = esc_attr__( '4 lb', 'cannabiz-menu' );
    $product_prices['price_five_pounds']        = esc_attr__( '5 lb', 'cannabiz-menu' );
    $product_prices['price_six_pounds']         = esc_attr__( '6 lb', 'cannabiz-menu' );
    $product_prices['price_seven_pounds']       = esc_attr__( '7 lb', 'cannabiz-menu' );
    $product_prices['price_eight_pounds']       = esc_attr__( '8 lb', 'cannabiz-menu' );
    $product_prices['price_nine_pounds']        = esc_attr__( '9 lb', 'cannabiz-menu' );
    $product_prices['price_ten_pounds']         = esc_attr__( '10 lb', 'cannabiz-menu' );
    $product_prices['price_eleven_pounds']      = esc_attr__( '11 lb', 'cannabiz-menu' );
    $product_prices['price_twelve_pounds']      = esc_attr__( '12 lb', 'cannabiz-menu' );
    $product_prices['price_thirteen_pounds']    = esc_attr__( '13 lb', 'cannabiz-menu' );
    $product_prices['price_fourteen_pounds']    = esc_attr__( '14 lb', 'cannabiz-menu' );
    $product_prices['price_fifteen_pounds']     = esc_attr__( '15 lb', 'cannabiz-menu' );
    $product_prices['price_twenty_pounds']      = esc_attr__( '20 lb', 'cannabiz-menu' );
    $product_prices['price_twenty_five_pounds'] = esc_attr__( '25 lb', 'cannabiz-menu' );
    $product_prices['price_fifty_pounds']       = esc_attr__( '50 lb', 'cannabiz-menu' );
    return $product_prices;
}
add_filter( 'wpd_flowers_product_prices', 'wpd_heavyweights_flowers_product_prices' );

/**
 * Add product keys
 * 
 * @param array $price_keys 
 * 
 * @since  2.2
 * @return array
 */
function wpd_heavyweights_prices_metabox_save_price_keys( $price_keys ) {
    $price_keys[] = 'price_two_ounces';
    $price_keys[] = 'price_quarter_pound';
    $price_keys[] = 'price_half_pound';
    $price_keys[] = 'price_one_pound';
    $price_keys[] = 'price_two_pounds';
    $price_keys[] = 'price_three_pounds';
    $price_keys[] = 'price_four_pounds';
    $price_keys[] = 'price_five_pounds';
    $price_keys[] = 'price_six_pounds';
    $price_keys[] = 'price_seven_pounds';
    $price_keys[] = 'price_eight_pounds';
    $price_keys[] = 'price_nine_pounds';
    $price_keys[] = 'price_ten_pounds';
    $price_keys[] = 'price_eleven_pounds';
    $price_keys[] = 'price_twelve_pounds';
    $price_keys[] = 'price_thirteen_pounds';
    $price_keys[] = 'price_fourteen_pounds';
    $price_keys[] = 'price_fifteen_pounds';
    $price_keys[] = 'price_twenty_pounds';
    $price_keys[] = 'price_twenty_five_pounds';
    $price_keys[] = 'price_fifty_pounds';
    return $price_keys;
}
add_filter( 'wp_dispensary_prices_metabox_save_price_keys', 'wpd_heavyweights_prices_metabox_save_price_keys' );
