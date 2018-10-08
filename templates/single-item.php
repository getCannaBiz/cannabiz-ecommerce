<?php
/*
Copy this file into your theme to customize
*/
get_header(); ?>
<div id="primary" class="col-lg-8 content-area">
    <main id="main" class="site-main" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            if ( isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) ) {
                echo "<div class='wpd-ecommerce-single-notifications'>";
                echo wpd_ecommerce_notifications();
                echo "</div>";
            } ?>
            <div class="entry-header wpd-ecommerce-shelfItem">
                <div class="image-wrapper">
                <?php
                if ( has_post_thumbnail()) {
                    echo the_post_thumbnail( 'wpd-small' );
                }
                ?>
                </div>
                <div class="product-details">
                    <header class="entry-header">
                        <h1 class="item_name"><?php the_title(); ?></h1>
                    </header>

                    <?php
                    // Display Flower Prices.
                    if ( 'flowers' === get_post_type() ) {
                        echo "<span class='wpd-ecommerce prices-flowers'>" . wpd_flowers_prices_simple() . "</span>";
                    }
                    ?>

                    <!-- ADD TO CART -->
                    <form name="add_to_cart" class="wpd-ecommerce" method="post">

                    <?php
                    // Prices.
                    if ( in_array( get_post_type( get_the_ID() ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
                        $regular_price  = esc_html( get_post_meta( get_the_ID(), '_priceeach', true ) );
                    } elseif ( 'topicals' === get_post_type() ) {
                        $regular_price = esc_html( get_post_meta( get_the_ID(), '_pricetopical', true ) );
                    } elseif ( 'flowers' === get_post_type() ) {
                        /**
                         * @todo make flower_names through the entier plugin filterable.
                         */
                        $flower_names = array(
                            '1 g'    => '_gram',
                            '2 g'    => '_twograms',
                            '1/8 oz' => '_eighth',
                            '5 g'    => '_fivegrams',
                            '1/4 oz' => '_quarter',
                            '1/2 oz' => '_halfounce',
                            '1 oz'   => '_ounce',
                        );
                        $regular_price = $flower_names;
                    } else {
                        $regular_price = '';
                    }

                    $sale_price = esc_html( get_post_meta( get_the_ID(), 'product_sale_price', true ) );
                    ?>
                    <?php if ( ! empty( $regular_price ) ) { ?>
                        <?php if ( 'flowers' === get_post_type() ) { ?>
                            <?php
                            printf( '<select name="wpd_ecommerce_flowers_prices" id="wpd_ecommerce_flowers_prices" class="widefat">' );
                            printf( '<option value="">Choose a weight</option>' );
                            foreach ( $regular_price as $name => $price ) {
                                printf( '<option value="'. esc_html( $price ) . '">' . esc_html( $name ) . '</option>' );
                            }
                            print( '</select>' );
                            ?>
                        <?php } else { ?>
                        <p class="wpd-ecommerce price"><?php echo CURRENCY; ?><?php echo number_format( $regular_price, 2, '.', ',' ); ?></p>
                        <?php } ?>
                    <?php } ?>

                    <?php
                    /**
                     * Add Items to Cart
                     */
                    if ( isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && isset( $_POST['wpd_ecommerce_flowers_prices'] ) ) {
                        $qtty = $_POST['qtty'];

                        /**
                         * ID's
                         */
                        $old_id = $post->ID;
                        $new_id = $post->ID . '' . $_POST['wpd_ecommerce_flowers_prices'];

                        /**
                         * Prices
                         */
                        $new_price = $_POST['wpd_ecommerce_flowers_prices'];
                        $old_price = get_post_meta( $old_id, $new_price, true );

                        /**
                         * Add items to cart
                         */
                        add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

                    } elseif ( isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) ) {
                        $qtty = $_POST['qtty'];
                        add_items_to_cart( $post->ID, $qtty, '', '', '' );
                    } else {
                        $qtty = 1;
                    }
                    ?>
                        <input type="number" name="qtty" id="qtty" value="1" class="item_Quantity" />
                        <input type="submit" class="item_add" id="add_item_btn" value="<?php echo __( 'Add to cart', 'wpd-ecommerce' ); ?>" name="add_me" />
                    </form>

                    <?php
                    // Display Item types.
                    echo "<div class='wpd-ecommerce item-types'>";
                    // Display Strain Type
                    echo "<span class='wpd-ecommerce strain-type'>" . get_the_term_list( get_the_ID(), 'strain_type', 'Strain type: ', ', ' ) . "</span>";
                    // Display Shelf Type
                    echo "<span class='wpd-ecommerce shelf-type'>" . get_the_term_list( get_the_ID(), 'shelf_type', '', ', ' ) . "</span>";

                    echo "</div>";

                    echo "<div class='wpd-ecommerce item-details'>";
                    /**
                     * @todo action hook here for people to add details before thc/cbd
                     */
                    if ( get_post_meta( get_the_ID(), '_thc', true ) ) {
                        $wpdthc = '<span class="wpd-ecommerce thc">' . __( 'THC', 'wp-dispensary' ) . ': ' . get_post_meta( get_the_id(), '_thc', true ) . '%</span>';
                    } else {
                        $wpdthc = '';
                    }
                    if ( get_post_meta( get_the_ID(), '_cbd', true ) ) {
                        $wpdcbd = '<span class="wpd-ecommerce cbd">' . __( 'CBD', 'wp-dispensary' ) . ': ' . get_post_meta( get_the_id(), '_cbd', true ) . '%</span>';
                    } else {
                        $wpdcbd = '';
                    }
                    /**
                     * @todo add additional details here, depending on menu type
                     */
                    //echo $wpdthc . $wpdcbd;
                    /**
                     * @todo action hook here for people to add details after thc/cbd
                     */
                    echo "</div>";
                    ?>

                    <!-- <p class="wpd-ecommerce-sku"><strong>SKU:</strong> <?php //echo esc_html( get_post_meta( get_the_ID(), 'product_sku', true ) ); ?> </p> -->

                    <?php if ( ! empty( $sale_price ) ) : ?>
                    <p class="wpd-ecommerce-sale-price"><?php echo esc_html( get_post_meta( get_the_ID(), 'product_sale_price', true ) ); ?></p>
                    <?php endif; ?>

                </div><!-- / .product-details -->
            </div><!-- / .wpd-ecommerce-shelfItem -->
            <div class="entry-content wpd-ecommerce-shelfContent">
                <?php the_content(); ?>
            </div><!-- / wpd-ecommerce-shelfContent -->
        </div>
    <?php endwhile; ?>
    </main>
</div>
<?php wp_reset_query(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
