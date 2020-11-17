<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.wpdispensary.com/
 * @since             4.0.0
 * @package           WPD_Locations
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpd-locations-activator.php
 */
function activate_wpd_locations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-locations-activator.php';
	WPD_Locations_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpd-locations-deactivator.php
 */
function deactivate_wpd_locations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-locations-deactivator.php';
	WPD_Locations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpd_locations' );
register_deactivation_hook( __FILE__, 'deactivate_wpd_locations' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpd-locations.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpd_locations() {

	$plugin = new WPD_Locations();
	$plugin->run();

}
run_wpd_locations();

// Add settings link on plugin page.
function dispensary_locations_settings_link( $links ) {
  $settings_link = '<a href="edit-tags.php?taxonomy=locations">' . __( 'Settings', 'wpd-locations' ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}

$pluginname = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$pluginname", 'dispensary_locations_settings_link' );

function dispensary_locations_add_admin_menu() {
	add_submenu_page( 'wpd-settings', 'WP Dispensary\'s Locations', 'Locations', 'manage_options', 'edit-tags.php?taxonomy=locations', null );
}
add_action( 'admin_menu', 'dispensary_locations_add_admin_menu', 7 );
