<?php
/**
 * WP Dispensary eCommerce patient account details
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front end registration
 * @todo see if this is for the wp-register.php default
 */
function wpd_ecommerce_registration_form() {
	$birth_year = ! empty( $_POST['year_of_birth'] ) ? intval( $_POST['year_of_birth'] ) : '';
	?>
	<p>
		<label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'wpd-ecommerce' ) ?><br/>
			<input type="number"
			       min="1900"
			       max="2018"
			       step="1"
			       id="year_of_birth"
			       name="year_of_birth"
			       value="<?php echo esc_attr( $birth_year ); ?>"
			       class="input"
			/>
		</label>
	</p>
	<?php
}
//add_action( 'register_form', 'wpd_ecommerce_registration_form' );

/**
 * Registration Errors
 */
function wpd_ecommerce_registration_errors( $errors, $sanitized_user_login, $user_email ) {

	// Error - no birth date.
	if ( empty( $_POST['year_of_birth'] ) ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'wpd-ecommerce' ) );
	}

	// Error - age requirement.
	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) < 1900 ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: You must be born after 1900.', 'wpd-ecommerce' ) );
	}

	return $errors;
}
//add_filter( 'registration_errors', 'wpd_ecommerce_registration_errors', 10, 3 );

/**
 * User Registration - Update user data
 */
function wpd_ecommerce_user_register( $user_id ) {
	// Year of birth.
	if ( ! empty( $_POST['year_of_birth'] ) ) {
		update_user_meta( $user_id, 'year_of_birth', intval( $_POST['year_of_birth'] ) );
	}
	// Phone number.
	if ( ! empty( $_POST['phone_number'] ) ) {
		update_user_meta( $user_id, 'phone_number', $_POST['phone_number'] );
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
			<th><label for="phone_number"><?php esc_html_e( 'Phone number', 'wpd-ecommerce' ); ?></label></th>
			<td>
				<input type="tel"
			       id="phone_number"
			       name="phone_number"
			       value=""
			       class="regular-text"
				/>
			</td>
		</tr>
		<tr>
			<th><label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'wpd-ecommerce' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'wpd-ecommerce' ); ?></span></th>
			<td>
				<input type="number"
			       min="1900"
			       max="2018"
			       step="1"
			       id="year_of_birth"
			       name="year_of_birth"
			       value=""
			       class="regular-text"
				/>
			</td>
		</tr>
	</table>
	<?php
}
//add_action( 'user_new_form', 'wpd_ecommerce_admin_registration_form' );

/**
 * User Profile  Backend fields
 */
function wpd_ecommerce_show_extra_profile_fields( $user ) {
	$birth_year = get_the_author_meta( 'year_of_birth', $user->ID );
	?>
	<h3><?php esc_html_e( 'Personal Information', 'wpd-ecommerce' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'wpd-ecommerce' ); ?></label></th>
			<td>
				<input type="number"
			       min="1900"
			       max="2018"
			       step="1"
			       id="year_of_birth"
			       name="year_of_birth"
			       value="<?php echo esc_attr( $birth_year ); ?>"
			       class="regular-text"
				/>
			</td>
		</tr>
	</table>
	<?php
}
//add_action( 'show_user_profile', 'wpd_ecommerce_show_extra_profile_fields' );
//add_action( 'edit_user_profile', 'wpd_ecommerce_show_extra_profile_fields' );

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
function wpd_ecommerce_show_contact_info_fields( $user ) {

	// Remove website field.
    unset( $fields['url'] );

	// Add Phone number.
	$fields['phone_number'] = __( 'Phone number', 'wpd-ecommerce' );

	// Add Address line 1.
	$fields['address_line_1'] = __( 'Address line 1', 'wpd-ecommerce' );

	// Add Address line 2.
	$fields['address_line_2'] = __( 'Address line 2', 'wpd-ecommerce' );

	// Add City.
	$fields['city'] = __( 'City', 'wpd-ecommerce' );

	// Add State / County.
	$fields['state_county'] = __( 'State / County', 'wpd-ecommerce' );

	// Add Postcode/ZIP.
	$fields['postcode_zip'] = __( 'Postcode / ZIP', 'wpd-ecommerce' );

	// Add Country.
	$fields['country'] = __( 'Country', 'wpd-ecommerce' );

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
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'wpd-ecommerce' ) );
	}

	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) < 1900 ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: You must be born after 1900.', 'wpd-ecommerce' ) );
	}
}
//add_action( 'user_profile_update_errors', 'wpd_ecommerce_user_profile_update_errors', 10, 3 );
