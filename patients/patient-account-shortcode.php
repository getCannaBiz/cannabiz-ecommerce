<?php
// Add Patient Account Shortcode.
function wpd_patient_account_shortcode() {

	global $current_user, $wp_roles;

	$error = array();

	// Display login/register forms
	if ( ! is_user_logged_in() ) {

		$args['form_id'] = 'wpd-ecommerce-login';

		echo "<div class='wpd-ecommerce-login-form'>";

		echo "<h3>Login</h3>";
		if ( 'failed' === $_GET['login'] ) {
			echo "<p class='wpd-ecommerce-notifications error'><strong>LOGIN FAILED DUMMY</strong></p>";
		}
		echo wp_login_form( $args );
		echo "</div>";

	} else {
		/* If account details were saved, update patient account. */
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

			/* Update user password. */
			if ( ! empty( $_POST['pass1'] ) && ! empty( $_POST['pass2'] ) ) {
				if ( $_POST['pass1'] == $_POST['pass2'] )
					wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
				else
					$error[] = __( 'The passwords you entered do not match.  Your password was not updated.', 'wpd-ecommerce' );
			}

			/* Update user email address */
			if ( ! empty( $_POST['email'] ) ) {
				if ( ! is_email( esc_attr( $_POST['email'] ) ) )
					$error[] = __( 'The Email you entered is not valid. Please try again.', 'wpd-ecommerce' );
				elseif( email_exists( esc_attr( $_POST['email'] ) ) != $current_user->id )
					$error[] = __( 'This email is already used by another user. Try a different one.', 'wpd-ecommerce' );
				else {
					wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] ) ) );
				}
			}

			/* Update user information */
			if ( ! empty( $_POST['first-name'] ) )
				update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
			if ( ! empty( $_POST['last-name'] ) )
				update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
			if ( ! empty( $_POST['phone_number'] ) )
				update_user_meta( $current_user->ID, 'phone_number', esc_attr( $_POST['phone_number'] ) );
			if ( ! empty( $_POST['address_line_1'] ) )
				update_user_meta( $current_user->ID, 'address_line_1', esc_attr( $_POST['address_line_1'] ) );
			if ( ! empty( $_POST['address_line_2'] ) )
				update_user_meta( $current_user->ID, 'address_line_2', esc_attr( $_POST['address_line_2'] ) );
			if ( ! empty( $_POST['city'] ) )
				update_user_meta( $current_user->ID, 'city', esc_attr( $_POST['city'] ) );
			if ( ! empty( $_POST['state_county'] ) )
				update_user_meta( $current_user->ID, 'state_county', esc_attr( $_POST['state_county'] ) );
			if ( ! empty( $_POST['postal_zip'] ) )
				update_user_meta( $current_user->ID, 'postal_zip', esc_attr( $_POST['postal_zip'] ) );
			if ( ! empty( $_POST['country'] ) )
				update_user_meta( $current_user->ID, 'country', esc_attr( $_POST['country'] ) );
			if ( ! empty( $_POST['description'] ) )
				update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

			/**
			 * Redirect so the page will show updated info.
			 */
			if ( count( $error ) == 0 ) {
				// Action hook for plugins and extra fields saving
				do_action( 'edit_user_profile_update', $current_user->ID );
				wp_redirect( get_permalink() );
				exit;
			}
		}
		?>

		<form method="post" id="patients" class="wpd-ecommerce form patient-account" action="<?php the_permalink(); ?>">

			<h3 class='wpd-ecommerce patient-title'><?php _e( 'Contact information', 'wpd-ecommerce' ); ?></h3>

			<?php
			/**
			 * @todo add action_hook here
			 */
			?>
			<p class="form-row first form-first-name">
				<label for="first-name"><?php _e( 'First Name', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
				<input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
			</p><!-- .form-first-name -->
			<p class="form-row last form-last-name">
				<label for="last-name"><?php _e( 'Last Name', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
				<input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
			</p><!-- .form-last-name -->

			<p class="form-row form-email">
				<label for="email"><?php _e( 'E-mail', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
				<input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
			</p><!-- .form-email -->

			<p class="form-row form-phone-number">
				<label for="email"><?php _e( 'Phone number', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="phone_number" type="text" id="phone_number" value="<?php the_author_meta( 'phone_number', $current_user->ID ); ?>" />
			</p><!-- .form-phone-number -->

			<p class="form-row form-address-line-1">
				<label for="email"><?php _e( 'Address line 1', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="address_line_1" type="text" id="address_line_1" value="<?php the_author_meta( 'address_line_1', $current_user->ID ); ?>" />
			</p><!-- .form-address-line-1 -->

			<p class="form-row form-address-line-2">
				<label for="email"><?php _e( 'Address line 2', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="address_line_2" type="text" id="address_line_2" value="<?php the_author_meta( 'address_line_2', $current_user->ID ); ?>" />
			</p><!-- .form-address-line-2 -->

			<p class="form-row form-city">
				<label for="email"><?php _e( 'City', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="city" type="text" id="city" value="<?php the_author_meta( 'city', $current_user->ID ); ?>" />
			</p><!-- .form-city -->

			<p class="form-row form-state-county">
				<label for="email"><?php _e( 'State / County', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="state-county" type="text" id="state_county" value="<?php the_author_meta( 'state_county', $current_user->ID ); ?>" />
			</p><!-- .form-state-county -->

			<p class="form-row form-postcode-zip">
				<label for="email"><?php _e( 'Postcode / ZIP', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="postcode_zip" type="text" id="postcode_zip" value="<?php the_author_meta( 'postcode_zip', $current_user->ID ); ?>" />
			</p><!-- .form-postcode-zip -->

			<p class="form-row form-country">
				<label for="email"><?php _e( 'Country', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="country" type="text" id="country" value="<?php the_author_meta( 'country', $current_user->ID ); ?>" />
			</p><!-- .form-phone-country -->

			<?php
			/**
			 * @todo add action_hook here
			 */
			?>

			<h3 class='wpd-ecommerce patient-title'><?php _e( 'Password change', 'wpd-ecommerce' ); ?></h3>

			<?php
			/**
			 * @todo add action_hook here
			 */
			?>

			<p class="form-row form-first form-password">
				<label for="pass1"><?php _e( 'Password', 'wpd-ecommerce' ); ?><span class="required">*</span> <em><?php _e( 'Leave blank to keep unchanged', 'wpd-ecommerce' ); ?></em></label>
				<input class="text-input" name="pass1" type="password" id="pass1" />
			</p><!-- .form-password -->
			<p class="form-row form-last form-password">
				<label for="pass2"><?php _e( 'Repeat Password', 'wpd-ecommerce' ); ?><span class="required">*</span> <em><?php _e( 'Leave blank to keep unchanged', 'wpd-ecommerce' ); ?></label>
				<input class="text-input" name="pass2" type="password" id="pass2" />
			</p><!-- .form-password -->

			<p class="form-submit">
				<input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e( 'Update', 'wpd-ecommerce' ); ?>" />
				<?php wp_nonce_field( 'update-user' ) ?>
				<input name="action" type="hidden" id="action" value="update-user" />
			</p><!-- .form-submit -->

			<?php
			/**
			 * @todo add action_hook here
			 */
			?>

		</form><!-- #patients -->
	<?php
	}
}
add_shortcode( 'wpd_account', 'wpd_patient_account_shortcode' );
