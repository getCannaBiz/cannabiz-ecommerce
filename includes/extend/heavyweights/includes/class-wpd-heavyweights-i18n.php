<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com
 * @since      1.0.0
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com
 * @since      1.0.0
 */
class WPD_Heavyweights_i18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since  1.0.0
     * @return void
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'wpd-heavyweights',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}
