<?php
/*
Copy this file into your theme to customize
*/
get_header();

do_action( 'wpd_ecommerce_templates_single_items_wrap_before' );
?>

    <?php while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            /**
             * @todo add this to an action hook
             * @todo replace this with the action hook
             */
            echo wpd_ecommerce_notifications();
            ?>
            <div class="entry-header wpd-ecommerce-shelfItem">
                <div class="image-wrapper">
                <?php
                /**
                 * @todo make the image size filterable.
                 */
                if ( has_post_thumbnail()) {
                    echo the_post_thumbnail( 'wpd-small' );
                }
                ?>
                </div>
                <?php
                /**
                 * @todo make these two filterable somehow, so they can be
                 * switched to be left/right aligned with each other, for the 
                 * people who want the content on the left and pictures on the right.
                 * 
                 */
                ?>
                <div class="product-details">
                    <?php
                    /**
                     * @todo add this to an action hook.
                     * @todo replace this with the action hook
                     * This will keep the template cleaner for developers building custom layouts.
                     */
                    // Get vendors.
                    if ( get_the_term_list( get_the_ID(), 'vendor', true ) ) {
                        $wpdvendors = '<span class="wpd-ecommerce vendors">' . get_the_term_list( $post->ID, 'vendor', '', ', ', '' ) . '</span>';
                    } else {
                        $wpdvendors = '';
                    }
                    // Display vendors.
                    echo $wpdvendors;
                    ?>
                    <header class="entry-header">
                        <h1 class="item_name"><?php the_title(); ?></h1>
                    </header>

                    <!-- ADD TO CART -->
                    <form name="add_to_cart" class="wpd-ecommerce" method="post">

                    <?php
                    // Prices.
                    if ( in_array( get_post_type( get_the_ID() ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
                        $regular_price = esc_html( get_post_meta( get_the_ID(), '_priceeach', true ) );
                        $pack_price    = esc_html( get_post_meta( get_the_ID(), '_priceperpack', true ) );
                        $pack_units    = esc_html( get_post_meta( get_the_ID(), '_unitsperpack', true ) );
                    } elseif ( 'topicals' === get_post_type() ) {
                        $regular_price = esc_html( get_post_meta( get_the_ID(), '_pricetopical', true ) );
                        $pack_price    = esc_html( get_post_meta( get_the_ID(), '_priceperpack', true ) );
                        $pack_units    = esc_html( get_post_meta( get_the_ID(), '_unitsperpack', true ) );
                    } elseif ( 'flowers' === get_post_type() ) {
                        /**
                         * @todo make flower_names through the entire plugin filterable.
                         */
                        $flower_names = array(
                            '1 g'    => esc_html( get_post_meta( get_the_ID(), '_gram', true ) ),
                            '2 g'    => esc_html( get_post_meta( get_the_ID(), '_twograms', true ) ),
                            '1/8 oz' => esc_html( get_post_meta( get_the_ID(), '_eighth', true ) ),
                            '5 g'    => esc_html( get_post_meta( get_the_ID(), '_fivegrams', true ) ),
                            '1/4 oz' => esc_html( get_post_meta( get_the_ID(), '_quarter', true ) ),
                            '1/2 oz' => esc_html( get_post_meta( get_the_ID(), '_halfounce', true ) ),
                            '1 oz'   => esc_html( get_post_meta( get_the_ID(), '_ounce', true ) ),
                        );
                        $regular_price = $flower_names;
                        $pack_price    = '';
                        $pack_units    = '';
                    } elseif ( 'concentrates' === get_post_type() ) {
                        /**
                         * @todo make concentrate_names through the entire plugin filterable.
                         */
                        $concentrate_names = array(
                            '1/2 g'  => esc_html( get_post_meta( get_the_ID(), '_halfgram', true ) ),
                            '1 g'    => esc_html( get_post_meta( get_the_ID(), '_gram', true ) ),
                            '2 g'    => esc_html( get_post_meta( get_the_ID(), '_twograms', true ) ),
                        );
                        $regular_price = $concentrate_names;
                        $pack_price    = '';
                        $pack_units    = '';
                    } else {
                        $regular_price = '';
                        $pack_price    = '';
                        $pack_units    = '';
                    }

                    $sale_price = esc_html( get_post_meta( get_the_ID(), 'product_sale_price', true ) );

                    ?>

                    <p class='wpd-ecommerce price'><?php wpd_all_prices_simple( NULL, FALSE ); ?></p>

                    <?php if ( ! empty( $regular_price ) ) { ?>

                        <?php if ( 'flowers' === get_post_type() ) { ?>
                            <?php

                            // Select a weight.
                            printf( '<select name="wpd_ecommerce_flowers_prices" id="wpd_ecommerce_flowers_prices" class="widefat">' );
                            printf( '<option value="" disabled selected>Choose a weight</option>' );
                            foreach ( $regular_price as $name => $price ) {
                                if ( '' != $price ) {
                                    printf( '<option value="'. esc_html( $price ) . '">' . CURRENCY . esc_html( $price ) . ' - ' . esc_html( $name ) . '</option>' );
                                }
                            }
                            print( '</select>' );

                            $weight_gram      = get_post_meta( get_the_ID(), '_gram', true );
                            $weight_twograms  = get_post_meta( get_the_ID(), '_twograms', true );
                            $weight_eighth    = get_post_meta( get_the_ID(), '_eighth', true );
                            $weight_fivegrams = get_post_meta( get_the_ID(), '_fivegrams', true );
                            $weight_quarter   = get_post_meta( get_the_ID(), '_quarter', true );
                            $weight_halfounce = get_post_meta( get_the_ID(), '_halfounce', true );
                            $weight_ounce     = get_post_meta( get_the_ID(), '_ounce', true );

                            if ( $weight_gram === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_gram';
                            } elseif ( $weight_twograms === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_twograms';
                            } elseif ( $weight_eighth === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_eighth';
                            } elseif ( $weight_fivegrams === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_fivegrams';
                            } elseif ( $weight_quarter === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_quarter';
                            } elseif ( $weight_halfounce === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_halfounce';
                            } elseif ( $weight_ounce === $_POST['wpd_ecommerce_flowers_prices'] ) {
                                $wpd_flower_meta_key = '_ounce';
                            } else {
                                $wpd_flower_meta_key = '';
                            }
                            ?>
                        <?php } elseif ( 'concentrates' === get_post_type() ) { ?>
                            <?php

                            // Price each (not a weight based price).
                            $price_each = get_post_meta( get_the_ID(), '_priceeach', true );

                            // If price_each is empty.
                            if ( '' === $price_each ) {
                                // Select a weight.
                                printf( '<select name="wpd_ecommerce_concentrates_prices" id="wpd_ecommerce_concentrates_prices" class="widefat">' );
                                printf( '<option value="" disabled selected>Choose a weight</option>' );
                                foreach ( $regular_price as $name => $price ) {
                                    if ( '' != $price ) {
                                        printf( '<option value="'. esc_html( $price ) . '">' . CURRENCY . esc_html( $price ) . ' - ' . esc_html( $name ) . '</option>' );
                                    }
                                }
                                print( '</select>' );

                                $weight_halfgram  = get_post_meta( get_the_ID(), '_halfgram', true );
                                $weight_gram      = get_post_meta( get_the_ID(), '_gram', true );
                                $weight_twograms  = get_post_meta( get_the_ID(), '_twograms', true );

                                if ( $weight_halfgram === $_POST['wpd_ecommerce_concentrates_prices'] ) {
                                    $wpd_concentrate_meta_key = '_halfgram';
                                } elseif ( $weight_gram === $_POST['wpd_ecommerce_concentrates_prices'] ) {
                                    $wpd_concentrate_meta_key = '_gram';
                                } elseif ( $weight_twograms === $_POST['wpd_ecommerce_concentrates_prices'] ) {
                                    $wpd_concentrate_meta_key = '_twograms';
                                } else {
                                    $wpd_concentrate_meta_key = '';
                                }
                            } else {
                                // Do nothing.
                            }
                            ?>
                        <?php } else { ?>
                            <?php
                            if ( ! empty( $pack_price ) ) { ?>
                                <?php

                                // Select a quantity.
                                print( '<select name="wpd_ecommerce_product_prices" id="wpd_ecommerce_product_prices" class="widefat">' );
                                printf( '<option value="" disabled selected>Choose a quantity</option>' );
                                printf( '<option value="'. esc_html( $regular_price ) . '">' . CURRENCY . esc_html( $regular_price ) . ' - each</option>' );
                                printf( '<option value="'. esc_html( $pack_price ) . '">' . CURRENCY . esc_html( $pack_price ) . ' - ' . esc_html( $pack_units ) . ' pack</option>' );
                                print( '</select>' );

                                $price_each     = get_post_meta( get_the_ID(), '_priceeach', true );
                                $price_topical  = get_post_meta( get_the_ID(), '_pricetopical', true );
                                $price_per_pack = get_post_meta( get_the_ID(), '_priceperpack', true );

                                if ( $price_each === $_POST['wpd_ecommerce_product_prices'] ) {
                                    $wpd_product_meta_key = '_priceeach';
                                } elseif ( $price_topical === $_POST['wpd_ecommerce_product_prices'] ) {
                                    $wpd_product_meta_key = '_pricetopical';
                                } elseif ( $price_per_pack === $_POST['wpd_ecommerce_product_prices'] ) {
                                    $wpd_product_meta_key = '_priceperpack';
                                } else {
                                    $wpd_product_meta_key = '_priceeach';
                                }

                                ?>
                            <?php } else { ?>
                            <?php } ?>
                        <?php } ?>

                    <?php } ?>

                    <?php
                    /**
                     * Add Items to Cart
                     */
                    if ( is_singular( 'flowers' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && NULL !== $_POST['wpd_ecommerce_flowers_prices'] ) {
                        $qtty = $_POST['qtty'];

                        /**
                         * ID's
                         */
                        $old_id = $post->ID;
                        $new_id = $post->ID . $wpd_flower_meta_key;

                        /**
                         * Prices
                         */
                        $new_price = $_POST['wpd_ecommerce_flowers_prices'];
                        $old_price = get_post_meta( $old_id, $new_price, true );

                        /**
                         * Add items to cart
                         */
                        wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

                    } elseif ( is_singular( 'concentrates' ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && NULL !== $_POST['wpd_ecommerce_concentrates_prices'] ) {
                        $qtty = $_POST['qtty'];

                        /**
                         * ID's
                         */
                        $old_id = $post->ID;
                        $new_id = $post->ID . $wpd_concentrate_meta_key;

                        /**
                         * Prices
                         */
                        $new_price = $_POST['wpd_ecommerce_concentrates_prices'];
                        $old_price = get_post_meta( $old_id, $new_price, true );

                        /**
                         * Add items to cart
                         */
                        wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

                    } elseif ( is_singular( array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) && isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) && NULL !== $_POST['wpd_ecommerce_product_prices'] ) {
                        $qtty = $_POST['qtty'];

                        /**
                         * ID's
                         */
                        $old_id = $post->ID;
                        $new_id = $post->ID . $wpd_product_meta_key;

                        /**
                         * Prices
                         */
                        $new_price = $_POST['wpd_ecommerce_product_prices'];
                        $old_price = get_post_meta( $old_id, $wpd_product_meta_key, true );

                        wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );

                    } elseif ( isset( $_POST['qtty'] ) && ! empty( $_POST['qtty'] ) && isset( $_POST['add_me'] ) ) {
                        $qtty = $_POST['qtty'];

                        // ID.
                        $old_id = $post->ID;

                        // Setup ID if SOMETHING is not done.
                        // This is where the check for adding to cart should come into play.
                        if ( empty( $new_id ) ) {
                            if ( 'topicals' === get_post_type() ) {
                                $new_id = $post->ID . '_pricetopical';
                            } else {
                                $new_id = $post->ID . '_priceeach';
                            }
                        } else {
                            $new_id = $post->ID . $wpd_product_meta_key;
                        }

                        // Pricing.
                        $new_price           = $_POST['wpd_ecommerce_product_prices'];
                        $concentrates_prices = $_POST['wpd_ecommerce_concentrates_prices'];

                        if ( empty( $new_price ) ) {
                            if ( 'topicals' === get_post_type() ) {

                                $old_price    = get_post_meta( $old_id, '_pricetopical', true );
                                $single_price = get_post_meta( $old_id, '_pricetopical', true );
                                $pack_price   = get_post_meta( $old_id, '_priceperpack', true );

                                /*
                                var_dump( $new_price );
                                echo "...<br />...";
                                print_r( $new_price );
                                */

                                if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price ) {
                                    wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
                                }

                            } elseif ( is_singular( array( 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {

                                $single_price = get_post_meta( $old_id, '_priceeach', true );
                                $pack_price   = get_post_meta( $old_id, '_priceperpack', true );

                                /*
                                var_dump( $new_price );
                                echo "...<br />...";
                                print_r( $new_price );
                                */

                                if ( '' !== $single_price && NULL == $pack_price && NULL == $new_price && NULL == $concentrates_prices ) {
                                    wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
                                }

                            }
                        } else {
                            $old_price = get_post_meta( $old_id, $wpd_product_meta_key, true );
                            // wpd_ecommerce_add_items_to_cart( $new_id, $qtty, $old_id, $new_price, $old_price );
                        }

                    } else {
                        $qtty = 1;
                    }
                    ?>
                        <input type="number" name="qtty" id="qtty" value="1" class="item_Quantity" />
                        <input type="submit" class="item_add" id="add_item_btn" value="<?php echo __( 'Add to cart', 'wpd-ecommerce' ); ?>" name="add_me" />
                    </form>

                    <?php
                    do_action( 'wpd_ecommerce_item_types_before' );

                    // Display Item types.
                    echo "<div class='wpd-ecommerce item-types'>";

                    do_action( 'wpd_ecommerce_item_types_inside_before' );

                    // Display Strain Type
                    echo "<span class='wpd-ecommerce strain-type'>" . get_the_term_list( get_the_ID(), 'strain_type', '', ', ' ) . "</span>";
                    // Display Shelf Type
                    echo "<span class='wpd-ecommerce shelf-type'>" . get_the_term_list( get_the_ID(), 'shelf_type', '', ', ' ) . "</span>";
                    // Display Edibles Category
                    echo "<span class='wpd-ecommerce category edibles'>" . get_the_term_list( get_the_ID(), 'edibles_category', '', ', ' ) . "</span>";
                    // Display Topicals Category
                    echo "<span class='wpd-ecommerce category topicals'>" . get_the_term_list( get_the_ID(), 'topicals_category', '', ', ' ) . "</span>";

                    do_action( 'wpd_ecommerce_item_types_inside_after' );

                    // End item-types div.
                    echo "</div>";

                    do_action( 'wpd_ecommerce_item_types_after' );

                    do_action( 'wpd_ecommerce_item_details_before' );

                    echo "<div class='wpd-ecommerce item-details'>";

                    do_action( 'wpd_ecommerce_item_details_inside_before' );

                    // Get compounds.
                    $compounds = wpd_compounds_simple( $type = '%', array() );

                    $showcompounds = $compounds;

                    do_action( 'wpd_ecommerce_item_details_inside_after' );

                    echo "</div>";

                    do_action( 'wpd_ecommerce_item_details_after' );

                    ?>

                </div><!-- / .product-details -->
            </div><!-- / .wpd-ecommerce-shelfItem -->
            <div class="entry-content wpd-ecommerce-shelfContent">
                <?php
                    do_action( 'wpd_ecommerce_single_item_content_before' );
                    the_content();
                    do_action( 'wpd_ecommerce_single_item_content_after' );
                ?>
            </div><!-- / wpd-ecommerce-shelfContent -->
        </div>
    <?php endwhile; ?>
<?php do_action( 'wpd_ecommerce_templates_single_items_wrap_after' ); ?>
<?php wp_reset_query(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
