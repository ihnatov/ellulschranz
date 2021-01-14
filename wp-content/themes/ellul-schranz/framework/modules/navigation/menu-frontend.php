<?php
/**
 * ELLUL_SCHRANZ Theme Frontend Navigation Walker
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

add_action( 'ellul_schranz-display-menu', 'ellul_schranz_display_menu' );
add_action( 'ellul_schranz-display-menu-widget', 'ellul_schranz_display_menu_widget' );

if( ! function_exists( 'ellul_schranz_display_menu' ) ){

	function ellul_schranz_display_menu(){
		if( has_nav_menu( 'primary' ) ){
			echo '<nav id="site-navigation" class="main-navigation" role="navigation">';
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'menu',
				'menu_class'     => 'sf-menu fixed',
				'container'      => false,
				'walker'         => new ELLUL_SCHRANZ_Theme_Walker_Nav_Menu,
			) );
			echo '</nav>';
		} elseif( current_user_can( 'edit_theme_options' ) ){
			echo '<p id="ellul_schranz-no-menu">', esc_html_x( 'Please select a menu for this Theme location from Apparence > Menus!', 'Frontend no menu notification', 'ellul-schranz' ), '</p>';
		}
	}

}

if( ! function_exists( 'ellul_schranz_display_menu_widget' ) ){

	function ellul_schranz_display_menu_widget(){
		if( is_active_sidebar( 'menu' ) ){
			dynamic_sidebar( 'menu' );
		} elseif( current_user_can( 'edit_theme_options' ) ){
			echo '<strong>', esc_html_e( 'Menu Widget Area', 'ellul-schranz' ), '</strong>';
		}
	}

}

if( ! class_exists( 'ELLUL_SCHRANZ_Theme_Walker_Nav_Menu' ) ){

	final class ELLUL_SCHRANZ_Theme_Walker_Nav_Menu extends Walker_Nav_Menu {

		protected	$is_mega_menu  = false,
					$is_widgetized = false,
					$columns       = 0;

		function start_lvl( &$output, $depth = 0, $args = array() ){
			$indent  = str_repeat( "\t", $depth );
			if( $this->is_mega_menu && $depth === 0 ){
				$output .= sprintf( "\n%s<div class=\"%s\">\n", $indent, 'sf-mega {{sf-mega-cols}}' );
			}else{
				$output .= sprintf( "\n%s<ul class=\"%s\">\n", $indent, 'sub-menu' );
			}
		}

		function end_lvl( &$output, $depth = 0, $args = array() ){
			$indent  = str_repeat( "\t", $depth );
			if( $this->is_mega_menu && $depth === 0 ){
				$output .= sprintf( "%s</div>\n", $indent );
				$output  = str_replace( '{{sf-mega-cols}}', sprintf( 'sf-mega-%u-col', $this->columns ), $output );
				$this->is_mega_menu  = false;
				$this->is_widgetized = false;
				$this->columns       = 0;
			} else {
				$output .= sprintf( "%s</ul>\n", $indent );
			}
		}

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
			global $wp_query;

			$indent    = str_repeat( "\t", $depth );
			$item_out  = '';
			$classes   = ! empty( $item->classes ) ? (array) $item->classes : array();
			$classes[] = ( $item->current ? 'current' : '' );

			$classes   = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item );

			$classes[] = ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' );
			$classes[] = ( $depth >=2 ? 'sub-sub-menu-item' : '' );
			$classes[] = ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' );
			$classes[] = 'menu-item-depth-' . $depth;

			if( $item->mega_menu && $depth === 0 ){
				$this->is_mega_menu = true;
			}

			if( $this->is_mega_menu && $depth === 1 ){

				$output .= sprintf( "\n%s<div class=\"sf-mega-section\">\n", $indent );

				$this->columns++;

				if( $item->widgetize ){

					$this->is_widgetized = true;

					$nav_widgets = get_theme_mod( 'custom-nav-widgets', array() );
					if( isset( $nav_widgets[ $item->ID ] ) ){
						$widget_id = sprintf( 'custom-nav-widget-%u', $item->ID );
						if( is_active_sidebar( $widget_id ) ){
							ob_start();
							dynamic_sidebar( $widget_id );
							$output .= ob_get_clean();
						}
					}

				}

			} else {

				if( $this->is_mega_menu && $this->is_widgetized ){
					return;
				}

				if( ! $this->is_mega_menu || $depth > 0 ){
					$classes[] = ( in_array( 'menu-item-has-children', $classes ) ? 'dropdown' : '' );
				}

				$output  .= sprintf( "\n%s<li id=\"menu-item-%u\" class=\"%s\">", $indent, $item->ID, esc_attr( implode( ' ', $classes ) ) );

				$attrs    = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
				$attrs   .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) . '"' : '';
				$attrs   .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) . '"' : '';
				$attrs   .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) . '"' : '';
				$attrs   .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

				$item_out = "\n$indent\t" . sprintf( '%1$s<a%2$s>%3$s%4$s%5$s%6$s</a>%7$s',
					$args->before,
					$attrs,
					$args->link_before,
					( $item->icon ? sprintf( '<i class="%s"></i> ', esc_attr( $item->icon ) ) : '' ),
					apply_filters( 'the_title', $item->title, $item->ID ),
					$args->link_after,
					$args->after
				);

			}

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_out, $item, $depth, $args );

		}

		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
            if( $this->is_mega_menu && $depth === 1 ){
				$output .= "</div>\n";
			} else {
				$output .= sprintf( "\n%s</li>\n", $indent );
			}
		}

	}

}
