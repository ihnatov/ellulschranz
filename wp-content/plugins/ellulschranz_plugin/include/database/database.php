<?php 

namespace Ell;

use Ell\Functions;

class Database {

    protected static $instance = NULL;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
        self::$instance = new self;
        }
        return self::$instance;
    }

/*  
*********************************************************************************************
    ACTIVATE PLUGIN
*********************************************************************************************
*/
    public static function create_table()
    {
        global $wpdb;
        if (!function_exists('maybe_create_table')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }

        require_once(ELLULS_PLUGIN_PATH . '/include/database/migration.php');
    }

    public static function booking_page()
    {
        $page_title = 'Booking';
        $page_content = '[booking_form]';
        $page_url = 'booking';
    
        $page_check = get_page_by_title($page_title);
        $new_page = [
            'post_type' => 'page',
            'post_title' => $page_title,
            'post_content' => $page_content,
            'post_name' => $page_url,
            'post_status' => 'publish',
            'post_author' => 1,
        ];
        if(!isset($page_check->ID)){
            $new_page_id = wp_insert_post($new_page);
        } 

        $payment_page_check = get_page_by_title('Booking payment');
        $payment_page = [
            'post_type' => 'page',
            'post_title' => 'Booking payment',
            'post_content' => '[woocommerce_checkout]',
            'post_name' => 'booking-payment',
            'post_status' => 'publish',
            'post_author' => 1,
        ];
        if(!isset($payment_page_check->ID)){
            $new_page_id = wp_insert_post($payment_page);
        }
    }

/*  
*********************************************************************************************
    SETTINGS / MENU  
*********************************************************************************************
*/
    public function add_booking_services($data = [])
    {
        if (!$data) return false;

        $manager_id = $data['manager_id'] ? (int)$data['manager_id'] : null;
        $parent = $data['parent_id'] ? (int)$data['parent_id'] : 0;
        $name = $data['new_service_name'] ? strip_tags($data['new_service_name']) : 'null';
        $title = $data['new_service_title'] ? strip_tags($data['new_service_title']) : '';
        $slug = "ell-service-$parent-" . sanitize_title($name);
        $price = $data['new_service_price'] ? (int)$data['new_service_price'] : 0;
        $option = $data['new_service_option'] ? (int)$data['new_service_option'] : 0;
        $btn_type = $data['new_btn_type'] ? strip_tags($data['new_btn_type']) : 'checkbox';

        global $wpdb;

        $services_t = $wpdb->prefix . 'ell_booking_services';

        $format = ['%f', '%f', '%s', '%s', '%s', '%d', '%d', '%s'];
        $insert = [
            'manager_id' => $manager_id,
            'parent' => $parent,
            'name' => $name,
            'title' => $title,
            'slug' => $slug,
            'price' => $price,
            'option' => $option,
            'btn_type' => $btn_type,
        ];

        $wpdb->insert($services_t, $insert, $format);
        $services_id = $wpdb->insert_id;
        if (!$services_id) return false;
        return true;
    }

    public function update_booking_services($data)
    {
        if (!$data) return;

        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';

        $services = $this->get_all_services();
        foreach ($services as $service) {
            $slug = $service->slug;
            $insert = [
                'manager_id' => $data[$slug.'-manager'],
                'title' => $data[$slug.'-title'],
                'price' => $data[$slug.'-price'],
                'option' => $data[$slug.'-option'],
                'btn_type' => $data[$slug.'-btn_type'],
            ];

            $wpdb->update($services_t, $insert, ['slug' => $slug], ['%s', '%d', '%d', '%s']);
        }

        return;
    }

    public function delete_booking_services($slug)
    {
        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';

        $sql = "SELECT id, slug FROM $services_t WHERE slug='$slug'";
        $id = $wpdb->get_results($sql);

        $ids = [];
        if ($id && $id[0]->id) {
            $this->get_all_services_child_slug($id[0]->id, $ids);

            if ($ids) {
                $ids = array_unique($ids);
                foreach ($ids as $item) {
                    $wpdb->delete($services_t, ['slug' => $item]);
                    delete_option($item);
                }
            }
        }
        $wpdb->delete($services_t, ['slug' => $slug]);
        delete_option($slug);

        $slugs = $ids;
        $slugs[] = $slug;

        return $slugs;
    }

    public function get_all_services_child_slug($id, &$arr)
    {
        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';
        $sql = "SELECT * FROM $services_t WHERE parent=$id";
        $slugs = $wpdb->get_results($sql);

        if (!$slugs) return false;

        foreach ($slugs as $item) {
            $arr[] = $item->slug;
            $this->get_all_services_child_slug($item->id, $arr);
        }

        return true;
    }

    public function get_all_services()
    {
        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';
        $sql = "SELECT * FROM $services_t";
        $services = $wpdb->get_results($sql);

        if (!$services) return false;
        return $services;
    }

    public function get_booked_appointments()
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $r_services = $wpdb->prefix . 'ell_reservation_services';

        $names = $this->get_services_name_is_have_price();
        $names = '(\''.implode('\', \'', $names).'\')';

        $sql = 
            "SELECT 
                t1.date,
                count(DISTINCT t1.id) AS r_count,
                count(t2.value) AS s_count
            FROM 
                $reservation AS t1
            LEFT JOIN
                (SELECT * FROM $r_services WHERE `value` IN $names) AS t2
            ON 
                t1.id = t2.reservation_id
            GROUP BY
                date
            ORDER BY 
                date DESC";

        $data = $wpdb->get_results($sql);
        if (!$data) return false;

        return $data;
    }

    public function get_services_count()
    {
        global $wpdb;
        $r_services = $wpdb->prefix . 'ell_reservation_services';

        $names = $this->get_services_name_is_have_price();
        $names = '(\''.implode('\', \'', $names).'\')';

        $sql = 
            "SELECT 
                value AS name,
                service_id,
                count(value) AS `count`
            FROM 
                $r_services 
            WHERE 
                `value` IN $names
            GROUP BY 
                service_id , value
            ORDER BY 
                `count` DESC";

        $services_count = $wpdb->get_results($sql);

        if (!$services_count) return false;

        return $services_count;
    }

    public function get_services_name_is_have_price($onlyServices = true)
    {
        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';

        $option = '';
        if ($onlyServices) $option = '`option`=0 AND';

        $sql = "SELECT DISTINCT `name` FROM $services_t WHERE $option price>0";
        $names = $wpdb->get_results($sql);

        if (!$names) return [];

        $arr = [];
        foreach ($names as $item) {
            $arr[] = $item->name;
        }
        return $arr;
    }

    public function save_booking_form($data = [])
    {
        if (!$data) return false;

        global $wpdb;

        $reservation = $wpdb->prefix . 'ell_reservation';
        $r_services = $wpdb->prefix . 'ell_reservation_services';

        $time = $data['time'] ? strip_tags($data['time']) : '';
        if (stripos($time, ':') === 1) $time = '0'.$time;

        $res_format = ['%f','%s', '%f', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'];
        $res_data = [
            'order_id' => $data['order_id'] ? (int)$data['order_id'] : 0,
            'status' => 'pending',
            'total' => $data['total'] ? (int)$data['total'] : 0,
            'date' => $data['date'] ? strip_tags($data['date']) : '',
            'time' => $time,
            'name' => $data['name'] ? strip_tags($data['name']) : '',
            'surname' => $data['surname'] ? strip_tags($data['surname']) : '',
            'email' => $data['email'] ? strip_tags($data['email']) : '',
            'mobile_phone' => $data['phone'] ? strip_tags($data['phone']) : '',
            'description' => $data['query'] ? strip_tags($data['query']) : '',
            'payment_link' => $data['payment_link'] ? strip_tags($data['payment_link']) : '',
        ];

        $wpdb->insert($reservation, $res_data, $res_format);
        $reservation_id = $wpdb->insert_id;
        if (!$reservation_id) return false;

        foreach ($data as $field => $value) {
            $service_id = (int)$this->check_service($field);

            if ($service_id !== -1) {
                $other_text = '';
                if (strcasecmp($value, 'other') === 0 || strcasecmp($value, 'others') === 0 && isset($data[$field.'_value']))
                    $other_text = $data[$field.'_value'];

                $insert_data = [
                    'reservation_id' => $reservation_id,
                    'service_id' => $service_id,
                    'value' => strval($value),
                    'other_text' => strval($other_text)
                ];
                $wpdb->insert($r_services, $insert_data, ['%d', '%d', '%s', '%s']);
            }
        }
        return $reservation_id;
    }

    public function check_service($field)
    {
        if (stripos($field, 'll-service') !== false || stripos($field, 'adio-group') !== false) {
            global $wpdb;
            $services_t = $wpdb->prefix . 'ell_booking_services';

            if (stripos($field, 'adio-group') !== false) {
                $id = str_replace('radio-group-', '', $field);
                $sql = "SELECT * FROM $services_t WHERE id=$id";
                $services = $wpdb->get_results($sql);
                if (!$services) return -1;
                return (int)$id;
            } else {
                $sql = "SELECT * FROM $services_t WHERE slug='$field'";
                $services = $wpdb->get_results($sql);
                if (!$services) return -1;
                return (int)$services[0]->parent;
            }
        }
        return -1;
    }

    public function get_parent_name($id)
    {
        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';

        $name = 'Services';
        if ($id > 0) {
            $sql = "SELECT * FROM $services_t WHERE id='$id'";
            $name = $wpdb->get_results($sql);
            $name = $name[0]->name;
        }

        if (!$name) return '';
        return $name;
    }

    public function get_all_booking()
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $sql = "SELECT * FROM $reservation";
        $booking = $wpdb->get_results($sql);
        if (!$booking) return false;
        return $booking;
    }

    public function get_active_booking($user = 0)
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $services_t = $wpdb->prefix . 'ell_booking_services';
        $r_services = $wpdb->prefix . 'ell_reservation_services';

        $yesterday = date('Y.m.d',strtotime("-1 days"));
        $forUser = '';
        if ($user > 0) {
            $sql2 = "SELECT GROUP_CONCAT(DISTINCT CONCAT(parent,'.',name) SEPARATOR '\', \'') AS names FROM $services_t WHERE manager_id='$user' GROUP BY manager_id";
            $manager_services = $wpdb->get_results($sql2);
            if ($manager_services) {
                $names = "('{$manager_services[0]->names}')";

                $sql3 = "SELECT DISTINCT reservation_id FROM $r_services WHERE CONCAT(service_id,'.',value) IN $names GROUP BY reservation_id";
                $reservations_id = $wpdb->get_results($sql3);
                if ($reservations_id) {
                    $ids = [];
                    foreach ($reservations_id as $id) {
                        $ids[] = $id->reservation_id;
                    }
                    $ids = "(".implode($ids, ",").")";
                    $forUser = "AND id IN $ids";
                }
            }
        }

        $sql = "SELECT * FROM $reservation WHERE (status<>'reject' OR `date`>'$yesterday') $forUser";
        $booking = $wpdb->get_results($sql);
        if (!$booking) return false;
        return $booking;
    }

    public function get_all_booking_services()
    {
        global $wpdb;
        $r_services = $wpdb->prefix . 'ell_reservation_services';

        $names = $this->get_services_name_is_have_price(false);
        $names = '(\''.implode('\', \'', $names).'\')';
        $sql = "SELECT * FROM $r_services WHERE `value` IN $names";

        // $sql = "SELECT * FROM $r_services";
        $services = $wpdb->get_results($sql);
        if (!$services) return false;

        $array = [];
        foreach ($services as $item) {
            $array[$item->reservation_id][$item->service_id][] = $item->value;
        }

        return $array;
    }

    public function get_services_manager()
    {
        global $wpdb;
        $b_services = $wpdb->prefix . 'ell_booking_services';

        $sql = "SELECT * FROM $b_services";
        $services = $wpdb->get_results($sql);
        if (!$services) return [];

        $array = [];
        foreach ($services as $item) {
            $manager = get_userdata((int)$item->manager_id);
            $array[$item->parent . '.' . $item->name] = $manager ? $manager->display_name : '';
        }
        return $array;
    }

    public function get_booking_details($id)
    {
        global $wpdb;
        $r_services = $wpdb->prefix . 'ell_reservation_services';

        $names = $this->get_services_name_is_have_price();
        $names = '(\''.implode('\', \'', $names).'\')';

        $sql = "SELECT * FROM $r_services WHERE `reservation_id`=$id AND `value` IN $names";
        $services = $wpdb->get_results($sql);
        if (!$services) return [];

        $managers = $this->get_services_manager();

        $array = [];
        foreach ($services as $item) {
            $other_parent = '';
            if (strcasecmp($item->value, 'other') === 0 || strcasecmp($item->value, 'others') === 0) $other_parent = ' ('.$this->get_parent_name($item->service_id).'): '. $item->other_text;
            $array[] = [
                'id' => $item->id,
                'service_id' => $item->service_id,
                'manager' => $managers[$item->service_id . '.' . $item->value],
                'value' => $item->value . $other_parent,
                'original_value' => $item->value,
                'status' => $item->status,
                'other_text' => $item->other_text,
            ];
        }

        return $array;
    }

    public function get_booking($id)
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $sql = "SELECT * FROM $reservation WHERE `id`=$id";
        $array = $wpdb->get_results($sql, ARRAY_A);
        if (!$array) return [];
        return $array[0];
    }


    /*  
    *********************************************************************************************
    FOR FUNCTIONS 
    *********************************************************************************************
    */

    public function get_manager_availability($manager_id)
    {
        if (!$manager_id) return [];

        global $wpdb;

        $manager_availability = $wpdb->prefix . 'ell_manager_availability';
        $sql = "SELECT * FROM $manager_availability WHERE manager_id=$manager_id";
        $dates = $wpdb->get_results($sql, ARRAY_A);

        if (!$dates) return [];
        return $dates;
    }

    public function add_manager_availability($data = [])
    {
        if (!$data) return false;

        $manager_id = $data['manager_id'] ? (int)$data['manager_id'] : null;
        $date = $data['date'] ? $data['date'] : '';
        $time = $data['time'] ? $data['time'] : '';

        if (!$manager_id || !$date) return false;

        global $wpdb;

        $manager_availability = $wpdb->prefix . 'ell_manager_availability';

        $format = ['%f', '%s', '%s'];
        $insert = [
            'manager_id' => $manager_id,
            'date' => $date,
            'time' => $time,
        ];

        $wpdb->insert($manager_availability, $insert, $format);
        $services_id = $wpdb->insert_id;
        if (!$services_id) return false;
        return true;
    }

    public function update_manager_availability($data)
    {
        if (!$data) return;

        global $wpdb;
        $manager_availability = $wpdb->prefix . 'ell_manager_availability';

        $id = $data['id'];
        $date = $data['date'] ? $data['date'] : '';
        $time = $data['time'] ? $data['time'] : '';

        if (!$id || !$date) return false;

        $insert = [
            'date' => $date,
            'time' => $time,
        ];

        $wpdb->update($manager_availability, $insert, ['id' => $id], ['%s', '%s']);

        return;
    }

    public function delete_manager_availability($id)
    {
        if (!$id) return false;

        global $wpdb;
        $manager_availability = $wpdb->prefix . 'ell_manager_availability';

        $wpdb->delete($manager_availability, ['manager_id' => $id]);

        return $id;
    }

    /*  
    *********************************************************************************************
    FOR FUNCTIONS 
    *********************************************************************************************
    */

    public static function get_table_header()
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $columns = $wpdb->get_col("DESC {$reservation}", 0);

        $unset = ['payment_link'];
        foreach ($columns as $key => $column) {
            if (in_array($column, $unset)) unset($columns[$key]); 
        }

        return $columns;
    }

    public static function get_services_id_name()
    {
        global $wpdb;

        $r_services = $wpdb->prefix . 'ell_reservation_services';
        $services_t = $wpdb->prefix . 'ell_booking_services';

        $sql = "SELECT DISTINCT(service_id) AS parent FROM $r_services ORDER BY service_id ASC;";
        $services = $wpdb->get_results($sql);
        if (!$services) return $columns;

        $parent_id = [];
        $services_title = '';
        foreach ($services as $item) {
            $item->parent != 0 ? $parent_id[] = $item->parent : $services_title = 'Services';
        }

        $in = '(' . implode(',', $parent_id) . ')';
        $sql = "SELECT id, `name`, `option` FROM $services_t WHERE id IN $in ;";
        $service_names = $wpdb->get_results($sql);
        if (!$service_names) return $columns;

        $array = [];
        if ($services_title) $array[0] = $services_title;
        foreach ($service_names as $item) {
            if (!$item->option)
                $array[$item->id] = $item->name;
        }
        foreach ($service_names as $item) {
            if ($item->option)
                $array[$item->id] = $item->name;
        }

        return $array;
    }

    public function change_booking_order_to($order_id, $change_to)
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $wpdb->update( $reservation, ['status' => $change_to], ['order_id' => $order_id] );
    }

    public function change_booking_detail_to($id, $newStatus)
    {
        global $wpdb;
        $r_services = $wpdb->prefix . 'ell_reservation_services';
        $wpdb->update( $r_services, ['status' => $newStatus], ['id' => $id] );
    }

    public function change_booking_status_to($id, $newStatus)
    {
        global $wpdb;
        $reservation = $wpdb->prefix . 'ell_reservation';
        $wpdb->update( $reservation, ['status' => $newStatus], ['id' => $id] );
    }

    public function check_all_accept_services($id)
    {
        global $wpdb;
        $r_services = $wpdb->prefix . 'ell_reservation_services';
        $sql = "SELECT * FROM $r_services WHERE reservation_id=$id";
        $services = $wpdb->get_results($sql, ARRAY_A);

        $all_services = $this->get_all_services();
        $check = [];
        if ($all_services) {
            foreach ($all_services as $item) {
                if ($item->option == 0 && $item->price > 0) {
                    $check[] = $item->parent . '.' . $item->name;
                }
            }
        }

        $accept = false;
        if ($check) {
            $accept = true;
            foreach ($services as $service) {
                if (in_array($service['service_id'] . '.' . $service['value'], $check)) {
                    if ($service['status'] !== 'accept')
                        $accept = false;
                }
            }
        }

        return $accept;
    }

    public function get_services()
    {
        global $wpdb;
        $services_t = $wpdb->prefix . 'ell_booking_services';
        $sql = "SELECT * FROM $services_t";
        $services = $wpdb->get_results($sql, ARRAY_A);
        if (!$services) return [];
        return $services;
    }

}