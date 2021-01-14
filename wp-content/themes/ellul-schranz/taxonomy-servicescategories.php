<?php
/**
 * ELLUL_SCHRANZ Theme Taxonomy
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'post' );

get_header();

do_action( 'ellul_schranz-template' );

get_footer();
