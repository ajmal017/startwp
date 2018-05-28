<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Iondigital_Demo_Content{

	public $parent = null;

	private $config = null;

	protected static $_instance = null;

	public function __construct( $parent ) {
   
        $this->parent = $parent;

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'rest_api_init', array($this, 'add_rest_api'));

    }

    public function add_rest_api(){

        register_rest_route( 'iondigital/v1', '/stepnumber', array(
			'methods'             => WP_REST_Server::CREATABLE, // POST
			'callback'            => array( $this, 'stepnumber' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' ),
        ) );

    }

    public function stepnumber($request){

        $params = $request->get_params();
        $rest = array(
            "stepnumber" => $params["value"]
        );
        update_option( 'iondigital_rest', $rest);
        return rest_ensure_response( array(
			'code'    => 'success',
			'message' => ''
		) );
    }

    public function permission_nonce_callback( $request ) {
       
        return wp_verify_nonce( $this->get_nonce( $request ), 'iondigital_rest' );
    }

    private function get_nonce( $request ) {
		$nonce = null;

		// Get the nonce we've been given
        $nonce = $request->get_param( 'iondigital_nonce' );
		if ( ! empty( $nonce ) ) {
            $nonce = wp_unslash( $nonce );
		}
 
		return $nonce;
	}

    public function register_hooks() {
			
        add_action( 'wp_ajax_Iondigital_ajax_import_widgets', array($this, 'Iondigital_ajax_import_widgets') );

        add_action( 'wp_ajax_Iondigital_ajax_import_posts_pages',  array($this, 'Iondigital_ajax_import_posts_pages') );

        add_action( 'wp_ajax_Iondigital_ajax_import_theme_options',  array($this, 'Iondigital_ajax_import_theme_options'));
            

    }

    public function init(){
        $this->config = Iondigital_Admin::get_theme_support();
        $this->register_hooks();

    }
    
    public function Iondigital_ajax_import_posts_pages() {

			
        $theme_support =  $this->config;



        $import_filepath = '';
        if( is_array( $theme_support['importer']) && ! empty($theme_support['importer']['import_filepath'])){

            $import_filepath =  $theme_support['importer']['import_filepath'];

            if( is_array( $import_filepath )){
                $import_filepath = $import_filepath[$_REQUEST['filename']];
            }
        }


        // initialize the step importing
        $stepNumber    = 1;
        $numberOfSteps = 1;
        $attachments = true;
        // get the data sent by the ajax call regarding the current step
        // and total number of steps
        if ( ! empty( $_REQUEST['step_number'] ) ) {
            $stepNumber = $_REQUEST['step_number'];
        }

        if ( ! empty( $_REQUEST['number_of_steps'] ) ) {
            $numberOfSteps = $_REQUEST['number_of_steps'];
        }

        if ( ! empty( $_REQUEST['attachments'] ) ) {
            $attachments = $_REQUEST['attachments'];
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

        require_once( plugin_dir_path( __FILE__ ) . 'import-demo-posts-pages.php' );

        if( isset($_REQUEST['from']) && $_REQUEST['from'] == 'customizer'){
            $xmlResponse = new WP_Ajax_Response($response);
            $xmlResponse->send();
        }else{
            wp_send_json_success( $response );
        }
    }

    
    public function Iondigital_ajax_import_theme_options() {


        $theme_support = $this->config;

        $import_filepath = '';
        if( is_array( $theme_support['importer']) && ! empty($theme_support['importer']['import_filepath'])){

            $import_filepath =  $theme_support['importer']['import_filepath'];

            if( is_array( $import_filepath )){
                $import_filepath = $import_filepath[$_REQUEST['filename']];
            }
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
        
        require_once( plugin_dir_path( __FILE__ ) . 'import-demo-theme-options.php' );
        
        if( isset($_REQUEST['from']) && $_REQUEST['from'] == 'customizer'){
            $xmlResponse = new WP_Ajax_Response($response);
            $xmlResponse->send();
        }else{
            wp_send_json_success( $response );
        }
    }

    public function Iondigital_ajax_import_widgets() {
        
        $theme_support = $this->config;
        
        $import_filepath = '';

        if( is_array( $theme_support['importer']) && ! empty($theme_support['importer']['import_filepath'])){
            
            $import_filepath =  $theme_support['importer']['import_filepath'];

            if( is_array( $import_filepath )){
                $import_filepath = $import_filepath[$_REQUEST['filename']];
            }
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

        require_once( plugin_dir_path( __FILE__ ) . 'import-demo-widgets.php' );

        if( isset($_REQUEST['from']) && $_REQUEST['from'] == 'customizer'){
            $xmlResponse = new WP_Ajax_Response($response);
            $xmlResponse->send();
        }else{
            wp_send_json_success( $response );
        }
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