<?php
/**
 * Listable functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Listable
 */

if ( ! function_exists( 'listable_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function listable_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Listable, use a find and replace
		 * to change 'listable' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'listable', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails', array( 'page' ));

		// Used for Listing Cards
		// Max Width of 450px
		add_image_size( 'listable-card-image', 450, 9999, false );

		// Used for Single Listing carousel images
		// Max Height of 800px
		add_image_size( 'listable-carousel-image', 9999, 800, false );

		// Used for Full Width (fill) images on Pages and Listings
		// Max Width of 2700px
		add_image_size( 'listable-featured-image', 2700, 9999, false );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'            => esc_html__( 'Primary Menu', 'listable' ),
			'secondary'          => esc_html__( 'Secondary Menu', 'listable' ),
			'search_suggestions' => esc_html__( 'Search Menu', 'listable' ),
			'footer_menu'        => esc_html__( 'Footer Menu', 'listable' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'custom-logo' );

		/*
		 * No support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array() );


		add_post_type_support( 'page', 'excerpt' );

		remove_post_type_support( 'page', 'thumbnail' );

		// custom javascript handlers - make sure it is the last one added
		add_action( 'wp_head', 'listable_load_custom_js_header', 999 );
		add_action( 'wp_footer', 'listable_load_custom_js_footer', 999 );

		/*
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts and self-hosted ones
		 */
		// add_editor_style( array( 'editor-style.css' ) );

	}
endif; // listable_setup
add_action( 'after_setup_theme', 'listable_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function listable_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'listable_content_width', 1050, 0 );
}
add_action( 'after_setup_theme', 'listable_content_width', 0 );

/**
 * Set the gallery widget width in pixels, based on the theme's design and stylesheet.
 */
function listable_gallery_widget_width( $args, $instance ) {
	return '1050';
}

add_filter( 'gallery_widget_content_width', 'listable_gallery_widget_width', 10, 3 );

/**
 * Enqueue scripts and styles.
 */
function listable_scripts() {
	$theme = wp_get_theme();

	// Add an API key if available in Listings -> Settings Google Maps API Key.
	$google_maps_key = get_option( 'job_manager_google_maps_api_key' );

	// back-compat with the old Listable field Google Maps API Key.
	if ( empty( $google_maps_key ) ) {
		$google_maps_key = pixelgrade_option( 'google_maps_api_key' );
	}

	if ( ! empty( $google_maps_key ) ) {
		$google_maps_key = '&key=' . $google_maps_key;
	} else {
		$google_maps_key = '';
	}

	//if there is no mapbox token use Google Maps instead
	if ( '' == pixelgrade_option( 'mapbox_token', '' ) ) {
		wp_deregister_script('google-maps');
		wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?v=3.exp&amp;libraries=places' . $google_maps_key, array(), '3.22', true );
		$listable_scripts_deps[] = 'google-maps';
	} elseif ( wp_script_is( 'google-maps' )  ) {
		wp_deregister_script('google-maps');
		wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?v=3.exp&amp;libraries=places' . $google_maps_key, array(), '3.22', false );
		$listable_scripts_deps[] = 'google-maps';
	}

	wp_deregister_style( 'wc-paid-listings-packages' );
	wp_deregister_style( 'wc-bookings-styles' );

	$main_style_deps = array();

	//only enqueue the de default font if Customify is not present
	if ( ! class_exists( 'PixCustomifyPlugin' ) ) {
		wp_enqueue_style( 'listable-default-fonts', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,700' );
		$main_style_deps[] = 'listable-default-fonts';
	}

	if ( !is_rtl() ) {
		wp_enqueue_style( 'listable-style', get_stylesheet_uri(), $main_style_deps, $theme->get( 'Version' ) );
	}

	global $post;
	$listable_scripts_deps = array('jquery');
	wp_enqueue_script( 'tween-lite', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/TweenLite.min.js', array( 'jquery' ) );
	$listable_scripts_deps[] = 'tween-lite';
	wp_enqueue_script( 'scroll-to-plugin', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/plugins/ScrollToPlugin.min.js', array( 'jquery' ) );
	$listable_scripts_deps[] = 'scroll-to-plugin';
	wp_enqueue_script( 'cssplugin', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/plugins/CSSPlugin.min.js', array( 'jquery' ) );
	$listable_scripts_deps[] = 'cssplugin';
	wp_enqueue_script( 'mousewheel', '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js', array( 'jquery' ) );
	$listable_scripts_deps[] = 'mousewheel';
	wp_enqueue_script( 'magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array( 'jquery' ) );
	$listable_scripts_deps[] = 'magnific';
	wp_enqueue_script( 'modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', array( 'jquery' ) );
	$listable_scripts_deps[] = 'modernizr';
	wp_enqueue_script( 'system', get_template_directory_uri() . '/assets/js/system.js', $listable_scripts_deps, $theme->get( 'Version' ), true );
	$listable_scripts_deps[] = 'system';
	$template = get_template_directory_uri();
	$systemjs_modules = json_encode(array('test.js', 'test1.js', 'main.js'));
	wp_add_inline_script('system', "$systemjs_modules.reduce(
        async (chain, module) => {
          await chain;
          return SystemJS.import(`$template/assets/js/systemjs/\${module}`);
        },
        Promise.resolve() 
      )");
	wp_enqueue_script( 'test', get_template_directory_uri() . '/assets/js/systemjs/test.js', $listable_scripts_deps, $theme->get( 'Version' ), true );
	wp_enqueue_script( 'test1', get_template_directory_uri() . '/assets/js/systemjs/test1.js', $listable_scripts_deps, $theme->get( 'Version' ), true );
	wp_enqueue_script( 'listable-scripts', get_template_directory_uri() . '/assets/js/systemjs/main.js', $listable_scripts_deps, $theme->get( 'Version' ), true );
	wp_localize_script( 'listable-scripts', 'listable_params', array(
		'login_url' => rtrim( esc_url( wp_login_url() ) , '/'),
		'listings_page_url' => listable_get_listings_page_url(),
		'strings' => array(
			'wp-job-manager-file-upload' => esc_html__( 'Add Photo', 'listable' ),
			'no_job_listings_found' => esc_html__( 'No results', 'listable' ),
			'results-no' => esc_html__( 'Results', 'listable'), //@todo this is not quite right as it is tied to the number of results - they can 1 or 0
			'select_some_options' => esc_html__( 'Select Some Options', 'listable' ),
			'select_an_option' => esc_html__( 'Select an Option', 'listable' ),
			'no_results_match' => esc_html__( 'No results match', 'listable' ),
			'social_login_string' => esc_html__( 'or', 'listable' ),
		)
	) ); 

}


add_action( 'wp_enqueue_scripts', 'listable_scripts' );

function listable_admin_scripts() {

	if ( listable_is_edit_page() ) {
		wp_enqueue_script( 'listable-admin-edit-scripts', get_template_directory_uri() . '/assets/js/admin/edit-page.js', array( 'jquery' ), '1.0.0', true );

		if ( get_post_type() === 'page' ) {
			wp_enqueue_style( 'listable-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/edit-page.css' );
		}
	}

	if ( listable_is_nav_menus_page() ) {
		wp_enqueue_script( 'listable-admin-nav-menus-scripts', get_template_directory_uri() . '/assets/js/admin/edit-nav-menus.js', array( 'jquery' ), '1.0.0', true );
	}

	wp_enqueue_script( 'listable-admin-general-scripts', get_template_directory_uri() . '/assets/js/admin/admin-general.js', array( 'jquery' ), '1.0.0', true );

	$translation_array = array (
			'import_failed' => esc_html__( 'The import didn\'t work completely!', 'listable') . '<br/>' . esc_html__( 'Check out the errors given. You might want to try reloading the page and try again.', 'listable'),
			'import_confirm' => esc_html__( 'Importing the demo data will overwrite your current site content and options. Proceed anyway?', 'listable'),
			'import_phew' => esc_html__( 'Phew...that was a hard one!', 'listable'),
			'import_success_note' => esc_html__( 'The demo data was imported without a glitch! Awesome! ', 'listable') . '<br/><br/>',
			'import_success_reload' => esc_html__( '<i>We have reloaded the page on the right, so you can see the brand new data!</i>', 'listable'),
			'import_success_warning' => '<p>' . esc_html__( 'Remember to update the passwords and roles of imported users.', 'listable') . '</p><br/>',
			'import_all_done' => esc_html__( "All done!", 'listable'),
			'import_working' => esc_html__( "Working...", 'listable'),
			'import_widgets_failed' => esc_html__( "The setting up of the demo widgets failed...", 'listable'),
			'import_widgets_error' => esc_html__( 'The setting up of the demo widgets failed', 'listable') . '</i><br />' . esc_html__( '(The script returned the following message', 'listable'),
			'import_widgets_done' => esc_html__( 'Finished setting up the demo widgets...', 'listable'),
			'import_theme_options_failed' => esc_html__( "The importing of the theme options has failed...", 'listable'),
			'import_theme_options_error' => esc_html__( 'The importing of the theme options has failed', 'listable') . '</i><br />' . esc_html__( '(The script returned the following message', 'listable'),
			'import_theme_options_done' => esc_html__( 'Finished importing the demo theme options...', 'listable'),
			'import_posts_failed' => esc_html__( "The importing of the theme options has failed...", 'listable'),
			'import_posts_step' => esc_html__( 'Importing posts | Step', 'listable'),
			'import_error' =>  esc_html__( "Error:", 'listable'),
			'import_try_reload' =>  esc_html__( "You can reload the page and try again.", 'listable'),
	);
	wp_localize_script( 'listable-admin-general-scripts', 'listable_admin_js_texts', $translation_array );
}

add_action( 'admin_enqueue_scripts', 'listable_admin_scripts' );


/**
 * Load custom javascript set by theme options
 * This method is invoked by wpgrade_callback_themesetup
 * The function is executed on wp_enqueue_scripts
 */
function listable_load_custom_js_header() {
	$custom_js = pixelgrade_option( 'custom_js' );
	if ( ! empty( $custom_js ) ) {
		//first lets test is the js code is clean or has <script> tags and such
		//if we have <script> tags than we will not enclose it in anything - raw output
		if ( strpos( $custom_js, '</script>' ) !== false ) {
			echo $custom_js . "\n";
		} else {
			echo "<script type=\"text/javascript\">\n;(function($){\n" . $custom_js . "\n})(jQuery);\n</script>\n";
		}
	}
}

function listable_load_custom_js_footer() {
	$custom_js = pixelgrade_option( 'custom_js_footer' );
	if ( ! empty( $custom_js ) ) {
		//first lets test is the js code is clean or has <script> tags and such
		//if we have <script> tags than we will not enclose it in anything - raw output
		if ( strpos( $custom_js, '</script>' ) !== false ) {
			echo $custom_js . "\n";
		} else {
			echo "<script type=\"text/javascript\">\n;(function($){\n" . $custom_js . "\n})(jQuery);\n</script>\n";
		}
	}
}

/**
 * Implement the Custom Header feature.
 */
// require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */

require get_template_directory() . '/inc/extras.php';

require get_template_directory() . '/inc/widgets.php';

require get_template_directory() . '/inc/activation.php';

/**
 * Load various plugin integrations
 */
require get_template_directory() . '/inc/integrations.php';

/**
 * Load theme's configuration file (via Customify plugin)
 */
require get_template_directory() . '/inc/config.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Recommended/Required plugins notification
 */
require get_template_directory() . '/inc/required-plugins/required-plugins.php';



// Callback function to insert 'styleselect' into the $buttons array
function listable_mce_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'listable_mce_buttons');

// Callback function to filter the MCE settings
function listable_formats( $init_array ) {
	// Define the style_formats array
	$style_formats = array(
		// Each array child is a format with it's own settings
		array(
			'title' => 'Intro',
			'inline' => 'span',
			'classes' => 'intro',
			'wrapper' => true
		),
		array(
			'title' => 'Two Columns',
			'block' => 'div',
			'classes' => 'twocolumn',
			'wrapper' => true
		),
		array(
			'title' => 'Separator',
			'block' => 'hr',
			'classes' => 'clear'
		),
	);
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );

	return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'listable_formats' );
