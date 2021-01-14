<?php
/**
 * ELLUL_SCHRANZ Social Media Widget
 *
 * @package ELLUL_SCHRANZ
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'widgets_init', [ 'ELLUL_SCHRANZ_Social_Media_Widget', '__custom_register_widget' ] );

class ELLUL_SCHRANZ_Social_Media_Widget extends WP_Widget {

	static private $_STRINGS = [];
	static private $social = [
		'facebook'       => '',
		'twitter'        => '',
		'dribbble'       => '',
		'pinterest'      => '',
		'google-plus'    => '',
		'tumblr'         => '',
		'instagram'      => '',
		'rss'            => '',
		'linkedin'       => '',
		'skype'          => '',
		'flickr'         => '',
		'vimeo-square'   => '',
		'github'         => '',
		'youtube'        => '',
		'windows'        => '',
		'dropbox'        => '',
		'xing'           => '',
		'adn'            => '',
		'android'        => '',
		'apple'          => '',
		'behance'        => '',
		'bitbucket'      => '',
		'bitcoin'        => '',
		'codepen'        => '',
		'css3'           => '',
		'delicious'      => '',
		'deviantart'     => '',
		'digg'           => '',
		'drupal'         => '',
		'empire'         => '',
		'foursquare'     => '',
		'git'            => '',
		'gittip'         => '',
		'hacker-news'    => '',
		'html5'          => '',
		'joomla'         => '',
		'jsfiddle'       => '',
		'linux'          => '',
		'maxcdn'         => '',
		'medium'         => '',
		'openid'         => '',
		'pagelines'      => '',
		'pied-piper'     => '',
		'qq'             => '',
		'rebel'          => '',
		'reddit'         => '',
		'renren'         => '',
		'share'          => '',
		'slack'          => '',
		'soundcloud'     => '',
		'spotify'        => '',
		'stack-exchange' => '',
		'stack-overflow' => '',
		'steam'          => '',
		'stumbleupon'    => '',
		'telegram'       => '',
		'tencent-weibo'  => '',
		'trello'         => '',
		'vine'           => '',
		'vk'             => '',
		'wechat'         => '',
		'weibo'          => '',
		'wordpress'      => '',
		'yahoo'          => '',
	];

	static function __custom_register_widget() {
		register_widget( __CLASS__ );
		self::$_STRINGS = [
			'title'          => esc_html_x( 'Title:', 'social widget', 'ellul-schranz' ),
			'facebook'       => esc_html_x( 'Facebook URL:', 'social widget', 'ellul-schranz' ),
			'twitter'        => esc_html_x( 'Twitter URL:', 'social widget', 'ellul-schranz' ),
			'dribbble'       => esc_html_x( 'Dribbble URL:', 'social widget', 'ellul-schranz' ),
			'pinterest'      => esc_html_x( 'Pinterest URL:', 'social widget', 'ellul-schranz' ),
			'google-plus'    => esc_html_x( 'Google+ URL:', 'social widget', 'ellul-schranz' ),
			'tumblr'         => esc_html_x( 'Tumblr URL:', 'social widget', 'ellul-schranz' ),
			'instagram'      => esc_html_x( 'Instagram URL:', 'social widget', 'ellul-schranz' ),
			'rss'            => esc_html_x( 'RSS URL:', 'social widget', 'ellul-schranz' ),
			'linkedin'       => esc_html_x( 'LinkedIn URL:', 'social widget', 'ellul-schranz' ),
			'skype'          => esc_html_x( 'Skype URL:', 'social widget', 'ellul-schranz' ),
			'flickr'         => esc_html_x( 'Flickr URL:', 'social widget', 'ellul-schranz' ),
			'vimeo-square'   => esc_html_x( 'Vimeo URL:', 'social widget', 'ellul-schranz' ),
			'github'         => esc_html_x( 'Github URL:', 'social widget', 'ellul-schranz' ),
			'youtube'        => esc_html_x( 'Youtube URL:', 'social widget', 'ellul-schranz' ),
			'windows'        => esc_html_x( 'Windows URL:', 'social widget', 'ellul-schranz' ),
			'dropbox'        => esc_html_x( 'Dropbox URL:', 'social widget', 'ellul-schranz' ),
			'xing'           => esc_html_x( 'Xing URL:', 'social widget', 'ellul-schranz' ),
			'adn'            => esc_html_x( 'ADN URL:', 'social widget', 'ellul-schranz' ),
			'android'        => esc_html_x( 'Android URL:', 'social widget', 'ellul-schranz' ),
			'apple'          => esc_html_x( 'Apple URL:', 'social widget', 'ellul-schranz' ),
			'behance'        => esc_html_x( 'Behance URL:', 'social widget', 'ellul-schranz' ),
			'bitbucket'      => esc_html_x( 'Bitbucket URL:', 'social widget', 'ellul-schranz' ),
			'bitcoin'        => esc_html_x( 'Bitcoin URL:', 'social widget', 'ellul-schranz' ),
			'codepen'        => esc_html_x( 'Codepen URL:', 'social widget', 'ellul-schranz' ),
			'css3'           => esc_html_x( 'CSS3 URL:', 'social widget', 'ellul-schranz' ),
			'delicious'      => esc_html_x( 'Delicious URL:', 'social widget', 'ellul-schranz' ),
			'deviantart'     => esc_html_x( 'Deviantart URL:', 'social widget', 'ellul-schranz' ),
			'digg'           => esc_html_x( 'Digg URL:', 'social widget', 'ellul-schranz' ),
			'drupal'         => esc_html_x( 'Drupal URL:', 'social widget', 'ellul-schranz' ),
			'empire'         => esc_html_x( 'Empire URL:', 'social widget', 'ellul-schranz' ),
			'foursquare'     => esc_html_x( 'Four Square URL:', 'social widget', 'ellul-schranz' ),
			'git'            => esc_html_x( 'Git URL:', 'social widget', 'ellul-schranz' ),
			'gittip'         => esc_html_x( 'Gittip URL:', 'social widget', 'ellul-schranz' ),
			'hacker-news'    => esc_html_x( 'Hacker News URL:', 'social widget', 'ellul-schranz' ),
			'html5'          => esc_html_x( 'HTML5 URL:', 'social widget', 'ellul-schranz' ),
			'joomla'         => esc_html_x( 'Joomla URL:', 'social widget', 'ellul-schranz' ),
			'jsfiddle'       => esc_html_x( 'jsFiddle URL:', 'social widget', 'ellul-schranz' ),
			'linux'          => esc_html_x( 'Linux URL:', 'social widget', 'ellul-schranz' ),
			'maxcdn'         => esc_html_x( 'Maxcdn URL:', 'social widget', 'ellul-schranz' ),
			'medium'         => esc_html_x( 'Medium URL:', 'social widget', 'ellul-schranz' ),
			'openid'         => esc_html_x( 'OpenID URL:', 'social widget', 'ellul-schranz' ),
			'pagelines'      => esc_html_x( 'Pagelines URL:', 'social widget', 'ellul-schranz' ),
			'pied-piper'     => esc_html_x( 'Pied Piper URL:', 'social widget', 'ellul-schranz' ),
			'qq'             => esc_html_x( 'QQ URL:', 'social widget', 'ellul-schranz' ),
			'rebel'          => esc_html_x( 'Rebel URL:', 'social widget', 'ellul-schranz' ),
			'reddit'         => esc_html_x( 'Reddit URL:', 'social widget', 'ellul-schranz' ),
			'renren'         => esc_html_x( 'RenRen URL:', 'social widget', 'ellul-schranz' ),
			'share'          => esc_html_x( 'Share URL:', 'social widget', 'ellul-schranz' ),
			'slack'          => esc_html_x( 'Slack URL:', 'social widget', 'ellul-schranz' ),
			'soundcloud'     => esc_html_x( 'SoundCloud URL:', 'social widget', 'ellul-schranz' ),
			'spotify'        => esc_html_x( 'Spotify URL:', 'social widget', 'ellul-schranz' ),
			'stack-exchange' => esc_html_x( 'StackExchange URL:', 'social widget', 'ellul-schranz' ),
			'stack-overflow' => esc_html_x( 'StackOverflow URL:', 'social widget', 'ellul-schranz' ),
			'steam'          => esc_html_x( 'Steam URL:', 'social widget', 'ellul-schranz' ),
			'stumbleupon'    => esc_html_x( 'Stumbleupon URL:', 'social widget', 'ellul-schranz' ),
			'telegram'       => esc_html_x( 'Telegram URL:', 'social widget', 'ellul-schranz' ),
			'tencent-weibo'  => esc_html_x( 'Tencent Weibo URL:', 'social widget', 'ellul-schranz' ),
			'trello'         => esc_html_x( 'Trello URL:', 'social widget', 'ellul-schranz' ),
			'vine'           => esc_html_x( 'Vine URL:', 'social widget', 'ellul-schranz' ),
			'vk'             => esc_html_x( 'VK URL:', 'social widget', 'ellul-schranz' ),
			'wechat'         => esc_html_x( 'WeChat URL:', 'social widget', 'ellul-schranz' ),
			'weibo'          => esc_html_x( 'Weibo URL:', 'social widget', 'ellul-schranz' ),
			'wordpress'      => esc_html_x( 'WordPress URL:', 'social widget', 'ellul-schranz' ),
			'yahoo'          => esc_html_x( 'Yahoo URL:', 'social widget', 'ellul-schranz' ),
		];
	}

	public function __construct() {
		$widget  = [
			'classname'   => 'ellul_schranz_social_media_widget',
			'description' => esc_html__( 'Display your Flickr photos', 'ellul-schranz' ),
		];
		$control = [
			'width'   => 300,
			'id_base' => 'ellul_schranz_social_media_widget'
		];
		parent::__construct( 'ellul_schranz_social_media_widget', esc_html( 'Custom Widget: Social Media' ), $widget, $control );
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'], apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ), $args['after_title'];
		}
		$socials = $instance;
		unset( $socials['title'] );
		foreach ( $socials as $key => $social ) {
			if ( ! empty( $social ) ) {
				echo '<a class="', esc_attr( $key ), '-icon social-icon" href="', esc_url( $social ), '" target="_blank"><i class="fa fa-', esc_attr( $key ), '"></i></a>';
			}
		}
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$instance = wp_parse_args( $instance, array_merge( [
			'title' => '',
		], self::$social ) );
		foreach ( $instance as $field => $value ) {
			echo '
				<p>
					<label for="', esc_attr( $this->get_field_id( $field ) ), '">
						', ( isset( self::$_STRINGS[ $field ] ) ? self::$_STRINGS[ $field ] : '' ), '
						<input type="text" id="', esc_attr( $this->get_field_id( $field ) ), '" name="', esc_attr( $this->get_field_name( $field ) ), '" value="', esc_attr( $value ), '" class="widefat" />
					</label>
				</p>';
		}
	}

	public function update( $new_instance, $old_instance ) {
		$accepted_inputs = array_merge( [
			'title' => '',
		], self::$social );
		$instance        = wp_parse_args( $new_instance, $accepted_inputs );
		foreach ( $instance as $key => $input ) {
			if ( ! array_key_exists( $key, $accepted_inputs ) ) {
				unset( $instance[ $key ] );
			}
		}
		$instance = array_map( 'sanitize_text_field', $instance );
		return $instance;
	}

}
