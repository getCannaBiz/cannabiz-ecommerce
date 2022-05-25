<?php
/**
 * Order Details database
 *
 * Adds order details to it's own database table.
 *
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add version number to global space
 * 
 * @since 1.0
 */
global $wpd_orders_db_version;
$wpd_orders_db_version = '1.0';

/**
 * Orders database install
 * 
 * @return void
 */
function wpd_ecommerce_orders_database_install() {
    global $wpdb;
    global $wpd_orders_db_version;

    $order_table_name      = $wpdb->prefix . 'wpd_orders';
    $order_meta_table_name = $wpdb->prefix . 'wpd_orders_meta';
    $charset_collate       = $wpdb->get_charset_collate();

    // Orders Table SQL.
    $orders_table_sql = "CREATE TABLE $order_table_name (
        item_id mediumint(9) NOT null AUTO_INCREMENT,
        order_id tinytext NOT null,
        order_type tinytext NOT null,
        order_key tinytext NOT null,
        order_value tinytext NOT null,
        PRIMARY KEY  (item_id)
    ) $charset_collate;";

    // Orders Meta Table SQL.
    $orders_meta_table_sql = "CREATE TABLE $order_meta_table_name (
        meta_id mediumint(9) NOT null AUTO_INCREMENT,
        meta_key tinytext NOT null,
        meta_value tinytext NOT null,
        item_id tinytext NOT null,
        PRIMARY KEY  (meta_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // Create Orders table.
    dbDelta( $orders_table_sql );

    // Create Orders Meta table.
    dbDelta( $orders_meta_table_sql );

    // Add option for database version number.
    add_option( 'wpd_orders_db_version', $wpd_orders_db_version );
}
