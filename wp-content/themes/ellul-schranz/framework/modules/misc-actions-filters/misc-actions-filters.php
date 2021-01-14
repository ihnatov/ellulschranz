<?php
/**
 * ELLUL_SCHRANZ Theme Miscellaneous Actions & Filters
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

final class ELLUL_SCHRANZ_Misc_Actions_Filters {

	public function __construct(){
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'ReduxFrameworkPlugin_admin_notice', array( $this, 'redux_activation_notice' ) );
		add_action( 'redux/plugin/hooks', array( $this, 'redux_activation_hook' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'tinymce_init' ) );
		add_filter( 'mce_buttons_2', array( $this, 'mce_buttons_2' ) );
		add_action( 'vc_before_init', array( $this, 'vc_set_as_theme' ) );
		$this->vc_set_as_theme();
	}

	public function init(){
		// remove redux notification - method from documentation ( not reliable )
		if( class_exists( 'ReduxFrameworkPlugin' ) ){
			remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks' ), null, 2 );
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
		}
		// remove revolution slider notification
		if( function_exists( 'set_revslider_as_theme' ) ){
			// it seems that the call is too late at this point, not working
			set_revslider_as_theme();
			// as a fallback we'll try to set the notice option to false when user is admin
			if( is_admin() && get_option( 'revslider-valid-notice' ) !== 'false' ){
				update_option( 'revslider-valid-notice', 'false' );
			}
		}
	}

	public function admin_menu(){
		remove_action( 'admin_init', 'vc_page_welcome_redirect' );
		remove_submenu_page( 'tools.php', 'redux-about' );
	}

	public function redux_activation_notice(){
		delete_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES' );
	}

	public function redux_activation_hook( $redux ){
		remove_action( 'wp_loaded', array( $redux, 'options_toggle_check' ) );
		remove_action( 'admin_notices', array( $redux, 'admin_notices' ) );
		remove_filter( 'plugin_row_meta', array( $redux, 'plugin_metalinks' ) );
	}

	public function vc_set_as_theme(){
		if( function_exists( 'vc_set_as_theme' ) ) vc_set_as_theme( true );
	}

	public function tinymce_init( $options ){
		$formats = array();
		if( array_key_exists( 'style_formats', $options ) ){
			$formats = json_decode( $options['style_formats'], true );
		}
		if( ! is_array( $formats ) ){
			$formats = array();
		}
		$formats[] = array(
			'title'    => esc_html_x( 'Custom list style', 'tinymce list style', 'ellul-schranz' ),
			'selector' => 'ul',
			'classes'  => 'circle-2',
		);
		$formats[] = array(
			'title'    => esc_html_x( 'Dropcap', 'tinymce list style', 'ellul-schranz' ),
			'selector' => 'p',
			'classes'  => 'dropcap',
		);
		$formats[] = array(
			'title'    => esc_html_x( 'Dropcap boxed layout', 'tinymce list style', 'ellul-schranz' ),
			'selector' => 'p',
			'classes'  => 'dropcap-square',
		);
		$formats[] = array(
			'title'    => esc_html_x( 'Dropcap circle layout', 'tinymce list style', 'ellul-schranz' ),
			'selector' => 'p',
			'classes'  => 'dropcap-circle',
		);
		$options['style_formats'] = json_encode( $formats );
		return $options;
	}

	public function mce_buttons_2( $buttons ){
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

}
new ELLUL_SCHRANZ_Misc_Actions_Filters;

// try to prevent redux from creating newsflash object...
if( ! class_exists( 'reduxNewsflash' ) ){
	class reduxNewsflash {}
}
