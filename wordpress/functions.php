<?php

show_admin_bar(false);

// -- CSS and Scripts ------------------------------------------------------------------ //
	function zanoma_wp_register_script($handle, $src, $deps = array(), $ver = false, $in_footer = false) {
		if(file_exists(ABSPATH . 'VERSION')){
			$ver = file_get_contents(ABSPATH . 'VERSION');
		}
		wp_register_script( $handle, $src, $deps, $ver, $in_footer );
	}

	function zanoma_wp_register_style($handle, $src, $deps = array(), $ver = false, $in_footer = false) {
		// var_dump(ABSPATH . 'VERSION');die();
		if(file_exists(ABSPATH . 'VERSION')){
			$ver = file_get_contents(ABSPATH . '/VERSION');
		}
		wp_register_style( $handle, $src, $deps, $ver, $in_footer );
	}

	function zanoma_scripts() {
		wp_deregister_script('jquery');
		zanoma_wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), "3.2.1", true );
		zanoma_wp_register_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array(), false, true );
		zanoma_wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), false, true );
		zanoma_wp_register_script( 'stickyfill', 'https://cdnjs.cloudflare.com/ajax/libs/stickyfill/2.0.3/stickyfill.min.js', array(), "2.0.3", true );
		zanoma_wp_register_script( 'scripts', get_template_directory_uri() . '/js/scripts.js', array(), "1.0.4", true );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'slick' );
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'stickyfill' );
		wp_enqueue_script( 'scripts' );
	}

	function zanoma_css() {
		zanoma_wp_register_style( 'fontawesome-style', get_template_directory_uri() . '/css/font-awesome.min.css', array(), "4.7.0", false );
		zanoma_wp_register_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), false, false );
		zanoma_wp_register_style( 'slick-theme', get_template_directory_uri() . '/css/slick-theme.css', array(), false, false );
		zanoma_wp_register_style( 'style', get_template_directory_uri() . '/css/style.css', array(), "1.0.5", false );

		wp_enqueue_style( 'fontawesome-style' );
		wp_enqueue_style( 'slick' );
		wp_enqueue_style( 'slick-theme' );
		wp_enqueue_style( 'style' );
	}

	add_action( 'wp_enqueue_scripts', 'zanoma_scripts' );
	add_action( 'wp_enqueue_scripts', 'zanoma_css' );
// ------------------------------------------------------------------ CSS and Scripts -- //

// -- Custom post type ------------------------------------------------------------------ //
	function create_post_type() {
		register_post_type( 'client_slider', [
			'labels'				=>	[
				'name'			=>	__('Clients'),
				'singular_name'	=>	__('Client'),
				'add_new_item'	=>	__('Add new client'),
				'add_new'		=>	__('Add new client'),
				'edit_item'		=>	__('Edit client'),
				'update_item'	=>	__('Update client'),
				'all_items'		=>	__('All clients'),
				'view_item'		=>	__('View client'),
			],
			'exclude_from_search'	=>	true,
			'menu_position'			=>	2,
			'public'				=>	true,
			'has_archive'			=>	false,
			'supports'				=>	[
				'title',
				'thumbnail',
			],
		]);

		register_post_type( 'team_member', [
			'labels'				=>	[
				'name'			=>	__('Team Members'),
				'singular_name'	=>	__('Team Member'),
				'add_new_item'	=>	__('Add new team member'),
				'add_new'		=>	__('Add new team member'),
				'edit_item'		=>	__('Edit team member'),
				'update_item'	=>	__('Update member'),
				'all_items'		=>	__('All members'),
				'view_item'		=>	__('View member'),
			],
			'exclude_from_search'	=>	true,
			'menu_position'			=>	2,
			'public'				=>	true,
			'has_archive'			=>	false,
			'supports'				=>	[
				'title',
				'thumbnail',
				'editor',
			],
			'taxonomies'			=>	['teams'],
		]);

		register_post_type( 'services', [
			'labels'				=>	[
				'name'			=>	__('Services'),
				'singular_name'	=>	__('Service'),
				'add_new_item'	=>	__('Add new service'),
				'add_new'		=>	__('Add new service'),
				'edit_item'		=>	__('Edit service'),
				'update_item'	=>	__('Update service'),
				'all_items'		=>	__('All services'),
				'view_item'		=>	__('View service'),
			],
			'exclude_from_search'	=>	true,
			'menu_position'			=>	2,
			'public'				=>	true,
			'has_archive'			=>	false,
			'supports'				=>	[
				'title',
				'thumbnail',
				'excerpt',
				'editor',
			],
			'taxonomies'			=>	['service_providers'],
		]);

		register_post_type( 'tutorials', [
			'labels'				=>	[
				'name'			=>	__('Tutorials'),
				'singular_name'	=>	__('Tutorial'),
				'add_new_item'	=>	__('Add new tutorial'),
				'add_new'		=>	__('Add new tutorial'),
				'edit_item'		=>	__('Edit tutorial'),
				'update_item'	=>	__('Update tutorial'),
				'all_items'		=>	__('All tutorials'),
				'view_item'		=>	__('View tutorial'),
			],
			'exclude_from_search'	=>	true,
			'menu_position'			=>	2,
			'public'				=>	true,
			'has_archive'			=>	false,
			'supports'				=>	[
				'title',
				'editor',
			],
			'taxonomies'			=>	['tutorials'],
		]);
	}

	add_action( 'init', 'create_post_type');

	function tutorials_links($post_link, $post = 0) {
		if($post->post_type !== 'tutorials' || $post->post_status !== 'publish')
			return $post_link;
		else
			return home_url('/' . $post->post_name . '/');
	}

	add_filter( 'post_type_link', 'tutorials_links', 10, 3 );

	function tutorial_links_to_main_query($q){
		if (!$q->is_main_query())
			return;
		if (!isset( $q->query['page']) || count( $q->query ) !== 2)
			return;

		if (empty($q->query['name']))
			return;

		$q->set('post_type', [
			'post',
			'page',
			'tutorials',
		]);
	}

	add_action( 'pre_get_posts', 'tutorial_links_to_main_query' );
// ------------------------------------------------------------------ Custom post type -- //

// -- Custom post taxonomy -------------------------------------------------------------- //
	function registerTerms($terms, $taxonomy, $args = []){
		foreach ($terms as $term)
			wp_insert_term($term, $taxonomy, $args);
	}

	function create_taxonomy() {
		register_taxonomy('team', 'team_member', [
				'labels'		=>	[
					'name' 			=>	__('Teams'),
					'singular_name'	=>	__('Team'),
					'all_items'		=>	__('All Team'),
					'edit_item'		=>	__('Edit Team'),
					'view_item'		=>	__('View team'),
					'add_new_item'	=>	__('Add new team'),
				],
				'hierarchical'	=>	true,
			]
		);

		$teams	=	[
			'Brazil',
			'India',
			'US',
		];

		register_taxonomy('service_providers', 'services', [
				'labels'		=>	[
					'name' 			=>	__('Service providers'),
					'singular_name'	=>	__('Service provider'),
					'all_items'		=>	__('All service providers'),
					'edit_item'		=>	__('Edit service provider'),
					'view_item'		=>	__('View service provider'),
					'add_new_item'	=>	__('Add new service providers'),
				],
				'hierarchical'	=>	true,
			]
		);

		$serviceProviders 	=	[
			'Amazon',
			'Google',
			'Website',
			'Strategy',
		];

		register_taxonomy('tutorial_group', 'tutorials', [
				'labels'		=>	[
					'name' 			=>	__('Tutorial groups'),
					'singular_name'	=>	__('tutorial group'),
					'all_items'		=>	__('All tutorial groups'),
					'edit_item'		=>	__('Edit tutorial group'),
					'view_item'		=>	__('View tutorial group'),
					'add_new_item'	=>	__('Add new tutorial group'),
				],
				'rewrite'		=>	[
					'slug'			=>	'tutorial',
				],
				'hierarchical'	=>	true,
			]
		);

		$tutorialGroups	=	[
			'Amazon',
			'Google',
			'Website',
		];

		registerTerms($teams, 'team');
		registerTerms($serviceProviders, 'service_providers');
		registerTerms($tutorialGroups, 'tutorial_group');
	}

	add_action( 'init', 'create_taxonomy');
// -------------------------------------------------------------- Custom post taxonomy -- //

// -- Featured image support ------------------------------------------------------------ //
	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'client_logo', 136, 68, true );
		add_image_size( 'news-post-image', 270, 360, true );
		add_image_size( 'home-green-bg-image', 600, null, true );
		add_image_size( 'home-banner-bg', 1920, null, true );
		add_image_size( 'home-after-map-image', 600, null, true );
	}
// ------------------------------------------------------------ Featured image support -- //

// -- The faq functionality ------------------------------------------------------------- //
	function theFaqAjax() {
		$itemsQuery =  new WP_Query([
			'post_type'			=>	'tutorials',
			'tax_query'			=>	[
				[
					'taxonomy'	=>	'tutorial_group',
					'field'		=>	'term_id',
					'terms'		=>	[$_POST['catID']],
				],
			],
			'posts_per_page'	=>	-1,
		]);


		$result						=	[];
		$result['tutorial_group']	=	get_term($_POST['catID']);

		while($itemsQuery->have_posts()){
			$itemsQuery->the_post();


			$itemsQuery->post->tutorial_group	=	get_the_terms($itemsQuery->post, 'tutorial_group')[0];

			$itemsQuery->post->prev				=	get_previous_post(true, '', 'tutorial_group');
			$itemsQuery->post->next				=	get_next_post(true, '', 'tutorial_group');

			$result['items'][]	=	$itemsQuery->post;
		}

		echo json_encode($result);

		wp_die();
	}

	function theFaqGetSubcats(){
		$subcats 	=	get_categories([
			'taxonomy'		=>	'tutorial_group',
			'hide_empty'	=>	false,
			'parent'		=>	$_POST['catID'],
			'order_by'		=>	'name,'
		]);

		if(count($subcats) > 0){
			echo json_encode($subcats);
			wp_die();
			die();
		} else {
			$itemsQuery =  new WP_Query([
				'post_type'			=>	'tutorials',
				'tax_query'			=>	[
					[
						'taxonomy'	=>	'tutorial_group',
						'field'		=>	'term_id',
						'terms'		=>	[$_POST['catID']],
					],
				],
				'posts_per_page'	=>	-1,
			]);


			$posts						=	[];

			while($itemsQuery->have_posts()){
				$itemsQuery->the_post();

				$itemsQuery->post->tutorial_group	=	get_the_terms($itemsQuery->post, 'tutorial_group')[0];

				$itemsQuery->post->prev				=	get_previous_post(true, '', 'tutorial_group');
				$itemsQuery->post->next				=	get_next_post(true, '', 'tutorial_group');

				$posts['items'][]	=	$itemsQuery->post;
			}
			echo json_encode($posts);
			wp_die();
			die();
		}

	}

	add_action( 'wp_ajax_theFaqGetSubcats', 'theFaqGetSubcats' );
	add_action( 'wp_ajax_nopriv_theFaqGetSubcats', 'theFaqGetSubcats' );

	add_action( 'wp_ajax_theFaqAjax', 'theFaqAjax' );
	add_action( 'wp_ajax_nopriv_theFaqAjax', 'theFaqAjax' );
// ------------------------------------------------------------- The faq functionality -- //

// -- MailChimp Functionality ----------------------------------------------------------- //
	function mailChimp() {
		post_to_mail_chimp($_POST);
		
		wp_die();
	}

	function post_to_mail_chimp($POST){
		$macacochave	=	'0bd8a91f2c426cdd8b5ca35cbc5ebe2e-us14';
		$list			=	$POST['macacoList'];

		unset($POST['action']);
		unset($POST['macacoList']);
		unset($POST['interestsLabels']);

		if (isset($POST['interests'])) {
			$interests	=	array_map(function($e){
				return	$e === 'true'	?	true	:	false;
			},$POST['interests']);

			$POST['interests']	=	$interests;
		}


		$payload = json_encode($POST);

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_URL => "https://us14.api.mailchimp.com/3.0/lists/$list/members",
			CURLOPT_USERPWD => "anystring:$macacochave",
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
			],
		]);

		$resp = curl_exec($curl);
	}

	add_action( 'wp_ajax_mailChimp', 'mailChimp' );
	add_action( 'wp_ajax_nopriv_mailChimp', 'mailChimp' );
// ----------------------------------------------------------- MailChimp Functionality -- //

// -- Contact message functionality ----------------------------------------------------- //
	function send_contact_email(){	
		$from_name	=	$_POST['merge_fields']['FNAME'] . ' ' . $_POST['merge_fields']['LNAME'];
		$from_email	=	$_POST['email_address'];

		$headers[]	=	"From: $from_name <$from_email>";

		apply_filters('wp_mail_from_name', $from_name);
		apply_filters('wp_mail_from', $from_email);

		$to			=	'contact@zanoma.com';
		$subject	=	'[Zanoma Website] New website contact';

		$message	=	"Name: $from_name\r\n";
		$message	.=	"E-mail: $from_email\r\n";
		$message	.=	'Phone: ' . $_POST['merge_fields']['PHONE'] . "\r\n";
		$message	.=	'Company: ' . $_POST['merge_fields']['COMPANY'] . "\r\n";
		$message	.=	'Website: ' . $_POST['merge_fields']['WEBSITE'] . "\r\n";
		$message	.=	'Details: ' . $_POST['merge_fields']['DETAILS'] . "\r\n";
		$message	.=	'Interests: ' . join(", ", $_POST['interestsLabels']) . "\r\n";

		wp_mail($to, $subject, $message, $headers);
		
		post_to_mail_chimp($_POST);

		wp_die();
	}

	add_action('wp_ajax_send_contact_email', 'send_contact_email');
	add_action('wp_ajax_nopriv_send_contact_email', 'send_contact_email');
// ----------------------------------------------------- Contact message functionality -- //

if(is_admin()) {
	// -- Adjust permalink structure -------------------------------------------------------- //
		add_action( 'init', function() {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		});
	// -------------------------------------------------------- Adjust permalink structure -- //


	// -- Change title placeholder ---------------------------------------------------------- //
		function wpb_change_title_text( $title ){
			$screen = get_current_screen();

			switch ($screen->post_type) {
				case 'client_slider':
					$title = 'Client name';
					break;
				case 'team_member':
					$title = 'Team member name';
					break;
				default:
					$title = 'Enter title here';
					break;
			}

			return $title;
		}

		add_filter( 'enter_title_here', 'wpb_change_title_text' );
	// ---------------------------------------------------------- Change title placeholder -- //

	// -- Auto-create mandatory taxonomy, post types and custom fields ---------------------- //
		$mandatoryPages	=	[
			'Home',
			'Services',
			'Who We Are',
			'Tutorials',
			'Clients',
			'Blog',
			'Let\'s talk',
		];

		foreach ($mandatoryPages as $title) {
			$page	=	get_page_by_title($title);

			if (!$page) {
				wp_insert_post([
					'post_type'		=>	'page',
					'post_title'	=>	$title,
					'post_name'		=>	$title == 'Let\'s talk' ? 'contact-us' : sanitize_title($title),
					'post_status'	=>	'publish',
				]);
			}
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', get_page_by_path('home')->ID );
		update_option( 'page_for_posts', get_page_by_path('blog')->ID );
		// ---------------------- Auto-create mandatory taxonomy, post types and custom fields -- //

	// -- Change featured image labels ------------------------------------------------------ //
		function cp_replace_featured_image_metabox($post_type, $context) {
			if ($context == 'side') {
				remove_meta_box( 'postimagediv', 'team_member', 'side' );

				switch ($post_type) {
					case 'client_slider':
							add_meta_box( 'postimagediv', __( 'Company Logo' ), 'post_thumbnail_meta_box', $post_type, 'side', 'low' );
						break;
					case 'team_member':
							add_meta_box( 'postimagediv', __( 'Team member photo' ), 'post_thumbnail_meta_box', $post_type, 'side', 'low' );
						break;
				}
			}
		}

		function cp_change_featured_image_link_text($content, $post_id) {
			switch (get_post_type(get_post($post_id))) {
				case 'client_slider':
						$content	=	str_replace( 'Set featured image', __( 'Set Company Logo'), $content );
						$content	=	str_replace( 'Remove featured image', __( 'Remove Company Logo'), $content );
					break;
				case 'team_member':
						$content	=	str_replace( 'Set featured image', __( 'Set team member photo'), $content );
						$content	=	str_replace( 'Remove featured image', __( 'Remove team member photo'), $content );
					break;
			}

			return $content;
		}

		function cp_update_media_view_featured_image_titles( $settings, $post ) {
			switch ($post->post_type) {
				case 'client_slider':
						$settings['setFeaturedImageTitle']	=	__( "Company Logo" );
						$settings['setFeaturedImage']     	=	__( "Set Company Logo" );
					break;
				case 'team_member':
						$settings['setFeaturedImageTitle']	=	__( "Team member photo" );
						$settings['setFeaturedImage']     	=	__( "Set team member photo" );
					break;
			}

			return $settings;
		}

		add_filter( 'media_view_strings', 'cp_update_media_view_featured_image_titles', 10, 2 );
		add_filter( 'admin_post_thumbnail_html', 'cp_change_featured_image_link_text', 10, 2 );
		add_action( 'do_meta_boxes', 'cp_replace_featured_image_metabox', 10, 2 );
	// ------------------------------------------------------ Change featured image labels -- //

	// -- Auto import advanced custom fields ------------------------------------------------ //
		if (function_exists('acf_get_field_groups')) {
			/**
			 * Function that will update ACF fields via JSON file update
			 */
			function sync_acf_fields() {
				// vars
				$groups = acf_get_field_groups();
				$sync 	= array();

				// bail early if no field groups
				if( empty($groups) )
					return;

				// find JSON field groups which have not yet been imported
				foreach($groups as $group) {
					$local		=	acf_maybe_get($group, 'local', false);
					$modified	=	acf_maybe_get($group, 'modified', 0);
					$private	=	acf_maybe_get($group, 'private', false);

					// ignore DB / PHP / private field groups
					if( $local !== 'json' || $private ){}
					elseif(!$group['ID'])
						$sync[ $group[ 'key' ] ] = $group;
					elseif( $modified && $modified > get_post_modified_time( 'U', true, $group[ 'ID' ], true ) )
						$sync[ $group[ 'key' ] ]  = $group;
				}

				// bail if no sync needed
				if(empty($sync))
					return;

				if(!empty( $sync)) {
					$new_ids = [];

					foreach($sync as $key => $v) {
						if(acf_have_local_fields($key))
							$sync[ $key ][ 'fields' ] = acf_get_local_fields( $key );

						$field_group = acf_import_field_group( $sync[ $key ] );
					}
				}
			}
			add_action( 'admin_init', 'sync_acf_fields' );
		}
	// ------------------------------------------------ Auto import advanced custom fields -- //
}

/*  Add responsive container to embeds
/* ------------------------------------ */
function alx_embed_html( $html ) {
    return '<div class="video-container">' . $html . '</div>';
}

add_filter( 'embed_oembed_html', 'alx_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'alx_embed_html' ); // Jetpack

// -- <body> classes --------------------------------------------------------------------------- //
	function add_slug_body_class($classes) {
		global $post;

		if (isset($post))
			$classes[]	=	$post->post_name;

		$classes[] = is_user_logged_in() ? 'adminBar' : '';

		return $classes;
	}

	add_filter( 'body_class', 'add_slug_body_class' );
// --------------------------------------------------------------------------- <body> classes -- //
