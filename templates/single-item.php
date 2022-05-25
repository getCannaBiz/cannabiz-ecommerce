<?php
/**
 * Copy this file into your theme to customize
 * 
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 */
get_header();

do_action( 'wpd_ecommerce_templates_single_items_wrap_before' );
?>

    <?php while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php do_action( 'wpd_ecommerce_templates_single_items_entry_header_before' ); ?>

            <div class="entry-header wpd-ecommerce-shelfItem">

                <div class="image-wrapper">
                    <?php wpd_product_image( get_the_ID(), 'wpd-medium' ); ?>
                </div><!-- // .image-wrapper -->

                <div class="product-details">

                    <?php do_action( 'wpd_ecommerce_templates_single_items_entry_title_before' ); ?>

                    <header class="entry-header">
                        <h1 class="item_name"><?php the_title(); ?></h1>
                    </header>

                    <?php
                    do_action( 'wpd_ecommerce_templates_single_items_entry_title_after' );

                    do_action( 'wpd_ecommerce_item_types_before' );

                    // Display Item types.
                    echo '<div class="wpd-ecommerce item-types">';

                    do_action( 'wpd_ecommerce_item_types_inside_before' );

                    do_action( 'wpd_ecommerce_templates_single_items_item_types_inside' );

                    do_action( 'wpd_ecommerce_item_types_inside_after' );

                    // End item-types div.
                    echo '</div>';

                    do_action( 'wpd_ecommerce_item_types_after' );

                    do_action( 'wpd_ecommerce_item_details_before' );

                    echo '<div class="wpd-ecommerce item-details">';

                    do_action( 'wpd_ecommerce_item_details_inside_before' );

                    // Get compounds.
                    $compounds = get_wpd_compounds_simple( get_the_ID(), $type = '%', array( 'thc', 'cbd' ) );

                    // Show compounds.
                    if ( $compounds ) {
                        echo $compounds;
                    }

                    do_action( 'wpd_ecommerce_item_details_inside_after' );

                    echo '</div>';

                    do_action( 'wpd_ecommerce_item_details_after' );
                    ?>

                </div><!-- / .product-details -->
            </div><!-- / .wpd-ecommerce-shelfItem -->

            <?php do_action( 'wpd_ecommerce_templates_single_items_entry_header_after' ); ?>

            <?php do_action( 'wpd_ecommerce_templates_single_items_entry_content_before' ); ?>

            <div class="entry-content wpd-ecommerce-shelfContent">
                <?php
                    do_action( 'wpd_ecommerce_single_item_content_before' );
                    the_content();
                    do_action( 'wpd_ecommerce_single_item_content_after' );
                ?>
            </div><!-- / wpd-ecommerce-shelfContent -->

            <?php do_action( 'wpd_ecommerce_templates_single_items_entry_content_after' ); ?>

        </div>
    <?php endwhile; ?>
<?php

do_action( 'wpd_ecommerce_templates_single_items_wrap_after' );

wp_reset_query();

get_sidebar();
get_footer();
