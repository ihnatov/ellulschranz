<?php
/**
 * ELLUL_SCHRANZ Theme Widget Areas
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

final class DNS_Widget_Manager {

	private	$hook;
	static	$widget_areas = array();

	public function __construct( $widget_areas = array() ){
		if( isset( $_POST['restore'] ) && wp_verify_nonce( $_POST['restore'], 'restore-defaults' ) ){
			delete_option( 'ellul_schranz-section' );
		}
		self::$widget_areas = is_array( $widget_areas ) ? $widget_areas : array();
		self::$widget_areas = get_option( 'ellul_schranz-section', self::$widget_areas );
		$this->register();
	}

	private function register(){
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'widgets_init', array( $this, 'widget_area_init' ), 12 );
		add_action( 'ellul_schranz-display-widgets', array( $this, 'do_widgets' ), 10, 4 );
		add_action( 'wp_ajax_update_status', array( $this, 'try_update_status' ) );
		add_action( 'wp_ajax_update_widget_area', array( $this, 'try_update_widget_area' ) );
	}

	public function widget_area_init(){
		if( is_array( self::$widget_areas ) ){
			foreach( self::$widget_areas as &$areas ){
				if( isset( $areas['rows'] ) && is_array( $areas['rows'] ) ){
					foreach( $areas['rows'] as &$area ){
						if( isset( $area['columns'] ) && is_array( $area['columns'] ) ){
							foreach( $area['columns'] as &$columns ){
								if( is_array( $columns ) ){
									foreach( $columns['elements'] as $id => &$widget ){
										if( isset( $widget['status'] ) && $widget['status'] === 'closed' ){
											continue;
										}
										$widget = wp_parse_args( $widget, array(
											'name'          => sprintf( '%1$s %2$s', __( 'Custom Widget:', 'ellul-schranz' ), $id ),
											'id'            => $id,
											'class'         => '',
											'description'   => '',
											'before_widget' => '<div id="%1$s" class="widget %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h5 class="widget-title">',
											'after_title'   => '</h5>',
										) );
										register_sidebar( $widget );
									}
								}
							}
						}
					}
				}
			}
		}
	}

	static function do_widgets( $area = null, $before = '', $after = '', $container = 'container' ){
		self::$widget_areas = get_option( 'ellul_schranz-section', self::$widget_areas );
		$enabled = false;
		if( isset( self::$widget_areas[ $area ], self::$widget_areas[ $area ]['enabled'] ) ){
			$enabled = ( bool ) self::$widget_areas[ $area ]['enabled'];
		}
		$rows = isset( self::$widget_areas[ $area ]['rows'] ) && is_array( self::$widget_areas[ $area ]['rows'] ) ?
					self::$widget_areas[ $area ]['rows'] : array();
		if( $enabled && $area && $rows ){
			echo $before;
			foreach( $rows as $area ){
				if( isset( $area['columns'] ) && is_array( $area['columns'] ) ){
					echo '<div class="', esc_attr( $container ), '"><div class="row">';
					foreach( $area['columns'] as $col ){
						$size = isset( $col['size'] ) ? absint( $col['size'] ) : 12;
						echo '<div class="span' . $size . '">';
						if( isset( $col['elements'] ) && is_array( $col['elements'] ) ){
							foreach( $col['elements'] as $id => $widget ){
								if( is_active_sidebar( $id ) ){
									dynamic_sidebar( $id );
								} elseif( current_user_can( 'edit_theme_options' ) ){
									echo '<strong>', esc_html_e( 'Widget area', 'ellul-schranz' ), '</strong>';
									if( isset( $widget['name'] ) && $widget['name'] ){
										echo '<br/>', esc_html( $widget['name'] );
									}
								}
							}
						}
						echo '</div>';
					}
					echo '</div></div>';
				}
			}
			echo $after;
		}
	}

	public function admin_menu(){
		$this->hook = add_theme_page(
			__( 'Widget Layouts', 'ellul-schranz' ),
			__( 'Widget Layouts', 'ellul-schranz' ),
			'manage_options',
			'widget-areas',
			array( $this, 'render_page' )
		);
	}

	public function admin_scripts( $hook ){
		if( $this->hook === $hook ){
			wp_enqueue_script( 'underscore' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-droppable' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_style( 'ellul_schranz-widget-area', ELLUL_SCHRANZ_MODULES_URI . '/widget-areas/widget-areas.css', null, ELLUL_SCHRANZ_VERSION );
			wp_enqueue_style( 'ellul_schranz-widget-area', ELLUL_SCHRANZ_MODULES_URI . '/widget-areas/widget-areas.css', null, ELLUL_SCHRANZ_VERSION );
			wp_enqueue_script( 'ellul_schranz-widget-area', ELLUL_SCHRANZ_MODULES_URI . '/widget-areas/widget-areas.js', array( 'jquery' ), ELLUL_SCHRANZ_VERSION, true );
			wp_localize_script( 'ellul_schranz-widget-area', 'ellul_schranz_tpl', array(
				'row_title'   => __( 'Untitled row', 'ellul-schranz' ),
				'row_text'    => __( 'Row name', 'ellul-schranz' ),
				'row_columns' => __( 'Columns', 'ellul-schranz' ),
				'row_remove'  => __( 'Remove', 'ellul-schranz' ),
				'columns'     => range( 1, 12 ),
				'confirm'     => esc_html__( 'You are about to reset the widget areas to there default settings. Do you want to continue?', 'ellul-schranz' ),
				'wp'          => array(
					'ajax'    => admin_url( 'admin-ajax.php' ),
					'img_src' => sprintf( '%s/widget-areas/', ELLUL_SCHRANZ_MODULES_URI ),
					'nonce'   => wp_create_nonce( 'widget-areas' ),
				),
			) );
		}
	}

	public function render_page(){
		$this->try_update_status();
		$this->try_update_widget_area();
		$areas = self::$widget_areas;
		echo '<div class="wrap">
				<h1>', esc_html__( 'Widget Layouts', 'ellul-schranz' ), '</h1>
				<form action="#" method="post" id="widget-area-form">
				<button id="ellul_schranz-restore" type="submit" name="restore" class="button button-primary" value="', wp_create_nonce( 'restore-defaults' ), '">
					', __( 'Restore Default Settings', 'ellul-schranz' ), '
				</button>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th>', esc_html__( 'Area name', 'ellul-schranz' ),'</th>
							<th width="10%">', esc_html__( 'Status', 'ellul-schranz' ),'</th>
							<th width="10%">', esc_html__( 'Toggle', 'ellul-schranz' ),'</th>
						</tr>
					</thead>
					<tbody>';
		if( self::$widget_areas ){
			foreach( self::$widget_areas as $index => $area ){
				echo '<tr>
					<td>
						', esc_html( isset( $area['name'] ) ? $area['name'] : $index ), '
						<div class="widget-areas"', ( $area['enabled'] ? ' style="display:block"' : '' ),'>
							<ul class="area-placeholder" data-index="', esc_attr( $index ),'">';
				if( isset( $area['rows'] ) && $area['rows'] ){
					foreach( $area['rows'] as $single_area ){
						$index_area = sprintf( 'rows[%s]', $index );
						echo '<li class="widget-block">';
						if( isset( $single_area['name'] ) ){
							echo '<span class="area-move dashicons dashicons-image-flip-vertical"></span>
								<a href="#" class="area-remove">', __( 'Remove', 'ellul-schranz' ), '</a>
								<div class="area-customize">
									<div class="section">
										<label>
											', __( 'Row name', 'ellul-schranz' ),'
										</label>
										<input type="text" name="', esc_attr( $index_area ), '[name][]" value="', esc_attr( $single_area['name'] ),'"/>
									</div>
									<div class="section">
										<label>
											', __( 'Columns', 'ellul-schranz' ),'
										</label>
										<select class="area-columns-size" name="', esc_attr( $index_area ),'[column][]">';
							$count = $single_area['columns'] ? count( $single_area['columns'] ) : 0;
							for( $i = 1; $i <= 12; $i++ ):
								echo '<option value="', $i, '"', ( $count == $i ? ' selected="selected"' : '' ), '>', $i,'</option>';
							endfor;
							echo '		</select>
										<div class="area-columns-outer">
											<div class="area-columns-inner">
												<div class="area-columns">';
													if( $count > 0 ){
														$offset = 0;
														for( $i = 0, $c = ( $count - 1 ); $i < $c; $i++ ){
															echo '<div class="widget-column" style="left:', $offset = absint( ( $single_area['columns'][ $i ]['size'] * 22 ) + $offset ), 'px">

															</div>';
														}
													}
							echo '				</div>';
							if( $count > 0 ){
								echo '<input type="hidden" class="area-size" name="', esc_attr( $index_area ), '[size][]" value="';
								for( $i = 0, $a = array(); $i < $count; $i++ ){
									$a[] = $single_area['columns'][ $i ]['size'];
								}
								echo implode( ',', $a );
								echo '"/>';
							}
							echo '			</div>
										</div>
									</div>
								</div>';
						}
						echo '</li>';
					}
				}
				echo'		</ul>
							<a href="#" class="area-add" data-index="', esc_attr( $index ),'" title="', esc_attr__( 'Add new row', 'ellul-schranz' ),'">
								<i class="dashicons dashicons-plus"></i>
							</a>
						</div>
					</td>
					<td>
						<button type="submit" name="change_status[', esc_attr( $index ),'][enabled]" value="', esc_attr( $area['enabled'] ? '0' : '1' ), '" class="on-off-btn">
							<img src="', ELLUL_SCHRANZ_MODULES_URI, '/widget-areas/', esc_attr( $area['enabled'] ? 'on.png' : 'off.png' ), '"/>
						</button>
						<img class="ajax-loader" src="', esc_url( ELLUL_SCHRANZ_ASSETS_URI ), '/images/ajax-loader.gif" alt="', esc_html__( 'Saving...', 'ellul-schranz' ), '" style="display:none;"/>
					</td>
					<td>
						<a href="#" class="edit-area" title="', esc_attr__( 'Edit area', 'ellul-schranz' ), '">',
							(
								$area['enabled'] ?  '<i class="dashicons dashicons-arrow-up"></i>' :
													'<i class="dashicons dashicons-arrow-down"></i>'
							),
						'</a>
					</td>
				</tr>';
			}
		} else {
			echo '<tr>
				<td colspan="3">', esc_html__( 'No widget areas defined.', 'ellul-schranz' ), '</td>
			</tr>';
		}
		echo '		</tbody>
					<tfoot>
						<tr>
							<td colspan="3">
								<button type="submit" name="update_areas" value="', wp_create_nonce( 'update-areas' ), '" class="button button-primary" id="update-areas">
									', __( 'Update areas', 'ellul-schranz' ), '
								</button>
								<img class="ajax-loader" src="', esc_url( ELLUL_SCHRANZ_ASSETS_URI ), '/images/ajax-loader.gif" alt="', esc_html__( 'Saving...', 'ellul-schranz' ), '" style="display:none;"/>
							</td>
						</tr>
					</tfoot>
				</table>
				<input type="hidden" name="nonce" value="', wp_create_nonce( 'widget-areas' ), '" />
				</form>
			</div>';
		echo '<script type="text/html" id="area-template">
		<li class="widget-block">
			<span class="area-move dashicons dashicons-image-flip-vertical ui-sortable-handle"></span>
			<a href="#" class="area-remove"><%- data.row_remove %></a>
			<div class="area-customize">
				<div class="section">
					<label>
						<%- data.row_text %>
					</label>
					<input name="rows[<%- data.section %>][name][]" value="<%- data.row_title %>" type="text"/>
				</div>
				<div class="section">
					<label>
						<%- data.row_columns %>
					</label>
					<select class="area-columns-size" name="rows[<%- data.section %>][column][]">
						<% for( var i in data.columns ){ %>
						<option value="<%- data.columns[ i ] %>"><%- data.columns[ i ] %></option>
						<% } %>
					</select>
					<div class="area-columns-outer">
						<div class="area-columns-inner">
							<div class="area-columns"></div>
							<input type="hidden" class="area-size" name="rows[<%- data.section %>][size][]" value="12" />
						</div>
					</div>
				</div>
			</div>
		</li>
		</script>';
	}

	public function try_update_status(){
		if( isset( $_POST['change_status'], $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'widget-areas' ) ){
			reset( $_POST['change_status'] );
			$area  = key( $_POST['change_status'] );
			$enabled = (bool)$_POST['change_status'][ $area ]['enabled'];
			if( isset( self::$widget_areas[ $area ] ) ){
				self::$widget_areas[ $area ]['enabled'] = $enabled;
				update_option( 'ellul_schranz-section', self::$widget_areas );
			}
		}
	}

	public function try_update_widget_area(){
		if( isset( $_POST['update_areas'] ) && wp_verify_nonce( $_POST['update_areas'], 'update-areas' ) ){
			if( isset( $_POST['rows'] ) && $_POST['rows'] ){
				$areas = array();
				foreach( self::$widget_areas as $index => $area ){
					$areas[ $index ] = 0;
				}
				foreach( $_POST['rows'] as $index => $area ){
					if( isset( $area['name'], $area['column'], $area['size'] ) ){
						$rows = array();
						for( $i = 0, $c = count( $area['name'] ); $i < $c; $i++ ){
							if( isset( $area['name'][ $i ], $area['column'][ $i ], $area['size'][ $i ] ) ){
								$name   = trim( wp_strip_all_tags( $area['name'][ $i ] ) );
								$column = absint( $area['column'][ $i ] );
								$size   = explode( ',', $area['size'][ $i ] );
								if( ! empty( $name ) && $column >= 1 && $column <= 12 && $column === count( $size ) && array_sum( $size ) === 12 ){
									$slug  = sprintf( '%s-%s', $index, sanitize_title_with_dashes( $name ) );
									$row = array();
									$row['name'] = $name;
									$row['columns'] = array();
									for( $x = 0; $x < $column; $x++ ){
										$row['columns'][] = array(
											'size'     => absint( $size[ $x ] ),
											'elements' => array(
												sprintf( '%s-%u', $slug, ( $x + 1 ) ) => array(
													'name' => sprintf( '%s: %s %u', $name, __( 'Column', 'ellul-schranz' ), ( $x + 1 ) ),
												),
											),
										);
									}
									$rows[] = $row;
									$areas[ $index ] |= 1;
								}
							}
						}
						self::$widget_areas[ $index ]['rows'] = $rows;
					}
				}
				foreach( $areas as $index => $done ){
					if( ! $done ){
						self::$widget_areas[ $index ]['rows'] = array();
					}
				}
			} else {
				foreach( self::$widget_areas as &$area ){
					$area['rows'] = array();
				}
			}
			update_option( 'ellul_schranz-section', self::$widget_areas );
		}
	}

}

new DNS_Widget_Manager( @$ellul_schranz_config['ellul_schranz-section'] );
