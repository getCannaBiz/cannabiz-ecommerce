<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package WPD_eCommerce
 * @author  CannaBiz Software <contact@cannabizsoftware.com>
 * @license GPL-2.0+ 
 * @link    https://cannabizsoftware.com
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package WPD_eCommerce
 * @author  CannaBiz Software <contact@cannabizsoftware.com>
 * @license GPL-2.0+ 
 * @link    https://cannabizsoftware.com
 * @since   1.0.0
 */
class CSV_Customers_Export {
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct() {
        if ( null !== filter_input( INPUT_GET, 'export_customers' ) ) {

            // Run generate CSV function.
            $csv = $this->generate_csv();

            header( "Pragma: public" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Cache-Control: private", false );
            header( "Content-Type: application/octet-stream" );
            header( "Content-Disposition: attachment; filename=\"cannabiz-menu-customers.csv\";" );
            header( "Content-Transfer-Encoding: binary" );

            echo $csv;
            exit;
        }

        // Create end-points.
        add_filter( 'query_vars', array( $this, 'query_vars' ) );
        add_action( 'parse_request', array( $this, 'parse_request' ) );
    }

    /**
     * Allow for custom query variables
     * 
     * @param array $query_vars 
     * 
     * @return array
     */
    public function query_vars( $query_vars ) {
        $query_vars[] = 'export_customers';
        return $query_vars;
    }

    /**
     * Parse the request
     * 
     * @param object $wp 
     * 
     * @return void
     */
    public function parse_request( &$wp ) {
        if ( array_key_exists( 'export_customers', $wp->query_vars ) ) {
            $this->export_customers();
            exit;
        }
    }

    /**
     * Export CannaBiz eCommerce customers
     * 
     * @return string
     */
    public function export_customers() {
        echo '<div class="wrap">';
        echo '<h2>' . esc_attr__( 'CannaBiz eCommerce\'s Customer Export', 'cannabiz-menu' ) . '</h2>';
        echo '<p>' . esc_attr__( 'Export your CannaBiz eCommerce customers as a CSV file by clicking the button below.', 'cannabiz-menu' ) . '</p>';
        echo '<p><a class="button" href="admin.php?page=export_customers&export_customers&_wpnonce=' . wp_create_nonce( 'download_csv' ) . '">' . esc_attr__( 'Export', 'cannabiz-menu' ) . '</a></p>';
    }

    /**
     * Converting data to CSV
     * 
     * @return string
     */
    public function generate_csv() {
        ob_start();

        $domain = 'domain';

        if ( null !== filter_input( INPUT_SERVER, 'SERVER_NAME' ) ) {
            $domain = filter_input( INPUT_SERVER, 'SERVER_NAME' );
        }
        // Create file name.
        $file_name = 'wpd-customers-' . $domain . '-' . time() . '.csv';

        // Set the headers.
        $header_row = array(
            esc_attr__( 'ID', 'cannabiz-menu' ),
            esc_attr__( 'Name', 'cannabiz-menu' ),
            esc_attr__( 'Email', 'cannabiz-menu' ),
            esc_attr__( 'Phone', 'cannabiz-menu' ),
            esc_attr__( 'Address', 'cannabiz-menu' ),
            esc_attr__( 'Recommendation number', 'cannabiz-menu' ),
            esc_attr__( 'Recommendation expiration', 'cannabiz-menu' ),
            esc_attr__( 'Total orders', 'cannabiz-menu' ),
        );

        // Filter headers.
        $header_row = apply_filters( 'wpd_csv_customers_export_header_row', $header_row );

        // Data rows.
        $data_rows = array();

        $args = array(
            'role'    => 'customer',
            'orderby' => 'user_nicename',
            'order'   => 'ASC'
        );

        $customers = get_users( $args );

        // Set the rows (matches headers).
        foreach ( $customers as $customer ) {

            // Create row.
            $row = array(
                $customer->ID,
                $customer->display_name,
                $customer->user_email,
                get_the_author_meta( 'phone_number', $customer->ID ),
                get_the_author_meta( 'address_line_1', $customer->ID ) . ' ' . get_the_author_meta( 'address_line_2', $customer->ID ) . ', ' . get_the_author_meta( 'city', $customer->ID ) . ' ' . get_the_author_meta( 'state_county', $customer->ID ) . ', ' . get_the_author_meta( 'postcode_zip', $customer->ID ) . ' ' . get_the_author_meta( 'country', $customer->ID ),
                get_the_author_meta( 'wpd_ecommerce_customer_recommendation_num', $customer->ID ),
                get_the_author_meta( 'wpd_ecommerce_customer_recommendation_exp', $customer->ID ),
                wpd_ecommerce_customer_total_order_count( $customer->ID )
            );
            $data_rows[] = apply_filters( 'wpd_csv_customers_export_data_row', $row, $customers );
        }

        $fh = @fopen( 'php://output', 'w' );

        fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );

        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: text/csv' );
        header( "Content-Disposition: attachment; filename={$file_name}" );
        header( 'Expires: 0' );
        header( 'Pragma: public' );

        fputcsv( $fh, $header_row );

        foreach ( $data_rows as $data_row ) {
            fputcsv( $fh, $data_row );
        }

        fclose( $fh );

        ob_end_flush();

        die();
    }
}

/**
 * Initialize the CSV Customers Export class
 * 
 * @since  2.0
 * @return void
 */
function wpd_ecommerce_csv_customers_export() {
    // Instantiate a singleton of this plugin.
    $csvExport = new CSV_Customers_Export();
}
add_action( 'init', 'wpd_ecommerce_csv_customers_export' );
