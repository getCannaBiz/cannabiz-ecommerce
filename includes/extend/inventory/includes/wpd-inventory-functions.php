<?php


/**
 * Out of Stock warnings in shortcodes.
 *
 * @return    string
 * @since     1.8
 */
function wpd_inventory_oos_shortcode_warnings() {

    // Out of stock warning.
    $out_of_stock = '';

    // Variables.
    $price_each     = get_post_meta( get_the_ID(), '_priceeach', true );
    $price_topical  = get_post_meta( get_the_ID(), '_pricetopical', true );
    $price_per_pack = get_post_meta( get_the_ID(), '_priceperpack', true );

    // Flowers out of stock.
    if ( 'flowers' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), '_inventory_flowers', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Concentrates out of stock.
    if ( 'concentrates' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( '' != $price_each ) {
            if ( ! get_post_meta( get_the_ID(), '_inventory_concentrates_each', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        } else {
            if ( ! get_post_meta( get_the_ID(), '_inventory_concentrates', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        }
    }

    // Edibles out of stock.
    if ( 'edibles' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), '_inventory_edibles', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Pre-rolls out of stock.
    if ( 'prerolls' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), '_inventory_prerolls', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Topicals out of stock.
    if ( 'topicals' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), '_inventory_topicals', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Growers out of stock.
    if ( 'growers' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( get_post_meta( get_the_ID(), '_clonecount', true ) ) {
            if ( ! get_post_meta( get_the_ID(), '_inventory_clones', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        } elseif ( get_post_meta( get_the_ID(), '_seedcount', true ) ) {
            if ( ! get_post_meta( get_the_ID(), '_inventory_seeds', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        }
    }

    // Tinctures out of stock.
    if ( 'tinctures' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), '_inventory_tinctures', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Gear out of stock.
    if ( 'gear' == get_post_type( get_the_ID() ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), '_inventory_gear', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . __( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Out of stock warning(s).
    echo apply_filters( 'wpd_inventory_oos_shortcode_warnings', $out_of_stock );

}
add_action( 'wpd_shortcode_inside_bottom', 'wpd_inventory_oos_shortcode_warnings', 9 );
