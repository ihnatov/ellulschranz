<?php
/**
 * ELLUL_SCHRANZ Contact Widget
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

add_action( 'widgets_init', array( 'ELLUL_SCHRANZ_Contact_Widget', '__custom_register_widget' ) );

class ELLUL_SCHRANZ_Contact_Widget extends WP_Widget {

	static function __custom_register_widget(){
		register_widget( __CLASS__ );
	}

	public function __construct(){
		$widget = array(
			'classname'   => 'ellul_schranz_widget_contact_info',
			'description' => esc_html__( 'Display your contact info', 'ellul-schranz' ),
		);
		$control = array(
			'width'   => 300,
			'id_base' => 'ellul_schranz_contact_widget'
		);
		parent::__construct( 'ellul_schranz_contact_widget', esc_html( 'Custom Widget: Contact' ), $widget, $control );
	}

	public function widget( $args, $instance ){
		echo $args['before_widget'];
		if( ! empty( $instance['title'] ) ){
			echo $args['before_title'], apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ), $args['after_title'];
		}
		echo '<ul>';
		if( ! empty( $instance['address'] ) ){
			echo '<li>',
				( $instance['icons'] ? '<i class="ifc-geo_fence"></i> ' : '' ),
				nl2br( esc_html( $instance['address'] ) ),
				'</li>';
		}
		if( ! empty( $instance['phone'] ) ){
			echo '<li>',
				( $instance['icons'] ? '<i class="ifc-phone1"></i> ' : '' ),
				esc_html( $instance['phone'] ),
				'</li>';
		}
		if( ! empty( $instance['email'] ) ){
			echo '<li>',
				( $instance['icons'] ? '<i class="ifc-message"></i> ' : '' ),
				'<a href="mailto:', esc_attr( $instance['email'] ), '">', esc_html( $instance['email'] ), '</a>',
				'</li>';
		}
		if( ! empty( $instance['website'] ) ){
			echo '<li>',
				( $instance['icons'] ? '<i class="ifc-domain"></i> ' : '' ),
				'<a href="', esc_url( $instance['website'] ), '">', esc_url( $instance['website'] ), '</a>',
				'</li>';
		}
		echo '</ul>';
		echo $args['after_widget'];
	}

	public function form( $instance ){
		$instance = wp_parse_args( $instance, array(
			'title'   => '',
			'address' => '',
			'phone'   => '',
			'email'   => '',
			'website' => '',
			'icons'   => false,
		) );
		echo '
			<p>
				<label for="', esc_attr( $this->get_field_id( 'title' ) ), '">
					', esc_html__( 'Title:', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'title' ) ), '" name="', esc_attr( $this->get_field_name( 'title' ) ), '" value="', esc_attr( $instance['title'] ), '" class="widefat" />
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'address' ) ), '">
					', esc_html_x( 'Address:', 'contact widget', 'ellul-schranz' ), '
					<textarea id="', esc_attr( $this->get_field_id( 'address' ) ), '" name="', esc_attr( $this->get_field_name( 'address' ) ), '" class="widefat" cols="5" rows="3">', esc_html( $instance['address'] ), '</textarea>
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'phone' ) ), '">
					', esc_html_x( 'Phone:', 'contact widget', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'phone' ) ), '" name="', esc_attr( $this->get_field_name( 'phone' ) ), '" value="', esc_attr( $instance['phone'] ), '"  class="widefat" />
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'email' ) ), '">
					', esc_html_x( 'E-mail:', 'contact widget', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'email' ) ), '" name="', esc_attr( $this->get_field_name( 'email' ) ), '" value="', esc_attr( $instance['email'] ), '"  class="widefat" />
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'website' ) ), '">
					', esc_html_x( 'Website:', 'contact widget', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'website' ) ), '" name="', esc_attr( $this->get_field_name( 'website' ) ), '" value="', esc_attr( $instance['website'] ), '"  class="widefat" />
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'icons' ) ), '">
					<input type="checkbox" id="', esc_attr( $this->get_field_id( 'icons' ) ), '" name="', esc_attr( $this->get_field_name( 'icons' ) ), '" value="1"', checked( $instance['icons'] ), '/>
					', esc_html_x( 'Show icons', 'contact widget', 'ellul-schranz' ), '
				</label>
			</p>';
	}

	public function update( $new_instance, $old_instance ){
		$instance = wp_parse_args( $new_instance, array(
			'title'   => '',
			'address' => '',
			'phone'   => '',
			'email'   => '',
			'website' => '',
			'icons'   => false,
		) );
		$instance = array_map( 'strip_tags', $instance );
		$instance = array_map( 'trim', $instance );
		$instance['title'] = sanitize_text_field( $instance['title'] );
		$instance['icons'] = (bool) $instance['icons'];
		return $instance;
	}

}
