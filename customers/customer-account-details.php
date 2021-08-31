<?php
/**
 * WP Dispensary eCommerce customer account details
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add extra fields to Account page registration form
 */
function wpd_ecommerce_registration_form() { ?>
	<p class="register-first-name">
		<label for="first_name"><?php _e( 'First Name', 'wpd-ecommerce' ); ?></label>
		<input type="text" name="first_name" value="" id="first_name" class="input" />
	</p>
<?php }
//add_action( 'wpd_ecommerce_customer_account_register_form_inside_top', 'wpd_ecommerce_registration_form' );

/**
 * User Registration - Update user data
 */
function wpd_ecommerce_user_register( $user_id ) {
	// First name.
	if ( ! empty( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
	}
}
//add_action( 'user_register', 'wpd_ecommerce_user_register' );
//add_action( 'edit_user_created_user', 'wpd_ecommerce_user_register' );

/**
 * Back end registration
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
			<th><label for="first_name"><?php esc_html_e( 'First Name', 'wpd-ecommerce' ); ?></label></th>
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
 */
function wpd_ecommerce_remove_user_profile_field_css() {
    echo '<style>tr.user-url-wrap{ display: none; }</style>';
}
add_action( 'admin_head-user-edit.php', 'wpd_ecommerce_remove_user_profile_field_css' );
add_action( 'admin_head-profile.php',   'wpd_ecommerce_remove_user_profile_field_css' );

/**
 * Backend - Contact Info
 */
function wpd_ecommerce_show_contact_info_fields( $fields ) {

	// Remove website field.
    unset( $fields['url'] );

	// Add custom fields.
	$fields['phone_number']   = __( 'Phone number', 'wpd-ecommerce' );
	$fields['address_line_1'] = __( 'Address line 1', 'wpd-ecommerce' );
	$fields['address_line_2'] = __( 'Address line 2', 'wpd-ecommerce' );
	$fields['city']           = __( 'City', 'wpd-ecommerce' );
	$fields['state_county']   = __( 'State / County', 'wpd-ecommerce' );
	$fields['postcode_zip']   = __( 'Postcode / ZIP', 'wpd-ecommerce' );
	$fields['country']        = __( 'Country', 'wpd-ecommerce' );

    // Return the amended contact fields.
    return $fields;
}
add_action( 'user_contactmethods', 'wpd_ecommerce_show_contact_info_fields' );

/**
 * User Profile - Update Fields
 */
function wpd_ecommerce_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) >= 1900 ) {
		update_user_meta( $user_id, 'year_of_birth', intval( $_POST['year_of_birth'] ) );
	}
}
//add_action( 'personal_options_update', 'wpd_ecommerce_update_profile_fields' );
//add_action( 'edit_user_profile_update', 'wpd_ecommerce_update_profile_fields' );

/**
 * User Profile - Update Errors
 */
function wpd_ecommerce_user_profile_update_errors( $errors, $update, $user ) {
	if ( empty( $_POST['year_of_birth'] ) ) {
		$errors->add( 'year_of_birth_error', apply_filters( 'wpd_ecommerce_user_profile_update_year_of_birth_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'wpd-ecommerce' ) ) );
	}

	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) < 1900 ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: You must be born after 1900.', 'wpd-ecommerce' ) );
	}
}
//add_action( 'user_profile_update_errors', 'wpd_ecommerce_user_profile_update_errors', 10, 3 );
