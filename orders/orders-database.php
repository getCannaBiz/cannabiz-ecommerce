<?php
/**
 * Order Details database
 *
 * Adds order details to it's own database table.
 *
 * @since    1.0
 */

/**
 * Add version number to global space
 * 
 * @todo figure out if this is needed, and if so is it the best way to handle it?
 */
global $wpd_orders_db_version;
$wpd_orders_db_version = '1.0';

function wpd_ecommerce_orders_database_install() {
	global $wpdb;
	global $wpd_orders_db_version;

    $order_table_name      = $wpdb->prefix . 'wpd_orders';
    $order_meta_table_name = $wpdb->prefix . 'wpd_orders_meta';
	$charset_collate       = $wpdb->get_charset_collate();

    // Orders Table SQL.
	$orders_table_sql = "CREATE TABLE $order_table_name (
		item_id mediumint(9) NOT NULL AUTO_INCREMENT,
		order_item_id tinytext NOT NULL,
		order_item_name tinytext NOT NULL,
		order_id tinytext NOT NULL,
		PRIMARY KEY  (item_id)
    ) $charset_collate;";

    // Orders Meta Table SQL.
    $orders_meta_table_sql = "CREATE TABLE $order_meta_table_name (
        meta_id mediumint(9) NOT NULL AUTO_INCREMENT,
        meta_key tinytext NOT NULL,
        meta_value tinytext NOT NULL,
        item_id tinytext NOT NULL,
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

/**
 * Function to add data from the order
 */
function wpd_ecommerce_orders_add_order() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'wpd_orders';

	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}
