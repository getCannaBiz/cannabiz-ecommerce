<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WPD_Heavyweights
 * @subpackage WPD_Heavyweights/admin
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @link       https://cannabizsoftware.com
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
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
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
        <tr><td class="wpdispensary-title" colspan="6"><?php esc_attr__( 'Heavyweight Pricing', 'cannabiz-menu' ); ?></td></tr>
    </table>
    <table class="wpdispensary-table single pricing wpd-heavyweights">
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_two_ounces', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '2 oz', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_two_ounces', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_quarter_pound', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '1/4 lb', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_quarter_pound', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_half_pound', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '1/2 lb', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_half_pound', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_one_pound', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '1 lb', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_one_pound', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_two_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '2 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_two_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_three_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '3 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_three_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_four_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '4 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_four_pounds', true ); ?></td>
            <?php } ?>
        </tr>
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_five_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '5 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_five_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_six_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '6 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_six_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_seven_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '7 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_seven_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_eight_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '8 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_eight_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_nine_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '9 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_nine_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_ten_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '10 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_ten_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_eleven_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '11 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_eleven_pounds', true ); ?></td>
            <?php } ?>
        </tr>
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_twelve_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '12 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_twelve_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '13 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_thirteen_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '14 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_fourteen_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '15 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_fifteen_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_twenty_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '20 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_twenty_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '25 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_twenty_five_pounds', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_fifty_pounds', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '50 lbs', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_fifty_pounds', true ); ?></td>
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
        <tr><td class="wpdispensary-title" colspan="6"><?php esc_attr__( 'Heavyweight Pricing', 'cannabiz-menu' ); ?></td></tr>
    </table>

    <table class="wpdispensary-table single pricing wpd-heavyweights">
        <tr>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_four_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '4 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_four_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_five_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '5 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_five_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_six_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '6 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_six_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_seven_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '7 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_seven_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_eight_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '8 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_eight_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_nine_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '9 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_nine_grams', true ); ?></td>
            <?php } ?>
            <?php if ( ! get_post_meta( get_the_ID(), 'price_ten_grams', true ) ) { } else { ?>
                <td><span><?php esc_attr__( '10 g', 'cannabiz-menu' ); ?>:</span> <?php echo wpd_currency_code(); ?><?php echo get_post_meta( get_the_ID(), 'price_ten_grams', true ); ?></td>
            <?php } ?>
        </tr>
    <?php } ?>

    <?php } ?>
<?php }
add_action( 'wpd_pricingoutput_bottom', 'add_wpd_heavyweight_prices' );
