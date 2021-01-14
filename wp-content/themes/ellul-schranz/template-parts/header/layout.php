<?php
/**
 * ELLUL_SCHRANZ Theme Template part for header layout
 *
 * @package ELLUL_SCHRANZ
 */

if( ! defined( 'ABSPATH' ) ){ exit; }

?>

<div id="header-wrap">

	<div class="container-fluid">
		<div class="row">
			<div class="span9">

				<div id="header">

					<!-- /// HEADER  //////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

					<div class="row">
						<div class="span3">

							<?php do_action( 'ellul_schranz-template-logo' ); ?>

						</div><!-- end .span3 -->
						<div class="span9">

                            <!-- // Mobile menu trigger // -->

                            <a href="#" id="mobile-menu-trigger">
                                <i class="fa fa-bars"></i>
                            </a>

							<?php do_action( 'ellul_schranz-display-menu' ); ?>

						</div><!-- end .span9 -->
					</div><!-- end .row -->

					<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

				</div><!-- end #header -->

			</div><!-- end .span9 -->
			<div class="span1">

				<!-- // Custom search // -->

				<div id="custom-search-form-container">

					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" id="custom-search-form" method="get" role="search">
						<input type="text" value="" name="s" id="s" placeholder="<?php esc_attr_e( 'Type and press enter to search...', 'ellul-schranz' ); ?>">
						<input type="submit" id="custom-search-submit" value="">
					</form>

				</div><!-- end #custom-search-form-container -->

				<a id="custom-search-button" href="#"></a>

			</div><!-- end .span1 -->
			<div class="span2" id="header-widget-area">

				<?php do_action( 'ellul_schranz-display-menu-widget' ); ?>

			</div><!-- end .span2 -->
		</div><!-- end .row -->
	</div><!-- end .container-fluid -->

</div><!-- end #header-wrap -->

