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

		// Used for Cards
		// Max Width of 450px
		add_image_size( 'bitstarter-card-image', 700, 500, true);

		// Used for Single carousel images
		// Max Height of 800px
		add_image_size('bitstarter-carousel-image', 9999, 800, false );

		// Used for Full Width (fill) images on Pages
		// Max Width of 1920px
		add_image_size('bitstarter-featured-image', 1920, 9999, true );

		// Iondigital Core

		function bitstarter_themedata() {
				
			if ( is_child_theme() ) {
				$theme_name  = get_template();
				$theme_data = wp_get_theme( $theme_name );
			} else {
				$theme_data = wp_get_theme();
			}
			
			return $theme_data;
		}

		add_theme_support( 'iondigital_kit',  array(
			'theme_config' => array(
				'active' => true,
				'shortname' => 'bitstarter',
				'setup' => 'bitstarter_config_getting_active',
				'theme_data' => bitstarter_themedata()
			),
			'importer' => array(
				'active' => true,
				'import_filepath' => get_template_directory() . '/inc/demo-data/demo_data',
				'import_info' => array(
					'bitstarter' => array(
                        'title' => 'Main',
                        'description' => 'Our main demo.',
                        'link' => 'http://bitstarter.iondigi.com/',
                        'img' => get_template_directory_uri() . '/inc/demo-data/bitstarter.png'
                    ),
				)
			),
			'share_servises' => array(
				'facebook' => 'assets/svg/fb-icon.php',
				'twitter' => 'assets/svg/tw-icon.php',
				'google' => 'assets/svg/goog-icon.php',
				'pinterest' => 'assets/svg/pntr-icon.php',
			),
			'likes' => array(
				'icon' => 'assets/svg/likes-icon.php' 
			)
		));
		
	
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
		 * Support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array('gallery','audio', 'quote', 'video', 'link') );


		add_post_type_support( 'page', 'excerpt' );

		remove_post_type_support( 'page', 'thumbnail' );
		remove_post_type_support( 'page', 'subtitles' ); 

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

function bitstarter_check_cdn(){

	$protocol = is_ssl()  ? 'https' : 'http';
	$cdn  = $protocol . '://cdnjs.cloudflare.com';

	$repository = get_transient( 'check_cdn' );

	if ( 'false' == ( $repository = get_transient( 'check_cdn' ) ) ) {
		return true;
	}
	elseif ( false === $repository ) {
		$response = wp_remote_head( $cdn );
		if ( ! is_wp_error( $response ) && 200 == $response[ 'response' ][ 'code' ] ) {
			set_transient( 'check_cdn', 'true', 60 * 5 );
			return true;
		}  else {
			set_transient( 'check_cdn', 'false', 60 * 5 );
			return false;
		}
	}

}

function bitstarter_fonts_url() {
    $font_url = '';
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'bitstarter' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Noto Sans:400,400italic,700italic,700&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
	}
	
    return $font_url;
}


function bitstarter_scripts() {
	$theme = wp_get_theme();

	$main_style_deps = array();

	//only enqueue the de default font if Customify is not present
	if ( ! class_exists( 'PixCustomifyPlugin' ) ) {
		wp_enqueue_style( 'bitstarter-default-fonts', bitstarter_fonts_url(), array(), '1.0.0');

		$main_style_deps[] = 'bitstarter-default-fonts';
		
		wp_enqueue_style( 'bitstarter-default-theme', get_template_directory_uri() . '/styles/theme.css'  );
		$main_style_deps[] = 'bitstarter-default-theme';

	}

	if ( !is_rtl() ) {
		wp_enqueue_style( 'bitstarter-style', get_stylesheet_uri(), $main_style_deps, $theme->get( 'Version' ) );
	}

	global $post;
	$bitstarter_scripts_deps = array('jquery');

	$is_cdn_available = bitstarter_check_cdn();

	if( $is_cdn_available ) {
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

		wp_enqueue_script('slick', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'slick';

		wp_enqueue_style( 'slick-style', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css' );

		wp_enqueue_script('highcharts', '//code.highcharts.com/highcharts.js', array( 'jquery' ));
		$bitstarter_scripts_deps[] = 'highcharts';

	} else {

		wp_enqueue_script( 'tween-max', get_template_directory_uri() . '/assets/js/fallbacks/tween-max.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'tween-max';
		
		wp_enqueue_script('imagesloaded', get_template_directory_uri() . '/assets/js/fallbacks/imagesLoaded.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'imagesloaded';

		wp_enqueue_script( 'scroll-to-plugin', get_template_directory_uri() . '/assets/js/fallbacks/scroll-to-plugin.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'scroll-to-plugin';

		wp_enqueue_script( 'cssplugin', get_template_directory_uri() . '/assets/js/fallbacks/CSSPlugin.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'cssplugin';

		wp_enqueue_script( 'mousewheel', get_template_directory_uri() . '/assets/js/fallbacks/jquery.mousewheel.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'mousewheel';

		wp_enqueue_script('slick', get_template_directory_uri() . '/assets/js/fallbacks/slick.min.js', array( 'jquery' ) );
		$bitstarter_scripts_deps[] = 'slick';

		wp_enqueue_style( 'slick-style', get_template_directory_uri() . '/assets/css/bundle.css' );

		wp_enqueue_script('highcharts', get_template_directory_uri() . '/assets/js/fallbacks/Highcharts.min.js', array( 'jquery' ));
		$bitstarter_scripts_deps[] = 'highcharts';
	}

	wp_enqueue_script( 'bitstarter-polyfill', get_template_directory_uri() . '/assets/js/polyfill.js' );
	$bitstarter_scripts_deps[] = 'bitstarter-polyfill';

	wp_enqueue_script( 'bitstarter-sw', get_template_directory_uri() . '/assets/js/install-sw.js' );
	$bitstarter_scripts_deps[] = 'bitstarter-sw';

	wp_enqueue_script( 'bitstarter-scripts', get_template_directory_uri() . '/assets/js/main.js', $bitstarter_scripts_deps, $theme->get( 'Version' ), true );

	wp_localize_script( 'jquery', 'IondigitalThemeParams', array(
		'ajax' => array (
				'url' => admin_url('admin-ajax.php'),
				'likes_action' => 'Iondigital_set_likes_number'
			),
		'login_url' => rtrim( esc_url( wp_login_url() ) , '/'),
		'templateUrl' => get_bloginfo('template_url')
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





function bitstarter_allowed_html() {

	$allowed_tags = array(
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'abbr' => array(
			'title' => array(),
		),
		'b' => array(),
		'blockquote' => array(
			'cite'  => array(),
		),
		'cite' => array(
			'title' => array(),
		),
		'code' => array(),
		'del' => array(
			'datetime' => array(),
			'title' => array(),
		),
		'dd' => array(),
		'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
			'data-slider' => array()
		),
		'table'  => array(
			'class' => array(),
			'style' => array()
		),
		'tbody'  => array(),
		'td'  => array(
			'class' => array(),
			'style' => array()
		),
		'tr'  => array(
			'class' => array(),
			'style' => array()
		),
		'dl' => array(),
		'dt' => array(),
		'em' => array(
			'class' => array(),
			'style' => array()
		),
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
		'h4' => array(),
		'h5' => array(),
		'h6' => array(),
		'i' => array(
			'class' => array(),
			'style' => array()
		),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'srcset'    => array(),
			'width'  => array(),
		),
		'li' => array(
			'class' => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
			'style' => array(),
			'data-bind' => array()
		),
		'q' => array(
			'cite' => array(),
			'title' => array(),
		),
		'embed' => array(
			'data-ratio' => array(),
			'width' => array(),
			'data-width' => array(),
			'height' => array(),
			'data-height' => array()
		),
		'iframe' => array(
			'data-ratio' => array(),
			'width' => array(),
			'data-width' => array(),
			'height' => array(),
			'data-height' => array()
		),
		'object' => array(
			'data-ratio' => array(),
			'width' => array(),
			'data-width' => array(),
			'height' => array(),
			'data-height' => array()
		),
		'span' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
			'data-post-id' => array()
		),
		'strike' => array(),
		'strong' => array(
			'class' => array(),
			'style' => array()
		),
		'ul' => array(
			'class' => array(),
			'style' => array()
		),
		'svg' => array(
			'width' => array(),
			'height' => array(),
			'version' => array(),
			'viewBox' => array(),
			'xmlns' => array(),
			'xmlns:xlink' => array()
		),
		'path' => array(
			'd' => array(),
			'transform' => array(),
			'fill' => array(),
			'style' => array(),
			'fill-rule' => array(),
			'id' => array(),
		),
		'g' => array(
			'transform' => array(),
			'fill' => array(),
			'opacity' => array(),
			'stroke' => array(),
			'stroke-width' => array(),
			'fill-rule' => array(),
			'style' => array(),
			'id' => array(),
		),
		'defs' => array(),
		'fieldset' => array(),
		'legend' => array()
	);
	return $allowed_tags;
}

define( 'PWA_THEME_MANIFEST_ARG', 'jetpack_app_manifest' );


function pwa_theme_get_manifest_path() {
    return add_query_arg( PWA_THEME_MANIFEST_ARG, '1', site_url() );
  }

  function pwa_theme_register_query_vars( $vars ) {
    // $vars[] = PWA_SW_QUERY_VAR;
    $vars[] = PWA_THEME_MANIFEST_ARG;
    return $vars;
  }
  add_filter( 'query_vars', 'pwa_theme_register_query_vars' );

function pwa_theme_render_custom_assets() {
	global $wp_query;

    if ( $wp_query->get( PWA_THEME_MANIFEST_ARG ) ) {
      $theme_color = pwa_theme_get_theme_color();

      $manifest = array(
          'start_url'  => get_bloginfo( 'url' ),
          'short_name' => get_bloginfo( 'name' ),
          'name'       => get_bloginfo( 'name' ),
          'display'    => 'standalone',
          'background_color' => $theme_color,
		  'theme_color' => $theme_color,
      );

      $icon_48 = pwa_site_icon_url( 48 );

      if ( $icon_48 ) {
          $manifest[ 'icons' ] = array(
              array(
                  'src' => $icon_48,
                  'sizes' => '48x48'
              ),
              array(
                  'src' => pwa_site_icon_url( 192 ),
                  'sizes' => '192x192'
              ),
              array(
                  'src' => pwa_site_icon_url( 512 ),
                  'sizes' => '512x512'
              )
          );
      }

      wp_send_json( $manifest );
    }
  }
  add_action( 'template_redirect', 'pwa_theme_render_custom_assets', 2 );

  
  function pwa_theme_get_theme_color() {
     // if we have AMP enabled, use those colors?
    if ( current_theme_supports( 'custom-background' ) ) {
      $theme_color = get_theme_support( 'custom-background' )->{'default-color'};
    } else {
      $theme_color = '#FFF';
    }
    return apply_filters( 'pwa_theme_background_color', $theme_color );
  }


  function pwa_site_icon_url( $size ) {
    $url = get_site_icon_url( $size );

    if ( ! $url ) {
      if ( ! function_exists( 'jetpack_site_icon_url' ) ) {
        require_once( JETPACK__PLUGIN_DIR . 'modules/site-icon/site-icon-functions.php' );
      }
      $url = jetpack_site_icon_url( null, $size );
    }

    return $url;
  }
