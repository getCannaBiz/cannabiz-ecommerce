<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com
 * @since      1.0.0
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 */
class WPD_Heavyweights_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPD_Heavyweights_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPD_Heavyweights_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpd-heavyweights-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPD_Heavyweights_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPD_Heavyweights_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpd-heavyweights-admin.js', array( 'jquery' ), $this->version, false );

	}

}

/**
 * Heavyweight Prices metabox
 *
 * Adds the Heavyweight Prices metabox to specific custom post types
 *
 * @since    1.0.0
 */
function add_heavyweight_prices_metaboxes() {

	$screens = array( 'flowers', 'concentrates' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_heavyweight_prices',
			__( 'Heavyweight Prices', 'wpd-ecommerce' ),
			'wpdispensary_heavyweight_prices',
			$screen,
			'normal',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_heavyweight_prices_metaboxes' );

/**
 * WP Dispensary Heavyweight Prices
 */
function wpdispensary_heavyweight_prices() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="heavyweightpricesmeta_noncename" id="heavyweightpricesmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	if ( in_array( get_post_type(), array( 'flowers' ) ) ) {
		/** Get the prices data if its already been entered */
		$twoounces        = get_post_meta( $post->ID, '_twoounces', true );
		$quarterpound     = get_post_meta( $post->ID, '_quarterpound', true );
		$halfpound        = get_post_meta( $post->ID, '_halfpound', true );
		$onepound         = get_post_meta( $post->ID, '_onepound', true );
		$twopounds        = get_post_meta( $post->ID, '_twopounds', true );
		$threepounds      = get_post_meta( $post->ID, '_threepounds', true );
		$fourpounds       = get_post_meta( $post->ID, '_fourpounds', true );
		$fivepounds       = get_post_meta( $post->ID, '_fivepounds', true );
		$sixpounds        = get_post_meta( $post->ID, '_sixpounds', true );
		$sevenpounds      = get_post_meta( $post->ID, '_sevenpounds', true );
		$eightpounds      = get_post_meta( $post->ID, '_eightpounds', true );
		$ninepounds       = get_post_meta( $post->ID, '_ninepounds', true );
		$tenpounds        = get_post_meta( $post->ID, '_tenpounds', true );
		$elevenpounds     = get_post_meta( $post->ID, '_elevenpounds', true );
		$twelvepounds     = get_post_meta( $post->ID, '_twelvepounds', true );
		$thirteenpounds   = get_post_meta( $post->ID, '_thirteenpounds', true );
		$fourteenpounds   = get_post_meta( $post->ID, '_fourteenpounds', true );
		$fifteenpounds    = get_post_meta( $post->ID, '_fifteenpounds', true );
		$twentypounds     = get_post_meta( $post->ID, '_twentypounds', true );
		$twentyfivepounds = get_post_meta( $post->ID, '_twentyfivepounds', true );
		$fiftypounds      = get_post_meta( $post->ID, '_fiftypounds', true );

		/** Echo out the fields */
		echo '<div class="pricebox">';
		echo '<p>' . __( '2 oz', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_twoounces" value="' . $twoounces  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '1/4 lb', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_quarterpound" value="' . $quarterpound  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '1/2 lb', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_halfpound" value="' . $halfpound  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '1 lb', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_onepound" value="' . $onepound  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '2 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_twopounds" value="' . $twopounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '3 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_threepounds" value="' . $threepounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '4 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fourpounds" value="' . $fourpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '5 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fivepounds" value="' . $fivepounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '6 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_sixpounds" value="' . $sixpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '7 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_sevenpounds" value="' . $sevenpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '8 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_eightpounds" value="' . $eightpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '9 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_ninepounds" value="' . $ninepounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '10 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_tenpounds" value="' . $tenpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '11 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_elevenpounds" value="' . $elevenpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '12 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_twelvepounds" value="' . $twelvepounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '13 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_thirteenpounds" value="' . $thirteenpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '14 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fourteenpounds" value="' . $fourteenpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '15 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fifteenpounds" value="' . $fifteenpounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '20 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_twentypounds" value="' . $twentypounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '25 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_twentyfivepounds" value="' . $twentyfivepounds  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '50 lbs', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fiftypounds" value="' . $fiftypounds  . '" class="widefat" />';
		echo '</div>';
	} // if is ( 'flowers' )

	if ( in_array( get_post_type(), array( 'concentrates' ) ) ) {
		/** Get the prices data if its already been entered */
		$threegrams = get_post_meta( $post->ID, '_threegrams', true );
		$fourgrams  = get_post_meta( $post->ID, '_fourgrams', true );
		$fivegrams  = get_post_meta( $post->ID, '_fivegrams', true );
		$sixgrams   = get_post_meta( $post->ID, '_sixgrams', true );
		$sevengrams = get_post_meta( $post->ID, '_sevengrams', true );
		$eightgrams = get_post_meta( $post->ID, '_eightgrams', true );
		$ninegrams  = get_post_meta( $post->ID, '_ninegrams', true );
		$tengrams   = get_post_meta( $post->ID, '_tengrams', true );

		/** Echo out the fields */
		echo '<div class="pricebox">';
		echo '<p>' . __( '3 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_threegrams" value="' . $threegrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '4 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fourgrams" value="' . $fourgrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '5 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_fivegrams" value="' . $fivegrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '6 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_sixgrams" value="' . $sixgrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '7 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_sevengrams" value="' . $sevengrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '8 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_eightgrams" value="' . $eightgrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '9 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_ninegrams" value="' . $ninegrams  . '" class="widefat" />';
		echo '</div>';
		echo '<div class="pricebox">';
		echo '<p>' . __( '10 g', 'wpd-ecommerce' ) . '</p>';
		echo '<input type="text" name="_tengrams" value="' . $tengrams  . '" class="widefat" />';
		echo '</div>';
	} // if is ( 'concentrates' )

}

/**
 * Save the Metabox Data
 */
function wpdispensary_save_heavyweight_prices_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['heavyweightpricesmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['heavyweightpricesmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$heavyweightprices_meta['_threegrams']       = esc_html( $_POST['_threegrams'] );
	$heavyweightprices_meta['_fourgrams']        = esc_html( $_POST['_fourgrams'] );
	$heavyweightprices_meta['_fivegrams']        = esc_html( $_POST['_fivegrams'] );
	$heavyweightprices_meta['_sixgrams']         = esc_html( $_POST['_sixgrams'] );
	$heavyweightprices_meta['_sevengrams']       = esc_html( $_POST['_sevengrams'] );
	$heavyweightprices_meta['_eightgrams']       = esc_html( $_POST['_eightgrams'] );
	$heavyweightprices_meta['_ninegrams']        = esc_html( $_POST['_ninegrams'] );
	$heavyweightprices_meta['_tengrams']         = esc_html( $_POST['_tengrams'] );
	$heavyweightprices_meta['_twoounces']        = esc_html( $_POST['_twoounces'] );
	$heavyweightprices_meta['_quarterpound']     = esc_html( $_POST['_quarterpound'] );
	$heavyweightprices_meta['_halfpound']        = esc_html( $_POST['_halfpound'] );
	$heavyweightprices_meta['_onepound']         = esc_html( $_POST['_onepound'] );
	$heavyweightprices_meta['_twopounds']        = esc_html( $_POST['_twopounds'] );
	$heavyweightprices_meta['_threepounds']      = esc_html( $_POST['_threepounds'] );
	$heavyweightprices_meta['_fourpounds']       = esc_html( $_POST['_fourpounds'] );
	$heavyweightprices_meta['_fivepounds']       = esc_html( $_POST['_fivepounds'] );
	$heavyweightprices_meta['_sixpounds']        = esc_html( $_POST['_sixpounds'] );
	$heavyweightprices_meta['_sevenpounds']      = esc_html( $_POST['_sevenpounds'] );
	$heavyweightprices_meta['_eightpounds']      = esc_html( $_POST['_eightpounds'] );
	$heavyweightprices_meta['_ninepounds']       = esc_html( $_POST['_ninepounds'] );
	$heavyweightprices_meta['_tenpounds']        = esc_html( $_POST['_tenpounds'] );
	$heavyweightprices_meta['_elevenpounds']     = esc_html( $_POST['_elevenpounds'] );
	$heavyweightprices_meta['_twelvepounds']     = esc_html( $_POST['_twelvepounds'] );
	$heavyweightprices_meta['_thirteenpounds']   = esc_html( $_POST['_thirteenpounds'] );
	$heavyweightprices_meta['_fourteenpounds']   = esc_html( $_POST['_fourteenpounds'] );
	$heavyweightprices_meta['_fifteenpounds']    = esc_html( $_POST['_fifteenpounds'] );
	$heavyweightprices_meta['_twentypounds']     = esc_html( $_POST['_twentypounds'] );
	$heavyweightprices_meta['_twentyfivepounds'] = esc_html( $_POST['_twelntyfivepounds'] );
	$heavyweightprices_meta['_fiftypounds']      = esc_html( $_POST['_fiftypounds'] );

	/** Add values of $prices_meta as custom fields */

	foreach ( $heavyweightprices_meta as $key => $value ) { /** Cycle through the $prices_meta array! */
		if ( 'revision' === $post->post_type ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_heavyweight_prices_meta', 1, 2 ); /** Save the custom fields */

/**
 * Action Hook
 *
 * This is the file responsible for adding the heavyweight prices
 * to the menu item pricing table.
 *
 * @since    1.0.0
 */
function add_wpd_heavyweight_prices() {
	
	if ( in_array( get_post_type(), array( 'flowers' ) ) ) {
		if (
			! get_post_meta( get_the_ID(), '_twoounces', true ) &&
			! get_post_meta( get_the_ID(), '_quarterpound', true ) &&
			! get_post_meta( get_the_ID(), '_halfpound', true ) &&
			! get_post_meta( get_the_ID(), '_onepound', true ) &&
			! get_post_meta( get_the_ID(), '_twopounds', true ) &&
			! get_post_meta( get_the_ID(), '_threepounds', true ) &&
			! get_post_meta( get_the_ID(), '_fourpounds', true ) &&
			! get_post_meta( get_the_ID(), '_fivepounds', true ) &&
			! get_post_meta( get_the_ID(), '_sixpounds', true ) &&
			! get_post_meta( get_the_ID(), '_sevenpounds', true ) &&
			! get_post_meta( get_the_ID(), '_eightpounds', true ) &&
			! get_post_meta( get_the_ID(), '_ninepounds', true ) &&
			! get_post_meta( get_the_ID(), '_tenpounds', true ) &&
			! get_post_meta( get_the_ID(), '_elevenpounds', true ) &&
			! get_post_meta( get_the_ID(), '_twelvepounds', true ) &&
			! get_post_meta( get_the_ID(), '_thirteenpounds', true ) &&
			! get_post_meta( get_the_ID(), '_fourteenpounds', true ) &&
			! get_post_meta( get_the_ID(), '_fifteenpounds', true ) &&
			! get_post_meta( get_the_ID(), '_twentypounds', true ) &&
			! get_post_meta( get_the_ID(), '_twentyfivepounds', true ) &&
			! get_post_meta( get_the_ID(), '_fiftypounds', true )
		) { } else {
	?>
	</tr>
	<tr><td class="wpdispensary-title" colspan="6"><?php __( 'Heavyweight Pricing', 'wpd-ecommerce' ); ?></td></tr>
	</table>
	<table class="wpdispensary-table single pricing wpd-heavyweights">
	<tr>
		<?php if ( ! get_post_meta( get_the_ID(), '_twoounces', true ) ) { } else { ?>
			<td><span><?php __( '2 oz', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_twoounces', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_quarterpound', true ) ) { } else { ?>
			<td><span><?php __( '1/4 lb', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_quarterpound', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_halfpound', true ) ) { } else { ?>
			<td><span><?php __( '1/2 lb', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_halfpound', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_onepound', true ) ) { } else { ?>
			<td><span><?php __( '1 lb', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_onepound', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_twopounds', true ) ) { } else { ?>
			<td><span><?php __( '2 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_twopounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_threepounds', true ) ) { } else { ?>
			<td><span><?php __( '3 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_threepounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_fourpounds', true ) ) { } else { ?>
			<td><span><?php __( '4 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fourpounds', true ); ?></td>
		<?php } ?>
	</tr>
	<tr>
		<?php if ( ! get_post_meta( get_the_ID(), '_fivepounds', true ) ) { } else { ?>
			<td><span><?php __( '5 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fivepounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_sixpounds', true ) ) { } else { ?>
			<td><span><?php __( '6 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_sixpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_sevenpounds', true ) ) { } else { ?>
			<td><span><?php __( '7 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_sevenpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_eightpounds', true ) ) { } else { ?>
			<td><span><?php __( '8 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_eightpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_ninepounds', true ) ) { } else { ?>
			<td><span><?php __( '9 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_ninepounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_tenpounds', true ) ) { } else { ?>
			<td><span><?php __( '10 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_tenpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_elevenpounds', true ) ) { } else { ?>
			<td><span><?php __( '11 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_elevenpounds', true ); ?></td>
		<?php } ?>
	</tr>
	<tr>
		<?php if ( ! get_post_meta( get_the_ID(), '_twelvepounds', true ) ) { } else { ?>
			<td><span><?php __( '12 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_twelvepounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_thirteenpounds', true ) ) { } else { ?>
			<td><span><?php __( '13 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_thirteenpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_fourteenpounds', true ) ) { } else { ?>
			<td><span><?php __( '14 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fourteenpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_fifteenpounds', true ) ) { } else { ?>
			<td><span><?php __( '15 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fifteenpounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_twentypounds', true ) ) { } else { ?>
			<td><span><?php __( '20 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_twentypounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_twentyfivepounds', true ) ) { } else { ?>
			<td><span><?php __( '25 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_twentyfivepounds', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_fiftypounds', true ) ) { } else { ?>
			<td><span><?php __( '50 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fiftypounds', true ); ?></td>
		<?php } ?>
	</tr>
	<?php } ?>

	<?php } // if ( 'flowers' )

	if ( in_array( get_post_type(), array( 'concentrates' ) ) ) {
		if (
			! get_post_meta( get_the_ID(), '_fourgrams', true ) &&
			! get_post_meta( get_the_ID(), '_fivegrams', true ) &&
			! get_post_meta( get_the_ID(), '_sixgrams', true ) &&
			! get_post_meta( get_the_ID(), '_sevengrams', true ) &&
			! get_post_meta( get_the_ID(), '_eightgrams', true ) &&
			! get_post_meta( get_the_ID(), '_ninegrams', true ) &&
			! get_post_meta( get_the_ID(), '_tengrams', true )
		) { } else {
	?>
	</tr>
	<tr><td class="wpdispensary-title" colspan="6"><?php __( 'Heavyweight Pricing', 'wpd-ecommerce' ); ?></td></tr>
	</table>

	<table class="wpdispensary-table single pricing wpd-heavyweights">
	<tr>
		<?php if ( ! get_post_meta( get_the_ID(), '_fourgrams', true ) ) { } else { ?>
			<td><span><?php __( '4 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fourgrams', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_fivegrams', true ) ) { } else { ?>
			<td><span><?php __( '5 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_fivegrams', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_sixgrams', true ) ) { } else { ?>
			<td><span><?php __( '6 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_sixgrams', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_sevengrams', true ) ) { } else { ?>
			<td><span><?php __( '7 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_sevengrams', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_eightgrams', true ) ) { } else { ?>
			<td><span><?php __( '8 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_eightgrams', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_ninegrams', true ) ) { } else { ?>
			<td><span><?php __( '9 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_ninegrams', true ); ?></td>
		<?php } ?>
		<?php if ( ! get_post_meta( get_the_ID(), '_tengrams', true ) ) { } else { ?>
			<td><span><?php __( '10 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), '_tengrams', true ); ?></td>
		<?php } ?>
	</tr>
	<?php } ?>

	<?php } ?>
<?php }
add_action( 'wpd_pricingoutput_bottom', 'add_wpd_heavyweight_prices' );
