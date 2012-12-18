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
				wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"), false);
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
    
	function remove_menus () {
	global $menu;
		$restricted = array(
				__('Dashboard'), 
				__('Posts'), 
				__('Media'), 
				__('Links'), 
				__('Pages'), 
				__('Appearance'), 
				__('Tools'), 
				__('Users'), 
				__('Settings'), 
				__('Comments'), 
				__('Plugins')
			);
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
	}
    
    
    /* Disable WordPress Admin Bar for all users but admins. */
    show_admin_bar(false);
    
    /* Disable WordPress Dashboard Admin Bar Add Button */
    function removeDashboardAdminBarAddButton() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('new-content');
        $wp_admin_bar->remove_menu('new-post');
        $wp_admin_bar->remove_menu('new-page');
        $wp_admin_bar->remove_menu('new-media');
        $wp_admin_bar->remove_menu('new-link');
        $wp_admin_bar->remove_menu('new-user');
        $wp_admin_bar->remove_menu('new-theme');
        $wp_admin_bar->remove_menu('new-plugin');
    }
    add_action( 'wp_before_admin_bar_render', 'removeDashboardAdminBarAddButton' );
    
    /* Removing default Dashboard widgets */
    function disable_default_dashboard_widgets() {  
         remove_meta_box('dashboard_right_now', 'dashboard', 'core');  
         remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');  
         remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  
         remove_meta_box('dashboard_plugins', 'dashboard', 'core');  
         remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  
         remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');  
         remove_meta_box('dashboard_primary', 'dashboard', 'core');  
         remove_meta_box('dashboard_secondary', 'dashboard', 'core');  
    }
    add_action('admin_menu', 'disable_default_dashboard_widgets');
    
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
    
    // WP-eCommerce items - have post-type "wpsc-product"
    
    function tg_add_class_to_menu($classes)
    {
    	// team is my custom post type
    	if (is_singular('wpsc-product'))
    	{
    		// we're viewing a custom post type, so remove the 'current-page' from all menu items.
    		$classes = array_filter($classes, "remove_parent");
    
    		// add the current page class to a specific menu item.
    		if (in_array('page-item-23', $classes)) $classes[] = 'current-page-ancestor';
    	}
    
    	return $classes;
    }
    
    if (!is_admin()) { add_filter('nav_menu_css_class', 'tg_add_class_to_menu'); }
    
?>