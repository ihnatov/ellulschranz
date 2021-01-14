<?php
/**
 * ELLUL_SCHRANZ Theme Search Results
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'search' );

get_header();

do_action( 'ellul_schranz-template' );

get_footer();
