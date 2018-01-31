<?php
/**
 * Theme activation hook
 *
 * @package bitcoin
 * @since bitcoin 1.0
 */



if ( ! function_exists( 'bitcoin_config_getting_active' ) ) :
	function bitcoin_config_getting_active() {

		/**
		 * ACTIVATION SETTINGS
		 * These settings will be needed when the theme will get active
		 * Careful with the first setup, most of them will go in the clients database and they will be stored there
		 */
		$pixtypes_conf_settings = array(
			'first_activation' => true,
			'metaboxes'        => array(
				'bitcoin_page_background' => array(
					'id'         => 'bitcoin_page_background',
					'title'      => sprintf(
							'%s <a class="tooltip" title="%s.<p>%s <strong>%s</strong>, %s <strong>%s</strong> %s.</p>"></a>',
							esc_html__( 'Hero Area', 'bitcoin' ) ,
							esc_html__( 'Add an image to be used as a Background for the Hero Area on the Page', 'bitcoin' ),
							esc_html__( 'Tip: Uploading', 'bitcoin' ),
							esc_html__( 'multiple images and/or videos', 'bitcoin' ),
							esc_html__( 'will', 'bitcoin' ),
							esc_html__( 'randomly', 'bitcoin' ),
							esc_html__( 'pick one when page is loaded', 'bitcoin' )
					),
					'pages'      => array( 'page' ), // Post type
					'context'    => 'side',
					'priority'   => 'low',
					'show_names' => true, // Show field names on the left
					'fields'     => array(
						array(
							'name' => esc_html__( 'Gallery Image', 'bitcoin' ),
							'id'   => 'blog_image_backgrounds',
							'type' => 'gallery',

						)
					)
				),
				'bitcoin_post_gallery' => array(
					'id' => 'bitcoin_post_gallery',
					'title' => sprintf(
						'%s <a class="tooltip" title="%s"></a>',
						esc_html__('Gallery', 'bitcoin'),
						esc_html__('Add an images to be used as a gallery for the Gallery Post Type Card', 'bitcoin')
					),
					'pages' => array('post'), // Post type
					'context' => 'side',
					'priority' => 'low',
					'show_names' => true, // Show field names on the left
					'fields' => array(
						array(
							'name' => esc_html__('Gallery Image', 'bitcoin'),
							'id' => 'post_gallery_input',
							'type' => 'gallery',

						)
					)
				),
				'bitcoin_post_audio' => array(
					'id' => 'bitcoin_post_audio',
					'title' => sprintf(
						'%s <a class="tooltip" title="%s"></a>',
						esc_html__('Audio', 'bitcoin'),
						esc_html__('Put Url to your audio file to be used in the Audio Post Type Card', 'bitcoin')
					),
					'pages' => array('post'), // Post type
					'context' => 'side',
					'priority' => 'low',
					'show_names' => true, // Show field names on the left
					'fields' => array(
						array(
							'name' => esc_html__('Audio File Source', 'bitcoin'),
							'id' => 'post_audio_file',
							'type' => 'textarea_code'
						),
						array(
							'name' => esc_html__('Audio Type', 'bitcoin'),
							'id' => 'post_audio_type',
							'type' => 'radio_inline',
							'std' => 'wp',
							'options'=> array(
								array(
									'name' => 'WordPress',
									'value' => 'wp'
								),
								array(
									'name' => 'SoundCloud',
									'value' => 'sc'
								)
							)

						)
					)
			),
			'bitcoin_post_video' => array(
				'id' => 'bitcoin_post_video',
				'title' => sprintf(
					'%s <a class="tooltip" title="%s"></a>',
					esc_html__('Video', 'bitcoin'),
					esc_html__('Put Url from youtube or vimeo to be used in the Video Post Type Card', 'bitcoin')
				),
				'pages' => array('post'), // Post type
				'context' => 'side',
				'priority' => 'low',
				'show_names' => true, // Show field names on the left
				'fields' => array(
					array(
						'name' => esc_html__('Video File Source', 'bitcoin'),
						'id' => 'post_video_file',
						'type' => 'textarea_code'
					),
					array(
						'name' => esc_html__('Video Type', 'bitcoin'),
						'id' => 'post_video_type',
						'type' => 'radio_inline',
						'std' => 'yt',
						'options' => array(
							array(
								'name' => 'Youtube',
								'value' => 'yt'
							),
							array(
								'name' => 'Vimeo',
								'value' => 'vi'
							)
						)

					)
				)
			),
			'bitcoin_post_likes' => array(
				'id' => 'bitcoin_post_likes',
				'title' => sprintf(
					'%s <a class="tooltip" title="%s"></a>',
					esc_html__('Likes', 'bitcoin'),
					esc_html__('Amount of Likes made on this post', 'bitcoin')
				),
				'pages' => array('post'), // Post type
				'context' => 'side',
				'priority' => 'low',
				'show_names' => true, // Show field names on the left
				'fields' => array(
					array(
						'name' => esc_html__('Amount of likes', 'bitcoin'),
						'id' => 'post_likes',
						'type' => 'text'
					)
					
				)
			)

			)
		);


		/**
		 * After this line we won't config nothing.
		 * Let's add these settings into WordPress's options db
		 */

		// First, Pixtypes
		$types_options = get_option( 'pixtypes_themes_settings' );
		if ( empty( $types_options ) ) {
			$types_options = array();
		}

		$types_options['bitcoin_pixtypes_theme'] = $pixtypes_conf_settings;
		update_option( 'pixtypes_themes_settings', $types_options );


		// add defaults widgets
		$already_added = get_option( 'bitcoin_default_widgets_added' );
		if ( empty( $already_added ) || $already_added !== '1' ) {

			$current_widgets = get_option( 'sidebars_widgets' );


			// prepare the default widgets
	


			$current_widgets['sidebar-1']         = array(
				'bitcoin_coinmarketcap-4',
				'custom_html-2',
				'wpcom_social_media_icons_widget-3',
				'search-5',
				'recent-posts-3',
				'categories-3',
				'tag_cloud-2'
			);
			$current_widgets['footer-widget-area-social']         = array(
				'wpcom_social_media_icons_widget-2'
			);
			$current_widgets['footer-widget-area-1']     = array(
				'media_image-2',
				'text-6'
			);
			$current_widgets['footer-widget-area-2']     = array(
				'nav_menu-5'
			);
			$current_widgets['footer-widget-area-3']     = array(
				'categories-5'
			);
			$current_widgets['footer-widget-area-4']     = array(
				'text-7',
				'bitcoin_footer_form-3'
			);
			
			update_option( 'sidebars_widgets', $current_widgets );
			update_option( 'bitcoin_default_widgets_added', '1' );

			update_option( 'widget_nav_menu', array('5'=> array('title' => 'INFORMATION','nav_menu'=> 28),'_multiwidget' => 1 ));
		}
		
	}
endif; // end bitcoin_config_getting_active


add_action( 'after_switch_theme', 'bitcoin_config_getting_active' );


// pixtypes requires these things 
if ( ! class_exists( 'wpgrade' ) ) :
	class wpgrade {
		static function shortname() {
			return 'bitcoin';
		}

		/** @var WP_Theme */
		protected static $theme_data = null;

		/**
		 * @return WP_Theme
		 */
		static function themedata() {
			if ( self::$theme_data === null ) {
				if ( is_child_theme() ) {
					$theme_name       = get_template();
					self::$theme_data = wp_get_theme( $theme_name );
				} else {
					self::$theme_data = wp_get_theme();
				}
			}

			return self::$theme_data;
		}

		/**
		 * @return string
		 */
		static function themeversion() {
			return wpgrade::themedata()->Version;
		}
	}

	function wpgrade_callback_geting_active() {
		bitcoin_config_getting_active();
	}

endif;
