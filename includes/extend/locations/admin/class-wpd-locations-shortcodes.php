<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Locations
 * @subpackage WPD_Locations/admin
 */

/**
 * Locations Shortcode Fuction
 */
function wp_dispensary_locations_shortcode( $atts ) {

	/* Attributes */
	extract( shortcode_atts(
		array(
			'posts'       => '100',
			'class'       => '',
			'id'          => '',
			'name'        => 'show',
			'info'        => 'show',
			'thc'         => '',
			'thca'        => '',
			'cbd'         => '',
			'cba'         => '',
			'cbn'         => '',
			'cbg'         => '',
			'totalthc'    => 'show',
			'title'       => 'Menu',
			'category'    => '',
			'aroma'       => '',
			'flavor'      => '',
			'effect'      => '',
			'symptom'     => '',
			'condition'   => '',
			'vendor'      => '',
			'shelf_type'  => '',
			'strain_type' => '',
			'weight'      => 'show',
			'orderby'     => '',
			'meta_key'    => '',
			'type'        => "products",
			'imgsize'     => 'dispensary-image',
			'location'    => '',
		),
		$atts,
		'wpd_locations'
	) );

	/**
	 * Defining variables
	 */
	$order    = '';
	$ordernew = '';

	/**
	 * Code to create shortcode
	 */

	// Create $tax_query variable.
	$tax_query = array(
		'relation' => 'AND',
	);

	// Add aromas to $tax_query.
	if ( '' !== $aroma ) {
		$tax_query[] = array(
			'taxonomy' => 'aromas',
			'field'    => 'slug',
			'terms'    => $aroma,
		);
	}

	// Add flavors to $tax_query.
	if ( '' !== $flavor ) {
		$tax_query[] = array(
			'taxonomy' => 'flavors',
			'field'    => 'slug',
			'terms'    => $flavor,
		);
	}

	// Add effects to $tax_query.
	if ( '' !== $effect ) {
		$tax_query[] = array(
			'taxonomy' => 'effects',
			'field'    => 'slug',
			'terms'    => $effect,
		);
	}

	// Add symptoms to $tax_query.
	if ( '' !== $symptom ) {
		$tax_query[] = array(
			'taxonomy' => 'symptoms',
			'field'    => 'slug',
			'terms'    => $symptom,
		);
	}

	// Add conditions to $tax_query.
	if ( '' !== $condition ) {
		$tax_query[] = array(
			'taxonomy' => 'conditions',
			'field'    => 'slug',
			'terms'    => $condition,
		);
	}

	// Add vendors to $tax_query.
	if ( '' !== $vendor ) {
		$tax_query[] = array(
			'taxonomy' => 'vendors',
			'field'    => 'slug',
			'terms'    => $vendor,
		);
	}

	// Add shelf types to $tax_query.
	if ( '' !== $shelf_type ) {
		$tax_query[] = array(
			'taxonomy' => 'shelf_types',
			'field'    => 'slug',
			'terms'    => $shelf_type,
		);
	}

	// Add strain types to $tax_query.
	if ( '' !== $strain_type ) {
		$tax_query[] = array(
			'taxonomy' => 'strain_types',
			'field'    => 'slug',
			'terms'    => $strain_type,
		);
	}

	// Order by.
	if ( '' !== $orderby ) {
		$order    = $orderby;
		$ordernew = 'ASC';
	}

	// Create $location_tax_query variable.
	$location_tax_query = array(
		'relation' => 'AND',
	);

	// Create $cat_tax_query variable.
	$cat_tax_query = array(
		'relation' => 'OR',
	);

	// Turn shortcode type="" input into an array.
	$array_type = explode( ', ', $type );

	// Turn shortcode category="" input into an array.
	$new_category = explode( ', ', $category );

	// If category="" isn't empty, add to $cat_tax_query.
	if ( ! empty( $category ) ) {
		$cat_tax_query[] = array(
			'taxonomy' => 'wpd_categories',
			'field'    => 'name',
			'terms'    => $new_category,
		);
	}

	$location_tax_query[] = array(
		'taxonomy' => 'locations',
		'field'    => 'name',
		'terms'    => $location,
	);

	// Create new tax query.
	$new_tax_query = array_merge( $tax_query, $cat_tax_query );

	// Create final tax query.
	$final_tax_query = array_merge( $new_tax_query, $location_tax_query );

	/**
	 * Example codes
	 * 
	*	echo '<h2>$tax_query</h2><pre>';
	*	print_r( $tax_query );
	*	echo '</pre>';

	*	echo '<h2>$cat_tax_query</h2><pre>';
	*	print_r( $cat_tax_query );
	*	echo '</pre>';

	*	echo '<h2>$location_tax_query</h2><pre>';
	*	print_r( $location_tax_query );
	*	echo '</pre>';
	*/

	// Create WP_Query args.
	$args = apply_filters( 'wpd_locations_shortcode_args', array(
		'post_type'      => explode( ', ', $type ),
		'posts_per_page' => $posts,
		'orderby'        => $order,
		'order'          => $ordernew,
		'meta_key'       => $meta_key,
		'tax_query'      => $final_tax_query,
	) );

	$wpdquery = new WP_Query( $args );

	if ( '' === $title ) {
		$showtitle = '';
	} else {
		$showtitle = '<h2 class="wpd-title">' . $title . '</h2>';
	}

	$wpdposts = '<div id="' . $id . '" class="wpdispensary">' . $showtitle;

	while ( $wpdquery->have_posts() ) :
		$wpdquery->the_post();

		if ( '' === $imgsize ) {
			$imagesize = 'dispensary-image';
		} else {
			$imagesize = $imgsize;
		}

		$thumbnail_id        = get_post_thumbnail_id();
		$thumbnail_url_array = wp_get_attachment_image_src( $thumbnail_id, $imagesize, false );
		$thumbnail_url       = $thumbnail_url_array[0];
		$querytitle          = get_the_title();

		if ( 'show' === $thc ) {
			if ( get_post_meta( get_the_ID(), 'compounds_thc', true ) ) {
				$thcinfo = '<span class="wpd-productinfo thc"><strong>' . esc_attr__( 'THC: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_thc', true ) . '%</span>';
			}
		} else {
			$thcinfo = '';
		}

		if ( 'show' === $thca ) {
			if ( get_post_meta( get_the_ID(), 'compounds_thca', true ) ) {
				$thcainfo = '<span class="wpd-productinfo thca"><strong>' . esc_attr__( 'THCA: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_thca', true ) . '%</span>';
			}
		} else {
			$thcainfo = '';
		}

		if ( 'show' === $cbd ) {
			if ( get_post_meta( get_the_ID(), 'compounds_cbd', true ) ) {
				$cbdinfo = '<span class="wpd-productinfo cbd"><strong>' . esc_attr__( 'CBD: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_cbd', true ) . '%</span>';
			}
		} else {
			$cbdinfo = '';
		}

		if ( 'show' === $cba ) {
			if ( get_post_meta( get_the_ID(), 'compounds_cba', true ) ) {
				$cbainfo = '<span class="wpd-productinfo cba"><strong>' . esc_attr__( 'CBA: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_cba', true ) . '%</span>';
			}
		} else {
			$cbainfo = '';
		}

		if ( 'show' === $cbn ) {
			if ( get_post_meta( get_the_ID(), 'compounds_cbn', true ) ) {
				$cbninfo = '<span class="wpd-productinfo cbn"><strong>' . esc_attr__( 'CBN: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_cbn', true ) . '%</span>';
			}
		} else {
			$cbninfo = '';
		}

		if ( 'show' === $cbg ) {
			if ( get_post_meta( get_the_ID(), 'compounds_cbg', true ) ) {
				$cbginfo = '<span class="wpd-productinfo cbg"><strong>' . esc_attr__( 'CBG: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_cbg', true ) . '%</span>';
			}
		} else {
			$cbginfo = '';
		}

		// Total THC (Servings X THC).
		if ( 'show' === $totalthc ) {
			if ( '' != get_post_meta( get_the_id(), 'compounds_thc', true ) && '' != get_post_meta( get_the_id(), 'product_servings', true ) ) {
				$total_thc = '<span class="wpd-productinfo thc"><strong>' . esc_attr__( 'THC: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_thc', true ) * get_post_meta( get_the_id(), 'product_servings', true ) . 'mg</span>';
			} else {
				$total_thc = '';
			}
		} else {
			$total_thc = '';
		}

		/*
		 * Get the seed count for Growers
		 */

		if ( get_post_meta( get_the_ID(), 'seed_count', true ) ) {
			$wpdseedcount = '<span class="wpd-productinfo seeds"><strong>' . esc_attr__( 'Seeds:', 'wpd-ecommerce' ) . '</strong> ' . get_post_meta( get_the_id(), 'seed_count', true );
		} else {
			$wpdseedcount = '';
		}

		/*
		 * Get the clone count for Growers
		 */

		if ( get_post_meta( get_the_ID(), 'clone_count', true ) ) {
			$wpdclonecount = '<span class="wpd-productinfo clones"><strong>' . esc_attr__( 'Clones:', 'wpd-ecommerce' ) . '</strong> ' . get_post_meta( get_the_id(), 'clone_count', true );
		} else {
			$wpdclonecount = '';
		}

		/** Get the details for Topicals */

		if ( get_post_meta( get_the_ID(), 'product_size', true ) ) {
			$topicalsize = '<span class="wpd-productinfo size"><strong>' . esc_attr__( 'Size:', 'wpd-ecommerce' ) . '</strong> ' . get_post_meta( get_the_id(), 'product_size', true ) . 'oz</span>';
		} else {
			$topicalsize = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_thc', true ) ) {
			$topicalthc = '<span class="wpd-productinfo thc"><strong>' . esc_attr__( 'THC:', 'wpd-ecommerce' ) . '</strong> ' . get_post_meta( get_the_id(), 'compounds_thc', true ) . 'mg</span>';
		} else {
			$topicalthc = '';
		}

		if ( get_post_meta( get_the_ID(), 'compounds_cbd', true ) ) {
			$topicalcbd = '<span class="wpd-productinfo cbd"><strong>' . esc_attr__( 'CBD:', 'wpd-ecommerce' ) . '</strong> ' . get_post_meta( get_the_id(), 'compounds_cbd', true ) . 'mg</span>';
		} else {
			$topicalcbd = '';
		}

		/*
		 * Get the weight for Pre-rolls
		 */
		if ( 'show' === $weight ) {
			if ( get_post_meta( get_the_ID(), 'product_weight', true ) ) {
				$prerollweight = '<span class="wpd-productinfo weight"><strong>' . esc_attr__( 'Weight:', 'wpd-ecommerce' ) . '</strong> ' . get_post_meta( get_the_id(), 'product_weight', true ) . 'g</span>';
			} else {
				$prerollweight = '';
			}
		} else {
			$prerollweight = '';
		}

		/*
		 * Get the details for Edibles
		 */

		if ( get_post_meta( get_the_ID(), 'compounds_thc', true ) ) {
			$thcmg = '<span class="wpd-productinfo thc"><strong>' . esc_attr__( 'THC: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'compounds_thc', true ) . 'mg</span>';
		} else {
			$thcmg = '';
		}

		$thcsep = ' - ';

		if ( get_post_meta( get_the_ID(), 'product_servings', true ) ) {
			$servingcount = '<span class="wpd-productinfo servings"><strong>' . esc_attr__( 'Servings: ', 'wpd-ecommerce' ) . '</strong>' . get_post_meta( get_the_id(), 'product_servings', true ) . '</span>';
		} else {
			$servingcount = '';
		}

		/** Check shortcode options input by user */

		if ( 'show' === $name ) {
			$showname = '<p class="wpd-producttitle"><strong><a href="' . get_permalink() . '">' . $querytitle . '</a></strong></p>';
		} else {
			$showname = '';
		}

		/** Growers */
		if ( in_array( get_post_type(), array( 'growers' ) ) ) {
			if ( 'show' === $info ) {
				$showinfo = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_growers_prices_simple() . '</span>' . $wpdseedcount . $wpdclonecount;
			} else {
				$showinfo = '';
			}
		}

		/** Topicals */
		if ( in_array( get_post_type(), array( 'topicals' ) ) ) {
			if ( 'show' === $info ) {
				$showinfo = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_topicals_prices_simple() . '</span>';
			} else {
				$showinfo = '';
			}
		}

		/** Pre-rolls */
		if ( in_array( get_post_type(), array( 'prerolls' ) ) ) {
			if ( 'show' === $info ) {
				$showinfo = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_prerolls_prices_simple() . '</span>' . $prerollweight;
			} else {
				$showinfo = '';
			}
		}

		/** Edibles */
		if ( in_array( get_post_type(), array( 'edibles' ) ) ) {
			if ( 'show' === $info ) {
				$showinfo = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_edibles_prices_simple() . '</span>' . $total_thc;
			} else {
				$showinfo = '';
			}
		}

		/** Concentrates */
		if ( in_array( get_post_type(), array( 'concentrates' ) ) ) {
			if ( 'show' === $info ) {
				$showinfo = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_concentrates_prices_simple() . '</span>';
			} else {
				$showinfo = '';
			}
		}

		/** Flowers */
		if ( in_array( get_post_type(), array( 'flowers' ) ) ) {
			if ( 'show' === $info ) {
				$showinfo = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_flowers_prices_simple() . '</span>';
			} else {
				$showinfo = '';
			}
		}

		// Image display codes.
		if ( null === $thumbnail_url && 'full' === $imagesize ) {
			$wpd_shortcodes_default_image = site_url() . '/wp-content/plugins/wp-dispensary/public/images/wpd-large.jpg';
			$defaultimg                   = apply_filters( 'wpd_shortcodes_default_image', $wpd_shortcodes_default_image );
			$showimage                    = '<a href="' . get_permalink() . '"><img src="' . $defaultimg . '" alt="Menu item" /></a>';
		} elseif ( null !== $thumbnail_url ) {
			$showimage = '<a href="' . get_permalink() . '"><img src="' . $thumbnail_url . '" alt="Menu item" /></a>';
		} else {
			$wpd_shortcodes_default_image = site_url() . '/wp-content/plugins/wp-dispensary/public/images/' . $imagesize . '.jpg';
			$defaultimg                   = apply_filters( 'wpd_shortcodes_default_image', $wpd_shortcodes_default_image );
			$showimage                    = '<a href="' . get_permalink() . '"><img src="' . $defaultimg . '" alt="Menu item" /></a>';
		}

		/** Shortcode display */

		ob_start();
			do_action( 'wpd_shortcode_inside_top' );
			$wpd_shortcode_inside_top = ob_get_contents();
		ob_end_clean();

		ob_start();
			do_action( 'wpd_shortcode_top_locations' );
			$wpd_shortcode_top_locations = ob_get_contents();
		ob_end_clean();

		$wpdposts .= '<div class="wpdshortcode wpd-locations ' . $class . '">' . $wpd_shortcode_top_locations . $wpd_shortcode_inside_top . $showimage;

		ob_start();
			do_action( 'wpd_shortcode_inside_bottom' );
			$wpd_shortcode_inside_bottom = ob_get_contents();
		ob_end_clean();

		ob_start();
			do_action( 'wpd_shortcode_bottom_locations' );
			$wpd_shortcode_bottom_locations = ob_get_contents();
		ob_end_clean();

		$wpdposts .= $showname . $showinfo . $wpd_shortcode_inside_bottom . $wpd_shortcode_bottom_locations . '</div>';

	endwhile;

	wp_reset_postdata();

	return $wpdposts . '</div>';

}
add_shortcode( 'wpd-locations', 'wp_dispensary_locations_shortcode' );
