<?php
/**
 * ELLUL_SCHRANZ Theme Index
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'blog' );

get_header();

do_action( 'ellul_schranz-template' );

get_footer();
