<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !class_exists('IodigitalThemeFunction') ){

    class IodigitalThemeFunction {

        protected static $instance; 

        private function __construct(){
            $this->init();
        }

        public function init(){

            add_action('wp_ajax_Iondigital_set_likes_number',  array($this, 'Iondigital_set_likes_number'));
            add_action('wp_ajax_nopriv_Iondigital_set_likes_number',  array($this,'Iondigital_set_likes_number'));
            
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );

        }

        /**
		 * Register the JavaScript.
		 *
		 * @since    1.0.0
		 */
		function enqueue_scripts() {
	
            wp_enqueue_script( 'enhancement', plugin_dir_url( __FILE__ ) . 'js/enhancement.js' , array('jquery') , true, true );

        }

        public static function share(){

            global $wp_filesystem;
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            WP_Filesystem();

            $config = get_theme_support( 'iondigital_kit' );
            $theme = '';
            $servises = array();

            if( is_array($config) ){
                $config = reset ( $config );
                $theme =  $config['theme_config']['shortname'];
                $servises = $config['share_servises'];
            }

        ?>

        <div class="entry-share">
            <ul class="entry-share__links">
                <?php
                    foreach( $servises as $name => $path){
                        echo   '<li> <a href="#" data-share="' . $name .'">
                                        <i class="' . $theme .'__icon ' . $theme .'__icon--opacity5">';

                        $wp_filesystem->get_contents(locate_template( $path, true, false ));
                                        
                        echo '</i></a></li>';
                    }
                ?>
            </ul>
        </div>

        <?php
        }


        function Iondigital_set_likes_number(){

            $config = get_theme_support( 'iondigital_kit' );
            $theme = 'bitstarter';
            $servises = array();

            if( is_array($config) ){
                $config = reset ( $config );
                $theme =  $config['theme_config']['shortname'];

            }


			$post_id = isset($_POST['ID'])?$_POST['ID']:null;

				if(!$post_id)
                    wp_send_json_error();
                    
                $cookies_slag = $theme . '_post_' . $post_id . '_liked';
            

				$data = array('cookie' => $_COOKIE[ $cookies_slag ]);


				if(empty($_COOKIE[ $cookies_slag ])) {
					$liked = get_post_meta($post_id, 'post_likes', true);
					$liked = intval($liked) + 1;
			
					update_post_meta($post_id, 'post_likes', $liked);
					
			
					setcookie( $cookies_slag , true, (time() + 24 * 3600 * 1000), COOKIEPATH, COOKIE_DOMAIN );
                    $data['liked'] = true;
                    
				} else {
					$liked = get_post_meta($post_id, 'post_likes', true);
					$liked = intval($liked) - 1;
					$liked = $liked < 0?0:$liked;
			
					update_post_meta($post_id, 'post_likes', $liked);
			
					setcookie( $cookies_slag , false, (time() + 24 * 3600 * 1000), COOKIEPATH, COOKIE_DOMAIN );
					$data['liked'] = false;
				}
			
				wp_send_json_success( $data );

		}



        public static function likes( ) {

            $post_id = get_the_ID();
	
            global $wp_filesystem;
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            WP_Filesystem();

        
            $config = get_theme_support( 'iondigital_kit' );
            $theme = '';
            $servises = array();

            if( is_array($config) ){
                $config = reset ( $config );
                $icon =  $config['likes']['icon'];
               
            }

            $html = sprintf(
            
                '<span data-post-id="%3$s" class="likes-count %4$s"><i class="bitstarter__icon bitstarter__icon--opacity2 ">%1$s</i>
                <span class="likes-count__number">%2$s</span></span>',
                $wp_filesystem->get_contents(locate_template( $icon )),
                bitstarter_get_likes_number(),
                $post_id ,
                isset($_COOKIE['bitstarter_post_' . $post_id . '_liked'])?'likes-count--active':''
            );
        
            echo  $html;
        }

        public static function instance() {

            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self;
            }

            return self::$instance;
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
}