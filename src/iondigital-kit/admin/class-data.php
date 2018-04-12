<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Iondigital_Data{

	public $parent = null;

	private $config = null;

	protected static $_instance = null;

	public function __construct( $parent ) {
		$this->parent = $parent;

		add_action( 'init', array( $this, 'init' ) );

		$this->config = Iondigital_Admin::get_theme_support();

	}

	public static function get_system_status_data() {

		$data = self::instance( Iondigital_Admin::instance(array()));

		return array(
			'installation'     => $data->get_install_data(),
			'system'           => $data->get_system_data()
		);
	}


	public function get_install_data(){
		
		// install url
		$install_data['url'] = array(
			'label'       => 'Home URL',
			'value'       => home_url( '/' ),
			'is_viewable' => true
		);

		// Theme Name
		$install_data['theme_name'] = array(
			'label'       => 'Theme Name',
			'value'       => ( empty( $this->config['theme_name'] ) ? '' : $this->config['theme_name'] ),
			'is_viewable' => true
		);

		// Theme Version
		$install_data['theme_version'] = array(
			'label'         => 'Theme Version',
			'value'         => ( empty( $this->config['theme_version'] ) ? '' : $this->config['theme_version'] ),
		);

		// Is Child THeme
		$install_data['is_child_theme'] = array(
			'label'       => 'Child Theme',
			'value'       => ( ! empty( $this->config['is_child'] ) && $this->config['is_child'] ? 'In use' : 'Not in use' ),
			'is_viewable' => true
		);

		// Template
		$install_data['template'] = array(
			'label'       => 'Template',
			'value'       => ( empty( $this->config['template'] ) ? '' : $this->config['template'] ),
			'is_viewable' => false
		);


		return $install_data;

	}


	public function get_system_data() {
		global $wpdb;

		// WP memory limit
		$wp_memory_limit = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
		if ( function_exists( 'memory_get_usage' ) ) {
			$wp_memory_limit = max( $wp_memory_limit, wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) ) );
		}

		$web_server = $_SERVER['SERVER_SOFTWARE'] ? $_SERVER['SERVER_SOFTWARE'] : '';

		$php_version = 'undefined';
		if ( function_exists( 'phpversion' ) ) {
			$php_version = phpversion();
		}

		$wp_min = array(
			'min_php_version'    => '5.5',
			'min_mysql_version'  => '5.5',
			'min_wp_version' => '4.8'
		);

		$db_charset = $wpdb->charset ? $wpdb->charset : 'undefined';

		$response = array(
			'wp_debug_mode'          => array(
				'label' => 'WP Debug Mode Active',
				'value' => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? "true" : "false",
			),
			'wp_cron'                => array(
				'label' => 'WP Cron Active',
				'value' => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) ? "true" : "false",
			),
			'wp_version'             => array(
				'label'         => 'WP Version',
				'value'         => get_bloginfo( 'version' ),
				'min_wp_version'  => $wp_min['min_wp_version'],
			),
			'web_server'             => array(
				'label' => 'Web Server',
				'value' => $web_server,
			),
			'wp_memory_limit'        => array(
				'label' => 'WP Memory Limit',
				'value' => $wp_memory_limit  / 1024 / 1024 / 1024 . ' GB' ,
			), // in bytes
			'php_post_max_size'      => array(
				'label' => 'PHP Post Max Size',
				'value' => wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) / 1024 / 1024 . ' MB', // in bytes
			),
			'php_max_execution_time' => array(
				'label' => 'PHP Max Execution Time',
				'value' => ini_get( 'max_execution_time' ) . ' s',
			),
			'php_version'            => array(
				'label'         => 'PHP Version',
				'value'         => $php_version,
				'min_php_version'  => $wp_min['min_php_version'],
			),
			'mysql_version'          => array(
				'label'         => 'MySQL Version',
				'value'         => $wpdb->db_version(),
				'min_mysql_version'  => $wp_min['min_mysql_version'],
			),
			'wp_locale'              => array(
				'label'       => 'WP Locale',
				'value'       => get_locale(),
			),
			'db_charset'             => array(
				'label'         => 'DB Charset',
				'value'         => $db_charset, //maybe get it from a mysql connection
			)
		);

		return $response;
	}


	public static function instance( $parent ) {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}

		return self::$_instance;
	} // End instance().

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
