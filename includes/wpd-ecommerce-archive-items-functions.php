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
    $price_each     = get_post_meta( $product_id, '_priceeach', true );
    $price_topical  = get_post_meta( $product_id, '_pricetopical', true );
    $price_per_pack = get_post_meta( $product_id, '_priceperpack', true );

    // Flowers.
    if ( 'flowers' == $post_type ) {
        // Create button.
        if ( '' != $price_each ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            // Remove button if inventory is empty.
            if ( ! get_post_meta( $product_id, '_inventory_flowers', true ) ) {
                $button = '';
            }    
        }
    }

    // Concentrates.
    if ( 'concentrates' == $post_type ) {
        // Create button.
        if ( '' != $price_each ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( '' != $price_each ) {
                // Remove button if inventory is empty.
                if ( ! get_post_meta( $product_id, '_inventory_concentrates_each', true ) ) {
                    $button = '';
                }
            } else {
                // Remove button if inventory is empty.
                if ( ! get_post_meta( $product_id, '_inventory_concentrates', true ) ) {
                    $button = '';
                }
            }
        }
    }

    // Edibles, Pre-rolls, Growers, Tinctures and Gear.
    if ( 'edibles' == $post_type || 'prerolls' == $post_type || 'growers' == $post_type || 'tinctures' == $post_type || 'gear' == $post_type ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }

    // Edibles inventory check.
    if ( 'edibles' == $post_type ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( ! get_post_meta( $product_id, '_inventory_edibles', true ) ) {
                $button = '';
            }
        }
    }

    // Pre-rolls inventory check.
    if ( 'prerolls' == $post_type ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( ! get_post_meta( $product_id, '_inventory_prerolls', true ) ) {
                $button = '';
            }
        }
    }

    // Topicals.
    if ( 'topicals' == $post_type ) {
    	if ( '' != $price_topical && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_topical && '' != $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_topical && '' === $price_per_pack ) {
            $button = '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_pricetopical" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            // Remove button if inventory is empty.
            if ( ! get_post_meta( $product_id, '_inventory_topicals', true ) ) {
                $button = '';
            }
        }
    }

    // Growers inventory check.
    if ( 'growers' == $post_type ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( get_post_meta( get_the_ID(), '_clonecount', true ) ) {
                if ( ! get_post_meta( get_the_ID(), '_inventory_clones', true ) ) {
                    $button = '';
                }
            }
            if ( get_post_meta( get_the_ID(), '_seedcount', true ) ) {
                if ( ! get_post_meta( get_the_ID(), '_inventory_seeds', true ) ) {
                    $button = '';
                }
            }
        }
    }

    // Tinctures inventory check.
    if ( 'tinctures' == $post_type ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( ! get_post_meta( $product_id, '_inventory_tinctures', true ) ) {
                $button = '';
            }
        }
    }

    // Gear inventory check.
    if ( 'gear' == $post_type ) {
        // Inventory management check.
        if ( function_exists( 'run_wpd_inventory' ) ) {
            if ( ! get_post_meta( $product_id, '_inventory_gear', true ) ) {
                $button = '';
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

	// Get WPD settings from General tab.
	$wpdas_general = get_option( 'wpdas_general' );

    // Check if user is required to be logged in to shop.
	if ( isset( $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'] ) ) {
		$login_to_shop = $wpdas_general['wpd_ecommerce_cart_require_login_to_shop'];
	} else {
		$login_to_shop = NULL;
	}

    // Check if user is required to login to shop.
	if ( ! is_user_logged_in() && 'on' == $login_to_shop ) {
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
    $price_each     = get_post_meta( $product_id, '_priceeach', true );
    $price_topical  = get_post_meta( $product_id, '_pricetopical', true );
    $price_per_pack = get_post_meta( $product_id, '_priceperpack', true );

    $str = '';

    if ( 'edibles' == $post_type || 'prerolls' == $post_type || 'growers' == $post_type || 'tinctures' == $post_type || 'gear' == $post_type ) {
    	if ( '' != $price_each && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_each && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_each && '' === $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }

    if ( 'topicals' == $post_type ) {
    	if ( '' != $price_topical && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' === $price_topical && '' != $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceperpack" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } elseif ( '' != $price_topical && '' === $price_per_pack ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_pricetopical" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            // Do nothing.
        }
    }

    if ( 'flowers' == $post_type || 'concentrates' == $post_type ) {
        if ( '' != $price_each ) {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '?add_item=' . $product_id . '_priceeach" class="button wpd-buy-btn">' . __( 'Buy Now', 'wpd-ecommerce' ) . '</a>';
        } else {
            $str .= '<a href="' . get_the_permalink( $product_id ) . '" class="button wpd-buy-btn">' . __( 'Select Options', 'wpd-ecommerce' ) . '</a>';
        }
    }

    return $str;
}
