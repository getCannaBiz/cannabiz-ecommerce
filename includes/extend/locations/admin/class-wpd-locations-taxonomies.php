<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Locations
 * @subpackage WPD_Locations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPD_Locations
 * @subpackage WPD_Locations/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 */

/**
 * Location Taxonomy
 *
 * Adds the Location taxonomy to all custom post types
 *
 * @since    1.0.0
 */
function wp_dispensary_locations() {

	$labels = array(
		'name'              => _x( 'Locations', 'taxonomy general name', 'wpd-ecommerce' ),
		'singular_name'     => _x( 'Location', 'taxonomy singular name', 'wpd-ecommerce' ),
		'search_items'      => esc_attr__( 'Search Locations', 'wpd-ecommerce' ),
		'all_items'         => esc_attr__( 'All Locations', 'wpd-ecommerce' ),
		'parent_item'       => esc_attr__( 'Parent Location', 'wpd-ecommerce' ),
		'parent_item_colon' => esc_attr__( 'Parent Location:', 'wpd-ecommerce' ),
		'edit_item'         => esc_attr__( 'Edit Location', 'wpd-ecommerce' ), 
		'update_item'       => esc_attr__( 'Update Location', 'wpd-ecommerce' ),
		'add_new_item'      => esc_attr__( 'Add New Location', 'wpd-ecommerce' ),
		'new_item_name'     => esc_attr__( 'New Location Name', 'wpd-ecommerce' ),
		'not_found'         => esc_attr__( 'No locations found', 'wpd-ecommerce' ),
		'menu_name'         => esc_attr__( 'Locations', 'wpd-ecommerce' ),
	); 	

	register_taxonomy( 'locations', 'products', array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'locations',
			'with_front' => true,
		),
	) );

}
add_action( 'init', 'wp_dispensary_locations', 0 );
