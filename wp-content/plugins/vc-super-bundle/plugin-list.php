<?php
/**
 * The list of features / plugins which are available.
 *
 * This file is AUTO GENERATED by gulp, do not modify it.
 *
 * @package VC Super Bundle
 */

$features = array(

	'background' => array(
		'name' => __( 'Background Effects', 'super-bundle' ),
		'group' => __( 'Row Effects', 'super-bundle' ),
		'description' => __( 'Elements that you can place in your rows to add a smoother parallax, video backgrounds, hover backgrounds, color cycle backgrounds, gradient backgrounds and more.', 'super-bundle' ),
		'desc_short' => 'Smoother parallax, video, hover, color cycle, gradient backgrounds and more.',
		'require' => 'features/background/class-plugin.php',
		'uninstall' => 'features/background/uninstall.php',
		'demo' => 'http://demos2.gambit.ph/parallax/?nopreview',
		'hide_in_demo' => 'true',
	),

	'separators' => array(
		'name' => __( 'Row Separators', 'super-bundle' ),
		'group' => __( 'Row Effects', 'super-bundle' ),
		'description' => __( 'New elements that you can add to rows to add unique row separator designs to distinguish between your different content.', 'super-bundle' ),
		'desc_short' => 'Separate your content with unique row separators designs',
		'require' => 'features/separators/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/rowseparators/?nopreview',
	),

	'row-scroll' => array(
		'name' => __( 'Row Scroll Animations', 'super-bundle' ),
		'group' => __( 'Row Effects', 'super-bundle' ),
		'description' => __( 'Elements that you can place inside your rows to add entrance/exit animations to them.', 'super-bundle' ),
		'desc_short' => 'Add entrance and exit animations on your containers',
		'require' => 'features/row-scroll/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/rowscroll/?nopreview',
	),

	'fonts' => array(
		'name' => __( 'Google Fonts', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'New setting tabs in rows and columns to customize the fonts of your headings and text.', 'super-bundle' ),
		'desc_short' => 'Use 800+ Google fonts',
		'require' => 'features/fonts/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/fonts?nopreview',
	),

	'carousel' => array(
		'name' => __( 'Carousel', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'Elements that you can use to create carousels and display a carouse of your posts.', 'super-bundle' ),
		'desc_short' => 'Create carousels containing anything',
		'require' => 'features/carousel/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/carousel/?nopreview',
	),

	'video-lightbox' => array(
		'name' => __( 'Video Lightbox', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'A video or image thumbnail that when clicked opens a large video lightbox popup.', 'super-bundle' ),
		'desc_short' => 'Play a large video lightbox popup',
		'require' => 'features/video-lightbox/class-plugin.php',
		'demo' => 'http://demos.gambit.ph/seamless-video-lightbox/',
		'new' => 'New!',
	),

	'countup' => array(
		'name' => __( 'Count Up', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'Count upward animation from zero, best for showcasing statistical numbers.', 'super-bundle' ),
		'desc_short' => 'Count upward animation from zero',
		'require' => 'features/countup/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/number-count-up/?nopreview',
		'new' => 'New!',
	),

	'shadows' => array(
		'name' => __( 'Shadows', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'New Shadows tab for column, image and button elements.', 'super-bundle' ),
		'desc_short' => 'Add shadows on columns, images and buttons',
		'require' => 'features/shadows/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/shadows?nopreview',
	),

	'text-gradient' => array(
		'name' => __( 'Text Gradient', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'New gradient tab for text elements.', 'super-bundle' ),
		'desc_short' => 'Color your text with gradients',
		'require' => 'features/text-gradient/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/text-gradient?nopreview',
	),

	'svg-icons' => array(
		'name' => __( 'SVG Icons', 'super-bundle' ),
		'group' => __( 'Essentials', 'super-bundle' ),
		'description' => __( 'Icon and button elements that allows you to use 12,000+ SVG vector icons.', 'super-bundle' ),
		'desc_short' => 'Add icons & buttons, choose from 12,000+ SVG vector icons',
		'require' => 'features/svg-icons/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/12k-svg-icons/?nopreview',
	),

	'svgdraw' => array(
		'name' => __( 'SVG Draw Animation', 'super-bundle' ),
		'group' => __( 'Animations', 'super-bundle' ),
		'description' => __( 'Draw outline entrance animation for SVG icons and images.', 'super-bundle' ),
		'desc_short' => 'SVG draw outline entrance animation',
		'require' => 'features/svgdraw/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/svg-draw-animation/?nopreview',
		'new' => 'New!',
	),

	'text-effects' => array(
		'name' => __( 'Text Effects', 'super-bundle' ),
		'group' => __( 'Animations', 'super-bundle' ),
		'description' => __( 'A text animation element, great for page headings.', 'super-bundle' ),
		'desc_short' => 'A cool animating text element, great for headings',
		'require' => 'features/text-effects/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/texteffects/?nopreview',
	),

	'hover-animations' => array(
		'name' => __( 'Hover Animations', 'super-bundle' ),
		'group' => __( 'Animations', 'super-bundle' ),
		'description' => __( 'Adds a new hover tab to all VC elements for adding animations that play on hover.', 'super-bundle' ),
		'desc_short' => 'Add animations to elements and rows that play on hover',
		'require' => 'features/hover-animations/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/hover-animation/?nopreview',
	),

	'css-animator' => array(
		'name' => __( 'CSS Animator', 'super-bundle' ),
		'group' => __( 'Animations', 'super-bundle' ),
		'description' => __( 'A container element that animates whatever you put inside it.', 'super-bundle' ),
		'desc_short' => 'A container element that animates whatever you put in it',
		'require' => 'features/css-animator/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/css-animator/?nopreview',
	),

	'device-mockups' => array(
		'name' => __( 'Device Mockups', 'super-bundle' ),
		'group' => __( 'Image Effects', 'super-bundle' ),
		'description' => __( 'An element that shows mockup images, carousels or videos inside devices (iMacs, iPhones, browsers, and more).', 'super-bundle' ),
		'desc_short' => 'Mockup images, carousels or videos inside desktops or mobile devices',
		'require' => 'features/device-mockups/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/mockup?nopreview',
	),

	'hero-boxes' => array(
		'name' => __( 'Hero Boxes', 'super-bundle' ),
		'group' => __( 'Image Effects', 'super-bundle' ),
		'description' => __( 'An image element with a unique hover effect suitable for hero images or feature areas.', 'super-bundle' ),
		'desc_short' => 'An image element with lots of unique hover effects',
		'require' => 'features/hero-boxes/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/herobox/?nopreview',
	),

	'loupe' => array(
		'name' => __( 'Image Loupe', 'super-bundle' ),
		'group' => __( 'Image Effects', 'super-bundle' ),
		'description' => __( 'An image element with a circular magnifying glass that users can drag around to view your images in more detail.', 'super-bundle' ),
		'desc_short' => 'A circular magnifying glass that users can drag around to view your images',
		'require' => 'features/loupe/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/loupe?nopreview',
	),

	'before-after' => array(
		'name' => __( 'Before and After', 'super-bundle' ),
		'group' => __( 'Image Effects', 'super-bundle' ),
		'description' => __( 'An element for displaying overlapped before and after images that visitors can interact with.', 'super-bundle' ),
		'desc_short' => 'Slide between two images to view before and after photos',
		'require' => 'features/before-after/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/before-after/?nopreview',
	),

	'isometric' => array(
		'name' => __( 'Isometric Image Tiles', 'super-bundle' ),
		'group' => __( 'Image Effects', 'super-bundle' ),
		'description' => __( 'An element that displays images in an arrangement of isometric tiles. Great for page or footer decorations.', 'super-bundle' ),
		'desc_short' => 'Display images in an arrangement of isometric tiles',
		'require' => 'features/isometric/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/isometric-tiles/?nopreview',
	),

	'free-image-search' => array(
		'name' => __( 'Free Image Search', 'super-bundle' ),
		'group' => __( 'Utility', 'super-bundle' ),
		'description' => __( 'Adds a new tab to the WordPress Media Manager for searching and downloading Free images.', 'super-bundle' ),
		'desc_short' => 'Search and download free images from inside the WordPress Media Manager',
		'require' => 'features/free-image-search/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/freeimagesearch?nopreview',
	),

	'live-preview' => array(
		'name' => __( 'Backend Live Preview', 'super-bundle' ),
		'group' => __( 'Utility', 'super-bundle' ),
		'description' => __( 'Enables live updating previews in Visual Composer\'s backend editor.', 'super-bundle' ),
		'desc_short' => 'Preview your changes live from the VC backend editor',
		'require' => 'features/live-preview/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/vcpreview/?nopreview',
	),

	'undo-redo' => array(
		'name' => __( 'Undo & Redo', 'super-bundle' ),
		'group' => __( 'Utility', 'super-bundle' ),
		'description' => __( 'Adds an undo and redo button to the backend of Visual Composer.', 'super-bundle' ),
		'desc_short' => 'Adds an undo and redo button to the backend of Visual Composer',
		'require' => 'features/undo-redo/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/undo-redo/?nopreview',
	),

	'smooth-scrolling' => array(
		'name' => __( 'Smooth Mouse Scroll', 'super-bundle' ),
		'group' => __( 'Utility', 'super-bundle' ),
		'description' => __( 'Smoothens out the mousewheel to make page scrolling a better experience. Great for parallax bakgrounds.', 'super-bundle' ),
		'desc_short' => 'Smoothens out the mousewheel to make page scrolling a better experience',
		'require' => 'features/smooth-scrolling/class-plugin.php',
		'demo' => 'http://demos2.gambit.ph/smooth-mousewheel/?nopreview',
		'admin_settings' => 'options-general.php',
	),
);
