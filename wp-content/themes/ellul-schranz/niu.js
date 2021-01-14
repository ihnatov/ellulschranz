/**
 * Created by christianxerri on 10/01/2017.
 */
jQuery(window).on('resize', checkSize);
jQuery(document).on('ready', checkSize);

function checkSize() {
    var $element = '';
    if (document.documentElement.clientWidth < 768) {
        jQuery(".service_swap").each(function( index ){
            $element = jQuery(this).find('> li.home-services-titles');
            $element.appendTo(jQuery(this));
        });
    }else {
        jQuery(".service_swap").each(function( index ){
            $element = jQuery(this).find('> li.home-services-titles');
            console.log($element.closest(".service_swap"));
            $element.prependTo(jQuery(this));
        });
    }
}

jQuery(document).on('ready', function () {
    var hiddenReferrerField = jQuery('.contact-form #input_1_7');
    if(hiddenReferrerField.length > 0) {
        var previousPage = document.referrer;
        hiddenReferrerField.val(previousPage);
        console.log(hiddenReferrerField.val());
    }
});

jQuery(document).ready(function(){
    jQuery(".owl-one").owlCarousel(
        {
            nav: true,
            loop: true,
            // center: true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:4
                }
            }
        }
    );

    jQuery(".home-owl-carousel").owlCarousel(
        {
            nav: true,
            loop: true,
            // center: true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:4
                }
            }
        }
    );
});