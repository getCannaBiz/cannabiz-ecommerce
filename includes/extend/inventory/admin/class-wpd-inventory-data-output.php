<?php

/**
 * Inventory stock action hooks.
 *
 * This is the file responsible for adding the current inventory count to menu
 * item detail tables.
 * 
 * @link       https://www.wpdispensary.com/
 * @since      1.1.0
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 */

/**
 * Inventory data for Flowers.
 *
 * @return string
 */
function add_wpd_inventory_data_flowers() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'flowers' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_grams', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?>:</span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_grams', true ); ?> <?php _e( 'grams', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_flowers', 10 );

/**
 * Inventory data for Concentrates.
 *
 * @return string
 */
function add_wpd_inventory_data_concentrates() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'concentrates' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_grams', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?></span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_grams', true ); ?> <?php _e( 'grams', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
			if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?></span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_units', true ); ?> <?php _e( 'units', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_concentrates', 10 );

/**
 * Inventory data for Edibles.
 *
 * @return string
 */
function add_wpd_inventory_data_edibles() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'edibles' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?>:</span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_units', true ); ?> <?php _e( 'units', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_edibles', 10 );

/**
 * Inventory data for Topicals.
 *
 * @return string
 */
function add_wpd_inventory_data_topicals() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'topicals' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?>:</span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_units', true ); ?> <?php _e( 'units', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_topicals', 10 );

/**
 * Inventory data for Pre-rolls.
 *
 * @return string
 */
function add_wpd_inventory_data_prerolls() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'prerolls' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?>:</span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_units', true ); ?> <?php _e( 'units', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_prerolls', 10 );

/**
 * Inventory data for Pre-rolls.
 *
 * @return string
 */
function add_wpd_inventory_data_growers() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'growers' ) ) ) {
			if ( get_post_meta( get_the_ID(), 'seed_count', true ) ) {
				if ( ! get_post_meta( get_the_ID(), 'inventory_seeds', true ) ) { } else { ?>
					<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?></span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_seeds', true ); ?> <?php _e( 'seeds', 'wpd-ecommerce' ); ?></td></tr>
				<?php }
			}
			if ( get_post_meta( get_the_ID(), 'clone_count', true ) ) {
				if ( ! get_post_meta( get_the_ID(), 'inventory_clones', true ) ) { } else { ?>
					<td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?></span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_clones', true ); ?> <?php _e( 'clones', 'wpd-ecommerce' ); ?></td>
				<?php }
			}
		}
	} // display check
} // function
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_growers', 10 );

/**
 * Inventory data for Tinctures.
 *
 * @return string
 */
function add_wpd_inventory_data_tinctures() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'tinctures' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?>:</span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_units', true ); ?> <?php _e( 'units', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_tinctures', 10 );

/**
 * Inventory data for Gear.
 *
 * @return string
 */
function add_wpd_inventory_data_gear() {
	if ( get_post_meta( get_the_ID(), 'wpd_inventory_display', true ) ) {
		if ( in_array( get_post_type(), array( 'gear' ) ) ) {
			if ( ! get_post_meta( get_the_ID(), 'inventory_units', true ) ) { } else { ?>
				<tr><td><span><?php _e( 'In stock', 'wpd-ecommerce' ); ?>:</span></td><td><?php echo get_post_meta( get_the_ID(), 'inventory_units', true ); ?> <?php _e( 'units', 'wpd-ecommerce' ); ?></td></tr>
			<?php }
		}
	}
}
add_action( 'wpd_dataoutput_bottom', 'add_wpd_inventory_data_gear', 10 );
