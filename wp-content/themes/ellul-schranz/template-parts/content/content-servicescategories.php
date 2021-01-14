<?php
/**
 * ELLUL_SCHRANZ Theme Template part for displaying single posts.
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
?>

<article id="tax-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'ellul_schranz-template-post-header' ); ?>

	<div class="post-header">
		<h4 class="post-title">
			<?php echo $term->name; ?>
		</h4>
		<?php do_action( 'ellul_schranz-template-post-footer' ); ?>
	</div>

	<div class="post-content">
        <?php echo do_shortcode(term_description($service_cat->term_id, $service_cat -> taxonomy));
?>
        <div id="categories-list-button" class="enquire-button">
            <a href="http://ellulschranz.com/contact/" class="btn btn-default btn-normal">Enquire</a>
        </div>
        <?php

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ellul-schranz' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
