<?php
/**
 * Bitstarter functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bitstarter
 */

if ( ! function_exists( 'bitstarter_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bitstarter_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Bitstarter, use a find and replace
		 * to change 'bitstarter' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'bitstarter', get_template_directory() . '/languages' );

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
		add_theme_support( 'post-thumbnails', array( 'page', 'post' ));

		// Used for Listing Cards
		// Max Width of 450px
		add_image_size( 'bitstarter-card-image', 700, 500, true);

		// Used for Single Listing carousel images
		// Max Height of 800px
		add_image_size('bitstarter-carousel-image', 9999, 800, false );

		// Used for Full Width (fill) images on Pages and Listings
		// Max Width of 2700px
		add_image_size('bitstarter-featured-image', 2700, 9999, false );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'            => esc_html__( 'Primary Menu', 'bitstarter' )
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
		add_theme_support( 'post-formats', array('gallery','audio', 'quote', 'video', 'link') );


		add_post_type_support( 'page', 'excerpt' );

		remove_post_type_support( 'page', 'thumbnail' );
		remove_post_type_support( 'page', 'subtitles' ); 

		// custom javascript handlers - make sure it is the last one added
		add_action( 'wp_head', 'bitstarter_load_custom_js_header', 999 );
		add_action( 'wp_footer', 'bitstarter_load_custom_js_footer', 999 );

		/*
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts and self-hosted ones
		 */
		add_editor_style( array( 'editor-style.css' ) );

		function jptweak_remove_share() {
			remove_filter('the_content', 'sharing_display', 19);
			remove_filter('the_excerpt', 'sharing_display', 19);
			if (class_exists('Jetpack_Likes')) {
				remove_filter('the_content', array(Jetpack_Likes::init(), 'post_likes'), 30, 1);
			}
		}

		add_action('loop_start', 'jptweak_remove_share');


	}
endif; // bitstarter_setup
add_action( 'after_setup_theme', 'bitstarter_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bitstarter_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bitstarter_content_width', 1140, 0 );
}
add_action( 'after_setup_theme', 'bitstarter_content_width', 0 );

/**
 * Set the gallery widget width in pixels, based on the theme's design and stylesheet.
 */
function bitstarter_gallery_widget_width( $args, $instance ) {
	return '1050';
}

add_filter( 'gallery_widget_content_width', 'bitstarter_gallery_widget_width', 10, 3 );

/**
 * Enqueue scripts and styles.
 */
function bitstarter_scripts() {
	$theme = wp_get_theme();


	$main_style_deps = array();

	//only enqueue the de default font if Customify is not present
	if ( ! class_exists( 'PixCustomifyPlugin' ) ) {
		wp_enqueue_style( 'bitstarter-default-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,300,600,700' );
		wp_enqueue_style( 'bitstarter-default-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,700' );
		$main_style_deps[] = 'bitstarter-default-fonts';
		
		wp_enqueue_style( 'bitstarter-default-theme', get_template_directory_uri() . '/styles/theme.css'  );
		$main_style_deps[] = 'bitstarter-default-theme';

	}

	if ( !is_rtl() ) {
		wp_enqueue_style( 'bitstarter-style', get_stylesheet_uri(), $main_style_deps, $theme->get( 'Version' ) );
	}

	global $post;
	$bitstarter_scripts_deps = array('jquery');
	wp_enqueue_script( 'tween-max', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'tween-max';
	wp_enqueue_script('imagesloaded', '//unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'imagesloaded';
	wp_enqueue_script('polyfill', '//cdn.polyfill.io/v2/polyfill.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'polyfill';
	wp_enqueue_script( 'scroll-to-plugin', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/plugins/ScrollToPlugin.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'scroll-to-plugin';
	wp_enqueue_script( 'cssplugin', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/plugins/CSSPlugin.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'cssplugin';
	wp_enqueue_script( 'mousewheel', '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'mousewheel';
	wp_enqueue_script( 'magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'magnific';
	wp_enqueue_script( 'modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'modernizr';
	wp_enqueue_script('slick', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js', array( 'jquery' ) );
	$bitstarter_scripts_deps[] = 'slick';

	wp_enqueue_style( 'slick-style', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css' );
	//wp_enqueue_style('slick-style-theme', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css');

	wp_register_script('highcharts', '//code.highcharts.com/highcharts.js', array( 'jquery' ));
	$bitstarter_scripts_deps[] = 'highcharts';

	wp_enqueue_script( 'bitstarter-scripts', get_template_directory_uri() . '/assets/js/main.js', $bitstarter_scripts_deps, $theme->get( 'Version' ), true );

	wp_localize_script( 'jquery', 'BitstarterParams', array(
		'ajax' => array (
				'url' => admin_url('admin-ajax.php'),
				'likes_action' => 'bitstarter_set_likes_number'
			),
		'login_url' => rtrim( esc_url( wp_login_url() ) , '/')
	) ); 



}


add_action( 'wp_enqueue_scripts', 'bitstarter_scripts' );

function bitstarter_admin_scripts() {

	if ( bitstarter_is_edit_page() ) {
		wp_enqueue_script('polyfill', '//cdn.polyfill.io/v2/polyfill.min.js', array('jquery'));
		wp_enqueue_script( 'bitstarter-admin-edit-scripts', get_template_directory_uri() . '/assets/js/admin/edit-page.js', array( 'jquery', 'polyfill' ), '1.0.0', true );

		wp_enqueue_style( 'bitstarter-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/edit-page.css' );

		wp_localize_script( 'bitstarter-admin-edit-scripts', 'BitstarterParams', array(
			'blog_id' => get_option('page_for_posts')
		) ); 
		
	}


}

add_action( 'admin_enqueue_scripts', 'bitstarter_admin_scripts' );


/**
 * Load custom javascript set by theme options
 * The function is executed on wp_enqueue_scripts
 */
function bitstarter_load_custom_js_header() {
	$custom_js = bitstarter_get_option( 'custom_js' );
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

function bitstarter_load_custom_js_footer() {
	$custom_js = bitstarter_get_option( 'custom_js_footer' );
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
function bitstarter_mce_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'bitstarter_mce_buttons');

// Callback function to filter the MCE settings
function bitstarter_formats( $init_array ) {
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
add_filter( 'tiny_mce_before_init', 'bitstarter_formats' );
