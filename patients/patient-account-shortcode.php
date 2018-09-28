<?php
// Add Patient Account Shortcode.
function wpd_patient_account_shortcode() {
	global $current_user, $wp_roles;

	$error = array();

	/* If account details were saved, update patient account. */
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

		/* Update user password. */
		if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
			if ( $_POST['pass1'] == $_POST['pass2'] )
				wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
			else
				$error[] = __( 'The passwords you entered do not match.  Your password was not updated.', 'wp-dispensary');
		}

		/* Update user information. */
		if ( ! empty( $_POST['email'] ) ) {
			if ( ! is_email(esc_attr( $_POST['email'] ) ) )
				$error[] = __( 'The Email you entered is not valid. Please try again.', 'wp-dispensary' );
			elseif( email_exists( esc_attr( $_POST['email'] ) ) != $current_user->id )
				$error[] = __( 'This email is already used by another user. Try a different one.', 'wp-dispensary' );
			else {
				wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] ) ) );
			}
		}

		if ( ! empty( $_POST['first-name'] ) )
			update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
		if ( ! empty( $_POST['last-name'] ) )
			update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
		if ( ! empty( $_POST['description'] ) )
			update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

		/**
		 * Redirect so the page will show updated info.
		 * I am not Author of this Code- i dont know why but it worked for me after changing below line
		 * to if ( count($error) == 0 ){
		 */
		if ( count( $error ) == 0 ) {
			//action hook for plugins and extra fields saving
			do_action( 'edit_user_profile_update', $current_user->ID );
			wp_redirect( get_permalink() );
			exit;
		}
	}
	?>

	<h2 class='wpd-ecommerce patient-title'>Account Details</h2>

	<form method="post" id="patients" class="wpd-ecommerce form patient-account" action="<?php the_permalink(); ?>">

		<p class="form-row first form-first-name">
			<label for="first-name"><?php _e('First Name', 'wp-dispensary' ); ?><span class="required">*</span></label>
			<input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
		</p><!-- .form-first-name -->
		<p class="form-row last form-last-name">
			<label for="last-name"><?php _e('Last Name', 'wp-dispensary' ); ?><span class="required">*</span></label>
			<input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
		</p><!-- .form-last-name -->

		<p class="form-row form-email">
			<label for="email"><?php _e( 'E-mail', 'wp-dispensary' ); ?><span class="required">*</span></label>
			<input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
		</p><!-- .form-email -->

		<p class="form-row form-first form-password">
			<label for="pass1"><?php _e( 'Password', 'wp-dispensary' ); ?><span class="required">*</span></label>
			<input class="text-input" name="pass1" type="password" id="pass1" />
		</p><!-- .form-password -->
		<p class="form-row form-last form-password">
			<label for="pass2"><?php _e('Repeat Password', 'wp-dispensary'); ?><span class="required">*</span></label>
			<input class="text-input" name="pass2" type="password" id="pass2" />
		</p><!-- .form-password -->

		<p class="form-submit">
			<input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e( 'Update', 'wp-dispensary' ); ?>" />
			<?php wp_nonce_field( 'update-user' ) ?>
			<input name="action" type="hidden" id="action" value="update-user" />
		</p><!-- .form-submit -->
	</form><!-- #patients -->
<?php
}
add_shortcode( 'wpd_account', 'wpd_patient_account_shortcode' );
