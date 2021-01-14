<?php
/**
 * ELLUL_SCHRANZ Theme Config File
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

/**********************************************************************
 * Define global config variables
 **********************************************************************/

$ellul_schranz_config = array();
$ellul_schranz_data   = array();

/**********************************************************************
 * TGM Plugin Activation Config
 * key: plugins [ multidimensional array ]
 * 			- collection of plugins
 * key: config [ array ]
 * 			- plugin configurations
 **********************************************************************/

$ellul_schranz_config['tgm'] = array(
	'plugins' => array(
		array(
			'name' 	             => esc_html__( 'Redux Framework', 'ellul-schranz' ),
			'slug' 	             => 'redux-framework',
			'required'           => true,
			'force_deactivation' => true,
		),
		array(
			'name' 	             => esc_html__( 'Meta Box', 'ellul-schranz' ),
			'slug' 	             => 'meta-box',
			'required'           => true,
			'force_deactivation' => true,
		),
		array(
			'name' 	             => esc_html__( 'Meta Box Tabs', 'ellul-schranz' ),
			'slug' 	             => 'meta-box-tabs',
			'source'             => 'meta-box-tabs.zip',
			'required'           => true,
			'force_deactivation' => true,
		),
		array(
			'name' 	   => esc_html__( 'Visual Composer', 'ellul-schranz' ),
			'slug' 	   => 'js_composer',
			'source'   => 'js_composer.zip',
			'required' => false,
		),
		array(
			'name' 	   => esc_html__( 'EllulSchranz Visual Composer Addon', 'ellul-schranz' ),
			'slug' 	   => 'ellulschranz-vc-addon',
			'source'   => 'ellulschranz-vc-addon.zip',
			'required' => false,
		),
		array(
			'name' 	   => esc_html__( 'EllulSchranz Portfolio Builder', 'ellul-schranz' ),
			'slug' 	   => 'ellulschranz-portfolio-builder',
			'source'   => 'ellulschranz-portfolio-builder.zip',
			'required' => false,
		),
		array(
			'name' 	   => esc_html__( 'EDNS Demo Importer', 'ellul-schranz' ),
			'slug' 	   => 'edns-demo-importer',
			'source'   => 'edns-demo-importer.zip',
			'required' => false,
		),
		array(
			'name' 	   => esc_html__( 'Revolution Slider', 'ellul-schranz' ),
			'slug' 	   => 'revslider',
			'source'   => 'revslider.zip',
			'required' => false,
		),
		array(
			'name' 	   => esc_html__( 'Contact Form 7', 'ellul-schranz' ),
			'slug' 	   => 'contact-form-7',
			'required' => false,
		),
	),
	'config'  => array(
		'domain'       => 'ellul-schranz',
		'default_path' => ELLUL_SCHRANZ_PLUGINS_DIR,
        'parent_slug'  => 'themes.php',
        'capability'   => 'manage_options',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => true,
		'strings'      => array(
			'page_title' => esc_html__( 'Install Suggested Plugins', 'ellul-schranz' ),
			'menu_title' => esc_html__( 'Theme Plugins', 'ellul-schranz' ),
		),
	),
);

/**********************************************************************
 * Widget Area Config
 **********************************************************************/

$ellul_schranz_config['ellul_schranz-section'] = array(
	'footer'        => array(
		'name'    => esc_html__( 'Footer', 'ellul-schranz' ),
		'enabled' => true,
		'rows'    => array(
			array(
				'name'    => esc_html__( 'Footer', 'ellul-schranz' ),
				'columns' => array(
					array(
						'size'     => 3,
						'elements' => array(
							'footer-footer-1' => array(
								'name' => esc_html__( 'Footer: Column 1', 'ellul-schranz' ),
							),
						),
					),
					array(
						'size'     => 3,
						'elements' => array(
							'footer-footer-2' => array(
								'name' => esc_html__( 'Footer: Column 2', 'ellul-schranz' ),
							),
						),
					),
					array(
						'size'     => 3,
						'elements' => array(
							'footer-footer-3' => array(
								'name' => esc_html__( 'Footer: Column 3', 'ellul-schranz' ),
							),
						),
					),
					array(
						'size'     => 3,
						'elements' => array(
							'footer-footer-4' => array(
								'name' => esc_html__( 'Footer: Column 4', 'ellul-schranz' ),
							),
						),
					),
				),
			),
		),
	),
	'footer-bottom' => array(
		'name'    => esc_html__( 'Footer Bottom', 'ellul-schranz' ),
		'enabled' => true,
		'rows'    => array(
			array(
				'name'    => esc_html__( 'Footer Bottom', 'ellul-schranz' ),
				'columns' => array(
					array(
						'size'     => 6,
						'elements' => array(
							'footer-bottom-footer-bottom-1' => array(
								'name' => esc_html__( 'Footer Bottom: Column 1', 'ellul-schranz' ),
							),
						),
					),
					array(
						'size'     => 6,
						'elements' => array(
							'footer-bottom-footer-bottom-2' => array(
								'name' => esc_html__( 'Footer Bottom: Column 2', 'ellul-schranz' ),
							),
						),
					),
				),
			),
		),
	),
);

/**********************************************************************
 * METABOX CONFIG
 **********************************************************************/

$ellul_schranz_custom_sidebars = get_option( 'ellul_schranz-custom-sidebars', array() );
array_unshift( $ellul_schranz_custom_sidebars, esc_html_x( 'Default', 'Custom sidebars', 'ellul-schranz' ) );
$ellul_schranz_custom_sidebars = array_map( 'esc_html', $ellul_schranz_custom_sidebars );

$extra_custom_post_type     = array();
if( class_exists( 'ERP_Portfolio' ) ){
	$extra_custom_post_type[] = ERP_Portfolio::$post_type;
}

$ellul_schranz_config['meta-box'] = array(
	array(
		'id'         => 'page-meta-box',
		'title'      => esc_html__( 'Page Settings', 'ellul-schranz' ),
		'post_types' => array( 'page' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'tabs'       => array(
			'layout'    => array(
				'label' => esc_html__( 'Layout', 'ellul-schranz' ),
			),
			'page-title' => array(
				'label' => esc_html__( 'Page title', 'ellul-schranz' ),
			),
		),
		'fields'     => array(
			array(
				'name'        => esc_html__( 'Page Layout', 'ellul-schranz' ),
				'id'          => 'layout-default-page',
				'type'        => 'select_advanced',
				'desc'        => wp_kses(
					__( 'Configure the page layout.<br/>
						<strong>Default (set in theme options)</strong> - use the page layout set as default in Theme Options > Layout > Default page layout<br/>
						<strong>Full width</strong> - page with no sidebar<br/>
						<strong>Sidebar Left</strong> - page with left sidebar<br/>
						<strong>Sidebar right</strong> - page with right sidebar<br/>
					', 'ellul-schranz' ),
					array( 'strong' => array(), 'br' => array() )
				),
				'placeholder' => wptexturize( esc_html_x( '-- Select --', 'metabox placeholder', 'ellul-schranz' ) ),
				'js_options'  => array(
					'allowClear' => false,
				),
				'tab'         => 'layout',
				'std'         => ELLUL_SCHRANZ_DEFAULT,
				'options'     => array(
					ELLUL_SCHRANZ_DEFAULT       => esc_html_x( 'Default ( set in theme options ) ', 'layout->sidebar', 'ellul-schranz' ),
					ELLUL_SCHRANZ_FULLWIDTH     => esc_html_x( 'Full width', 'layout->sidebar', 'ellul-schranz' ),
					ELLUL_SCHRANZ_LEFT_SIDEBAR  => esc_html_x( 'Sidebar left', 'layout->sidebar', 'ellul-schranz' ),
					ELLUL_SCHRANZ_RIGHT_SIDEBAR => esc_html_x( 'Sidebar right', 'layout->sidebar', 'ellul-schranz' ),
				),
			),
			array(
				'name'        => esc_html__( 'Sidebar Source', 'ellul-schranz' ),
				'id'          => 'layout-sidebar',
				'type'        => 'select_advanced',
				'desc'        => wp_kses(
					__( 'Select the sidebar you wish to display on this page.<br/>
						Only applies when a Sidebar Left or Sidebar Right layout has been selected.<br/>
						You can create new sidebars from Apparence > Sidebars<br/>
						<strong>Default</strong> - use the default sidebar (Main Sidebar)
					', 'ellul-schranz' ),
					array( 'strong' => array(), 'br' => array() )
				),
				'placeholder' => wptexturize( esc_html_x( '-- Select --', 'metabox placeholder', 'ellul-schranz' ) ),
				'js_options'  => array(
					'allowClear' => false,
				),
				'tab'         => 'layout',
				'options'     => $ellul_schranz_custom_sidebars,
				'std'         => ELLUL_SCHRANZ_DEFAULT,
			),
			array(
				'id'   => 'page-title-on',
				'type' => 'checkbox',
				'name' => esc_html__( 'Page title', 'ellul-schranz' ),
				'desc' => esc_html__( 'Enable page title on this page', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-bg-image',
				'type' => 'file_input',
				'name' => esc_html__( 'Page title background', 'ellul-schranz' ),
				'desc' => esc_html__( 'Upload an Image to be used as  a background for the page title. (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-title',
				'type' => 'text',
				'name' => esc_html__( 'Custom page title', 'ellul-schranz' ),
				'desc' => esc_html__( 'By default the page title displays the title given to the page. You can optionally overwrite it by providing a custom title.  (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-subtitle',
				'type' => 'text',
				'name' => esc_html__( 'Page sub title', 'ellul-schranz' ),
				'desc' => esc_html__( 'Allows you to provide a subtitle to be used with the page title (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
		),
    ),
	array(
		'id'         => 'post-meta-box',
		'title'      => esc_html__( 'Post Settings', 'ellul-schranz' ),
		'post_types' => array( 'post' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'tabs'       => array(
			'layout'    => array(
				'label' => esc_html__( 'Layout', 'ellul-schranz' ),
			),
			'page-title' => array(
				'label' => esc_html__( 'Page title', 'ellul-schranz' ),
			),
		),
		'fields'     => array(
			array(
				'id'   => 'blog-hide-featured-image',
				'type' => 'checkbox',
				'name' => esc_html__( 'Hide featured image', 'ellul-schranz' ),
				'desc' => esc_html__( 'Check this if you want to hide the Featured Image on the Blog Detail Page', 'ellul-schranz' ),
				'tab'  => 'layout',
			),
			array(
				'id'   => 'page-title-on',
				'type' => 'checkbox',
				'name' => esc_html__( 'Page title', 'ellul-schranz' ),
				'desc' => esc_html__( 'Enable page title on this page', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-bg-image',
				'type' => 'file_input',
				'name' => esc_html__( 'Page title background', 'ellul-schranz' ),
				'desc' => esc_html__( 'Upload an Image to be used as  a background for the page title. (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-title',
				'type' => 'text',
				'name' => esc_html__( 'Custom page title', 'ellul-schranz' ),
				'desc' => esc_html__( 'By default the page title displays the title given to the page. You can optionally overwrite it by providing a custom title.  (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-subtitle',
				'type' => 'text',
				'name' => esc_html__( 'Page sub title', 'ellul-schranz' ),
				'desc' => esc_html__( 'Allows you to provide a subtitle to be used with the page title (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
		),
    ),
);

if( class_exists( 'ERP_Portfolio' ) ){
	$ellul_schranz_config['meta-box'][] = array(
		'id'         => 'portfolio-meta-box',
		'title'      => esc_html__( 'Portfolio Settings', 'ellul-schranz' ),
		'post_types' => array( ERP_Portfolio::$post_type ),
		'context'    => 'normal',
		'priority'   => 'high',
		'tabs'       => array(
			'layout'    => array(
				'label' => esc_html__( 'Layout', 'ellul-schranz' ),
			),
			'page-title' => array(
				'label' => esc_html__( 'Page title', 'ellul-schranz' ),
			),
		),
		'fields'     => array(
			array(
				'name'        => esc_html__( 'Page Layout', 'ellul-schranz' ),
				'id'          => 'layout-default-page',
				'type'        => 'select_advanced',
				'desc'        => wp_kses(
					__( 'Configure the page layout.<br/>
						<strong>Default (set in theme options)</strong> - use the page layout set as default in Theme Options > Layout > Default page layout<br/>
						<strong>Full width</strong> - page with no sidebar<br/>
						<strong>Sidebar Left</strong> - page with left sidebar<br/>
						<strong>Sidebar right</strong> - page with right sidebar<br/>
					', 'ellul-schranz' ),
					array( 'strong' => array(), 'br' => array() )
				),
				'placeholder' => wptexturize( esc_html_x( '-- Select --', 'metabox placeholder', 'ellul-schranz' ) ),
				'js_options'  => array(
					'allowClear' => false,
				),
				'tab'         => 'layout',
				'std'         => ELLUL_SCHRANZ_DEFAULT,
				'options'     => array(
					ELLUL_SCHRANZ_DEFAULT       => esc_html_x( 'Default ( set in theme options ) ', 'layout->sidebar', 'ellul-schranz' ),
					ELLUL_SCHRANZ_FULLWIDTH     => esc_html_x( 'Full width', 'layout->sidebar', 'ellul-schranz' ),
					ELLUL_SCHRANZ_LEFT_SIDEBAR  => esc_html_x( 'Sidebar left', 'layout->sidebar', 'ellul-schranz' ),
					ELLUL_SCHRANZ_RIGHT_SIDEBAR => esc_html_x( 'Sidebar right', 'layout->sidebar', 'ellul-schranz' ),
				),
			),
			array(
				'name'        => esc_html__( 'Sidebar Source', 'ellul-schranz' ),
				'id'          => 'layout-sidebar',
				'type'        => 'select_advanced',
				'desc'        => wp_kses(
					__( 'Select the sidebar you wish to display on this page.<br/>
						Only applies when a Sidebar Left or Sidebar Right layout has been selected.<br/>
						You can create new sidebars from Apparence > Sidebars<br/>
						<strong>Default</strong> - use the default sidebar (Main Sidebar)
					', 'ellul-schranz' ),
					array( 'strong' => array(), 'br' => array() )
				),
				'placeholder' => wptexturize( esc_html_x( '-- Select --', 'metabox placeholder', 'ellul-schranz' ) ),
				'js_options'  => array(
					'allowClear' => false,
				),
				'tab'         => 'layout',
				'options'     => $ellul_schranz_custom_sidebars,
				'std'         => ELLUL_SCHRANZ_DEFAULT,
			),
			array(
				'id'   => 'page-title-on',
				'type' => 'checkbox',
				'name' => esc_html__( 'Page title', 'ellul-schranz' ),
				'desc' => esc_html__( 'Enable page title on this page', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-bg-image',
				'type' => 'file_input',
				'name' => esc_html__( 'Page title background', 'ellul-schranz' ),
				'desc' => esc_html__( 'Upload an Image to be used as  a background for the page title. (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-title',
				'type' => 'text',
				'name' => esc_html__( 'Custom page title', 'ellul-schranz' ),
				'desc' => esc_html__( 'By default the page title displays the title given to the page. You can optionally overwrite it by providing a custom title.  (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
			array(
				'id'   => 'page-subtitle',
				'type' => 'text',
				'name' => esc_html__( 'Page sub title', 'ellul-schranz' ),
				'desc' => esc_html__( 'Allows you to provide a subtitle to be used with the page title (optional)', 'ellul-schranz' ),
				'tab'  => 'page-title',
			),
		),
	);
}

/**********************************************************************
 * Redux Framework Configs
 * key: opt_name [ string ]
 * 			- global variable name for redux options
 * key: args [ array ]
 * 			- arguments set via Redux::setArgs
 * key: sections [ multidimensional array ]
 * 			- will loop over each element and call Redux::setSection
 **********************************************************************/

$ellul_schranz_all_pages = array();
$ellul_schranz_cur_pages = get_posts( array(
	'post_type'      => 'page',
	'post_status'    => 'publish',
	'posts_per_page' => '-1',
	'orderby'        => 'title',
	'order'          => 'asc',
) );
foreach( $ellul_schranz_cur_pages as $ellul_schranz_cur_page ){
	$ellul_schranz_all_pages[ $ellul_schranz_cur_page->ID ] = esc_html( $ellul_schranz_cur_page->post_title );
}

$ellul_schranz_config['redux'] = array(
	'opt_name' => 'ellul_schranz_data',
	'args'     => array(
		'opt_name'         => 'ellul_schranz_data',
		'dev_mode'         => false,
		'update_notice'    => false,
		'disable_tracking' => true,
		'menu_type'        => 'submenu',
		'menu_title'       => esc_html__( 'Theme Options', 'ellul-schranz' ),
		'page_parent'      => 'themes.php',
		'default_mark'     => '',
		'compiler'         => true,
		'database'         => 'theme_mods',
		'hints'            => array(
			'icon_position' => 'right',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color' => 'light',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'duration' => 500,
					'event'    => 'mouseover',
				),
				'hide' => array(
					'duration' => 500,
					'event'    => 'mouseleave unfocus',
				),
			),
		),
	), // end args
	'sections' => array(
		array(
			'title' => esc_html__( 'Layout', 'ellul-schranz' ),
			'icon'  => 'el el-th',
			'fields'     => array(
				array(
					'id'   => 'general-page-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Page layout options', 'ellul-schranz' ),
				),
				array(
					'id'       => 'layout-default-page',
					'type'     => 'select',
					'title'    => esc_html__( 'Default page layout', 'ellul-schranz' ),
					'options'  => array(
						ELLUL_SCHRANZ_FULLWIDTH     => esc_html_x( 'Full width', 'layout->sidebar', 'ellul-schranz' ),
						ELLUL_SCHRANZ_LEFT_SIDEBAR  => esc_html_x( 'Sidebar left', 'layout->sidebar', 'ellul-schranz' ),
						ELLUL_SCHRANZ_RIGHT_SIDEBAR => esc_html_x( 'Sidebar right', 'layout->sidebar', 'ellul-schranz' ),
					),
					'default'  => ELLUL_SCHRANZ_FULLWIDTH,
				),
				array(
					'id'            => 'layout-sidebar-size',
					'type'          => 'slider',
					'title'         => esc_html__( 'Sidebar column size', 'ellul-schranz' ),
					'default'       => 3,
					'min'           => 2,
					'step'          => 1,
					'max'           => 5,
					'display_value' => 'text',
				),
				array(
					'id'   => 'misc-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Misc', 'ellul-schranz' ),
				),
				array(
					'id'       => 'back-to-top',
					'type'     => 'switch',
					'title'    => esc_html__( 'Back to top button', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Enable / disable back to top button', 'ellul-schranz' ),
					'on'       => esc_html__( 'On', 'ellul-schranz' ),
					'off'      => esc_html__( 'Off', 'ellul-schranz' ),
					'default'  => 1,
				),
			),
		),
		array(
			'title'  => esc_html__( 'Styling', 'ellul-schranz' ),
			'icon'   => 'el el-brush',
			'fields' => array(
				array(
					'id'   => 'colors-general-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Colors', 'ellul-schranz' ),
				),
				array(
					'id'          => 'color-accent',
					'type'        => 'color',
					'title'       => esc_html__( 'Accent colors', 'ellul-schranz' ),
					'default'     => '#1C9BDC',
					'transparent' => false,
				),
				array(
					'id'   => 'colors-tile-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Tile colors', 'ellul-schranz' ),
				),
				array(
					'id'          => 'color-ms-tile',
					'type'        => 'color',
					'title'       => esc_html__( 'MS Tile', 'ellul-schranz' ),
					'default'     => '#1C9BDC',
					'transparent' => false,
				),
				array(
					'id'          => 'color-android-theme',
					'type'        => 'color',
					'title'       => esc_html__( 'Android', 'ellul-schranz' ),
					'default'     => '#1C9BDC',
					'transparent' => false,
				),
			),
		),
		array(
			'title'  => esc_html__( 'Header', 'ellul-schranz' ),
			'icon'   => 'el el-file-edit',
			'fields' => array(
				array(
					'id'   => 'header-layout-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Header layout options', 'ellul-schranz' ),
				),
				array(
					'id'       => 'header-layout',
					'type'     => 'image_select',
					'title'    => esc_html__( 'Header layout', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Select the header layout', 'ellul-schranz' ),
					'options'  => array(
						1 => array(
							'img' => ELLUL_SCHRANZ_ASSETS_URI . '/images/redux/header-1.jpg',
						),
						2 => array(
							'img' => ELLUL_SCHRANZ_ASSETS_URI . '/images/redux/header-2.jpg',
						),
					),
					'default'  => 1,
				),
				array(
					'id'            => 'header-height',
					'type'          => 'slider',
					'title'         => esc_html__( 'Header height', 'ellul-schranz' ),
					'subtitle'      => esc_html__( 'Select the header height. Adjust it until logo fit nicely in the header.', 'ellul-schranz' ),
					'default'       => 105,
					'min'           => 100,
					'step'          => 1,
					'max'           => 500,
					'display_value' => 'text',
				),
				array(
					'id'       => 'header-sticky',
					'type'     => 'switch',
					'title'    => esc_html__( 'Sticky header', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Enable / disable sticky header.', 'ellul-schranz' ),
					'on'       => esc_html__( 'On', 'ellul-schranz' ),
					'off'      => esc_html__( 'Off', 'ellul-schranz' ),
					'default'  => 1,
				),
				array(
					'id'            => 'header-sticky-height',
					'type'          => 'slider',
					'title'         => esc_html__( 'Sticky header height', 'ellul-schranz' ),
					'subtitle'      => esc_html__( 'Select the sticky header height', 'ellul-schranz' ),
					'default'       => 105,
					'min'           => 100,
					'step'          => 1,
					'max'           => 500,
					'display_value' => 'text',
					'required'      => array( 'header-sticky', '=', 1 ),
				),
				array(
					'id'   => 'header-logo-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Logo', 'ellul-schranz' ),
				),
				array(
					'id'       => 'header-logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Logo', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Select the header style logo', 'ellul-schranz' ),
					'default'  => array(
						'url' => ELLUL_SCHRANZ_ASSETS_URI . '/images/logo.png',
					),
				),
			),
		),
		array(
			'title'  => esc_html__( 'Page title', 'ellul-schranz' ),
			'icon'   => 'el el-text-width',
			'fields' => array(
				array(
					'id'   => 'page-default-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Default  settings', 'ellul-schranz' ),
				),
				array(
					'id'       => 'page-title-on',
					'type'     => 'switch',
					'title'    => esc_html__( 'Page title', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Enable / disable page title', 'ellul-schranz' ),
					'on'       => esc_html__( 'On', 'ellul-schranz' ),
					'off'      => esc_html__( 'Off', 'ellul-schranz' ),
					'default'  => 1,
				),
			),
		),
		array(
			'title'  => esc_html__( 'Pages', 'ellul-schranz' ),
			'icon'   => 'el el-file',
			'fields' => array(
				array(
					'id'   => 'page-general-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Page general settings', 'ellul-schranz' ),
				),
				array(
					'id'       => 'page-comments',
					'type'     => 'switch',
					'title'    => esc_html__( 'Global page comments', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'You can globally disable the page comments ( not blog )', 'ellul-schranz' ),
					'on'       => esc_html__( 'On', 'ellul-schranz' ),
					'off'      => esc_html__( 'Off', 'ellul-schranz' ),
					'default'  => 1,
				),
				array(
					'id'   => 'page-404-divider',
					'type' => 'info',
					'desc' => esc_html__( '404', 'ellul-schranz' ),
				),
				array(
					'id'       => '404',
					'type'     => 'select',
					'title'    => esc_html__( '404 page', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Select the 404 page', 'ellul-schranz' ),
					'options'  => $ellul_schranz_all_pages,
				),
				array(
					'id'   => 'page-search-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Search', 'ellul-schranz' ),
				),
				array(
					'id'       => 'search-post-types',
					'type'     => 'checkbox',
					'title'    => esc_html__( 'Search results to display', 'ellul-schranz' ),
					'desc'     => esc_html__( 'Leaving them un-checked will display the default post types.', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Check the post types to display in search results', 'ellul-schranz' ),
					'data'     => 'post_types',
					'default'  => 'all',
				),
				array(
					'id'       => 'search-page',
					'type'     => 'select',
					'title'    => esc_html__( 'Search results page', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Select the search results page', 'ellul-schranz' ),
					'options'  => $ellul_schranz_all_pages,
				),
			),
		),
		array(
			'title'  => esc_html__( 'Blog', 'ellul-schranz' ),
			'icon'   => 'el el-indent-left',
			'fields' => array(
				array(
					'id'   => 'blog-general-divider',
					'type' => 'info',
					'desc' => esc_html__( 'General settings', 'ellul-schranz' ),
				),
				array(
					'id'       => 'blog-meta-on',
					'type'     => 'switch',
					'title'    => esc_html__( 'Blog metadata', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Enabled / disable metadata on blog posts', 'ellul-schranz' ),
					'on'       => esc_html__( 'On', 'ellul-schranz' ),
					'off'      => esc_html__( 'Off', 'ellul-schranz' ),
					'default'  => 1,
				),
				array(
					'id'       => 'blog-metadata',
					'type'     => 'checkbox',
					'title'    => esc_html__( 'Metadata options', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Check the metadata to display', 'ellul-schranz' ),
					'options'  => array(
						ELLUL_SCHRANZ_META_POST_FORMAT  => esc_html_x( 'Post format', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_PUBLISH_TIME => esc_html_x( 'Publish time', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_CATEGORIES   => esc_html_x( 'Categories', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_TAGS         => esc_html_x( 'Tags', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_ATTACHMENT   => esc_html_x( 'Attachment size', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_COMMENTS     => esc_html_x( 'Comments', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_AUTHOR       => esc_html_x( 'Author', 'Option name under Blog -> metadata', 'ellul-schranz' ),
						ELLUL_SCHRANZ_META_FEATURED     => esc_html_x( 'Featured', 'Option name under Blog -> metadata', 'ellul-schranz' ),
					),
					'default'  => array(
						ELLUL_SCHRANZ_META_POST_FORMAT  => 1,
						ELLUL_SCHRANZ_META_PUBLISH_TIME => 1,
						ELLUL_SCHRANZ_META_CATEGORIES   => 1,
						ELLUL_SCHRANZ_META_TAGS         => 1,
						ELLUL_SCHRANZ_META_ATTACHMENT   => 1,
						ELLUL_SCHRANZ_META_COMMENTS     => 1,
						ELLUL_SCHRANZ_META_AUTHOR       => 0,
						ELLUL_SCHRANZ_META_FEATURED     => 1,
					),
					'required' => array( 'blog-meta-on', '=', 1 ),
				),
				array(
					'id'       => 'blog-hide-featured-image',
					'type'     => 'switch',
					'title'    => esc_html__( 'Featured image on single blog post', 'ellul-schranz' ),
					'subtitle' => esc_html__( 'Default behavior of single blog post featured image', 'ellul-schranz' ),
					'on'       => esc_html__( 'Hide', 'ellul-schranz' ),
					'off'      => esc_html__( 'Show', 'ellul-schranz' ),
					'default'  => 0,
				),
			),
		),
		array(
			'title'  => esc_html__( 'Advanced', 'ellul-schranz' ),
			'icon'   => 'el el-adjust-alt',
			'fields' => array(
				array(
					'id'   => 'google-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Google API Settings', 'ellul-schranz' ),
				),
				array(
					'id'    => 'google-api-key',
					'type'  => 'text',
					'title' => esc_html__( 'Google API key', 'ellul-schranz' ),
				),
				array(
					'id'   => 'advanced-divider',
					'type' => 'info',
					'desc' => esc_html__( 'Advanced CSS', 'ellul-schranz' ),
				),
				array(
					'id'       => 'custom-css',
					'type'     => 'ace_editor',
					'title'    => esc_html__( 'Custom CSS', 'ellul-schranz' ),
					'mode'     => 'css',
					'theme'    => 'monokai',
				),
			),
		),
	), // end sections
);
