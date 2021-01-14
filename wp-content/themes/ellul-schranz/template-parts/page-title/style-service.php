<?php
/**
 * ELLUL_SCHRANZ Theme Page Style 1
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>

<div id="page-header" style="background-image:url('<?php echo the_post_thumbnail_url('full'); ?>'); background-size: cover;">
	<div class="container">
		<div class="row">
			<div class="span12">

				<h3><?php do_action( 'ellul_schranz-template-title' ); ?></h3>
				<p><?php do_action( 'ellul_schranz-template-subtitle' ); ?></p>


			</div><!-- end .span12 -->
		</div><!-- end .row -->
	</div><!-- end .container -->
</div><!-- end #page-header -->
