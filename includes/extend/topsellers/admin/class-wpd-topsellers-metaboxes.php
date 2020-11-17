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
		'top_sellers-top-sellers',
		__( 'Top Sellers', 'wpd-ecommerce' ),
		'top_sellers_html',
		apply_filters( 'wpd_top_sellers_metabox', wpd_menu_types_simple( TRUE ) ),
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'top_sellers_add_meta_box' );

function top_sellers_html( $post ) {
	wp_nonce_field( '_top_sellers_nonce', 'top_sellers_nonce' ); ?>
	<p>
		<input type="checkbox" name="wpd_topsellers" id="wpd_topsellers" value="add_wpd_topsellers" <?php echo ( wpd_topsellers_meta( 'wpd_topsellers' ) === 'add_wpd_topsellers' ) ? 'checked' : ''; ?>>
		<label for="wpd_topsellers"><?php _e( 'Mark this product as a top seller', 'wpd-ecommerce' ); ?></label>
	</p>
	<?php
}

function top_sellers_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['top_sellers_nonce'] ) || ! wp_verify_nonce( $_POST['top_sellers_nonce'], '_top_sellers_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['wpd_topsellers'] ) )
		update_post_meta( $post_id, 'wpd_topsellers', esc_attr( $_POST['wpd_topsellers'] ) );
	else
		update_post_meta( $post_id, 'wpd_topsellers', null );
}
add_action( 'save_post', 'top_sellers_save' );
