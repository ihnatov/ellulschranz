<?php
/**
 * ELLUL_SCHRANZ Theme Template part for displaying results in search pages.
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( sprintf( '<h5 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h5>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php do_action( 'ellul_schranz-template-post-footer' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

<br>
<br>
