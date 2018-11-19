<?php
/** 
 * Copy this file into your theme to customize
 * 
 * @version 1.0
 * @since 1.0
 */
get_header();

do_action( 'wpd_ecommerce_templates_archive_items_before' );

/**
 * Set image size
 * 
 * @todo Move this along with the code below to run on the do_action
 * from within the functions file for templates
 * 
 * @since 1.0
 */
if ( empty( $img_size ) ) {
    $image_size = 'dispensary-image';
} else {
    $image_size = $img_size;
}

$thumbnail_id        = get_post_thumbnail_id();
$thumbnail_url_array = wp_get_attachment_image_src( $thumbnail_id, $image_size, false );
$thumbnail_url       = $thumbnail_url_array[0];
$querytitle          = get_the_title();
$vendor_name         = get_term_by( 'slug', $_GET['vendor'], 'vendor' );
$title               = $vendor_name->name;

//print_r( $vendor_name );

//    echo $querytitle;

//    echo $vendor_name;

/**
 * Taxonomy check.
 * 
 * @since 1.0
 */
if ( is_tax() ) {

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
 * Set order_by.
 * 
 * @since 1.0
 */
if ( '' !== $order_by ) {
        $order    = $order_by;
        $ordernew = 'ASC';
}

/**
 * Set vendor page title.
 * 
 * @since 1.0
 */
if ( ! empty( $_GET['vendor'] ) ) {
    echo "<h1>TESTING</h1>";
    $vendor_name = get_term_by( 'slug', $_GET['vendor'], 'vendor' );
    $page_title = __( $vendor_name->name, 'wpd-ecommerce' );
    $menu_type_name = $page_title;
} elseif( is_tax() ) {
    
} else {
    // Get post type.
    $post_type = get_post_type();

    // Create post type variables.
    if ( $post_type ) {
        $post_type_data = get_post_type_object( $post_type );
        $post_type_name = $post_type_data->label;
        $post_type_slug = $post_type_data->rewrite['slug'];
        //echo $post_type_slug;
        //echo $post_type_name;
        $menu_type_name = $post_type_name;
    }

    /*
    echo "<pre>";
    print_r( $post_type_data );
    echo "</pre>";
    */

    if ( is_post_type_archive( $post_type_slug ) ) {
        //echo "<h2>Menu: " . $post_type_slug . "</h2>";
    }

}

//print_r( $vendor_name );

/**
 * @todo wrap this outer wrap in an action hook, like WooCommerce
 */
?>
<div id="primary" class="col-lg-8 content-area">
    <main id="main" class="site-main" role="main">

    <?php if ( have_posts() ) : ?>
    <div class="wpdispensary">
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2 class="wpd-title"><?php _e( $menu_type_name, 'wpd-ecommerce' ); ?></h2>
            <div class="item-wrapper">
                <div class="wpd-row">
                <?php
                    $count      = 0;
                    $perrow     = ( empty( $options['perrow'] ) ) ? 3 : $options['perrow'];
                    $item_width = ( empty( $options['perrow'] ) ) ? 32 : ( 100 / $options['perrow'] ) - 1;
                    
                    // Get the posts.
                    $loop = new WP_Query(
                        array(
                            'post_status' => 'publish',
                            'post_type'   => $post_type,
                            'tax_query'   => $tax_query
    //                      'posts_per_page' => $options['perpage'] /** @todo create admin setting for this */
                        )
                    );
                    /*
                    echo "<pre>";
                    print_r( $loop );
                    echo "</pre>";
                    */

                    // Loop through each post.
                    while ( $loop->have_posts() ) : $loop->the_post();

                    echo ( $count % $perrow === 0 && $count > 0 ) ? '</div><div class="wpd-row">' : '';

                    // Create variables.
                    $thumbnail_id        = get_post_thumbnail_id();
                    $thumbnail_url_array = wp_get_attachment_image_src( $thumbnail_id, $image_size, false );
                    $thumbnail_url       = $thumbnail_url_array[0];
                    if ( null === $thumbnail_url && 'full' === $image_size ) {
                        $wpd_shortcodes_default_image = site_url() . '/wp-content/plugins/wp-dispensary/public/images/wpd-large.jpg';
                        $defaultimg                   = apply_filters( 'wpd_shortcodes_default_image', $wpd_shortcodes_default_image );
                        $showimage                    = '<a href="' . get_permalink() . '"><img src="' . $defaultimg . '" alt="' . __( 'Menu - Edible', 'wp-dispensary' ) . '" /></a>';
                    } elseif ( null !== $thumbnail_url ) {
                        $showimage = '<a href="' . get_permalink() . '"><img src="' . $thumbnail_url . '" alt="' . __( 'Menu - Edible', 'wp-dispensary' ) . '" /></a>';
                    } else {
                        $wpd_shortcodes_default_image = site_url() . '/wp-content/plugins/wp-dispensary/public/images/' . $image_size . '.jpg';
                        $defaultimg                   = apply_filters( 'wpd_shortcodes_default_image', $wpd_shortcodes_default_image );
                        $showimage                    = '<a href="' . get_permalink() . '"><img src="' . $defaultimg . '" alt="' . __( 'Menu - Edible', 'wp-dispensary' ) . '" /></a>';
                    }
                ?>
                    <?php do_action( 'wpd_ecommerce_archive_items_product_before' ); ?>
                    <div class="wpdshortcode item" style="width:<?php echo ceil( $item_width ); ?>%;">
                        <?php do_action( 'wpd_ecommerce_archive_items_product_inside_before' ); ?>
                        <?php echo $showimage; ?>
                        <p class="wpd-producttitle"><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></p>
                        <?php
                            wpd_all_prices_simple( NULL, TRUE );
                            /**
                             * @todo add customization filter for this to include items in the array spot.
                             */
                            //esc_html( wpd_compounds_simple( $post_type_slug, array() ) );
                        ?>
                        <?php do_action( 'wpd_ecommerce_archive_items_product_inside_after' ); ?>
                    </div><!-- // .wpdshortcode item -->
                    <?php do_action( 'wpd_ecommerce_archive_items_product_after' ); ?>
                    <?php
                        $count++;
                        endwhile;
                    ?>
                    <div class="wpd-page-numbers">
                    <?php
                        // Get total number of pages
                        global $wp_query;
                        $total = $wp_query->max_num_pages;
                        // Only paginate if we have more than one page
                        if ( $total > 1 )  {
                            // Get the current page
                            if ( ! $current_page = get_query_var( 'paged' ) )
                                $current_page = 1;
                                $format = empty( get_option( 'permalink_structure' ) ) ? '&page=%#%' : 'page/%#%/';
                                echo paginate_links( array(
                                    'base'      => get_pagenum_link(1) . '%_%',
                                    'format'    => $format,
                                    'current'   => $current_page,
                                    'total'     => $total,
                                    'mid_size'  => 4,
                                    'prev_text' => '&larr;',
                                    'next_text' => '&rarr;',
                                    'type'      => 'list'
                                ) );
                        }
                        //the_posts_navigation();
                    ?>
                    </div><!-- // .wpd-page-links -->
                </div><!-- // .row -->
            </div><!-- // .item-wrapper -->
        </div><!-- // #post -->
    </div><!-- // .wpdispensary -->
    <?php endif; ?>

    <?php
    /**
     * @todo wrap this outer wrap in an action hook, like WooCommerce
     */
    ?>
    </main>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
