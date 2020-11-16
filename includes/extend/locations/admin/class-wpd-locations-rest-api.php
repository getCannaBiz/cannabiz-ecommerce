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

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Locations taxonomy for all Custom Post Types
 */
function wpd_locations( $data, $post, $request ) {
	$_data                  = $data->data;
	$_data['location_list'] = get_the_term_list( $post->ID, 'locations', '', ' ', '' );
	$data->data             = $_data;
	return $data;
}
add_filter( 'rest_prepare_products', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_flowers', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_concentrates', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_edibles', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_prerolls', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_topicals', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_gear', 'wpd_locations', 10, 3 );
add_filter( 'rest_prepare_tinctures', 'wpd_locations', 10, 3 );
