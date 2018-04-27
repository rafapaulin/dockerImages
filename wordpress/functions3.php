<?php
show_admin_bar(false);

require_once ( ABSPATH . 'wp-content/themes/phyto-animal-health/functions/themeSetup.php');
require_once ( ABSPATH . 'wp-content/themes/phyto-animal-health/functions/ajaxCalls.php');
require_once ( ABSPATH . 'wp-content/themes/phyto-animal-health/functions/acf.php');



// -- WORDPRESS MIGRAÇÃO ---------------------------------------------------------------- //


// -- CSS ------------------------------------------------------------------------------ //
	function phyto_wp_register_style($handle, $src, $deps = array(), $ver = false, $in_footer = false) {
		if(file_exists(ABSPATH . 'VERSION'))
			$ver = file_get_contents(ABSPATH . '/VERSION');

		wp_register_style( $handle, $src, $deps, $ver, $in_footer );
	}
	function phyto_css() {
		phyto_wp_register_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), false, false );
		phyto_wp_register_style( 'style', get_template_directory_uri() . '/css/style.css', array(), "1.3", false );

		wp_enqueue_style( 'slick' );
		wp_enqueue_style( 'style' );
	}

	add_action( 'wp_enqueue_scripts', 'phyto_css' );
// ------------------------------------------------------------------------------ CSS -- //

// -- Scripts -------------------------------------------------------------------------- //
	function phyto_wp_register_script($handle, $src, $deps = array(), $ver = false, $in_footer = false) {
		if(file_exists(ABSPATH . 'VERSION')){
			$ver = file_get_contents(ABSPATH . 'VERSION');
		}
		wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	}

	function phyto_scripts() {
		wp_deregister_script('jquery');
		phyto_wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), '3.2.1', true );
		phyto_wp_register_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array(), '1.8.1', true );
		phyto_wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), false, true );
		phyto_wp_register_script( 'stickyfill', 'https://cdnjs.cloudflare.com/ajax/libs/stickyfill/2.0.3/stickyfill.min.js', array(), "2.0.3", true );
		phyto_wp_register_script( 'scrollspy', get_template_directory_uri() . '/js/plugins/scrollspy.js', array(), '1.0', true );
		phyto_wp_register_script( 'scripts', get_template_directory_uri() . '/js/scripts.js', array(), '1.0', true );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'slick' );
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'stickyfill' );
		wp_enqueue_script( 'scrollspy' );
		wp_enqueue_script( 'scripts' );
	}

	add_action( 'wp_enqueue_scripts', 'phyto_scripts' );
// -------------------------------------------------------------------------- Scripts -- //

function special_nav_class($classes, $item){
	$current_page	=	get_post();

	if($item->url == $current_page->guid)
		$classes[] = 'active';

	return $classes;
}

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);