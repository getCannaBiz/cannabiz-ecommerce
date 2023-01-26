<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com
 * @since      1.0.0
 *
 * @package    WPD_Styles
 * @subpackage WPD_Styles/admin
 */

function wpd_styles_register_theme_customizer( $wp_customize ) {

	/*-----------------------------------------------------------*
	 * Color options
	 *-----------------------------------------------------------*/

	/* Shortcode - Title */
	$wp_customize->add_setting(
		'wpd_styles_shortcode_title',
		array(
			'default'     		  => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'   		  => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'shortcode_title',
			array(
				'label'    => 'WPD: Shortcode Title',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_shortcode_title',
				'priority' => 10
			)
		)
	);

	/* Shortcode - Item - Title */
	$wp_customize->add_setting(
		'wpd_styles_shortcode_item_title',
		array(
			'default'     		  => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'   		  => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'shortcode_item_title',
			array(
			  'label'    => 'WPD: Shortcode Item Title',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_shortcode_item_title',
				'priority' => 10
			)
		)
	);

	/* Shortcode - Item - Title (hover) */
	$wp_customize->add_setting(
		'wpd_styles_shortcode_item_title_hover',
		array(
			'default'     		  => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'   		  => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'shortcode_item_title_hover',
			array(
			  'label'    => 'WPD: Shortcode Item Title (hover)',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_shortcode_item_title_hover',
				'priority' => 10
			)
		)
	);

	/* Shortcode - Item - Info */
	$wp_customize->add_setting(
		'wpd_styles_shortcode_item_info',
		array(
			'default'     		  => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'   		  => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'shortcode_item_info',
			array(
			  'label'    => 'WPD: Shortcode Item Info',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_shortcode_item_info',
				'priority' => 10
			)
		)
	);

	/* Data Output - Table - Background */
	$wp_customize->add_setting(
		'wpd_styles_table_background',
		array(
			'default'     		  => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'   		  => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'table_background',
			array(
			  'label'    => 'WPD: Table Background',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_table_background',
				'priority' => 10
			)
		)
	);

	/* Data Output - Table - Text */
	$wp_customize->add_setting(
		'wpd_styles_table_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'         => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'table_text',
			array(
			  'label'    => 'WPD: Table Text',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_table_text',
				'priority' => 10
			)
		)
	);

	/* Data Output - Table - Price */
	$wp_customize->add_setting(
		'wpd_styles_table_price',
		array(
			'default'           => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'         => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'table_price',
			array(
			  'label'    => 'WPD: Table Price',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_table_price',
				'priority' => 10
			)
		)
	);

	/* Data Output - Table - Border */
	$wp_customize->add_setting(
		'wpd_styles_table_border',
		array(
			'default'           => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'         => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'table_border',
			array(
			  'label'    => 'WPD: Table Border',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_table_border',
				'priority' => 10
			)
		)
	);

	/* Data Output - Table - Title Background */
	$wp_customize->add_setting(
		'wpd_styles_table_title_background',
		array(
			'default'           => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'         => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'table_title_background',
			array(
			  'label'    => 'WPD: Table Title Background',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_table_title_background',
				'priority' => 10
			)
		)
	);

	/* Data Output - Table - Title Text */
	$wp_customize->add_setting(
		'wpd_styles_table_title_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wpd_styles_sanitize_input',
			'transport'         => 'refresh'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'table_title_text',
			array(
			  'label'    => 'WPD: Table Title Text',
			  'section'  => 'colors',
			  'settings' => 'wpd_styles_table_title_text',
				'priority' => 10
			)
		)
	);

} // end wpd_styles_register_theme_customizer
add_action( 'customize_register', 'wpd_styles_register_theme_customizer' );

/**
 * Sanitizes the incoming input and returns it prior to serialization.
 *
 * @param      string    $input    The string to sanitize
 * @return     string              The sanitized string
 * @package    wpd_styles
 * @since      1.0.0
 * @version    1.0.0
 */
function wpd_styles_sanitize_input( $input ) {
	return strip_tags( stripslashes( $input ) );
} // end wpd_styles_sanitize_input

function wpd_styles_sanitize_text( $input ) {
	$allowed = array(
		's'      => array(),
		'br'     => array(),
		'em'     => array(),
		'i'      => array(),
		'strong' => array(),
		'b'      => array(),
		'a'      => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
		'form'   => array(
			'id'           => array(),
			'class'        => array(),
			'action'       => array(),
			'method'       => array(),
			'autocomplete' => array(),
			'style'        => array(),
		),
		'input'  => array(
			'type'        => array(),
			'name'        => array(),
			'class'       => array(),
			'id'          => array(),
			'value'       => array(),
			'placeholder' => array(),
			'tabindex'    => array(),
			'style'       => array(),
		),
		'img'    => array(
			'src'    => array(),
			'alt'    => array(),
			'class'  => array(),
			'id'     => array(),
			'style'  => array(),
			'height' => array(),
			'width'  => array(),
		),
		'span'   => array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
		'p'      => array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
		'div'    => array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
		'blockquote' => array(
			'cite'  => array(),
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
	);
    return wp_kses( $input, $allowed );
} // end wpd_styles_sanitize_text

/**
 * Writes styles out the `<head>` element of the page based on the configuration options
 * saved in the Theme Customizer.
 *
 * @since      1.0.0
 * @version    1.0.0
 */
function wpd_styles_customizer_css() { ?>
	<style type="text/css">

	<?php if ( '' != get_theme_mod( 'wpd_styles_shortcode_title' ) ) { ?>
		h2.wpd-title {
			color: <?php echo get_theme_mod( 'wpd_styles_shortcode_title' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_shortcode_item_title' ) ) { ?>
		p.wpd-producttitle a,
		p.wpd-producttitle a:visited {
			color: <?php echo get_theme_mod( 'wpd_styles_shortcode_item_title' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_shortcode_item_title_hover' ) ) { ?>
		p.wpd-producttitle a:hover {
			color: <?php echo get_theme_mod( 'wpd_styles_shortcode_item_title_hover' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_shortcode_item_info' ) ) { ?>
		.wpdshortcode span.wpd-productinfo {
			color: <?php echo get_theme_mod( 'wpd_styles_shortcode_item_info' ); ?>;
		}
	<?php } ?>

	<?php if ( '' != get_theme_mod( 'wpd_styles_table_background' ) ) { ?>
		table.wpdispensary-table {
			background: <?php echo get_theme_mod( 'wpd_styles_table_background' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_table_border' ) ) { ?>
		table.wpdispensary-table td {
			border-color: <?php echo get_theme_mod( 'wpd_styles_table_border' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_table_text' ) ) { ?>
		table.wpdispensary-table td,
		table.wpdispensary-table td span {
			color: <?php echo get_theme_mod( 'wpd_styles_table_text' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_table_price' ) ) { ?>
		table.wpdispensary-table.pricing td {
			color: <?php echo get_theme_mod( 'wpd_styles_table_price' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_table_title_background' ) ) { ?>
		table.wpdispensary-table td.wpdispensary-title {
			background: <?php echo get_theme_mod( 'wpd_styles_table_title_background' ); ?>;
		}
	<?php } ?>
	<?php if ( '' != get_theme_mod( 'wpd_styles_table_title_text' ) ) { ?>
		table.wpdispensary-table td.wpdispensary-title,
		table.wpdispensary-table.pricing td.wpdispensary-title {
			color: <?php echo get_theme_mod( 'wpd_styles_table_title_text' ); ?>;
		}
	<?php } ?>

	</style>
<?php
} // end wpd_styles_customizer_css
add_action( 'wp_head', 'wpd_styles_customizer_css' );

/**
 * Registers the Theme Customizer Preview with WordPress.
 *
 * @package    wpd_styles
 * @since      1.0.0
 * @version    1.0.0
 */
function wpd_styles_customizer_live_preview() {
	wp_enqueue_script(
		'wpd-styles-theme-customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ),
		'1.0.0',
		true
	);
} // end wpd_styles_customizer_live_preview
add_action( 'customize_preview_init', 'wpd_styles_customizer_live_preview' );
