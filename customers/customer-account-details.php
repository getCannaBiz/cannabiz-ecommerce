<?php
/**
 * WP Dispensary eCommerce customer account details
 *
 * @package    WPD_eCommerce
 * @subpackage WPD_eCommerce/customers
 * @author     WP Dispensary <contact@wpdispensary.com>
 * @license    GPL-2.0+ 
 * @link       https://www.wpdispensary.com
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add extra fields to Account page registration form
 * 
 * @return string
 */
function wpd_ecommerce_registration_form() { ?>
    <p class="register-first-name">
        <label for="first_name"><?php esc_html_e( 'First Name', 'wpd-ecommerce' ); ?></label>
        <input type="text" name="first_name" value="" id="first_name" class="input"/>
    </p>
<?php }
//add_action( 'wpd_ecommerce_customer_account_register_form_inside_top', 'wpd_ecommerce_registration_form' );

/**
 * User Registration - Update user data
 * 
 * @param int $user_id 
 * 
 * @return void
 */
function wpd_ecommerce_user_register( $user_id ) {
    // First name.
    if ( ! empty( filter_input( INPUT_POST, 'first_name' ) ) ) {
        update_user_meta( $user_id, 'first_name', sanitize_text_field( filter_input( INPUT_POST, 'first_name' ) ) );
    }
}
//add_action( 'user_register', 'wpd_ecommerce_user_register' );
//add_action( 'edit_user_created_user', 'wpd_ecommerce_user_register' );

/**
 * Back end registration
 * 
 * @param string $operation 
 * 
 * @return string
 */
function wpd_ecommerce_admin_registration_form( $operation ) {
    if ( 'add-new-user' !== $operation ) {
        // $operation may also be 'add-existing-user' (multisite, I believe!?)
        return;
    }
    ?>
    <h3><?php esc_html_e( 'Additional Details', 'wpd-ecommerce' ); ?></h3>

    <table class="form-table">
        <tr>
            <th>
                <label for="first_name"><?php esc_html_e( 'First Name', 'wpd-ecommerce' ); ?></label>
            </th>
            <td>
                <input type="text" id="first_name" name="first_name" value="" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
//add_action( 'user_new_form', 'wpd_ecommerce_admin_registration_form' );

/**
 * User Profile - Remove Website
 * 
 * @return string
 */
function wpd_ecommerce_remove_user_profile_field_css() {
    echo '<style>tr.user-url-wrap{ display: none; }</style>';
}
add_action( 'admin_head-user-edit.php', 'wpd_ecommerce_remove_user_profile_field_css' );
add_action( 'admin_head-profile.php',   'wpd_ecommerce_remove_user_profile_field_css' );

/**
 * Backend - Contact Info
 * 
 * @param array $fields 
 * 
 * @return string
 */
function wpd_ecommerce_show_contact_info_fields( $fields ) {

    // Remove website field.
    unset( $fields['url'] );

    // Add custom fields.
    $fields['phone_number']   = esc_attr__( 'Phone number', 'wpd-ecommerce' );
    $fields['address_line_1'] = esc_attr__( 'Address line 1', 'wpd-ecommerce' );
    $fields['address_line_2'] = esc_attr__( 'Address line 2', 'wpd-ecommerce' );
    $fields['city']           = esc_attr__( 'City', 'wpd-ecommerce' );
    $fields['state_county']   = esc_attr__( 'State / County', 'wpd-ecommerce' );
    $fields['postcode_zip']   = esc_attr__( 'Postcode / ZIP', 'wpd-ecommerce' );
    $fields['country']        = esc_attr__( 'Country', 'wpd-ecommerce' );

    // Return the amended contact fields.
    return $fields;
}
add_action( 'user_contactmethods', 'wpd_ecommerce_show_contact_info_fields' );

/**
 * User Profile - Update Fields
 * 
 * @param int $user_id 
 * 
 * @return void
 */
function wpd_ecommerce_update_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( ! empty( filter_input( INPUT_POST, 'year_of_birth' ) ) && intval( filter_input( INPUT_POST, 'year_of_birth' ) ) >= 1900 ) {
        update_user_meta( $user_id, 'year_of_birth', intval( filter_input( INPUT_POST, 'year_of_birth' ) ) );
    }
}
//add_action( 'personal_options_update', 'wpd_ecommerce_update_profile_fields' );
//add_action( 'edit_user_profile_update', 'wpd_ecommerce_update_profile_fields' );

/**
 * User Profile - Update Errors
 * 
 * @param [type] $errors 
 * @param [type] $update 
 * @param object $user 
 * 
 * @return void 
 */
function wpd_ecommerce_user_profile_update_errors( $errors, $update, $user ) {
    if ( empty( filter_input( INPUT_POST, 'year_of_birth' ) ) ) {
        $errors->add( 'year_of_birth_error', apply_filters( 'wpd_ecommerce_user_profile_update_year_of_birth_error', esc_attr__( '<strong>ERROR</strong>: Please enter your year of birth.', 'wpd-ecommerce' ) ) );
    }

    if ( ! empty( filter_input( INPUT_POST, 'year_of_birth' ) ) && intval( filter_input( INPUT_POST, 'year_of_birth' ) ) < 1900 ) {
        $errors->add( 'year_of_birth_error', esc_attr__( '<strong>ERROR</strong>: You must be born after 1900.', 'wpd-ecommerce' ) );
    }
}
//add_action( 'user_profile_update_errors', 'wpd_ecommerce_user_profile_update_errors', 10, 3 );
