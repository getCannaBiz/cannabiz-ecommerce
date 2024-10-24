<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com/
 * @since      4.0.0
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com/
 * @since      4.0.0
 */
class WPD_Inventory_i18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since  1.0.0
     * @return void
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'wpd-inventory',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}
