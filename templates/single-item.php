<?php
/*
Copy this file into your theme to customize
*/
get_header();

do_action( 'wpd_ecommerce_templates_single_items_wrap_before' );
?>

    <?php while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php do_action( 'wpd_ecommerce_templates_single_items_entry_header_before' ); ?>

            <div class="entry-header wpd-ecommerce-shelfItem">

                <div class="image-wrapper">
                <?php
                if ( has_post_thumbnail() ) {
                    $img_size = apply_filters( 'wpd_ecommerce_single_item_image_size', 'wpd-small' );
                    echo '<a href="' . get_the_post_thumbnail_url( get_the_ID(), 'full' ) . '"><img src="' . get_the_post_thumbnail_url( get_the_ID(), $img_size ) . '" alt="' . get_the_title() . '" /></a>';
                } else {
                    $no_img = get_bloginfo( 'url' ) . '/wp-content/plugins/wp-dispensary/public/images/wpd-medium.jpg';
                    echo '<img src="' . apply_filters( 'wpd_ecommerce_single_item_no_image', $no_img ) . '" alt="' . get_the_title() . '" />';
                }
                ?>
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

                    // Display Strain Type.
                    echo '<span class="wpd-ecommerce strain-type">' . get_the_term_list( get_the_ID(), 'strain_type', '', '' ) . '</span>';
                    // Display Shelf Type.
                    echo '<span class="wpd-ecommerce shelf-type">' . get_the_term_list( get_the_ID(), 'shelf_type', '', '' ) . '</span>';
                    // Display Edibles Category.
                    echo '<span class="wpd-ecommerce category edibles">' . get_the_term_list( get_the_ID(), 'edibles_category', '', '' ) . '</span>';
                    // Display Topicals Category.
                    echo '<span class="wpd-ecommerce category topicals">' . get_the_term_list( get_the_ID(), 'topicals_category', '', '' ) . '</span>';

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
<?php do_action( 'wpd_ecommerce_templates_single_items_wrap_after' ); ?>
<?php wp_reset_query(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
