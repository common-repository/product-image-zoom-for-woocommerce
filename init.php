<?php
/**
Plugin Name: Easy Product Image Zooom For WooCommerce
Plugin URI: http://demo.azplugins.com/product-image-zoom/
Description: This plugin enable options to zoom product image in the WooCommerce single product page.
Version: 1.0.2
Author: AZ Plugins
Author URI: https://azplugins.com
Text Domain: pizfwc
License:     GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
*/

/**
 * Define path
 */
define( 'PIZFWC_PLUGIN_URL', plugins_url('', __FILE__) );
define( 'PIZFWC_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'PIZFWC_PLUGIN_VERSION', dirname( __FILE__ ) );

/**
 * Include all files
 */
require_once( PIZFWC_PLUGIN_DIR. '/includes/class.settings-api.php');
require_once( PIZFWC_PLUGIN_DIR. '/includes/plugin-options.php');

/**
 * Load text domain
 */
function pizfwc_load_textdomain() {
    load_plugin_textdomain( 'pizfwc', false, basename(PIZFWC_PLUGIN_URL) . '/languages/' );
}
add_action( 'init', 'pizfwc_load_textdomain' );

/**
 * Initialize
 */
function pizfwc_initialize(){
    // enable/disable zoom control
    $pizfwc_enable_status = pizfwc_get_option('enable_zoom', 'pizfwc_settings_general');

    // load on single product page
    if(is_singular( 'product' ) && $pizfwc_enable_status == 'on'){
        add_filter( 'body_class', 'pizfwc_modify_body_class' );

        // list current product categories
        $prod_cats = wp_get_post_terms(get_the_id(), 'product_cat');
        $prod_cat_ids = array();

        foreach ($prod_cats as $prod_cat) {
            $prod_cat_ids[] = $prod_cat->term_id;
        }

        // check user input categories with current categories
        $exclude_category_ids = pizfwc_get_option('exclude_category_ids', 'pizfwc_settings_ie');
        $exclude_category_ids = explode(',', $exclude_category_ids);

        if(array_intersect($exclude_category_ids, $prod_cat_ids)){
            $pizfwc_enable_status = false;
        }

        // check user input ids with current product id
        $exclude_product_ids = pizfwc_get_option('exclude_product_ids', 'pizfwc_settings_ie');
        $exclude_product_ids = explode(',', $exclude_product_ids);
        if(in_array(get_the_id(), $exclude_product_ids)){
            $pizfwc_enable_status = false;
        }
    }

    // enable/disable in mobile device
    $pizfwc_mobile_enable_status = pizfwc_get_option('enable_in_mobile', 'pizfwc_settings_general');

    if(wp_is_mobile() && $pizfwc_mobile_enable_status == 'false'){
       $pizfwc_enable_status = false;
    }

    if($pizfwc_enable_status != 'on' || $pizfwc_enable_status == false){
        return;
    }

    // load woocommerce customizations
    require_once( PIZFWC_PLUGIN_DIR. '/includes/woo-config.php');

    // enqueue scripts
    add_action( 'wp_enqueue_scripts','pizfwc_enqueue_scripts');
}
add_action( 'template_redirect', 'pizfwc_initialize' );


/**
 * Enqueue scripts
 */
function  pizfwc_enqueue_scripts(){
     wp_enqueue_style( 'pizfwc-main', PIZFWC_PLUGIN_URL.'/assets/css/main.css');
     wp_enqueue_script( 'jquery-elevatezoom', PIZFWC_PLUGIN_URL.'/assets/js/jquery.elevatezoom.js', array('jquery'), '3.0.8', false);
     wp_enqueue_script( 'pizfwc-main', PIZFWC_PLUGIN_URL.'/assets/js/main.js', array('jquery'), '1.0.0', true);

     // general options
     $localized_vars = array();
     $localized_vars['zoom_type'] = pizfwc_get_option( 'zoom_type', 'pizfwc_settings_general' );
     $localized_vars['zoom_window_width'] = pizfwc_get_option( 'zoom_window_width', 'pizfwc_settings_general' );
     $localized_vars['zoom_window_height'] = pizfwc_get_option( 'zoom_window_height', 'pizfwc_settings_general' );
     $localized_vars['zoom_window_offsetx'] = pizfwc_get_option( 'zoom_window_offsetx', 'pizfwc_settings_general' );
     $localized_vars['zoom_window_offsety'] = pizfwc_get_option( 'zoom_window_offsety', 'pizfwc_settings_general' );
     $localized_vars['zoom_window_position'] = pizfwc_get_option( 'zoom_window_position', 'pizfwc_settings_general' );
     $localized_vars['cursor'] = pizfwc_get_option( 'cursor', 'pizfwc_settings_general' );
     $localized_vars['lens_shape'] = pizfwc_get_option( 'lens_shape', 'pizfwc_settings_general' );
     $localized_vars['lens_size'] = pizfwc_get_option( 'lens_size', 'pizfwc_settings_general' );
     $localized_vars['tint'] = pizfwc_get_option( 'tint', 'pizfwc_settings_general' );
     $localized_vars['scroll_zoom'] = pizfwc_get_option( 'scroll_zoom', 'pizfwc_settings_general' );
     $localized_vars['contain_lens_zoom'] = pizfwc_get_option( 'contain_lens_zoom', 'pizfwc_settings_general' );

     // styling options
     $localized_vars['border_size'] = pizfwc_get_option( 'border_size', 'pizfwc_settings_styling' );
     $localized_vars['border_colour'] = pizfwc_get_option( 'border_colour', 'pizfwc_settings_styling' );
     $localized_vars['lens_border'] = pizfwc_get_option( 'lens_border', 'pizfwc_settings_styling' );
     $localized_vars['lens_colour'] = pizfwc_get_option( 'lens_colour', 'pizfwc_settings_styling' );
     $localized_vars['lens_opacity'] = pizfwc_get_option( 'lens_opacity', 'pizfwc_settings_styling' );
     $localized_vars['tint_colour'] = pizfwc_get_option( 'tint_colour', 'pizfwc_settings_styling' );
     $localized_vars['tint_opacity'] = pizfwc_get_option( 'tint_opacity', 'pizfwc_settings_styling' );

     // animation
     $localized_vars['easing'] = pizfwc_get_option( 'easing', 'pizfwc_settings_animation' );
     $localized_vars['easing_duration'] = pizfwc_get_option( 'easing_duration', 'pizfwc_settings_animation' );
     $localized_vars['lens_fade_in'] = pizfwc_get_option( 'lens_fade_in', 'pizfwc_settings_animation' );
     $localized_vars['lens_fade_out'] = pizfwc_get_option( 'lens_fade_out', 'pizfwc_settings_animation' );
     $localized_vars['zoom_window_fade_in'] = pizfwc_get_option( 'zoom_window_fade_in', 'pizfwc_settings_animation' );
     $localized_vars['zoom_window_fade_out'] = pizfwc_get_option( 'zoom_window_fade_out', 'pizfwc_settings_animation' );
     $localized_vars['zoom_tint_fade_in'] = pizfwc_get_option( 'zoom_tint_fade_in', 'pizfwc_settings_animation' );
     $localized_vars['zoom_tint_fade_out'] = pizfwc_get_option( 'zoom_tint_fade_out', 'pizfwc_settings_animation' );
     
     // localize script
     wp_localize_script( "pizfwc-main", "pizfwc_localize", $localized_vars );
}

/**
 * Admin style
 */
function pizfwc_admin_enqueue_scripts(){
    // admin inline styles
    $admin_inline_style = "
       .pizfwc p.description {
           color: #ca4a1f;
       }
    ";
    wp_add_inline_style( 'wp-admin', $admin_inline_style );
}
add_action( 'admin_enqueue_scripts','pizfwc_admin_enqueue_scripts');

/**
 * Modify body class
 */
function pizfwc_modify_body_class($classes){
    $zoom_type = pizfwc_get_option('zoom_type', 'pizfwc_settings_general');
    $lens_shape = pizfwc_get_option('lens_shape', 'pizfwc_settings_general');
    $classes[] = 'pizfwc_zoom_type--' . $zoom_type;
    $classes[] = 'pizfwc_lens_shape--'.$lens_shape;

    return $classes;
}

/**
 * Add settings page link
 */
add_filter('plugin_action_links_product-image-zoom-for-wc/init.php', 'pizfwc_settings_page_link_add', 10, 4);
function pizfwc_settings_page_link_add( $actions, $plugin_file, $plugin_data, $context ){
    $new_link = sprintf( '<a href="%s">%s</a>',
        esc_url( get_admin_url() . 'admin.php?page=pizfwc_options' ),
        esc_html__( 'Settings', 'pizfwc' )
    );

    array_unshift( $actions, $new_link );

    return $actions;
}