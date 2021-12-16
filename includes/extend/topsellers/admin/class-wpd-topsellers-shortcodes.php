<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_TopSellers
 * @subpackage WPD_TopSellers/admin
 */

/**
 * Top Sellers Shortcode Fuction
 */
function wpdispensary_topsellers_shortcode( $atts ) {

	// Menu types (array).
	$array_types = wpd_menu_types();

	// Loop through menu types.
	foreach ( $array_types as $key=>$value ) {
		// Strip wpd- from the menu type name.
		$name = str_replace( 'wpd-', '', $key );
		// Add menu type name to new array.
		$menu_types_simple[] = $name;
	}

	// Menu types (string).
	$menu_types = implode ( ', ', $menu_types_simple );
	
	/* Attributes */
	extract( shortcode_atts(
		array(
			'posts'       => '100',
			'class'       => '',
			'id'          => '',
			'title'       => 'Top Sellers',
			'name'        => 'show',
			'info'        => 'show',
			'price'       => 'show',
			'thc'         => 'show',
			'thca'        => '',
			'cbd'         => '',
			'cba'         => '',
			'cbn'         => '',
			'cbg'         => '',
			'aroma'       => '',
			'flavor'      => '',
			'effect'      => '',
			'symptom'     => '',
			'condition'   => '',
			'vendor'      => '',
			'shelf_type'  => '',
			'strain_type' => '',
			'category'    => '',
			'seed_count'  => 'show',
			'clone_count' => 'show',
			'total_thc'   => 'show',
			'size'        => 'show',
			'weight'      => 'show',
			'servings'    => '',
			'order'       => 'DESC',
			'orderby'     => '',
			'type'        => $menu_types,
			'image'       => 'show',
			'imgsize'     => 'wpd-small',
			'viewall'     => ''
		),
		$atts,
		'wpd_topsellers'
	) );

	// Create $tax_query variable.
	$tax_query = array(
		'relation' => 'AND',
	);

	// Add aromas to $tax_query.
	if ( '' !== $aroma ) {
		$tax_query[] = array(
			'taxonomy' => 'aroma',
			'field'    => 'slug',
			'terms'    => $aroma,
		);
	}

	// Add flavors to $tax_query.
	if ( '' !== $flavor ) {
		$tax_query[] = array(
			'taxonomy' => 'flavor',
			'field'    => 'slug',
			'terms'    => $flavor,
		);
	}

	// Add effects to $tax_query.
	if ( '' !== $effect ) {
		$tax_query[] = array(
			'taxonomy' => 'effect',
			'field'    => 'slug',
			'terms'    => $effect,
		);
	}

	// Add symptoms to $tax_query.
	if ( '' !== $symptom ) {
		$tax_query[] = array(
			'taxonomy' => 'symptom',
			'field'    => 'slug',
			'terms'    => $symptom,
		);
	}

	// Add conditions to $tax_query.
	if ( '' !== $condition ) {
		$tax_query[] = array(
			'taxonomy' => 'condition',
			'field'    => 'slug',
			'terms'    => $condition,
		);
	}

	// Add vendors to $tax_query.
	if ( '' !== $vendor ) {
		$tax_query[] = array(
			'taxonomy' => 'vendor',
			'field'    => 'slug',
			'terms'    => $vendor,
		);
	}

	// Add shelf types to $tax_query.
	if ( '' !== $shelf_type ) {
		$tax_query[] = array(
			'taxonomy' => 'shelf_type',
			'field'    => 'slug',
			'terms'    => $shelf_type,
		);
	}

	// Add strain types to $tax_query.
	if ( '' !== $strain_type ) {
		$tax_query[] = array(
			'taxonomy' => 'strain_type',
			'field'    => 'slug',
			'terms'    => $strain_type,
		);
	}

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

		// Add flowers categories to $cat_tax_query.
		if ( in_array( 'flowers', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'flowers_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}
		
		// Add concentrates categories to $cat_tax_query.
		if ( in_array( 'concentrates', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'concentrates_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}
		
		// Add edibles categories to $cat_tax_query.
		if ( in_array( 'edibles', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'edibles_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}
		
		// Add flowers categories to $cat_tax_query.
		if ( in_array( 'prerolls', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'flowers_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}
		
		// Add topicals categories to $cat_tax_query.
		if ( in_array( 'topicals', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'topicals_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}

		// Add growers categories to $cat_tax_query.
		if ( in_array( 'growers', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'growers_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}

		// Add tinctures categories to $cat_tax_query.
		if ( in_array( 'tinctures', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'wpd_tinctures_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}

		// Add gear categories to $cat_tax_query.
		if ( in_array( 'gear', $array_type ) ) {
			$cat_tax_query[] = array(
				'taxonomy' => 'wpd_gear_category',
				'field'    => 'name',
				'terms'    => $new_category,
			);
		}
	}

	// Create new tax query.
	$new_tax_query = array_merge( $tax_query, $cat_tax_query );

	/**
	 * Code to create shortcode for Dispensary Top Sellers
	 */
	$args = apply_filters( 'wpd_topsellers_shortcode_args', array(
		'post_type'      => explode( ', ', $type ),
		'posts_per_page' => $posts,
		'orderby'        => $orderby,
		'order'          => $order,
		'meta_key'       => 'wpd_topsellers',
		'meta_value'     => 'add_wpd_topsellers',
		'tax_query'      => $new_tax_query,
	) );

	$wpdquery = new WP_Query( $args );

	// Get post type name.
	$post_type_data = get_post_type_object( $value );
	$post_type_name = $post_type_data->label;
	$post_type_slug = $post_type_data->rewrite['slug'];

	// Menu type name.
	$menu_type_name = $post_type_name;

	// Image size.
	if ( '' === $image_size ) {
		$img_size = 'wpd-small';
	} else {
		$img_size = $image_size;
	}

	// Product details.
	$product_details = array(
		'thc'         => $thc,
		'thca'        => $thca,
		'cbd'         => $cbd,
		'cba'         => $cba,
		'cbn'         => $cbn,
		'cbg'         => $cbg,
		'seed_count'  => $seed_count,
		'clone_count' => $clone_count,
		'total_thc'   => $total_thc,
		'size'        => $size,
		'servings'    => $servings,
		'weight'      => $weight
	);

	if ( '' === $title ) {
		$showtitle = '';
	} else {
		$showtitle = '<h2 class="wpd-title">' . $title . '</h2>';
	}

	$wpd_products = '<div id="' . $id . '" class="wpdispensary">' . $showtitle . '<div class="wpd-menu">';

	while ( $wpdquery->have_posts() ) : $wpdquery->the_post();

		/** Check shortcode options input by user */

		// Show name.
		if ( 'show' === $name ) {
			$show_name = '<h2 class="wpd-producttitle"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
		} else {
			$show_name = '';
		}

		// Show Price.
		if ( 'show' === $price ) {
			$show_price = '<span class="wpd-productinfo pricing"><strong>' . esc_html( get_wpd_pricing_phrase( $singular = true ) ) . ':</strong> ' . get_wpd_all_prices_simple( get_the_ID() ) . '</span>';

			// Filter price.
			$show_price = apply_filters( 'wpd_shortcodes_product_price', $show_price );
		} else {
			$show_price = '';
		}

		// Set empty variable for info.
		$show_info = '';

		// Show info.
		if ( 'show' === $info ) {
			$show_info = get_wpd_product_details( get_the_ID(), $product_details, 'span' );
		}

		// Set empty variable for image.
		$wpd_image = '';

		// Set image if set to show.
		if ( 'show' === $image ) {
			$wpd_image = get_wpd_product_image( get_the_ID(), $imgsize );
		}

		ob_start();
			do_action( 'wpd_shortcode_inside_top' );
			$wpd_shortcode_inside_top = ob_get_contents();
		ob_end_clean();

		ob_start();
			do_action( 'wpd_shortcode_top_topsellers' );
			$wpd_shortcode_top_topsellers = ob_get_contents();
		ob_end_clean();

		// Shortcode item start.
		$wpd_products .= '<div class="wpdshortcode wpd-topsellers ' . $class . '">' . $wpd_shortcode_top_topsellers . $wpd_shortcode_inside_top . $wpd_image;

		ob_start();
			do_action( 'wpd_shortcode_inside_bottom' );
			$wpd_shortcode_inside_bottom = ob_get_contents();
		ob_end_clean();

		ob_start();
			do_action( 'wpd_shortcode_bottom_topsellers' );
			$wpd_shortcode_bottom_topsellers = ob_get_contents();
		ob_end_clean();

		// Shortcode item.
		$wpd_products .= $show_name . $show_price . $show_info . $wpd_shortcode_inside_bottom . $wpd_shortcode_bottom_topsellers . '</div>';

	endwhile;

	wp_reset_postdata();

	return $wpd_products . '</div>';

}
add_shortcode( 'wpd-topsellers', 'wpdispensary_topsellers_shortcode' );

/**
 * Add TOP SELLERS icon to WPD shortcodes
 * through the use of the WPD action hooks
 */
function wpd_topsellers_icon() {
	if ( get_post_meta( get_the_ID(), 'product_featured', true ) ) { ?>
		<span class="wpd-topsellers-icon"><i class="fa fa-trophy" aria-hidden="true"></i></span>
	<?php }
}
add_action( 'wpd_shortcode_inside_top', 'wpd_topsellers_icon' );

/**
 * Add TOP SELLERS icon to WPD data output
 * through the use of the WPD action hooks
 */
function wpd_topsellers_icon_single() {
	if ( get_post_meta( get_the_ID(), 'product_featured', true ) ) { ?>
		<span class="wpd-topsellers-icon single"><i class="fa fa-trophy" aria-hidden="true"></i></span>
	<?php }
}
add_action( 'wpd_dataoutput_before', 'wpd_topsellers_icon_single' );
