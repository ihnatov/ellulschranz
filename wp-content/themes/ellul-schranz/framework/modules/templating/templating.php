<?php
/**
 * ELLUL_SCHRANZ Theme Templating
 *
 * @package ELLUL_SCHRANZ
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ELLUL_SCHRANZ_TPL {

	static private $page_type, $page_ID, $is_custom_404,
		$is_custom_search, $grid, $post, $inst;

	/**************************************************************
	 * Constructor
	 **************************************************************/
	public function __construct() {
		if ( is_null( self::$inst ) ) {
			add_action( 'ellul_schranz-set-page-id', [ 'ELLUL_SCHRANZ_TPL', 'set_page_ID' ] );
			add_action( 'ellul_schranz-template', [ 'ELLUL_SCHRANZ_TPL', 'render' ] );
			add_action( 'ellul_schranz-template-title', [ 'ELLUL_SCHRANZ_TPL', 'get_the_title' ] );
			add_action( 'ellul_schranz-template-subtitle', [ 'ELLUL_SCHRANZ_TPL', 'get_the_subtitle' ] );
			add_filter( 'ellul_schranz-template-title-bg', [ 'ELLUL_SCHRANZ_TPL', 'title_bg' ] );
			add_action( 'ellul_schranz-template-header', [ 'ELLUL_SCHRANZ_TPL', 'header_template' ] );
			add_action( 'ellul_schranz-template-logo', [ 'ELLUL_SCHRANZ_TPL', 'logo' ] );
			add_action( 'ellul_schranz-template-featured-image', [ 'ELLUL_SCHRANZ_TPL', 'featured_image' ] );
			add_action( 'ellul_schranz-template-post-header', [ 'ELLUL_SCHRANZ_TPL', 'post_header' ] );
			add_action( 'ellul_schranz-template-post-footer', [ 'ELLUL_SCHRANZ_TPL', 'post_footer' ] );
			add_action( 'wp_footer', [ 'ELLUL_SCHRANZ_TPL', 'back_to_top' ] );
			add_action( 'edit_category', [ 'ELLUL_SCHRANZ_TPL', 'flush_category_transient' ] );
			add_action( 'save_post', [ 'ELLUL_SCHRANZ_TPL', 'flush_category_transient' ] );
			add_filter( 'the_content_more_link', [ 'ELLUL_SCHRANZ_TPL', 'the_content_more_link' ] );
			add_filter( 'body_class', [ 'ELLUL_SCHRANZ_TPL', 'body_class' ] );
			add_filter( 'pre_get_posts', [ 'ELLUL_SCHRANZ_TPL', 'search_filter' ] );
			self::$inst = $this;
		}

		return self::$inst;
	}

	/**************************************************************
	 * Get The Current Title Set by Parent Post / Page
	 **************************************************************/
	static public function get_the_title() {
		$title = get_the_title();
		if ( ( $meta_title = self::get_meta( 'page-title', self::$page_ID ) ) ) {
			$title = apply_filters( 'the_title', $meta_title, self::$page_ID );
		} elseif ( '404' == self::$page_type && ! self::$is_custom_404 ) {
			$title = esc_html_x( '404', 'The page title', 'ellul-schranz' );
		} elseif ( 'archive' == self::$page_type ) {
			$obj = get_post_type_object( get_post_type() );
			if ( isset( $obj->labels->archives ) ) {
				$title = esc_html( $obj->labels->archives );
			} else {
				$title = esc_html_x( 'Archives', 'The page title', 'ellul-schranz' );
			}
		} elseif ( 'blog' == self::$page_type ) {
			$title = esc_html_x( 'Blog', 'The page title', 'ellul-schranz' );
		} elseif ( 'search' == self::$page_type && ! self::$is_custom_search ) {
			$title = esc_html_x( 'Search', 'The page title', 'ellul-schranz' );
		} elseif ( 'woocommerce' == self::$page_type ) {
			$title = esc_html_x( 'Shop', 'The page title', 'ellul-schranz' );
		}
		echo $title;
	}

	/**************************************************************
	 * Get The Current SubTitle Set by Parent Post / Page
	 **************************************************************/
	static public function get_the_subtitle() {
		if ( ( $meta_title = self::get_meta( 'page-subtitle', self::$page_ID ) ) ) {
			echo $meta_title;
		}
	}

	/**************************************************************
	 * Get The Title Background URL
	 **************************************************************/
	static public function title_bg( $url ) {
		if ( ( $background = self::get_meta( 'page-bg-image', self::$page_ID ) ) ) {
			return $background;
		}

		return $url;
	}

	/**************************************************************
	 * Header Layout Template
	 **************************************************************/
	static public function header_template() {
		get_template_part( 'template-parts/header/layout', self::get_data( 'header-layout' ) );
	}

	/**************************************************************
	 * Logo
	 **************************************************************/
	static public function logo() {
		$logo = self::get_data( 'header-logo' );
		$logo = ELLUL_SCHRANZ_ASSETS_URI . '/images/logo-niu.png';
		ob_start();
		get_template_part( 'template-parts/logo' );
		printf( ob_get_clean(), esc_url( home_url( '/' ) ), esc_url( $logo ) );
	}

	/**************************************************************
	 * Show / Hide Featured Image
	 **************************************************************/
	static public function featured_image() {
		$hide = absint(
			self::get_meta( 'blog-hide-featured-image', get_the_ID() ) === '0' ?
				0 : self::cascade_meta( 'blog-hide-featured-image', get_the_ID() )
		);
		if ( ! $hide ) {
			get_template_part( 'template-parts/featured-image' );
		}
	}

	/**************************************************************
	 * Back to top
	 **************************************************************/
	static public function back_to_top() {
		if ( self::get_data( 'back-to-top' ) ) {
			get_template_part( 'template-parts/back-to-top' );
		}
	}

	/**************************************************************
	 * Post entry header
	 **************************************************************/
	static public function post_header() {

		if ( ! self::get_data( 'blog-meta-on' ) ) {
			return;
		}

		$metadata = self::get_data( 'blog-metadata' );

		// show featured text
		$show_featured = isset( $metadata[ ELLUL_SCHRANZ_META_FEATURED ] ) && $metadata[ ELLUL_SCHRANZ_META_FEATURED ] ?
			true : false;
		if ( $show_featured && is_sticky() && is_home() && ! is_paged() ) {
			printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'ellul-schranz' ) );
		}

		// show publish date/time
		$show_publish_time = isset( $metadata[ ELLUL_SCHRANZ_META_PUBLISH_TIME ] ) && $metadata[ ELLUL_SCHRANZ_META_PUBLISH_TIME ] ?
			true : false;
		if ( $show_publish_time && in_array( get_post_type(), [ 'post', 'attachment' ] ) ) {
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				get_the_date( 'M' ) . '<br>' . get_the_date( 'j' ),
				esc_attr( get_the_modified_date( 'c' ) ),
				get_the_modified_date()
			);

			printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Posted on', 'Used before publish date.', 'ellul-schranz' ),
				$time_string
			);
		}

	}

	/**************************************************************
	 * Post entry footer
	 **************************************************************/
	static public function post_footer() {

		if ( ! self::get_data( 'blog-meta-on' ) ) {
			return;
		}

		$metadata = self::get_data( 'blog-metadata' );
		$output   = [];

		// show author
		$show_author = isset( $metadata[ ELLUL_SCHRANZ_META_AUTHOR ] ) && $metadata[ ELLUL_SCHRANZ_META_AUTHOR ] ?
			true : false;
		if ( $show_author ) {
			$output[] = sprintf(
				esc_html_x( '%s', 'post author', 'ellul-schranz' ),
				'<span class="byline author vcard">' . esc_html( get_the_author() ) . '</span>'
			);
		}

		// show post format
		$show_post_format = isset( $metadata[ ELLUL_SCHRANZ_META_POST_FORMAT ] ) && $metadata[ ELLUL_SCHRANZ_META_POST_FORMAT ] ?
			true : false;
		if ( $show_post_format ) {
			$format = get_post_format() ?: 'standard';
			if ( current_theme_supports( 'post-formats', $format ) ) {
				$output[] = sprintf( '<span class="post-format">%1$s<a href="%2$s">%3$s</a></span>',
					sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'ellul-schranz' ) ),
					esc_url( get_post_format_link( $format ) ),
					get_post_format_string( $format )
				);
			}
		}

		if ( 'post' == get_post_type() ) {

			// show categories
			$show_categories = isset( $metadata[ ELLUL_SCHRANZ_META_CATEGORIES ] ) && $metadata[ ELLUL_SCHRANZ_META_CATEGORIES ] ?
				true : false;
			if ( $show_categories ) {
				$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'ellul-schranz' ) );
				if ( $categories_list && self::categorized_blog() ) {
					$output[] = sprintf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
						_x( 'Categories', 'Used before category names.', 'ellul-schranz' ),
						$categories_list
					);
				}
			}

			// show tags
			$show_tags = isset( $metadata[ ELLUL_SCHRANZ_META_TAGS ] ) && $metadata[ ELLUL_SCHRANZ_META_TAGS ] ?
				true : false;
			if ( $show_tags ) {
				$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'ellul-schranz' ) );
				if ( $tags_list ) {
					$output[] = sprintf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
						_x( 'Tags', 'Used before tag names.', 'ellul-schranz' ),
						$tags_list
					);
				}
			}

		}

		// show attachment image size
		$show_attachment = isset( $metadata[ ELLUL_SCHRANZ_META_ATTACHMENT ] ) && $metadata[ ELLUL_SCHRANZ_META_ATTACHMENT ] ?
			true : false;
		if ( $show_attachment && is_attachment() && wp_attachment_is_image() ) {
			$attachment = wp_get_attachment_metadata();
			$output[]   = sprintf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
				_x( 'Full size', 'Used before full size attachment link.', 'ellul-schranz' ),
				esc_url( wp_get_attachment_url() ),
				$attachment['width'],
				$attachment['height']
			);
		}

		echo join( '<span class="post-footer-separator"></span>', $output );

		// show comments
		$show_comments = isset( $metadata[ ELLUL_SCHRANZ_META_COMMENTS ] ) && $metadata[ ELLUL_SCHRANZ_META_COMMENTS ] ?
			true : false;
		if ( $show_comments && ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( 0, 1, '%' );
			echo '</span>';
		}

	}

	/**************************************************************
	 * Main Render
	 * @page_type [ page ]: current page rendered
	 **************************************************************/
	static public function render() {
		self::render_page_title();
		self::render_before_content();
		self::render_content();
		self::render_after_content();
	}

	/**************************************************************
	 * Render Title
	 **************************************************************/
	static private function render_page_title() {
		$title = self::get_meta( 'page-title-on' ) !== '' && self::get_meta( 'page-title-on' ) !== false ?
			self::get_meta( 'page-title-on' ) :
			self::get_data( 'page-title-on' );
		$style = absint( self::cascade_meta( 'page-title-style' ) );
		if ( $title ) {
			if ( is_singular( "service" ) ) {
				get_template_part( 'template-parts/page-title/style-service', $style );
			} else if ( is_tax( 'servicescategories' ) ) {
				get_template_part( 'template-parts/page-title/style-servicescategories', $style );
			} else {
				get_template_part( 'template-parts/page-title/style', $style );
			}
		}
	}

	/**************************************************************
	 * Render Content
	 * @page_type [ page ]: current page rendered
	 **************************************************************/
	static private function render_content() {
		switch ( self::$page_type ) {
			case '404'         :
				self::render_404();
				break;
			case 'archive'     :
				self::render_archive();
				break;
			case 'blog'        :
				self::render_blog();
				break;
			case 'page'        :
				self::render_page();
				break;
			case 'post'        :
				self::render_post();
				break;
			case 'search'      :
				self::render_search();
				break;
			case 'woocommerce' :
				self::render_woocommerce();
				break;
			default:
				return;
		}
	}

	/**************************************************************
	 * Render Before Content
	 **************************************************************/
	static private function render_before_content() {
		global $post;
		self::$post = $post;
		$grid       = self::get_grid_layout();


		if ( defined( 'WPB_VC_VERSION' ) && isset( self::$post->post_content ) && stripos( self::$post->post_content, '[/vc_row]' ) !== false && ! $grid ) {
			echo '<div class="container">';
		} elseif ( isset( $grid[0], $grid[1], $grid[2] ) ) {
			if ( $grid[2] === ELLUL_SCHRANZ_LEFT_SIDEBAR ) {
				echo '<div class="container">
					<div class="row">
						<div class="span', absint( $grid[1] ), '">';
				self::get_sidebar();
				echo '</div>
						<div class="span', absint( $grid[0] ), '">';
			} elseif ( $grid[2] === ELLUL_SCHRANZ_RIGHT_SIDEBAR ) {
				echo '<div class="container">
						<div class="row">
							<div class="span', absint( $grid[0] ), '">';
			} else {
				echo '<div class="container"><div class="row"><div class="span12">';
			}
		} else {
			echo '<div class="container"><div class="row"><div class="span12">';
		}
		if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) {
			yoast_breadcrumb( '
        <p id="breadcrumbs">', '</p>
' );
		}
		if ( is_home() ) {
			echo do_shortcode( '[vc_row css=".news-headline{padding-bottom: 30px !important;}"][vc_column][evca_headline title="News"][/vc_column][/vc_row]' );
		}

	}

	/**************************************************************
	 * Render After Content
	 **************************************************************/
	static private function render_after_content() {
		$grid = self::get_grid_layout();
		if ( defined( 'WPB_VC_VERSION' ) && isset( self::$post->post_content ) && stripos( self::$post->post_content, '[/vc_row]' ) !== false && ! $grid ) {
			echo '</div>';
		} elseif ( isset( $grid[0], $grid[1], $grid[2] ) ) {
			if ( $grid[2] === ELLUL_SCHRANZ_LEFT_SIDEBAR ) {
				echo '</div>
					</div>
				</div>';
			} elseif ( $grid[2] === ELLUL_SCHRANZ_RIGHT_SIDEBAR ) {
				echo '</div>
						<div class="span', absint( $grid[1] ), '">';
				self::get_sidebar();
				echo '</div>
					</div>
				</div>';
			} else {
				echo '</div></div></div>';
			}
		} else {
			echo '</div></div></div>';
		}
	}

	/**************************************************************
	 * Render 404
	 **************************************************************/
	static private function render_404() {
		if ( self::$is_custom_404 ) {
			// our 404 WP_Query may reset with left sidebar,
			// so we need to get the correct post content
			$custom_404 = new WP_Query( [
				'p'         => self::$page_ID,
				'post_type' => 'page',
			] );
			if ( ! is_wp_error( $custom_404 ) && $custom_404->have_posts() ) {
				$custom_404->the_post();
				the_content();
			}
		} else {
			get_template_part( 'template-parts/404' );
		}

	}

	/**************************************************************
	 * Render Archive
	 **************************************************************/
	static private function render_archive() {
		if ( have_posts() ):
			if ( get_post_type() == 'testimonial' ) {
				$title = '<h3 class="page-title">' . post_type_archive_title( '', false ) . '</h3>';

			} else {
				$title = the_archive_title( '<h3 class="page-title">', '</h3>' );
			}

			echo '<header class="page-header">',
			$title;
			echo the_archive_description( '<div class="taxonomy-description">', '</div>' ),
			'</header>';

			while ( have_posts() ): the_post();
				get_template_part( 'template-parts/content/content', get_post_format() );
				?>
                <a href="<?= get_permalink() ?>" class="btn search-result-btn">Read More</a>
			<?php
			endwhile;
			the_posts_pagination( [
				'prev_text'          => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Previous page', 'ellul-schranz' ) . ' </span>',
				'next_text'          => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Next page', 'ellul-schranz' ) . ' </span>',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'ellul-schranz' ) . ' </span>',
			] );

		else :
			get_template_part( 'template-parts/content/content', 'none' );
		endif;
	}

	/**************************************************************
	 * Render Content: Page
	 **************************************************************/
	static private function render_page() {
		while ( have_posts() ): the_post();
			get_template_part( 'template-parts/content/content', 'page' );
			?>
            <a href="<?= get_permalink() ?>" class="btn search-result-btn">Read More</a>
			<?php
			if ( self::get_data( 'page-comments' ) && ( comments_open() || get_comments_number() ) ):
				comments_template();
			endif;
		endwhile;
	}

	/**************************************************************
	 * Render Content: Blog
	 **************************************************************/
	static private function render_blog() {
		if ( have_posts() ):
			while ( have_posts() ): the_post();
				get_template_part( 'template-parts/content/content', get_post_format() );

				?>
                <a href="<?= get_permalink() ?>" class="btn search-result-btn">Read More</a>
			<?php
			endwhile;
			the_posts_pagination( [
				'prev_text'          => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Previous page', 'ellul-schranz' ) . ' </span>',
				'next_text'          => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Next page', 'ellul-schranz' ) . ' </span>',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'ellul-schranz' ) . ' </span>',
			] );

		else:
			get_template_part( 'template-parts/content/content', 'none' );
		endif;
	}

	/**************************************************************
	 * Render Content: Single Post
	 **************************************************************/
	static private function render_post() {
		if ( ! is_tax( 'servicescategories' ) ) {
			while ( have_posts() ): the_post();
				get_template_part( 'template-parts/content/content', get_post_type() );
				?>
                <a href="<?= get_permalink() ?>" class="btn search-result-btn">Read More</a>
				<?php
				//~ the_post_navigation();
				if ( comments_open() || get_comments_number() ):
					comments_template();
				endif;
			endwhile;
		} else {
			get_template_part( 'template-parts/content/content', 'servicescategories' );
		}

	}

	/**************************************************************
	 * Render Search
	 **************************************************************/
	static private function render_search() {
		$grid        = self::get_grid_layout();
		$js_composer = false;
		if ( self::$is_custom_search && isset( self::$post->post_content ) && self::$post->post_content ) {
			echo do_shortcode( self::$post->post_content );
			if ( stripos( self::$post->post_content, '[/vc_row]' ) !== false && ! $grid ) {
				echo '<div class="row"><div class="span12">';
				$js_composer = true;
			}
		}
		if ( have_posts() ):
			get_template_part( 'template-parts/search/query-vars' );
			while ( have_posts() ): the_post();
				get_template_part( 'template-parts/content/content', 'search' );
				?>
                <a href="<?= get_permalink() ?>" class="btn search-result-btn">Read More</a>
			<?php
			endwhile;
			the_posts_pagination( [
				'prev_text'          => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Previous page', 'ellul-schranz' ) . ' </span>',
				'next_text'          => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Next page', 'ellul-schranz' ) . ' </span>',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'ellul-schranz' ) . ' </span>',
			] );
		else:
			get_template_part( 'template-parts/content/content', 'none' );
		endif;
		if ( $js_composer ) {
			echo '</div></div>';
		}
	}

	/**************************************************************
	 * Render WooCommerce
	 **************************************************************/
	static private function render_woocommerce() {
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		woocommerce_content();
	}

	/**************************************************************
	 * Get Grid Layout
	 **************************************************************/
	static private function get_grid_layout() {
		if ( is_null( self::$grid ) ) {
			$layout  = absint( self::cascade_meta( 'layout-default-page' ) );
			$sidebar = absint( self::get_data( 'layout-sidebar-size' ) );
			$sidebar = $sidebar >= 2 && $sidebar <= 5 ? $sidebar : 4;
			if ( $layout === ELLUL_SCHRANZ_LEFT_SIDEBAR || $layout === ELLUL_SCHRANZ_RIGHT_SIDEBAR ) {
				self::$grid = [
					( 12 - $sidebar ),
					$sidebar,
					$layout
				];
			} else {
				self::$grid = false;
			}
		}

		return self::$grid;
	}

	/**************************************************************
	 * Get Current Page Sidebars
	 **************************************************************/
	static private function get_sidebar() {
		$sidebar = self::get_meta( 'layout-sidebar' );
		if ( $sidebar && is_dynamic_sidebar( $sidebar ) ) {
			if ( ! is_active_sidebar( $sidebar ) && current_user_can( 'edit_theme_options' ) ) {
				echo '<p id="ellul_schranz-no-widgets">', esc_html_x( 'Please add widgets to your sidebar', 'Frontend no sidebar notification', 'ellul-schranz' ), '</p>';
			}
			dynamic_sidebar( $sidebar );
		} elseif ( is_dynamic_sidebar( 'sidebar' ) ) {
			if ( ! is_active_sidebar( 'sidebar' ) && current_user_can( 'edit_theme_options' ) ) {
				echo '<p id="ellul_schranz-no-widgets">', esc_html_x( 'Please add widgets to your sidebar', 'Frontend no sidebar notification', 'ellul-schranz' ), '</p>';
			}
			dynamic_sidebar( 'sidebar' );
		}
	}

	/**************************************************************
	 * Get meta data
	 * @key : meta id
	 * @args: extra arguments supplied to rwmb_meta
	 **************************************************************/
	static private function get_meta( $key, $post_id = null ) {
		$post_id = $post_id ? $post_id : self::$page_ID;

		return get_post_meta( $post_id, $key, true );
	}

	/**************************************************************
	 * Get redux config data
	 **************************************************************/
	static private function get_data( $key ) {
		global $ellul_schranz_data;
		if ( array_key_exists( $key, $ellul_schranz_data ) ) {
			return $ellul_schranz_data[ $key ];
		}

		return null;
	}

	/**************************************************************
	 * Cascade meta data to redux config data
	 **************************************************************/
	static private function cascade_meta( $key, $args = [] ) {
		global $ellul_schranz_data;
		if ( array_key_exists( $key, $ellul_schranz_data ) ) {
			if ( $meta = self::get_meta( $key, $args ) ) {
				return $meta;
			}

			return $ellul_schranz_data[ $key ];
		}

		return null;
	}

	/**************************************************************
	 * Set Current Page ID
	 * @page_type [ page ]: current page type rendered
	 **************************************************************/
	static public function set_page_ID( $page_type = null ) {
		global $wp_query;
		self::$page_type = $page_type;
		// blog page
		if ( is_home() && ! is_front_page() ) {
			self::$page_ID = get_option( 'page_for_posts' );
			// woocommerce
		} elseif ( 'woocommerce' == self::$page_type ) {
			self::$page_ID = woocommerce_get_page_id( 'shop' );
			// custom 404
		} elseif ( '404' == self::$page_type && self::get_data( '404' ) ) {
			$custom_404 = new WP_Query( [
				'p'         => self::get_data( '404' ),
				'post_type' => 'page',
			] );
			if ( ! is_wp_error( $custom_404 ) && $custom_404->have_posts() ) {
				$custom_404->the_post();
				self::$page_ID       = get_the_ID();
				self::$is_custom_404 = true;
			}
			// custom search
		} elseif ( 'search' == self::$page_type ) {
			if ( self::get_data( 'search-page' ) ) {
				$search = new WP_Query( [
					'p'         => self::get_data( 'search-page' ),
					'post_type' => 'page',
				] );
				if ( ! is_wp_error( $search ) && $search->have_posts() ) {
					$search->the_post();
					self::$page_ID          = get_the_ID();
					self::$is_custom_search = true;
				}
			}
			// page
		} elseif ( 'page' == self::$page_type && isset( $wp_query->post ) && $wp_query->post ) {
			self::$page_ID = $wp_query->post->ID;
			// archive
		} elseif ( 'archive' == self::$page_type ) {
			$queryObj = get_queried_object();
			$inherit  = [ 'category', 'post_tag', 'post_format' ];
			if ( isset( $queryObj->taxonomy ) && in_array( $queryObj->taxonomy, $inherit ) ) {
				// inherit blog settings
				self::$page_ID = get_option( 'page_for_posts' );
			}
			// post
		} elseif ( 'post' == self::$page_type && isset( $wp_query->post ) && $wp_query->post ) {
			if ( 'post' == get_post_type() ) {
				$page_for_posts = get_option( 'page_for_posts' );
				if ( $page_for_posts ) {
					self::$page_ID = $page_for_posts;
				} else {
					self::$page_ID = $wp_query->post->ID;
				}
			} else {
				self::$page_ID = $wp_query->post->ID;
			}
		}
	}

	/**************************************************************
	 * Get / Set Category Transient
	 **************************************************************/
	static private function categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'ellul_schranz_theme_categories' ) ) ) {
			$all_the_cool_cats = get_categories( [
				'fields'     => 'ids',
				'hide_empty' => 1,
				'number'     => 2,
			] );
			$all_the_cool_cats = count( $all_the_cool_cats );
			set_transient( 'ellul_schranz_theme_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			return true;
		}

		return false;
	}

	/**************************************************************
	 * Flush Category Transient
	 **************************************************************/
	static public function flush_category_transient() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		delete_transient( 'ellul_schranz_theme_categories' );
	}

	/**************************************************************
	 * Extend Body Class
	 **************************************************************/
	static public function body_class( $body_class ) {
		if ( self::get_data( 'header-sticky' ) ) {
			$body_class[] = 'sticky-header';
		}
		if ( self::get_data( 'header-menu-style' ) ) {
			$body_class[] = sprintf( 'menu-style-%u', absint( self::get_data( 'header-menu-style' ) ) );
		}
		$style        = absint( self::get_data( 'header-layout' ) );
		$style        = $style === 2 ? 2 : 1;
		$body_class[] = sprintf( 'header-style-%u', $style );

		return $body_class;
	}

	/**************************************************************
	 * Extend Body Class
	 **************************************************************/
	static public function the_content_more_link( $more_link_element ) {
		return sprintf( '<p>%s</p>', str_replace( 'more-link', 'more-link btn btn-grey', $more_link_element ) );
	}

	/**************************************************************
	 * Search Filter
	 **************************************************************/
	static public function search_filter( $query ) {
		if ( is_search() && $query->is_search ) {

			// exclude custom 404 and search page
			$post__not_in = [
				self::get_data( '404' ),
				self::get_data( 'search-page' ),
			];
			$post__not_in = array_filter( $post__not_in );
			$post__not_in = array_unique( $post__not_in );
			$post__not_in = array_values( $post__not_in );

			$query->set( 'post__not_in', $post__not_in );

			// select post types
			$search_post_types = self::get_data( 'search-post-types' );
			if ( $search_post_types != 'all' && is_array( $search_post_types ) ) {
				$post_types = [];
				foreach ( $search_post_types as $post_type => $checked ) {
					$checked = absint( $checked );
					if ( $checked ) {
						$post_types[] = $post_type;
					}
				}
				if ( $post_types ) {
					$query->set( 'post_type', $post_types );
				}
			}

		}

		return $query;
	}

}

new ELLUL_SCHRANZ_TPL;
