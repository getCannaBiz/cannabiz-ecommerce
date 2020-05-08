<?php
/**
 * WP Dispensary eCommerce patient helper functions
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Disable admin bar for patients.
 *
 * @since 1.0.0
 */
function wpd_ecommerce_disable_admin_bar() {

    // Only run for logged in users.
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        $role = ( array ) $user->roles;

        // Hide admin bar for patients.
        if ( 'patient' === $role[0] ) {
            show_admin_bar( FALSE );
        } else {
            show_admin_bar( TRUE );
        }
    }
}
add_action( 'after_setup_theme', 'wpd_ecommerce_disable_admin_bar' );
 
/**
 * Disable wp-admin for patients.
 *
 * @since 1.0.0
 */
function wpd_ecommerce_disable_admin_dashboard() {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    // Redirect patients from any wp-admin page to account page.
    if ( is_admin() && 'patient' === $role[0] && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( wpd_ecommerce_account_url() );
        exit;
    } elseif ( is_user_logged_in() ) {
        // Do nothing for any other logged in user.
    } else {
        // Redirect admin for non-logged in users.
        if ( ! is_user_logged_in() && is_admin() ) {
            wp_redirect( wpd_ecommerce_account_url() );
            exit;
        }
    }

}
add_action( 'init', 'wpd_ecommerce_disable_admin_dashboard' );

/**
 * Prevent wp-login.php for patients
 */
function wpd_ecommerce_prevent_wp_login() {

    global $pagenow;

    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    /**
     * This locks down wp-login.php for patients only
     */
    if ( 'patient' === $role[0] ) {

        // Check if a $_GET['action'] is set, and if so, load it into $action variable
        $action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';

        // Check if we're on the login page, and ensure the action is not 'logout'
        if ( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array( $action, array( 'logout', 'lostpassword', 'rp', 'resetpass' ) ) ) ) ) {
            wp_redirect( wpd_ecommerce_account_url() );
            exit;
        }
    }

}
add_action( 'init', 'wpd_ecommerce_prevent_wp_login' );

/**
 * Login failed redirect
 * 
 * @since 1.0
 */
function wpd_ecommerce_login_failed() {
    $login_page = wpd_ecommerce_account_url();
    $ref_link   = $_SERVER['HTTP_REFERER'];

    // Redirect logins from account page.
    if ( $login_page === $ref_link ) {
        wp_redirect( $login_page . '?login=failed' );
        exit;
    }
}
add_action( 'wp_login_failed', 'wpd_ecommerce_login_failed' );

/**
 * Login success redirect
 * 
 * @since 1.0
 */
function wpd_ecommerce_login_redirect( $redirect_to, $request, $user ) {
    $user         = wp_get_current_user();
    $role         = ( array ) $user->roles;
    $login_page   = wpd_ecommerce_account_url();
    $ref_link     = $_SERVER['HTTP_REFERER'];

    // If user is patient.
    if ( 'patient' === $role[0] ) {
        // redirect them to another URL, in this case, the homepage 
        $redirect_to =  $login_page;
    }

    // Redirect logins from account page.
    if ( $ref_link === $login_page . '?login=failed' ) {
        $redirect_to =  $login_page;
    }

    return $redirect_to;
}
add_filter( 'login_redirect', 'wpd_ecommerce_login_redirect', 10, 3 );

/**
 * Disable author archives for patients.
 *
 * @since 1.0
 */
function wpd_ecommerce_disable_author_archives_for_customers() {
    global $author;
	if ( is_author() ) {
        $user = get_user_by( 'id', $author );
        $role = ( array ) $user->roles;

		if ( 'patient' === $role[0] ) {
			wp_redirect( wpd_ecommerce_menu_url() );
		} else {
			// Do nothing.
        }
	}
}
add_action( 'template_redirect', 'wpd_ecommerce_disable_author_archives_for_customers' );

/**
 * Add profile options to Edit User screen
 * 
 * @since 1.1
 */
function wpd_ecommerce_add_profile_options( $profileuser ) {
    // Update recommendation number.
    if ( isset( $_POST['wpd_ecommerce_patient_recommendation_num'] ) ) {
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_num', $_POST['wpd_ecommerce_patient_recommendation_num'] );
    }
    // Update recommendation expiration date.
    if ( isset( $_POST['wpd_ecommerce_patient_recommendation_exp'] ) ) {
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_exp', sanitize_text_field( $_POST['wpd_ecommerce_patient_recommendation_exp'] ) );
    }
    // Remove valid ID file from user profile.
    if ( isset( $_POST['remove_valid_id'] ) ) {
        // Update user meta.
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_valid_id', '' );
    }

    // Remove doctor recommendation file from user profile.
    if ( isset( $_POST['remove_recommendation_doc'] ) ) {
        // Update user meta.
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_doc', '' );		
    }

    $doc_rec  = get_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_doc', true );
    $valid_id = get_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_valid_id', true );
    ?>
    <?php do_action( 'wpd_ecommerce_verification_title_before' ); ?>
    <h2><?php _e( 'Verification', 'wpd-ecommerce' ); ?></h2>
    <?php do_action( 'wpd_ecommerce_verification_table_before' ); ?>
	<table class="form-table">
    <?php do_action( 'wpd_ecommerce_verification_table_top' ); ?>
    <tr>
        <th scope="row"><?php _e( 'Drivers license or Valid ID', 'wpd-ecommerce' ); ?></th>
        <td class="wpd-details-valid-id">
            <?php if ( get_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_valid_id', true ) ) { ?>
            <div class="valid-id">
            <?php //var_dump( $valid_id );
                if ( ! isset( $valid_id['error'] ) ) {
                    if ( ! empty( $valid_id ) ) {
                        $valid_id = $valid_id['url'];
                        echo "<a href='" . $valid_id . "' target='_blank'><img src='" . $valid_id . "' width='100' height='100' class='wpd-details-recommendation-doc' /></a><br />";
                    }
                } else {
                    $valid_id = $valid_id['error'];
                    echo $valid_id. '<br />';
                }
            ?>
			<input type="submit" class="remove-valid-id" name="remove_valid_id" value="<?php _e( 'x', 'wpd-ecommerce' ); ?>" />
            </div><!-- /.valid-id -->
            <?php } ?>
            <input type="file" name="wpd_ecommerce_valid_id" value="" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Doctor recommendation', 'wpd-ecommerce' ); ?></th>
        <td class="wpd-details-recommendation-doc">
            <?php if ( get_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_doc', true ) ) { ?>
            <div class="recommendation-doc">
            <?php //var_dump( $doc_rec );
                if ( ! isset( $doc_rec['error'] ) ) {
                    if ( ! empty( $doc_rec ) ) {
                        $doc_rec = $doc_rec['url'];
                        echo "<a href='" . $doc_rec . "' target='_blank'><img src='" . $doc_rec . "' width='100' height='100' class='wpd-details-recommendation-doc' /></a><br />";
                    }
                } else {
                    $doc_rec = $doc_rec['error'];
                    echo $doc_rec. '<br />';
                }
            ?>
			<input type="submit" class="remove-recommendation-doc" name="remove_recommendation_doc" value="<?php _e( 'x', 'wpd-ecommerce' ); ?>" />
            </div><!-- /.recommendation-doc -->
            <?php } ?>
            <input type="file" name="wpd_ecommerce_recommendation_doc" value="" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Recommendation number', 'wpd-ecommerce' ); ?></th>
        <td>
            <input class="regular-text" type="text" name="wpd_ecommerce_patient_recommendation_num" value="<?php echo get_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_num', true ); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Expiration date', 'wpd-ecommerce' ); ?></th>
        <td>
            <input class="regular-text" type="date" name="wpd_ecommerce_patient_recommendation_exp" value="<?php echo get_user_meta( $profileuser->ID, 'wpd_ecommerce_patient_recommendation_exp', true ); ?>" />
        </td>
    </tr>
    <?php do_action( 'wpd_ecommerce_verification_table_bottom' ); ?>
    </table>
    <?php do_action( 'wpd_ecommerce_verification_table_after' ); ?>
    <?php
}
add_action( 'show_user_profile', 'wpd_ecommerce_add_profile_options' );
add_action( 'edit_user_profile', 'wpd_ecommerce_add_profile_options' );

/**
 * Save File upload in user profile.
 * 
 * @since 1.1
 */
function wpd_ecommerce_patient_save_custom_profile_fields( $user_id ) {

    // Update recommendation number.
    if ( isset( $_POST['wpd_ecommerce_patient_recommendation_num'] ) ) {
        update_user_meta( $user_id, 'wpd_ecommerce_patient_recommendation_num', $_POST['wpd_ecommerce_patient_recommendation_num'] );
    }
    // Update recommendation expiration date.
    if ( isset( $_POST['wpd_ecommerce_patient_recommendation_exp'] ) ) {
        update_user_meta( $user_id, 'wpd_ecommerce_patient_recommendation_exp', sanitize_text_field( $_POST['wpd_ecommerce_patient_recommendation_exp'] ) );
    }

    // If no new files are uploaded, return.
    if ( ! isset( $_FILES ) || empty( $_FILES ) || ! isset( $_FILES['wpd_ecommerce_patient_recommendation_doc'] ) &&  ! isset( $_FILES['wpd_ecommerce_patient_valid_id'] ) )
        return;

    // Include file for wp_handle_upload.
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    // Include file for wp_generate_attachment_metadata.
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
    }

    // Handle the upload.
    $_POST['action'] = 'wp_handle_upload';

    // Get doctor recommendation file upload (if any).
    $doc_rec = wp_handle_upload( $_FILES['wpd_ecommerce_patient_recommendation_doc'], array( 'test_form' => false, 'mimes' => array( 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png', 'jpeg' => 'image/jpeg' ) ) );

    // Take doctor recommendation upload, add to media library.
    if ( isset( $doc_rec['file'] ) ) {
        // Update doctor recommendation meta.
        update_user_meta( $user_id, 'wpd_ecommerce_patient_recommendation_doc', $doc_rec, get_user_meta( $user_id, 'wpd_ecommerce_patient_recommendation_doc', true ) );

        $filename   = $doc_rec['file'];
        $title      = explode( '.', basename( $filename ) );
        $ext        = array_pop( $title );
        $attachment = array(
           'guid'           => $doc_rec['url'], 
           'post_mime_type' => $doc_rec['type'],
           'post_title'     => implode( '.', $title ),
           'post_content'   => '',
           'post_status'    => 'inherit'
        );
        $attach_id   = wp_insert_attachment( $attachment, $filename );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

        wp_update_attachment_metadata( $attach_id, $attach_data );
    }

    // Get valid ID file upload (if any).
    $valid_id = wp_handle_upload( $_FILES['wpd_ecommerce_patient_valid_id'], array( 'test_form' => false, 'mimes' => array( 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png', 'jpeg' => 'image/jpeg' ) ) );

    // Take doctor recommendation upload, add to media library.
    if ( isset( $valid_id['file'] ) ) {
        // Update doctor recommendation meta.
        update_user_meta( $user_id, 'wpd_ecommerce_patient_valid_id', $valid_id, get_user_meta( $user_id, 'wpd_ecommerce_patient_valid_id', true ) );

        $filename   = $valid_id['file'];
        $title      = explode( '.', basename( $filename ) );
        $ext        = array_pop( $title );
        $attachment = array(
           'guid'           => $valid_id['url'], 
           'post_mime_type' => $valid_id['type'],
           'post_title'     => implode( '.', $title ),
           'post_content'   => '',
           'post_status'    => 'inherit'
        );
        $attach_id   = wp_insert_attachment( $attachment, $filename );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

        wp_update_attachment_metadata( $attach_id, $attach_data );
    }

}
add_action( 'edit_user_profile_update', 'wpd_ecommerce_patient_save_custom_profile_fields' );
add_action( 'wpd_ecommerce_patient_account_verification_before', 'wpd_ecommerce_patient_save_custom_profile_fields' );

/**
 * Redirect patients on successful registration.
 * 
 * @since 1.5
 */
function wpd_ecommerce_patients_registration_redirect( $registration_redirect ) {
    if ( TRUE == wpd_settings_patients_registration_redirect() ) {
        // Return new redirect URL.
        return wpd_settings_patients_registration_redirect();
    } else {
        // Do nothing.
    }
}
add_filter( 'registration_redirect', 'wpd_ecommerce_patients_registration_redirect' );
