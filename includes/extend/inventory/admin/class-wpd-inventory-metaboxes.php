<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 */

/**
 * Inventory management metabox
 *
 * Adds the inventory metabox to WP Dispensary products
 *
 * @since    1.0.0
 */
function wp_dispensary_add_inventory_metaboxes() {
	add_meta_box(
		'wp_dispensary_inventory_management',
		esc_attr__( 'Inventory management', 'wpd-ecommerce' ),
		'wp_dispensary_inventory_management',
		'products',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wp_dispensary_add_inventory_metaboxes' );

/**
 * Building the metabox
 */
function wp_dispensary_inventory_management() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventory_management_meta_noncename" id="inventory_management_meta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventory_grams   = get_post_meta( $post->ID, 'inventory_grams', true );
	$inventory_each    = get_post_meta( $post->ID, 'inventory_units', true );
	$inventory_seeds   = get_post_meta( $post->ID, 'inventory_seeds', true );
	$inventory_clones  = get_post_meta( $post->ID, 'inventory_clones', true );
  $inventory_display = get_post_meta( $post->ID , 'inventory_display', true );
	$inventory_check   = checked( $inventory_display, 'add_inventory_display', false );

	/** Echo out the fields */
	$string  = '<div class="wpd-inventory growers">';
	$string .= '<p>' . esc_attr__( 'Available Seeds (units)', 'wpd-ecommerce' ) . ':</p>';
	$string .= '<input type="text" name="inventory_seeds" value="' . $inventory_seeds  . '" class="widefat" />';
	$string .= '<p>' . esc_attr__( 'Available Clones (units)', 'wpd-ecommerce' ) . ':</p>';
	$string .= '<input type="text" name="inventory_clones" value="' . $inventory_clones  . '" class="widefat" />';
	$string .= '</div>';
	$string .= '<div class="wpd-inventory grams">';
	$string .= '<p>' . esc_attr__( 'Available grams', 'wpd-ecommerce' ) . ':</p>';
	$string .= '<input type="text" name="inventory_grams" value="' . $inventory_grams  . '" class="widefat" />';
	$string .= '</div>';
	$string .= '<div class="wpd-inventory units">';
	$string .= '<p>' . esc_attr__( 'Available units', 'wpd-ecommerce' ) . ':</p>';
	$string .= '<input type="text" name="inventory_units" value="' . $inventory_each  . '" class="widefat" />';
	$string .= '</div>';
	$string .= '<div class="wpd-inventory display">';
	$string .= '<p><input type="checkbox" name="inventory_display" id="inventory_display" value="add_inventory_display" '. $inventorycheck .'><label for="inventory_display">' . esc_attr__( 'Display inventory in Details table', 'wpd-ecommerce' ) . '</label></p>';
	$string .= '</div>';

	echo wp_kses( $string, wp_kses_allowed_html( 'post' ) );

}

/**
 * Save the metabox
 */
function wp_dispensary_save_inventory_management_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( null == filter_input( INPUT_POST, 'inventory_management_meta_noncename' ) || ! wp_verify_nonce( filter_input( INPUT_POST, 'inventory_management_meta_noncename' ), plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['inventory_seeds']   = filter_input( INPUT_POST, 'inventory_seeds' );
	$inventory_meta['inventory_clones']  = filter_input( INPUT_POST, 'inventory_clones' );
	$inventory_meta['inventory_grams']   = filter_input( INPUT_POST, 'inventory_grams' );
	$inventory_meta['inventory_units']   = filter_input( INPUT_POST, 'inventory_units' );
	$inventory_meta['inventory_display'] = filter_input( INPUT_POST, 'inventory_display' );

	/** Add values of $inventory_meta as custom fields */

	// Cycle through the $inventory_meta array.
	foreach ( $inventory_meta as $key => $value ) {
		// Don't store custom data twice.
		if ( $post->post_type == 'revision' ) {
			return;
		}
		// If $value is an array, make it a CSV (unlikely).
		$value = implode( ',', (array) $value );
		// Add/update the custom field.
		if ( get_post_meta( $post->ID, $key, false ) ) {
			update_post_meta( $post->ID, $key, $value );
		} else {
			add_post_meta( $post->ID, $key, $value );
		}
		// Delete the field if blank.
		if ( ! $value ) {
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wp_dispensary_save_inventory_management_meta', 1, 2 ); // save the custom fields
