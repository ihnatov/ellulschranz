<?php
/**
 * ELLUL_SCHRANZ Theme Template part for displaying a default 404 page
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>

<section class="error-404 not-found">
	<header class="page-header">
		<h2 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'ellul-schranz' ); ?></h2>
	</header><!-- .page-header -->

	<div class="page-content">
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ellul-schranz' ); ?></p>

		<?php get_search_form(); ?>

	</div><!-- .page-content -->
</section><!-- .error-404 -->
