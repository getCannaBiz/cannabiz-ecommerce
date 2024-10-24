<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://cannabizsoftware.com/
 * @since             1.0.0
 * @package           WPD_TopSellers
 *
 * @wordpress-plugin
 * Plugin Name:       WP Dispensary's Top Sellers
 * Plugin URI:        https://cannabizsoftware.com/downloads/dispensary-top-sellers
 * Description:       Easily select and display your menu's top selling items.
 * Version:           1.6
 * Author:            WP Dispensary
 * Author URI:        https://cannabizsoftware.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpd-topsellers
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpd-topsellers-activator.php
 */
function activate_wpd_topsellers() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-topsellers-activator.php';
    WPD_TopSellers_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpd-topsellers-deactivator.php
 */
function deactivate_wpd_topsellers() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-topsellers-deactivator.php';
    WPD_TopSellers_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpd_topsellers' );
register_deactivation_hook( __FILE__, 'deactivate_wpd_topsellers' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpd-topsellers.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpd_topsellers() {

    $plugin = new WPD_TopSellers();
    $plugin->run();

}
run_wpd_topsellers();
