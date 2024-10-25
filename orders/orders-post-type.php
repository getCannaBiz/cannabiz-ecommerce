<?php
/**
 * WP Dispensary eCommerce orders custom post type
 *
 * @package WPD_eCommerce
 * @author  CannaBiz Software <contact@cannabizsoftware.com>
 * @license GPL-2.0+ 
 * @link    https://cannabizsoftware.com
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Custom Post Type
 * 
 * @return void
 */
function wpd_orders_post_type() {

    $labels = array(
        'name'                  => _x( 'Orders', 'Post Type General Name', 'cannabiz-menu' ),
        'singular_name'         => _x( 'Order', 'Post Type Singular Name', 'cannabiz-menu' ),
        'menu_name'             => esc_attr__( 'Orders', 'cannabiz-menu' ),
        'name_admin_bar'        => esc_attr__( 'Order', 'cannabiz-menu' ),
        'archives'              => esc_attr__( 'Order Archives', 'cannabiz-menu' ),
        'attributes'            => esc_attr__( 'Order Attributes', 'cannabiz-menu' ),
        'parent_item_colon'     => esc_attr__( 'Parent Order:', 'cannabiz-menu' ),
        'all_items'             => esc_attr__( 'All Orders', 'cannabiz-menu' ),
        'add_new_item'          => esc_attr__( 'Add New Order', 'cannabiz-menu' ),
        'add_new'               => esc_attr__( 'Add New', 'cannabiz-menu' ),
        'new_item'              => esc_attr__( 'New Order', 'cannabiz-menu' ),
        'edit_item'             => esc_attr__( 'Edit Order', 'cannabiz-menu' ),
        'update_item'           => esc_attr__( 'Update Order', 'cannabiz-menu' ),
        'view_item'             => esc_attr__( 'View Order', 'cannabiz-menu' ),
        'view_items'            => esc_attr__( 'View Orders', 'cannabiz-menu' ),
        'search_items'          => esc_attr__( 'Search Order', 'cannabiz-menu' ),
        'not_found'             => esc_attr__( 'Not found', 'cannabiz-menu' ),
        'not_found_in_trash'    => esc_attr__( 'Not found in Trash', 'cannabiz-menu' ),
        'featured_image'        => esc_attr__( 'Featured Image', 'cannabiz-menu' ),
        'set_featured_image'    => esc_attr__( 'Set featured image', 'cannabiz-menu' ),
        'remove_featured_image' => esc_attr__( 'Remove featured image', 'cannabiz-menu' ),
        'use_featured_image'    => esc_attr__( 'Use as featured image', 'cannabiz-menu' ),
        'insert_into_item'      => esc_attr__( 'Insert into order', 'cannabiz-menu' ),
        'uploaded_to_this_item' => esc_attr__( 'Uploaded to this order', 'cannabiz-menu' ),
        'items_list'            => esc_attr__( 'Orders list', 'cannabiz-menu' ),
        'items_list_navigation' => esc_attr__( 'Orders list navigation', 'cannabiz-menu' ),
        'filter_items_list'     => esc_attr__( 'Filter orders list', 'cannabiz-menu' ),
    );
    $rewrite = array(
        'slug'       => 'order',
        'with_front' => true,
        'pages'      => true,
        'feeds'      => true,
    );
    $capabilities = array(
        'edit_post'          => 'edit_shop_order',
        'read_post'          => 'read_shop_order',
        'delete_post'        => 'delete_shop_order',
        'edit_posts'         => 'edit_shop_orders',
        'edit_others_posts'  => 'edit_others_shop_orders',
        'publish_posts'      => 'publish_shop_orders',
        'read_private_posts' => 'read_private_shop_orders',
        'create_posts'       => 'edit_shop_orders',
    );
    $args = array(
        'label'               => esc_attr__( 'Order', 'cannabiz-menu' ),
        'description'         => esc_attr__( 'View your store\'s order history', 'cannabiz-menu' ),
        'labels'              => $labels,
        'supports'            => false,
        'taxonomies'          => [],
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'menu_icon'           => 'dashicons-cart',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => 'orders',
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capabilities'        => $capabilities,
        'show_in_rest'        => false,
    );
    register_post_type( 'wpd_orders', $args );
}
add_action( 'init', 'wpd_orders_post_type', 0 );

/**
 * Adds Orders admin submenu link.
 * 
 * @return void
 */
function wpd_admin_menu_orders() {
    // Add submenu page.
    add_submenu_page( 'wpd-settings', 'WP Dispensary\'s eCommerce orders', 'Orders', 'manage_options', 'edit.php?post_type=wpd_orders', null );
}
add_action( 'admin_menu', 'wpd_admin_menu_orders', 4 );

/**
 * Add the custom columns to the wpd_orders post type
 * 
 * @since  1.0
 * @return array
 */
function set_custom_edit_wpd_orders_columns( $columns ) {
    unset( $columns['author'] );
    unset( $columns['date'] );
    $columns['wpd_orders_date']     = esc_attr__( 'Date', 'cannabiz-menu' );
    $columns['wpd_orders_status']   = esc_attr__( 'Status', 'cannabiz-menu' );
    $columns['wpd_orders_customer'] = esc_attr__( 'Customer', 'cannabiz-menu' );
    $columns['wpd_orders_total']    = esc_attr__( 'Total', 'cannabiz-menu' );

    return $columns;
}
add_filter( 'manage_wpd_orders_posts_columns', 'set_custom_edit_wpd_orders_columns' );

/**
 * Add the data to the custom columns for the wpd_orders post type
 * 
 * @param object $column 
 * @param int    $post_id 
 * 
 * @return object
 */
function custom_wpd_orders_column( $column, $post_id ) {
    switch ( $column ) {

    case 'wpd_orders_date' :
        echo get_the_date( '', $post_id );
        break;

    case 'wpd_orders_status' :
        $status_display = wpd_ecommerce_order_statuses( $post_id, null, null );
        if ( '' !== $status_display ) {
            echo $status_display;
        }
        break;

    case 'wpd_orders_customer' :
        $order_customer_id = get_post_meta( $post_id, 'wpd_order_customer_id', true );
        $user_info         = get_userdata( $order_customer_id );
        if ( isset( $user_info ) ) {
            if ( '' != $user_info->first_name ) {
                echo $user_info->first_name . ' ';
            }
            if ( '' != $user_info->last_name ) {
                echo $user_info->last_name;
            }
        } else {
            // Do nothing.
        }
        break;

    case 'wpd_orders_total' :
        $order_subtotal = get_post_meta( $post_id, 'wpd_order_subtotal_price', true );
        $order_total    = get_post_meta( $post_id, 'wpd_order_total_price', true );
        if ( '' !== $order_total ) {
            echo CURRENCY . $order_total;
        }
        break;

    }
}
add_action( 'manage_wpd_orders_posts_custom_column', 'custom_wpd_orders_column', 10, 2 );

/**
 * Restrict pages for new user roles
 * 
 * @since 2.3.0
 * @return void
 */
function wpd_ecommerce_restrict_wpd_orders_by_user_role() {
    if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_owner' ) && ! current_user_can( 'shop_manager' ) ) {
        wp_die( 'You are not allowed to access this page.' );
    }
}
add_action( 'load-edit.php?post_type=wpd_orders', 'wpd_ecommerce_restrict_wpd_orders_by_user_role' );
