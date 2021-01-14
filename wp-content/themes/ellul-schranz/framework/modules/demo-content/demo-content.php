<?php
/**
 * ELLUL_SCHRANZ Theme Demo Content
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

class ELLUL_SCHRANZ_Demo_Content {

	public function __construct(){
		add_filter( 'edc-demo-content', array( $this, 'setup_demo_content' ) );
	}

	public function setup_demo_content( $import ){
		$path = ELLUL_SCHRANZ_MODULES_DIR . '/demo-content/demo-files';
		$import[ $path ] = array(
			'xml'       => array(
				'ellulschranz-wp.xml',
			),
			'redux'     => array(
				'ellul_schranz_data' => 'ellul-schranz.json',
			),
			'revslider' => array(
				'ellulschranz-wp-slider.zip',
			),
		);
		return $import;
	}

}

new ELLUL_SCHRANZ_Demo_Content;
