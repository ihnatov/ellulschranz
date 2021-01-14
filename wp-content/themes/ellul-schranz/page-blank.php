<?php
/*
 * Template Name: Blank Template
 *
 * ELLUL_SCHRANZ Theme Blank Page Template
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'page' );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>

	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ellul-schranz' ); ?></a>

	<div id="wrap">

		<div id="content">

			<?php do_action( 'ellul_schranz-template' ); ?>

		</div>

	</div>

	<?php wp_footer(); ?>

</body>
</html>
