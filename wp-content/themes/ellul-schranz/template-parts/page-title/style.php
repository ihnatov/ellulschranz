<?php
/**
 * ELLUL_SCHRANZ Theme Page Style 1
 *
 * @package ELLUL_SCHRANZ
 */


if( ! defined( 'ABSPATH' ) ){ exit; }

if (is_singular('team_member'))
{
    ?>
    <div id="page-header"
         style="background-image:url('<?php echo get_field('team_member_banner'); ?>'); background-size: cover">
        <div class="container">
            <div class="row">
                <div class="span12">

                    <h3><?php do_action('ellul_schranz-template-title'); ?></h3>
                    <p><?php do_action('ellul_schranz-template-subtitle'); ?></p>

                </div><!-- end .span12 -->
            </div><!-- end .row -->
        </div><!-- end .container -->

    </div><!-- end #page-header -->
    <?php
}

else
{
?>
<div id="page-header"
     style="background-image:url('<?php echo esc_url(apply_filters('ellul_schranz-template-title-bg', '#')); ?>');">
    <div class="container">
        <div class="row">
            <div class="span12">

                <h3><?php do_action('ellul_schranz-template-title'); ?></h3>
                <p><?php do_action('ellul_schranz-template-subtitle'); ?></p>

            </div><!-- end .span12 -->
        </div><!-- end .row -->
    </div><!-- end .container -->

</div><!-- end #page-header -->
<?php
}