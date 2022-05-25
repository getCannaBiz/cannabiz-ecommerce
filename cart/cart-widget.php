<?php
/**
 * WP Dispensary eCommerce cart widget
 *
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Dispensary's Cart Widget
 * 
 * @package WPD_eCommerce
 * @author  WP Dispensary <contact@wpdispensary.com>
 * @license GPL-2.0+ 
 * @link    https://www.wpdispensary.com
 * @since   1.0.0
 */
class WPD_eCommerce_Widget extends WP_Widget {
    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function __construct() {

        parent::__construct(
            'wpd_cart_widget',
            esc_attr__( 'Shopping Cart', 'wpd-ecommerce' ),
            array(
                'description' => esc_attr__( 'Display your cart details', 'wpd-ecommerce' ),
                'classname'   => 'wp-dispensary-cart-widget',
            )
        );

    }

    /**
     * Form
     * 
     * @param object $instance 
     * 
     * @return string
     */
    function form( $instance ) {
        $defaults = array(
            'title' => esc_attr__( 'Cart', 'wpd-ecommerce' ),
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'wpd-ecommerce' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
        </p>
        <?php
    }

    /**
     * Update
     * 
     * @param [type] $new_instance 
     * @param [type] $old_instance 
     * 
     * @return [type]
     */
    function update( $new_instance, $old_instance ) {
        $instance          = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /**
     * Widget
     * 
     * @param array  $args 
     * @param [type] $instance 
     * 
     * @return string
     */
    function widget( $args, $instance ) {
        global $post;

        // Extract Args.
        extract( $args );

        // Content variable for has_shortcode check.
        $content = get_the_content();

        // Check content for wpd_cart shortcode.
        if ( ! has_shortcode( $content, 'wpd_cart' ) && ! has_shortcode( $content, 'wpd_checkout' ) ) {

            $line_check = '';

            if ( ! empty( $_SESSION['wpd_ecommerce'] ) ) {
                $line_check = $_SESSION['wpd_ecommerce']->lines;
            }

            // If cart isn't empty, display the widget.
            if ( 0 != $line_check ) {
        
                do_action( 'wpd_ecommerce_widget_before' );

                echo $before_widget;

                $title = apply_filters( 'widget_title', $instance['title'] );

                if ( $title ) {
                    echo $before_title . $title . $after_title; 
                } else {
                    $title = '';
                }

                $str  = '<table class="wpd-ecommerce widget">';
                $str .= '<tbody>';

                foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
                    $i             = new Item( $id, '', '', '' );
                    $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
                    $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

                    if ( in_array( get_post_meta( $item_old_id, 'product_type', true ), array( 'edibles', 'prerolls', 'topicals', 'growers', 'gear', 'tinctures' ) ) ) {

                        $units_per_pack = esc_html( get_post_meta( $item_old_id, 'units_per_pack', true ) );
                        $item_old_id    = preg_replace( '/[^0-9.]+/', '', $i->id );
                        $item_meta_key  = preg_replace( '/[0-9]+/', '', $i->id );

                        // Set the regular price.
                        if ( 'price_per_pack' === $item_meta_key ) {
                            $regular_price = esc_html( get_post_meta( $item_old_id, 'price_per_pack', true ) );
                        } else {
                            $regular_price = esc_html( get_post_meta( $item_old_id, 'price_each', true ) );
                        }

                        // Weight name.
                        $weight_name = '';

                        // Set the weight name.
                        if ( 'price_per_pack' === $item_meta_key ) {
                            $weight_name = ' - ' . $units_per_pack . ' pack';
                        }

                    } elseif ( 'flowers' === get_post_meta( $item_old_id, 'product_type', true ) ) {
                        $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
                        $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );
    
                        // Loop through all registered flower weights.
                        foreach ( wpd_flowers_weights_array() as $key=>$value ) {
                            if ( $value == $flower_weight_cart ) {
                                $weight_name   = ' - ' . $key;
                                $regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
                            }
                        }
                    } elseif ( 'concentrates' === get_post_meta( $item_old_id, 'product_type', true ) ) {
                        $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
                        $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                        // Loop through all registered concentrate weights.    
                        foreach ( wpd_concentrates_weights_array() as $key=>$value ) {
                            if ( $value == $concentrate_weight_cart ) {
                                $weight_name   = ' - ' . $key;
                                $regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
                            }
                        }

                        // Price each.
                        if ( 'price_each' === $concentrate_weight_cart ) {
                            $weight_name   = '';
                            $regular_price = esc_html( get_post_meta( $item_old_id, 'price_each', true ) );
                        }
                    } else {
                        // Do nothing.
                    }

                    // Get the total price.
                    $total_price = $amount * $regular_price;

                    $str .= '<tr><td><a href="' . $i->permalink . '" class="wpd-ecommerce-widget title">' . $i->title . $weight_name . '</a> - ' . $amount . ' x <span class="wpd-ecommerce-widget amount">' . CURRENCY . number_format( $regular_price, 2, '.', ',' ) . '</span></td><td>' . $i->thumbnail . '</td></tr>';
                endforeach;
                $str .= '</tbody>';
                $str .= '</table>';
        
                $str .= '<p class="wpd-ecommerce-widget subtotal"><strong>' . esc_attr__( 'Subtotal', 'wpd-ecommerce' ) . ':</strong> ' . wpd_ecommerce_cart_subtotal() . '</p>';

                $str .= '<p class="wpd-ecommerce-widget buttons"><a href="' . wpd_ecommerce_checkout_url() . '" class="button">' . esc_attr__( 'Checkout', 'wpd-ecommerce' ) . '</a></p>';

                echo $str;

                echo $after_widget;

                do_action( 'wpd_ecommerce_widget_after' );

            } else {
                // Do nothing.
            }

        } else {
            // Do nothing.
        }

    }

}

/**
 * Register the new widget
 *
 * @since  1.0.0
 * @return void
 */
function wpd_cart_register_widget() {
    register_widget( 'WPD_eCommerce_Widget' );
}
add_action( 'widgets_init', 'wpd_cart_register_widget' );
