<?php
/**
 * Archive items
 * 
 * These functions work on the archive-items.php file
 * 
 * @since 1.0
 */
function wpd_ecommerce_archive_items_buttons( $product_id ) {

    if ( NULL == $product_id ) {
        $product_id = get_the_ID();
    } else {
        $product_id = $product_id;
    }

    $post_type      = get_post_type( $product_id );
    $price_each     = get_post_meta( $product_id, '_priceeach', true );
    $price_topical  = get_post_meta( $product_id, '_pricetopical', true );
    $price_per_pack = get_post_meta( $product_id, '_priceperpack', true );

    if ( 'edibles' == $post_type || 'prerolls' == $post_type || 'growers' == $post_type || 'tinctures' == $post_type || 'gear' == $post_type ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }
    if ( 'topicals' == $post_type ) {
    	if ( '' != $price_topical && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_topical && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_topical && '' === $price_per_pack ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_pricetopical" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }
    if ( 'flowers' == $post_type || 'concentrates' == $post_type ) {
        if ( '' != $price_each ) {
            echo '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            echo '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
    }

}
add_action( 'wpd_ecommerce_archive_items_product_inside_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_shortcode_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );

add_action( 'wpd_flowers_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_concentrates_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_edibles_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_prerolls_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_topicals_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_growers_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_gear_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_tinctures_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );

/**
 * Product Buttons
 * 
 * This function gets the buy buttons for products
 * 
 * @since 1.0
 */
function get_wpd_ecommerce_product_buttons( $product_id ) {

    $post_type      = get_post_type( $product_id );
    $price_each     = get_post_meta( $product_id, '_priceeach', true );
    $price_topical  = get_post_meta( $product_id, '_pricetopical', true );
    $price_per_pack = get_post_meta( $product_id, '_priceperpack', true );

    $str = '';

    if ( 'edibles' == $post_type || 'prerolls' == $post_type || 'growers' == $post_type || 'tinctures' == $post_type || 'gear' == $post_type ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }
    if ( 'topicals' == $post_type ) {
    	if ( '' != $price_topical && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_topical && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_topical && '' === $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_pricetopical" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }
    if ( 'flowers' == $post_type || 'concentrates' == $post_type ) {
        if ( '' != $price_each ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
    }

    return $str;
}
