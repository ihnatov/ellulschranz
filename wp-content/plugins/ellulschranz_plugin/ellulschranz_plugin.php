<?php
/*
 * Plugin Name: !Ellulschranz booking plugin
 * Description: Ellulschranz site plugin (for WooCommerce)
 * Author:      Ellulschranz
 * Version:     1.0
 */

 define( 'ELLULS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once(ELLULS_PLUGIN_PATH . '/include/functions.php');
require_once(ELLULS_PLUGIN_PATH . '/include/database/database.php');
require_once(ELLULS_PLUGIN_PATH . '/include/hooks_functions.php');

use Ell\Database;
use Ell\Functions;
use Ell\Hooks;

register_activation_hook( __FILE__, 'activate_ellulschranz_plugin' );

// activate this plugin
function activate_ellulschranz_plugin(){
    Database::create_table();
    Database::booking_page();

    Functions::set_product();
}

add_action('init', [Hooks::get_instance(), 'init']);
add_action('admin_menu', [Hooks::get_instance(), 'admin_menu']);

add_action('admin_post_nopriv_booking_form', [Functions::get_instance(), 'booking_form_action']);
add_action('admin_post_booking_form', [Functions::get_instance(), 'booking_form_action']);

add_action('admin_post_booking_settings', [Functions::get_instance(), 'booking_settings_action']);
add_action('admin_post_add_booking_services', [Functions::get_instance(), 'add_booking_services_action']);

// Ajax
add_action( 'wp_ajax_nopriv_booking_form', [Functions::get_instance(), 'booking_form_action'] );
add_action( 'wp_ajax_booking_form', [Functions::get_instance(), 'booking_form_action'] );
add_action( 'wp_ajax_delete_service', [Functions::get_instance(), 'delete_service'] );
add_action( 'wp_ajax_get_booking_details', [Functions::get_instance(), 'get_booking_details'] );

add_action( 'wp_ajax_save_reservate_datetimes', [Functions::get_instance(), 'save_reservate_datetimes'] );
add_action( 'wp_ajax_show_manager_calendar', [Functions::get_instance(), 'show_manager_calendar'] );

add_action( 'wp_ajax_nopriv_show_booking_calendar_disabled', [Functions::get_instance(), 'show_booking_calendar_disabled'] );
add_action( 'wp_ajax_show_booking_calendar_disabled', [Functions::get_instance(), 'show_booking_calendar_disabled'] );

add_action( 'wp_ajax_update_details_statuses', [Functions::get_instance(), 'update_details_statuses'] );