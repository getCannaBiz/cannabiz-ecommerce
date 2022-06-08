<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 * @link       https://www.wpdispensary.com/
 * @since      4.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 * @link       https://www.wpdispensary.com/
 * @since      4.0.0
 */
class WPD_Inventory_Admin {

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_plugin_name    The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_version    The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $_plugin_name - The name of this plugin.
     * @param string $_version     - The version of this plugin.
     * 
     * @since  1.0.0
     * @return void
     */
    public function __construct( $_plugin_name, $_version ) {

        $this->plugin_name = $_plugin_name;
        $this->version     = $_version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles() {
        // Enqueue the admin stylesheet.
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpd-inventory-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue the admin javascript file.
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpd-inventory-admin.js', array( 'jquery' ), $this->version, false );
        // Localize the admin javascript file.
        wp_localize_script( $this->plugin_name, 'WPaAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        // Enqueue the tablesorter javascript files.
        wp_enqueue_script( 'tablesorter', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'tablesorter-jquery', plugin_dir_url( __FILE__ ) . 'js/tablesorter.js', array( 'jquery' ), $this->version, false );
    }

}
