<?php

if ( !function_exists( 'maybe_drop_column' ) ) { 
    require_once ABSPATH . '/wp-admin/install-helper.php'; 
} 

/*---------- DROP OLD TABLE ------------------*/
$old_tables = [
    'reservation',
    'reservation_services',
    'reservation_legal_services',
    'reservation_accountancy',
];

foreach ($old_tables as $o_tbl) {
    $table_name = $wpdb->prefix . $o_tbl;
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}
/*---------- / DROP OLD TABLE ------------------*/

/*  
*********************************************************************************************
    BOOKING TABLE (reservation)  
*********************************************************************************************
*/
$reservation = $wpdb->prefix . 'ell_reservation';
$sql = 'CREATE TABLE ' . $reservation . '(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) DEFAULT NULL,
    `status` varchar(100) COLLATE utf8mb4_danish_ci DEFAULT NULL,'.
    /* `manager` varchar(100) COLLATE utf8mb4_danish_ci DEFAULT NULL, */ 
    '`total` float NOT NULL DEFAULT \'0\',
    `date` varchar(20) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `time` varchar(20) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `name` varchar(100) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `surname` varchar(100) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `email` varchar(75) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `mobile_phone` varchar(20) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `description` text COLLATE utf8mb4_danish_ci,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
maybe_create_table($reservation, $sql);

$sql = "ALTER TABLE $reservation DROP manager;";
maybe_drop_column($reservation, 'manager', $sql);

$sql = "ALTER TABLE $reservation ADD `payment_link` VARCHAR(500) NULL DEFAULT NULL;";
maybe_add_column($reservation, 'payment_link', $sql);

/* 
*********************************************************************************************
    SERVICES TABLE   
*********************************************************************************************
*/
$services_table = $wpdb->prefix . 'ell_booking_services';
$sql = 'CREATE TABLE ' . $services_table . '(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `parent` int(11) NOT NULL DEFAULT \'0\',
    `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `slug` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `price` float NOT NULL DEFAULT \'0\',
    `option` float NOT NULL DEFAULT \'0\',
    `btn_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
maybe_create_table($services_table, $sql);

$sql = "ALTER TABLE $services_table ADD manager_id INT NULL DEFAULT NULL AFTER `id`;";
maybe_add_column($services_table, 'manager_id', $sql);

/*  
*********************************************************************************************
    RESERVATION -> SERVICES    
*********************************************************************************************
*/
$r_services = $wpdb->prefix . 'ell_reservation_services';
$sql = 'CREATE TABLE ' . $r_services . '(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reservation_id` int(11) DEFAULT NULL,
    `service_id` int(11) DEFAULT NULL,
    `value` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `reservation_id` (`reservation_id`),
    CONSTRAINT `'.$r_services.'_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `' . $reservation . '` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
maybe_create_table($r_services, $sql);

// $sql = "ALTER TABLE $r_services ADD manager_id INT NULL DEFAULT NULL AFTER `reservation_id`;";
// maybe_add_column($r_services, 'manager_id', $sql);

$sql = "ALTER TABLE $r_services DROP manager_id;";
maybe_drop_column($r_services, 'manager_id', $sql);

$sql = "ALTER TABLE $r_services ADD `status` VARCHAR(50) NULL DEFAULT NULL AFTER `value`;";
maybe_add_column($r_services, 'status', $sql);

$sql = "ALTER TABLE $r_services ADD `other_text` VARCHAR(150) NULL DEFAULT NULL AFTER `value`;";
maybe_add_column($r_services, 'other_text', $sql);


/* 
*********************************************************************************************
    MANAGER AVAILABILITY   
*********************************************************************************************
*/
$manager_availability = $wpdb->prefix . 'ell_manager_availability';
$sql = 'CREATE TABLE ' . $manager_availability . '(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `manager_id` int(11) NOT NULL DEFAULT \'0\',
    `date` varchar(20) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    `time` varchar(20) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
maybe_create_table($manager_availability, $sql);
