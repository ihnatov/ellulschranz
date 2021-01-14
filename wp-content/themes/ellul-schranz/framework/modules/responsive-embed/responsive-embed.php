<?php
/**
 * ELLUL_SCHRANZ Theme Responsive Embed
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

class ELLUL_SCHRANZ_Responsive_Embed {

	public function __construct(){
		add_filter( 'embed_oembed_html', array( $this, 'filter_embed' ) );
	}

	public function filter_embed( $html ){
		return sprintf( '<div class="responsive-embed">%s</div>', $html );
	}

}

new ELLUL_SCHRANZ_Responsive_Embed;
