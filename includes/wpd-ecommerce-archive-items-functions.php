<?php
/**
 * WP Dispensary eCommerce archive helper functions
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Archive Buttons
 * 
 * Add to cart functionality for blocks, shortcodes, and widgets
 * 
 * @since 1.0
 */
function wpd_ecommerce_archive_items_buttons( $product_id ) {

    if ( NULL == $product_id ) {
        $product_id = get_the_ID();
    } else {
        $product_id = $product_id;
    }

    // Product data.
    $post_type      = get_post_type( $product_id );
    $price_each     = get_post_meta( $product_id, 'price_each', true );
    $price_per_pack = get_post_meta( $product_id, 'price_per_pack', true );

    // Flowers.
    if ( 'flowers' == get_post_meta( $product_id, 'product_type', true ) ) {
        // Create button.
        if ( '' != $price_each ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_each" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . esc_attr__( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            // Remove button if inventory is empty.
            if ( ! get_post_meta( $product_id, 'inventory_grams', true ) || 0 >= get_post_meta( $product_id, 'inventory_grams', true ) ) {
                $button = '';
            }    
        }
    }

    // Concentrates.
    if ( 'concentrates' == get_post_meta( $product_id, 'product_type', true ) ) {
        // Create button.
        if ( '' != $price_each ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_each" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . esc_attr__( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( '' != $price_each ) {
                // Remove button if inventory is empty.
                if ( ! get_post_meta( $product_id, 'inventory_units', true ) || 0 >= get_post_meta( $product_id, 'inventory_units', true ) ) {
                    $button = '';
                }
            } else {
                // Remove button if inventory is empty.
                if ( ! get_post_meta( $product_id, 'inventory_grams', true ) || 0 >= get_post_meta( $product_id, 'inventory_grams', true ) ) {
                    $button = '';
                }
            }
        }
    }

    // Edibles, Pre-rolls, Topicals, Tinctures, Gear.
    if ( in_array( get_post_meta( $product_id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'tinctures', 'gear' ) ) ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . esc_attr__( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_per_pack" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_each" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }

    // Growers.
    if ( 'growers' == get_post_meta( $product_id, 'product_type', true ) ) {
        // Create button.
    	if ( '' != $price_each && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . esc_attr__( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_per_pack" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_each" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }

    // Inventory check.
    if ( in_array( get_post_meta( $product_id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'tinctures', 'gear' ) ) ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( ! get_post_meta( $product_id, 'inventory_units', true ) || 0 >= get_post_meta( $product_id, 'inventory_units', true ) ) {
                $button = '';
            }
        }
    }

    // Growers inventory check.
    if ( 'growers' == get_post_meta( $product_id, 'product_type', true ) ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            // If no clone or seed count has been added.
            if ( ! get_post_meta( get_the_ID(), 'seed_count', true ) && ! get_post_meta( get_the_ID(), 'clone_count', true ) ) {
                $button = '';
            }
            // Clone count.
            if ( get_post_meta( get_the_ID(), 'clone_count', true ) ) {
                if ( ! get_post_meta( get_the_ID(), 'inventory_clones', true ) || 0 >= get_post_meta( get_the_ID(), 'inventory_clones', true ) ) {
                    $button = '';
                }
            }
            // Seed count.
            if ( get_post_meta( get_the_ID(), 'seed_count', true ) ) {
                if ( ! get_post_meta( get_the_ID(), 'inventory_seeds', true ) || 0 >= get_post_meta( get_the_ID(), 'inventory_seeds', true ) ) {
                    $button = '';
                }
            }
        }
    }

    echo apply_filters( 'wpd_ecommerce_archive_items_buttons', $button );

}

/**
 * Require login to shop
 * 
 * This will hide the add to cart functionality if option is 
 * selected in the admin settings
 * 
 * @since 1.3
 */
function wpd_ecommerce_item_buttons() {

    // Check if user is required to login to shop.
	if ( ! is_user_logged_in() && 'on' == wpd_ecommerce_require_login_to_shop() ) {
        // Do nothing.
    } else {
        // Add buttons to the various shortcodes, archives, and widgets.
        add_action( 'wpd_ecommerce_archive_items_product_inside_after', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_shortcode_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_flowers_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_concentrates_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_edibles_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_prerolls_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_topicals_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_growers_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_gear_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        add_action( 'wpd_tinctures_widget_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
        // The action hook below is for WPD v3.0+
        add_action( 'wp_dispensary_widget_product_inside_bottom', 'wpd_ecommerce_archive_items_buttons' );
    }

}
add_action( 'plugins_loaded', 'wpd_ecommerce_item_buttons' );

/**
 * Product Buttons
 * 
 * This function gets the buy buttons for products
 * 
 * @since 1.0
 */
function get_wpd_ecommerce_product_buttons( $product_id ) {

    $post_type      = get_post_type( $product_id );
    $price_each     = get_post_meta( $product_id, 'price_each', true );
    $price_per_pack = get_post_meta( $product_id, 'price_per_pack', true );

    $str = '';

    if ( in_array( get_post_meta( $product_id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'growers', 'tinctures', 'gear' ) ) ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . esc_attr__( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_per_pack" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_each" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }

    if ( in_array( get_post_meta( $product_id, 'product_type', true ), array( 'flowers', 'concentrates' ) ) ) {
        if ( '' != $price_each ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . 'price_each" class="button wpd-buy-btn">' . esc_attr__( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . esc_attr__( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
    }

    return $str;
}

/**
 * Archive template data
 * 
 * Helper function to return specific data that's used in the Archive template
 * 
 * @since  1.8
 * @return array
 */
function wpd_ecommerce_archive_template_data() {
    if ( ! empty( $_GET['vendors'] ) ) {
        $vendor_name = get_term_by( 'slug', $_GET['vendors'], 'vendors' );
        $title       = $vendor_name->name;
    }
    
    /**
     * Taxonomy check.
     * 
     * @since 1.0
     */
    if ( is_tax() ) {

        global $wp_query;
        // Get current info.
        $tax = $wp_query->get_queried_object();
    
        //print_r( $tax );
    
        $taxonomy             = $tax->taxonomy;
        $taxonomy_slug        = $tax->slug;
        $taxonomy_name        = $tax->name;
        $taxonomy_count       = $tax->count;
        $taxonomy_description = $tax->description;
    
        $tax_query = array(
            'relation' => 'AND',
        );
    
        $tax_query[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $taxonomy_slug,
        );
    
        $menu_type_name        = $taxonomy_name;
        $menu_type_description = $taxonomy_description;
    
    } else {
        $tax_query = '';
    }
    
    /**
     * Set vendor page title.
     * 
     * @since 1.0
     */
    if ( ! empty( $_GET['vendors'] ) ) {
        $vendor_name    = get_term_by( 'slug', $_GET['vendors'], 'vendors' );
        $page_title     = $vendor_name->name;
        $menu_type_name = $page_title;
    } elseif ( is_tax() ) {
        // Do nothing.
    } else {
        // Get post type.
        $post_type = get_post_type();
    
        // Create post type variables.
        if ( $post_type ) {
            $post_type_data = get_post_type_object( $post_type );
            $post_type_name = $post_type_data->label;
            $post_type_slug = $post_type_data->rewrite['slug'];
            $menu_type_name = $post_type_name;
        }
    
    }

    // Build data array.
    $data = array(
        'menu_type_name' => $menu_type_name,
        'post_type'      => $post_type,
        'tax_query'      => $tax_query
    );

    return $data;
}
