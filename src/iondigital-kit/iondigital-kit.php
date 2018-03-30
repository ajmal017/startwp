<?php
/*
Plugin Name: Iondigital Kit
Plugin URI: http://www.iondigi.com
Description: Iondigital Kit Plugin
Version: 1.0.6
Author: ION Digital
Author URI: http://www.iondigi.com
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !class_exists( 'Iondigital_Kit' ) ) {


	class Iondigital_Kit {

		 /**
		 * Plugin version, used for cache-busting of style and script file references.
		 *
		 */
		protected $version = '1.0.6';

		/**
		 * Unique identifier for your plugin.
		 *
		 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
		 * match the Text Domain file header in the main plugin file.
		 *
		 */
		protected $plugin_slug = 'iondigital-kit';
		

		protected $language_domain = 'iondigital_kit';


		protected $plugin_name = 'Iodigital Kit';

		/**
		 * The lowest supported WordPress adn PHP version
		 */
		protected $wp_support = '4.6';
		
		protected $minimalRequiredPhpVersion  = '5.6';

		protected static $theme_support;

	 	/**
		  * Instance of this class.
		 **/
		protected static $instance = null;


		public function get_plugin_version() {
			return $this->version;
		}

		public function get_plugin_name() {
			return $this->plugin_name;
		}

		public function get_language_domain() {
			return $this->language_domain;
		}



		/**
		 * This method adds other methods to specific hooks within WordPress.
		 *
		 */
		protected function __construct() {

			if ( $this->php_version_check() ) {
				$this->init();
			}

		}

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 *
		 */
		function init() {

			if ( $this->is_wp_compatible() ) {
				$this->load_modules();
				// $this->set_locale();
				$this->register_hooks();
			}

		}

		private function load_modules() {
		
			require_once( plugin_dir_path( __FILE__ ) . 'metabox.php' );
		
			$metabox = Enhanced::get_instance(  $this->get_plugin_version() );
			register_activation_hook( __FILE__ , array( 'Enhanced', 'activate' ) );

			
			/**
			 * The class responsible for defining all logic that occurs in the admin area.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/class-admin.php');

			$admin = Iondigital_Admin::instance( array(
				'version' => $this->get_plugin_version(),
				'plugin_name' => $this->get_plugin_name(),
				'language_domain' => $this->get_language_domain()
			));

	
			require_once( plugin_dir_path( __FILE__ ) . 'inc/themefunction/index.php');
			$itf = IodigitalThemeFunction::get_instance();
			
	
		}

		public function register_hooks() {
			
	
			add_action( 'wp_ajax_Iondigital_ajax_import_widgets', array($this, 'Iondigital_ajax_import_widgets') );

			add_action( 'wp_ajax_Iondigital_ajax_import_posts_pages',  array($this, 'Iondigital_ajax_import_posts_pages') );

			add_action( 'wp_ajax_Iondigital_ajax_import_theme_options',  array($this, 'Iondigital_ajax_import_theme_options'));
				

			add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
			
		}




	    /**
	     * Return an instance of this class.
	     *
	     * @since     1.0.0
	     *
	     * @return    object    A single instance of this class.
	     */
	    public static function get_instance() {

		    // If the single instance hasn't been set, set it now.
		    if ( null == self::$instance ) {
			    self::$instance = new self;
		    }

		    return self::$instance;
	    }


		public function Iondigital_ajax_import_posts_pages() {

			
			$theme_support =  $this::set_theme_support();

			$import_filepath = '';
			if( is_array( $theme_support['importer']) && ! empty($theme_support['importer']['import_filepath'])){

				$import_filepath =  $theme_support['importer']['import_filepath'];
			}


			// initialize the step importing
			$stepNumber    = 1;
			$numberOfSteps = 1;

			// get the data sent by the ajax call regarding the current step
			// and total number of steps
			if ( ! empty( $_REQUEST['step_number'] ) ) {
				$stepNumber = $_REQUEST['step_number'];
			}

			if ( ! empty( $_REQUEST['number_of_steps'] ) ) {
				$numberOfSteps = $_REQUEST['number_of_steps'];
			}

			$response = array(
				'what'         => 'import_posts_pages',
				'action'       => 'import_submit',
				'id'           => 'true',
				'supplemental' => array(
					'stepNumber'    => $stepNumber,
					'numberOfSteps' => $numberOfSteps,
				)
			);

			// check if user is allowed to save and if its his intention with
			// a nonce check
			if ( function_exists( 'check_ajax_referer' ) ) {
				check_ajax_referer( 'Iondigital_nonce_import_demo_posts_pages' );
			}

			require_once( plugin_dir_path( __FILE__ ) . 'inc/import/import-demo-posts-pages.php' );

			$response = new WP_Ajax_Response( $response );
			$response->send();
		}

		
		public function Iondigital_ajax_import_theme_options() {

	
			$theme_support = $this::set_theme_support();

			$import_filepath = '';
			if( is_array( $theme_support['importer']) && ! empty($theme_support['importer']['import_filepath'])){

				$import_filepath =  $theme_support['importer']['import_filepath'];
			}


			$response = array(
				'what'   => 'import_theme_options',
				'action' => 'import_submit',
				'id'     => 'true',
			);

			// check if user is allowed to save and if its his intention with
			// a nonce check
			if ( function_exists( 'check_ajax_referer' ) ) {
				check_ajax_referer( 'Iondigital_nonce_import_demo_theme_options' );
			}
			
			require_once( plugin_dir_path( __FILE__ ) . 'inc/import/import-demo-theme-options.php' );

			$response = new WP_Ajax_Response( $response );
			$response->send();
		}

		public function Iondigital_ajax_import_widgets() {
	
			$theme_support = $this::set_theme_support();

			$import_filepath = '';

			if( is_array( $theme_support['importer']) && ! empty($theme_support['importer']['import_filepath'])){
				
				$import_filepath =  $theme_support['importer']['import_filepath'];
			}
			
			$response = array(
				'what'   => 'import_widgets',
				'action' => 'import_submit',
				'id'     => 'true',
			);

			// check if user is allowed to save and if its his intention with
			// a nonce check
			if ( function_exists( 'check_ajax_referer' ) ) {
				check_ajax_referer( 'Iondigital_nonce_import_demo_widgets' );
			}

			require_once( plugin_dir_path( __FILE__ ) . 'inc/import/import-demo-widgets.php' );

			$response = new WP_Ajax_Response( $response );
			$response->send();
		}


		/**
		 * Register the JavaScript.
		 *
		 * @since    1.0.0
		 */
		function enqueue_scripts() {
	
		}


		function is_wp_compatible() {
			global $wp_version;
	
			if ( version_compare( $wp_version, $this->wp_support, '>=' ) ) {
				return true;
			}
			
			add_action( 'admin_notices', array( $this, 'add_incompatibility_notice' ) );
			return false;
		}
	
		function add_incompatibility_notice() {
			global $wp_version;
	
			printf(
				'<div class="%1$s"><p><strong>%2$s %3$s %4$s </strong></p><p>%5$s %6$s %7$s</p></div>',
				esc_attr( 'notice notice-error' ),
				esc_html__( "Your theme requires WordPress version", $this->language_domain ),
				$this->wp_support,
				esc_html__( "or later", $this->language_domain ),
				esc_html__( 'You\'re using an old version of WordPress', $this->language_domain ),
				$wp_version,
				esc_html__( 'which is not compatible with the current theme. Please update to the latest version to benefit from all its features.', $this->language_domain )
			);
		}
		
		/**
		 * PHP version check
		 */
		protected function php_version_check() {
	
			if ( version_compare( phpversion(), $this->minimalRequiredPhpVersion ) < 0 ) {
				add_action( 'admin_notices', array( $this, 'notice_php_version_wrong' ) );
				return false;
			}
	
			return true;
		}
	
		public function notice_php_version_wrong() {
			$allowed = array(
				'div'    => array(
					'class' => array(),
					'id'    => array(),
				),
				'p'      => array(),
				'br'     => array(),
				'strong' => array(),
			);
			$html = '<div class="updated fade">' .
					__( 'Error: plugin "' . $this->plugin_name . '" requires a newer version of PHP to be running.', $this->language_domain ) .
					'<br/>' . __( 'Minimal version of PHP required: ', $this->language_domain ) . '<strong>' . $this->minimalRequiredPhpVersion . '</strong>
					<br/>' . __( 'Your server\'s PHP version: ', $this->language_domain ) . '<strong>' . phpversion() . '</strong>
					</div>';
			echo wp_kses( $html, $allowed );
		}

		/**
		 * Set the data regarding theme_support.
		 *
		 * @return array
		 */
		public static function set_theme_support() {
			$config = get_theme_support( 'iondigital_kit' );

			if ( empty( $config ) ) {
				self::$theme_support = array();
				return self::$theme_support;
			}

			// Update the current theme_support
			self::$theme_support = reset( $config );
			return self::$theme_support;
		}

	}

    // Instantiate the class
    $iondigital_kit = Iondigital_Kit::get_instance();


}
