<?php
/**
 * ELLUL_SCHRANZ Theme Backend Navigation Walker
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

final class ELLUL_SCHRANZ_Menu_Manager {

	static private $markup;
	static public  $icons = array(
		// icon-font-custom
		'ifc-zoom_out', 'ifc-zoom_in', 'ifc-zip', 'ifc-xls', 'ifc-xlarge_icons', 'ifc-workstation', 'ifc-workflow','ifc-edit_image',
		'ifc-word', 'ifc-windows_client', 'ifc-wifi_logo', 'ifc-wifi_direct', 'ifc-wifi', 'ifc-whole_hand', 'ifc-week_view',
		'ifc-wedding_rings', 'ifc-wedding_photo', 'ifc-wedding_day', 'ifc-web_shield', 'ifc-web_camera', 'ifc-waypoint_map',
		'ifc-waxing_gibbous', 'ifc-waxing_crescent', 'ifc-wav', 'ifc-water', 'ifc-watch', 'ifc-washing_machine', 'ifc-warning_shield',
		'ifc-waning_gibbous', 'ifc-waning_crescent', 'ifc-wallet', 'ifc-wacom_tablet', 'ifc-vpn', 'ifc-volleyball', 'ifc-voip_gateway',
		'ifc-vkontakte', 'ifc-visa', 'ifc-virtual_mashine', 'ifc-virtual_machine', 'ifc-video_camera', 'ifc-vector', 'ifc-variable',
		'ifc-user_male4', 'ifc-user_male3', 'ifc-user_male2', 'ifc-user_male', 'ifc-user_female4', 'ifc-user_female3', 'ifc-user_female2',
		'ifc-user_female', 'ifc-USD', 'ifc-uppercase', 'ifc-upload2_filled', 'ifc-upload2', 'ifc-upload_filled', 'ifc-upload', 'ifc-update',
		'ifc-up4', 'ifc-up3', 'ifc-up2', 'ifc-up_right', 'ifc-up_left', 'ifc-up', 'ifc-unlock', 'ifc-unicast', 'ifc-undo', 'ifc-underline',
		'ifc-umbrella_filled', 'ifc-umbrella', 'ifc-type', 'ifc-txt', 'ifc-two_smartphones', 'ifc-twitter', 'ifc-tv_show', 'ifc-tv',
		'ifc-tumbler', 'ifc-ttf', 'ifc-trophy', 'ifc-treasury_map', 'ifc-trash2', 'ifc-trash', 'ifc-transistor', 'ifc-torah', 'ifc-toolbox',
		'ifc-tones', 'ifc-today', 'ifc-timezone-12', 'ifc-timezone-11', 'ifc-timezone-10', 'ifc-timezone-9', 'ifc-timezone-8', 'ifc-timezone-7',
		'ifc-timezone-6', 'ifc-timezone-5', 'ifc-timezone-4', 'ifc-timezone-3', 'ifc-timezone-2', 'ifc-timezone-1', 'ifc-timezone_utc',
		'ifc-timezone_12', 'ifc-timezone_11', 'ifc-timezone_10', 'ifc-timezone_9', 'ifc-timezone_8', 'ifc-timezone_7', 'ifc-timezone_6',
		'ifc-timezone_5', 'ifc-timezone_4', 'ifc-timezone_3', 'ifc-timezone_2', 'ifc-timezone_1', 'ifc-timezone', 'ifc-timer', 'ifc-tif',
		'ifc-thumb_up', 'ifc-thumb_down', 'ifc-this_way_up', 'ifc-text_color', 'ifc-temperature', 'ifc-tea', 'ifc-tar', 'ifc-talk', 'ifc-tails',
		'ifc-table', 'ifc-switch_camera_filled', 'ifc-switch_camera', 'ifc-switch', 'ifc-swipe_up', 'ifc-swipe_right', 'ifc-swipe_left',
		'ifc-swipe_down', 'ifc-swimming', 'ifc-surface', 'ifc-sun', 'ifc-summer', 'ifc-student', 'ifc-strikethrough', 'ifc-storm', 'ifc-stopwatch',
		'ifc-stepper_motor', 'ifc-stack_of_photos', 'ifc-stack', 'ifc-ssd', 'ifc-speedometer', 'ifc-speech_bubble', 'ifc-south_direction',
		'ifc-smartphone_tablet', 'ifc-small_lens', 'ifc-small_icons', 'ifc-slr_small_lens', 'ifc-slr_large_lens', 'ifc-slr_camera2_filled',
		'ifc-slr_camera2', 'ifc-slr_camera_body', 'ifc-slr_camera', 'ifc-slr_back_side', 'ifc-sling_here', 'ifc-sleet', 'ifc-slave', 'ifc-skype',
		'ifc-skip_to_start', 'ifc-shuffle', 'ifc-shopping_cart_loaded', 'ifc-shopping_cart_empty', 'ifc-shopping_basket', 'ifc-shop', 'ifc-shield',
		'ifc-shared', 'ifc-settings3', 'ifc-settings2', 'ifc-settings', 'ifc-server', 'ifc-sent', 'ifc-sensor', 'ifc-sell', 'ifc-SEK',
		'ifc-security_ssl', 'ifc-security_checked', 'ifc-security_aes', 'ifc-search', 'ifc-sea_waves', 'ifc-scrolling', 'ifc-screwdriver',
		'ifc-scales_of_Balance', 'ifc-sale', 'ifc-sagittarius', 'ifc-safari', 'ifc-sad', 'ifc-running_rabbit', 'ifc-running', 'ifc-run_command',
		'ifc-rugby', 'ifc-rucksach', 'ifc-rss', 'ifc-router', 'ifc-rotation_cw', 'ifc-rotation_ccw', 'ifc-rotate_to_portrait', 'ifc-rotate_to_landscape',
		'ifc-rotate_camera', 'ifc-rook', 'ifc-right3', 'ifc-right2', 'ifc-right_click', 'ifc-right', 'ifc-rfid_tag', 'ifc-rfid_signal', 'ifc-rfid_sensor',
		'ifc-rewind', 'ifc-resize', 'ifc-replay', 'ifc-repeat', 'ifc-rename', 'ifc-remove_user', 'ifc-remove_image', 'ifc-remote_working', 'ifc-reload',
		'ifc-relay', 'ifc-register_editor', 'ifc-redo', 'ifc-red_hat', 'ifc-recycle_sign_filled', 'ifc-recycle_sign', 'ifc-read_message', 'ifc-rar',
		'ifc-radio_tower', 'ifc-radar_plot', 'ifc-rack', 'ifc-quote', 'ifc-puzzle', 'ifc-put_out', 'ifc-put_in_motion', 'ifc-put_in', 'ifc-publisher',
		'ifc-psd', 'ifc-processor', 'ifc-private2', 'ifc-print', 'ifc-price_tag_usd', 'ifc-price_tag_pound', 'ifc-price_tag_euro', 'ifc-price_tag',
		'ifc-pressure', 'ifc-presentation', 'ifc-power_point', 'ifc-positive_dynamic', 'ifc-portrait_mode', 'ifc-popular_topic', 'ifc-polyline',
		'ifc-polygone', 'ifc-polygon', 'ifc-poll_topic', 'ifc-png', 'ifc-plus', 'ifc-plugin', 'ifc-pliers', 'ifc-play', 'ifc-plasmid', 'ifc-piston',
		'ifc-pinterest', 'ifc-pinch', 'ifc-pin', 'ifc-pill', 'ifc-pie_chart', 'ifc-picture', 'ifc-pickup', 'ifc-photo', 'ifc-phone2', 'ifc-phone1',
		'ifc-perforator', 'ifc-pencil_sharpener', 'ifc-pen', 'ifc-pdf', 'ifc-pawn', 'ifc-pause', 'ifc-password', 'ifc-passenger', 'ifc-paper_clip',
		'ifc-paper_clamp', 'ifc-panorama', 'ifc-paint_bucket', 'ifc-paint_basket', 'ifc-pain_brush', 'ifc-overhead_crane', 'ifc-outlook', 'ifc-outline',
		'ifc-outgoing_data', 'ifc-otf', 'ifc-osm', 'ifc-origami', 'ifc-opera', 'ifc-opened_folder', 'ifc-open_in_browser', 'ifc-online', 'ifc-one_note',
		'ifc-one_finger', 'ifc-old_time_camera', 'ifc-ogg', 'ifc-office_lamp', 'ifc-numerical_sorting_21', 'ifc-north_direction', 'ifc-night_vision',
		'ifc-new_moon', 'ifc-neutral_decision', 'ifc-negative_dynamic', 'ifc-near_me', 'ifc-nas', 'ifc-mute', 'ifc-musical', 'ifc-music_video',
		'ifc-music_record', 'ifc-music', 'ifc-multiple_smartphones', 'ifc-multiple_inputs', 'ifc-multiple_devices', 'ifc-multiple_cameras', 'ifc-multicast',
		'ifc-mpg', 'ifc-mp3', 'ifc-movie', 'ifc-moved_topic', 'ifc-move_by_trolley', 'ifc-mov', 'ifc-mouse_trap', 'ifc-mouse', 'ifc-month_view',
		'ifc-money_box', 'ifc-money_bag', 'ifc-money', 'ifc-mobile_home', 'ifc-minus', 'ifc-mind_map', 'ifc-micro2', 'ifc-micro', 'ifc-message',
		'ifc-mess_tin', 'ifc-menu', 'ifc-memory_module', 'ifc-megaphone2', 'ifc-megaphone', 'ifc-medium_volume', 'ifc-medium_icons', 'ifc-medium_battery',
		'ifc-math', 'ifc-matches', 'ifc-mastercard', 'ifc-map_marker', 'ifc-map_editing', 'ifc-map', 'ifc-male', 'ifc-magnet', 'ifc-mac_client',
		'ifc-luggage_trolley', 'ifc-lowercase', 'ifc-low_volume', 'ifc-low_battery', 'ifc-lol', 'ifc-log_cabine', 'ifc-lock_portrait', 'ifc-lock_landscape',
		'ifc-lock', 'ifc-livingroom', 'ifc-little_snow', 'ifc-little_rain', 'ifc-literature', 'ifc-list', 'ifc-linux_client', 'ifc-linkedin', 'ifc-link',
		'ifc-line_width', 'ifc-line_chart', 'ifc-line', 'ifc-like', 'ifc-lift_cart_here', 'ifc-libra', 'ifc-left3', 'ifc-left2', 'ifc-left_click', 'ifc-left',
		'ifc-lcd', 'ifc-layers', 'ifc-last_quarter', 'ifc-laser_beam', 'ifc-large_lens', 'ifc-large_icons', 'ifc-laptop', 'ifc-lantern', 'ifc-lamp',
		'ifc-knight', 'ifc-knife', 'ifc-kmz', 'ifc-kml', 'ifc-king', 'ifc-keyboard', 'ifc-key', 'ifc-keep_dry', 'ifc-jpg', 'ifc-joystick', 'ifc-jingle_bell',
		'ifc-jcb', 'ifc-java_coffee_cup_logo', 'ifc-iphone', 'ifc-ipad', 'ifc-ip_address', 'ifc-invisible', 'ifc-internet_explorer', 'ifc-internal',
		'ifc-integrated_webcam', 'ifc-integrated_circuit', 'ifc-instagram', 'ifc-infrared_beam_sensor', 'ifc-infrared_beam_sending', 'ifc-infrared',
		'ifc-informatics', 'ifc-info', 'ifc-increase_font', 'ifc-incoming_data', 'ifc-incendiary_grenade', 'ifc-inbox', 'ifc-in_love', 'ifc-import', 'ifc-idea',
		'ifc-icq', 'ifc-hydroelectric', 'ifc-humidity', 'ifc-humburger', 'ifc-human_footprints', 'ifc-hub', 'ifc-html', 'ifc-hot_dog', 'ifc-hot_chocolate',
		'ifc-horseshoe', 'ifc-home', 'ifc-history', 'ifc-high_volume', 'ifc-high_battery', 'ifc-hex_burner', 'ifc-herald_trumpet', 'ifc-help2', 'ifc-help',
		'ifc-helicopter', 'ifc-heat_map', 'ifc-heart_monitor', 'ifc-headset', 'ifc-headphones', 'ifc-handle_with_care', 'ifc-hand_planting', 'ifc-hand_palm_scan',
		'ifc-hand', 'ifc-hammer', 'ifc-group', 'ifc-grass', 'ifc-gpx', 'ifc-gps_receiving', 'ifc-gps_disconnected', 'ifc-google_plus', 'ifc-good_decision',
		'ifc-gis', 'ifc-gift', 'ifc-gif', 'ifc-geocaching', 'ifc-geo_fence', 'ifc-generic_text', 'ifc-generic_sorting2', 'ifc-generic_sorting', 'ifc-genealogy',
		'ifc-genderqueer', 'ifc-GBP', 'ifc-gas2', 'ifc-gantt_chart', 'ifc-gallery', 'ifc-gaiters', 'ifc-fully_charged_battery', 'ifc-full_moon', 'ifc-fridge',
		'ifc-french_fries', 'ifc-four_fingers', 'ifc-forward2', 'ifc-forward', 'ifc-fork_truck', 'ifc-fork', 'ifc-football2', 'ifc-football', 'ifc-food', 'ifc-folder',
		'ifc-fog_night', 'ifc-fog_day', 'ifc-flv', 'ifc-flow_chart', 'ifc-flip_vertical', 'ifc-flip_horizontal', 'ifc-flip_flops', 'ifc-flash_light', 'ifc-flag2',
		'ifc-flag', 'ifc-first_quarter', 'ifc-firefox', 'ifc-find_user', 'ifc-filter', 'ifc-film_reel', 'ifc-filled_box', 'ifc-fb2', 'ifc-fast_forward', 'ifc-fantasy',
		'ifc-facebook', 'ifc-external_link', 'ifc-external', 'ifc-export', 'ifc-expensive', 'ifc-expand', 'ifc-exit', 'ifc-exe', 'ifc-excel', 'ifc-EUR', 'ifc-error',
		'ifc-eraser', 'ifc-epub', 'ifc-eps', 'ifc-enter', 'ifc-engineering', 'ifc-end', 'ifc-email', 'ifc-ellipse', 'ifc-electronics', 'ifc-eggs', 'ifc-edit_user',
		'ifc-edit', 'ifc-east_direction', 'ifc-earth_element', 'ifc-dribbble', 'ifc-drafting_compass', 'ifc-downpour', 'ifc-download2_filled', 'ifc-download2',
		'ifc-download_filled', 'ifc-download', 'ifc-down4', 'ifc-down2', 'ifc-down_right', 'ifc-down_left', 'ifc-down', 'ifc-double_tap', 'ifc-donut_chart',
		'ifc-domain', 'ifc-documentary', 'ifc-document', 'ifc-doctor_suitecase', 'ifc-doctor', 'ifc-doc', 'ifc-do_not_tilt', 'ifc-do_not_stack',
		'ifc-do_not_expose_to_sunlight', 'ifc-do_not_drop', 'ifc-dna_helix', 'ifc-directions', 'ifc-diamonds', 'ifc-dharmacakra', 'ifc-design', 'ifc-delete_sign',
		'ifc-delete_shield', 'ifc-delete_message', 'ifc-define_location', 'ifc-decrease_font', 'ifc-day_view', 'ifc-date_to', 'ifc-date_from', 'ifc-database_protection',
		'ifc-database_encryption', 'ifc-database_backup', 'ifc-database', 'ifc-data_in_both_directions', 'ifc-cymbals', 'ifc-cut', 'ifc-currency', 'ifc-csv', 'ifc-css',
		'ifc-crystal_ball', 'ifc-crop', 'ifc-creek', 'ifc-coral', 'ifc-copy_link', 'ifc-copy', 'ifc-control_panel', 'ifc-content', 'ifc-contacts', 'ifc-contact_card',
		'ifc-construction_worker', 'ifc-console', 'ifc-connected_no_data', 'ifc-compost_heap', 'ifc-compass2', 'ifc-compas', 'ifc-command_line', 'ifc-combo_chart',
		'ifc-comb', 'ifc-color_dropper', 'ifc-collect', 'ifc-collapse', 'ifc-coffee', 'ifc-code', 'ifc-coctail', 'ifc-clouds', 'ifc-cloud_storage', 'ifc-close_up_mode',
		'ifc-close', 'ifc-clock', 'ifc-clipperboard', 'ifc-clear_shopping_cart', 'ifc-circuit', 'ifc-chrome', 'ifc-christmas_star', 'ifc-christmas_gift',
		'ifc-chisel_tip_marker', 'ifc-child_new_post', 'ifc-checkmark', 'ifc-checked_user', 'ifc-cheap', 'ifc-charge_battery', 'ifc-change_user', 'ifc-centre_of_gravity',
		'ifc-center_direction', 'ifc-cash_receiving', 'ifc-carabiner', 'ifc-car_battery', 'ifc-capacitor', 'ifc-cannon', 'ifc-cancel', 'ifc-camping_tent',
		'ifc-camera_identification', 'ifc-camera_addon_identification', 'ifc-camera_addon', 'ifc-camcoder_pro', 'ifc-camcoder', 'ifc-calendar', 'ifc-CAD',
		'ifc-cable_release', 'ifc-business', 'ifc-bus', 'ifc-bungalow', 'ifc-bunch_ingredients', 'ifc-broadcasting', 'ifc-briefcase', 'ifc-brandenburg_gate',
		'ifc-brain_filled', 'ifc-brain', 'ifc-box2', 'ifc-box', 'ifc-border_color', 'ifc-bookmark', 'ifc-blur', 'ifc-bluetooth2', 'ifc-bluetooth', 'ifc-birthday_cake',
		'ifc-birthday', 'ifc-biotech', 'ifc-barbers_scissors', 'ifc-bar_chart', 'ifc-banknotes', 'ifc-bandage', 'ifc-ball_point_pen', 'ifc-bad_decision',
		'ifc-background_color', 'ifc-back', 'ifc-avi', 'ifc-average', 'ifc-audio_wave2', 'ifc-audio_wave', 'ifc-asc', 'ifc-armchair', 'ifc-area_chart', 'ifc-archive',
		'ifc-aquarius', 'ifc-application_shield', 'ifc-apartment', 'ifc-antiseptic_cream', 'ifc-android_os', 'ifc-ancore', 'ifc-anchor', 'ifc-ammo_tin', 'ifc-amex',
		'ifc-ambulance', 'ifc-alphabetical_sorting_za', 'ifc-alphabetical_sorting_az', 'ifc-align_right', 'ifc-align_left', 'ifc-align_justify', 'ifc-align_center',
		'ifc-alarm_clock', 'ifc-airplane_take_off', 'ifc-airplane', 'ifc-ai', 'ifc-age', 'ifc-adventures', 'ifc-adobe_photoshop', 'ifc-adobe_indesign', 'ifc-adobe_illustrator',
		'ifc-adobe_flash', 'ifc-adobe_fireworks', 'ifc-adobe_dreamweaver', 'ifc-adobe_bridge', 'ifc-administrative_tools', 'ifc-add_user', 'ifc-add_image', 'ifc-add_database',
		'ifc-zip2', 'ifc-f_tap', 'ifc-f_swipe_up', 'ifc-f_swipe_right', 'ifc-f_swipe_left', 'ifc-f_swipe_down', 'ifc-f_double_tap',
		// et-line-font
		'icon-mobile', 'icon-laptop', 'icon-desktop', 'icon-tablet', 'icon-phone', 'icon-document', 'icon-documents', 'icon-search', 'icon-clipboard', 'icon-newspaper',
		'icon-notebook', 'icon-book-open', 'icon-browser', 'icon-calendar', 'icon-presentation', 'icon-picture', 'icon-pictures', 'icon-video', 'icon-camera', 'icon-printer',
		'icon-toolbox', 'icon-briefcase', 'icon-wallet', 'icon-gift', 'icon-bargraph', 'icon-grid', 'icon-expand', 'icon-focus', 'icon-edit', 'icon-adjustments', 'icon-ribbon',
		'icon-hourglass', 'icon-lock', 'icon-megaphone', 'icon-shield', 'icon-trophy', 'icon-flag', 'icon-map', 'icon-puzzle', 'icon-basket', 'icon-envelope', 'icon-streetsign',
		'icon-telescope', 'icon-gears', 'icon-key', 'icon-paperclip', 'icon-attachment', 'icon-pricetags', 'icon-lightbulb', 'icon-layers', 'icon-pencil', 'icon-tools',
		'icon-tools-2', 'icon-scissors', 'icon-paintbrush', 'icon-magnifying-glass', 'icon-circle-compass', 'icon-linegraph', 'icon-mic', 'icon-strategy', 'icon-beaker',
		'icon-caution', 'icon-recycle', 'icon-anchor', 'icon-profile-male', 'icon-profile-female', 'icon-bike', 'icon-wine', 'icon-hotairballoon', 'icon-globe', 'icon-genius',
		'icon-map-pin', 'icon-dial', 'icon-chat', 'icon-heart', 'icon-cloud', 'icon-upload', 'icon-download', 'icon-target', 'icon-hazardous', 'icon-piechart', 'icon-speedometer',
		'icon-global', 'icon-compass', 'icon-lifesaver', 'icon-clock', 'icon-aperture', 'icon-quote', 'icon-scope', 'icon-alarmclock', 'icon-refresh', 'icon-happy', 'icon-sad',
		'icon-facebook', 'icon-twitter', 'icon-googleplus', 'icon-rss', 'icon-tumblr', 'icon-linkedin', 'icon-dribbble',
	);

	public function __construct(){
		add_filter( 'wp_edit_nav_menu_walker', array( 'ELLUL_SCHRANZ_Theme_Walker_Nav_Menu_Edit', 'get_class_name' ) );
		add_filter( 'wp_setup_nav_menu_item' , array( $this, 'extend_nav_meta' ) );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_nav_menu_item' ), 10, 3 );
		add_action( 'wp_update_nav_menu', array( $this, 'update_nav_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'widgets_init', array( $this, 'nav_widgets_init' ), 11 );
	}

	static public function get_icons(){
		if( is_null( self::$markup ) ){
			self::$markup = '
			<label for="menu-item-icon-%1$u-remove-menu-icon" data-id="edit-menu-item-icon-%1$u" title="' . esc_attr__( 'No Icon', 'ellul-schranz' ) . '">
				<i></i>
				<input type="radio" id="menu-item-icon-%1$u-remove-menu-icon" name="menu-item-icon[%1$u]" value=""/>
			</label>';
			for( $i = 0, $c = count( self::$icons ); $i < $c; $i++ ){
				$icon          = self::$icons[ $i ];
				self::$markup .= '
				<label for="menu-item-icon-%1$u-' . esc_attr( $icon ) . '" title="' . esc_attr( $icon ) . '" data-id="edit-menu-item-icon-%1$u">
					<i class="' . esc_attr( $icon ) . '"></i>
					<input type="radio" id="menu-item-icon-%1$u-' . esc_attr( $icon ) . '" name="menu-item-icon[%1$u]" value="' . esc_attr( $icon ) . '"/>
				</label>';
			}
		}
		return self::$markup;
	}

	public function extend_nav_meta( $menu_item ){
		$menu_item->icon      = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
		$menu_item->mega_menu = get_post_meta( $menu_item->ID, '_menu_item_mega_menu', true );
		$menu_item->widgetize = get_post_meta( $menu_item->ID, '_menu_item_widgetize', true );
		return $menu_item;
	}

	public function update_nav_menu_item( $menu_id = 0, $menu_item_db_id = 0, $menu_item_data = array() ){
		// ico update
		if(
			isset( $_REQUEST['menu-item-icon'], $_REQUEST['menu-item-icon'][ $menu_item_db_id ] ) &&
			( in_array( $_REQUEST['menu-item-icon'][ $menu_item_db_id ], self::$icons ) || ! $_REQUEST['menu-item-icon'][ $menu_item_db_id ] )
		){
			update_post_meta( $menu_item_db_id, '_menu_item_icon', $_REQUEST['menu-item-icon'][ $menu_item_db_id ] );
		}
		// mega menu update
		$mega_menu = isset( $_REQUEST['menu-item-mega-menu'], $_REQUEST['menu-item-mega-menu'][ $menu_item_db_id ] ) ?
			(int)(bool)$_REQUEST['menu-item-mega-menu'][ $menu_item_db_id ] :
			'';
		update_post_meta( $menu_item_db_id, '_menu_item_mega_menu', $mega_menu );
		// widgetize area update
		$widgetize = '';
		if(
			isset(
				$_REQUEST['menu-item-widgetize'], $_REQUEST['menu-item-widgetize'][ $menu_item_db_id ],
				$_REQUEST['menu-item-parent-id'][ $menu_item_db_id ], $_REQUEST['menu-item-mega-menu'][ $_REQUEST['menu-item-parent-id'][ $menu_item_db_id ] ]
			) &&
			$_REQUEST['menu-item-parent-id'][ $menu_item_db_id ] > 0 &&
			$_REQUEST['menu-item-mega-menu'][ $_REQUEST['menu-item-parent-id'][ $menu_item_db_id ] ]
		){
			$widgetize = (int)(bool)$_REQUEST['menu-item-widgetize'][ $menu_item_db_id ];
		}
		update_post_meta( $menu_item_db_id, '_menu_item_widgetize', $widgetize );
	}

	public function update_nav_menu( $menu_id ){
		$custom_navs = array();
		$menu_items  = wp_get_nav_menu_items( $menu_id );
		foreach( $menu_items as $item ){
			if( isset( $item->widgetize ) && $item->widgetize ){
				$custom_navs[ $item->ID ] = wp_strip_all_tags( $item->post_title );
			}
		}
		set_theme_mod( 'custom-nav-widgets', $custom_navs );
	}

	public function admin_enqueue( $hook ){
		if( $hook === 'nav-menus.php' ){
			$cur_uri = ELLUL_SCHRANZ_MODULES_URI . '/navigation';
			wp_enqueue_style( 'icon-font-custom', ELLUL_SCHRANZ_ASSETS_URI . '/fonts/iconfontcustom/icon-font-custom.css', null, ELLUL_SCHRANZ_VERSION );
			wp_enqueue_style( 'et-line-font', ELLUL_SCHRANZ_ASSETS_URI . '/fonts/et-line-font/style.css', null, ELLUL_SCHRANZ_VERSION );
			wp_enqueue_style( 'ellul_schranz-admin-menu', $cur_uri . '/admin-menu.css', null, ELLUL_SCHRANZ_VERSION );
			wp_enqueue_script( 'ellul_schranz-admin-menu', $cur_uri . '/admin-menu.js', array( 'jquery' ), ELLUL_SCHRANZ_VERSION, true );
			wp_localize_script( 'ellul_schranz-admin-menu', 'EdnsMenuItem', array(
				'icons'  => self::$icons,
				'markup' => self::get_icons(),
			) );
		}
	}

	public function nav_widgets_init(){
		$nav_widgets = get_theme_mod( 'custom-nav-widgets', array() );
		if( ! empty( $nav_widgets ) && $nav_widgets ){
			foreach( $nav_widgets as $widget_id => $name ){
				if( $name ){
					$widget_id = sprintf( 'custom-nav-widget-%u', $widget_id );
					$widget    = array(
						'name'          => sprintf( '%1$s %2$s', __( 'Nav:', 'ellul-schranz' ), $name ),
						'id'            => $widget_id,
						'class'         => '',
						'description'   => '',
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>',
					);
					register_sidebar( $widget );
				}
			}
		}
	}

}

new ELLUL_SCHRANZ_Menu_Manager;

final class ELLUL_SCHRANZ_Theme_Walker_Nav_Menu_Edit extends Walker_Nav_Menu {

	static function get_class_name(){
		return __CLASS__;
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			$title = sprintf( __( '%s (Invalid)', 'ellul-schranz' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			$title = sprintf( __( '%s (Pending)', 'ellul-schranz' ), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';
		?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item', 'ellul-schranz' ); ?></span></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-up"><abbr title="<?php esc_attr_e( 'Move up', 'ellul-schranz' ); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-down"><abbr title="<?php esc_attr_e( 'Move down', 'ellul-schranz' ); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e( 'Edit Menu Item', 'ellul-schranz' ); ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
						?>"><?php _e( 'Edit Menu Item', 'ellul-schranz' ); ?></a>
					</span>
				</div>
			</div>

			<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if ( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e( 'URL', 'ellul-schranz' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e( 'Navigation Label', 'ellul-schranz' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="field-title-attribute description description-wide">
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e( 'Title Attribute', 'ellul-schranz' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php _e( 'Open link in a new window/tab', 'ellul-schranz' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e( 'CSS Classes (optional)', 'ellul-schranz' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e( 'Link Relationship (XFN)', 'ellul-schranz' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e( 'Description', 'ellul-schranz' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); ?></textarea>
						<span class="description"><?php _e( 'The description will be displayed in the menu if the current theme supports it.', 'ellul-schranz' ); ?></span>
					</label>
				</p>
				<!-- Custom meta -->
				<div class="field-icon description description-wide">
					<label for="edit-menu-item-icon-<?php echo $item_id; ?>">
						<?php _e( 'Icon', 'ellul-schranz' ); ?><br />
					</label>
					<div id="edit-menu-item-icon-<?php echo $item_id; ?>" class="menu-item-icon-wrap" data-id="<?php echo $item_id; ?>">
						<?php if( $item->icon ): ?>
						<i class="<?php echo esc_attr( $item->icon ); ?>"></i>
						<?php endif; ?>
					</div>
					<div id="edit-menu-item-icon-<?php echo $item_id; ?>-search" class="menu-icon-search-wrap">
						<input type="text" class="menu-icon-search" data-id="edit-menu-item-icon-<?php echo $item_id; ?>-packages" placeholder="<?php esc_attr_e( 'Search icon...', 'ellul-schranz' ); ?>"/>
					</div>
					<div id="edit-menu-item-icon-<?php echo $item_id; ?>-packages" class="menu-item-icon-choose"></div>
				</div>
				<div class="field-mega-menu description description-wide">
					<label for="edit-menu-item-mega-menu-<?php echo $item_id; ?>">
						<?php _e( 'Mega Menu', 'ellul-schranz' ); ?><br />
						<input type="checkbox"
							id="edit-menu-item-mega-menu-<?php echo $item_id; ?>"
							class="widefat code edit-menu-item-mega-menu ios-switch"
							name="menu-item-mega-menu[<?php echo $item_id; ?>]" value="1"
							<?php echo $item->mega_menu ? ' checked="checked"' : ''; ?> />
						<div class="switch"></div>
					</label>
				</div>
				<div class="field-widgetize description description-wide">
					<label for="edit-menu-item-widgetize-<?php echo $item_id; ?>">
						<?php _e( 'Widgetize Section', 'ellul-schranz' ); ?><br />
						<input type="checkbox"
							id="edit-menu-item-widgetize-<?php echo $item_id; ?>"
							class="widefat code edit-menu-item-widgetize ios-switch"
							name="menu-item-widgetize[<?php echo $item_id; ?>]" value="1"
							<?php echo $item->widgetize ? ' checked="checked"' : ''; ?> />
						<div class="switch"></div>
					</label>
				</div>
				<!-- end Custom meta -->
				<p class="field-move hide-if-no-js description description-wide">
					<label>
						<span><?php _e( 'Move', 'ellul-schranz' ); ?></span>
						<a href="#" class="menus-move menus-move-up" data-dir="up"><?php _e( 'Up one', 'ellul-schranz' ); ?></a>
						<a href="#" class="menus-move menus-move-down" data-dir="down"><?php _e( 'Down one', 'ellul-schranz' ); ?></a>
						<a href="#" class="menus-move menus-move-left" data-dir="left"></a>
						<a href="#" class="menus-move menus-move-right" data-dir="right"></a>
						<a href="#" class="menus-move menus-move-top" data-dir="top"><?php _e( 'To the top', 'ellul-schranz' ); ?></a>
					</label>
				</p>

				<div class="menu-item-actions description-wide submitbox">
					<?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( __( 'Original: %s', 'ellul-schranz' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							admin_url( 'nav-menus.php' )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php _e( 'Remove', 'ellul-schranz' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e( 'Cancel', 'ellul-schranz' ); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}

}
