<?php
/**
 * Archive items
 * 
 * These functions work on the archive-items.php file
 * 
 * @since 1.0
 */
function wpd_ecommerce_archive_items_buttons() {
    global $post;

    $post_type      = get_post_type( $post->ID );
    $price_each     = get_post_meta( get_the_ID(), '_priceeach', true );
    $price_topical  = get_post_meta( get_the_ID(), '_pricetopical', true );
    $price_per_pack = get_post_meta( get_the_ID(), '_priceperpack', true );

    if ( 'edibles' == $post_type || 'prerolls' == $post_type || 'growers' == $post_type || 'tinctures' == $post_type || 'gear' == $post_type ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink() . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink() . '?add_item=' . get_the_ID() . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            echo '<a href="' . get_the_permalink() . '?add_item=' . get_the_ID() . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }
    if ( 'topicals' == $post_type ) {
    	if ( '' != $price_topical && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink() . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_topical && '' != $price_per_pack ) {
            echo '<a href="' . get_the_permalink() . '?add_item=' . get_the_ID() . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_topical && '' === $price_per_pack ) {
            echo '<a href="' . get_the_permalink() . '?add_item=' . get_the_ID() . '_pricetopical" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }
    if ( 'flowers' == $post_type || 'concentrates' == $post_type ) {
        if ( '' != $price_each ) {
            echo '<a href="' . get_the_permalink() . '?add_item=' . get_the_ID() . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            echo '<a href="' . get_the_permalink() . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
    }

}
add_action( 'wpd_ecommerce_archive_items_product_inside_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_shortcode_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );

add_action( 'wpd_flowers_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_concentrates_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_edibles_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_prerolls_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_topicals_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_growers_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_gear_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_tinctures_widget_inside_loop_after', 'wpd_ecommerce_archive_items_buttons' );
