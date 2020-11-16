<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Locations
 * @subpackage WPD_Locations/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WPD_Locations
 * @subpackage WPD_Locations/includes
 * @author     WP Dispensary <contact@wpdispensary.com>
 */
class WPD_Locations_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/**
		 * Taxonomies
		 */
		wp_dispensary_locations();

		/**
		 * Flush Rewrite Rules
		 */
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
	}

}
