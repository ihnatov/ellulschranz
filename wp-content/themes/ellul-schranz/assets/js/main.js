(function($) {

	"use strict";

/* ==========================================================================
   ieViewportFix - fixes viewport problem in IE 10 SnapMode and IE Mobile 10
   ========================================================================== */

	function ieViewportFix() {

		var msViewportStyle = document.createElement("style");

		msViewportStyle.appendChild(
			document.createTextNode(
				"@-ms-viewport { width: device-width; }"
			)
		);

		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {

			msViewportStyle.appendChild(
				document.createTextNode(
					"@-ms-viewport { width: auto !important; }"
				)
			);
		}

		document.getElementsByTagName("head")[0].
				appendChild(msViewportStyle);

	}

/* ==========================================================================
   exists - Check if an element exists
   ========================================================================== */

	function exists(e) {
		return $(e).length > 0;
	}

/* ==========================================================================
   handleMobileMenu
   ========================================================================== */

	var MOBILEBREAKPOINT = 1241;

	function handleMobileMenu() {

		if ($(window).width() > MOBILEBREAKPOINT) {

			$("#mobile-menu").hide();
			$("#mobile-menu-trigger").removeClass("mobile-menu-opened").addClass("mobile-menu-closed");

		} else {

			if (!exists("#mobile-menu")) {

				$("#menu").clone().attr({
					id: "mobile-menu",
					"class": "fixed"
				}).insertAfter("#header-wrap");

				$("#mobile-menu > li > a, #mobile-menu > li > ul > li > a").each(function() {
					var $t = $(this);
					if ($t.next().hasClass('sub-menu') || $t.next().is('ul') || $t.next().is('.sf-mega')) {
						$t.append('<span class="fa fa-angle-down mobile-menu-submenu-arrow mobile-menu-submenu-closed"></span>');
					}
				});

				$(".mobile-menu-submenu-arrow").on("click", function(event) {
					var $t = $(this);
					if ($t.hasClass("mobile-menu-submenu-closed")) {
						$t.removeClass("mobile-menu-submenu-closed fa-angle-down").addClass("mobile-menu-submenu-opened fa-angle-up").parent().siblings("ul, .sf-mega").slideDown(300);
					} else {
						$t.removeClass("mobile-menu-submenu-opened fa-angle-up").addClass("mobile-menu-submenu-closed fa-angle-down").parent().siblings("ul, .sf-mega").slideUp(300);
					}
					event.preventDefault();
				});

				$("#mobile-menu li, #mobile-menu li a, #mobile-menu ul").attr("style", "");

			}

		}

	}

/* ==========================================================================
   showHideMobileMenu
   ========================================================================== */

	function showHideMobileMenu() {

		$("#mobile-menu-trigger").on("click", function(event) {

			var $t = $(this),
				$n = $("#mobile-menu");

			if ($t.hasClass("mobile-menu-opened")) {
				$t.removeClass("mobile-menu-opened").addClass("mobile-menu-closed");
				$n.slideUp(300);
			} else {
				$t.removeClass("mobile-menu-closed").addClass("mobile-menu-opened");
				$n.slideDown(300);
			}
			event.preventDefault();

		});

	}

/* ==========================================================================
   handleBackToTop
   ========================================================================== */

   function handleBackToTop() {

		$('#back-to-top').on("click", function(){
			$('html, body').animate({scrollTop:0}, 'slow');
			return false;
		});

   }

/* ==========================================================================
   showHidebackToTop
   ========================================================================== */

	function showHidebackToTop() {

		if ($(window).scrollTop() > $(window).height() / 2 ) {
			$("#back-to-top").removeClass('gone').addClass('visible');
		} else {
			$("#back-to-top").removeClass('visible').addClass('gone');
		}

	}

/* ==========================================================================
   handleSearch
   ========================================================================== */

	function handleSearch() {

		$("#custom-search-button").on("click", function(e) {

			e.preventDefault();

			if(!$("#custom-search-button").hasClass("open")) {

				$("#custom-search-form-container").addClass("open-custom-search-form");

			} else {

				$("#custom-search-form-container").removeClass("open-custom-search-form");

			}
			$(window).resize(function(){
				$("#custom-search-form-container").removeClass("open-custom-search-form");
			})

		});

		$("#custom-search-form").after('<a class="custom-search-form-close" href="#">x</a>');

		$("#custom-search-form-container a.custom-search-form-close").on("click", function(event){

			event.preventDefault();
			$("#custom-search-form-container").removeClass("open-custom-search-form");

		});

	 }

/* ==========================================================================
   handleStickyHeader
   ========================================================================== */

	var stickyHeader = false;
	var stickypoint = 40;

	if ($('body').hasClass('sticky-header')){
		stickyHeader = true;
	}

	if ($('body').hasClass('header-style-1')) {
		stickypoint = 40;
	} else {
		stickypoint = $("#header-top").outerHeight() + $("#header-wrap").outerHeight() + 150;
	}

	function handleStickyHeader() {

		var b = document.documentElement,
        	e = false;

		function f() {

			window.addEventListener("scroll", function (h) {

				if (!e) {
					e = true;
					setTimeout(d, 0);
				}
			}, false);

			window.addEventListener("load", function (h) {

				if (!e) {
					e = true;
					setTimeout(d, 0);
				}
			}, false);
		}

		function d() {

			var h = c();

			if (h >= stickypoint) {
				$('#header-wrap').addClass("stuck");
			} else {
				$('#header-wrap').removeClass("stuck");
			}

			e = false;
		}

		function c() {

			return window.pageYOffset || b.scrollTop;

		}

		f();

	}

/* ==========================================================================
   When document is ready, do
   ========================================================================== */

	$(document).ready(function() {

		if( String && String.trim ){
			$('#commentform').on('submit',function( e ){
				var $comment = $('#comment',this);
				if( $comment.val().trim() == '' ){
					e.preventDefault();
					$comment.addClass('error');
				} else {
					$comment.removeClass('error');
				}
			});
		}

		ieViewportFix();

		handleMobileMenu();
		showHideMobileMenu();

		handleBackToTop();
		showHidebackToTop();

		handleSearch();

		if(stickyHeader && ($(window).width() > 1024)){
			handleStickyHeader();
		}

		// twitterFetcher
		// http://jasonmayes.com/projects/twitterApi/

		if(typeof twitterFetcher !== 'undefined' && ($('.ellul_schranz_twitter_widget').length > 0)) {

			$('.ellul_schranz_twitter_widget').each(function(index){

				var account_id = $('.ellul_schranz-tweet-list', this).attr('data-account-id'),
					items = $('.ellul_schranz-tweet-list', this).attr('data-items'),
					newID = 'ellul_schranz-tweet-list-' + index;

				$('.ellul_schranz-tweet-list', this).attr('id', newID);

				var config = {
				  "id": account_id,
				  "domId": newID,
				  "maxTweets": items,
				  "showRetweet": false,
				  "showTime": false,
				  "showUser": false
				};

				twitterFetcher.fetch(config);
			});

		}

		// simplePlaceholder - polyfill for mimicking the HTML5 placeholder attribute using jQuery
		// https://github.com/marcgg/Simple-Placeholder/blob/master/README.md

		if(typeof $.fn.simplePlaceholder !== 'undefined'){

			$('input[placeholder], textarea[placeholder]').simplePlaceholder();

		}

		// Fitvids - fluid width video embeds
		// https://github.com/davatron5000/FitVids.js/blob/master/README.md

		if(typeof $.fn.fitVids !== 'undefined'){

			$('.fitvids,.responsive-embed').fitVids();

		}

		// Superfish - enhance pure CSS drop-down menus
		// http://users.tpg.com.au/j_birch/plugins/superfish/options/

		if(typeof $.fn.superfish !== 'undefined'){

			$('#menu').superfish({
				delay: 500,
				animation: {opacity:'show',height:'show'},
				speed: 100,
				cssArrows: true
			});

		}

		//

	});

/* ==========================================================================
   When the window is scrolled, do
   ========================================================================== */

	$(window).scroll(function() {

		showHidebackToTop();

		if(stickyHeader && ($(window).width() > 1024)){
			handleStickyHeader();
		}


	});

/* ==========================================================================
   When the window is resized, do
   ========================================================================== */

	$(window).resize(function() {

		handleMobileMenu();

		if(stickyHeader && ($(window).width() > 1024)){
			handleStickyHeader();
		}

	});


})(jQuery);

// non jQuery scripts below
