<?php
/**
 * ELLUL_SCHRANZ Theme Archive
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'archive' );

get_header();

do_action( 'ellul_schranz-template' );

get_footer();
