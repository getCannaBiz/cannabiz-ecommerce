<?php
/** 
 * Copy this file into your theme to customize
 * 
 * @package WPD_eCommerce
 * @author  CannaBiz Software <contact@cannabizsoftware.com>
 * @license GPL-2.0+ 
 * @link    https://cannabizsoftware.com
 * @since   1.0.0
 */
get_header();

// Get archive template data.
$archive_data = wpd_ecommerce_archive_template_data();

do_action( 'wpd_ecommerce_templates_archive_items_before' );

do_action( 'wpd_ecommerce_templates_archive_items_wrap_before' );
?>

    <?php if ( have_posts() ) : ?>
    <div class="wpdispensary">
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( get_term_meta( get_queried_object_id(), 'vendor_logo', true ) ) { ?>
                <div class="vendor-logo">
                    <?php echo wp_get_attachment_image( get_term_meta( get_queried_object_id(), 'vendor_logo', true ), 'full' ); ?>
                </div><!-- /.vendor-logo -->
                <h2 class="wpd-title" style="display:none;"><?php esc_attr_e( $archive_data['menu_type_name'], 'wpd-ecommerce' ); ?></h2>
            <?php } else { ?>
                <h2 class="wpd-title"><?php esc_attr_e( $archive_data['menu_type_name'], 'wpd-ecommerce' ); ?></h2>
            <?php } ?>
            <div class="item-wrapper">
                <div class="wpd-row">
                <?php
                    $count  = 0;
                    $perrow = ( empty( $options['perrow'] ) ) ? apply_filters( 'wpd_ecommerce_template_archive_items_per_row', 3 ) : $options['perrow'];

                    // Get the posts.
                    $loop = new WP_Query(
                        array(
                            'post_status' => 'publish',
                            'post_type'   => $archive_data['post_type'],
                            'tax_query'   => $archive_data['tax_query']
                          //'posts_per_page' => $options['perpage'] /** @todo create admin setting for this */
                        )
                    );

                    // Loop through each post.
                    while ( $loop->have_posts() ) : $loop->the_post();

                    echo ( $count % $perrow === 0 && $count > 0 ) ? '</div><div class="wpd-row">' : '';
                    ?>
                    <?php do_action( 'wpd_ecommerce_archive_items_product_before' ); ?>
                    <div class="wpdshortcode item">
                        <?php do_action( 'wpd_ecommerce_archive_items_product_inside_before' ); ?>
                        <?php echo wpd_product_image( get_the_ID(), apply_filters( 'wpd_ecommerce_archive_items_product_image_size', 'wpd-small' ) ); ?>
                        <p class="wpd-producttitle"><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></p>
                        <div class="product-details">
                        <?php wpd_all_prices_simple( get_the_ID(), true, true ); ?>
                        <?php
                            // Get product details.
                            $product_details = array(
                                'thc'         => 'show',
                                'thca'        => '',
                                'cbd'         => 'show',
                                'cba'         => '',
                                'cbn'         => '',
                                'cbg'         => '',
                                'seed_count'  => 'show',
                                'clone_count' => 'show',
                                'total_thc'   => 'show',
                                'thc_topical' => 'show',
                                'size'        => 'show',
                                'servings'    => '',
                                'weight'      => 'show'
                            );

                            // Apply filters to product details.
                            $product_details = apply_filters( 'wpd_ecommerce_template_archive_items_product_details', $product_details );

                            // Return product details.
                            wpd_product_details( get_the_ID(), $product_details, 'span' );
                        ?>
                        </div><!-- /.product-details -->
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
                    // Only paginate if we have more than one page.
                    if ( $total > 1 ) {
                        // Get the current page.
                        if ( ! $current_page = get_query_var( 'paged' ) ) {
                            $current_page = 1;
                            $link_format  = empty( get_option( 'permalink_structure' ) ) ? '&page=%#%' : '/page/%#%/';
                            // Display pagination links.
                            echo paginate_links( array(
                                'base'      => get_pagenum_link( 1 ) . '%_%',
                                'format'    => $link_format,
                                'current'   => $current_page,
                                'total'     => $total,
                                'mid_size'  => 4,
                                'prev_text' => '&larr;',
                                'next_text' => '&rarr;',
                                'type'      => 'list'
                            ) );
                        }
                    }
                    ?>
                    </div><!-- // .wpd-page-links -->
                </div><!-- // .row -->
            </div><!-- // .item-wrapper -->
        </div><!-- // #post -->
    </div><!-- // .wpdispensary -->
    <?php endif; ?>

    <?php do_action( 'wpd_ecommerce_templates_archive_items_wrap_after' ); ?>
<?php
get_footer();
