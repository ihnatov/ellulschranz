<?php
namespace Ell;

use Ell\Functions;
use Ell\Database;

class Hooks {

    protected static $instance = NULL;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
          self::$instance = new self;
        }
        return self::$instance;
    }

    public function init()
    {
        $functions = Functions::get_instance();

        add_shortcode('booking_form', [$functions, 'booking_form']); // register shortcode
        add_shortcode('booking_modal_form', [$functions, 'booking_modal_form']); // register shortcode
        add_action('wp_enqueue_scripts', [$functions, 'booking_css_sctipt']); // register plugin CSS and JS
        if ($_GET['page'] !== 'booking-my-calendar') {
            add_action( 'admin_enqueue_scripts', [$functions, 'booking_css_sctipt']);
        }

        // change booking status
        add_action('woocommerce_payment_complete', [$functions, 'change_booking_status_payed'] );
        add_action('woocommerce_order_status_processing', [$functions, 'checkout_success_order_paid'], 10, 1);
    }

    public function admin_menu()
    {
        $functions = Functions::get_instance();
        $functions->menu_settings_page();
    }
}