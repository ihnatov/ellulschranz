<?php
/**
 * ELLUL_SCHRANZ Theme 404 Page
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', '404' );

get_header();

do_action( 'ellul_schranz-template' );

get_footer();
