<?php
show_admin_bar(false);

// -- Setup theme ---------------------------------------------------------------------- //
	if (!function_exists('solelab_setup')){
		function solelab_setup() {
			if ( function_exists( 'add_theme_support' ) ) {
				add_theme_support( 'title-tag' );
				add_theme_support( 'post-thumbnails' );
			}
		}
	}

	add_action('after_setup_theme', 'solelab_setup');
// ---------------------------------------------------------------------- Setup theme -- //

// -- CSS and Scripts enqueue ---------------------------------------------------------- //
	function solelab_wp_register_script($handle, $src, $deps = [], $ver = false, $in_footer = false) {
		if(file_exists(ABSPATH . 'VERSION')){
			$ver = file_get_contents(ABSPATH . 'VERSION');
		}
		wp_register_script( $handle, $src, $deps, $ver, $in_footer);
	}

	function solelab_wp_register_style($handle, $src, $deps = [], $ver = false, $in_footer = false) {
		if(file_exists(ABSPATH . 'VERSION')){
			$ver = file_get_contents(ABSPATH . '/VERSION');
		}
		wp_register_style($handle, $src, $deps, $ver, $in_footer);
	}

	function solelab_scripts() {
		wp_deregister_script('jquery');

		solelab_wp_register_script('jquery', get_template_directory_uri() . '/js/jquery.min.js', [], "3.2.1", true);
		solelab_wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', [], true, true);
		solelab_wp_register_script('slick', get_template_directory_uri() . '/js/slick.min.js', ['jquery'], true, true);
		solelab_wp_register_script('scripts', get_template_directory_uri() . '/js/scripts.js', [], time(), true);

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'slick' );
		wp_enqueue_script( 'scripts' );
	}

	function solelab_css() {
		solelab_wp_register_style( 'style', get_template_directory_uri() . '/css/style.css', array(), time(), false );

		wp_enqueue_style( 'solelab-style', get_stylesheet_uri() );
		wp_enqueue_style( 'style' );
	}

	add_action( 'wp_enqueue_scripts', 'solelab_scripts' );
	add_action( 'wp_enqueue_scripts', 'solelab_css' );
// ---------------------------------------------------------- CSS and Scripts enqueue -- //

// -- <body> classes ------------------------------------------------------------------- //
	function add_slug_body_class( $classes ) {
		global $post;

		if (isset($post))
			$classes[]	=	$post->post_name;

		if(is_shop() || is_product())
			$classes[]	=	'shop';

		return $classes;
	}

	add_filter( 'body_class', 'add_slug_body_class' );
// ------------------------------------------------------------------- <body> classes -- //

if(is_admin()) {
	// -- Adjust permalink structure --------------------------------------------------- //
		add_action( 'init', function() {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		});
	// --------------------------------------------------- Adjust permalink structure -- //

	// -- Auto-create mandatory pages -------------------------------------------------- //
		$mandatoryPages	=	[
			'Home'							=>	null,
			'Blog'							=>	null,
			'About us'						=>	null,
			'Contact'						=>	null,
			'Frequently Asked Questions'	=>	'faq',
		];

		foreach ($mandatoryPages as $title => $slug) {
			$page	=	get_page_by_title($title);

			if (!$page) {
				wp_insert_post([
					'post_type'		=>	'page',
					'post_title'	=>	$title,
					'post_name'		=>	$slug ? $slug : sanitize_title($title),
					'post_status'	=>	'publish',
				]);
			}
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', get_page_by_path('home')->ID );
		update_option( 'page_for_posts', get_page_by_path('blog')->ID );
	// -------------------------------------------------- Auto-create mandatory pages -- //

}
// -- Woocommerce functions and support -------------------------------------------- //
if (class_exists('WooCommerce'))
require get_template_directory() . '/woocommerce/woocommerce.php';
// -------------------------------------------- Woocommerce functions and support -- //

function filter_genre( $query ) {
	// var_dump($query);
	if($query->is_post_type_archive && is_post_type_archive( 'product' )){
		$gender = empty($_GET['gender']) ? false : $_GET['gender'];
		// var_dump($genre);die();
		if($gender){
			// $genre_category = get_categories([
			// 	'hide_empty' => false,
			// 	'taxonomy' => 'product_cat',
			// 	'slug' => $genre,
			// ]);
			// $query->set( 'cat', '-1,-1347' );
			$query->set('tax_query', array(
						array ('taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => $gender
					)
				)
			);
		}
	}
    // if ( $query->is_home() && $query->is_main_query() ) {
    //     $query->set( 'cat', '-1,-1347' );
    // }
		// var_dump($genre);
		// die();
}
add_action( 'pre_get_posts', 'filter_genre' );

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page();

}

global $product_galery;

function getProductImages()
{
	global $product_galery;
	if(empty($product_galery)){
		global $product;
		$attachment_ids = $product->get_gallery_attachment_ids();
		foreach( $attachment_ids as $attachment_id ) {
			$product_galery[] = wp_get_attachment_url( $attachment_id );
		}
	}
	return $product_galery;
}
