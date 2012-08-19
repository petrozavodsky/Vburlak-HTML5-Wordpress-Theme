<?php
        // Translations can be filed in the /languages/ directory
        load_theme_textdomain( 'html5reset', TEMPLATEPATH . '/languages' );
 
        $locale = get_locale();
        $locale_file = TEMPLATEPATH . "/languages/$locale.php";
        if ( is_readable($locale_file) )
            require_once($locale_file);
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !function_exists(core_mods) ) {
		function core_mods() {
			if ( !is_admin() ) {
				wp_deregister_script('jquery');
				wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"), false);
				wp_enqueue_script('jquery');
			}
		}
		core_mods();
	}

	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => __('Sidebar Widgets','html5reset' ),
    		'id'   => 'sidebar-widgets',
    		'description'   => __( 'These are widgets for the sidebar.'),
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));
    }
    
    add_theme_support( 'post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video')); // Add 3.1 post format theme support.
    
    add_theme_support( 'post-thumbnails', array( 'post' ) );

    add_theme_support('menus');
    
    register_nav_menus(array(  
			'primary' => 'Главное меню',
			//'secondary' => 'Второстепенное меню',
			//'footer' => 'Меню для Подвала',
		    ));
    function my_wp_nav_menu_args($args=''){  
			$args['container'] = '';  
			return $args;  
		    }
    add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
    
    // HOVERS for current Post Type in menu
    
    //NEED TO CONFIG
    
    function change_page_menu_classes($menu){
    	global $post;
    	if (get_post_type($post) == 'post-type-name')
    	{
    		$menu = str_replace( 'current_page_parent', '', $menu ); // remove all current_page_parent classes
    		$menu = str_replace( 'page-item-366', 'page-item-366 current_page_parent', $menu ); // add the current_page_parent class to the page you want
    	}
    	return $menu;
    }
    add_filter( 'wp_page_menu', 'change_page_menu_classes', 0 );
    
?>