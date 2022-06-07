<?php
/**
 * Adding the Custom Post Type metaboxes
 * to the WordPress REST API
 *
 * @package    WPD_Heavyweights
 * @subpackage WP_Heavyweights/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 * @link       https://www.wpdispensary.com
 * @since      1.0.0
 */

/**
 * This adds the wpdispensary_prices metafields to the
 * API callback for flowers
 *
 * @since  1.0.0
 * @return void
 */
function slug_register_heavyweight_prices() {
    // Heavyweight sizes.
    $flowers_sizes      = apply_filters( 'wpd_heavyweights_rest_api_flowers_sizes', array( '_twoounces', '_quarterpound', '_halfpound', '_onepound', '_twopounds', '_threepounds', '_fourpounds', '_fivepounds', '_sixpounds', '_sevenpounds', '_eightpounds', '_ninepounds', '_tenpounds', '_elevenpounds', '_twelvepounds' ) );
    $concentrates_sizes = apply_filters( 'wpd_heavyweights_rest_api_concentrates_sizes', array( '_threegrams', '_fourgrams', '_fivegrams', '_sixgrams', '_sevengrams', '_eightgrams', '_ninegrams', '_tengrams' ) );

    // Loop through each flowers size.
    foreach ( $flowers_sizes as $size ) {
        register_rest_field(
            array( 'flowers' ),
            $size,
            array(
                'get_callback'    => 'slug_get_heavyweight_prices',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

    // Loop through each concentrates size.
    foreach ( $concentrates_sizes as $size ) {
        register_rest_field(
            array( 'concentrates' ),
            $size,
            array(
                'get_callback'    => 'slug_get_heavyweight_prices',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

}
add_action( 'rest_api_init', 'slug_register_heavyweight_prices' );

/**
 * Get Prices
 * 
 * @param object $object     - 
 * @param string $field_name - 
 * @param string $request    - 
 * 
 * @return string
 */
function slug_get_heavyweight_prices( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], $field_name, true );
}
