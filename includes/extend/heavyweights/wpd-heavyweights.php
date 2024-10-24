<?php

/**
 * The plugin bootstrap file
 *
 * @package WPD_Heavyweights
 * @author  CannaBiz Software <contact@cannabizsoftware.com>
 * @link    https://cannabizsoftware.com
 * @since   4.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    wp_die();
}

// Current plugin version.
define( 'WPD_HEAVYWEIGHTS_VERSION', '1.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpd-heavyweights-activator.php
 * 
 * @return void
 */
function activate_wpd_heavyweights() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-heavyweights-activator.php';
    WPD_Heavyweights_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpd-heavyweights-deactivator.php
 * 
 * @return void
 */
function deactivate_wpd_heavyweights() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-heavyweights-deactivator.php';
    WPD_Heavyweights_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpd_heavyweights' );
register_deactivation_hook( __FILE__, 'deactivate_wpd_heavyweights' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpd-heavyweights.php';

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
function run_wpd_heavyweights() {

    $plugin = new WPD_Heavyweights();
    $plugin->run();

}
run_wpd_heavyweights();
