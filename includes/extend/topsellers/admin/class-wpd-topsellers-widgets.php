<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cannabizsoftware.com/
 * @since      1.0.0
 *
 * @package    WPD_TopSellers
 * @subpackage WPD_TopSellers/admin
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Create custom featured image size for the widget
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'wpd-topsellers-widget', 312, 156, true );
}

/**
 * Dispensary Top Sellers Widget
 *
 * @since       1.0.0
 */
class wpdtopsellers_widget extends WP_Widget {

    /**
     * Constructor
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function __construct() {

        parent::__construct(
            'wpdtopsellers_widget',
            __( 'Dispensary Top Sellers', 'cannabiz-menu' ),
            array(
                'description' => esc_attr__( 'Display your top selling items', 'cannabiz-menu' ),
                'classname'   => 'wpd-topsellers-widget',
            )
        );

    }

    /**
     * Widget definition
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::widget
     * @param       array $args Arguments to pass to the widget
     * @param       array $instance A given widget instance
     * @return      void
     */
    public function widget( $args, $instance ) {

        global $post;

        if( ! isset( $args['id'] ) ) {
            $args['id'] = 'wpdtopsellers_widget';
        }

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        do_action( 'wpdtopsellers_before_widget' );

            if ( 'on' !== $instance['featuredimage'] ) {
                echo '<ul class="wpd-topsellers-list">';
            }

            // Empty var.
            $randorder = '';

            // Set random order.
            if ( 'on' == $instance['order'] ) {
                $randorder = 'rand';
            }

            // Get the post type selected by user.
            $type = $instance['type'];

            // Set the post type selected by user.
            if ( 'all' == $type ) {
                $post_type = apply_filters( 'wpd_topsellers_widgets', array( 'flowers', 'concentrates', 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) );
            } else {
                $post_type = $type;
            }

            // @todo update the post_type and change the type selector from above to be added to the meta_query
            $wpdtopsellers_widget = new WP_Query(
                array(
                    'post_type'  => $post_type,
                    'showposts'  => $instance['limit'],
                    'orderby'    => $randorder,
                    'meta_query' => array(
                        array(
                            'key'   => 'product_featured',
                            'value' => 'product_featured'
                        ),
                    )
                )
            );

            while ( $wpdtopsellers_widget->have_posts() ) : $wpdtopsellers_widget->the_post();
            
            $do_not_duplicate = $post->ID;

            if ( 'on' == $instance['featuredimage'] ) {
                
                echo '<div class="wpd-topsellers-widget">';

                wpd_product_image( $post->ID, $instance['imagesize'] );

                if ( 'on' == $instance['itemname'] ) {
                    echo '<span class="wpd-topsellers-widget-title"><a href="' . get_permalink( $post->ID ) . '">' . get_the_title( $post->ID ) . '</a></span>';
                }
                if ( 'on' == $instance['itemcategory'] ) {
                    echo '<span class="wpd-topsellers-widget-categories">' . get_the_term_list( $post->ID, 'wpd_categories' ) . '</a></span>';
                }
                echo '</div>';
                
            } else {
                
                echo '<li>';
                if ( 'on' == $instance['itemname'] ) {
                    echo '<a href="' . get_permalink( $post->ID ) . '" class="wpd-topsellers-widget-link">' . get_the_title( $post->ID ) . '</a>';
                }
                echo '</li>';
                
            }

            endwhile; // end loop
            
            if ( 'on' !== $instance['featuredimage'] ) {
                echo '</ul>';
            }

        do_action( 'wpdtopsellers_after_widget' );
        
        echo $args['after_widget'];
    }


    /**
     * Update widget options
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::update
     * @param       array $new_instance The updated options
     * @param       array $old_instance The old options
     * @return      array $instance The updated instance options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['type']          = strip_tags( $new_instance['type'] );
        $instance['title']         = strip_tags( $new_instance['title'] );
        $instance['limit']         = strip_tags( $new_instance['limit'] );
        $instance['order']         = $new_instance['order'];
        $instance['featuredimage'] = $new_instance['featuredimage'];
        $instance['imagesize']     = $new_instance['imagesize'];
        $instance['itemname']      = $new_instance['itemname'];
        $instance['itemcategory']  = $new_instance['itemcategory'];

        return $instance;
    }


    /**
     * Display widget form on dashboard
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::form
     * @param       array $instance A given widget instance
     * @return      void
     */
    public function form( $instance ) {
        $defaults = array(
            'title'         => 'Top Sellers',
            'limit'         => '5',
            'type'          => '',
            'order'         => '',
            'featuredimage' => '',
            'imagesize'     => 'wpd-small',
            'itemname'      => '',
            'itemcategory'  => ''
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'cannabiz-menu' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Menu item type:', 'cannabiz-menu' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat" style="width:100%;">
                <option <?php if ( 'all' == $instance['type'] ) echo 'selected="selected"'; ?> value="all"><?php esc_html_e( 'All types', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'flowers' == $instance['type'] ) echo 'selected="selected"'; ?> value="flowers"><?php esc_html_e( 'Flowers', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'concentrates' == $instance['type'] ) echo 'selected="selected"'; ?> value="concentrates"><?php esc_html_e( 'Concentrates', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'edibles' == $instance['type'] ) echo 'selected="selected"'; ?> value="edibles"><?php esc_html_e( 'Edibles', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'prerolls' == $instance['type'] ) echo 'selected="selected"'; ?> value="prerolls"><?php esc_html_e( 'Pre-rolls', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'topicals' == $instance['type'] ) echo 'selected="selected"'; ?> value="topicals"><?php esc_html_e( 'Topicals', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'growers' == $instance['type'] ) echo 'selected="selected"'; ?> value="growers"><?php esc_html_e( 'Growers', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'gear' == $instance['type'] ) echo 'selected="selected"'; ?> value="gear"><?php esc_html_e( 'Gear', 'cannabiz-menu' ); ?></option>
                <option <?php if ( 'tinctures' == $instance['type'] ) echo 'selected="selected"'; ?> value="tinctures"><?php esc_html_e( 'Tinctures', 'cannabiz-menu' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Amount of items to show:', 'cannabiz-menu' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" min="1" max="999" value="<?php echo $instance['limit']; ?>" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['order'], 'on' ); ?> id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" /> 
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Randomize output?', 'cannabiz-menu' ); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['featuredimage'], 'on' ); ?> id="<?php echo $this->get_field_id( 'featuredimage' ); ?>" name="<?php echo $this->get_field_name( 'featuredimage' ); ?>" /> 
            <label for="<?php echo esc_attr( $this->get_field_id( 'featuredimage' ) ); ?>"><?php esc_html_e( 'Display featured image?', 'cannabiz-menu' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['itemname'], 'on' ); ?> id="<?php echo $this->get_field_id( 'itemname' ); ?>" name="<?php echo $this->get_field_name( 'itemname' ); ?>" /> 
            <label for="<?php echo esc_attr( $this->get_field_id( 'itemname' ) ); ?>"><?php esc_html_e( 'Display item name?', 'cannabiz-menu' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['itemcategory'], 'on' ); ?> id="<?php echo $this->get_field_id( 'itemcategory' ); ?>" name="<?php echo $this->get_field_name( 'itemcategory' ); ?>" /> 
            <label for="<?php echo esc_attr( $this->get_field_id( 'itemcategory' ) ); ?>"><?php esc_html_e( 'Display item category?', 'cannabiz-menu' ); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'imagesize' ) ); ?>"><?php esc_html_e( 'Image size:', 'cannabiz-menu' ); ?></label>
            <?php
                $terms = apply_filters( 'wpd_widgets_featured_image_sizes', wpd_featured_image_sizes() );
                if ( $terms ) {
                    printf( '<select name="%s" id="' . esc_html( $this->get_field_id( 'imagesize' ) ) . '" name="' . esc_html( $this->get_field_name( 'imagesize' ) ) . '" class="widefat">', esc_attr( $this->get_field_name( 'imagesize' ) ) );
                    foreach ( $terms as $term ) {
                        if ( esc_html( $term ) != $instance['imagesize'] ) {
                            $imagesizeinfo = '';
                        } else {
                            $imagesizeinfo = 'selected="selected"';
                        }
                        printf( '<option value="%s" ' . esc_html( $imagesizeinfo ) . '>%s</option>', esc_html( $term ), esc_html( $term ) );
                    }
                    print( '</select>' );
                }
            ?>
        </p>
        <?php
    }
}

/**
 * Register the new widget
 *
 * @since       1.0.0
 * @return      void
 */
function wpd_topsellers_register_widget() {
    register_widget( 'wpdtopsellers_widget' );
}
add_action( 'widgets_init', 'wpd_topsellers_register_widget' );
