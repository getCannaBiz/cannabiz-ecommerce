<?php
/**
 * Main Class file for `WPD_INVENTORY_SETTINGS`
 *
 * Main class that deals with all other classes.
 *
 * @since   1.0.0
 * @package WPDINVENTORY
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPD_INVENTORY_SETTINGS.
 *
 * WP Settings API Class.
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'WPD_INVENTORY_SETTINGS' ) ) :

	class WPD_INVENTORY_SETTINGS {

		/**
		 * Sections array.
		 *
		 * @var   array
		 * @since 1.0.0
		 */
		private $sections_array = array();

		/**
		 * Fields array.
		 *
		 * @var   array
		 * @since 1.0.0
		 */
		private $fields_array = array();

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			// Enqueue the admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// Hook it up.
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Menu.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		}

		/**
		 * Admin Scripts.
		 *
		 * @since 1.0.0
		 */
		public function admin_scripts() {
			// jQuery is needed.
			wp_enqueue_script( 'jquery' );

			// Color Picker.
			wp_enqueue_script(
				'iris',
				admin_url( 'js/iris.min.js' ),
				array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
				false,
				1
			);

			// Media Uploader.
			wp_enqueue_media();

		}


		/**
		 * Set Sections.
		 *
		 * @param array $sections
		 * @since 1.0.0
		 */
		public function set_sections( $sections ) {
			// Bail if not array.
			if ( ! is_array( $sections ) ) {
				return false;
			}

			// Assign to the sections array.
			$this->sections_array = $sections;

			return $this;
		}


		/**
		 * Add a single section.
		 *
		 * @param array $section
		 * @since 1.0.0
		 */
		public function add_section( $section ) {
			// Bail if not array.
			if ( ! is_array( $section ) ) {
				return false;
			}

			// Assign the section to sections array.
			$this->sections_array[] = $section;

			return $this;
		}


		/**
		 * Set Fields.
		 *
		 * @since 1.0.0
		 */
		public function set_fields( $fields ) {
			// Bail if not array.
			if ( ! is_array( $fields ) ) {
				return false;
			}

			// Assign the fields.
			$this->fields_array = $fields;

			return $this;
		}



		/**
		 * Add a single field.
		 *
		 * @since 1.0.0
		 */
		public function add_field( $section, $field_array ) {
			// Set the defaults
			$defaults = array(
				'id'   => '',
				'name' => '',
				'desc' => '',
				'type' => 'text',
			);

			// Combine the defaults with user's arguements.
			$arg = wp_parse_args( $field_array, $defaults );

			// Each field is an array named against its section.
			$this->fields_array[ $section ][] = $arg;

			return $this;
		}



		/**
		 * Initialize API.
		 *
		 * Initializes and registers the settings sections and fields.
		 * Usually this should be called at `admin_init` hook.
		 *
		 * @since  1.0.0
		 */
		function admin_init() {
			/**
			 * Register the sections.
			 *
			 * Sections array is like this:
			 *
			 * $sections_array = array (
			 *   $section_array,
			 *   $section_array,
			 *   $section_array,
			 * );
			 *
			 * Section array is like this:
			 *
			 * $section_array = array (
			 *   'id'    => 'section_id',
			 *   'title' => 'Section Title'
			 * );
			 *
			 * @since 1.0.0
			 */
			foreach ( $this->sections_array as $section ) {
				if ( false == get_option( $section['id'] ) ) {
					// Add a new field as section ID.
					add_option( $section['id'] );
				}

				// Deals with sections description.
				if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
					// Build HTML.
					$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';

					// Create the callback for description.
					$callback = function() use ( $section ) {
						echo str_replace( '"', '\"', $section['desc'] );
					};

				} elseif ( isset( $section['callback'] ) ) {
					$callback = $section['callback'];
				} else {
					$callback = null;
				}

				/**
				 * Add a new section to a settings page.
				 *
				 * @param string $id
				 * @param string $title
				 * @param callable $callback
				 * @param string $page | Page is same as section ID.
				 * @since 1.0.0
				 */
				add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
			} // foreach ended.

			/**
			 * Register settings fields.
			 *
			 * Fields array is like this:
			 *
			 * $fields_array = array (
			 *   $section => $field_array,
			 *   $section => $field_array,
			 *   $section => $field_array,
			 * );
			 *
			 *
			 * Field array is like this:
			 *
			 * $field_array = array (
			 *   'id'   => 'id',
			 *   'name' => 'Name',
			 *   'type' => 'text',
			 * );
			 *
			 * @since 1.0.0
			 */
			foreach ( $this->fields_array as $section => $field_array ) {
				foreach ( $field_array as $field ) {
					// ID.
					$id = isset( $field['id'] ) ? $field['id'] : false;

					// Type.
					$type = isset( $field['type'] ) ? $field['type'] : 'text';

					// Name.
					$name = isset( $field['name'] ) ? $field['name'] : 'No Name Added';

					// Label for.
					$label_for = "{$section}[{$field['id']}]";

					// Description.
					$description = isset( $field['desc'] ) ? $field['desc'] : '';

					// Size.
					$size = isset( $field['size'] ) ? $field['size'] : null;

					// Options.
					$options = isset( $field['options'] ) ? $field['options'] : '';

					// Standard default value.
					$default = isset( $field['default'] ) ? $field['default'] : '';

					// Standard default placeholder.
					$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';

					// Sanitize Callback.
					$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';

					$args = array(
						'id'                => $id,
						'type'              => $type,
						'name'              => $name,
						'label_for'         => $label_for,
						'desc'              => $description,
						'section'           => $section,
						'size'              => $size,
						'options'           => $options,
						'std'               => $default,
						'placeholder'       => $placeholder,
						'sanitize_callback' => $sanitize_callback,
					);

					/**
					 * Add a new field to a section of a settings page.
					 *
					 * @param string   $id
					 * @param string   $title
					 * @param callable $callback
					 * @param string   $page
					 * @param string   $section = 'default'
					 * @param array    $args = array()
					 * @since 1.0.0
					 */

					// @param string 	$id
					$field_id = $section . '[' . $field['id'] . ']';

					add_settings_field(
						$field_id,
						$name,
						array( $this, 'callback_' . $type ),
						$section,
						$section,
						$args
					);
				} // foreach ended.
			} // foreach ended.

			// Creates our settings in the fields table.
			foreach ( $this->sections_array as $section ) {
				/**
				 * Registers a setting and its sanitization callback.
				 *
				 * @param string $field_group   | A settings group name.
				 * @param string $field_name    | The name of an option to sanitize and save.
				 * @param callable  $sanitize_callback = ''
				 * @since 1.0.0
				 */
				register_setting( $section['id'], $section['id'], array( $this, 'sanitize_fields' ) );
			} // foreach ended.

		} // admin_init() ended.


		/**
		 * Sanitize callback for Settings API fields.
		 *
		 * @since 1.0.0
		 */
		public function sanitize_fields( $fields ) {
			foreach ( $fields as $field_slug => $field_value ) {
				$sanitize_callback = $this->get_sanitize_callback( $field_slug );

				// If callback is set, call it.
				if ( $sanitize_callback ) {
					$fields[ $field_slug ] = call_user_func( $sanitize_callback, $field_value );
					continue;
				}
			}

			return $fields;
		}


		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param string $slug option slug.
		 * @return mixed string | bool false
		 * @since  1.0.0
		 */
		function get_sanitize_callback( $slug = '' ) {
			if ( empty( $slug ) ) {
				return false;
			}

			// Iterate over registered fields and see if we can find proper callback.
			foreach ( $this->fields_array as $section => $field_array ) {
				foreach ( $field_array as $field ) {
					if ( $field['name'] != $slug ) {
						continue;
					}

					// Return the callback name.
					return isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false;
				}
			}

			return false;
		}


		/**
		 * Get field description for display
		 *
		 * @param array $args settings field args
		 */
		public function get_field_description( $args ) {
			if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc = '';
			}

			return $desc;
		}


		/**
		 * Displays a title field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_title( $args ) {
			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			if ( '' !== $args['name'] ) {
				$name = $args['name'];
			} else {
			};
			$type = isset( $args['type'] ) ? $args['type'] : 'title';

			$html = '';
			echo $html;
		}


		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_text( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'], $args['placeholder'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type  = isset( $args['type'] ) ? $args['type'] : 'text';

			$html  = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"placeholder="%6$s"/>', $type, $size, $args['section'], $args['id'], $value, $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a url field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_url( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a number field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_number( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_checkbox( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

			$html  = '<fieldset>';
			$html .= sprintf( '<label for="wposa-%1$s[%2$s]">', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="checkbox" class="checkbox" id="wposa-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
			$html .= sprintf( '%1$s</label>', $args['desc'] );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_multicheck( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

			$html = '<fieldset>';
			foreach ( $args['options'] as $key => $label ) {
				$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
				$html   .= sprintf( '<label for="wposa-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html   .= sprintf( '<input type="checkbox" class="checkbox" id="wposa-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
				$html   .= sprintf( '%1$s</label><br>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_radio( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

			$html = '<fieldset>';
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<label for="wposa-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html .= sprintf( '<input type="radio" class="radio" id="wposa-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
				$html .= sprintf( '%1$s</label><br>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_select( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
			}
			$html .= sprintf( '</select>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_textarea( $args ) {

			$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args.
		 * @return string
		 */
		function callback_html( $args ) {
			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a rich text textarea for a settings field
		 *
		 * @param array $args settings field args.
		 */
		function callback_wysiwyg( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

			echo '<div style="max-width: ' . $size . ';">';

			$editor_settings = array(
				'teeny'         => true,
				'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
				'textarea_rows' => 10,
			);
			if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
				$editor_settings = array_merge( $editor_settings, $args['options'] );
			}

			wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

			echo '</div>';

			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		function callback_file( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = isset( $args['options']['button_label'] ) ?
			$args['options']['button_label'] :
			__( 'Choose File' );

			$html  = sprintf( '<input type="text" class="%1$s-text wpd-inventory-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= '<input type="button" class="button wpd-inventory-browse" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays an image upload field with a preview
		 *
		 * @param array $args settings field args.
		 */
		function callback_image( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = isset( $args['options']['button_label'] ) ?
			$args['options']['button_label'] :
			__( 'Choose Image' );

			$html  = sprintf( '<input type="text" class="%1$s-text wpd-inventory-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= '<input type="button" class="button wpd-inventory-browse" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );
			$html .= '<p class="wpd-inventory-image-preview"><img src=""/></p>';

			echo $html;
		}

		/**
		 * Displays a password field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_password( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_color( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'], $args['placeholder'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="text" class="%1$s-text color-picker" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" placeholder="%6$s" />', $size, $args['section'], $args['id'], $value, $args['std'], $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a separator field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_separator( $args ) {
			$type = isset( $args['type'] ) ? $args['type'] : 'separator';

			$html  = '';
			$html .= '<div class="wpd-inventory-settings-separator"></div>';
			echo $html;
		}


		/**
		 * Get the value of a settings field
		 *
		 * @param string $option  settings field name.
		 * @param string $section the section name this field belongs to.
		 * @param string $default default text if it's not found.
		 * @return string
		 */
		function get_option( $option, $section, $default = '' ) {

			$options = get_option( $section );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $default;
		}

		/**
		 * Add submenu page to the Settings main menu.
		 *
		 * @param string $page_title
		 * @param string $menu_title
		 * @param string $capability
		 * @param string $menu_slug
		 * @param callable $function = ''
		 * @author Ahmad Awais
		 * @since  [version]
		 */

		// public function admin_menu( $page_title = 'Page Title', $menu_title = 'Menu Title', $capability = 'manage_options', $menu_slug = 'settings_page', $callable = 'plugin_page' ) {
		public function admin_menu() {
			// add_options_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, $callable ) );
			add_submenu_page(
				'wpd-settings',
				'WP Dispensary\'s Inventory Management',
				'Inventory',
				'manage_options',
				'wpd-inventory',
				array( $this, 'plugin_page' )
			);
		}

		public function plugin_page() {
			echo '<div class="wrap">';
			echo '<h1>WP Dispensary\'s Inventory Management <span style="font-size:50%;">v' . WPD_INVENTORY_VERSION . '</span></h1>';
			$this->show_table();
			echo '</div>';
		}

		/**
		 * Show navigations as tab
		 *
		 * Shows all the settings section labels as tab
		 */
		function show_navigation() {
			$html = '<h2 class="nav-tab-wrapper">';

			foreach ( $this->sections_array as $tab ) {
				$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
			}

			$html .= '</h2>';

			echo $html;
		}

		/**
		 * Show the section settings forms
		 *
		 * This function displays every sections in a different form
		 */
		function show_forms() {
			?>
			<div class="metabox-holder">
				<?php foreach ( $this->sections_array as $form ) { ?>
					<!-- style="display: none;" -->
					<div id="<?php echo $form['id']; ?>" class="group" >
						<form method="post" action="options.php">
							<?php
							do_action( 'wsa_form_top_' . $form['id'], $form );
							settings_fields( $form['id'] );
							do_settings_sections( $form['id'] );
							do_action( 'wsa_form_bottom_' . $form['id'], $form );
							?>
							<div style="padding-left: 10px">
								<?php submit_button(); ?>
							</div>
						</form>
					</div>
				<?php } ?>
			</div>
			<?php
			$this->script();
		}

		/**
		 * Show the user table
		 *
		 * This function displays all users in a table.
		 */
		function show_table() {
			$html  = '';
			$html .= '<p>Brought to you by <a href="https://www.wpdispensary.com" target="_blank">WP Dispensary</a> &middot; <a href="https://www.wpdispensary.com/documentation" target="_blank">Documentation</a></p>';
			$html .= '<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search by product name">';
			$html .= '<table id="myTable" class="tablesorter wpd-inventory">
						<thead>
						<tr>
							<th>ID</th>
							<th>Type</th>
							<th>Product name</th>
							<th>Categories</th>
							<th>Stock</th>
						</tr>
						</thead>
						<tbody>';

			$loop = new WP_Query( array(
				'post_type'      => array( 'flowers', 'concentrates', 'edibles', 'topicals', 'prerolls', 'growers', 'gear', 'tinctures' ),
				'posts_per_page' => -1,
			) );

			// Get user info.
			$user    = wp_get_current_user();
			$user_id = $user->ID;

			// Start loop.
			if ( $loop->have_posts() ) :
			while ( $loop->have_posts() ) : $loop->the_post();
				if ( in_array( get_post_type(), array( 'flowers' ) ) ) {
					// Get permalink base for Flowers.
					$wpd_flowers_slug = get_option( 'wpd_flowers_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_flowers_slug ) {
						$wpd_flowers_slug = 'flowers';
					}

					// Capitalize first letter of new slug.
					$wpd_flowers_slug_cap = ucfirst( $wpd_flowers_slug );

					// Menu type name.
					$menutype = $wpd_flowers_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'flowers_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( ! get_post_meta( get_the_id(), '_inventory_flowers', true ) ) {
						$inventory_number = '0';
						$inventory        = "<input id='_inventory_flowers' name='" . get_the_id() . "' type='number' class='qty' value='" . $inventory_number . "' /> grams";
					} else {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_flowers', true );
						$inventory        = '<input id="_inventory_flowers" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> grams';
					}
				} elseif ( in_array( get_post_type(), array( 'concentrates' ) ) ) {

					// Get permalink base for Concentrates.
					$wpd_concentrates_slug = get_option( 'wpd_concentrates_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_concentrates_slug ) {
						$wpd_concentrates_slug = 'concentrates';
					}

					// Capitalize first letter of new slug.
					$wpd_concentrates_slug_cap = ucfirst( $wpd_concentrates_slug );

					// Menu type name.
					$menutype = $wpd_concentrates_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'concentrates_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( get_post_meta( get_the_id(), '_inventory_concentrates_each', true ) ) {
						$inventory        = '<input id="_inventory_concentrates_each" name="' . get_the_id() . '" type="number" class="qty" value="' . get_post_meta( get_the_id(), '_inventory_concentrates_each', true ) . '" /> units';
						$inventory_number = get_post_meta( get_the_id(), '_inventory_concentrates_each', true );
					} elseif ( get_post_meta( get_the_id(), '_inventory_concentrates', true ) ) {
						$inventory        = '<input id="_inventory_concentrates" name="' . get_the_id() . '" type="number" class="qty" value="' . get_post_meta( get_the_id(), '_inventory_concentrates', true ) . '" /> grams';
						$inventory_number = get_post_meta( get_the_id(), '_inventory_concentrates', true );
					} else {
						$inventory        = "<input id='_inventory_concentrates' name='" . get_the_id() . "' type='number' class='qty' value='0' /> grams";
						$inventory_number = '0';
					}

				} elseif ( in_array( get_post_type(), array( 'edibles' ) ) ) {
					// Get permalink base for Edibles.
					$wpd_edibles_slug = get_option( 'wpd_edibles_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_edibles_slug ) {
						$wpd_edibles_slug = 'edibles';
					}

					// Capitalize first letter of new slug.
					$wpd_edibles_slug_cap = ucfirst( $wpd_edibles_slug );

					// Menu type name.
					$menutype = $wpd_edibles_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'edibles_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( ! get_post_meta( get_the_id(), '_inventory_edibles', true ) ) {
						$inventory_number = '0';
						$inventory        = "<input id='_inventory_edibles' name='" . get_the_id() . "' type='number' class='qty' value='" . $inventory_number . "' /> units";
					} else {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_edibles', true );
						$inventory        = '<input id="_inventory_edibles" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
					}
				} elseif ( in_array( get_post_type(), array( 'topicals' ) ) ) {
					// Get permalink base for Topicals.
					$wpd_topicals_slug = get_option( 'wpd_topicals_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_topicals_slug ) {
						$wpd_topicals_slug = 'topicals';
					}

					// Capitalize first letter of new slug.
					$wpd_topicals_slug_cap = ucfirst( $wpd_topicals_slug );

					// Menu type name.
					$menutype = $wpd_topicals_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'topicals_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( ! get_post_meta( get_the_id(), '_inventory_topicals', true ) ) {
						$inventory_number = '0';
						$inventory        = "<input id='_inventory_topicals' name='" . get_the_id() . "' type='number' class='qty' value='" . $inventory_number . "' /> units";
					} else {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_topicals', true );
						$inventory        = '<input id="_inventory_topicals" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
					}
				} elseif ( in_array( get_post_type(), array( 'prerolls' ) ) ) {
					// Get permalink base for Pre-rolls.
					$wpd_prerolls_slug = get_option( 'wpd_prerolls_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_prerolls_slug ) {
						$wpd_prerolls_slug = 'pre-rolls';
					}

					// Capitalize first letter of new slug.
					$wpd_prerolls_slug_cap = ucfirst( $wpd_prerolls_slug );

					// Menu type name.
					$menutype = $wpd_prerolls_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'flowers_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( ! get_post_meta( get_the_id(), '_inventory_prerolls', true ) ) {
						$inventory_number = '0';
						$inventory        = "<input id='_inventory_prerolls' name='" . get_the_id() . "' type='number' class='qty' value='0' /> units";
					} else {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_prerolls', true );
						$inventory        = '<input id="_inventory_prerolls" name="' . get_the_id() . '" type="number" class="qty" value="' . get_post_meta( get_the_id(), '_inventory_prerolls', true ) . '" /> units';
					}
				} elseif ( in_array( get_post_type(), array( 'growers' ) ) ) {
					// Get permalink base for Growers.
					$wpd_growers_slug = get_option( 'wpd_growers_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_growers_slug ) {
						$wpd_growers_slug = 'growers';
					}

					// Capitalize first letter of new slug.
					$wpd_growers_slug_cap = ucfirst( $wpd_growers_slug );

					// Menu type name.
					$menutype = $wpd_growers_slug_cap;

					// Categories.
					$categories = wp_get_object_terms( get_the_id(), 'growers_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( get_post_meta( get_the_id(), '_inventory_seeds', true ) ) {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_seeds', true );
						$inventory        = '<input id="_inventory_seeds" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> seeds';
					} elseif ( get_post_meta( get_the_ID(), '_inventory_clones', TRUE ) ) {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_clones', true );
						$inventory        = '<input id="_inventory_clones" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> clones';
					} else {
						$inventory_number = '0';
						$inventory        = '<input id="_inventory_seeds" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> seeds';
					}
				} elseif ( in_array( get_post_type(), array( 'gear' ) ) ) {
					// Get permalink base for Gear.
					$wpd_gear_slug = get_option( 'wpd_gear_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_gear_slug ) {
						$wpd_gear_slug = 'gear';
					}

					// Capitalize first letter of new slug.
					$wpd_gear_slug_cap = ucfirst( $wpd_gear_slug );

					// Menu type name.
					$menutype = $wpd_gear_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'wpd_gear_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( ! get_post_meta( get_the_id(), '_inventory_gear', true ) ) {
						$inventory_number = '0';
						$inventory        = "<input id='_inventory_gear' name='" . get_the_id() . "' type='number' class='qty' value='0' /> units";
					} else {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_gear', true );
						$inventory        = '<input id="_inventory_gear" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
					}
				} elseif ( in_array( get_post_type(), array( 'tinctures' ) ) ) {
					// Get permalink base for Tinctures.
					$wpd_tinctures_slug = get_option( 'wpd_tinctures_slug' );

					// If custom base is empty, set default.
					if ( '' == $wpd_tinctures_slug ) {
						$wpd_tinctures_slug = 'tinctures';
					}

					// Capitalize first letter of new slug.
					$wpd_tinctures_slug_cap = ucfirst( $wpd_tinctures_slug );

					// Menu type name.
					$menutype = $wpd_tinctures_slug_cap;

					// Get categories.
					$categories = wp_get_object_terms( get_the_id(), 'wpd_tinctures_category' );

					if ( ! $categories ) {
						$categories = '';
					} else {
						foreach ( $categories as $cat ) {
							$categories = $cat->name;
						}
					}
					if ( ! get_post_meta( get_the_id(), '_inventory_tinctures', true ) ) {
						$inventory_number = '0';
						$inventory        = "<input id='_inventory_tinctures' name='" . get_the_id() . "' type='number' class='qty' value='0' /> units";
					} else {
						$inventory_number = get_post_meta( get_the_id(), '_inventory_tinctures', true );
						$inventory        = '<input id="_inventory_tinctures" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
					}
				}
				$html .= '<tr><td class="action">' . get_the_ID() . '</td><td>' . $menutype . '</td><td><a href="' . get_the_permalink() . '" target="_blank">' . get_the_title() . '</a> <a href="' . admin_url( 'post.php?post=' . get_the_id() . '&action=edit' ) . '" target="_blank" class="wpd-inventory edit-link">(Edit)</a></td><td>' . $categories . '</td><td data-sort-value="' . $inventory_number . '">'. $inventory . '</span></td></tr>';
			endwhile;
			endif;
			wp_reset_postdata();

			$html .= '</tbody></table>';

			echo $html;

		}

		/**
		 * Tabbable JavaScript codes & Initiate Color Picker
		 *
		 * This code uses localstorage for displaying active tabs
		 */
		function script() {
			?>
			<script>
				jQuery( document ).ready( function( $ ) {

				//Initiate Color Picker.
				$('.color-picker').iris();

				// Switches option sections
				$( '.group' ).hide();
				var activetab = '';
				if ( 'undefined' != typeof localStorage ) {
					activetab = localStorage.getItem( 'activetab' );
				}
				if ( '' != activetab && $( activetab ).length ) {
					$( activetab ).fadeIn();
				} else {
					$( '.group:first' ).fadeIn();
				}
				$( '.group .collapsed' ).each( function() {
					$( this )
						.find( 'input:checked' )
						.parent()
						.parent()
						.parent()
						.nextAll()
						.each( function() {
							if ( $( this ).hasClass( 'last' ) ) {
								$( this ).removeClass( 'hidden' );
								return false;
							}
							$( this )
								.filter( '.hidden' )
								.removeClass( 'hidden' );
						});
				});

				if ( '' != activetab && $( activetab + '-tab' ).length ) {
					$( activetab + '-tab' ).addClass( 'nav-tab-active' );
				} else {
					$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
				}
				$( '.nav-tab-wrapper a' ).click( function( evt ) {
					$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
					$( this )
						.addClass( 'nav-tab-active' )
						.blur();
					var clicked_group = $( this ).attr( 'href' );
					if ( 'undefined' != typeof localStorage ) {
						localStorage.setItem( 'activetab', $( this ).attr( 'href' ) );
					}
					$( '.group' ).hide();
					$( clicked_group ).fadeIn();
					evt.preventDefault();
				});

				$( '.wpd-inventory-browse' ).on( 'click', function( event ) {
					event.preventDefault();

					var self = $( this );

					// Create the media frame.
					var file_frame = ( wp.media.frames.file_frame = wp.media({
						title: self.data( 'uploader_title' ),
						button: {
							text: self.data( 'uploader_button_text' )
						},
						multiple: false
					}) );

					file_frame.on( 'select', function() {
						attachment = file_frame
							.state()
							.get( 'selection' )
							.first()
							.toJSON();

						self
							.prev( '.wpd-inventory-url' )
							.val( attachment.url )
							.change();
					});

					// Finally, open the modal
					file_frame.open();
				});

				$( 'input.wpd-inventory-url' )
					.on( 'change keyup paste input', function() {
						var self = $( this );
						self
							.next()
							.parent()
							.children( '.wpd-inventory-image-preview' )
							.children( 'img' )
							.attr( 'src', self.val() );
					})
					.change();
			});

			</script>

			<style type="text/css">
				/** WordPress 3.8 Fix **/
				.form-table th {
					padding: 20px 10px;
				}

				#wpbody-content .metabox-holder {
					padding-top: 5px;
				}

				.wpd-inventory-image-preview img {
					height: auto;
					max-width: 70px;
				}

				.wpd-inventory-settings-separator {
					background: #ccc;
					border: 0;
					color: #ccc;
					height: 1px;
					position: absolute;
					left: 0;
					width: 99%;
				}
				.group .form-table input.color-picker {
					max-width: 100px;
				}
			</style>
			<?php
		}
	} // WPD_INVENTORY ended.

endif;

/**
 * AJAX function to update inventory on settings page
 */
function wpd_inventory_settings() {

	$wpd_item_id = $_POST['wpd_item_id'];
	$metakey     = $_POST['metakey'];
	$metavalue   = $_POST['metavalue'];

	// Update inventory for WP Dispensary product.
	update_post_meta( $wpd_item_id, $metakey, $metavalue );

	// WooCommerce product loop $args
	$args = array(
        'post_type'      => 'product',
        'posts_per_page' => 1000,
    );

	// Get all products based on $args.
    $loop = get_posts( $args );

	// Loop through each product.
	foreach ( $loop as $item ) {

		if ( $wpd_item_id === get_post_meta( $item->ID, '_selected_flowers', true ) ) {
			/** Update the inventory metabox for the selected flower with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_concentrates', true ) ) {
			/** Update the inventory metabox for the selected concentrate with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_edibles', true ) ) {
			/** Update the inventory metabox for the selected edible with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_prerolls', true ) ) {
			/** Update the inventory metabox for the selected pre-roll with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_topicals', true ) ) {
			/** Update the inventory metabox for the selected topical with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_growers', true ) ) {
			/** Update the inventory metabox for the selected grower item with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_tinctures', true ) ) {
			/** Update the inventory metabox for the selected tinctures with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_gear', true ) ) {
			/** Update the inventory metabox for the selected gear with the updated stock_quantity */
			update_post_meta( $item->ID, '_stock', $metavalue );
			// If stock quantity is 0, change stock status.
			if ( '0' == $metavalue ) {
				update_post_meta( $item->ID, '_stock_status', 'outofstock' );
			} else {
				update_post_meta( $item->ID, '_stock_status', 'instock' );
			}
		} else {
			// Do nothing.
		}

	}

    exit;
}
add_action( 'wp_ajax_wpd_inventory_settings', 'wpd_inventory_settings' );
//add_action('wp_ajax_nopriv_wpd_inventory_settings', 'wpd_inventory_settings');
