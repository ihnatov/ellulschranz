<?php
/**
 * ELLUL_SCHRANZ Theme Archive
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

do_action( 'ellul_schranz-set-page-id', 'archive' );

get_header();

$shortcode = <<<SHORTCODE
[vc_row full_width="stretch_row_content_no_spaces" 
css=".vc_custom_1485852365037{margin-bottom: 0px !important;border-top-width: 20px !important;}"]
    [vc_column]
        [vc_column_text]
            [home_services include_news="false"]
        [/vc_column_text]
    [/vc_column]
[/vc_row]
SHORTCODE;

echo '<div class="container">';
echo '<div class="entry-content">';
echo do_shortcode($shortcode);
echo '</div>';
echo '</div>';

get_footer();