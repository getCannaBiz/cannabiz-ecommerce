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

// Keeps the WP Dispensary link list open when taxonomy submenu items are open.
function wpd_locations_keep_taxonomy_menu_open( $parent_file ) {
    global $current_screen;
    // Get current screen taxonomy.
    $taxonomy = $current_screen->taxonomy;
    // Check taxonomies.
    if ( 'locations' == $taxonomy ) {
        $parent_file = 'wpd-settings';
    }

    return $parent_file;
}
add_action( 'parent_file', 'wpd_locations_keep_taxonomy_menu_open' );

// Adds Locations admin submenu link.
function wpd_admin_menu_locations() {
    add_submenu_page( 'wpd-settings', 'WP Dispensary\'s Locations', 'Locations', 'manage_options', 'edit-tags.php?taxonomy=locations', null );
}
add_action( 'admin_menu', 'wpd_admin_menu_locations', 6 );
