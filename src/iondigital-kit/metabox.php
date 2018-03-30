<?php

/**
 * Plugin class.
 *

 */
class Enhanced {

	/**
	 * Path to the plugin.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_basepath = null;

	public $display_admin_menu = false;

    protected $config;
    
	protected static $instance = null;
	
    protected static $theme_support;


	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 *
	 * @param string $version
	 */
    protected $version;

	protected function __construct( $version ) {
		$this->version = $version;
		$this->plugin_basepath = plugin_dir_path( __FILE__ );
		$this->config          = self::config();

		// Load admin stylesheet and JavaScript files.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

        add_action( 'plugins_loaded', array( $this, 'register_metaboxes' ), 14 );
        
		// We need this later then the default 10 priority so we can have things happening between the init-10 and the config
		add_action( 'init', array( 'Enhanced', 'set_theme_support' ), 11 );
        add_action( 'init', array( $this, 'theme_version_check' ), 15 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @param string $version The current plugin version.
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance( $version ) {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self( $version );
		}

		return self::$instance;
	}

	public static function config() {

		return array(
            
            'settings-key' => 'iondigital_settings'

        );
	}



	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate() {
		$config = self::config();

		/** get options defined by themes */
		$theme_types    = get_option( 'iondigital_themes_settings' );
        $types_settings = get_option( $config['settings-key'] );
    
        $current_theme  = '_iondigital_theme';
		// init settings
		if ( empty( $theme_types ) ) {
            $theme_types = array();
		}
        
		if ( empty( $types_settings ) ) {
            $types_settings = array( 'themes' => array() );
		}
        
		if ( class_exists( 'ThemeConfig' ) ) {
			
            $current_theme = ThemeConfig::shortname() . $current_theme;
			$types_settings['theme_version'] = ThemeConfig::themeversion();

        }else{

			$current_theme = self::$theme_support['theme_config']['shortname'] . $current_theme;
            $types_settings['theme_version'] = self::$theme_support['theme_config']['theme_data']['Version'];

		}

        if ( ! empty( $theme_types ) ) {
			foreach ( $theme_types as $theme_key => $theme ) {
				$theme_name = str_replace( '_iondigital_theme', '', $theme_key );
				
				$types_settings['themes'][ $theme_name ] = $theme_types[ $theme_key ];
			}
        }
        
		update_option( $config['settings-key'], $types_settings );

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, basename( dirname( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_localize_script( $this->plugin_slug . '-admin-script', 'locals',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' )
				)
			);
		}
	}


	function register_metaboxes() {
		require_once( $this->plugin_basepath . 'inc/metaboxes/metaboxes.php' );
	}

	/**
	 * On every wpgrade themes update we need to reconvert theme options into plugin options
	 */
	function theme_version_check() {
		if ( class_exists( 'ThemeConfig' ) ) {

			$config = self::config();
			// Each theme should have it's config theme version saved
			$options = get_option( $config['settings-key'] );

			// Make sure that we fix things just in case
			
			if ( ! isset( $options['theme_version'] ) ) {
				$options['theme_version'] = '0.0.1';
			}
			if ( version_compare( ThemeConfig::themeversion(), $options['theme_version'], '!=' ) ) {
                // here the theme is updating it's options
                $setup = ThemeConfig::setup();
				if ( function_exists( $setup ) ) {
					$setup();
					// the plugin will copy these options into it's own field
					self::activate(  );
				}
			}
		}elseif( is_array(self::$theme_support) && isset(self::$theme_support['theme_config']) ){

			$config = self::config();
			// Each theme should have it's config theme version saved
			$options = get_option( $config['settings-key'] );

			// Make sure that we fix things just in case
			
			if ( ! isset( $options['theme_version'] ) ) {
				$options['theme_version'] = '0.0.1';
			}
			if ( version_compare( self::$theme_support['theme_config']['theme_data']['Version'] , $options['theme_version'], '!=' ) ) {
                // here the theme is updating it's options
				$setup = self::$theme_support['theme_config']['setup'];
				if ( function_exists( $setup ) ) {
					$setup();
					// the plugin will copy these options into it's own field
					self::activate();
				}
			}
		}
	}


	function get_plugin_version() {
		return $this->version;
	}

	    /**
     * Set the data regarding theme_support.
     *
     * @return array
     */
    public static function set_theme_support() {
	    $config = get_theme_support( 'iondigital_kit' );

	    if ( empty( $config ) ) {
		    self::$theme_support = null;
		    return self::$theme_support;
	    }

        // Update the current theme_support
        self::$theme_support = reset( $config );
        return self::$theme_support;
    }
}

