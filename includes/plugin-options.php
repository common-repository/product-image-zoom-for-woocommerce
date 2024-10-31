<?php

/**
 * Option page for this plugin
 */
if ( !class_exists('PIZFWC_Settings' ) ):
class PIZFWC_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new PIZFWC_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu'), 100 );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_submenu_page( 'woocommerce', esc_html__('Product Image Zoom Options', 'pizfwc'), esc_html__('Product Image Zoom', 'pizfwc'), 'delete_posts', 'pizfwc_options', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'pizfwc_settings_general',
                'title' => esc_html__( 'General', 'pizfwc' )
            ),
            array(
                'id'    => 'pizfwc_settings_styling',
                'title' => esc_html__( 'Styling', 'pizfwc' )
            ),
            array(
                'id'    => 'pizfwc_settings_animation',
                'title' => esc_html__( 'Animation', 'pizfwc' )
            ),
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'pizfwc_settings_general' => array(
                // enable_zoom
                array(
                    'name'  => 'enable_zoom',
                    'label' => esc_html__( 'Enable/Disable Product Zoom', 'pizfwc' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class' => 'pizfwc',
                ),

                // enable_in_mobile
                array(
                    'name'  => 'enable_in_mobile',
                    'label' => esc_html__( 'Enable/Disable On Mobile', 'pizfwc' ),
                    'desc' => esc_html__( 'It Works with real mobile devices. Do not test it by resize browser window.', 'pizfwc' ),
                    'type'    => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => esc_html__('Enable','pizfwc'),
                        'false'  => esc_html__('Disable','pizfwc')
                    ),
                    'class' => 'pizfwc',
                ),

                // zoom_type
                array(
                    'name'    => 'zoom_type',
                    'label'   => esc_html__( 'Zoom Type', 'pizfwc' ),
                    'type'    => 'select',
                    'default' => 'inner',
                    'options' => array(
                        'inner' => 'Inner',
                        'window'  => 'Window',
                        'lens'  => 'Lens'
                    ),
                    'class' => 'pizfwc',
                ),

                // zoom_window_width
                array(
                    'name'              => 'zoom_window_width',
                    'label'             => esc_html__( 'Zoom Window Width', 'pizfwc' ),
                    'desc'              => esc_html__( 'Width of the Zoom Window. (Effective for Zoom Type: window)', 'pizfwc' ),
                    'placeholder'       => esc_html__( '400', 'pizfwc' ),
                    'type'              => 'text',
                    'default'           => '400',
                    'class' => 'pizfwc',
                ),
                // zoom_window_height
                array(
                    'name'              => 'zoom_window_height',
                    'label'             => esc_html__( 'Zoom Window Height', 'pizfwc' ),
                    'desc'              => esc_html__( 'Width of the Zoom Window.  (Effective for Zoom Type: window)', 'pizfwc' ),
                    'placeholder'       => esc_html__( '400', 'pizfwc' ),
                    'type'              => 'text',
                    'default'           => '400',
                    'class' => 'pizfwc',
                ),
                // zoom_window_offsetx
                array(
                    'name'              => 'zoom_window_offsetx',
                    'label'             => esc_html__( 'Zoom Window Offset X', 'pizfwc' ),
                    'desc'              => esc_html__( 'x-axis offset of the zoom window.  (Effective for Zoom Type: window) Default: 0', 'pizfwc' ),
                    'placeholder'       => esc_html__( '0', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 1000,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // zoom_window_offsety
                array(
                    'name'              => 'zoom_window_offsety',
                    'label'             => esc_html__( 'Zoom Window Offset Y', 'pizfwc' ),
                    'desc'              => esc_html__( 'y-axis offset of the zoom window.  (Effective for Zoom Type: window) Default: 0', 'pizfwc' ),
                    'placeholder'       => esc_html__( '0', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 1000,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // cursor
                array(
                    'name'    => 'cursor',
                    'label'   => esc_html__( 'Cursor Type', 'pizfwc' ),
                    'desc'    => esc_html__( '(Effective for Zoom Type: window/inner)', 'pizfwc' ),
                    'type'    => 'select',
                    'default' => 'crosshair',
                    'options' => array(
                        'cursor' => esc_html__('Cursor','pizfwc'),
                        'crosshair'  => esc_html__('Crosshair','pizfwc')
                    ),
                    'class' => 'pizfwc',
                ),
                // lens_shape
                array(
                    'name'    => 'lens_shape',
                    'label'   => esc_html__( 'Lens Shape', 'pizfwc' ),
                    'desc'    => esc_html__( '(Effective for Zoom Type: window/lens)', 'pizfwc' ),
                    'type'    => 'select',
                    'default' => 'square',
                    'options' => array(
                        'square'  => esc_html__('Square','pizfwc'),
                        'round' => esc_html__('Round','pizfwc')
                    ),
                    'class' => 'pizfwc',
                ),
                // lens_size
                array(
                    'name'              => 'lens_size',
                    'label'             => esc_html__( 'Lens Size', 'pizfwc' ),
                    'desc'              => esc_html__( 'Used when Zoom Type set to lens, when zoom type is set to window, then the lens size is auto calculated', 'pizfwc' ),
                    'placeholder'       => esc_html__( '400', 'pizfwc' ),
                    'type'              => 'text',
                    'default'           => '400',
                    'class' => 'pizfwc',
                ),
                // contain_lens_zoom
                array(
                    'name'    => 'contain_lens_zoom',
                    'label'   => esc_html__( 'Enable/Disable Contain Lens Zoom', 'pizfwc' ),
                    'desc'   => esc_html__( '(Effective for Zoom Type: lens) This makes sure the lens does not fall outside the outside of the image', 'pizfwc' ),
                    'type'    => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => esc_html__('Enable','pizfwc'),
                        'false'  => esc_html__('Disable','pizfwc')
                    ),
                    'class' => 'pizfwc',
                ),
            ),
            'pizfwc_settings_styling' => array(
                // border_size
                array(
                    'name'              => 'border_size',
                    'label'             => esc_html__( 'Window Border Size', 'pizfwc' ),
                    'desc'             => esc_html__( 'Border size of the window. Default:1', 'pizfwc' ),
                    'placeholder'       => esc_html__( '1', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '1',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // border_colour
                array(
                    'name'    => 'border_colour',
                    'label'   => esc_html__( 'Window Border Color', 'pizfwc' ),
                    'type'    => 'color',
                    'default' => '',
                    'class' => 'pizfwc',
                ),
                // lens_colour
                array(
                    'name'    => 'lens_colour',
                    'label'   => esc_html__( 'Lens Background Color', 'pizfwc' ),
                    'desc'    => esc_html__( 'Color of the lens background. When using tint, this is overrided to transparent.', 'pizfwc' ),
                    'type'    => 'color',
                    'default' => '',
                    'class' => 'pizfwc',
                ),
                // lens_opacity
                array(
                    'name'              => 'lens_opacity',
                    'label'             => esc_html__( 'Lens Opacity', 'pizfwc' ),
                    'desc'              => esc_html__( 'Used in combination with lensColour to make the lens see through. When using tint, this is overrided to 0', 'pizfwc' ),
                    'placeholder'       => esc_html__( '0.4', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '0.1',
                    'type'              => 'number',
                    'default'           => '0.4',
                    'sanitize_callback' => 'floatval',
                    'class' => 'pizfwc',
                ),
            ),
            'pizfwc_settings_animation' => array(
                // easing
                array(
                    'name'    => 'easing',
                    'label'   => esc_html__( 'Enable/Disable Easing Effect', 'pizfwc' ),
                    'type'    => 'select',
                    'default' => 'true',
                    'options' => array(
                        'true' => 'Enable',
                        'false'  => 'Disable'
                    ),
                    'class' => 'pizfwc',
                ),
                // lens_fade_in
                array(
                    'name'              => 'lens_fade_in',
                    'label'             => esc_html__( 'Lens Fade In Speed', 'pizfwc' ),
                    'desc'             => esc_html__( 'Set as a number e.g 200 for speed of Lens fadeIn. Default: false', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 10000,
                    'step'              => '50',
                    'type'              => 'number',
                    'default'           => '',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // lens_Fade_out
                array(
                    'name'              => 'lens_fade_out',
                    'label'             => esc_html__( 'Lens Fade Out Speed', 'pizfwc' ),
                    'desc'             => esc_html__( 'Set as a number e.g 200 for speed of Lens fadeOut. Default: false', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 10000,
                    'step'              => '50',
                    'type'              => 'number',
                    'default'           => '',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // zoom_window_fade_in
                array(
                    'name'              => 'zoom_window_fade_in',
                    'label'             => esc_html__( 'Zoom Window Fade In Speed', 'pizfwc' ),
                    'desc'             => esc_html__( 'Set as a number e.g 200 for speed of Zoom Window fadeIn. Default: false', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 10000,
                    'step'              => '50',
                    'type'              => 'number',
                    'default'           => '',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // zoom_window_fade_out
                array(
                    'name'              => 'zoom_window_fade_out',
                    'label'             => esc_html__( 'Zoom Window Fade Out Speed', 'pizfwc' ),
                    'desc'             => esc_html__( 'Set as a number e.g 200 for speed of Zoom Window fadeOut. Default: false', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 10000,
                    'step'              => '50',
                    'type'              => 'number',
                    'default'           => '',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // zoom_tint_fade_in
                array(
                    'name'              => 'zoom_tint_fade_in',
                    'label'             => esc_html__( 'Zoom Tint Fade In Speed', 'pizfwc' ),
                    'desc'             => esc_html__( 'Set as a number e.g 200 for speed of Zoom Tint fadeIn. Default: false', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 10000,
                    'step'              => '50',
                    'type'              => 'number',
                    'default'           => '',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
                // zoom_tint_fade_out
                array(
                    'name'              => 'zoom_tint_fade_out',
                    'label'             => esc_html__( 'Zoom Tint Fade Out Speed', 'pizfwc' ),
                    'desc'             => esc_html__( 'Set as a number e.g 200 for speed of Zoom Tint fadeOut. Default: false', 'pizfwc' ),
                    'min'               => 0,
                    'max'               => 10000,
                    'step'              => '50',
                    'type'              => 'number',
                    'default'           => '',
                    'sanitize_callback' => 'intval',
                    'class' => 'pizfwc',
                ),
            ),
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

new PIZFWC_Settings();

/**
 * Get plugin option value
 */
function pizfwc_get_option($opt_name = '', $section, $default = ''){

    // check query string
    if(isset($_REQUEST[$opt_name])){

        return sanitize_text_field($_REQUEST[$opt_name]);

    // check plugin option
    } elseif($section && $opt_name){

        $options = get_option( $section );
        if ( isset( $options[$opt_name] ) ) {
            return $options[$opt_name];
        } else {
            return $default;
        }

    } else {

        return $default;

    }
    
}