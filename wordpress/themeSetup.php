<?php
// -- Featured image support ------------------------------------------------------------------- //
	if (function_exists('add_theme_support')) {
		add_theme_support('post-thumbnails');
		add_image_size('home_carousel_slide_m', 576, 450, true);
		add_image_size('home_carousel_slide_t', 992, 750, true);
		add_image_size('home_carousel_slide', 1920, 750, true);
		add_image_size('featured_post_list', 560, 295, true);
		add_image_size('post_list', 290, 220, true);
		add_image_size('embedded_on_post', 860, null, false);
	}
	function custom_sizes( $sizes ) {
	    return array_merge( $sizes,[
	        'home_carousel_slide'	=>	__('Carousel Desktop size'),
	        'home_carousel_slide_t'	=>	__('Carousel Tablet size'),
	        'home_carousel_slide_m'	=>	__('Carousel Mobile size'),
	        'featured_post_list'	=>	__('Featured post size'),
	        'post_list'				=>	__('Post size'),
	        'embedded_on_post'		=>	__('Embedded size'),
	    ]);
	}
	add_filter( 'image_size_names_choose', 'custom_sizes' );
// ------------------------------------------------------------------- Featured image support -- //

// -- Adjust permalink structure --------------------------------------------------------------- //
	add_action( 'init', function() {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
	});
// --------------------------------------------------------------- Adjust permalink structure -- //

// -- Auto-create mandatory pages -------------------------------------------------------------- //
	function create_pages() {
		$mandatoryPages	=	[
			'Home',
			'Blog',
			'About',
			'Veterinarians & Retailers',
			'CBD for your pets',
			'Hemp Benefits For Animals',
			'Contact',
			'Faq',
		];


		foreach ($mandatoryPages as $title) {
			$page	=	get_page_by_title($title);

			if (!$page) {
				wp_insert_post([
					'post_type'		=>	'page',
					'post_title'	=>	$title,
					'post_name'		=>	sanitize_title($title),
					'post_status'	=>	'publish',
				]);
			}
		}
	}
	
	add_action( 'after_setup_theme', 'create_pages', 10, 0);

	update_option('show_on_front', 'page');
	update_option('page_on_front', get_page_by_path('home')->ID);
	update_option('page_for_posts', get_page_by_path('blog')->ID);
// -------------------------------------------------------------- Auto-create mandatory pages -- //

// -- Custom post types ------------------------------------------------------------------------ //
	function create_post_types() {
		register_post_type( 'home_carousel', [
			'labels'				=>	[
				'name'			=>	__('Home Carousel Slides'),
				'singular_name'	=>	__('Home Carousel Slide'),
				'add_new_item'	=>	__('Add new slide'),
				'add_new'		=>	__('Add new slide'),
				'edit_item'		=>	__('Edit slide'),
				'update_item'	=>	__('Update slide'),
				'all_items'		=>	__('All slides'),
				'view_item'		=>	__('View slide'),
			],
			'menu_icon'				=>	'dashicons-slides',
			'exclude_from_search'	=>	true,
			'menu_position'			=>	2,
			'public'				=>	true,
			'has_archive'			=>	false,
			'supports'				=>	[
				'title',
				'editor',
			],
		]);

		register_post_type( 'faq', [
			'labels'				=>	[
				'name'			=>	__('FAQ'),
				'singular_name'	=>	__('FAQ'),
				'add_new_item'	=>	__('Add new FAQ'),
				'add_new'		=>	__('Add new FAQ'),
				'edit_item'		=>	__('Edit FAQ'),
				'update_item'	=>	__('Update FAQ'),
				'all_items'		=>	__('All FAQs'),
				'view_item'		=>	__('View FAQ'),
			],
			'menu_icon'				=>	'dashicons-lightbulb',
			'exclude_from_search'	=>	true,
			'menu_position'			=>	2,
			'public'				=>	true,
			'has_archive'			=>	false,
			'supports'				=>	[
				'title',
				'editor',
			],
		]);

	}

	add_action( 'after_setup_theme', 'create_post_types', 11, 0);
// ------------------------------------------------------------------------ Custom post types -- //

// -- Register menus --------------------------------------------------------------------------- //
	function phyto_register_menus() {
	  register_nav_menu('header-menu',__( 'Header Menu' ));
	  register_nav_menu('footer-menu',__( 'Footer Menu' ));
	}

	add_action('after_setup_theme', 'phyto_register_menus', 12, 0);
// --------------------------------------------------------------------------- Register menus -- //

// -- Auto-create menu ------------------------------------------------------------------------- //
	function phyto_create_menu(){
		$menu_name	=	'Main Menu';
		$mainMenu	=	wp_get_nav_menu_object($menu_name);

		if(!$mainMenu)
			$menu_id	=	wp_create_nav_menu($menu_name);
		else
			$menu_id	=	$mainMenu->term_id;

		$menuMainItems	=	[
			[
				'title'		=>	'About',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
			[
				'title'		=>	'Products',
				'classes'	=>	'menuItem',
				'url'		=>	'#',
				'type'		=>	'custom',
			],
			[
				'title'		=>	'CBD for your pets',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
			[
				'title'		=>	'Veterinarians & Retailers',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
			[
				'title'		=>	'Hemp Benefits For Animals',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
			[
				'title'		=>	'Blog',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
			[
				'title'		=>	'FAQ',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
			[
				'title'		=>	'Contact',
				'classes'	=>	'menuItem',
				'object'	=>	'page',
			],
		];

		foreach ($menuMainItems as $menuItem)
			phyto_create_menu_item($menu_id, $menuItem);

		$productsMenuID	=	phyto_get_menu_item_by_title('Products', $menu_id)->ID;
		$menuSubitems	=	[
			[
				'title'		=>	'Pet Vitality CBD Liquid',
				'classes'	=>	'menuItem',
				'url'		=>	'https://hempmedspx.com/phyto-animal-health-vitality', 
				'target'	=>	'_blank',
				'parentID'	=>	$productsMenuID,
				'type'		=>	'custom',
			],
			[
				'title'		=>	'Pet Vitality CBD Concentrate',
				'classes'	=>	'menuItem',
				'url'		=>	'/veterinarians-retailers', 
				'parentID'	=>	$productsMenuID,
				'type'		=>	'custom',
			],
			[
				'title'		=>	'Vitality-X',
				'classes'	=>	'menuItem',
				'url'		=>	'https://hempmedspx.com/phyto-animal-health-vitality-x', 
				'target'	=>	'_blank',
				'parentID'	=>	$productsMenuID,
				'type'		=>	'custom',
			],
			[
				'title'		=>	'Hemp Bedding & Litter',
				'classes'	=>	'menuItem',
				'url'		=>	'https://hempmedspx.com/phyto-animal-health-hemp-bedding', 
				'target'	=>	'_blank',
				'parentID'	=>	$productsMenuID,
				'type'		=>	'custom',
			],
		];

		foreach ($menuSubitems as $menuSubItem)
			phyto_create_menu_item($menu_id, $menuSubItem);
	}

	function phyto_create_menu_item($menuID, $args){
		extract($args);


		if(!phyto_get_menu_item_by_title($args['title'], $menuID)){
			$newItemData	=	[
				'menu-item-title'	=>	$args['title'],
				'menu-item-status'	=>	'publish',
			];

			$newItemData['menu-item-type']	=	isset($args['type'])	?	$args['type']	:	'post_type';

			if(isset($args['classes']))
				$newItemData['menu-item-classes']	=	$args['classes'];
			if(isset($args['target']))
				$newItemData['menu-item-target']	=	$args['target'];
			if(isset($args['url']))
				$newItemData['menu-item-url']		=	$args['url'];
			if(isset($args['object']))
				$newItemData['menu-item-object']	=	$args['object'];
			if(isset($args['object']) && $object == 'page')
				$newItemData['menu-item-object-id']	=	get_page_by_path(sanitize_title($title))->ID;
			if(isset($args['parentID']))
				$newItemData['menu-item-parent-id']	=	$args['parentID'];

			return wp_update_nav_menu_item($menuID, 0, $newItemData);
		}

		return;
	}
	
	function phyto_get_menu_item_by_title($title, $menu){
		if(gettype($menu) == 'array')
			$items		=	$menu;
		else
			$items		=	wp_get_nav_menu_items($menu);

		foreach($items as $item)
			if(sanitize_title($title) == sanitize_title($item->title))
				return $item;
			
		return null;
	}

	add_action( 'after_setup_theme', 'phyto_create_menu', 13, 0);
// ------------------------------------------------------------------------- Auto-create menu -- //

// -- Configure menu slots --------------------------------------------------------------------- //
	function phyto_configure_menu_slots(){
		$locations	=	get_theme_mod('nav_menu_locations');
		$locations['header-menu'] = wp_get_nav_menu_object('Main Menu')->term_id;
		$locations['footer-menu'] = wp_get_nav_menu_object('Main Menu')->term_id;
		set_theme_mod('nav_menu_locations', $locations);
	}
	add_action( 'after_setup_theme', 'phyto_configure_menu_slots', 14, 0);
// --------------------------------------------------------------------- Configure menu slots -- //

// -- Categories ------------------------------------------------------------------------------- //
	function phyto_create_cats(){
		$cats	=	[
			[
				'name'	=>	'Product Education',
				'slug'	=>	null,
			],
			[
				'name'	=>	'Media & Events',
				'slug'	=>	null,
			],
			[
				'name'	=>	'Press Releases',
				'slug'	=>	null,
			],
			[
				'name'	=>	'Company Updates',
				'slug'	=>	null,
			],
			[
				'name'	=>	'Pet & Livestock Health',
				'slug'	=>	null,
			],
			[
				'name'	=>	'Phyto Friends',
				'slug'	=>	null,
			],
			[
				'name'	=>	'Veterinarians & Retailers',
				'slug'	=>	null,
			],
		];

		foreach ($cats as $cat) {
			$args	=	null;

			if($cat['slug']){
				$args			=	[];
				$args['slug']	=	$cat['slug']	?	$cat['slug']	:	sanitize_title($cat['name']);
			}

			wp_insert_term($cat['name'], 'category', $args);
		}
	}
	add_action( 'after_setup_theme', 'phyto_create_cats', 15, 0);
// ------------------------------------------------------------------------------- Categories -- //

// -- <body> classes --------------------------------------------------------------------------- //
	function add_slug_body_class($classes) {
		global $post;

		if (isset($post))
			$classes[]	=	$post->post_name;

		return $classes;
	}

	add_filter( 'body_class', 'add_slug_body_class' );
// --------------------------------------------------------------------------- <body> classes -- //

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
