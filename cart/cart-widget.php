<?php
/**
 * WP Dispensary's Cart Widget
 */
class WPD_eCommerce_Widget extends WP_Widget {
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
		if ( ! has_shortcode( $content, 'wpd_cart' ) && ! has_shortcode( $content, 'wpd_checkout' ) ) {

			// If cart isn't empty, display the widget.
			if ( 0 != $_SESSION['wpd_ecommerce']->lines ) {
		
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

					if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {

						$units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

						$item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
						$item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

						if ( '_priceperpack' === $item_meta_key ) {
							$regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
						} else {
							$regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
						}

						if ( '_priceperpack' === $item_meta_key ) {
							$weightname = ' - ' . $units_per_pack . ' pack';
						} else {
							$weightname = '';
						}

                    } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {

						$units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

						$item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
						$item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

						if ( '_pricetopical' === $item_meta_key ) {
							$regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
						} elseif ( '_priceperpack' === $item_meta_key ) {
							$regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
						} elseif ( '_priceeach' === $item_meta_key ) {
							$regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
						}

						if ( '_priceperpack' === $item_meta_key ) {
							$weightname = ' - ' . $units_per_pack . ' pack';
						} else {
							$weightname = '';
						}

                    } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
						$item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
						$flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );
	
						foreach ( wpd_flowers_weights_array() as $key=>$value ) {
							if ( $value == $flower_weight_cart ) {
								$weightname    = " - " . $key;
								$regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
							}
						}
					} elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
						$item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
						$concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );
	
						foreach ( wpd_concentrates_weights_array() as $key=>$value ) {
							if ( $value == $concentrate_weight_cart ) {
								$weightname    = ' - ' . $key;
								$regular_price = esc_html( get_post_meta( $item_old_id, $value, true ) );
							}
						}
						if ( '_priceeach' === $concentrate_weight_cart ) {
							$weightname    = '';
							$regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
						}
					} else {
						// Do nothing.
                    }

					// print_r( $i );

					$total_price = $amount * $regular_price;

					$str .=	"<tr><td><a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> - " . $amount . " x <span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $regular_price, 2, '.', ',' ) . "</span></td><td>" . $i->thumbnail . "</td></tr>";
				endforeach;
				$str .= "</tbody>";
				$str .= "</table>";
		
				$str .= "<p class='wpd-ecommerce-widget subtotal'><strong>" . __( 'Subtotal', 'wpd-ecommerce' ) . ":</strong> " . CURRENCY . number_format( $_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . "</p>";

				$wpdas_pages   = get_option( 'wpdas_pages' );
				$checkout_page = $wpdas_pages['wpd_pages_setup_checkout_page'];

				$str .= "<p class='wpd-ecommerce-widget buttons'><a href='" . get_bloginfo( 'url' ) . "/" . $checkout_page . "' class='button'>" . __( 'Checkout', 'wpd-ecommerce' ) . "</a></p>";

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
	register_widget( 'WPD_eCommerce_Widget' );
}
add_action( 'widgets_init', 'wpd_cart_register_widget' );
