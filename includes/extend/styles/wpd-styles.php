<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.wpdispensary.com
 * @since             1.0.0
 * @package           WPD_Styles
 *
 * @wordpress-plugin
 * Plugin Name:       WP Dispensary's Menu Styles
 * Plugin URI:        https://www.wpdispensary.com/product/styles
 * Description:       Take complete creative control over your WP Dispensary powered cannabis menu.
 * Version:           1.5
 * Author:            WP Dispensary
 * Author URI:        https://www.wpdispensary.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpd-styles
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPD_STYLES_VERSION', '1.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpd-styles-activator.php
 */
function activate_wpd_styles() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-styles-activator.php';
	WPD_Styles_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpd-styles-deactivator.php
 */
function deactivate_wpd_styles() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpd-styles-deactivator.php';
	WPD_Styles_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpd_styles' );
register_deactivation_hook( __FILE__, 'deactivate_wpd_styles' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpd-styles.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpd_styles() {

	$plugin = new WPD_Styles();
	$plugin->run();

}
run_wpd_styles();

function wpd_styles_modal_scripts() {
	wp_enqueue_style( 'wpd-styles-fancybox', plugins_url( 'public/css/jquery.fancybox.min.css', __FILE__) );
	wp_enqueue_style( 'wpd-styles-magnific-popup', plugins_url('public/css/magnific-popup.css', __FILE__),'', WPD_STYLES_VERSION );
	wp_enqueue_style( 'wpd-styles-main', plugins_url('public/css/main.css', __FILE__),'', WPD_STYLES_VERSION );

	wp_enqueue_script( 'wpd-styles-fancybox', plugins_url( 'public/js/jquery.fancybox.min.js', __FILE__ ), array( 'jquery' ), WPD_STYLES_VERSION, true );
	wp_enqueue_script( 'wpd-styles-magnific-popup', plugins_url('public/js/jquery.magnific-popup.min.js', __FILE__),array('jquery'), WPD_STYLES_VERSION, false );
	wp_enqueue_script( 'wpb-styles-plugin-main', plugins_url('public/js/main.js', __FILE__),array('jquery'), WPD_STYLES_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'wpd_styles_modal_scripts' );
