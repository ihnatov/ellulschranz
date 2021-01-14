<?php
/**
 * ELLUL_SCHRANZ Theme Template part for displaying search results query vars
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>
<header class="page-header">
	<h3 class="page-title">
		<?php printf( esc_html__( 'Search Results for: %s', 'ellul-schranz' ), '<span>' . get_search_query() . '</span>' ); ?>
	</h3><br>
</header>
