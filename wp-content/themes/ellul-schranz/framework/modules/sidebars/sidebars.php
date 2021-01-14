<?php
/**
 * ELLUL_SCHRANZ Theme Custom Sidebars
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

final class ELLUL_SCHRANZ_Sidebars {

	private	$hook;

	public function __construct(){
		add_action( 'widgets_init', array( $this, 'register_custom_sidebars' ), 10 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_ajax_ellul_schranz-save-sidebar', array( $this, 'save_sidebar' ) );
		add_action( 'wp_ajax_ellul_schranz-delete-sidebar', array( $this, 'delete_sidebar' ) );
	}

	public function admin_menu(){
		$this->hook = add_theme_page(
			__( 'Custom sidebars', 'ellul-schranz' ),
			__( 'Custom sidebars', 'ellul-schranz' ),
			'manage_options',
			'sidebars',
			array( $this, 'render_page' )
		);
	}

	public function render_page(){
		echo
		'<div class="wrap">
			<h1>',
				esc_html__( 'Custom Sidebars', 'ellul-schranz' ),
				'<a href="#" id="ellul_schranz-add-new-sidebar" class="add-new-h2">', esc_html__( 'Add new sidebar', 'ellul-schranz' ), '</a>',
				'&nbsp;<img id="ajax-loader" src="', esc_url( ELLUL_SCHRANZ_ASSETS_URI ),'/images/ajax-loader.gif" alt="', esc_html__( 'Saving...', 'ellul-schranz' ), '" style="display:none;">',
			'</h1>
			<form action="#" method="post">
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th>', esc_html__( 'Sidebar name', 'ellul-schranz' ),'</th>
							<th width="10%">', esc_html__( 'Actions', 'ellul-schranz' ),'</th>
						</tr>
					</thead>
					<tbody id="ellul_schranz-sidebar-wrap">';
		$sidebars = get_option( 'ellul_schranz-custom-sidebars', array() );
		$got_milk = is_array( $sidebars ) && $sidebars ? true : false;
		echo '<tr id="ellul_schranz-no-sidebars"', ( $got_milk ? ' style="display:none"' : '' ), '><td colspan="2">', esc_html__( "You haven't defined any sidebars, yet!", 'ellul-schranz' ), '</td></tr>';
		if( $got_milk ){
			foreach( $sidebars as $id => $sidebar ){
				echo '<tr>';
				echo '<td>', esc_html( $sidebar ), '</td>';
				echo '<td>
						<a href="#" class="ellul_schranz-sidebar-remove" data-id="', esc_attr( $id ), '" title="', esc_html__( 'Delete', 'ellul-schranz' ), '"><i class="dashicons dashicons-trash"></i></a>
						<img src="', esc_url( ELLUL_SCHRANZ_ASSETS_URI ),'/images/ajax-loader.gif" alt="', esc_html__( 'Deleting...', 'ellul-schranz' ), '" style="display:none;">
					</td>';
				echo '</tr>';
			}
		}
		echo  		'</tbody>
				</table>
			</form>
		</div>';
	}

	public function admin_scripts( $hook ){
		if( $this->hook === $hook ){
			wp_enqueue_script( 'ellul_schranz-custom-sidebars', ELLUL_SCHRANZ_MODULES_URI . '/sidebars/sidebars.js', array( 'jquery' ), ELLUL_SCHRANZ_VERSION, true );
			wp_localize_script( 'ellul_schranz-custom-sidebars', 'ellul_schranz_sidebars', array(
				'l10n' => array(
					'give_me_name'   => esc_html__( 'Please specify a name for your sidebar.', 'ellul-schranz' ),
					'confirm_delete' => esc_html__( 'Are you sure you want to delete this sidebar?', 'ellul-schranz' ),
				),
				'wp'   => array(
					'ajax'  => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'ellul_schranz-custom-sidebars' ),
				),
			) );
		}
	}

	public function save_sidebar(){
		$response = array(
			'success' => false,
			'message' => esc_html__( 'Unable to verify nonce', 'ellul-schranz' ),
			'markup'  => '',
		);
		if( isset( $_POST['name'], $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'ellul_schranz-custom-sidebars' ) ){
			global $wp_registered_sidebars;
			$name     = trim( wp_strip_all_tags( $_POST['name'] ) );
			$sidebar  = sprintf( 'custom-sidebar-%s', sanitize_title_with_dashes( $name ) );
			$sidebars = get_option( 'ellul_schranz-custom-sidebars', array() );
			if( $sidebar && ! array_key_exists( $sidebar, $wp_registered_sidebars ) && ! array_key_exists( $sidebar, $sidebars ) ){
				$sidebars[ $sidebar ] = $name;
				update_option( 'ellul_schranz-custom-sidebars', $sidebars );
				$response['success'] = true;
				$response['message'] = esc_html__( 'Sidebar successfully saved.', 'ellul-schranz' );
				$response['markup']  = sprintf(
					'<tr><td>%s</td><td><a href="#" class="ellul_schranz-sidebar-remove" data-id="%s" title="%s"><i class="dashicons dashicons-trash"></i></a>%s</td></tr>',
					esc_html( $name ), esc_attr( $sidebar ), esc_html__( 'Delete', 'ellul-schranz' ),
					( '<img src="' . esc_url( ELLUL_SCHRANZ_ASSETS_URI ) . '/images/ajax-loader.gif" alt="' . esc_html__( 'Deleting...', 'ellul-schranz' ) . '" style="display:none;">' )
				);
			} else {
				$response['message'] = esc_html__( 'This sidebar already exists', 'ellul-schranz' );
			}
		}
		wp_send_json( $response );
	}

	public function delete_sidebar(){
		$response = array(
			'success' => false,
			'message' => esc_html__( 'Unable to verify nonce', 'ellul-schranz' ),
		);
		if( isset( $_POST['id'], $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'ellul_schranz-custom-sidebars' ) ){
			$sidebar  = $_POST['id'];
			$sidebars = get_option( 'ellul_schranz-custom-sidebars', array() );
			if( $sidebar && array_key_exists( $sidebar, $sidebars ) ){
				unset( $sidebars[ $sidebar ] );
				update_option( 'ellul_schranz-custom-sidebars', $sidebars );
				$response['success'] = true;
				$response['message'] = esc_html__( 'Sidebar successfully deleted.', 'ellul-schranz' );
			} else {
				$response['message'] = esc_html__( 'Unable to find the specified sidebar.', 'ellul-schranz' );
			}
		}
		wp_send_json( $response );
	}

	public function register_custom_sidebars(){
		$sidebars = get_option( 'ellul_schranz-custom-sidebars', array() );
		if( $sidebars && is_array( $sidebars ) ){
			foreach( $sidebars as $id => $sidebar ){
				$widget = array(
					'name'          => sprintf( '%1$s %2$s', __( 'Sidebar:', 'ellul-schranz' ), esc_html( $sidebar ) ),
					'id'            => $id,
					'class'         => '',
					'description'   => '',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget-title">',
					'after_title'   => '</h5>',
				);
				register_sidebar( $widget );
			}
		}
	}

}

new ELLUL_SCHRANZ_Sidebars;
