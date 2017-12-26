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
					'show_names' => false, // Show field names on the left
					'show_on'    => array('key'   => 'id',
											'value' => get_option('page_for_posts')),
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



				// '_page_frontpage_categories' => array(
				// 	'id'         => '_page_frontpage_listing_categories',
				// 	'title'      => '&#x1f535; ' . esc_html__( 'Front Page &raquo; Highlighted Categories', 'bitcoin' ) . ' <a class="tooltip" title="' . esc_html__( '<p>You can select which categories to highlight, by adding their <em>slugs</em>, separated by a comma: <em>food, hotels, restaurants</em></p><p> You can change their <em>shown name</em> (in case it is too long) with this pattern: <em>slug (My Custom Name)</em></p>', 'bitcoin' ) . '"></a>',
				// 	'pages'      => array( 'page' ), // Post type
				// 	'priority'   => 'high',
				// 	'show_names' => false, // Show field names on the left
				// 	'show_on'    => array(
				// 		'key'   => 'page-template',
				// 		'value' => array( 'page-templates/front_page.php' ),
				// 	),
				// 	'fields'     => array(
				// 		array(
				// 			'name' => esc_html__( 'Frontend Categories', 'bitcoin' ),
				// 			'id'   => 'frontpage_listing_categories',
				// 			'type' => 'text',
				// 		),
				// 	)
				// ),

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
			$current_widgets['listing_content']         = array(
				'listing_actions-2',
				'listing_content-2',
				'listing_comments-2'
			);
			$current_widgets['listing_sidebar']         = array(
				'listing_sidebar_categories-2',
				'listing_sidebar_hours-2',
				'listing_sidebar_gallery-2',
			);
			$current_widgets['front_page_sections']     = array(
				'front_page_listing_categories-2',
				'front_page_listing_cards-2',
				'front_page_spotlights-2',
				'front_page_recent_posts-2',
			);

			update_option( 'sidebars_widgets', $current_widgets );
			update_option( 'bitcoin_default_widgets_added', '1' );

			update_option( 'widget_listing_content', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_listing_actions', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_listing_comments', array( '2' => array(), '_multiwidget' => 1 ) );

			update_option( 'widget_listing_sidebar_categories', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_listing_sidebar_hours', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_listing_sidebar_gallery', array( '2' => array(), '_multiwidget' => 1 ) );

			update_option( 'widget_front_page_listing_categories', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_front_page_listing_cards', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_front_page_spotlights', array( '2' => array(), '_multiwidget' => 1 ) );
			update_option( 'widget_front_page_recent_posts', array( '2' => array(), '_multiwidget' => 1 ) );
		}
	}
endif; // end bitcoin_config_getting_active

add_action( 'after_switch_theme', 'bitcoin_config_getting_active' );


// pixtypes requires these things below for a pixelgrade theme
// for the moment we'll shim them until we update pixtypes
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
