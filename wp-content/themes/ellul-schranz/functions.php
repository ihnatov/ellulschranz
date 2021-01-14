<?php
/**
 * ELLUL_SCHRANZ Theme Core
 *
 * @package ELLUL_SCHRANZ
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$theme = wp_get_theme();

/**********************************************************************
 * Define Constants
 **********************************************************************/

define( 'ELLUL_SCHRANZ_FRAMEWORK_DIR', trailingslashit( get_template_directory() ) . 'framework' );
define( 'ELLUL_SCHRANZ_ASSETS_URI', trailingslashit( get_template_directory_uri() ) . 'assets' );
define( 'ELLUL_SCHRANZ_MODULES_DIR', trailingslashit( get_template_directory() ) . 'framework/modules' );
define( 'ELLUL_SCHRANZ_MODULES_URI', trailingslashit( get_template_directory_uri() ) . 'framework/modules' );
define( 'ELLUL_SCHRANZ_PLUGINS_DIR', trailingslashit( ELLUL_SCHRANZ_FRAMEWORK_DIR . '/plugins' ) );
define( 'ELLUL_SCHRANZ_VERSION', $theme->get( 'Version' ) );
define( 'ELLUL_SCHRANZ_DEFAULT', 0 );
define( 'ELLUL_SCHRANZ_FULLWIDTH', 1 );
define( 'ELLUL_SCHRANZ_LEFT_SIDEBAR', 2 );
define( 'ELLUL_SCHRANZ_RIGHT_SIDEBAR', 3 );
define( 'ELLUL_SCHRANZ_STYLE_1', 1 );
define( 'ELLUL_SCHRANZ_STYLE_2', 2 );
define( 'ELLUL_SCHRANZ_STYLE_3', 3 );
define( 'ELLUL_SCHRANZ_META_POST_FORMAT', 1 );
define( 'ELLUL_SCHRANZ_META_PUBLISH_TIME', 2 );
define( 'ELLUL_SCHRANZ_META_CATEGORIES', 3 );
define( 'ELLUL_SCHRANZ_META_TAGS', 4 );
define( 'ELLUL_SCHRANZ_META_ATTACHMENT', 5 );
define( 'ELLUL_SCHRANZ_META_COMMENTS', 6 );
define( 'ELLUL_SCHRANZ_META_AUTHOR', 7 );
define( 'ELLUL_SCHRANZ_META_FEATURED', 8 );

/**********************************************************************
 * Define Theme Setup Actions
 **********************************************************************/

add_action( 'after_setup_theme', 'set_content_width', 0 );
add_action( 'after_setup_theme', 'ellul_schranz_theme_setup' );
add_action( 'wp_enqueue_scripts', 'ellul_schranz_scripts_styles' );
add_action( 'wp_enqueue_scripts', 'ellul_schranz_scripts_styles_later', 11 );
add_action( 'admin_enqueue_scripts', 'ellul_schranz_admin_scripts_styles', 30 );
add_action( 'widgets_init', 'ellul_schranz_main_sidebar' );

/**********************************************************************
 * Include Config && Main Framework Files
 **********************************************************************/

require_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/config.php';
require_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/init.php';

/**********************************************************************
 * Set Theme Content Width
 **********************************************************************/

function set_content_width() {

	$GLOBALS['content_width'] = apply_filters( 'ellul_schranz_theme_content_width', 1170 );

}

/**********************************************************************
 * Theme Setup
 **********************************************************************/

function ellul_schranz_theme_setup() {

	// load textdomain
	load_theme_textdomain( 'ellul-schranz', get_template_directory() . '/languages' );

	// add editor style
	add_editor_style( [
		get_template_directory_uri() . '/assets/fonts/fontawesome/font-awesome.min.css',
		get_template_directory_uri() . '/assets/css/editor.css',
	] );

	// register nav menu
	register_nav_menus( [
		'primary' => esc_html__( 'Primary Menu', 'ellul-schranz' ),
	] );

	// add theme support
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', [
		'search-form',
		//~ 'comment-form',
		//~ 'comment-list',
		'gallery',
		'caption',
	] );
	add_theme_support( 'post-formats', [
		'aside',
		'image',
		'video',
		'quote',
		'link',
	] );
}

/**********************************************************************
 * Load Global Theme Scripts && Styles
 **********************************************************************/

function ellul_schranz_scripts_styles() {

	// CSS: Google Fonts
	wp_enqueue_style( 'ellul_schranz-google-fonts', add_query_arg(
		'family',
		urlencode(
			implode( '|', [
				'Open Sans:400,400italic,600,600italic,700,700italic,800,800italic',
				'Hind:300,400,500,600,700',
			] )
		),
		'//fonts.googleapis.com/css'
	) );

	// CSS: FontAwesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/fonts/fontawesome/font-awesome.min.css', null, ELLUL_SCHRANZ_VERSION );

	// CSS: Custom Icon Font
	wp_enqueue_style( 'icon-font-custom', get_template_directory_uri() . '/assets/fonts/iconfontcustom/icon-font-custom.css', null, ELLUL_SCHRANZ_VERSION );

	// CSS: ET Line Font
	wp_enqueue_style( 'et-line-font', get_template_directory_uri() . '/assets/fonts/et-line-font/et-line-font.css', null, ELLUL_SCHRANZ_VERSION );

	// CSS: deregister plugin components
	wp_dequeue_style( 'evca-components' );
	wp_deregister_style( 'evca-components' );

	// CSS: Template CSS
	wp_enqueue_style( 'ellul_schranz-reset', get_template_directory_uri() . '/assets/css/reset.css', null, ELLUL_SCHRANZ_VERSION );
	wp_enqueue_style( 'ellul_schranz-grid', get_template_directory_uri() . '/assets/css/grid.css', null, ELLUL_SCHRANZ_VERSION );
	wp_enqueue_style( 'ellul_schranz-elements', get_template_directory_uri() . '/assets/css/elements.css', null, ELLUL_SCHRANZ_VERSION );
	wp_enqueue_style( 'ellul_schranz-layout', get_template_directory_uri() . '/assets/css/layout.css', null, ELLUL_SCHRANZ_VERSION );
	wp_enqueue_style( 'evca-components', get_template_directory_uri() . '/assets/css/components.css', null, ELLUL_SCHRANZ_VERSION );
	wp_enqueue_style( 'ellul_schranz-wordpress', get_template_directory_uri() . '/assets/css/wordpress.css', null, ELLUL_SCHRANZ_VERSION );
	if ( function_exists( 'set_revslider_as_theme' ) ) {
		wp_enqueue_style( 'ellul_schranz-rev-slider', get_template_directory_uri() . '/assets/css/rev-slider.css', null, ELLUL_SCHRANZ_VERSION );
	}
	if ( function_exists( 'is_woocommerce' ) ) {
		wp_enqueue_style( 'ellul_schranz-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', null, ELLUL_SCHRANZ_VERSION );
	}

	// JS: Vendors
	wp_enqueue_script( 'simpleplaceholder', get_template_directory_uri() . '/assets/vendors/simpleplaceholder/jquery.simpleplaceholder.js', [ 'jquery' ], ELLUL_SCHRANZ_VERSION, true );
	wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/assets/vendors/fitvids/jquery.fitvids.js', [ 'jquery' ], ELLUL_SCHRANZ_VERSION, true );
	wp_enqueue_script( 'superfish-hoverintent', get_template_directory_uri() . '/assets/vendors/superfish/hoverIntent.js', [ 'jquery' ], ELLUL_SCHRANZ_VERSION, true );
	wp_enqueue_script( 'superfish', get_template_directory_uri() . '/assets/vendors/superfish/superfish.js', [ 'jquery' ], ELLUL_SCHRANZ_VERSION, true );
	wp_enqueue_script( 'twitter', get_template_directory_uri() . '/assets/vendors/twitter/twitterfetcher.js', [ 'jquery' ], ELLUL_SCHRANZ_VERSION, true );

	// JS: Main JS
	wp_enqueue_script( 'ellul_schranz-main', get_template_directory_uri() . '/assets/js/main.js', [ 'jquery' ], ELLUL_SCHRANZ_VERSION, true );

	// JS: Comment Reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/* OWL CAROUSEL */
	wp_enqueue_style( 'owl-carousel-style', get_template_directory_uri() . '/owlcarousel/assets/owl.carousel.min.css' );
	wp_enqueue_script( 'owl-carousel-script', get_template_directory_uri() . '/owlcarousel/owl.carousel.min.js', [ 'jquery' ], false, true );
	/* OWL CAROUSEL */

}

/**********************************************************************
 * Load Global Theme Scripts && Styles Later
 **********************************************************************/

function ellul_schranz_scripts_styles_later() {

	// CSS: deregister portfolio css
	wp_dequeue_style( 'edns-portfolio' );
	wp_deregister_style( 'edns-portfolio' );

	// portfolio css
	wp_enqueue_style( 'edns-portfolio', get_template_directory_uri() . '/assets/css/portfolio.css', null, ELLUL_SCHRANZ_VERSION );

}

/**********************************************************************
 * Load Global Admin Scripts && Styles
 **********************************************************************/

function ellul_schranz_admin_scripts_styles() {

	// CSS: admin styling
	wp_enqueue_style( 'ellul_schranz-admin', get_template_directory_uri() . '/assets/css/admin.css', null, ELLUL_SCHRANZ_VERSION );

}

/**********************************************************************
 * Register Main Sidebar
 **********************************************************************/

function ellul_schranz_main_sidebar() {

	register_sidebar( [
		'id'            => 'sidebar',
		'name'          => esc_html__( 'Main Sidebar', 'ellul-schranz' ),
		'description'   => esc_html__( 'Main Sidebar', 'ellul-schranz' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	] );

	register_sidebar( [
		'id'            => 'menu',
		'name'          => esc_html__( 'Menu', 'ellul-schranz' ),
		'description'   => esc_html__( 'Menu widgets', 'ellul-schranz' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	] );

}

/**********************************************************************
 * NIU Functions
 **********************************************************************/
function niu_adding_scripts() {
	wp_register_style( 'niu_style', get_template_directory_uri() . '/style.css', [], '4.4' );
	wp_enqueue_style( 'niu_style' );

	wp_register_script( 'niu_script', get_template_directory_uri() . '/niu.js', "jquery", false, true );
	wp_enqueue_script( 'niu_script' );

	wp_enqueue_script( 'niu_fontawesome', 'https://use.fontawesome.com/b59a1c2067.js' );
}

add_action( 'wp_enqueue_scripts', 'niu_adding_scripts' );

// Enable shortcodes in text widgets
add_filter( 'widget_text', 'do_shortcode' );


function copyright_footer_func( $atts ) {
	return date( 'Y' ) . ' All Rights Reserved E&S';

}

add_shortcode( 'copyright_footer', 'copyright_footer_func' );


function home_services_func( $atts ) {
	$atts                = shortcode_atts(
		[
			'include_news' => false,
		],
		$atts
	);
	$li_count            = 0;
	$services_categories = get_terms( [ 'taxonomy' => 'servicescategories', ] );


	ob_start();
	$ordered_sc = [];
	foreach ( $services_categories as $service_cat ) {
		$order = get_field( "service-order", $service_cat );


		$ordered_sc[ $order ] = $service_cat;
	}
	ksort( $ordered_sc, SORT_NUMERIC );


	foreach ( $ordered_sc as $service_cat ) {
		?>
        <div class="wpbwrapper">
            <ul class="niu-services-list home-services-cats <?= ( $li_count % 2 ) != 0 ? 'service_swap' : ''; ?>">
				<?php
				if ( ( $li_count % 2 ) == 0 ) {
					?>
                    <li class="home-services-images"
                        style="background-image: url('<?php the_field( "service_home_image", $service_cat ) ?>');)">
                    </li>
                    <li class="home-services-height home-services-titles">
                        <div class="home-services-titles-div"><?php echo $service_cat->name; ?></div>
                        <p class="home-services-paragraph">
							<?php echo do_shortcode( term_description( $service_cat->term_id, $service_cat->taxonomy ) ); ?>
                        </p>
                        <!--<div id="categories-list-button">
                            <a href="<?php /*echo get_term_link($service_cat->term_id, 'servicescategories') */ ?>" class="btn btn-default btn-normal btn-not-italic">Read More</a>
                        </div>-->
                        <div id="categories-list-button">
                            <a href="<?php echo get_bloginfo( 'url' ) ?>/contact"
                               class="btn btn-default btn-normal btn-not-italic">Enquire</a>
                        </div>
                    </li>
					<?php
				} else {
					?>
                    <li class="home-services-height home-services-titles">
                        <div class="home-services-titles-div"><?php echo $service_cat->name; ?></div>
                        <p class="home-services-paragraph">
							<?php echo do_shortcode( term_description( $service_cat->term_id, $service_cat->taxonomy ) ); ?>
                        </p>
                        <!--<div id="categories-list-button">
                            <a href="<?php /*echo get_term_link($service_cat->term_id, 'servicescategories') */ ?>" class="btn btn-default btn-normal">Read More</a>
                        </div>-->
                        <div id="categories-list-button">
                            <a href="<?php echo get_bloginfo( 'url' ) ?>/contact"
                               class="btn btn-default btn-normal btn-not-italic">Enquire</a>
                        </div>
                    </li>

                    <li class="home-services-images"
                        style="background-image: url('<?php the_field( "service_home_image", $service_cat ) ?>');)">
                    </li>
					<?php

				}
				?>
            </ul>
        </div>
		<?php
		$li_count ++;
	}

	if ( $atts['include_news'] == "true" ) {
		?>
        <div class="wpbwrapper">
            <ul class="niu-services-list home-services-cats <?= ( $li_count % 2 ) != 0 ? 'service_swap' : '' ?>">
				<?php

				$thumb_url = get_template_directory_uri() . '/assets/images/home_news_banner.jpg';
				if ( ( $li_count % 2 ) == 0 ) { ?>
                    <li class="home-services-images" style="background-image: url('<?= $thumb_url ?>');)">
                    </li>
                    <li class="home-services-titles home-services-height">
                        NEWS

						<?php
						get_home_posts();
						?>

                        <div id="categories-list-button">
                            <a href="<?php echo get_bloginfo( 'url' ) . '/news/' ?>" class="btn btn-default btn-normal">All
                                News</a>
                        </div>
                    </li>
					<?php
				} else {
					?>

                    <li class="home-services-titles home-services-height">
                        NEWS

						<?php
						get_home_posts();
						?>

                        <div id="categories-list-button">
                            <a href="<?php echo get_bloginfo( 'url' ) . '/news/' ?>" class="btn btn-default btn-normal">All
                                News</a>
                        </div>
                    </li>

                    <li class="home-services-images" style="background-image: url('<?= $thumb_url ?>');)">
                    </li>
					<?php

				}
				?>
            </ul>
        </div>
		<?php
	}

	return ob_get_clean();


}

add_shortcode( 'home_services', 'home_services_func' );


function get_services_by_taxonomy( $service_tax_slug ) {
	$args           = [
		'post_type' => 'service',
		'tax_query' => [
			[
				'taxonomy' => 'servicescategories',
				'field'    => 'slug',
				'terms'    => $service_tax_slug,
			],
		],
	];
	$services_query = new WP_Query( $args );

	if ( $services_query->have_posts() ) {
		echo '<ul class="hs-list">';
		while ( $services_query->have_posts() ) {
			$services_query->the_post();
			echo '<li class="home-niu-services-list"> <a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			//echo get_the_title();
		}
		echo '</ul>';

		wp_reset_postdata();
	}

}

function get_home_posts() {
	$args = [
		'post_type'      => 'post',
		'posts_per_page' => 6
	];

	$posts_query = new WP_Query( $args );

	if ( $posts_query->have_posts() ) {
		echo '<ul class="hs-list">';
		while ( $posts_query->have_posts() ) {
			$posts_query->the_post();
			echo '<li class="home-niu-services-list"> <a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			//echo get_the_title();
		}
		echo '</ul>';

		wp_reset_postdata();
	}

}

add_action( 'init', 'codex_service_init' );
function codex_service_init() {
	$labels = [
		'name'               => _x( 'Services', 'post type general name', 'ellul-schranz' ),
		'singular_name'      => _x( 'Service', 'post type singular name', 'ellul-schranz' ),
		'menu_name'          => _x( 'Services', 'admin menu', 'ellul-schranz' ),
		'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'ellul-schranz' ),
		'add_new'            => _x( 'Add New', 'service', 'ellul-schranz' ),
		'add_new_item'       => __( 'Add New Service', 'ellul-schranz' ),
		'new_item'           => __( 'New Service', 'ellul-schranz' ),
		'edit_item'          => __( 'Edit Service', 'ellul-schranz' ),
		'view_item'          => __( 'View Service', 'ellul-schranz' ),
		'all_items'          => __( 'All Services', 'ellul-schranz' ),
		'search_items'       => __( 'Search Services', 'ellul-schranz' ),
		'parent_item_colon'  => __( 'Parent Services:', 'ellul-schranz' ),
		'not_found'          => __( 'No services found.', 'ellul-schranz' ),
		'not_found_in_trash' => __( 'No services found in Trash.', 'ellul-schranz' )
	];

	$args = [
		'labels'             => $labels,
		'description'        => __( 'Description.', 'ellul-schranz' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'service' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-clipboard',
		'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt' ]
	];

	register_post_type( 'service', $args );
}


// hook into the init action and call services_cat when it fires
add_action( 'init', 'niu_services_cat', 0 );
function niu_services_cat() {

	$labels = [
		'name'              => _x( 'Services Categories', 'taxonomy general name', 'ellul-schranz' ),
		'singular_name'     => _x( 'Services Categories', 'taxonomy singular name', 'ellul-schranz' ),
		'search_items'      => __( 'Search Services Categories', 'ellul-schranz' ),
		'all_items'         => __( 'All Services Categories', 'ellul-schranz' ),
		'parent_item'       => __( 'Parent Services Categorie', 'ellul-schranz' ),
		'parent_item_colon' => __( 'Parent Services Category:', 'ellul-schranz' ),
		'edit_item'         => __( 'Edit Services Category', 'ellul-schranz' ),
		'update_item'       => __( 'Update Services Category', 'ellul-schranz' ),
		'add_new_item'      => __( 'Add New Services Category', 'ellul-schranz' ),
		'new_item_name'     => __( 'New Services Category Name', 'ellul-schranz' ),
		'menu_name'         => __( 'Services Category', 'ellul-schranz' ),
	];

	$args = [
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'servicescategories' ],
	];

	register_taxonomy( 'servicescategories', [ 'service' ], $args );
}

add_filter( 'wp_mail', 'niu_wp_mail_filter', 99 );
function niu_wp_mail_filter( $args ) {

	$new_header = [];
	//If Header is String Extract values to $new_header array
	if ( is_string( $args['headers'] ) ) {
		$headers = explode( ';', $args['headers'] );
		foreach ( $headers as $header ) {
			//Explode to get Header type ('From','Content Type') and Header Value('text/html')
			$inner_header = explode( ':', $header );
			//Remove Break Lines
			$value = trim( preg_replace( '/\s+/', ' ', $inner_header[1] ) );
			//Set Header in Array
			$new_header[ $inner_header[0] ] = $inner_header[0] . ': ' . $value;
		}
	} else {
		$new_header = $args['headers'];
	}

	//Set From header if not set
	if ( ! isset( $new_header['From'] ) ) {
		$new_header['From'] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>';
	}
	//Set Reply-To Header if not set
	if ( ! isset( $new_header['Reply-To'] ) ) {
		$new_header['Reply-To'] = 'Reply-To: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>';
	}

	//Assign New Values
	$new_wp_mail = [
		'to'          => $args['to'],
		'subject'     => $args['subject'],
		'message'     => $args['message'],
		'headers'     => $new_header,
		'attachments' => $args['attachments'],
	];

	return $new_wp_mail;
}

add_action( 'init', 'niu_testimonials_init' );
function niu_testimonials_init() {
	$labels = [
		'name'               => _x( 'Testimonials', 'post type general name', 'ellul-schranz' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'ellul-schranz' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu', 'ellul-schranz' ),
		'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'ellul-schranz' ),
		'add_new'            => _x( 'Add New', 'testimonial', 'ellul-schranz' ),
		'add_new_item'       => __( 'Add New Testimonial', 'ellul-schranz' ),
		'new_item'           => __( 'New Testimonial', 'ellul-schranz' ),
		'edit_item'          => __( 'Edit Testimonial', 'ellul-schranz' ),
		'view_item'          => __( 'View Testimonial', 'ellul-schranz' ),
		'all_items'          => __( 'All Testimonials', 'ellul-schranz' ),
		'search_items'       => __( 'Search Testimonials', 'ellul-schranz' ),
		'parent_item_colon'  => __( 'Parent Testimonials:', 'ellul-schranz' ),
		'not_found'          => __( 'No testimonials found.', 'ellul-schranz' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'ellul-schranz' )
	];

	$args = [
		'labels'             => $labels,
		'description'        => __( 'Description.', 'ellul-schranz' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'testimonial' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-media-text',
		'supports'           => [ 'title', 'editor' ]
	];

	register_post_type( 'testimonial', $args );
}

add_action( 'init', 'niu_partners_init' );
function niu_partners_init() {
	$labels = [
		'name'               => _x( 'Partners', 'Post Type General Name' ),
		'singular_name'      => _x( 'Partner', 'Post Type Singular Name' ),
		'menu_name'          => __( 'Partners' ),
		'parent_item_colon'  => __( 'Parent Partner' ),
		'all_items'          => __( 'All Partners' ),
		'view_item'          => __( 'View Partner' ),
		'add_new_item'       => __( 'Add New Partner' ),
		'add_new'            => __( 'Add New' ),
		'edit_item'          => __( 'Edit Partner' ),
		'update_item'        => __( 'Update Partner' ),
		'search_items'       => __( 'Search Partner' ),
		'not_found'          => __( 'Not Found' ),
		'not_found_in_trash' => __( 'Not found in Trash' ),
	];

	$args = [
		'label'               => __( 'partners', 'attardcowines' ),
		'description'         => __( 'Partners', 'attardcowines' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => [
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',
			'comments',
			'revisions',
			'custom-fields',
		],
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 50,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	];

	// Registering your Custom Post Type
	register_post_type( 'partners', $args );
}


function get_testimonials( $atts ) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = [
		'post_type'      => 'testimonial',
		'posts_per_page' => 5,
		'paged'          => $paged
	];

	$testimonial_query = new WP_Query( $args );
	ob_start();
	if ( $testimonial_query->have_posts() ) {

		while ( $testimonial_query->have_posts() ) {
			$testimonial_query->the_post();
			get_template_part( 'template-parts/content/content', get_post_format() );
		}
		wp_reset_postdata();
	}

	if ( $testimonial_query->max_num_pages > 1 ) { // check if the max number of pages is greater than 1  ?>
        <nav class="prev-next-posts">
            <div class="prev-posts-link">
				<?php echo get_next_posts_link( 'Previous Testimonials', $testimonial_query->max_num_pages ); // display older posts link ?>
            </div>
            <div class="next-posts-link">
				<?php echo get_previous_posts_link( 'Next Testimonials' ); // display newer posts link ?>
            </div>
        </nav>
	<?php }

	return ob_get_clean();
}

add_shortcode( 'testimonials_list', 'get_testimonials' );

function get_carousel_testimonials() {
	//[evca_testimonial_slider][evca_testimonial name="Test1" position="Test1" testimonial="I love you"][evca_testimonial name="Test 2" position="Test 2" testimonial="Test 2"][/evca_testimonial_slider]

	$output = "[evca_testimonial_slider]";


	$args = [
		'post_type'      => 'testimonial',
		'posts_per_page' => 6,
		'order_by'       => 'date',
		'order'          => 'desc'
	];


	$testimonial_query = new WP_Query( $args );
	ob_start();
	if ( $testimonial_query->have_posts() ) {

		while ( $testimonial_query->have_posts() ) {
			$testimonial_query->the_post();

			$testimonial_position = get_field( 'testimonial_job_description' );
			$company_name         = $testimonial_position == '' ? get_field( 'testimonial_company_name' ) : ', ' . get_field( 'testimonial_company_name' );


			$output .= '[evca_testimonial name= "' . get_the_title() . '"  position="' . $testimonial_position . $company_name
			           . '" testimonial="' . get_the_content() . '"]';
		}
		wp_reset_postdata();
	}

	$output .= "[/evca_testimonial_slider]";

	return do_shortcode( $output );
}

add_shortcode( 'niu_testimonial_carousel', 'get_carousel_testimonials' );


function testimonial_by_id_func( $atts ) {
	$atts = shortcode_atts(
		[
			'id' => false,
		],
		$atts
	);
	ob_start();
	$testimonial = get_post( $atts['id'] );
	echo "</br>";
	echo "</br>";
	echo "</br>";
	var_dump( $testimonial );
	if ( $testimonial->post_type == 'testimonial' ) {
		echo '<div class="custom-testimonials"><div class="post-content"><p>' .
		     $testimonial->post_content . '</p></div>' .
		     '<div class="testimonial-title">' . $testimonial->post_title . '</div></div>';
		echo '<h6 class="h6-custom-testimonials">' . get_field( 'testimonial_company_name', $atts['id'] ) . '</h6>';
		echo '<h6 class="h6-custom-testimonials">' . get_field( 'testimonial_job_description', $atts['id'] ) . '</h6>';

	}

	return ob_get_clean();
}

add_shortcode( 'testimonial_by_id', 'testimonial_by_id_func' );


function get_services_by_taxonomy_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( [
		'service_slug' => ''
	], $atts );

	ob_start();

	get_services_by_taxonomy( esc_attr( $a['service_slug'] ) );

	return ob_get_clean();


}

add_shortcode( 'get_services_by_taxonomy', 'get_services_by_taxonomy_shortcode' );

add_action( 'gform_pre_submission', 'niu_populate_contact_page_hidden_field' );

function niu_populate_contact_page_hidden_field( $form ) {
	foreach ( $form['fields'] as $field ) {
		if ( $field->inputName == 'form_sent_referrer' ) {
			$referer_field_id      = $field->id;
			$hidden_referrer_field = $_POST[ 'input_' . $referer_field_id ];
			if ( $hidden_referrer_field == '' ) {
				$_POST[ 'input_' . $referer_field_id ] = get_bloginfo( 'url' );
			}
			//            $page_title = get_page_by_path(basename(untrailingslashit($hidden_referrer_field)))->post_title;
			//            $_POST['input_' . $referer_field_id] = $page_title . ' - ' . $hidden_referrer_field;
		}
	}
}

add_filter( 'rwmb_meta_boxes', 'niu_add_meta_box_partners' );

function niu_add_meta_box_partners( $meta_boxes ) {
	$prefix       = 'niu_partners_';
	$meta_boxes[] = [
		'id'         => 'partners_external_link',
		'title'      => 'External Link Area',
		'post_types' => 'partners',
		'context'    => 'normal',
		'priority'   => 'high',
		'fields'     => [
			[
				'name' => 'External URL Field',
				'id'   => $prefix . 'external_url',
				'type' => 'text'
			],
		]
	];

	return $meta_boxes;
}

add_shortcode( 'niu_show_partners_logos', 'niu_show_partners_logos_func' );

function niu_show_partners_logos_func( $atts ) {
    //Check if attributes have been set to the shortcode. The current attribute indicates if the shortcode is for the home page.
	$owl_carousel_class = 'owl-carousel';
	if ( isset( $atts['class'] ) && $atts['class'] !== '' ) {
		$owl_carousel_class .= ' ' . $atts['class'];
	} else {
		$owl_carousel_class .= ' owl-one';
	}

	$partners_args  = [
		'post_type'      => 'partners',
		'posts_per_page' => - 1,
		'no_found_rows'  => true
	];
	$partners_query = new WP_Query( $partners_args );
	ob_start();
	if ( $partners_query->have_posts() ) {
		?>
        <h3 class="owl-carousel-name">ICO Clients</h3>
        <div class="<?php echo $owl_carousel_class ?>">
			<?php
			while ( $partners_query->have_posts() ) {
				$partners_query->the_post();
				$external_url = get_post_meta( get_the_ID(), 'niu_partners_external_url', true );
				if ( $external_url != '' ) {
				    //Check if an external URL has been set and add an a tag
					?>
                    <a href="<?php echo $external_url ?>">
				<?php } ?>
                <div style="background-image: url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?>)"></div>
				<?php
				if ( $external_url != '' ) {
					?>
                    </a>
					<?php
				}
			}
			?>
        </div>
		<?php
	}

	return ob_get_clean();
}

if ( ! function_exists( 'write_log' ) ) {
	function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
}