<?php
/**
 * ELLUL_SCHRANZ Theme Search Form
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>
<form action="<?php echo esc_url( home_url( '/' ) ); ?>" id="searchform" class="search-form" method="get" role="search">
	<div>
		<label class="screen-reader-text"><?php esc_html_e( 'Search for:', 'ellul-schranz' ); ?></label>
		<input type="search" name="s" class="search-field" value="<?php the_search_query(); ?>" placeholder="<?php esc_html_e( 'Search...', 'ellul-schranz' ); ?>" title="<?php esc_html_e( 'Search...', 'ellul-schranz' ); ?>" />
		<input type="submit" id="searchsubmit" value="" />
	</div>
</form>
