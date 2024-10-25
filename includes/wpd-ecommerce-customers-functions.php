<?php
/**
 * WP Dispensary eCommerce customer helper functions
 *
 * @package    WPD_eCommerce
 * @subpackage WPD_eCommerce/includes
 * @author     CannaBiz Software <contact@cannabizsoftware.com>
 * @license    GPL-2.0+ 
 * @link       https://cannabizsoftware.com
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Disable admin bar for customers.
 *
 * @since  1.0.0
 * @return void
 */
function wpd_ecommerce_disable_admin_bar() {

    // Only run for logged in users.
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        $role = ( array ) $user->roles;

        // Show admin bar.
        show_admin_bar( true );

        // Hide admin bar for customers.
        if ( 'customer' === $role[0] ) {
            show_admin_bar( false );
        }
    }
}
add_action( 'after_setup_theme', 'wpd_ecommerce_disable_admin_bar' );
 
/**
 * Disable wp-admin for customers.
 *
 * @since  1.0.0
 * @return void
 */
function wpd_ecommerce_disable_admin_dashboard() {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    // Redirect customers from any wp-admin page to account page.
    if ( ! empty( $role ) && is_admin() && 'customer' === $role[0] && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_safe_redirect( wpd_ecommerce_account_url() );
        exit;
    } elseif ( is_user_logged_in() ) {
        // Do nothing for any other logged in user.
    } else {
        // Redirect admin for non-logged in users.
        if ( ! is_user_logged_in() && is_admin() ) {
            ob_start();
            wp_safe_redirect( wpd_ecommerce_account_url() );
            ob_end_clean();
            //exit;
        }
    }

}
add_action( 'init', 'wpd_ecommerce_disable_admin_dashboard' );

/**
 * Prevent wp-login.php for customers
 * 
 * @since  1.0.0
 * @return void
 */
function wpd_ecommerce_prevent_wp_login() {

    global $pagenow;

    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    /**
     * This locks down wp-login.php for customers only
     */
    if ( ! empty( $role ) && 'customer' === $role[0] ) {

        // Check if a $_GET['action'] is set, and if so, load it into $action variable
        $action = ( null !== filter_input( INPUT_GET, 'action' ) ) ? filter_input( INPUT_GET, 'action' ) : '';

        // Check if we're on the login page, and ensure the action is not 'logout'
        if ( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array( $action, array( 'logout', 'lostpassword', 'rp', 'resetpass' ) ) ) ) ) {
            wp_safe_redirect( wpd_ecommerce_account_url() );
            exit;
        }
    }

}
add_action( 'init', 'wpd_ecommerce_prevent_wp_login' );

/**
 * Customer dashboard - update user data
 * 
 * @since  1.0.0
 * @return void
 */
function wpd_ecommerce_update_user_customer_dashboard() {
    global $current_user;

    // Create array.
    $error = array();

    // If account details were saved, update customer account.
    if ( 'POST' == filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) && ! empty( filter_input( INPUT_POST, 'action' ) ) && filter_input( INPUT_POST, 'action' ) == 'update-user' ) {

        // Remove valid ID file from user profile.
        if ( null !== filter_input( INPUT_POST, 'remove_valid_id' ) ) {
            // Update user meta.
            update_user_meta( $current_user->ID, 'wpd_ecommerce_customer_valid_id', '' );
        }
    
        // Remove doctor recommendation file from user profile.
        if ( null !== filter_input( INPUT_POST, 'remove_recommendation_doc' ) ) {
            // Update user meta.
            update_user_meta( $current_user->ID, 'wpd_ecommerce_customer_recommendation_doc', '' );
        }
    
        // Update user password.
        if ( ! empty( filter_input( INPUT_POST, 'pass1' ) ) && ! empty( filter_input( INPUT_POST, 'pass2' ) ) ) {
            if ( filter_input( INPUT_POST, 'pass1' ) == filter_input( INPUT_POST, 'pass2' ) ) {
                wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => sanitize_text_field( filter_input( INPUT_POST, 'pass1' ) ) ) );
            } else {
                $error[] = esc_attr__( 'The passwords you entered do not match.  Your password was not updated.', 'cannabiz-menu' );
            }
        }

        // Update user email address.
        if ( null !== filter_input( INPUT_POST, 'email' ) ) {
            if ( ! is_email( sanitize_text_field( filter_input( INPUT_POST, 'email' ) ) ) ) {
                $error[] = esc_attr__( 'The Email you entered is not valid. Please try again.', 'cannabiz-menu' );
            } elseif( email_exists( sanitize_text_field( filter_input( INPUT_POST, 'email' ) ) ) != $current_user->id ) {
                $error[] = esc_attr__( 'This email is already used by another user. Try a different one.', 'cannabiz-menu' );
            } else {
                wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => sanitize_text_field( filter_input( INPUT_POST, 'email' ) ) ) );
            }
        }

        // Update user information.
        if ( null !== filter_input( INPUT_POST, 'first-name' ) ) {
            update_user_meta( $current_user->ID, 'first_name', sanitize_text_field( filter_input( INPUT_POST, 'first-name' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'last-name' ) ) {
            update_user_meta($current_user->ID, 'last_name', sanitize_text_field( filter_input( INPUT_POST, 'last-name' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'phone_number' ) ) {
            update_user_meta( $current_user->ID, 'phone_number', sanitize_text_field( filter_input( INPUT_POST, 'phone_number' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'address_line_1' ) ) {
            update_user_meta( $current_user->ID, 'address_line_1', sanitize_text_field( filter_input( INPUT_POST, 'address_line_1' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'address_line_2' ) ) {
            update_user_meta( $current_user->ID, 'address_line_2', sanitize_text_field( filter_input( INPUT_POST, 'address_line_2' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'city' ) ) {
            update_user_meta( $current_user->ID, 'city', sanitize_text_field( filter_input( INPUT_POST, 'city' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'state_country' ) ) {
            update_user_meta( $current_user->ID, 'state_county', sanitize_text_field( filter_input( INPUT_POST, 'state_county' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'postal_zip' ) ) {
            update_user_meta( $current_user->ID, 'postal_zip', sanitize_text_field( filter_input( INPUT_POST, 'postal_zip' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'country' ) ) {
            update_user_meta( $current_user->ID, 'country', sanitize_text_field( filter_input( INPUT_POST, 'country' ) ) );
        }
        if ( null !== filter_input( INPUT_POST, 'description' ) ) {
            update_user_meta( $current_user->ID, 'description', sanitize_text_field( filter_input( INPUT_POST, 'description' ) ) );
        }

        /**
         * Redirect so the page will show updated info.
         */
        if ( 0 == count( $error ) ) {
            // Action hook for plugins and extra fields saving
            do_action( 'edit_user_profile_update', $current_user->ID );
            wp_safe_redirect( wpd_ecommerce_account_url() . '?profile_saved' );
            exit;
        }
    }
}
add_action( 'init', 'wpd_ecommerce_update_user_customer_dashboard' );

/**
 * Login failed redirect
 * 
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_login_failed() {
    $login_page = wpd_ecommerce_account_url();
    $ref_link   = filter_input( INPUT_SERVER, 'HTTP_REFERER' );

    // Redirect logins from account page.
    if ( $login_page === $ref_link ) {
        wp_safe_redirect( $login_page . '?login=failed' );
        exit;
    }
}
add_action( 'wp_login_failed', 'wpd_ecommerce_login_failed' );

/**
 * Login success redirect
 * 
 * @param string           $redirect_to 
 * @param string           $request      
 * @param WP_User|WP_Error $user        
 * 
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_login_redirect( $redirect_to, $request, $user ) {
    $user         = wp_get_current_user();
    $role         = ( array ) $user->roles;
    $login_page   = wpd_ecommerce_account_url();
    $ref_link     = filter_input( INPUT_SERVER, 'HTTP_REFERER' );

    if ( $role ) {
        // If user is customer.
        if ( 'customer' === $role[0] ) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  $login_page;
        }
    }

    // Redirect logins from account page.
    if ( $ref_link === $login_page . '?login=failed' ) {
        $redirect_to =  $login_page;
    }

    return $redirect_to;
}
add_filter( 'login_redirect', 'wpd_ecommerce_login_redirect', 10, 3 );

/**
 * Disable author archives for customers.
 *
 * @since  1.0
 * @return void
 */
function wpd_ecommerce_disable_author_archives_for_customers() {
    global $author;
    if ( is_author() ) {
        $user = get_user_by( 'id', $author );
        $role = ( array ) $user->roles;

        if ( 'customer' === $role[0] ) {
            wp_safe_redirect( wpd_ecommerce_menu_url() );
        }
    }
}
add_action( 'template_redirect', 'wpd_ecommerce_disable_author_archives_for_customers' );

/**
 * Add profile options to Edit User screen
 * 
 * @param int $profileuser 
 * 
 * @since  1.1
 * @return void
 */
function wpd_ecommerce_add_profile_options( $profileuser ) {
    // Update recommendation number.
    if ( null !== filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_num' ) ) {
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_num', filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_num' ) );
    }
    // Update recommendation expiration date.
    if ( null !== filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_exp' ) ) {
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_exp', sanitize_text_field( filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_exp' ) ) );
    }
    // Remove valid ID file from user profile.
    if ( null !== filter_input( INPUT_POST, 'remove_valid_id' ) ) {
        // Update user meta.
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_valid_id', '' );
    }
    // Remove doctor recommendation file from user profile.
    if ( null !== filter_input( INPUT_POST, 'remove_recommendation_doc' ) ) {
        // Update user meta.
        update_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_doc', '' ); 
    }

    $doc_rec  = get_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_doc', true );
    $valid_id = get_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_valid_id', true );
    ?>
    <?php do_action( 'wpd_ecommerce_verification_title_before' ); ?>
    <h2><?php esc_html_e( 'Verification', 'cannabiz-menu' ); ?></h2>
    <?php do_action( 'wpd_ecommerce_verification_table_before' ); ?>
    <table class="form-table">
    <?php do_action( 'wpd_ecommerce_verification_table_top' ); ?>
    <tr>
        <th scope="row"><?php esc_html_e( 'Drivers license or Valid ID', 'cannabiz-menu' ); ?></th>
        <td class="wpd-details-valid-id">
            <?php if ( get_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_valid_id', true ) ) { ?>
            <div class="valid-id">
                <?php //var_dump( $valid_id );
                if ( ! isset( $valid_id['error'] ) ) {
                    if ( ! empty( $valid_id ) ) {
                        $valid_id = $valid_id['url'];
                        echo '<a href="' . $valid_id . '" target="_blank"><img src="' . $valid_id . '" width="100" height="100" class="wpd-details-recommendation-doc" /></a><br />';
                    }
                } else {
                    $valid_id = $valid_id['error'];
                    echo $valid_id. '<br />';
                }
                ?>
                <input type="submit" class="remove-valid-id" name="remove_valid_id" value="<?php esc_html_e( 'x', 'cannabiz-menu' ); ?>" />
            </div><!-- /.valid-id -->
            <?php } ?>
            <input type="file" name="wpd_ecommerce_valid_id" value="" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e( 'Doctor recommendation', 'cannabiz-menu' ); ?></th>
        <td class="wpd-details-recommendation-doc">
            <?php if ( get_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_doc', true ) ) { ?>
            <div class="recommendation-doc">
                <?php //var_dump( $doc_rec );
                if ( ! isset( $doc_rec['error'] ) ) {
                    if ( ! empty( $doc_rec ) ) {
                        $doc_rec = $doc_rec['url'];
                        echo '<a href="' . $doc_rec . '" target="_blank"><img src="' . $doc_rec . '" width="100" height="100" class="wpd-details-recommendation-doc" /></a><br />';
                    }
                } else {
                    $doc_rec = $doc_rec['error'];
                    echo $doc_rec. '<br />';
                }
                ?>
            <input type="submit" class="remove-recommendation-doc" name="remove_recommendation_doc" value="<?php esc_html_e( 'x', 'cannabiz-menu' ); ?>" />
            </div><!-- /.recommendation-doc -->
            <?php } ?>
            <input type="file" name="wpd_ecommerce_recommendation_doc" value="" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e( 'Recommendation number', 'cannabiz-menu' ); ?></th>
        <td>
            <input class="regular-text" type="text" name="wpd_ecommerce_customer_recommendation_num" value="<?php echo get_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_num', true ); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e( 'Expiration date', 'cannabiz-menu' ); ?></th>
        <td>
            <input class="regular-text" type="date" name="wpd_ecommerce_customer_recommendation_exp" value="<?php echo get_user_meta( $profileuser->ID, 'wpd_ecommerce_customer_recommendation_exp', true ); ?>" />
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
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int    $length   - How many characters do we want?
 * @param string $keyspace - A string of all possible characters to select from
 * 
 * @return string
 */
function wpd_ecommerce_random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ( $length < 1 ) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max    = mb_strlen( $keyspace, '8bit' ) - 1;

    for ( $i = 0; $i < $length; ++$i ) {
        $pieces []= $keyspace[random_int(0, $max)];
    }

    return implode( '', $pieces );
}

/**
 * Customize customer file upload names
 * 
 * This function creates a random string that will be used
 * by wp_handle_upload to create a unique filename for the 
 * uploaded file
 * 
 * @since  2.2.0
 * @return string
 */
function wpd_ecommerce_customer_upload_customize_file_name() {
    // Create random string.
    return wpd_ecommerce_random_str();
}

/**
 * Save File upload in user profile.
 * 
 * @param int $user_id 
 * 
 * @since  1.1
 * @return void
 */
function wpd_ecommerce_customer_save_custom_profile_fields( $user_id ) {

    // Update recommendation number.
    if ( null !== filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_num' ) ) {
        update_user_meta( $user_id, 'wpd_ecommerce_customer_recommendation_num', filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_num' ) );
    }
    // Update recommendation expiration date.
    if ( null !== filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_exp' ) ) {
        update_user_meta( $user_id, 'wpd_ecommerce_customer_recommendation_exp', sanitize_text_field( filter_input( INPUT_POST, 'wpd_ecommerce_customer_recommendation_exp' ) ) );
    }

    // If no new files are uploaded, return.
    if ( ! isset( $_FILES ) || empty( $_FILES ) || ! isset( $_FILES['wpd_ecommerce_customer_recommendation_doc'] ) &&  ! isset( $_FILES['wpd_ecommerce_customer_valid_id'] ) ) {
        return;
    }

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

    // Register our upload path override.
    add_filter( 'upload_dir', 'wpd_ecommerce_custom_upload_dir' );

    // Get doctor recommendation file upload (if any).
    $doc_rec = wp_handle_upload( $_FILES['wpd_ecommerce_customer_recommendation_doc'], array( 'test_form' => false, 'unique_filename_callback' => 'wpd_ecommerce_customer_upload_customize_file_name', 'mimes' => array( 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png', 'jpeg' => 'image/jpeg' ) ) );

    // Deregister our upload path override.
    remove_filter( 'upload_dir', 'wpd_ecommerce_custom_upload_dir' );

    // Take doctor recommendation upload, add to media library.
    if ( isset( $doc_rec['file'] ) ) {
        // Update doctor recommendation meta.
        update_user_meta( $user_id, 'wpd_ecommerce_customer_recommendation_doc', $doc_rec, get_user_meta( $user_id, 'wpd_ecommerce_customer_recommendation_doc', true ) );

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

    // Register our upload path override.
    add_filter( 'upload_dir', 'wpd_ecommerce_custom_upload_dir' );

    // Get valid ID file upload (if any).
    $valid_id = wp_handle_upload( $_FILES['wpd_ecommerce_customer_valid_id'], array( 'test_form' => false, 'unique_filename_callback' => 'wpd_ecommerce_customer_upload_customize_file_name', 'mimes' => array( 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png', 'jpeg' => 'image/jpeg' ) ) );

    // Deregister our upload path override.
    remove_filter( 'upload_dir', 'wpd_ecommerce_custom_upload_dir' );

    // Take doctor recommendation upload, add to media library.
    if ( isset( $valid_id['file'] ) ) {
        // Update doctor recommendation meta.
        update_user_meta( $user_id, 'wpd_ecommerce_customer_valid_id', $valid_id, get_user_meta( $user_id, 'wpd_ecommerce_customer_valid_id', true ) );

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
add_action( 'edit_user_profile_update', 'wpd_ecommerce_customer_save_custom_profile_fields' );
add_action( 'wpd_ecommerce_customer_account_verification_before', 'wpd_ecommerce_customer_save_custom_profile_fields' );

/**
 * Redirect customers on successful registration.
 * 
 * @param [type] $registration_redirect 
 * 
 * @since  1.5
 * @return void
 */
function wpd_ecommerce_customers_registration_redirect( $registration_redirect ) {
    if ( true == wpd_settings_customers_registration_redirect() ) {
        // Return new redirect URL.
        return wpd_settings_customers_registration_redirect();
    }
}
add_filter( 'registration_redirect', 'wpd_ecommerce_customers_registration_redirect' );

/**
 * Login form
 * 
 * @since  2.0
 * @return string
 */
function wpd_ecommerce_login_form() {

    $args['form_id'] = 'wpd-ecommerce-login';

    do_action( 'wpd_ecommerce_customer_account_login_form_before' );
    echo '<div class="wpd-ecommerce-login-form">';
    do_action( 'wpd_ecommerce_customer_account_login_form_before_inside' );
    echo '<h3>' . esc_attr__( 'Login', 'cannabiz-menu' ) . '</h3>';
    echo wp_login_form( $args );
    do_action( 'wpd_ecommerce_customer_account_login_form_after_inside' );
    echo '</div>';
    do_action( 'wpd_ecommerce_customer_account_login_form_after' );

}

/**
 * Register form
 * 
 * @since  2.0
 * @return string
 */
function wpd_ecommerce_register_form() {
    do_action( 'wpd_ecommerce_customer_account_register_form_before' );

    echo '<div class="wpd-ecommerce-register-form">';
    do_action( 'wpd_ecommerce_customer_account_register_form_before_inside' );
    echo '<h3>' . esc_attr__( 'Register', 'cannabiz-menu' ) . '</h3>';
    ?>
    <form id="wpd-ecommerce-register" action="<?php echo site_url( 'wp-login.php?action=register', 'login_post' ) ?>" method="post">
    <?php do_action( 'wpd_ecommerce_customer_account_register_form_inside_top' ); ?>
    <p class="register-username">
    <label for="user_login"><?php esc_html_e( 'Username', 'cannabiz-menu' ); ?></label>
    <input type="text" name="user_login" value="" id="user_login" class="input" />
    </p>
    <?php do_action( 'wpd_ecommerce_customer_account_register_form_after_username' ); ?>
    <p class="register-email-address">
    <label for="user_email"><?php esc_html_e( 'Email address', 'cannabiz-menu' ); ?></label>
    <input type="text" name="user_email" value="" id="user_email" class="input" />
    </p>
    <?php do_action( 'wpd_ecommerce_customer_account_register_form_after_email' ); ?>
    <p class="statement"><?php esc_html_e( 'A password will be emailed to you.', 'cannabiz-menu' ); ?></p>
    <p class="register-submit">
    <input type="submit" value="<?php esc_html_e( 'Register', 'cannabiz-menu' ); ?>" id="register" />
    </p>
    <?php do_action( 'wpd_ecommerce_customer_account_register_form_inside_bottom' ); ?>
    </form>
    <?php
    do_action( 'wpd_ecommerce_customer_account_register_form_after_inside' );
    echo '</div>';

    do_action( 'wpd_ecommerce_customer_account_register_form_after' );
}
