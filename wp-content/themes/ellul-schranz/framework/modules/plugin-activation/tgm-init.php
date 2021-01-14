<?php
/**
 * ELLUL_SCHRANZ Theme Plugin Activation
 *
 * @package ELLUL_SCHRANZ
 */

if( !defined( 'ABSPATH' ) ){ exit; }

final class ELLUL_SCHRANZ_TGM_PLUGINS {

	public function __construct(){
		add_action( 'admin_head', array( $this, 'fix_notice_layout' ) );
		add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
	}

	public function fix_notice_layout(){
		echo '<style type="text/css">h1~#setting-error-ellul-schranz{ display: block; }</style>';
	}

	public function register_required_plugins(){
		global $ellul_schranz_config;
		if( isset( $ellul_schranz_config['tgm'], $ellul_schranz_config['tgm']['plugins'], $ellul_schranz_config['tgm']['config'] ) ){
			tgmpa( $ellul_schranz_config['tgm']['plugins'], $ellul_schranz_config['tgm']['config'] );
		}
	}

}

new ELLUL_SCHRANZ_TGM_PLUGINS;
