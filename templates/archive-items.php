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
 * Check if vendor is searched.
 * 
 * @since 1.0
 */
if ( ! empty( $_GET['vendor'] ) ) {
    $tax_query = array(
        'relation' => 'AND',
    );
    $tax_query[] = array(
        'taxonomy' => 'vendor',
        'field'    => 'slug',
        'terms'    => $_GET['vendor'],
    );
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
                    $item_width = ( empty( $options['perrow'] ) ) ? 33 : ( 100 / $options['perrow'] ) - 1;
                    
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

                <?php;

                //print_r( $testing_this );
                /**
                 * @todo
                 * 
                 * Add the shortcode layout here, and customize it
                 */
                ?>
                <div class="wpdshortcode item" style="width:<?php echo ceil( $item_width ); ?>%;">
                    <?php echo $showimage; ?>
                    <p class="wpd-producttitle"><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></p>
                    <?php esc_html( get_wpd_all_prices_simple( NULL ) ); ?>
                    <?php esc_html( wpd_compounds_simple( $post_type_slug, array( 'thc', 'cbd' ) ) ); ?>
                </div><!-- // .wpdshortcode item -->
                <?php
                    $count++;
                    endwhile;
                ?>
                </div><!-- // .row -->
            </div><!-- // .item -->
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
