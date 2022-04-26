<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_TopSellers
 * @subpackage WPD_TopSellers/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPD_TopSellers
 * @subpackage WPD_TopSellers/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 */


/**
 * Top Seller metabox
 *
 * Adds the Top Seller metabox to specific custom post types
 *
 * @since    1.0.0
 */
function wpd_topsellers_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function top_sellers_add_meta_box() {
	add_meta_box(
		'wpd_featured_product',
		__( 'Featured', 'wpd-ecommerce' ),
		'top_sellers_html',
		apply_filters( 'wpd_product_featured_metabox', 'products' ),
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'top_sellers_add_meta_box' );

function top_sellers_html( $post ) {
	wp_nonce_field( 'product_featured_nonce', 'top_sellers_nonce' ); ?>
	<p>
		<input type="checkbox" name="product_featured" id="product_featured" value="product_featured" <?php echo ( wpd_topsellers_meta( 'product_featured' ) === 'product_featured' ) ? 'checked' : ''; ?>>
		<label for="product_featured"><?php _e( 'This is a featured product', 'wpd-ecommerce' ); ?></label>
	</p>
	<?php
}

function top_sellers_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( null == filter_input( INPUT_POST, 'top_sellers_nonce' ) || ! wp_verify_nonce( filter_input( INPUT_POST, 'top_sellers_nonce' ), 'product_featured_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( null !== filter_input( INPUT_POST, 'product_featured' ) )
		update_post_meta( $post_id, 'product_featured', esc_attr( filter_input( INPUT_POST, 'product_featured' ) ) );
	else
		update_post_meta( $post_id, 'product_featured', null );
}
add_action( 'save_post', 'top_sellers_save' );
