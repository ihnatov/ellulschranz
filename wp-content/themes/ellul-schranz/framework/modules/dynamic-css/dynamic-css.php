<?php
/**
 * ELLUL_SCHRANZ Theme Dynamic CSS Generator
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

class ELLUL_SCHRANZ_Dynamic_CSS {

 	/**************************************************************
	* Constructor
	**************************************************************/
	public function __construct(){
		add_action( 'wp_head', array( $this, 'generate_css' ) );
		add_action( 'wp_head', array( $this, 'generate_tile_color' ), 0 );
	}

 	/**************************************************************
	* Generate Dynamic CSS
	**************************************************************/
	public function generate_css(){
		echo '<style id="ellul_schranz-dynamic-css" type="text/css">';
		ob_start();
		// header height
		if( ( $height = absint( $this->get_data( 'header-height' ) ) ) ){
			$padding = ( ( $height - 26 - 5 ) / 2 );
			$top     = ( $height - 18 ) / 2;
			printf(
				'#header-wrap,
				.header-style-1 #header-widget-area {
					height: %1$dpx;
				}
				.sf-menu > li > a,
				.sf-menu > li.dropdown > a {
					padding-top: %3$.2fpx;
					padding-bottom: %2$.2fpx;
				}
				.header-style-1 #header-widget-area {
					padding-top: %2$.2fpx;
					padding-bottom: %2$.2fpx;
				}
				@media (min-width: 992px) {
					.header-style-1 #custom-search-button {
						height: %1$dpx;
					}
					#custom-search-button {
						top: %4$.2fpx;
					}
				}
				',
				$height, $padding, $padding + 5, $top
			);
		}
		// header sticky height
		if( $this->get_data( 'header-sticky' ) && ( $height = absint( $this->get_data( 'header-sticky-height' ) ) ) ){
			$padding = ( $height - 26 - 15 ) / 2;
			$top     = ( ( $height - 15 ) / 2 ) - 18;
			printf(
				'@media (min-width: 1025px) {
					.header-style-2 #header-wrap.stuck {
						height: %1$.2fpx;
					}
					.header-style-2 #header-wrap.stuck .sf-menu > li > a,
					.header-style-2 #header-wrap.stuck .sf-menu > li.dropdown > a {
						padding-top: %2$.2fpx;
						padding-bottom: %3$.2fpx;
					}
					.header-style-2 #header-wrap.stuck #custom-search-button {
						top: %4$.2fpx;
					}
				}',
				$height - 15, $padding - 5, $padding + 5, $top
			);
		}
		// color accent
		$color = array(
			'#1c9bdc',
			'#198eca',
			'#1482ba',
			'#1073a5',
		);
		if( $colorX = $this->sanitize_hex_color( $this->get_data( 'color-accent' ) ) ){
			$color[0] = $colorX;
			$color[1] = $this->hex_brightness( $colorX, 0.918 );
			$color[2] = $this->hex_brightness( $colorX, 0.841 );
			$color[3] = $this->hex_brightness( $colorX, 0.745 );
		}
		printf(
			'h1 a,
			h2 a,
			h3 a,
			h4 a,
			h5 a,
			h6 a {
				color: #252525;
			}

			h1 a:hover,
			h2 a:hover,
			h3 a:hover,
			h4 a:hover,
			h5 a:hover,
			h6 a:hover {
				color: %1$s;
			}

			.text-highlight { color: %1$s; }

			a {
				color: %1$s;
			}

			ul.circle-2 li:before {
				border-color: %1$s;
			}

			ul.check li:before {
				color: %1$s;
			}

			button,
			input[type="reset"],
			input[type="submit"],
			input[type="button"] {
				background-color: %1$s;
			}

			.header-style-1 #header-widget-area {
				background-color: %1$s;
			}

			.sf-menu > li.current > a,
			.sf-menu li.sfHover > a,
			.sf-menu a:hover,
			.sf-menu li.sfHover a:hover {
				color: %1$s;
			}

			.sf-menu > li.dropdown ul { border-top-color: %1$s; }

			.sf-mega {
				border-top-color: %1$s;
			}

			#mobile-menu {
				background-color: %1$s;
			}

			#mobile-menu .sf-mega {
				background-color: %1$s;
			}

			.header-style-1 #custom-search-button {
				background-color: %1$s;
			}

			#custom-search-form-container {
				background-color: %1$s;
			}

			.header-style-1 #header-wrap.stuck {
				background-color: %1$s;
			}

			#page-header p {
				color: %1$s;
			}

			#footer a { color: #7b7b7b; }
			#footer a:hover { color: %1$s; }

			#footer-bottom a { color: #273941; }
			#footer-bottom a:hover { color: %1$s; }

			.vc_tta.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-panel-title > a,
			.vc_tta.vc_tta-accordion .vc_tta-panel .vc_tta-panel-title > a:hover {
				background-color: %1$s !important;
				border-color: %1$s !important;
			}

			.alert.success {
				background-color: %1$s;
			}

			.bordered:before {
				border-color: %1$s;
			}

			h1.error {
				border-color: %1$s;
				color: %1$s;
			}

			.box.box-style-2 {
				background-color: %1$s;
			}

			.btn {
				background-color: %1$s;
			}

			.btn-grey:after { background-color: %1$s; }

			.btn-grey:hover {
				background-color: %1$s;
			}

			.countdown-section {
				background-color: %1$s;
			}

			.countdown-section:nth-child(1) { background-color: %1$s; }
			.countdown-section:nth-child(2) { background-color: %2$s; }
			.countdown-section:nth-child(3) { background-color: %3$s; }
			.countdown-section:nth-child(4) { background-color: %4$s; }

			.countdown-section:after {
				border-left-color: %1$s;
			}

			.countdown-section:nth-child(1):after { border-left-color: %1$s; }
			.countdown-section:nth-child(2):after { border-left-color: %2$s; }
			.countdown-section:nth-child(3):after { border-left-color: %3$s; }
			.countdown-section:nth-child(4):after { border-left-color: %4$s; }

			.headline:before {
				background-color: %1$s;
			}

			.icon-box-1.alt > i:after {
				background-color: %1$s;
			}

			.icon-box-1:hover > i {
				border-color: %1$s;
				color: %1$s;
			}

			.icon-box-1.alt:hover > i {
				border-color: %1$s;
				background-color: %1$s;
			}

			.icon-box-2:hover > i { color: %1$s; }

			.icon-box-3 > i {
				color: %1$s;
			}

			.icon-box-4:before {
				border-color: %1$s;
			}

			.icon-box-4:hover:before { background-color: %1$s; }

			.icon-box-5 h4:before {
				background-color: %1$s;
			}

			.icon-box-5 h4 a { color: %1$s; }

			.icon-box-6 h4:after {
				background-color: %1$s;
			}

			.icon-box-6 h4 a { color: %1$s; }

			.image-box > i {
				background-color: %1$s;
			}

			.image-box.alt-2:hover .image-box-img > i { background-color: %1$s; }

			#info-box-1 {
				background-color: %1$s;
			}

			.milestone .milestone-content {
				color: %1$s;
			}

			.vertical-process-builder .process-description h4:before {
				border-top-color: %1$s;
			}

			.vertical-process-builder li:hover i,
			.vertical-process-builder li:hover h1 {
				border-color: %1$s;
				color: %1$s;
			}

			.vertical-process-builder li:hover i,
			.vertical-process-builder li:hover h1 {
				border-color: %1$s;
				color: %1$s;
			}

			.pricing-table:hover .pricing-table-header:before { border-color: %1$s; }

			.progress-bar .progress-bar-outer {
				background-color: %1$s;
			}

			.niu-services-list li {
				background-color: %1$s;
			}

			.niu-services-list li:nth-child(4n+1){ background-color: %1$s; }
			.niu-services-list li:nth-child(4n+2){ background-color: %2$s; }
			.niu-services-list li:nth-child(4n+3){ background-color: %3$s; }
			.niu-services-list li:nth-child(4n+4){ background-color: %4$s; }

			.niu-services-list li:after {
				border-left-color: %1$s;
			}

			.niu-services-list li:nth-child(4n+1):after { border-left-color: %1$s; }
			.niu-services-list li:nth-child(4n+2):after { border-left-color: %2$s; }
			.niu-services-list li:nth-child(4n+3):after { border-left-color: %3$s; }
			.niu-services-list li:nth-child(4n+4):after { border-left-color: %4$s; }

			.vc_tta.vc_tta-tabs .vc_tta-tab > a:hover,
			.vc_tta.vc_tta-tabs .vc_tta-tab.vc_active > a {
				background-color: %1$s !important;
			}

			.testimonial > h4 span { color: %1$s; }

			.team-member h4:before {
				background-color: %1$s;
			}

			.team-member h6 {
				color: %1$s;
			}

			.bx-wrapper .bx-pager.bx-default-pager a.active { background: %1$s; }

			.testimonial-slider h3:before {
				background-color: %1$s;
			}

			.testimonial-slider-2:before {
				border-color: %1$s;
				color: %1$s;
			}

			.sticky-post {
				background-color: %1$s;
			}

			.post-title:before {
				border-top-color: %1$s;
			}

			.posted-on {
				background-color: %1$s;
			}

			.post.alt .posted-on {
				color: %1$s;
			}

			.comments-link a {
				background-color: %1$s;
			}

			.comments-link a:after {
				border-top-color: %1$s;
			}

			.pagination .page-numbers:hover,
			.pagination .page-numbers.current {
				background-color: %1$s;
			}

			.comment-meta {
				background-color: %1$s;
			}

			.widget_pages ul li.current_page_item a { color: %1$s; }

			.widget_pages a:hover {
				color: %1$s;
			}

			#footer-bottom .widget_pages ul li.current_page_item a { color: %1$s; }

			.widget_archive a:hover {
				color: %1$s;
			}

			.widget_categories a:hover {
				color: %1$s;
			}

			.widget_meta a:hover {
				color: %1$s;
			}

			.widget_tag_cloud a {
				background-color: %1$s;
			}

			#wp-calendar tbody a {
				background-color: %1$s;
			}

			.widget_nav_menu a:hover {
				color: %1$s;
			}

			.ellul_schranz_widget_latest_posts ul li .title:hover {
				color: %1$s;
			}

			.ellul_schranz_widget_latest_posts ul li .comments a {
				background-color: %1$s;
			}

			.ellul_schranz_widget_latest_posts ul li .comments a:after {
				border-top-color: %1$s;
			}

			.ellul_schranz_widget_recommended ul li .title:hover {
				color: %1$s;
			}

			.ellul_schranz_widget_recommended ul li .comments a {
				background-color: %1$s;
			}

			.ellul_schranz_widget_recommended ul li .comments a:after {
				border-top-color: %1$s;
			}

			.ellul_schranz_social_media_widget {
				background-color: %1$s;
			}

			.tp-bullets.default .tp-bullet:hover,
			.tp-bullets.default .tp-bullet.selected {
				border-color: %1$s;
				background: %1$s;
			}

			.tp-leftarrow.default:hover,
			.tp-rightarrow.default:hover {
				border-color: %1$s;
				background: %1$s;
			}

			.tp-bannertimer {
				background-color: %1$s;
			}
			.erp-iframe-wrap .mfp-close{ background-color: %1$s; }
			',
			$color[0], $color[1], $color[2], $color[3]
		);
		// custom css
		if( $this->get_data( 'custom-css' ) ){
			echo wp_strip_all_tags( $this->get_data( 'custom-css' ) );
		}
		// strip newlines and tabs
		echo str_replace( array( "\n", "\r", "\t" ), '', ob_get_clean() );
		echo '</style>', "\n";
	}

 	/**************************************************************
	* Generate Tile Color
	**************************************************************/
	public function generate_tile_color(){
		if( $this->get_data( 'color-ms-tile' ) ){
			$color = $this->sanitize_hex_color( $this->get_data( 'color-ms-tile' ) );
			echo '<meta name="msapplication-TileColor" content="', esc_attr( $color ), '"/>', "\n";
		}
		if( $this->get_data( 'color-android-theme' ) ){
			$color = $this->sanitize_hex_color( $this->get_data( 'color-android-theme' ) );
			echo '<meta name="theme-color" content="', esc_attr( $color ), '"/>', "\n";
		}
	}

	/**************************************************************
	* Get Data Value
	* @param: $key : data key( id )
	**************************************************************/
	private function get_data( $key ){
		global $ellul_schranz_data;
		return isset( $ellul_schranz_data[ $key ] ) ? $ellul_schranz_data[ $key ] : null;
	}

	/**************************************************************
	* Sanitize HexColor
	* @param: $hex
	**************************************************************/
	private function sanitize_hex_color( $hex = null ){
		return ( empty( $hex ) || ! preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $hex ) ) ? '' : $hex;
	}

	/**************************************************************
	* Hex 2 RGBA
	* @param: $hex [ hexadecimal color ]
	* @param: $alpha [ float from 0 to 1 ]
	**************************************************************/
	private function hex2rgba( $hex = null, $alpha = 1 ){
		$hex = $this->sanitize_hex_color( $hex );
		if( $hex ){
			$hex = ltrim( $hex, '#' );
			if( strlen( $hex ) == 3 ){
				$red   = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$green = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$blue  = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$red   = hexdec( substr( $hex, 0, 2 ) );
				$green = hexdec( substr( $hex, 2, 2 ) );
				$blue  = hexdec( substr( $hex, 4, 2 ) );
			}
			$alpha = (float) $alpha >= 0 && $alpha <= 1 ? $alpha : 1;
			return sprintf(
				'rgba(%1$u,%2$u,%3$u,%4$.1f)',
				$red, $green, $blue, $alpha
			);
		}
		return '';
	}


	/**************************************************************
	* Hex Brightness
	* @param: $hex [ hexadecimal color ]
	* @param: $brightness [ float from 0 to 1 ]
	**************************************************************/
	private function hex_brightness( $hex, $brightness = 1 ){
		$hex    = str_replace( '#', '', $hex );
		$length = strlen( $hex );
		if( 6 === $length ){
			$rgb = str_split( $hex, 2 );
		} elseif( 3 === $length ){
			$rgb = str_split( $hex, 1 );
		} else {
			return $hex;
		}
		$out = '';
		for( $i = 0; $i < 3; $i++ ){
			$decimal   = hexdec( $rgb[ $i ] ) * $brightness;
			$decimal   = $decimal <= 255 ? abs( round( $decimal ) ) : 255;
			$rgb[ $i ] = str_pad( dechex( $decimal ), 2, 0, STR_PAD_LEFT );
		}
		return '#' . join( '', $rgb );
	}

}

new ELLUL_SCHRANZ_Dynamic_CSS;

