<?php

/**
 * Post grid category total count.
 *
 * @since 1.3
 */
function nectar_post_grid_get_category_total($category_id, $post_type) {
  
  // All.
  if( '-1' === $category_id) {
    $category_id = null;
  }
  
  if( 'post' === $post_type ) {
    
    $nectar_post_grid_cat_query = new WP_Query( array(
      'nopaging' => false,
      'posts_per_page' => 1,
      'post_type' => 'post',
      'category_name' => sanitize_text_field($category_id)
    ));
    
  } else if( 'portfolio' === $post_type ) {
    
    $nectar_post_grid_cat_query = new WP_Query( array(
      'nopaging' => false,
      'posts_per_page' => 1,
      'post_type' => 'portfolio',
      'project-type' => sanitize_text_field($category_id)
    ));
    
  }
  
  
  return $nectar_post_grid_cat_query->found_posts;
  
} 


/**
 * Post grid item display.
 *
 * @since 1.3
 */ 
if(!function_exists('nectar_post_grid_item_markup')) {
  
  function nectar_post_grid_item_markup($atts) {
      
      $markup = '';
      
      global $post;
      
      if( $post ) {

          $bg_style_markup = '';
          $category_markup = null;
          $excerpt_markup = null;
          $image_size = 'large';
          
          if( isset($atts['image_size']) && !empty($atts['image_size']) ) {
            $image_size = sanitize_text_field($atts['image_size']);
          }
          
          // Defaults
          if( !isset($atts['color_overlay_opacity']) ) {
            $atts['color_overlay_opacity'] = '0';
          }
          if( !isset($atts['color_overlay_hover_opacity']) ) {
            $atts['color_overlay_hover_opacity'] = '0';
          }
          if( !isset($atts['grid_style'])) {
            $atts['grid_style'] = 'content_overlaid';
          }
          if( !isset($atts['heading_tag'])) {
            $atts['heading_tag'] = 'default';
          }
          
          // Handle Heading Tag.
          $heading_tag = 'h3';
          switch( $atts['heading_tag'] ) {
            case 'h2':
              $heading_tag = 'h2';
              break;
            case 'default':
              $heading_tag = 'h3';
              break;
            case 'h4':
              $heading_tag = 'h4';
              break;
            default:
              $heading_tag = 'h3';
          }
          
          // Post.
          if($atts['post_type'] === 'post') {
            
            // Featured Image.
            if( has_post_thumbnail() ) { 
              if( 'lazy-load' === $atts['image_loading'] && NectarLazyImages::activate_lazy() ) {
                $bg_style_markup = 'data-nectar-img-src="'. get_the_post_thumbnail_url( $post->ID, $image_size, array( 'title' => '' ) ) .'"';
              } else {
                $bg_style_markup = 'style="background-image:url('. get_the_post_thumbnail_url( $post->ID, $image_size, array( 'title' => '' ) ) .');"';
              }
            }
            
            // Categories.
            if( isset($atts['display_categories']) && 'yes' === $atts['display_categories'] ) {
              
              $category_markup .= '<span class="meta-category">';
              
              $categories = get_the_category();
              
              if ( ! empty( $categories ) ) {
                $output = null;
                foreach ( $categories as $category ) {
                  $output .= '<a class="' . esc_attr( $category->slug ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                }
                $category_markup .=  trim( $output ); 
              }
              
              $category_markup .= '</span>'; 
              
            }
            
            // Excerpt.
            if( isset($atts['display_excerpt']) && 'yes' === $atts['display_excerpt'] && has_excerpt() ) {
              $excerpt_markup = '<div class="item-meta-extra"><span class="meta-excerpt">' . get_the_excerpt() . '</span></div>';
            }
            
            // Permalink.
            $post_perma = get_the_permalink();
            
          } 
          // Portfolio Post Type.
          else if( $atts['post_type'] === 'portfolio') {
            
            // Featured Image.
            $custom_thumbnail = get_post_meta($post->ID, '_nectar_portfolio_custom_thumbnail', true); 

            if( !empty($custom_thumbnail) ) {
              if( 'lazy-load' === $atts['image_loading'] && NectarLazyImages::activate_lazy() ) {
                $bg_style_markup = 'data-nectar-img-src="'. nectar_ssl_check( esc_url( $custom_thumbnail ) ) .'"';
              } else {
                $bg_style_markup = 'style="background-image:url('. nectar_ssl_check( esc_url( $custom_thumbnail ) ) .');"';
              }
              
            } else {
              $thumbnail_id = get_post_thumbnail_id( $post->ID );
              $image_bg = wp_get_attachment_image_src( $thumbnail_id, $image_size);
              
              if( 'lazy-load' === $atts['image_loading'] && NectarLazyImages::activate_lazy() ) {
                  $bg_style_markup = (!empty($image_bg)) ? 'data-nectar-img-src="'. esc_url( $image_bg[0] ) .'"' : '';
              } else {
                  $bg_style_markup = (!empty($image_bg)) ? 'style="background-image:url('. esc_url( $image_bg[0] ) .');"' : '';
              }
            
            }
            
            // Categories.
            $category_markup = null;
            
            if( isset($atts['display_categories']) && 'yes' === $atts['display_categories'] ) {
              
              $category_markup .= '<span class="meta-category">';
              
              $project_categories = get_the_terms($post->id,"project-type");

              if ( !empty($project_categories) ){
                $output = null;
                foreach ( $project_categories as $term ) {
                  $output .= '<a class="' . esc_attr( $term->slug ) . '" href="' . esc_url( get_category_link( $term->term_id ) ) . '">' . esc_html( $term->name ) . '</a>';
                }
                $category_markup .=  trim( $output ); 
              }
              
              $category_markup .= '</span>'; 
              
            }
            
            // Excerpt.
            if( isset($atts['display_excerpt']) && 'yes' === $atts['display_excerpt'] ) {
              $project_excerpt = get_post_meta($post->ID, '_nectar_project_excerpt', true);
              $excerpt_markup = (!empty($project_excerpt)) ? '<div class="item-meta-extra"><span class="meta-excerpt">' . $project_excerpt . '</span></div>' : '';
            }
            
            // Permalink.
            $custom_project_link = get_post_meta($post->ID, '_nectar_external_project_url', true);
            $post_perma = ( !empty($custom_project_link) ) ? $custom_project_link : get_the_permalink();
            
          }
          
          
          $bg_overlay_markup = (isset($atts['color_overlay']) && !empty($atts['color_overlay'])) ? 'style=" background-color: '. esc_attr($atts['color_overlay']) .';"' : '';
          
          
          
          
          // Main output structure.
          $markup .= '<div class="nectar-post-grid-item"> <div class="inner">';
          
          // Conditional based on style
          if( 'content_overlaid' !== $atts['grid_style'] ) { 
            $markup .= '<div class="nectar-post-grid-item-bg-wrap"><div class="nectar-post-grid-item-bg-wrap-inner"><a href="'. esc_attr($post_perma) .'"></a>'; 
          }
          
          $markup .= '<div class="nectar-post-grid-item-bg" '.$bg_style_markup.'></div>';
          
          if( 'content_overlaid' !== $atts['grid_style'] ) { 
            $markup .= '</div></div>'; 
          }
          
          if( 'content_overlaid' === $atts['grid_style'] ) {
            $markup .= '<div class="bg-overlay" '.$bg_overlay_markup.' data-opacity="'. esc_attr($atts['color_overlay_opacity']) .'" data-hover-opacity="'. esc_attr($atts['color_overlay_hover_opacity']) .'"></div>';
          }
          
          
          
          $markup .= '<div class="content" >';
          
          $markup .= '<a class="nectar-post-grid-link" href="'. esc_attr($post_perma) .'"></a>';
          
          $markup .= $category_markup;
          
          $post_title_overlay = ( isset($atts['post_title_overlay']) && 'yes' === $atts['post_title_overlay'] ) ? ' data-title-text="'.get_the_title().'"' : '';
          
          $markup .= '<div class="item-main"><'.esc_html($heading_tag).' class="post-heading"><a href="'. esc_attr($post_perma) .'"'.$post_title_overlay.'><span>'. get_the_title() .'</span></a></'.esc_html($heading_tag).'>';
          
          if( isset($atts['display_date']) && 'yes' === $atts['display_date'] ) {
            $markup .= '<span class="meta-date">' . get_the_date() . '</span>';
          }

          $markup .= $excerpt_markup;
    
          $markup .= '</div>';

          $markup .= '</div>';
          $markup .= '</div></div>';
      }
      
      return $markup;
      
  }
}