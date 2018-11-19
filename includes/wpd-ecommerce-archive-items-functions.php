<?php
/**
 * Archive items
 * 
 * These functions work on the archive-items.php file
 * 
 * @since 1.0
 */
function wpd_ecommerce_archive_items_buttons() {
    $post_type      = get_post_type();
    $price_each     = get_post_meta( get_the_ID(), '_priceeach', true );
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

}
add_action( 'wpd_ecommerce_archive_items_product_inside_after', 'wpd_ecommerce_archive_items_buttons' );
add_action( 'wpd_shortcode_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
