<?php
/**
 * ELLUL_SCHRANZ Theme Footer
 *
 * @package ELLUL_SCHRANZ
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

</div><!-- end #content -->

<?php do_action('ellul_schranz-display-widgets', 'footer', '<div id="footer">', '</div>'); ?>

<?php do_action('ellul_schranz-display-widgets', 'footer-bottom', '<div id="footer-bottom">', '</div>'); ?>

</div><!-- end #wrap -->

<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-74947725-24', 'auto');
    ga('send', 'pageview');

</script>

<?php wp_footer();
?>

</body>
</html>
