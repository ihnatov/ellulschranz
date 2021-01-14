<?php
/**
 * ELLUL_SCHRANZ Flicker Widget
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

add_action( 'widgets_init', array( 'ELLUL_SCHRANZ_Flicker_Widget', '__custom_register_widget' ) );

class ELLUL_SCHRANZ_Flicker_Widget extends WP_Widget {

	static function __custom_register_widget(){
		register_widget( __CLASS__ );
	}

	public function __construct(){
		$widget = array(
			'classname'   => 'ellul_schranz_widget_flickr',
			'description' => esc_html__( 'Display your Flickr photos', 'ellul-schranz' ),
		);
		$control = array(
			'width'   => 300,
			'id_base' => 'ellul_schranz_flicker_widget'
		);
		parent::__construct( 'ellul_schranz_flicker_widget', esc_html( 'Custom Widget: Flicker Badge' ), $widget, $control );
	}

	public function widget( $args, $instance ){
		echo $args['before_widget'];
		if( ! empty( $instance['title'] ) ){
			echo $args['before_title'], apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ), $args['after_title'];
		}
		if( ! empty( $instance['flickr_id'] ) ){
			printf(
				'<div class="flickr-feed feed-size-%5$s">
					<script type="text/javascript" src="//www.flickr.com/badge_code_v2.gne?layout=x&amp;count=%1$u&amp;source=%2$s&amp;%2$s=%3$s&amp;display=%4$s&amp;size=%5$s"></script>
				</div>',
				absint( $instance['images'] ), esc_attr( $instance['source'] ), esc_attr( $instance['flickr_id'] ),
				esc_attr( $instance['display'] ), esc_attr( $instance['size'] )
			);
		} else {
			esc_html_e( 'The Flickr Badge ID is not set.', 'ellul-schranz' );
		}
		echo $args['after_widget'];
	}

	public function form( $instance ){
		$instance = wp_parse_args( $instance, array(
			'title'     => '',
			'flickr_id' => '',
			'source'    => 'user',
			'display'   => 'latest',
			'size'      => 's',
			'images'    => 9,
		) );
		echo '
			<p>
				<label for="', esc_attr( $this->get_field_id( 'title' ) ), '">
					', esc_html__( 'Title:', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'title' ) ), '" name="', esc_attr( $this->get_field_name( 'title' ) ), '" value="', esc_attr( $instance['title'] ), '" class="widefat" />
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'source' ) ), '">
					', esc_html__( 'Flickr ID type:', 'ellul-schranz' ), '
					<select id="', esc_attr( $this->get_field_id( 'source' ) ), '" name="', esc_attr( $this->get_field_name( 'source' ) ), '" class="widefat">
						<option value="user"', ( $instance['source'] === 'user' ? ' selected="selected"' : '' ), '>', esc_html_x( 'User', 'flickr user', 'ellul-schranz' ), '</option>
						<option value="group"', ( $instance['source'] === 'group' ? ' selected="selected"' : '' ), '>', esc_html_x( 'Group', 'flickr group', 'ellul-schranz' ), '</option>
					</select>
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'flickr_id' ) ), '">
					', esc_html__( 'Flickr ID:', 'ellul-schranz' ), '
					<input type="text" id="', esc_attr( $this->get_field_id( 'flickr_id' ) ), '" name="', esc_attr( $this->get_field_name( 'flickr_id' ) ), '" value="', esc_attr( $instance['flickr_id'] ), '"  class="widefat" />
				</label>
			</p>
			<p>', esc_html__( 'You can find the Flickr ID on', 'ellul-schranz' ), ' <a href="http:', '//idgettr.com/" target="_blank">http:', '//idgettr.com/</a></p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'display' ) ), '">
					', esc_html_x( 'Display:', 'flickr display images', 'ellul-schranz' ), '
					<select id="', esc_attr( $this->get_field_id( 'display' ) ), '" name="', esc_attr( $this->get_field_name( 'display' ) ), '" class="widefat">
						<option value="latest"', ( $instance['display'] === 'latest' ? ' selected="selected"' : '' ), '>', esc_html_x( 'Latest', 'flickr display image', 'ellul-schranz' ), '</option>
						<option value="random"', ( $instance['display'] === 'random' ? ' selected="selected"' : '' ), '>', esc_html_x( 'Random', 'flickr display image', 'ellul-schranz' ), '</option>
					</select>
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'size' ) ), '">
					', esc_html__( 'Image Size:', 'ellul-schranz' ), '
					<select id="', esc_attr( $this->get_field_id( 'size' ) ), '" name="', esc_attr( $this->get_field_name( 'size' ) ), '" class="widefat">
						<option value="s"', ( $instance['size'] === 's' ? ' selected="selected"' : '' ), '>', esc_html_x( 'Small', 'flickr image size', 'ellul-schranz' ), '</option>
						<option value="t"', ( $instance['size'] === 't' ? ' selected="selected"' : '' ), '>', esc_html_x( 'Thumbnail', 'flickr image size', 'ellul-schranz' ), '</option>
						<option value="m"', ( $instance['size'] === 'm' ? ' selected="selected"' : '' ), '>', esc_html_x( 'Medium', 'flickr image size', 'ellul-schranz' ), '</option>
					</select>
				</label>
			</p>
			<p>
				<label for="', esc_attr( $this->get_field_id( 'images' ) ), '">
					', esc_html__( 'Number of images to show:', 'ellul-schranz' ), '
					<input type="number" id="', esc_attr( $this->get_field_id( 'images' ) ), '" name="', esc_attr( $this->get_field_name( 'images' ) ), '" value="', absint( $instance['images'] ), '" min="1" step="1" size="3"/>
				</label>
			</p>';
	}

	public function update( $new_instance, $old_instance ){
		$instance = wp_parse_args( $new_instance, array(
			'title'     => '',
			'flickr_id' => '',
			'source'    => '',
			'display'   => 'latest',
			'size'      => 's',
			'images'    => 9,
		) );
		$sizes    = array( 's', 't', 'm' );
		$instance = array_map( 'strip_tags', $instance );
		$instance['title']   = sanitize_text_field( $instance['title'] );
		$instance['source']  = $instance['source'] === 'group' ? 'group' : 'user';
		$instance['display'] = $instance['display'] === 'random' ? 'random' : 'latest';
		$instance['size']    = in_array( $instance['size'], $sizes ) ? $instance['size'] : $sizes[0];
		$instance['images']  = absint( $instance['images'] );
		$instance['images']  = $instance['images'] > 0 ? $instance['images'] : 9;
		return $instance;
	}

}
