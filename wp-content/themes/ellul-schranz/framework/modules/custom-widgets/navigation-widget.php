<?php
/**
 * ELLUL_SCHRANZ Navigation Widget
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

add_action( 'widgets_init', array( 'ELLUL_SCHRANZ_Navigation_Widget', '__custom_register_widget' ) );

class ELLUL_SCHRANZ_Navigation_Widget extends WP_Widget {

	static function __custom_register_widget(){
		register_widget( __CLASS__ );
	}

	public function __construct(){
		$widget = array(
			'classname'   => 'ellul_schranz_widget_navigation',
			'description' => esc_html__( 'Display a pages sub pages', 'ellul-schranz' ),
		);
		$control = array(
			'width'   => 300,
			'id_base' => 'ellul_schranz_navigation_widget'
		);
		parent::__construct( 'ellul_schranz_navigation_widget', esc_html( 'Custom Widget: Navigation' ), $widget, $control );
	}

	function widget( $args, $instance ){
		if( $instance['page_id'] > 0 ){
			echo $args['before_widget'];
			if( ! empty( $instance['title'] ) ){
				echo $args['before_title'], apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ), $args['after_title'];
			}
			echo '<ul>';
			if( $instance['show_parent'] ){
				wp_list_pages( array(
					'title_li' => null,
					'include'  => $instance['page_id'],
				) );
			}
			wp_list_pages( array(
				'title_li' => null,
				'child_of' => $instance['page_id'],
			) );
			echo '</ul>';
			echo $args['after_widget'];
		}
	}

	function form( $instance ){
		$instance = wp_parse_args( $instance, array(
			'title'       => '',
			'page_id'     => 0,
			'show_parent' => false,
		) );
		echo '
			<p>
				<label for="', esc_attr( $this->get_field_id( 'title' ) ), '">
					', esc_html__( 'Title:', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'title' ) ), '" name="', esc_attr( $this->get_field_name( 'title' ) ), '" value="', esc_attr( $instance['title'] ), '" class="widefat" />
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'page_id' ) ), '">
					', esc_html__( 'Select page:', 'ellul-schranz' ),
					wp_dropdown_pages( array(
						'echo'              => 0,
						'name'              => esc_attr( $this->get_field_name( 'page_id' ) ),
						'id'                => esc_attr( $this->get_field_id( 'page_id' ) ),
						'class'             => 'widefat',
						'selected'          => $instance['page_id'],
						'show_option_none'  => wptexturize( esc_html_x( '-- Select --', 'navigation widget', 'ellul-schranz' ) ),
						'option_none_value' => 0,
					) ),
				'</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'show_parent' ) ), '">
					<input type="checkbox" id="', esc_attr( $this->get_field_id( 'show_parent' ) ), '" name="', esc_attr( $this->get_field_name( 'show_parent' ) ), '" value="1"', checked( $instance['show_parent'] ), '/>
					', esc_html_x( 'Include parent', 'navigation widget', 'ellul-schranz' ), '
				</label>
			</p>';
	}

	function update( $new_instance, $old_instance ){
		$instance = wp_parse_args( $new_instance, array(
			'title'       => '',
			'page_id'     => 0,
			'show_parent' => false,
		) );
		$instance['title']       = sanitize_text_field( $instance['title'] );
		$instance['page_id']     = absint( $instance['page_id'] );
		$instance['show_parent'] = (bool) $instance['show_parent'];
		return $instance;
	}

}
