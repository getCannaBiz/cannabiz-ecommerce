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
    $price_each     = get_post_meta( get_the_ID(), 'price_each', true );
    $price_topical  = get_post_meta( get_the_ID(), 'price_each', true );
    $price_per_pack = get_post_meta( get_the_ID(), 'price_per_pack', true );

    // Flowers out of stock.
    if ( 'flowers' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), 'inventory_grams', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Concentrates out of stock.
    if ( 'concentrates' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( '' != $price_each ) {
            if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        } else {
            if ( ! get_post_meta( get_the_ID(), 'inventory_grams', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        }
    }

    // Edibles out of stock.
    if ( 'edibles' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Pre-rolls out of stock.
    if ( 'prerolls' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Topicals out of stock.
    if ( 'topicals' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Growers out of stock.
    if ( 'growers' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // If no clone or seed count has been added.
        if ( ! get_post_meta( get_the_ID(), 'seed_count', true ) && ! get_post_meta( get_the_ID(), 'clone_count', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
        // Add out of stock notice to output string.
        if ( get_post_meta( get_the_ID(), 'clone_count', true ) ) {
            if ( ! get_post_meta( get_the_ID(), 'inventory_clones', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        } elseif ( get_post_meta( get_the_ID(), 'seed_count', true ) ) {
            if ( ! get_post_meta( get_the_ID(), 'inventory_seeds', true ) ) {
                $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
            }
        }
    }

    // Tinctures out of stock.
    if ( 'tinctures' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Gear out of stock.
    if ( 'gear' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
        // Add out of stock notice to output string.
        if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) {
            $out_of_stock .= '<span class="wpd-inventory warning">' . esc_attr__( 'out of stock', 'wpd-ecommerce' ) . '</span>';
        }
    }

    // Out of stock warning(s).
    echo apply_filters( 'wpd_inventory_oos_shortcode_warnings', $out_of_stock );

}
add_action( 'wpd_shortcode_inside_bottom', 'wpd_inventory_oos_shortcode_warnings', 9 );
add_action( 'wpd_ecommerce_archive_items_product_inside_after', 'wpd_inventory_oos_shortcode_warnings', 9 );