<?php
namespace Ell;

use Ell\Database;

class Functions
{
    protected static $instance = NULL;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
        self::$instance = new self;
        }
        return self::$instance;
    }

    public function booking_css_sctipt() 
    {
        wp_enqueue_style('booking_css', plugins_url('/include/css/booking.css', dirname(__FILE__)));
        wp_enqueue_script('booking_sctipt', plugins_url('/include/js/booking.js', dirname(__FILE__)), ['jquery']);

        // air_datepicker css / js 
        wp_enqueue_style('air_datepicker_css', plugins_url('/include/air-datepicker/css/datepicker.css', dirname(__FILE__)));
        wp_enqueue_script('air_datepicker_js', plugins_url('/include/air-datepicker/js/datepicker.js', dirname(__FILE__)), ['jquery']);

        // time picker css / js 
        wp_enqueue_style('time_picker_css', plugins_url('/include/time-picker/css/time-picker.css', dirname(__FILE__)));
        wp_enqueue_script('time_picker_js', plugins_url('/include/time-picker/js/time-picker.js', dirname(__FILE__)), ['jquery']);

        // DataTables
        wp_enqueue_style('data_table_css', plugins_url('/include/DataTables/datatables.css', dirname(__FILE__)));
        wp_enqueue_script('data_table_js', plugins_url('/include/DataTables/datatables.js', dirname(__FILE__)), ['jquery']);
    }

    /*
    *********************************************************************************************
        FORMS
    *********************************************************************************************
    */

    // 
    //      BOOKING FORM 
    //
    public function booking_form()
    {
        $template = file_get_contents(plugins_url('/include/template/booking_form.php', dirname(__FILE__)));

        $form_action = esc_url(admin_url('admin-post.php'));
        $ajaxurl = esc_url(admin_url('admin-ajax.php'));
        $template = str_replace('[form_action]', $form_action, $template);
        $template = str_replace('[ajax_url]', $ajaxurl, $template);
        $template = str_replace('[services_block]', $this->get_services_to_booking_page(), $template);
        $template = str_replace('[options_block]', $this->get_options_to_booking_page(), $template);

        echo $template;
    }

    public function booking_modal_form()
    {
        $template = file_get_contents(plugins_url('/include/template/booking_modal_form.php', dirname(__FILE__)));
        $form_action = esc_url(admin_url('admin-post.php'));
        $ajaxurl = esc_url(admin_url('admin-ajax.php'));
        $template = str_replace('[form_action]', $form_action, $template);
        $template = str_replace('[ajax_url]', $ajaxurl, $template);
        $template = str_replace('[services_block]', $this->get_services_to_booking_page(), $template);
        $template = str_replace('[options_block]', $this->get_options_to_booking_page(), $template);

        echo $template;
    }

    function booking_form_action() {
        $db = Database::get_instance();

        $ajax = true;
        $back = '/booking/';
        $action = $_POST['action'] ?? '';

        if ($ajax) {
            if ($action !== 'booking_form') {echo json_encode(['status' => false]); wp_die();}
            if ($_POST['total'] < 1)  {echo json_encode(['status' => false]); wp_die();}
        } else {
            if ($action !== 'booking_form') wp_safe_redirect($back);
            if ($_POST['total'] < 1) wp_safe_redirect($back);
        }

        $order = $this->create_wc_order($_POST['total']);
        $_POST['order_id'] = $order->id;
        $_POST['payment_link'] = $order->get_checkout_payment_url();

        $save = $db->save_booking_form($_POST);
        $_POST['reservation_id'] = $save;

        if ($save) {
            if ($ajax) {
                $this->reservation_mail($_POST);
                
                echo json_encode(['status' => true]);
                wp_die();
            } else {
                wp_safe_redirect($back);
            }
        } else {
            if ($ajax) {echo json_encode(['status' => false]); wp_die();}
            wp_safe_redirect($back);
        }
    }

    public function get_services_to_booking_page()
    {
        $services_block = '';

        $db = Database::get_instance();
        $services = $db->get_all_services();
        if (!$services) return $services_block;

        $options = [];
        $services_block .= $this->get_services_to_page($services);
        foreach ($services as $key => $service) {
            $parent = $service->id;
            $block_title = $service->title ? $service->title : $service->name;
            if ($service->option) $options[] = $service->id;

            unset($services[$key]);

            $isChildren = false;
            foreach ($services as $service) {
                if ($service->parent == $parent && !in_array($service->parent, $options)) $isChildren = true;
            }

            if ($isChildren) {
                $services_block .= $this->get_services_to_page($services, false, $parent, $block_title);
            }
        }

        return $services_block;
    }

    public function get_options_to_booking_page()
    {
        $services_block = '';

        $db = Database::get_instance();
        $services = $db->get_all_services();
        if (!$services) return $services_block;

        $options = [];
        $services = $db->get_all_services();
        foreach ($services as $key => $service) {
            $parent = $service->id;
            $block_title = $service->title ? $service->title : $service->name;
            if ($service->option) $options[] = $service->id;

            unset($services[$key]);

            $isChildren = false;
            foreach ($services as $service) {
                if ($service->parent == $parent && in_array($service->parent, $options)) $isChildren = true;
            }

            if ($isChildren) {
                $services_block .= $this->get_services_to_page($services, true, $parent, $block_title);
            }
        }

        return $services_block;
    }


    // 
    //      SETTINGS BOOKING FORM 
    //
    public function settings_form()
    {
        $form_action = esc_url(admin_url('admin-post.php'));

        $template = file_get_contents(plugins_url('/include/template/admin/setting_page.php', dirname(__FILE__)));
        $template = str_replace('[parent_list]', $this->get_parent_list(), $template);
        $template = str_replace('[managers_list]', $this->get_managers_list(), $template);
        $template = str_replace('[services_form]', $this->get_services_form(), $template);
        $template = str_replace('[form_action]', $form_action, $template);

        // $modal = do_shortcode('[booking_modal_form]');
        // $template = str_replace('[booking_modal_form]', $modal, $template);

        echo $template;
    }

    public function booking_settings_action()
    {
        if (isset($_POST)) $this->update_options($_POST);
        wp_safe_redirect(admin_url('admin.php?page=booking-options'));
    }

    public function add_booking_services_action()
    {
        $back = admin_url('admin.php?page=booking-options');

        if (empty($_POST['action']) || empty($_POST['new_service_name'])) wp_safe_redirect($back);

        $action = $_POST['action'] ?? '';
        if ($action !== 'add_booking_services') wp_safe_redirect($back);

        $db = Database::get_instance();
        $save = $db->add_booking_services($_POST);

        wp_safe_redirect($back);
    }

    public function get_services_form()
    {
        $form = '';

        $db = Database::get_instance();
        $services = $db->get_all_services();
        if (!$services) return $form;

        $form = 
            "<form method='post' action='[form_action]' class='validate'>
                [services_block]
                <div class='booking_submit'>
                    <button type='submit'>
                        Update options
                    </button>
                </div>
                <input type='hidden' name='action' value='booking_settings'>
            </form>";

        $services_block = '';
        $services_block .= $this->get_services_form_field($services);
        foreach ($services as $key => $service) {
                $new_parent = $service->id;
                $h3 = $service->title ? $service->title : $service->name;
                unset($services[$key]);
                $isChildren = false;
                foreach ($services as $service) {
                    if ($service->parent == $new_parent) $isChildren = true;
                }
                if ($isChildren) {
                    $services_block .= $this->get_services_form_field($services, $new_parent, $h3);
                }
        }

        $form = str_replace('[services_block]', $services_block, $form);

        return $form;
    }

    /*  
    *********************************************************************************************
    WooCommerce order  
    *********************************************************************************************
    */
    public function create_wc_order($total)
    {
        global $woocommerce;

        $order = wc_create_order();

        $consultation = get_page_by_title('Booking consultation', OBJECT, 'product');
        if (!$consultation) return 0;

        $product = get_product($consultation->ID);

        $order->add_product($product, 1, [
            'total' => $total,
        ]);

        $order->calculate_totals();
        $order->save();

        return $order;
    }

    function change_booking_status_payed( $order_id ){
        $db = Database::get_instance();
        $db->change_booking_order_to($order_id, 'payed');
    }

    function checkout_success_order_paid ($order_id)
    {
        $db = Database::get_instance();
        $db->change_booking_order_to($order_id, 'payed');
    }
    /*  
    *********************************************************************************************
    SETTINGS / MENU  
    *********************************************************************************************
    */
    public function menu_settings_page()
    {
        if (function_exists('add_menu_page'))
        {
            add_menu_page(
                'Booking options',
                'Booking', 
                'manage_options', 
                'booking-options',
                [Functions::get_instance(), 'settings_form'],
                'dashicons-clipboard',
                3
            );
        }

        if (function_exists('add_options_page'))
        {
            add_submenu_page( 
                'booking-options',
                'Options',
                'Options', 
                'manage_options', 
                'booking-options',
                [Functions::get_instance(), 'settings_form']
            );

            add_submenu_page( 
                'booking-options',
                'Statistics',
                'Statistics', 
                'manage_options', 
                'booking-statistics',
                [Functions::get_instance(), 'booking_statistics_page']
            );

            add_submenu_page( 
                'booking-options',
                'Upcoming meetings',
                'Upcoming meetings', 
                'manage_options', 
                'upcoming-meetings',
                [Functions::get_instance(), 'all_booking_page']
            );

            add_submenu_page( 
                'booking-options',
                'My appointments',
                'My appointments', 
                'edit_posts', 
                'booking-my-appointments',
                [Functions::get_instance(), 'my_appointments_page']
            );

            add_submenu_page( 
                'booking-options',
                'My calendar',
                'My calendar', 
                'edit_posts', 
                'booking-my-calendar',
                [Functions::get_instance(), 'my_calendar_page']
            );
        }
    }

    /*  
    *********************************************************************************************
    PAGES  
    *********************************************************************************************
    */

    public function all_booking_page()
    {
        $template = file_get_contents(plugins_url('/include/template/statistics/all_booking.php', dirname(__FILE__)));

        $db = Database::get_instance();

        // table header
        $table_header = $db::get_table_header();
        $services_header = $db->get_services_id_name();
        $t_header = '';
        foreach ($table_header as $item) {
            $t_header .= '<th>'.ucfirst(str_replace('_', ' ', str_replace('id', 'ID', $item))).'</th>';
        }
        foreach ($services_header as $item) {
            $t_header .= '<th>'.ucfirst(str_replace('_', ' ', str_replace('id', 'ID', $item))).'</th>';
        }
        $template = str_replace('[table_header]', $t_header, $template);

        // table body
        $all_booking = $db->get_active_booking();
        $all_services = $db->get_all_booking_services();

        if ($all_booking) {
            $t_body = '';
            foreach ($all_booking as $booking) {
                $t_body .= '<tr id="booking_id_'.$booking->id.'">';
                    foreach ($table_header as $field) {
                        $t_body .= '<td>' .  $booking->$field . '</td>';
                    }
                        foreach ($services_header as $id => $services) {
                            if (array_key_exists($booking->id, $all_services)) {
                                if (array_key_exists($id, $all_services[$booking->id])) {
                                    $t_body .= '<td>' .  implode(', ', $all_services[$booking->id][$id]) . '</td>';
                                } else {
                                    $t_body .= '<td></td>';
                                }
                            } else {
                                $t_body .= '<td></td>';
                            }
                        }
                $t_body .= '</tr>';
            }
            $template = str_replace('[table_body]', $t_body, $template);
        }

        $ajax_url = admin_url("admin-ajax.php");
        $template = str_replace('[ajax_url]', $ajax_url, $template);

        echo $template;
    }

    public function booking_statistics_page()
    {
        $db = Database::get_instance();
        $booked_appointments = $db->get_booked_appointments();
        $must_used_services = $db->get_services_count();

        $template = file_get_contents(plugins_url('/include/template/statistics/statistics_page.php', dirname(__FILE__)));

        $t_body = '<tr><td></td><td></td><td></td></tr>';
        if ($booked_appointments) {
            $t_body = '';
            foreach ($booked_appointments as $item) {
                $t_body .= '<tr>';
                    $t_body .= '<td>' .  $item->date . '</td>';
                    $t_body .= '<td>' .  $item->r_count . '</td>';
                    $t_body .= '<td>' .  $item->s_count . '</td>';
                $t_body .= '</tr>';
            }
        }

        $template = str_replace('[t_booked_appointments_body]', $t_body, $template);

        $t_body = '<tr><td></td><td></td></tr>';
        if ($must_used_services) {
            $t_body = '';
            foreach ($must_used_services as $item) {
                $other_parent = '';
                if (strcasecmp($item->name, 'other') === 0 || strcasecmp($item->name, 'others') === 0) $other_parent = " (".$this->get_parent_name($item->service_id).")";

                $t_body .= '<tr>';
                    $t_body .= '<td>' .  $item->name . $other_parent . '</td>';
                    $t_body .= '<td>' .  $item->count . '</td>';
                $t_body .= '</tr>';
            }
        }

        $template = str_replace('[t_muse_used_services_body]', $t_body, $template);

        echo $template;
    }

    public function my_appointments_page()
    {
        $template = file_get_contents(plugins_url('/include/template/manager/my_appointments.php', dirname(__FILE__)));

        $db = Database::get_instance();

        // table header
        $table_header = $db::get_table_header();
        $services_header = $db->get_services_id_name();
        $t_header = '';
        foreach ($table_header as $item) {
            $t_header .= '<th>'.ucfirst(str_replace('_', ' ', str_replace('id', 'ID', $item))).'</th>';
        }
        foreach ($services_header as $item) {
            $t_header .= '<th>'.ucfirst(str_replace('_', ' ', str_replace('id', 'ID', $item))).'</th>';
        }
        $template = str_replace('[table_header]', $t_header, $template);

        $current_user_id = get_current_user_id();

        // table body
        $all_booking = $db->get_active_booking($current_user_id);
        $all_services = $db->get_all_booking_services();

        if ($all_booking) {
            $t_body = '';
            foreach ($all_booking as $booking) {
                $t_body .= '<tr id="booking_id_'.$booking->id.'">';
                    foreach ($table_header as $field) {
                        $t_body .= '<td>' .  $booking->$field . '</td>';
                    }
                    foreach ($services_header as $id => $services) {
                        if (array_key_exists($booking->id, $all_services)) {
                            if (array_key_exists($id, $all_services[$booking->id])) {
                                $t_body .= '<td>' .  implode(', ', $all_services[$booking->id][$id]) . '</td>';
                            } else {
                                $t_body .= '<td></td>';
                            }
                        } else {
                            $t_body .= '<td></td>';
                        }
                    }
                $t_body .= '</tr>';
            }
            $template = str_replace('[table_body]', $t_body, $template);
        }

        $ajax_url = admin_url("admin-ajax.php");
        $template = str_replace('[ajax_url]', $ajax_url, $template);

        // if (!current_user_can('administrator')) {
            $template = str_replace(['NOT_ADMIN--->', '<!---NOT_ADMIN'], '', $template);
        // }

        echo $template;
    }

    public function my_calendar_page()
    {
        $this->booking_css_sctipt();

        $template = file_get_contents(plugins_url('/include/template/manager/calendar.php', dirname(__FILE__)));

        $db = Database::get_instance();

        // table header
        $table_header = $db::get_table_header();
        $services_header = $db->get_services_id_name();
        $blocks = $db->get_manager_availability(get_current_user_id());

        $b_dates = [];
        $b_datetimes = [];
        if ($blocks) {
            foreach ($blocks as $item) {
                if ($item['time']) {
                    $b_datetimes[] = $item['date'].'|'.$item['time'];
                } else {
                    $b_dates[] = $item['date'];
                }
            }
            $b_dates = array_values(array_unique($b_dates));
            $b_datetimes = array_values(array_unique($b_datetimes));
        }

        $template = str_replace('[b_dates]', json_encode($b_dates), $template);
        $template = str_replace('[b_datetimes]', json_encode($b_datetimes), $template);


        $active_booking = $db->get_active_booking(get_current_user_id());
        $m_dates = [];
        $m_datetimes = [];
        if ($active_booking) {
            foreach ($active_booking as $item) {
                $m_datetimes[] = $item->date.'|'.$item->time;
                $m_dates[] = $item->date;
            }
            $m_dates = array_values(array_unique($m_dates));
            $m_datetimes = array_values(array_unique($m_datetimes));
        }

        $template = str_replace('[m_dates]', json_encode($m_dates), $template);
        $template = str_replace('[m_datetimes]', json_encode($m_datetimes), $template);

        $t_header = '';
        foreach ($table_header as $item) {
            $t_header .= '<th>'.ucfirst(str_replace('_', ' ', str_replace('id', 'ID', $item))).'</th>';
        }
        foreach ($services_header as $item) {
            $t_header .= '<th>'.ucfirst(str_replace('_', ' ', str_replace('id', 'ID', $item))).'</th>';
        }
        $template = str_replace('[table_header]', $t_header, $template);

        $current_user_id = get_current_user_id();

        // table body
        $all_booking = $db->get_active_booking($current_user_id);
        $all_services = $db->get_all_booking_services();

        if ($all_booking) {
            $t_body = '';
            foreach ($all_booking as $booking) {
                $t_body .= '<tr id="booking_id_'.$booking->id.'">';
                    foreach ($table_header as $field) {
                        $t_body .= '<td>' .  $booking->$field . '</td>';
                    }
                        foreach ($services_header as $id => $services) {
                            if (array_key_exists($booking->id, $all_services)) {
                                if (array_key_exists($id, $all_services[$booking->id])) {
                                    $t_body .= '<td>' .  implode(', ', $all_services[$booking->id][$id]) . '</td>';
                                } else {
                                    $t_body .= '<td></td>';
                                }
                            } else {
                                $t_body .= '<td></td>';
                            }
                        }
                $t_body .= '</tr>';
            }
            $template = str_replace('[table_body]', $t_body, $template);
        }

        $ajax_url = admin_url("admin-ajax.php");
        $template = str_replace('[ajax_url]', $ajax_url, $template);

        $is_admin = '<br>';
        if (current_user_can('administrator')) {
            $is_admin .= '<br>';
            $is_admin .= '<select name="manager_id" id="managers_list">';
            $is_admin .= $this->get_managers_list();
            $is_admin .= '</select>';
            $is_admin .= '<button type="button" id="show_manager_calendar">Show</button>';
            $is_admin .= '<br>';
        }
        $is_admin .= '<br>';
        $template = str_replace('[is_admin]', $is_admin, $template);

        echo $template;
    }

    public function save_reservate_datetimes()
    {
        $action = $_POST['action'] ?? '';
        if ($action !== 'save_reservate_datetimes') {echo json_encode(['status' => false]); wp_die();}

        $db = Database::get_instance();

        $dates = $_POST['dates'] ?? [];
        $datetimes = $_POST['times'] ?? [];
        $user_id = $_POST['user'] && $_POST['user'] > 0 ? $_POST['user'] : get_current_user_id();
        
        $db->delete_manager_availability($user_id);

        foreach ($datetimes as $item) {
            $datetime = explode('|', $item);
            $result = $db->add_manager_availability([
                'manager_id' => $user_id,
                'date' => $datetime[0],
                'time' => $datetime[1],
            ]);
            if (!$result) {echo json_encode(['status' => false]); wp_die();}
        }

        foreach ($dates as $date) {
            $result = $db->add_manager_availability([
                'manager_id' => $user_id,
                'date' => $date,
            ]);
            if (!$result) {echo json_encode(['status' => false]); wp_die();}
        }

        echo json_encode(['status' => true]);
        wp_die();
    }

    public function show_manager_calendar()
    {
        $action = $_POST['action'] ?? '';
        if ($action !== 'show_manager_calendar') {echo json_encode(['status' => false]); wp_die();}

        $db = Database::get_instance();

        $user_id = $_POST['user'] && $_POST['user'] > 0 ? $_POST['user'] : get_current_user_id();
        
        $blocks = $db->get_manager_availability($user_id);

        $b_dates = [];
        $b_datetimes = [];
        if ($blocks) {
            foreach ($blocks as $item) {
                if ($item['time']) {
                    $b_datetimes[] = $item['date'].'|'.$item['time'];
                } else {
                    $b_dates[] = $item['date'];
                }
            }
            $b_dates = array_values(array_unique($b_dates));
            $b_datetimes = array_values(array_unique($b_datetimes));
        }

        $active_booking = $db->get_active_booking($user_id);
        $m_dates = [];
        $m_datetimes = [];
        if ($active_booking) {
            foreach ($active_booking as $item) {
                $m_datetimes[] = $item->date.'|'.$item->time;
                $m_dates[] = $item->date;
            }
            $m_dates = array_values(array_unique($m_dates));
            $m_datetimes = array_values(array_unique($m_datetimes));
        }

        echo json_encode([
            'status' => true,
            'b_dates' => json_encode($b_dates),
            'b_datetimes' => json_encode($b_datetimes),
            'm_dates' => json_encode($m_dates),
            'm_datetimes' => json_encode($m_datetimes),
        ]);
        wp_die();
    }

    public function show_booking_calendar_disabled()
    {
        $action = $_POST['action'] ?? '';
        if ($action !== 'show_booking_calendar_disabled') {echo json_encode(['status' => false]); wp_die();}

        $db = Database::get_instance();
        $all_services = $db->get_all_services();

        $form = $_POST['form'];
        $select_services = [];

        if ($form) {
            foreach ($form as $item) {
                if (stripos($item['name'], 'll-ser') !== false && $item['value'])
                    $select_services[] = $item['name'];
            }
        }

        $user_ids = [];

        if ($select_services) {
            foreach ($all_services as $item) {
                if (in_array($item->slug, $select_services))
                    $user_ids[] = $item->manager_id;
            }
        }

        $user_ids = array_values(array_unique($user_ids));

        $b_dates = [];
        $b_datetimes = [];

        if ($user_ids) {
            $today = date('Y.m.d');
            foreach ($user_ids as $id) {
                $blocks = $db->get_manager_availability($id);
                if ($blocks) {
                    foreach ($blocks as $item) {
                        if ($item['time']) {
                            $b_datetimes[] = $item['date'].'|'.$item['time'];
                        } else {
                            if ($today <= $item['date'])
                                $b_dates[] = $item['date'];
                        }
                    }
                }
            }
        }

        $b_dates = array_values(array_unique($b_dates));
        $b_datetimes = array_values(array_unique($b_datetimes));

        echo json_encode([
            'status' => true,
            'b_dates' => json_encode($b_dates),
            'b_datetimes' => json_encode($b_datetimes),
        ]);
        wp_die();
    }

    /*  
    *********************************************************************************************
        GETTERS / SETTERS  
    *********************************************************************************************
    */

    public function update_options($data)
    {
        if (!$data) return;

        $db = Database::get_instance();
        $db->update_booking_services($data);

        return;
    }

    public function get_parent_name($id)
    {
        $db = Database::get_instance();
        $name = $db->get_parent_name($id);

        if (!$name) return '';
        return $name;
    }

    public function get_parent_list()
    {
        $select = '';

        $db = Database::get_instance();
        $services = $db->get_all_services();

        if (!$services) return $select;

        $select .= $this->add_parent_list_lvl($services);
        
        return $select;
    }

    public function get_managers_list()
    {
        $users = get_users();

        $select = '';
        foreach ($users as $key => $user) {
            $select .= "<option value='$user->id'>$user->display_name</option>";
        }

        return $select;
    }
    
    public function add_parent_list_lvl(&$services, $lvl = 0, $parent = 0)
    {
        $str = '';
        $pre = str_repeat('--', $lvl);
        foreach ($services as $key => $service) {
            if ($service->parent == $parent) {
                $str .= "<option value='$service->id'>$pre $service->name</option>";
                $new_parent = $service->id;
                unset($services[$key]);
                $str .= $this->add_parent_list_lvl($services, $lvl+1, $new_parent);
            }
        }
        return $str;
    }

    public function get_services_form_field(&$services, $parent = 0, $s_title = 'Services')
    {
        $managers = get_users();

        $str = "<h1 class='admin-booking-group-title'>$s_title</h1><br>";
        $str .= '<div class="admin_booking_form_list">';
            foreach ($services as $key => $service) {
                if ($service->parent == $parent) {
                    $btn_id = rand(100,999);
                    $selected_check = '';
                    $selected_radio = '';

                    $service->btn_type == 'radio' ? $selected_radio = 'selected' : $selected_check = 'selected' ;
                    $service->option == 1 ? $isOption = 'checked' : $isOption = '' ;
                    $service->option == 1 ? $manager_disabled = 'disabled' : $manager_disabled = '' ;

                    $str .= '<div class="admin-booking-field-title" id="title-for-'.$service->slug.'">';
                        $str .= "<h3>$service->name</h3>";
                    $str .= "</div>";
                    $str .= '<div class="admin-services">';
                        $str .= "<label>Title</label>";
                        $str .= "<input name='$service->slug-title' type='text' value='$service->title'>";
                    $str .= "</div>";
                    $str .= '<div class="admin-services">';
                        $str .= "<label>Price</label>";
                        $str .= "<input name='$service->slug-price' type='text' value='$service->price' syze='2'>";
                    $str .= "</div>";
                    $str .= '<div class="admin-services">';
                        $str .= "<label>Is option?</label>";
                        $str .= "<input name='$service->slug-option' type='checkbox' value='1' $isOption>";
                    $str .= "</div>";
                    $str .= '<div class="admin-services">';
                        $str .= "<label>Button type</label>";
                        $str .= "<select name='$service->slug-btn_type' id='btn_type'>";
                            $str .= "<option value='checkbox' $selected_check>Checkbox</option>";
                            $str .= "<option value='radio' $selected_radio>Radio</option>";
                        $str .= "<select>";
                    $str .= "</div>";

                    $str .= '<div class="admin-services">';
                        $str .= "<label>Manager</label>";
                        $str .= "<select name='$service->slug-manager' id='manager' $manager_disabled>";
                            foreach ($managers as $user) {
                                $service->manager_id == $user->id ? $selected_manager = 'selected' : $selected_manager = '' ;
                                $str .= "<option value='$user->id' $selected_manager>$user->display_name</option>";
                            }
                        $str .= "<select>";
                    $str .= "</div>";

                    $str .= 
                        "<button type='button' class='delete-service' aria-label='Close' onclick='delete_service(\"$service->slug\")'>
                            <span aria-hidden='true'>&times;</span>
                        </button>";
                }
            }
        $str .= '</div>';
        return $str;
    }

    public function get_services_to_page(&$services, $isOption = false, $parent = 0, $s_title = 'Choose a service(s)')
    {
        $hidden = "hidden";
        if ($isOption) {
            $hidden = "";
        }

        $parent == 0 ? $str = "<div id='child-$parent'>" : $str = "<div id='child-$parent' $hidden>";
            $str .= /* $isOption ? */ "<h4><label>$s_title <strong>*</strong></label></h4>"/*  : "<label>$s_title</label>" */;
            $str .= "<label id='err-$parent' style='display:none;' class='valid_err'>Please select at least one item</label>";
            $str .= "<ul>";
                foreach ($services as $key => $service) {
                    if ($service->parent == $parent && $service->option == $isOption /* && $service->price > 0 */) {
                        $show_price = '';
                        if ($service->price > 0) $show_price = "<span>+$$service->price</span>";

                        $str .= "<li>";

                            if ($service->btn_type == 'radio') {
                                $str .= "<input name='radio-group-$parent' type='radio' value='$service->name' id='services_$service->id' data-parent_id='$service->parent' data-price='$service->price'>";
                            } else {
                                $str .= "<input name='$service->slug' type='checkbox' value='$service->name' id='services_$service->id' data-parent_id='$service->parent' data-price='$service->price'>";
                            }

                            $str .= "<label for='services_$service->id'>$service->name$show_price</label>";

                            if (strcasecmp($service->name, 'other') === 0 || strcasecmp($service->name, 'others') === 0) {
                                $str .= "<input name='$service->slug"."_value' type='text' value='' data-parent_id='$service->parent' style='display:none;'>";
                            }

                        $str .= "</li>";
                    }
                }
            $str .= '</ul>';
        $str .= '</div>';
        return $str;
    }

    function delete_service(){
        $slug = ($_POST['slug']) ?? '';
    
        if (!$slug) {
            echo false;
            wp_die();
        }
    
        $db = Database::get_instance();
        $slugs = $db->delete_booking_services($slug);

        echo json_encode($slugs);
        wp_die();
    }

    public static function add_options()
    {
        foreach (self::$default_options_price as $option => $value) {
            add_option($option, $value);
        }
    }

    public function get_booking_details()
    {
        $id = $_POST['id'];
        if (!$id) {
            echo json_encode(['id' => $_POST['id'], 'status' => false]);
            wp_die();
        }

        $db = Database::get_instance();
        $details = $db->get_booking_details($id);

        if (!$details) {
            echo json_encode(['id' => $_POST['id'], 'status' => false]);
            wp_die();
        }

        $current_user = wp_get_current_user();
        $d_name = $current_user->display_name;
        $my_ids = [];

        $show_btn = true;
        $rows = '';
        foreach ($details as $item) {
            $manager = $item['manager'] ? $item['manager'] : '---';
            $status = $item['status'] ? $item['status'] : 'pending';
            $rows .= 
                "<tr>
                    <td>{$item['value']}</td>
                    <td>{$manager}</td>
                    <td id='my_id_{$item['id']}'>{$status}</td>
                </tr>";

            if ($manager == $d_name) {
                $my_ids[] = $item['id'];
                if ($status !== 'pending') $show_btn = false;
            }
        }

        echo json_encode([
            'id' => $_POST['id'],
            'rows' => $rows,
            'my_ids' => implode(',', $my_ids),
            'show_btn' => $show_btn,
            'status' => true
        ]);
        wp_die();
    }

    public function update_details_statuses()
    {
        $action = $_POST['action'] ?? '';
        if ($action !== 'update_details_statuses') {echo json_encode(['status' => false]); wp_die();}
        if (!$_POST['my_ids']) {echo json_encode(['status' => false]); wp_die();}
        
        $newStatus = strtolower($_POST['status']);
        $booking_id = $_POST['id'];
        $ids = explode(',', $_POST['my_ids']);

        $db = Database::get_instance();
        foreach ($ids as $id) {
            $db->change_booking_detail_to($id, $newStatus);
        }

        if ($newStatus == 'reject') {
            $db->change_booking_status_to($booking_id, $newStatus);
            $this->reject_reservation_mail($booking_id);
        } 
        elseif ($newStatus == 'accept') {
            $accept = $db->check_all_accept_services($booking_id);
            if ($accept) {
                $db->change_booking_status_to($booking_id, $newStatus);
                $this->payment_link_mail($booking_id);
            }
        }

        echo json_encode([
            'status' => true
        ]);
        wp_die();
    }

    /*  
    *********************************************************************************************
    ACTIVATE PLUGIN
    *********************************************************************************************
    */

    public static function set_product()
    {
        $title = 'Booking consultation';
        $post = get_page_by_title($title, OBJECT, 'product');
        if ($post) return;

        if(!class_exists('WC_Product')){
            include(WP_PLUGIN_DIR.'/woocommerce/includes/abstract/abstract-wc-product.php');// adjust the link
        }

        $objProduct = new \WC_Product();

        $objProduct->set_name($title);
        $objProduct->set_status('publish');  // can be publish,draft or any wordpress post status
        $objProduct->set_catalog_visibility('visible'); // add the product visibility status
        $objProduct->set_description('');
        $objProduct->set_sku(''); //can be blank in case you don't have sku, but You can't add duplicate sku's
        // $objProduct->set_price(10.55); // set product price
        // $objProduct->set_regular_price(10.55); // set product regular price
        $objProduct->set_manage_stock(false); // true or false
        // $objProduct->set_stock_quantity(10);
        $objProduct->set_stock_status('instock'); // in stock or out of stock value
        $objProduct->set_backorders('no');
        $objProduct->set_reviews_allowed(true);
        $objProduct->set_sold_individually(true);

        $category = get_term_by( 'slug', 'uncategorized', 'product_cat' );
        $cat_id = $category->term_id;
        $objProduct->set_category_ids([$cat_id]); 

        $objProduct->save();
        return;
    }

    /*  
    *********************************************************************************************
        SEND MAIL
    *********************************************************************************************
    */
    public function reservation_mail($data)
    {
        $db = Database::get_instance();
        $services = $db->get_services();
        $all_services = $db->get_all_services();

        $managers_id = [];
        $booking_list = '';

        foreach ($services as $item) {
            if (isset($data[$item['slug']])) {
                $managers_id[] = $item['manager_id'];

                $other_parent = '';
                if (strcasecmp($item['name'], 'other') === 0 || strcasecmp($item['name'], 'others') === 0) 
                    $other_parent = ' ('.$this->get_parent_name($item['parent']).')';

                if (strcasecmp($value, 'other') === 0 || strcasecmp($value, 'others') === 0 && isset($data[$item['slug'].'_value']))
                    $other_parent .= ': ' . $data[$item['slug'].'_value'];

                $booking_list .= "<li>{$item['name']} $other_parent</li>";
            }
        }

        $options_list = '';
        foreach ($all_services as $item) {
            if ($item->option == 1) {
                $group = 'radio-group-'.$item->id;
                if ($data[$group]) {
                    $value = $data[$group];
                    $options_list .= "<li><b>{$item->name}</b>: $value</li>";
                }
            }
        }

        $managers_id = array_unique($managers_id);
        
        $emails = [];
        foreach ($managers_id as $id) {
            $user = get_userdata($id);
            if ($user) $emails[] = $user->user_email;
        }

        $template = file_get_contents(plugins_url('/include/template/mails/reservation.html', dirname(__FILE__)));
        $template = str_replace('[reservation_no]', $data['reservation_id'], $template);
        $template = str_replace('[booking_list]', $booking_list, $template);
        $template = str_replace('[options_list]', $options_list, $template);
        $template = str_replace('[date-time]', $data['date'] . ' ' . $data['time'], $template);
        $template = str_replace('[booking-my-appointments]', admin_url('/admin.php?page=booking-my-appointments'), $template);

        $send_to = implode(', ', $emails);
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($send_to, 'E&S Group | New reservation', $template, $headers);
        // wp_mail('handbat0@yandex.ru, handbat0@gmail.com', 'E&S Group | New reservation', $template, $headers);
        return;
    }

    public function payment_link_mail($id)
    {
        $db = Database::get_instance();
        $booking = $db->get_booking($id);
        $details = $db->get_booking_details($id);
        $all_services = $db->get_all_services();

        $options = [];
        $services = [];
        foreach ($all_services as $item) {
            if ($item->option == 1) {
                $options[] = $item->parent . '.' . $item->name;
            } else {
                $services[] = $item->parent . '.' . $item->name;
            }
        }

        $booking_list = '';
        $options_list = '';
        foreach ($details as $item) {
            if (in_array($item['service_id'] . '.' . $item['original_value'], $services)) {
                $booking_list .= "<li>{$item['value']}</li>";
            }
            else if (in_array($item['service_id'] . '.' . $item['original_value'], $options)) {
                $options_list .= "<li><b>{$db->get_parent_name($item['service_id'])}</b>: {$item['value']}</li>";
            }
        }

        $template = file_get_contents(plugins_url('/include/template/mails/payment_link.html', dirname(__FILE__)));
        $template = str_replace('[reservation_no]', $id, $template);
        $template = str_replace('[booking_list]', $booking_list, $template);
        $template = str_replace('[options_list]', $options_list, $template);
        $template = str_replace('[date-time]', $booking['date'] . ' ' . $booking['time'], $template);
        $template = str_replace('[payment_link]', $booking['payment_link'], $template);
        $template = str_replace('[total]', $booking['total'], $template);

        $send_to = $booking['email'];
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($send_to, 'E&S Group | Booking payment', $template, $headers);
        // wp_mail('handbat0@yandex.ru', 'E&S Group | Booking payment', $template, $headers);
        return;
    }

    public function reject_reservation_mail($id)
    {
        $db = Database::get_instance();
        $booking = $db->get_booking($id);
        $details = $db->get_booking_details($id);
        $all_services = $db->get_all_services();

        $options = [];
        $services = [];
        foreach ($all_services as $item) {
            if ($item->option == 1) {
                $options[] = $item->parent . '.' . $item->name;
            } else {
                $services[] = $item->parent . '.' . $item->name;
            }
        }

        $booking_list = '';
        $options_list = '';
        foreach ($details as $item) {
            if (in_array($item['service_id'] . '.' . $item['original_value'], $services)) {
                $booking_list .= "<li>{$item['value']}</li>";
            }
            else if (in_array($item['service_id'] . '.' . $item['original_value'], $options)) {
                $options_list .= "<li><b>{$db->get_parent_name($item['service_id'])}</b>: {$item['value']}</li>";
            }
        }

        $booking_page = get_page_by_title('Booking');
        if ($booking_page) {
            $booking_page = $booking_page->guid;
        } else {
            $booking_page = '';
        }

        $template = file_get_contents(plugins_url('/include/template/mails/reject_reservation.html', dirname(__FILE__)));
        $template = str_replace('[booking_list]', $booking_list, $template);
        $template = str_replace('[options_list]', $options_list, $template);
        $template = str_replace('[date-time]', $booking['date'] . ' ' . $booking['time'], $template);
        $template = str_replace('[booking_page]', $booking_page, $template);

        $send_to = $booking['email'];
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($send_to, 'E&S Group | Oops! Rebook', $template, $headers);
        // wp_mail('handbat0@yandex.ru', 'E&S Group | Oops! Rebook', $template, $headers);
        return;
    }
}


/*  
*********************************************************************************************
    HELPERS
*********************************************************************************************
*/

function log($data, $file = 'log.txt', $cont = false)
{
    $cont ? file_put_contents($file, print_r($data, true), FILE_APPEND) : file_put_contents($file, print_r($data, true));
}

function dd($data, $file = 'dd.log', $cont = false)
{
    $cont ? file_put_contents($file, print_r($data, true), FILE_APPEND) : file_put_contents($file, print_r($data, true));
}

function pre($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}