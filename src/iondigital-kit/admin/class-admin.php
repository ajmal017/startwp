<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Iondigital_Admin {

    /**
     * Instance of this class 
    */
    protected static $instance = null;

    protected $version = '1.0.6';

    protected static $theme_support;
    
    protected $plugin_name;

    protected $admin_option = 'iondigital_kit_option';

    /**
	 * The lowest supported WordPress adn PHP version
	 */
    protected $wp_support = '4.6';
    
    protected $minimalRequiredPhpVersion  = '5.6';

    public function __construct($info){
        if( is_array($info) ){
            if( !empty($info['version']) ){
                $this->version = $info['version'];
            }
            if( !empty($info['plugin_name']) ){
                $this->plugin_name = $info['plugin_name'];
            }

        }
        if ( $this->php_version_check() ) {
            add_action( 'init', array( $this, 'init' ) );
        }

    }

    /**
     * Initialize our class
     */
    public function init() {
        
        // init hooks 
        add_action( 'admin_init', array( 'Iondigital_Admin', 'set_theme_support' ), 11 );

        add_action( 'admin_init', array( $this, 'admin_redirects' ), 15 );
        
		add_action( 'admin_menu', array( $this, 'add_iondigital_kit_menu' ) );

		add_action( 'admin_init', array( $this, 'settings_init' ) );

		add_action( 'current_screen', array( $this, 'add_tabs' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    }

        /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        if ( self::is_iondigital_kit_dashboard() ) {
            if ( is_rtl() ) {

            } else {
                wp_enqueue_style('iondigital-dashboard', plugin_dir_url( 'iondigital-kit/admin/css/iondigital_kit_dashboard.css' ) . '/iondigital_kit_dashboard.css', array(), $this->version, 'all' );
            }
        }
    }

    /**
	 * Determine if we are looking at the Pixelgrade Care dashboard WP Admin page.
	 *
	 * @return bool
	 */
	public static function is_iondigital_kit_dashboard() {
        if ( ! empty( $_GET['page'] ) && 'iondigital_kit' === $_GET['page'] ) {
            return true;
        }
        return false;
    }

        /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        if ( self::is_iondigital_dashboard() ) {
           
            wp_enqueue_script( 'updates' );

            wp_enqueue_script( 'react', '//unpkg.com/react/umd/react.development.js' );

            wp_enqueue_script( 'react-dom', '//unpkg.com/react-dom/umd/react-dom.development.js', array('react'));

            wp_enqueue_script( 'iondigital-admin', plugin_dir_url( 'iondigital-kit/admin/js/dashboard.js' ) . 'dashboard.js', array(
                'jquery',
                'wp-util',
                'react-dom'
            ), $this->version, true );

            $this->localize_js_data();
        }


    }


    /**
     * Localize a script with or just return the `pixcare` data.
     *
     * @param string $script_id
     * @param bool $localize
     *
     * @return array
     */
    public function localize_js_data( $script_id = 'iondigital-admin', $localize = true ) {
	   

        if ( empty( self::$theme_support ) ) {
            self::set_theme_support();
        }

        $current_user = _wp_get_current_user();
        $theme_config = array();

        if ( class_exists( 'TGM_Plugin_Activation' ) ) {
            $theme_config['pluginManager']['tgmpaPlugins'] = self::localize_tgmpa_data();
        }

        // This tells us if there is a Pixelgrade theme installed, not necessarily activated.
        self::$theme_support['hasIondigitalTheme'] = self::has_iondigital_theme();

        $import_data = array (
            'import_failed' => esc_html__( 'The import didn\'t work completely!', 'bitstarter') . '<br/>' . esc_html__( 'Check out the errors given. You might want to try reloading the page and try again.', 'bitstarter'),
            'import_confirm' => esc_html__( 'Importing the demo data will overwrite your current site content and options. Proceed anyway?', 'bitstarter'),
            'import_phew' => esc_html__( 'Phew...that was a hard one!', 'bitstarter'),
            'import_success_note' => esc_html__( 'The demo data was imported without a glitch! Awesome! ', 'bitstarter') . '<br/><br/>',
            'import_success_reload' => esc_html__( '<i>We have reloaded the page on the right, so you can see the brand new data!</i>', 'bitstarter'),
            'import_success_warning' => '<p>' . esc_html__( 'Remember to update the passwords and roles of imported users.', 'bitstarter') . '</p><br/>',
            'import_all_done' => esc_html__( "All done!", 'bitstarter'),
            'import_working' => esc_html__( "Working...", 'bitstarter'),
            'import_widgets_failed' => esc_html__( "The setting up of the demo widgets failed...", 'bitstarter'),
            'import_widgets_error' => esc_html__( 'The setting up of the demo widgets failed', 'bitstarter') . '</i><br />' . esc_html__( 'The script returned the following message', 'bitstarter'),
            'import_widgets_done' => esc_html__( 'Finished setting up the demo widgets...', 'bitstarter'),
            'import_theme_options_failed' => esc_html__( "The importing of the theme options has failed...", 'bitstarter'),
            'import_theme_options_error' => esc_html__( 'The importing of the theme options has failed', 'bitstarter') . '</i><br />' . esc_html__( 'The script returned the following message', 'bitstarter'),
            'import_theme_options_done' => esc_html__( 'Finished importing the demo theme options...', 'bitstarter'),
            'import_posts_failed' => esc_html__( "The importing of the theme options has failed...", 'bitstarter'),
            'import_posts_step' => esc_html__( 'Importing posts | Step', 'bitstarter'),
            'import_error' =>  esc_html__( "Error:", 'bitstarter'),
            'import_try_reload' =>  esc_html__( "You can reload the page and try again.", 'bitstarter'),
        );

        // Use camelCase since this is going to JS!!!
        $localized_data = array(
 
            'themeSupports' => self::$theme_support,
            'themeConfig'   => $theme_config,
            'wpRest'        => array(
                'root'          => esc_url_raw( rest_url() ),
                'base'          => esc_url_raw( rest_url() . 'pixcare/v1/' ),
                //'endpoint'      => self::$internalApiEndpoints,
                'nonce'         => wp_create_nonce( 'wp_rest' ),
                'iondigital_nonce' => wp_create_nonce( 'iondigital_rest' ),
            ),
            //@todo why is this a global prop?
  
            'siteUrl'       => home_url( '/' ),
            'dashboardUrl'  => admin_url( 'admin.php?page=iondigital_kit' ),
            'adminUrl'      => admin_url(),
            'themesUrl'     => admin_url( 'themes.php' ),
            'customizerUrl' => admin_url( 'customize.php' ),
            'importer' => array(
                'ajaxUrl' => admin_url( "admin-ajax.php" ),
                'nonceImportWidgets' => wp_create_nonce( 'Iondigital_nonce_import_demo_widgets' ),
                'nonceImportThemeOptions' => wp_create_nonce( 'Iondigital_nonce_import_demo_theme_options' ),
                'nonceImportPostsPages' => wp_create_nonce( 'Iondigital_nonce_import_demo_posts_pages' ),
                'import_data' => $import_data
            ),
            'user'          => array(
                'name'   => ( empty( $current_user->display_name ) ? $current_user->user_login : $current_user->display_name ),
                'id'     => $current_user->ID,
                'email'  => $current_user->user_email,
	            // This will be filled by JS fetched from the Pixelgrade server.
	            // @todo Maybe we could fetch them in PHP also and cache them shortly
                'themes' => array(),
            ),
            'themeMod'      => array(),
            'version'       => $this->version,
        );

        /*
         * User data
         */
        $oauth_token = get_user_meta( $current_user->ID, 'pixcare_oauth_token', true );
        if ( ! empty( $oauth_token ) ) {
            $localized_data['user']['oauth_token'] = $oauth_token;
        }
        $oauth_token_secret = get_user_meta( $current_user->ID, 'pixcare_oauth_token_secret', true );
        if ( ! empty( $oauth_token_secret ) ) {
            $localized_data['user']['oauth_token_secret'] = $oauth_token_secret;
        }
        $oauth_verifier = get_user_meta( $current_user->ID, 'pixcare_oauth_verifier', true );
        if ( ! empty( $oauth_verifier ) ) {
            $localized_data['user']['oauth_verifier'] = $oauth_verifier;
        }
        $pixcare_user_ID = get_user_meta( $current_user->ID, 'pixcare_user_ID', true );
        if ( ! empty( $pixcare_user_ID ) ) {
            $localized_data['user']['pixcare_user_ID'] = $pixcare_user_ID;
        }
        $pixelgrade_user_login = get_user_meta( $current_user->ID, 'pixelgrade_user_login', true );
        if ( ! empty( $pixelgrade_user_login ) ) {
            $localized_data['user']['pixelgrade_user_login'] = $pixelgrade_user_login;
        }
        $pixelgrade_user_email = get_user_meta( $current_user->ID, 'pixelgrade_user_email', true );
        if ( ! empty( $pixelgrade_user_email ) ) {
            $localized_data['user']['pixelgrade_user_email'] = $pixelgrade_user_email;
        }
        $pixelgrade_display_name = get_user_meta( $current_user->ID, 'pixelgrade_display_name', true );
        if ( ! empty( $pixelgrade_user_email ) ) {
            $localized_data['user']['pixelgrade_display_name'] = $pixelgrade_display_name;
        }
        $user_force_disconnected = get_user_meta( $current_user->ID, 'pixcare_force_disconnected', true );
        if ( ! empty( $user_force_disconnected ) ) {
            $localized_data['user']['force_disconnected'] = true;
            // Delete the user meta so we don't nag the user, forever.
            delete_user_meta( $current_user->ID, 'pixcare_force_disconnected' );
        } else {
            $localized_data['user']['force_disconnected'] = false;
        }

        /*
         * Theme data
         */

        $license_hash = get_theme_mod( 'pixcare_license_hash' );
        if ( ! empty( $license_hash ) ) {
            $localized_data['themeMod']['licenseHash'] = $license_hash;
        }
        $license_status = get_theme_mod( 'pixcare_license_status' );
        if ( ! empty( $license_status ) ) {
            $localized_data['themeMod']['licenseStatus'] = $license_status;
        }
        // localize the license type - can be either shop or envato
        $license_type = get_theme_mod( 'pixcare_license_type' );
        if ( ! empty( $license_type ) ) {
            $localized_data['themeMod']['licenseType'] = $license_type;
        }
        // localize the license expiry date
        $license_exp = get_theme_mod( 'pixcare_license_expiry_date' );
        if ( ! empty( $license_exp ) ) {
            $localized_data['themeMod']['licenseExpiryDate'] = $license_exp;
        }
        $new_theme_version = get_theme_mod( 'pixcare_new_theme_version' );
        if ( ! empty( $new_theme_version ) ) {
            $localized_data['themeMod']['themeNewVersion'] = $new_theme_version;
        }

        //$localized_data = apply_filters( 'pixcare_localized_data', $localized_data, $script_id );

        // We can also skip the script localization, and only return the data
        if ( $localize ) {
            wp_localize_script( $script_id, 'iondigital', $localized_data );
        }

        return $localized_data;
    }   

        /**
     * Returns the localized TGMPA data used for setup wizard
     *
     * @return array
     */
    public static function localize_tgmpa_data() {
        /** @var TGM_Plugin_Activation $tgmpa */
        $tgmpa = TGM_Plugin_Activation::$instance;
        // Bail if we have nothing to work with
        if ( empty( $tgmpa ) || empty( $tgmpa->plugins ) ) {
            return array();
        }


        foreach ( $tgmpa->plugins as $slug => $plugin ) {
            // do not add Iondigital Kit in the required plugins array
            if ( $slug === 'iondigital-kit' ) {
                unset( $tgmpa->plugins[ $slug ] );
                continue;
            }
            $tgmpa->plugins[ $slug ]['is_installed']  = false;
            $tgmpa->plugins[ $slug ]['is_active']     = false;
            $tgmpa->plugins[ $slug ]['is_up_to_date'] = true;
            // We need to test for method existence because older versions of TGMPA don't have it.
            if ( method_exists( $tgmpa, 'is_plugin_installed' ) && $tgmpa->is_plugin_installed( $slug ) ) {
                $tgmpa->plugins[ $slug ]['is_installed'] = true;
                if ( method_exists( $tgmpa, 'is_plugin_active' ) && $tgmpa->is_plugin_active( $slug ) ) {
                    $tgmpa->plugins[ $slug ]['is_active'] = true;
                }
                if ( method_exists( $tgmpa, 'does_plugin_have_update' ) && $tgmpa->does_plugin_have_update( $slug ) ) {
                    $tgmpa->plugins[ $slug ]['is_up_to_date'] = false;
                }
                $data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin['file_path'], false );
                $tgmpa->plugins[ $slug ]['description']    = $data['Description'];
                $tgmpa->plugins[ $slug ]['active_version'] = $data['Version'];
            }
            $perm = current_user_can( 'activate_plugins' );
            if ( current_user_can( 'activate_plugins' ) && is_plugin_inactive( $plugin['file_path'] ) && method_exists( $tgmpa, 'get_tgmpa_url' ) ) {
                $tgmpa->plugins[ $slug ]['activate_url'] = wp_nonce_url(
                    add_query_arg(
                        array(
                            'plugin'         => urlencode( $slug ),
                            'tgmpa-activate' => 'activate-plugin',
                        ),
                        $tgmpa->get_tgmpa_url()
                    ),
                    'tgmpa-activate',
                    'tgmpa-nonce'
                );
                $tgmpa->plugins[ $slug ]['install_url'] = wp_nonce_url(
                    add_query_arg(
                        array(
                            'plugin'        => urlencode( $slug ),
                            'tgmpa-install' => 'install-plugin',
                        ),
                        $tgmpa->get_tgmpa_url()
                    ),
                    'tgmpa-install',
                    'tgmpa-nonce'
                );
            }
        }

        return $tgmpa->plugins;
    }


    /**
     * Check if everything is in order with the theme's support
     *
     * @return bool
     */
    public static function check_theme_support() {
        if ( ! current_theme_supports( 'iondigital_kit' ) ) {
            return false;
        }
        
        $config = get_theme_support( 'iondigital_kit' );
        if ( ! is_array( $config ) ) {
            return false;
        }

        $config = self::validate_theme_supports( reset( $config ) );
        if ( empty( $config ) ) {
            return false;
        }
        return true;
    }


	/**
	 * Determine if we are looking at the Pixelgrade Care dashboard WP Admin page.
	 *
	 * @return bool
	 */
	public static function is_iondigital_dashboard() {
        if ( ! empty( $_GET['page'] ) && 'iondigital_kit' === $_GET['page'] ) {
            return true;
        }
        return false;
    }


    /**
     * Set the data regarding theme_support.
     *
     * @return array
     */
    public static function set_theme_support() {
	    $config = get_theme_support( 'iondigital_kit' );


        if ( ! self::check_theme_support() || ! is_array( $config ) ) {
            self::$theme_support = self::validate_theme_supports( array() );
            return self::$theme_support;
        }

        $config = self::validate_theme_supports( reset( $config ) );
	    if ( empty( $config ) ) {
		    self::$theme_support = array();
		    return self::$theme_support;
	    }

        // Update the current theme_support
        self::$theme_support = $config;
        return self::$theme_support;
    }


    /**
     * @param array $config
     *
     * @return array
     */
    public static function validate_theme_supports( $config ) {
       
        // Detect whether the current active theme is one of ours
        if ( empty( $config['is_iondigital_theme'] ) ) {
            $config['is_iondigital_theme'] = self::is_iondigital_theme();
        }
        // Complete the config with theme details
        /** @var WP_Theme $theme */
        $theme = wp_get_theme();
        $parent = $theme->parent();
        if ( is_child_theme() && ! empty( $parent ) ) {
            $theme = $parent;
        }

        // If for some reason we couldn't get the theme name from the WUpdates code, use the standard theme name
        if ( empty( $config['theme_name'] ) ) {
            $config['theme_name'] = $theme->get( 'Name' );
        }

        if ( empty( $config['theme_uri'] ) ) {
            $config['theme_uri'] = $theme->get( 'ThemeURI' );
        }
        if ( empty( $config['theme_desc'] ) ) {
            $config['theme_desc'] = $theme->get( 'Description' );
        }
        if ( empty( $config['theme_version'] ) ) {
            $config['theme_version'] = $theme->get( 'Version' );
        }
 
        $config['is_child'] = is_child_theme();
        $config['template'] = $theme->get_template();
        return apply_filters( 'iondigital_validate_theme_supports', $config );
    }

    /**
     * The first access to PixCare needs to be redirected to the setup wizard.
     */
    function admin_redirects() {
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $plugin_version = get_option( 'iondigital_kit_version' );
       
        // if ( empty( $plugin_version ) ) {
        //     // Yay this is a fresh install and we are not on a setup page, just go there already.
        //     wp_redirect( admin_url( 'index.php?page=pixelgrade_care-setup-wizard' ) );
        //     exit;
        // }
    }

    	/**
	 * Determine if the current theme is one of ours.
	 *
	 * @return bool
	 */
	public static function is_iondigital_theme() {
   

        // Next we will test for the author in the theme header
		$theme = wp_get_theme();
		$theme_author = $theme->get( 'Author' );
		if ( ! empty( $theme_author ) && strtolower( $theme_author ) == 'iondigital' ) {
			return true;
		}

        return false;
    }

        /**
     * Determine if there are any Iondigital themes currently installed.
     *
     * @return bool
     */
    public static function has_iondigital_theme() {
        $themes = wp_get_themes();
        // Loop through the themes.
        // If we find a theme from pixelgrade return true.
        /** @var WP_Theme $theme */
        foreach ( $themes as $theme ) {
            $theme_author = $theme->get( 'Author' );

            if ( ! empty( $theme_author ) && strtolower( $theme_author ) == 'iondigital' ) {
                return true;
            }
        }

        // No themes from pixelgrade found, return false.
        return false;
    }

    	/**
	 * Adds the WP Admin menus
	 */
	public function add_iondigital_kit_menu() {

        add_menu_page( 'Iondigital', 'Iondigital Kit', 'activate_plugins', 'iondigital_kit', array(
            $this,
            'iondigital_kit_options_page',
        ), plugin_dir_url( 'iondigital-kit/admin/images/ion.png' ) . 'ion.png', 2 );

        add_submenu_page( 'iondigital_kit', 'Dashboard', 'Dashboard', 'activate_plugins', 'iondigital_kit', array(
            $this,
            'iondigital_kit_options_page',
        ) );

        add_submenu_page( 'iondigital_kit', 'Дополнительная страница инструментов', 'Название инструмента', 'manage_options', 'my-custom-submenu-page', 'my_custom_submenu_page_callback' ); 

        function my_custom_submenu_page_callback() {
            // контент страницы
            echo '<div class="wrap">';
                echo '<h2>'. get_admin_page_title() .'</h2>';
            echo '</div>';
        
        }


    }

    
    public function settings_init() {
        register_setting( 'iondigital_kit', 'iondigital_kit_settings' );
        add_settings_section(
            'iondigital_kit_section',
            'Pixelgrade Care description',
             null,
            'iondigital_kit'
        );
    }

    public function iondigital_kit_settings_section_callback() {
        echo esc_html__( 'This section description', 'iondigital_kit' );
    }

    public function iondigital_kit_options_page() { ?>
        <div class="iondigital_kit-wrapper">
            <div id="iondigital-kit-dashboard" class="Ion"></div>
        </div>
        <?php
    }

        /**
     * Add Contextual help tabs.
     */
    public function add_tabs() {
        $screen = get_current_screen();
        $screen->add_help_tab( array(
            'id'      => 'iondigital_kit_setup_wizard_tab',
            'title'   => __( 'Iondigital Kit Setup', 'iondigital_kit' ),
            'content' =>
                '<h2>' . __( 'Iondigital Kit Setup', 'iondigital_kit' ) . '</h2>' .
                '<p><a href="' . esc_url( admin_url( 'index.php?page=iondigital_kit-setup-wizard' ) ) . '" class="button button-primary">' . esc_html__( 'Setup Iondigital Kit', 'iondigital_kit' ) . '</a></p>',
        ) );
    }

    /**
     * Main Iondigital_Admin Instance
     *
     * Ensures only one instance of Iondigital_Admin is loaded or can be loaded.
     *
     * @return Iondigital_Admin 
     */
    public static function instance( $info ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $info );
        }
        return self::$instance;
    } // End instance().


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
			esc_html__( "Your theme requires WordPress version", 'pixelgrade_care' ),
			$this->wp_support,
			esc_html__( "or later", 'pixelgrade_care' ),
			esc_html__( 'You\'re using an old version of WordPress', 'pixelgrade_care' ),
			$wp_version,
			esc_html__( 'which is not compatible with the current theme. Please update to the latest version to benefit from all its features.', 'pixelgrade_care' )
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
		        __( 'Error: plugin "' . $this->plugin_name . '" requires a newer version of PHP to be running.', 'iondigital_kit' ) .
		        '<br/>' . __( 'Minimal version of PHP required: ', 'iondigital_kit' ) . '<strong>' . $this->minimalRequiredPhpVersion . '</strong>
				<br/>' . __( 'Your server\'s PHP version: ', 'iondigital_kit' ) . '<strong>' . phpversion() . '</strong>
				</div>';
		echo wp_kses( $html, $allowed );
	}


    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?' ) ), esc_html( $this->parent->get_version() ) );
    } // End __clone().

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, esc_html( __( 'Cheatin&#8217; huh?' ) ), esc_html( $this->parent->get_version() ) );
    } // End __wakeup().
}