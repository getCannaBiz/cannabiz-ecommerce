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
        private $_sections_array = array();

        /**
         * Fields array.
         *
         * @var   array
         * @since 1.0.0
         */
        private $_fields_array = array();

        /**
         * Constructor.
         *
         * @since  1.0.0
         * @return void
         */
        public function __construct() {
            // Enqueue the admin scripts.
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

            // Hook it up.
            add_action( 'admin_init', array( $this, 'admin_init' ) );

            // Menu.
            add_action( 'admin_menu', array( $this, 'admin_menu' ), 98 );

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
         * 
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
         * 
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
             * $_sections_array = array (
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
            foreach ( $this->_sections_array as $section ) {
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
             * $_fields_array = array (
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
            foreach ( $this->_fields_array as $section => $field_array ) {
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
                     * 
                     * @since 1.0.0
                     */

                    // @param string     $id
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
            foreach ( $this->_sections_array as $section ) {
                /**
                 * Registers a setting and its sanitization callback.
                 *
                 * @param string   $field_group | A settings group name.
                 * @param string   $field_name  | The name of an option to sanitize and save.
                 * @param callable $sanitize_callback = ''
                 * 
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
         */

        // public function admin_menu( $page_title = 'Page Title', $menu_title = 'Menu Title', $capability = 'manage_options', $menu_slug = 'settings_page', $callable = 'plugin_page' ) {
        public function admin_menu() {
            // add_options_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, $callable ) );
            add_submenu_page(
                'wpd-settings',
                'WP Dispensary\'s Inventory Management',
                __( 'Inventory', 'wpd-ecommerce' ),
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
                            <th>' . esc_attr__( 'ID', 'wpd-ecommerce' ) . '</th>
                            <th>' . esc_attr__( 'Type', 'wpd-ecommerce' ) . '</th>
                            <th>' . esc_attr__( 'Product name', 'wpd-ecommerce' ) . '</th>
                            <th>' . esc_attr__( 'Categories', 'wpd-ecommerce' ) . '</th>
                            <th>' . esc_attr__( 'Stock', 'wpd-ecommerce' ) . '</th>
                        </tr>
                        </thead>
                        <tbody>';

            $loop = new WP_Query( array(
                'post_type'      => array( 'products' ),
                'posts_per_page' => -1,
            ) );

            // Get user info.
            $user    = wp_get_current_user();
            $user_id = $user->ID;

            // Start loop.
            if ( $loop->have_posts() ) :
            while ( $loop->have_posts() ) : $loop->the_post();
                if ( 'flowers' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( ! get_post_meta( get_the_id(), 'inventory_grams', true ) ) {
                        $inventory_number = '0';
                        $inventory        = "<input id='inventory_grams' name='" . get_the_id() . "' type='number' class='qty' value='" . $inventory_number . "' /> grams";
                    } else {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_grams', true );
                        $inventory        = '<input id="inventory_grams" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> grams';
                    }
                } elseif ( 'concentrates' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( get_post_meta( get_the_id(), 'inventory_units', true ) ) {
                        $inventory        = '<input id="inventory_units" name="' . get_the_id() . '" type="number" class="qty" value="' . get_post_meta( get_the_id(), 'inventory_units', true ) . '" /> units';
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_units', true );
                    } elseif ( get_post_meta( get_the_id(), 'inventory_grams', true ) ) {
                        $inventory        = '<input id="inventory_grams" name="' . get_the_id() . '" type="number" class="qty" value="' . get_post_meta( get_the_id(), 'inventory_grams', true ) . '" /> grams';
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_grams', true );
                    } else {
                        $inventory        = "<input id='inventory_grams' name='" . get_the_id() . "' type='number' class='qty' value='0' /> grams";
                        $inventory_number = '0';
                    }

                } elseif ( 'edibles' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( ! get_post_meta( get_the_id(), 'inventory_units', true ) ) {
                        $inventory_number = '0';
                        $inventory        = "<input id='inventory_units' name='" . get_the_id() . "' type='number' class='qty' value='" . $inventory_number . "' /> units";
                    } else {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_units', true );
                        $inventory        = '<input id="inventory_units" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
                    }
                } elseif ( 'topicals' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( ! get_post_meta( get_the_id(), 'inventory_units', true ) ) {
                        $inventory_number = '0';
                        $inventory        = "<input id='inventory_units' name='" . get_the_id() . "' type='number' class='qty' value='" . $inventory_number . "' /> units";
                    } else {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_units', true );
                        $inventory        = '<input id="inventory_units" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
                    }
                } elseif ( 'prerolls' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( ! get_post_meta( get_the_id(), 'inventory_units', true ) ) {
                        $inventory_number = '0';
                        $inventory        = "<input id='inventory_units' name='" . get_the_id() . "' type='number' class='qty' value='0' /> units";
                    } else {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_units', true );
                        $inventory        = '<input id="inventory_units" name="' . get_the_id() . '" type="number" class="qty" value="' . get_post_meta( get_the_id(), 'inventory_units', true ) . '" /> units';
                    }
                } elseif ( 'growers' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( get_post_meta( get_the_id(), 'inventory_seeds', true ) ) {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_seeds', true );
                        $inventory        = '<input id="inventory_seeds" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> seeds';
                    } elseif ( get_post_meta( get_the_ID(), 'inventory_clones', true ) ) {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_clones', true );
                        $inventory        = '<input id="inventory_clones" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> clones';
                    } else {
                        $inventory_number = '0';
                        $inventory        = '<input id="inventory_seeds" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> seeds';
                    }
                } elseif ( 'gear' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( ! get_post_meta( get_the_id(), 'inventory_units', true ) ) {
                        $inventory_number = '0';
                        $inventory        = "<input id='inventory_units' name='" . get_the_id() . "' type='number' class='qty' value='0' /> units";
                    } else {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_units', true );
                        $inventory        = '<input id="inventory_units" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
                    }
                } elseif ( 'tinctures' == get_post_meta( get_the_ID(), 'product_type', true ) ) {
                    // Menu type name.
                    $menutype = wpd_product_type_display_name( get_post_meta( get_the_ID(), 'product_type', true ) );

                    // Get categories.
                    $categories = wp_get_object_terms( get_the_id(), 'wpd_categories' );

                    if ( ! $categories ) {
                        $categories = '';
                    } else {
                        foreach ( $categories as $cat ) {
                            $categories = $cat->name;
                        }
                    }
                    if ( ! get_post_meta( get_the_id(), 'inventory_tinctures', true ) ) {
                        $inventory_number = '0';
                        $inventory        = "<input id='inventory_tinctures' name='" . get_the_id() . "' type='number' class='qty' value='0' /> units";
                    } else {
                        $inventory_number = get_post_meta( get_the_id(), 'inventory_tinctures', true );
                        $inventory        = '<input id="inventory_units" name="' . get_the_id() . '" type="number" class="qty" value="' . $inventory_number . '" /> units';
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

    $wpd_item_id = filter_input( INPUT_POST, 'wpd_item_id' );
    $metakey     = filter_input( INPUT_POST, 'metakey' );
    $metavalue   = filter_input( INPUT_POST, 'metavalue' );

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
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_concentrates', true ) ) {
            /** Update the inventory metabox for the selected concentrate with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_edibles', true ) ) {
            /** Update the inventory metabox for the selected edible with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_prerolls', true ) ) {
            /** Update the inventory metabox for the selected pre-roll with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_topicals', true ) ) {
            /** Update the inventory metabox for the selected topical with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_growers', true ) ) {
            /** Update the inventory metabox for the selected grower item with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_tinctures', true ) ) {
            /** Update the inventory metabox for the selected tinctures with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } elseif ( $wpd_item_id === get_post_meta( $item->ID, '_selected_gear', true ) ) {
            /** Update the inventory metabox for the selected gear with the updated stock_quantity */
            update_post_meta( $item->ID, '_stock', $metavalue );
            // If stock quantity is 0, change stock status.
            if ( '0' == $metavalue ) {
                update_post_meta( $item->ID, 'stock_status', 'outofstock' );
            } else {
                update_post_meta( $item->ID, 'stock_status', 'instock' );
            }
        } else {
            // Do nothing.
        }

    }

    exit;
}
add_action( 'wp_ajax_wpd_inventory_settings', 'wpd_inventory_settings' );
//add_action('wp_ajax_nopriv_wpd_inventory_settings', 'wpd_inventory_settings');
