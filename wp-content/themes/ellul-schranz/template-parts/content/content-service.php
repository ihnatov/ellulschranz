<?php
/**
 * ELLUL_SCHRANZ Theme Template part for displaying single posts.
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'ellul_schranz-template-post-header' ); ?>

	<div class="post-header">
		<h4 class="post-title">
			<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h4>
		<?php do_action( 'ellul_schranz-template-post-footer' ); ?>
	</div>

	<div class="post-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ellul-schranz' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
