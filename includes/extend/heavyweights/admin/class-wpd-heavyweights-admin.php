<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 * @link       https://www.wpdispensary.com
 * @since      1.0.0
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
     * @since  1.0.0
     * @access private
     * @var    string    $_plugin_name    The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_version    The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $_plugin_name - The name of this plugin.
     * @param string $_version     - The version of this plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $_plugin_name, $_version ) {

        $this->plugin_name = $_plugin_name;
        $this->version     = $_version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles() {

        // Admin CSS.
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpd-heavyweights-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts() {

        // Admin JS.
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpd-heavyweights-admin.js', array( 'jquery' ), $this->version, false );

    }

}

/**
 * Heavyweight Prices metabox
 *
 * Adds the Heavyweight Prices metabox to specific custom post types
 *
 * @since  1.0.0
 * @return void
 */
function add_heavyweight_prices_metaboxes() {

    $screens = array( 'products' );

    foreach ( $screens as $screen ) {
        add_meta_box(
            'wpdispensary_heavyweight_prices',
            esc_attr__( 'Heavyweight Prices', 'wpd-ecommerce' ),
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
 * 
 * @since  1.0.0
 * @return void
 */
function wpdispensary_heavyweight_prices() {
    global $post;

    // Noncename needed to verify where the data originated.
    echo '<input type="hidden" name="heavyweightpricesmeta_noncename" id="heavyweightpricesmeta_noncename" value="' .
    wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

    if ( in_array( get_post_type(), array( 'products' ) ) ) {
        // Get the prices data if its already been entered.
        $twoounces        = get_post_meta( $post->ID, 'price_two_ounces', true );
        $quarterpound     = get_post_meta( $post->ID, 'price_quarter_pound', true );
        $halfpound        = get_post_meta( $post->ID, 'price_half_pound', true );
        $onepound         = get_post_meta( $post->ID, 'price_one_pound', true );
        $twopounds        = get_post_meta( $post->ID, 'price_two_pounds', true );
        $threepounds      = get_post_meta( $post->ID, 'price_three_pounds', true );
        $fourpounds       = get_post_meta( $post->ID, 'price_four_pounds', true );
        $fivepounds       = get_post_meta( $post->ID, 'price_five_pounds', true );
        $sixpounds        = get_post_meta( $post->ID, 'price_six_pounds', true );
        $sevenpounds      = get_post_meta( $post->ID, 'price_seven_pounds', true );
        $eightpounds      = get_post_meta( $post->ID, 'price_eight_pounds', true );
        $ninepounds       = get_post_meta( $post->ID, 'price_nine_pounds', true );
        $tenpounds        = get_post_meta( $post->ID, 'price_ten_pounds', true );
        $elevenpounds     = get_post_meta( $post->ID, 'price_eleven_pounds', true );
        $twelvepounds     = get_post_meta( $post->ID, 'price_twelve_pounds', true );
        $thirteenpounds   = get_post_meta( $post->ID, 'price_thirteen_pounds', true );
        $fourteenpounds   = get_post_meta( $post->ID, 'price_fourteen_pounds', true );
        $fifteenpounds    = get_post_meta( $post->ID, 'price_fifteen_pounds', true );
        $twentypounds     = get_post_meta( $post->ID, 'price_twenty_pounds', true );
        $twentyfivepounds = get_post_meta( $post->ID, 'price_twenty_five_pounds', true );
        $fiftypounds      = get_post_meta( $post->ID, 'price_fifty_pounds', true );

        // Echo out the fields.
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '2 oz', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_two_ounces" value="' . $twoounces  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '1/4 lb', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_quarter_pound" value="' . $quarterpound  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '1/2 lb', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_half_pound" value="' . $halfpound  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '1 lb', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_one_pound" value="' . $onepound  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '2 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_two_pounds" value="' . $twopounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '3 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_three_pounds" value="' . $threepounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '4 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_four_pounds" value="' . $fourpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '5 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_five_pounds" value="' . $fivepounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '6 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_six_pounds" value="' . $sixpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '7 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_seven_pounds" value="' . $sevenpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '8 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_eight_pounds" value="' . $eightpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '9 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_nine_pounds" value="' . $ninepounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '10 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_ten_pounds" value="' . $tenpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '11 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_eleven_pounds" value="' . $elevenpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '12 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_twelve_pounds" value="' . $twelvepounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '13 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_thirteen_pounds" value="' . $thirteenpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '14 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_fourteen_pounds" value="' . $fourteenpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '15 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_fifteen_pounds" value="' . $fifteenpounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '20 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_twenty_pounds" value="' . $twentypounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '25 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_twenty_five_pounds" value="' . $twentyfivepounds  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '50 lbs', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_fifty_pounds" value="' . $fiftypounds  . '" class="widefat" />';
        echo '</div>';
    } // if is ( 'flowers' )

    if ( in_array( get_post_type(), array( 'products' ) ) ) {
        // Get the prices data if its already been entered.
        $threegrams = get_post_meta( $post->ID, 'price_three_grams', true );
        $fourgrams  = get_post_meta( $post->ID, 'price_four_grams', true );
        $fivegrams  = get_post_meta( $post->ID, 'price_five_grams', true );
        $sixgrams   = get_post_meta( $post->ID, 'price_six_grams', true );
        $sevengrams = get_post_meta( $post->ID, 'price_seven_grams', true );
        $eightgrams = get_post_meta( $post->ID, 'price_eight_grams', true );
        $ninegrams  = get_post_meta( $post->ID, 'price_nine_grams', true );
        $tengrams   = get_post_meta( $post->ID, 'price_ten_grams', true );

        // Echo out the fields.
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '3 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_three_grams" value="' . $threegrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '4 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_four_grams" value="' . $fourgrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '5 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_five_grams" value="' . $fivegrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '6 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_six_grams" value="' . $sixgrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '7 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_seven_grams" value="' . $sevengrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '8 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_eight_grams" value="' . $eightgrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '9 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_nine_grams" value="' . $ninegrams  . '" class="widefat" />';
        echo '</div>';
        echo '<div class="pricebox">';
        echo '<p>' . esc_attr__( '10 g', 'wpd-ecommerce' ) . '</p>';
        echo '<input type="text" name="price_ten_grams" value="' . $tengrams  . '" class="widefat" />';
        echo '</div>';
    } // if is ( 'concentrates' )

}

/**
 * Save the Metabox Data
 * 
 * @param int    $post_id - 
 * @param object $post    - 
 * 
 * @return void
 */
function wpdispensary_save_heavyweight_prices_meta( $post_id, $post ) {

    /**
     * Verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times
     */
    if ( null == filter_input( INPUT_POST, 'heavyweightpricesmeta_noncename' ) || ! wp_verify_nonce( filter_input( INPUT_POST, 'heavyweightpricesmeta_noncename' ), plugin_basename( __FILE__ ) ) ) {
        return $post->ID;
    }

    // Is the user allowed to edit the post or page?.
    if ( ! current_user_can( 'edit_post', $post->ID ) ) {
        return $post->ID;
    }

    /**
     * OK, we're authenticated: we need to find and save the data
     * We'll put it into an array to make it easier to loop though.
     */

    $price_keys = array(
        'price_three_grams',
        'price_four_grams',
        'price_five_grams',
        'price_six_grams',
        'price_seven_grams',
        'price_eight_grams',
        'price_nine_grams',
        'price_ten_grams',
        'price_two_ounces',
        'price_quarter_pound',
        'price_half_pound',
        'price_one_pound',
        'price_one_pounds',
        'price_two_pounds',
        'price_three_pounds',
        'price_four_pounds',
        'price_five_pounds',
        'price_six_pounds',
        'price_seven_pounds',
        'price_eight_pounds',
        'price_nine_pounds',
        'price_ten_pounds',
        'price_eleven_pounds',
        'price_twelve_pounds',
        'price_thirteen_pounds',
        'price_fourteen_pounds',
        'price_fifteen_pounds',
        'price_twenty_pounds',
        'price_twenty_five_pounds',
        'price_fifty_pounds',
    );

    foreach ( $price_keys as $key ) {
        $prices_meta[$key] = filter_input( INPUT_POST, $key );
    }

    // Add values of $prices_meta as custom fields.
    foreach ( $prices_meta as $key => $value ) { /** Cycle through the $prices_meta array! */
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
add_action( 'save_post', 'wpdispensary_save_heavyweight_prices_meta', 1, 2 );

/**
 * Action Hook
 *
 * This is the file responsible for adding the heavyweight prices
 * to the menu item pricing table.
 *
 * @since  1.0.0
 * @return string
 */
function add_wpd_heavyweight_prices() {
    
    if ( in_array( get_post_type(), array( 'products' ) ) ) {
        if (
            ! get_post_meta( get_the_ID(), 'price_two_ounces', true ) &&
            ! get_post_meta( get_the_ID(), 'price_quarter_pound', true ) &&
            ! get_post_meta( get_the_ID(), 'price_half_pound', true ) &&
            ! get_post_meta( get_the_ID(), 'price_one_pound', true ) &&
            ! get_post_meta( get_the_ID(), 'price_two_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_three_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_four_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_five_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_six_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_seven_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_eight_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_nine_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_ten_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_eleven_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_twelve_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_twenty_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ) &&
            ! get_post_meta( get_the_ID(), 'price_fifty_pounds', true ) 
        ) { } else {
            ?>
        </tr>
        <tr><td class="wpdispensary-title" colspan="6"><?php esc_attr__( 'Heavyweight Pricing', 'wpd-ecommerce' ); ?></td></tr>
    </table>
    <table class="wpdispensary-table single pricing wpd-heavyweights">
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_two_ounces', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '2 oz', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_two_ounces', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_quarter_pound', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '1/4 lb', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_quarter_pound', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_half_pound', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '1/2 lb', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_half_pound', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_one_pound', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '1 lb', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_one_pound', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_two_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '2 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_two_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_three_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '3 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_three_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_four_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '4 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_four_pounds', true ); ?></td>
            <?php } ?>
        </tr>
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_five_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '5 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_five_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_six_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '6 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_six_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_seven_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '7 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_seven_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_eight_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '8 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_eight_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_nine_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '9 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_nine_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_ten_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '10 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_ten_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_eleven_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '11 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_eleven_pounds', true ); ?></td>
            <?php } ?>
        </tr>
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_twelve_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '12 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_twelve_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '13 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '14 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '15 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_twenty_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '20 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_twenty_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '25 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_fifty_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '50 lbs', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_fifty_pounds', true ); ?></td>
            <?php } ?>
        </tr>
        <?php } ?>

        <?php } // if ( 'flowers' )

        if ( in_array( get_post_type(), array( 'products', 'concentrates' ) ) ) {
            if (
                ! get_post_meta( get_the_ID(), 'price_four_grams', true ) &&
                ! get_post_meta( get_the_ID(), 'price_five_grams', true ) &&
                ! get_post_meta( get_the_ID(), 'price_six_grams', true ) &&
                ! get_post_meta( get_the_ID(), 'price_seven_grams', true ) &&
                ! get_post_meta( get_the_ID(), 'price_eight_grams', true ) &&
                ! get_post_meta( get_the_ID(), 'price_nine_grams', true ) &&
                ! get_post_meta( get_the_ID(), 'price_ten_grams', true )
            ) { } else {
        ?>
        </tr>
        <tr><td class="wpdispensary-title" colspan="6"><?php esc_attr__( 'Heavyweight Pricing', 'wpd-ecommerce' ); ?></td></tr>
    </table>

    <table class="wpdispensary-table single pricing wpd-heavyweights">
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_four_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '4 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_four_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_five_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '5 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_five_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_six_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '6 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_six_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_seven_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '7 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_seven_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_eight_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '8 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_eight_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_nine_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '9 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_nine_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_ten_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '10 g', 'wpd-ecommerce' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_ten_grams', true ); ?></td>
            <?php } ?>
        </tr>
    <?php } ?>

    <?php } ?>
<?php }
add_action( 'wpd_pricingoutput_bottom', 'add_wpd_heavyweight_prices' );
