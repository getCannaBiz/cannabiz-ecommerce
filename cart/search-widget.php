<?php
/**
 * WP Dispensary's Search Widget
 * 
 * @todo fix this to work right make sure to include it in the main wpd-ecommerce.php file
 */
class WPD_Search_Widget extends WP_Widget {
	/**
	 * Constructor
	 *
	 * @access      public
	 * @since       1.0.0
	 * @return      void
	 */
	public function __construct() {

		parent::__construct(
			'wpd_search_widget',
			__( 'Dispensary Search', 'wpd-ecommerce' ),
			array(
				'description' => __( 'Display a WP Dispensary search box', 'wpd-ecommerce' ),
				'classname'   => 'wp-dispensary-search-widget',
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

		do_action( 'wpd_ecommerce_widget_before' );

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( $title ) {
			echo $before_title . $title . $after_title; 
		} else {
			$title = '';
		}

		$str . '<form role="search" method="get" class="search-form" action="' . get_bloginfo( 'home' ) . '">';

		// Search option #1 not working right now
		//echo '<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search Business or Service', 'wpd-ecommerce' ) . '" value="' . get_query_var( 'vendor' ) . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />';

		$location_args = array( 
			'taxonomy'          => 'vendor', 
			'value_field'       => 'slug', 
			'name'              => 'vendor', 
			'show_option_none'  => __( 'Select Vendor', 'wpd-ecommerce' ), 
			'option_none_value' => '0', 
			'order'             => 'ASC', 
			'hide_empty'        => 0
		);

		wp_dropdown_categories( $location_args );

		$str .= '<input type="submit" class="button" style="width: 100%;" value="' . __( 'Search', 'wpd-ecommerce' ) . '" />';
		$str .= '</form>';

		echo $str;

		echo $after_widget;

		do_action( 'wpd_ecommerce_widget_after' );

	}

}

/**
 * Register the new widget
 *
 * @since       1.0.0
 * @return      void
 */
function wpd_search_register_widget() {
	register_widget( 'WPD_Search_Widget' );
}
add_action( 'widgets_init', 'wpd_search_register_widget' );
