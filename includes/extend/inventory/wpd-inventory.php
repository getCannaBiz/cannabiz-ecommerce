<?php

/**
 * The plugin bootstrap file
 *
 * @package WPD_Inventory
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @link    https://www.wpdispensary.com/
 * @since   4.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'WPD_INVENTORY_VERSION' ) ) {
    define( 'WPD_INVENTORY_VERSION', '1.8.1' );
}

if ( ! defined( 'WPD_INVENTORY_NAME' ) ) {
    define( 'WPD_INVENTORY_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'WPD_INVENTORY_DIR' ) ) {
    define( 'WPD_INVENTORY_DIR', WP_PLUGIN_DIR . '/' . WPD_INVENTORY_NAME );
}

if ( ! defined( 'WPD_INVENTORY_URL' ) ) {
    define( 'WPD_INVENTORY_URL', WP_PLUGIN_URL . '/' . WPD_INVENTORY_NAME );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpd-inventory-activator.php
 * 
 * @return void
 */
function activate_wpd_inventory() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-inventory-activator.php';
    WPD_Inventory_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpd-inventory-deactivator.php
 * 
 * @return void
 */
function deactivate_wpd_inventory() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-inventory-deactivator.php';
    WPD_Inventory_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpd_inventory' );
register_deactivation_hook( __FILE__, 'deactivate_wpd_inventory' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpd-inventory.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 * @return void
 */
function run_wpd_inventory() {

    $plugin = new WPD_Inventory();
    $plugin->run();

}
run_wpd_inventory();

/**
 * Actions/Filters
 *
 * Related to all settings API.
 *
 * @since  1.0.0
 * @return void
 */
if ( class_exists( 'WPD_INVENTORY_SETTINGS' ) ) {
    /**
     * Object Instantiation.
     *
     * Object for the class `WPD_INVENTORY_SETTINGS`.
     */
    $wpd_inventory_obj = new WPD_INVENTORY_SETTINGS();
}
