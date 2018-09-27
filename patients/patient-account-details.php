<?php

/**
 * Front end registration
 */

add_action( 'register_form', 'wpd_shopping_cart_registration_form' );
function wpd_shopping_cart_registration_form() {

	$year = ! empty( $_POST['year_of_birth'] ) ? intval( $_POST['year_of_birth'] ) : '';

	?>
	<p>
		<label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'wp-dispensary' ) ?><br/>
			<input type="number"
			       min="1900"
			       max="2018"
			       step="1"
			       id="year_of_birth"
			       name="year_of_birth"
			       value="<?php echo esc_attr( $year ); ?>"
			       class="input"
			/>
		</label>
	</p>
	<?php
}

add_filter( 'registration_errors', 'wpd_shopping_cart_registration_errors', 10, 3 );
function wpd_shopping_cart_registration_errors( $errors, $sanitized_user_login, $user_email ) {

	if ( empty( $_POST['year_of_birth'] ) ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'wp-dispensary' ) );
	}

	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) < 1900 ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: You must be born after 1900.', 'wp-dispensary' ) );
	}

	return $errors;
}

add_action( 'user_register', 'wpd_shopping_cart_user_register' );
function wpd_shopping_cart_user_register( $user_id ) {
	if ( ! empty( $_POST['year_of_birth'] ) ) {
		update_user_meta( $user_id, 'year_of_birth', intval( $_POST['year_of_birth'] ) );
	}
}

/**
 * Back end registration
 */

add_action( 'user_new_form', 'wpd_shopping_cart_admin_registration_form' );
function wpd_shopping_cart_admin_registration_form( $operation ) {
	if ( 'add-new-user' !== $operation ) {
		// $operation may also be 'add-existing-user' (multisite, I believe!?)
		return;
	}

	$year = ! empty( $_POST['year_of_birth'] ) ? intval( $_POST['year_of_birth'] ) : '';

	?>
	<h3><?php esc_html_e( 'Personal Information', 'wp-dispensary' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'wp-dispensary' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'wp-dispensary' ); ?></span></th>
			<td>
				<input type="number"
			       min="1900"
			       max="2017"
			       step="1"
			       id="year_of_birth"
			       name="year_of_birth"
			       value="<?php echo esc_attr( $year ); ?>"
			       class="regular-text"
				/>
			</td>
		</tr>
	</table>
	<?php
}

add_action( 'user_profile_update_errors', 'wpd_shopping_cart_user_profile_update_errors', 10, 3 );
function wpd_shopping_cart_user_profile_update_errors( $errors, $update, $user ) {
	if ( empty( $_POST['year_of_birth'] ) ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'wp-dispensary' ) );
	}

	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) < 1900 ) {
		$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: You must be born after 1900.', 'wp-dispensary' ) );
	}
}

add_action( 'edit_user_created_user', 'wpd_shopping_cart_user_register' );


/**
 * Back end display
 */

add_action( 'show_user_profile', 'wpd_shopping_cart_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'wpd_shopping_cart_show_extra_profile_fields' );

function wpd_shopping_cart_show_extra_profile_fields( $user ) {
	$year = get_the_author_meta( 'year_of_birth', $user->ID );
	?>
	<h3><?php esc_html_e( 'Personal Information', 'wp-dispensary' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'wp-dispensary' ); ?></label></th>
			<td>
				<input type="number"
			       min="1900"
			       max="2018"
			       step="1"
			       id="year_of_birth"
			       name="year_of_birth"
			       value="<?php echo esc_attr( $year ); ?>"
			       class="regular-text"
				/>
			</td>
		</tr>
	</table>
	<?php
}

add_action( 'personal_options_update', 'wpd_shopping_cart_update_profile_fields' );
add_action( 'edit_user_profile_update', 'wpd_shopping_cart_update_profile_fields' );

function wpd_shopping_cart_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! empty( $_POST['year_of_birth'] ) && intval( $_POST['year_of_birth'] ) >= 1900 ) {
		update_user_meta( $user_id, 'year_of_birth', intval( $_POST['year_of_birth'] ) );
	}
}
