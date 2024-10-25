<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com/
 * @since      4.0.0
 */

/**
 * Out of Stock message.
 *
 * @param string $content 
 * 
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

    $original = '';
    $content  = $content;

    if ( in_array( get_post_type(), array( 'products' ) ) ) {
        $original = $content;
    }

    if ( in_array( get_post_type(), array( 'products' ) ) ) {
        $content = '';
    }

    // Flowers.
    if ( is_singular( 'products' ) ) {
        $product_type = get_post_meta( get_the_ID(), 'product_type', true );
        // Out of stock message.
        if ( 'flowers' == $product_type && ! get_post_meta( get_the_ID(), 'inventory_grams', true ) ) {
            $content .= '<div class="wpd-inventory ' . $product_type . ' warning">';
            $content .= '<p>' . esc_attr__( 'Sorry, this flower is currently out of stock.', 'cannabiz-menu' ) . '</p>';
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
