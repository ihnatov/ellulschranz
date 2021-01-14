<?php
/**
 * ELLUL_SCHRANZ Theme Framework Init
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

/**********************************************************************
* Init Redux Framework
**********************************************************************/
if( class_exists( 'Redux' ) && isset( $ellul_schranz_config, $ellul_schranz_config['redux'], $ellul_schranz_config['redux']['opt_name'] ) ){

	$redux = &$ellul_schranz_config['redux'];

	$redux['args']['display_name']    = $theme->get( 'Name' );
	$redux['args']['page_title']      = $theme->get( 'Name' );
	$redux['args']['display_version'] = $theme->get( 'Version' );

	Redux::setArgs( $redux['opt_name'], $redux['args'] );

	if( isset( $redux['sections'] ) && $redux['sections'] ){
		foreach( $redux['sections'] as $section ){
			Redux::setSection( $redux['opt_name'], $section );
		}
	}

	function ellul_schranz_redux_css(){
		wp_enqueue_style( 'redux-custom-css', ELLUL_SCHRANZ_ASSETS_URI . '/css/redux.css', null, ELLUL_SCHRANZ_VERSION );
	}

	add_action( sprintf( 'redux/page/%s/enqueue', $redux['opt_name'] ), 'ellul_schranz_redux_css' );

} elseif( ! class_exists( 'Redux' ) && isset( $ellul_schranz_config, $ellul_schranz_config['redux']['sections'], $ellul_schranz_config['redux']['opt_name'] ) ){
	$generated_option_name = &$$ellul_schranz_config['redux']['opt_name'];
	foreach( $ellul_schranz_config['redux']['sections'] as $section ){
		foreach( $section['fields'] as $field ){
			$generated_option_name[ $field['id'] ] = isset( $field['default'] ) ? $field['default'] : null;
		}
	}
}

/**********************************************************************
* Load PHP Module Files
**********************************************************************/

// auto update
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/auto-update/github.php';

// custom widgets
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/custom-widgets/contact-widget.php';
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/custom-widgets/flickr-widget.php';
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/custom-widgets/latest-posts-widget.php';
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/custom-widgets/navigation-widget.php';
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/custom-widgets/social-media-widget.php';

// demo content importer
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/demo-content/demo-content.php';

// dynamic css
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/dynamic-css/dynamic-css.php';

// extra
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/extras/extras.php';

// meta box settings
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/meta-box/meta-box.php';

// miscellaneous actions and filters
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/misc-actions-filters/misc-actions-filters.php';

// navigation menu
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/navigation/menu-backend.php';
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/navigation/menu-frontend.php';

// plugin activation
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/plugin-activation/class-tgm-plugin-activation.php';
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/plugin-activation/tgm-init.php';

// responsive videos
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/responsive-embed/responsive-embed.php';

// sidebars
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/sidebars/sidebars.php';

// main templating file and logical structures
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/templating/templating.php';

// widget areas
include_once ELLUL_SCHRANZ_FRAMEWORK_DIR . '/modules/widget-areas/widget-areas.php';
