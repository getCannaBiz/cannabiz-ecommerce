<?php
/**
 * WP Dispensary eCommerce patient account shortcode
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

// Add Patient Account Shortcode.
function wpd_patient_account_shortcode() {

	global $current_user, $wp_roles;

	$error = array();

	// Display login/register forms
	if ( ! is_user_logged_in() ) {

		$args['form_id'] = 'wpd-ecommerce-login';

		echo wpd_ecommerce_notifications();

		do_action( 'wpd_ecommerce_patient_account_login_form_before' );

		echo '<div class="wpd-ecommerce-login-form">';
		do_action( 'wpd_ecommerce_patient_account_login_form_before_inside' );
		echo '<h3>' . __( 'Login', 'wpd-ecommerce' ) . '</h3>';
		echo wp_login_form( $args );
		do_action( 'wpd_ecommerce_patient_account_login_form_after_inside' );
		echo '</div>';

		do_action( 'wpd_ecommerce_patient_account_login_form_after' );

		if ( get_option( 'users_can_register' ) ) {
			do_action( 'wpd_ecommerce_patient_account_register_form_before' );

			echo '<div class="wpd-ecommerce-register-form">';
			do_action( 'wpd_ecommerce_patient_account_register_form_before_inside' );
			echo '<h3>' . __( 'Register', 'wpd-ecommerce' ) . '</h3>';
			?>
			<form id="wpd-ecommerce-register" action="<?php echo site_url( 'wp-login.php?action=register', 'login_post' ) ?>" method="post">
			<?php do_action( 'wpd_ecommerce_patient_account_register_form_inside_top' ); ?>
			<p class="register-username">
			<label for="user_login"><?php _e( 'Username', 'wpd-ecommerce' ); ?></label>
			<input type="text" name="user_login" value="" id="user_login" class="input" />
			</p>
			<?php do_action( 'wpd_ecommerce_patient_account_register_form_after_username' ); ?>
			<p class="register-email-address">
			<label for="user_email"><?php _e( 'Email address', 'wpd-ecommerce' ); ?></label>
			<input type="text" name="user_email" value="" id="user_email" class="input" />
			</p>
			<?php do_action( 'wpd_ecommerce_patient_account_register_form_after_email' ); ?>
			<p class="statement"><?php _e( 'A password will be emailed to you.', 'wpd-ecommerce' ); ?></p>
			<p class="register-submit">
			<input type="submit" value="<?php _e( 'Register', 'wpd-ecommerce' ); ?>" id="register" />
			</p>
			<?php do_action( 'wpd_ecommerce_patient_account_register_form_inside_bottom' ); ?>
			</form>
			<?php
			do_action( 'wpd_ecommerce_patient_account_register_form_after_inside' );
			echo '</div>';

			do_action( 'wpd_ecommerce_patient_account_register_form_after' );

		} // users_can_register
	} else {
		/**
		 * @todo move this to another helper function so it's cleaner here
		 */
		/* If account details were saved, update patient account. */
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

			// Remove valid ID file from user profile.
			if ( isset( $_POST['remove_valid_id'] ) ) {
				// Update user meta.
				update_user_meta( $current_user->ID, 'wpd_ecommerce_patient_valid_id', '' );
			}
		
			// Remove doctor recommendation file from user profile.
			if ( isset( $_POST['remove_recommendation_doc'] ) ) {
				// Update user meta.
				update_user_meta( $current_user->ID, 'wpd_ecommerce_patient_recommendation_doc', '' );		
			}
		
			/* Update user password. */
			if ( ! empty( $_POST['pass1'] ) && ! empty( $_POST['pass2'] ) ) {
				if ( $_POST['pass1'] == $_POST['pass2'] )
					wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => sanitize_text_field( $_POST['pass1'] ) ) );
				else
					$error[] = __( 'The passwords you entered do not match.  Your password was not updated.', 'wpd-ecommerce' );
			}

			/* Update user email address */
			if ( ! empty( $_POST['email'] ) ) {
				if ( ! is_email( sanitize_text_field( $_POST['email'] ) ) )
					$error[] = __( 'The Email you entered is not valid. Please try again.', 'wpd-ecommerce' );
				elseif( email_exists( sanitize_text_field( $_POST['email'] ) ) != $current_user->id )
					$error[] = __( 'This email is already used by another user. Try a different one.', 'wpd-ecommerce' );
				else {
					wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => sanitize_text_field( $_POST['email'] ) ) );
				}
			}

			/* Update user information */
			if ( ! empty( $_POST['first-name'] ) )
				update_user_meta( $current_user->ID, 'first_name', sanitize_text_field( $_POST['first-name'] ) );
			if ( ! empty( $_POST['last-name'] ) )
				update_user_meta($current_user->ID, 'last_name', sanitize_text_field( $_POST['last-name'] ) );
			if ( ! empty( $_POST['phone_number'] ) )
				update_user_meta( $current_user->ID, 'phone_number', sanitize_text_field( $_POST['phone_number'] ) );
			if ( ! empty( $_POST['address_line_1'] ) )
				update_user_meta( $current_user->ID, 'address_line_1', sanitize_text_field( $_POST['address_line_1'] ) );
			if ( ! empty( $_POST['address_line_2'] ) )
				update_user_meta( $current_user->ID, 'address_line_2', sanitize_text_field( $_POST['address_line_2'] ) );
			if ( ! empty( $_POST['city'] ) )
				update_user_meta( $current_user->ID, 'city', sanitize_text_field( $_POST['city'] ) );
			if ( ! empty( $_POST['state_county'] ) )
				update_user_meta( $current_user->ID, 'state_county', sanitize_text_field( $_POST['state_county'] ) );
			if ( ! empty( $_POST['postal_zip'] ) )
				update_user_meta( $current_user->ID, 'postal_zip', sanitize_text_field( $_POST['postal_zip'] ) );
			if ( ! empty( $_POST['country'] ) )
				update_user_meta( $current_user->ID, 'country', sanitize_text_field( $_POST['country'] ) );
			if ( ! empty( $_POST['description'] ) )
				update_user_meta( $current_user->ID, 'description', sanitize_text_field( $_POST['description'] ) );

			/**
			 * Redirect so the page will show updated info.
			 */
			if ( 0 == count( $error ) ) {
				// Action hook for plugins and extra fields saving
				do_action( 'edit_user_profile_update', $current_user->ID );
				wp_redirect( get_permalink() );
				exit;
			}
		}

		// Display notifications.
		echo wpd_ecommerce_notifications();
		?>

		<div class="wpd-ecommerce patient-account">
			<input class="account-links" id="tab1" type="radio" name="tabs" checked>
			<label class="account-links" for="tab1"><?php _e( 'Dashboard', 'wpd-ecommerce' ); ?></label>

			<input class="account-links" id="tab2" type="radio" name="tabs">
			<label class="account-links" for="tab2"><?php _e( 'Orders', 'wpd-ecommerce' ); ?></label>

			<input class="account-links" id="tab3" type="radio" name="tabs">
			<label class="account-links" for="tab3"><?php _e( 'Details', 'wpd-ecommerce' ); ?></label>

	<!--
			<input class="account-links" id="tab4" type="radio" name="tabs">
			<label class="account-links" for="tab4">Another One</label>
	-->

			<section id="content1">

				<?php
					$user = wp_get_current_user();
					$role = ( array ) $user->roles;
				
					// Patient name based on specific profile info.
					if ( '' !== $user->first_name && '' !== $user->last_name ) {
						$patient_name = $user->first_name . '  ' . $user->last_name;
					} elseif ( '' !== $user->display_name ) {
						$patient_name = $user->display_name;
					} else {
						$patient_name = $user->user_nicename;
					}
					?>
				<p><?php _e( 'Hello', 'wpd-ecommerce' ); ?> <strong><?php echo $patient_name; ?></strong> (<a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( 'Log out', 'wpd-ecommerce' ); ?></a>)</p>

				<?php					
				// If user is administrator.
				if ( 'administrator' !== $role[0] ) {
				?>
				<h3 class="wpd-ecommerce patient-title"><?php _e( 'Account dashboard', 'wpd-ecommerce' ); ?></h3>
				<p><?php _e( 'From your account dashboard you can view your order history and account details.', 'wpd-ecommerce' ); ?></p>
				<?php
				}
					// If user is administrator.
					if ( 'administrator' === $role[0] ) {

						$today = getdate();

						// Daily orders.
						$date_query_daily = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
								'day'   => $today['mday'],
							),
						);

						$date_query_yesterday = date( 'd', strtotime( '-1 days' ) );

						// Weekly orders.
						$date_query_weekly = array(
							array(
								'year' => date( 'Y' ),
								'week' => date( 'W' ),
							),
						);

						$args = array(
							'nopaging'    => TRUE,
							'post_status' => 'publish',
							'post_type'   => 'wpd_orders',
							'order'       => 'ASC',
							'date_query'  => $date_query_weekly,
							'meta_query'  => array(
								array(
									'key' => 'wpd_order_total_price',
								),
							),
						);
						$the_query = new WP_Query( $args );
						?>

						<?php
						if ( $the_query->have_posts() ) :
							while ( $the_query->have_posts() ) : $the_query->the_post();
								// Add item's total price to the total_earnings array.
								$total_earnings[] = get_post_meta( get_the_ID(), 'wpd_order_total_price', TRUE );
							endwhile;
							wp_reset_postdata();
						else : endif;

						$order_count   = $the_query->post_count;
						$patient_count = $users_count = count( get_users( array( 'fields' => array( 'ID' ), 'role' => 'patient' ) ) );

						if ( isset( $total_earnings ) ) {
							$total_final = array_sum( $total_earnings );
						} else {
							$total_final = '0';
						}

						echo '<div class="wpd-ecommerce account-administrator patients">' . $patient_count . '<span>' . apply_filters( 'wpd_ecommerce_account_admin_patients_text', 'Patients' ) . '</span></div>';
						echo '<div class="wpd-ecommerce account-administrator orders">' . $order_count . '<span>' . __( 'Orders', 'wpd-ecommerce' ) . '</span></div>';
						echo '<div class="wpd-ecommerce account-administrator earnings">' . CURRENCY . number_format( (float)$total_final, 2, '.', ',' ) . '<span>' . __( 'This Week', 'wpd-ecommerce' ) . '</span></div>';
					}
				// If user is administrator.
				if ( 'administrator' === $role[0] ) {
				?>
				<h3 class="wpd-ecommerce patient-title"><?php _e( 'Recent Store Orders', 'wpd-ecommerce' ); ?></h3>
				<table class="wpd-ecommerce patient-orders">
					<thead>
						<td><?php _e( 'ID', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Name', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Date', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Status', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Total', 'wpd-ecommerce' ); ?></td>
					</thead>
					<tbody>
					<?php
						$user = wp_get_current_user();
						$args = array(
							'post_type' => 'wpd_orders',
						);
						$the_query   = new WP_Query( $args );
						// Create empty table_admin variable.
						$table_admin = '';
					?>
					<?php if ( $the_query->have_posts() ) : ?>
				
					<!-- the loop -->
					<?php
					while ( $the_query->have_posts() ) : $the_query->the_post();

					$total             = get_post_meta( get_the_ID(), 'wpd_order_total_price', TRUE );
					$status_names      = wpd_ecommerce_get_order_statuses();
					$status            = get_post_meta( get_the_ID(), 'wpd_order_status', TRUE );
					$status_display    = wpd_ecommerce_order_statuses( get_the_ID(), NULL, NULL );
					$order_customer_id = get_post_meta( get_the_ID(), 'wpd_order_customer_id', true );
					$customer          = get_userdata( $order_customer_id );

					if ( isset( $customer ) ) {
						$customer_name = $customer->first_name . ' ' . $customer->last_name;
					} else {
						$customer_name = '';
					}

					if ( empty( $status ) ) {
						$status = 'wpd-pending';
					}

					$table_admin .= '<tr>
						<td><a href="' . get_the_permalink() . '">#' . get_the_ID() . '</a></td>
						<td>' . $customer_name . '</td>
						<td>' . get_the_date() . '<td>' . $status_display . '</td>
						<td>' . CURRENCY . number_format( (float)$total, 2, '.', ',' ) . '</td>
					</tr>';

					endwhile;
					endif;
					?>
					<!-- end of the loop -->

					<?php wp_reset_postdata(); ?>

					<?php echo $table_admin; ?>
					</tbody>
				</table>

				<?php
				} // end if is administrator.
				?>
			</section>

			<section id="content2">
				<h3 class="wpd-ecommerce patient-title"><?php _e( 'Order history', 'wpd-ecommerce' ); ?></h3>
				<table class="wpd-ecommerce patient-orders">
					<thead>
						<td><?php _e( 'ID', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Date', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Status', 'wpd-ecommerce' ); ?></td>
						<td><?php _e( 'Total', 'wpd-ecommerce' ); ?></td>
					</thead>
					<tbody>
					<?php
						$table = '';
						$user  = wp_get_current_user();
						$args  = array(
							'post_type'  => 'wpd_orders',
							'meta_query' => array(
								array(
									'key'   => 'wpd_order_customer_id',
									'value' => $user->ID,
								),
							),
						);
						$the_query = new WP_Query( $args );
					?>
					<?php if ( $the_query->have_posts() ) : ?>
				
					<!-- the loop -->
					<?php
					while ( $the_query->have_posts() ) : $the_query->the_post();

					$total          = get_post_meta( get_the_ID(), 'wpd_order_total_price', TRUE );
					$status_names   = wpd_ecommerce_get_order_statuses();
					$status         = get_post_meta( get_the_ID(), 'wpd_order_status', TRUE );
					$status_display = wpd_ecommerce_order_statuses( get_the_ID(), NULL, NULL );

					if ( empty( $status ) ) {
						$status = 'wpd-pending';
					}

					$table .= '<tr>
						<td><a href="' . get_the_permalink() . '">#' . get_the_ID() . '</a></td>
						<td>' . get_the_date() . '<td>' . $status_display . '</td>
						<td>' . CURRENCY . number_format( (float)$total, 2, '.', ',' ) . '</td>
					</tr>';

					endwhile;
					endif;
					?>
					<!-- end of the loop -->

					<?php wp_reset_postdata(); ?>

					<?php echo $table; ?>
					</tbody>
				</table>
			</section>

			<section id="content3">

				<?php do_action( 'wpd_ecommerce_patient_account_form_before' ); ?>

				<form method="post" id="patients" class="wpd-ecommerce form patient-account" action="<?php the_permalink(); ?>" enctype="multipart/form-data">

				<?php do_action( 'wpd_ecommerce_patient_account_form_before_inside' ); ?>

				<h3 class='wpd-ecommerce patient-title'><?php _e( 'Contact information', 'wpd-ecommerce' ); ?></h3>

				<?php do_action( 'wpd_ecommerce_patient_account_contact_information_before' ); ?>

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
					<select id="country" name="country" class="form-control">
						<?php
						// Current user's country.
						$current_user_country = get_the_author_meta( 'country', $current_user->ID );

						// Countries output as select fields.
						$options = array( 'Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegowina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, the Democratic Republic of the', 'Cook Islands', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France Metropolitan', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard and Mc Donald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran (Islamic Republic of)', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao, People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia, The Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia (Slovak Republic)', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'Spain', 'Sri Lanka', 'St. Helena', 'St. Pierre and Miquelon', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen Islands', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan, Province of China', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela', 'Vietnam', 'Virgin Islands (British)', 'Virgin Islands (U.S.)', 'Wallis and Futuna Islands', 'Western Sahara', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe' );

						// Filter countries options.
						$options = apply_filters( 'wpd_ecommerce_patient_account_details_form_countries', $options );

						// Create country select list.
						foreach( $options as $value=>$name ) {
							if ( $name == $current_user_country ) {
								echo '<option selected="selected" value="' . $name . '">' . $name . '</option>';
							} else {
								echo '<option value="' . $name . '">' . $name . '</option>';
							}
						}
						?>
					</select>
				</p><!-- .form-phone-country -->

				<?php do_action( 'wpd_ecommerce_patient_account_contact_information_after' ); ?>

				<?php
				$wpd_patients = get_option( 'wpdas_patients' );

				if ( empty( $wpd_patients ) ) {
					// Do nothing.
				} elseif (
					'on' == $wpd_patients['wpd_settings_patients_verification_drivers_license'] &&
					'on' == $wpd_patients['wpd_settings_patients_verification_recommendation_doc'] && 
					'on' == $wpd_patients['wpd_settings_patients_verification_recommendation_num'] && 
					'on' == $wpd_patients['wpd_settings_patients_verification_recommendation_exp'] ) {
				} else { ?>
				<h3 class="wpd-ecommerce patient-title"><?php _e( 'Verification', 'wpd-ecommerce' ); ?></h3>

				<?php do_action( 'wpd_ecommerce_patient_account_verification_before' ); ?>

				<?php
				if ( 'on' == $wpd_patients['wpd_settings_patients_verification_drivers_license'] ) {
					// Do nothing.
				} else {
				?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_wpd_ecommerce_patient_valid_id"><?php _e( 'Drivers License or Valid ID', 'woocommerce' ); ?></label>
					<?php if ( get_user_meta( $user->ID, 'wpd_ecommerce_patient_valid_id', true ) ) { ?>
					<div class="valid-id">
						<?php
						$valid_id = get_user_meta( $user->ID, 'wpd_ecommerce_patient_valid_id', true );
						if ( ! isset( $valid_id['error'] ) ) {
							if ( ! empty( $valid_id ) ) {
								$valid_id = $valid_id['url'];
								echo '<a href="' . $valid_id . '" target="_blank"><img src="' . $valid_id . '" width="100" height="100" class="wpd-ecommerce-valid-id" /></a><br />';
							}
						} else {
							$valid_id = $valid_id['error'];
							echo $valid_id. '<br />';
						}
						?>
						<input type="submit" class="remove-valid-id" name="remove_valid_id" value="<?php _e( 'x', 'wpd-ecommerce' ); ?>" />
					</div><!-- /.valid-id -->
					<?php } ?>
					<input type="file" name="wpd_ecommerce_patient_valid_id" id="reg_wpd_ecommerce_patient_valid_id" value="" />
				</p>

				<?php 
				}

				if ( 'on' == $wpd_patients['wpd_settings_patients_verification_recommendation_doc'] ) {
					// Do nothing.
				} else {
				?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_wpd_ecommerce_patient_recommendation_doc"><?php _e( 'Doctor recommendation', 'woocommerce' ); ?></label>
					<?php if ( get_user_meta( $user->ID, 'wpd_ecommerce_patient_recommendation_doc', true ) ) { ?>
					<div class="recommendation-doc">
						<?php
						$doc_rec = get_user_meta( $user->ID, 'wpd_ecommerce_patient_recommendation_doc', true );
						if ( ! isset( $doc_rec['error'] ) ) {
							if ( ! empty( $doc_rec ) ) {
								$doc_rec = $doc_rec['url'];
								echo '<a href="' . $doc_rec . '" target="_blank"><img src="' . $doc_rec . '" width="100" height="100" class="wpd-ecommerce-recommendation-doc" /></a><br />';
							}
						} else {
							$doc_rec = $doc_rec['error'];
							echo $doc_rec. '<br />';
						}
						?>
						<input type="submit" class="remove-recommendation-doc" name="remove_recommendation_doc" value="<?php _e( 'x', 'wpd-ecommerce' ); ?>" />
					</div><!-- /.recommendation-doc -->
					<?php } ?>
					<input type="file" name="wpd_ecommerce_patient_recommendation_doc" id="reg_wpd_ecommerce_patient_recommendation_doc" value="" />
				</p>

				<?php 
				}

				if ( 'on' == $wpd_patients['wpd_settings_patients_verification_recommendation_num'] ) {
					// Do nothing.
				} else {
				?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_wpd_ecommerce_patient_recommendation_num"><?php _e( 'Recommendation number', 'woocommerce' ); ?></label>
					<input type="text" class="input-text" name="wpd_ecommerce_patient_recommendation_num" id="reg_wpd_ecommerce_patient_recommendation_num" value="<?php echo get_user_meta( $user->ID, 'wpd_ecommerce_patient_recommendation_num', true ); ?>" />
				</p>

				<?php 
				}

				if ( 'on' == $wpd_patients['wpd_settings_patients_verification_recommendation_exp'] ) {
					// Do nothing.
				} else {
				?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_wpd_ecommerce_patient_recommendation_exp"><?php _e( 'Expiration Date', 'woocommerce' ); ?></label>
					<input type="date" class="input-date" name="wpd_ecommerce_patient_recommendation_exp" id="reg_wpd_ecommerce_patient_recommendation_exp" value="<?php echo get_user_meta( $user->ID, 'wpd_ecommerce_patient_recommendation_exp', true ); ?>" />
				</p>

				<?php } // recommendation_exp ?>

				<?php do_action( 'wpd_ecommerce_patient_account_verification_after' ); ?>

				<?php } // if all verifications are hidden ?>

				<h3 class="wpd-ecommerce patient-title"><?php _e( 'Password change', 'wpd-ecommerce' ); ?></h3>

				<?php do_action( 'wpd_ecommerce_patient_account_password_change_before' ); ?>

				<p class="form-row form-first form-password">
					<label for="pass1"><?php _e( 'Password', 'wpd-ecommerce' ); ?><span class="required">*</span> <em><?php _e( 'Leave blank to keep unchanged', 'wpd-ecommerce' ); ?></em></label>
					<input class="text-input" name="pass1" type="password" id="pass1" />
				</p><!-- .form-password -->
				<p class="form-row form-last form-password">
					<label for="pass2"><?php _e( 'Repeat Password', 'wpd-ecommerce' ); ?><span class="required">*</span> <em><?php _e( 'Leave blank to keep unchanged', 'wpd-ecommerce' ); ?></em></label>
					<input class="text-input" name="pass2" type="password" id="pass2" />
				</p><!-- .form-password -->

				<p class="form-submit">
					<input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e( 'Update', 'wpd-ecommerce' ); ?>" />
					<?php wp_nonce_field( 'update-user' ) ?>
					<input name="action" type="hidden" id="action" value="update-user" />
				</p><!-- .form-submit -->

				<?php do_action( 'wpd_ecommerce_patient_account_form_inside_after' ); ?>

				</form><!-- #patients -->

				<?php do_action( 'wpd_ecommerce_patient_account_form_after' ); ?>

			</section>
		</div>

	<?php
	}
}
add_shortcode( 'wpd_account', 'wpd_patient_account_shortcode' );
