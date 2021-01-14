<?php
/**
 * ELLUL_SCHRANZ Theme Header
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>

	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<?php wp_head(); ?>
    <!-- Global site tag (gtag.js) - AdWords: 959473675 --> <script async src="https://www.googletagmanager.com/gtag/js?id=AW-959473675"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-959473675'); </script>
</head>

<body <?php body_class(); ?>>

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ellul-schranz' ); ?></a>

	<div id="wrap">

		<?php do_action( 'ellul_schranz-template-header' ); ?>

		<div id="content">