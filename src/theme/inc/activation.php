<?php
/**
 * Theme activation hook
 *
 * @package listable
 * @since listable 1.0
 */

if ( ! function_exists( 'listable_config_getting_active' ) ) :
	function listable_config_getting_active() {


		/**
		 * ACTIVATION SETTINGS
		 * These settings will be needed when the theme will get active
		 * Careful with the first setup, most of them will go in the clients database and they will be stored there
		 */
		$pixtypes_conf_settings = array(
			'first_activation' => true,
			'metaboxes'        => array(
				'_page_background' => array(
					'id'         => '_page_background',
					'title'      => sprintf(
							'%s <a class="tooltip" title="%s.<p>%s <strong>%s</strong>, %s <strong>%s</strong> %s.</p>"></a>',
							esc_html__( 'Hero Area', 'listable' ) ,
							esc_html__( 'Add an image or a video to be used as a Background for the Hero Area on the Front Page', 'listable' ),
							esc_html__( 'Tip: Uploading', 'listable' ),
							esc_html__( 'multiple images and/or videos', 'listable' ),
							esc_html__( 'will', 'listable' ),
							esc_html__( 'randomly', 'listable' ),
							esc_html__( 'pick one when page is loaded', 'listable' )
					),
					'pages'      => array( 'page' ), // Post type
					'context'    => 'side',
					'priority'   => 'low',
					'show_names' => false, // Show field names on the left
					'show_on'    => array(
						'0' => array('key'   => 'page-template',
							 'value' => array( 'page-templates/front_page.php', '' ),
						)
					),
					'fields'     => array(
						array(
							'name' => esc_html__( 'Gallery Image', 'listable' ),
							'id'   => 'image_backgrounds',
							'type' => 'gallery',

						),
						array(
							'name' => esc_html__( 'Gallery Image', 'listable' ),
							'id'   => 'image_backgrounds1',
							'type' => 'gallery',

						),
						array(
							'name' => esc_html__( 'Playlist', 'listable' ),
							'id'   => 'videos_backgrounds',
							'type' => 'playlist',
						),
						array(
						'name'    => 'Test Color Picker',
						'id'      => 'wiki_test_colorpicker',
						'type'    => 'colorpicker',
						'default' => '#ffffff',
						'std' => '#ffffff')
					)
				),
				'_page_frontpage_search_fields' => array(
					'id'         => '_page_frontpage_search_fields',
					'title'      => '&#x1f535; ' . esc_html__( 'Front Page &raquo; Search Fields', 'listable' ) . ' <a class="tooltip" title="' . esc_html__( '<p>Choose what fields to show in the hero area of this front page.</p>', 'listable' ) . '"></a>',
					'pages'      => array( 'page' ), // Post type
					'priority'   => 'high',
					'show_names' => true, // Show field names on the left
					'show_on'    => array(
						'key'   => 'page-template',
						'value' => array( 'page-templates/front_page.php' ),
					),
					'fields'     => array(
						array(
							'name' => esc_html__( 'Search Fields', 'listable' ),
							'desc' => '',
							'id'   => 'frontpage_search_fields',
							'type' => 'multicheck',
							'options' => array(
								'keywords' => esc_html__( 'Keywords', 'listable' ),
								'location' => esc_html__( 'Location', 'listable' ),
								'categories' => esc_html__( 'Categories', 'listable' ),
							),
							'std' => array('keywords'),
						),
					)
				),
				// '_page_frontpage_categories' => array(
				// 	'id'         => '_page_frontpage_listing_categories',
				// 	'title'      => '&#x1f535; ' . esc_html__( 'Front Page &raquo; Highlighted Categories', 'listable' ) . ' <a class="tooltip" title="' . esc_html__( '<p>You can select which categories to highlight, by adding their <em>slugs</em>, separated by a comma: <em>food, hotels, restaurants</em></p><p> You can change their <em>shown name</em> (in case it is too long) with this pattern: <em>slug (My Custom Name)</em></p>', 'listable' ) . '"></a>',
				// 	'pages'      => array( 'page' ), // Post type
				// 	'priority'   => 'high',
				// 	'show_names' => false, // Show field names on the left
				// 	'show_on'    => array(
				// 		'key'   => 'page-template',
				// 		'value' => array( 'page-templates/front_page.php' ),
				// 	),
				// 	'fields'     => array(
				// 		array(
				// 			'name' => esc_html__( 'Frontend Categories', 'listable' ),
				// 			'id'   => 'frontpage_listing_categories',
				// 			'type' => 'text',
				// 		),
				// 	)
				// ),
				'_page_blogpage' => array(
					'id'         => '_page_blogpage',
					'title'      => esc_html__( 'Posts Page Options', 'listable' ),
					'pages'      => array( 'page' ), // Post type
					'priority'   => 'high',
					'show_names' => true, // Show field names on the left
					'show_on'    => array(
						'key'   => 'id',
						'value' => get_option( 'page_for_posts' ),
					),
					'fields'    =>  array(
						array(
							'name' => esc_html__( 'View Posts Type', 'listable' ) . ' <a class="tooltip" title="' . esc_html__( '<p>Choose how the blog posts visualy displays in the Blog Posts page.</p>', 'listable' ) . '"></a>',
							'desc' => '',
							'id'   => 'blogpage_type',
							'type' => 'radio_inline',
							'options' => array(
								array(
									'id' => esc_html__( 'radio-list', 'listable' ),
									'value' =>esc_html__( 'list', 'listable' ),
									'name' => esc_html__( 'List', 'listable' )
								),
								array(
									'id' => esc_html__( 'radio-tile', 'listable' ),
									'value' =>esc_html__( 'tile', 'listable' ),
									'name' => esc_html__( 'Tile', 'listable' )
								)
							),
							'std' => 'tile'),
						array(
							'name' => esc_html__( 'Blog Categories', 'listable' ) . ' <a class="tooltip" title="' . esc_html__( '<p>Write what post categoty to show in the Blog Posts page.</p>', 'listable' ) . '"></a>',
							'id'   => 'blogpage_categories',
							'type' => 'text'),
					)
				)
			),
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

		$types_options['listable_pixtypes_theme'] = $pixtypes_conf_settings;
		update_option( 'pixtypes_themes_settings', $types_options );


		// add defaults widgets
		$already_added = get_option( 'listable_default_widgets_added' );
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
			update_option( 'listable_default_widgets_added', '1' );

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
endif; // end listable_config_getting_active
listable_config_getting_active();
add_action( 'after_switch_theme', 'listable_config_getting_active' );


// pixtypes requires these things below for a pixelgrade theme
// for the moment we'll shim them until we update pixtypes
if ( ! class_exists( 'wpgrade' ) ) :
	class wpgrade {
		static function shortname() {
			return 'listable';
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
		listable_config_getting_active();
	}

endif;