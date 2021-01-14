<?php
/**
 * ELLUL_SCHRANZ Theme Widget Areas
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'woocommerce' );

get_header();

do_action( 'ellul_schranz-template' );

get_footer();
