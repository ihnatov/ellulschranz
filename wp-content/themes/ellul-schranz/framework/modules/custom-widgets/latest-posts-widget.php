<?php
/**
 * ELLUL_SCHRANZ Latest Posts Widget
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

add_action( 'widgets_init', array( 'ELLUL_SCHRANZ_Latest_Posts_Widget', '__custom_register_widget' ) );
add_action( 'after_setup_theme', array( 'ELLUL_SCHRANZ_Latest_Posts_Widget', '__custom_register_image_size' ) );

class ELLUL_SCHRANZ_Latest_Posts_Widget extends WP_Widget_Recent_Posts {

	static function __custom_register_widget(){
		register_widget( __CLASS__ );
	}

	static function __custom_register_image_size(){
		add_image_size( 'thumbnail_80x85', 80, 85 );
	}

	public function __construct(){
		$widget = array(
			'classname'   => 'ellul_schranz_widget_latest_posts',
			'description' => esc_html__( 'Your site&#8217;s most recent Posts with featured image.', 'ellul-schranz' ),
		);
		$control = array(
			'width'   => 300,
			'id_base' => 'ellul_schranz_latest_posts_widget'
		);
		WP_Widget::__construct( 'ellul_schranz_latest_posts_widget', esc_html( 'Custom Widget: Recent posts with image' ), $widget, $control );
	}

	public function widget( $args, $instance ){
		if( ! isset( $args['widget_id'] ) ){
			$args['widget_id'] = $this->id;
		}

		$instance['title'] = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'ellul-schranz' );
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $instance['number'],
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if( $r->have_posts() ){
			echo $args['before_widget'];
			if( $instance['title'] ){
				echo $args['before_title'], $instance['title'], $args['after_title'];
			}
			echo '<ul>';
			while( $r->have_posts() ) : $r->the_post();
				printf(
					'<li>
						%1$s
						<a href="%2$s" class="title">%3$s</a>
						<p class="comments">
							<a href="%2$s">%4$s</a>
						</p>
						%5$s
					</li>',
					get_the_post_thumbnail( get_the_ID(), 'thumbnail_80x85' ), get_the_permalink(),
					( get_the_title() ? get_the_title() : get_the_ID() ), get_comments_number(),
					( $instance['show_date'] ? sprintf( '<span class="post-date">%s</span>', get_the_date() ) : '' )
				);
			endwhile;
			echo '</ul>';
			echo $args['after_widget'];
			wp_reset_postdata();
		}
	}

}
