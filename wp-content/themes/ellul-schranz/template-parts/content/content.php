<?php
/**
 * ELLUL_SCHRANZ Theme Template part for displaying posts.
 *
 * @package ELLUL_SCHRANZ
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php do_action( 'ellul_schranz-template-post-header' ); ?>

<?php if ( has_post_thumbnail() ): ?>
    <a href="<?php echo esc_url( get_permalink() ); ?>" class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
    </a>
<?php endif; ?>

<?php if ( get_post_type() == 'testimonial' ) { ?>


    <div class="post-content">
		<?php
		the_content( sprintf(
			wp_kses( __( 'Read more %s', 'ellul-schranz' ), [ 'span' => [ 'class' => [] ] ] ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		) );
		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ellul-schranz' ),
			'after'  => '</div>',
		] );
		?>
    </div><!-- .post-content -->


    <div class="post-header">
		<?php the_title( sprintf( '<h4 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

		if ( get_field( 'testimonial_company_name' ) != "" ) { ?><h6
                class="testimonials"><?php echo get_field( 'testimonial_job_description' ); ?></h6>
            <h6 class="testimonials"><?php echo get_field( 'testimonial_company_name' ); ?></h6>

			<?php
		} elseif ( get_field( 'testimonial_job_description' ) != "" ) {
			echo get_field( 'testimonial_company_name' );
			echo get_field( 'testimonial_job_description' );
		}
		do_action( 'ellul_schranz-template-post-footer' ); ?>
    </div><!-- .post-header -->

<?php } else { ?>

    <div class="post-header">
		<?php the_title( sprintf( '<h4 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' ); ?>
		<?php do_action( 'ellul_schranz-template-post-footer' ); ?>
    </div><!-- .post-header -->

    <div class="post-content">
		<?php
		the_content( sprintf(
			wp_kses( __( 'Read more %s', 'ellul-schranz' ), [ 'span' => [ 'class' => [] ] ] ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		) );
		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ellul-schranz' ),
			'after'  => '</div>',
		] );
		?>
    </div><!-- .post-content -->

    </article><!-- #post-## -->
<?php } ?>