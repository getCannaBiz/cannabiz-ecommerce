<?php
/**
 * WP Dispensary's Cart Widget
 */
class My_Cart_Widget extends WP_Widget {
	/**
	 * Constructor
	 *
	 * @access      public
	 * @since       1.0.0
	 * @return      void
	 */
	public function __construct() {

		parent::__construct(
			'wpd_cart_widget',
			__( 'Shopping Cart', 'wpd-ecommerce' ),
			array(
				'description' => __( 'Display your cart details', 'wpd-ecommerce' ),
				'classname'   => 'wp-dispensary-cart-widget',
			)
		);

	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Cart',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	    ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'wpd-ecommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function widget( $args, $instance ) {
		global $post;

		// Extract Args.
		extract( $args );

		// Content variable for has_shortcode check.
		$content = get_the_content();

		// Check content for wpd_cart shortcode.
		if ( ! has_shortcode( $content, 'wpd_cart' ) ) {

			// If cart isn't empty, display the widget.
			if ( ! empty( $_SESSION['wpd_ecommerce'] ) ) {
		
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
					$i    = new Item( $id, '', '', '' );
					$item_old_id = preg_replace( '/[^0-9.]+/', '', $id );
					$weight_option2 = preg_replace( '/[0-9]+/', '', $id );
					if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {
                        $regular_price  = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
						$weightname = '';
                    } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {
                        $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
						$weightname = '';
                    } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
                        $regular_price = esc_html( get_post_meta( $item_old_id, $weight_option2, true ) );

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
	
						$item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
						$flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );
	
						foreach ( $flower_names as $value=>$key ) {
							if ( $key == $flower_weight_cart ) {
								$weightname = " - " . $value;
							}
						}
					} else {
						$regular_price = '';
						$weightname = '';
                    }

					// print_r( $i );

					$total_price = $amount * $regular_price;
	
					$str .=	"<tr><td><button id='wpd_ecommerce_remove_product' href='?remove=" . $i->id . "' class='wpd-ecommerce-widget remove'>x</button></td><td><a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> - " . $amount . " x <span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</span></td><td>" . $i->thumbnail . "</td></tr>";
				endforeach;
				$str .= "</tbody>";
				$str .= "</table>";
		
				$str .= "<p class='wpd-ecommerce-widget subtotal'><strong>Subtotal:</strong> " . CURRENCY . number_format( $_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . "</p>";

				/**
				 * @todo make the Cart/Checkout links work with Settings option, and/or default
				 */
				$str .= "<p class='wpd-ecommerce-widget buttons'><a href='" . get_bloginfo( 'url' ) . "/cart' class='button'>View cart</a> <a href='" . get_bloginfo( 'url' ) . "/checkout' class='button'>Checkout</a></p>";

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
 * @since       1.0.0
 * @return      void
 */
function wpd_cart_register_widget() {
	register_widget( 'My_Cart_Widget' );
}
add_action( 'widgets_init', 'wpd_cart_register_widget' );
