<?php
/**
 * Bitcoin Options Config
 *
 * @package Bitcoin
 * @since Bitcoin 1.0
 */

/**
 * Hook into the Customify's fields and settings
 *
 * @param $options array - Contains the plugin's options array right before they are used, so edit with care
 *
 * @return mixed
 */ 
if ( ! function_exists( 'bitcoin_add_customify_options' ) ) :
	function bitcoin_add_customify_options( $options ) {

		// Recommended Fonts List
		// Headings
		$recommended_headings_fonts = array(
			'Noto Sans',
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere',
		);

		// Body
		$recommended_body_fonts = array(
			'Noto Sans',
			'Source Sans Pro',
			'Lato',
			'Open Sans',
			'PT Sans',
			'Cabin',
			'Gentium Book Basic',
			'PT Serif',
			'Droid Serif'
		);

		$options['opt-name'] = 'bitcoin_options';

		// keep this empty now
		$options['sections'] = array();

		$options['sections']['style_presets'] = array(
			'title'   => '&#x1f3ad; ' . esc_html__( 'Style Presets', 'bitcoin' ),
			'options' => array(
				'theme_style' => array(
					'type'         => 'preset',
					'label'        => __( 'Select a style:', 'bitcoin' ),
					'desc'         => __( 'Conveniently change the design of your site with built-in style presets. Easy as pie.', 'patch' ),
					'default'      => 'bitcoin',
					'choices_type' => 'awesome',
					'choices'      => array(
						'bitcoin' => array(
							'label'   => __( 'Bitcoin', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#ff4d55',
								'background-label' => '#f13d46',
								'font-main'        => 'Noto Sans',
								'font-alt'         => 'Open Sans',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#ffffff',
								'site_title_color'            => '#484848',
								'search_color'                => '#484848',
								'nav_link_color'              => '#919191',
								'nav_active_color'            => '#FF4D55',
								'nav_button_color'            => '#EBEBEB',

								// Main Content
								'content_background'          => '#ffffff',
								'page_background'             => '#F9F9F9',
								'page_titles_color'           => '#484848',
								'page_subtitles_color'        => '#919191',
								'text_color'                  => '#484848',
								'buttons_color'               => '#FF4D55',

								// Cards
								'cards_background'            => '#ffffff',
								'cards_radius'                => '8',
								'thumbs_radius'               => '4',
								'cards_title_color'           => '#FF4D55',
								'cards_text_color'            => '#ababab',
								'cards_icon_color'            => '#FF4D5A',
								'cards_icon_border_color'     => '#FF4D5A',
								'cards_icon_background_color' => '#FFFFFF',
								'pin_background_color'        => '#FFFFFF',
								'pin_icon_color'              => '#FF4D5A',

								// Pre Footer
								'prefooter_background'        => '#2F2929',
								'prefooter_text_color'        => '#FFFFFF',

								// Footer
								'footer_background'           => '#261E1E',
								'footer_text_color'           => '#ADADB2',
								'footer_credits_color'        => '#706C6C',

								// Other Colors
								'accent_color'                => '#FF4D58',
								'fields_color'                => '#919191',
								'micro_color'                 => '#ABABAB',

								// FONTS
								'site_title_font'             => array(
									'font_family'       => 'Noto Sans',
									'selected_variants' => '400'
								),
								'site_title_font_size'        => '24',
								'site_title_text_transform'   => 'None',
								'site_title_letter-spacing'   => '0',

								'navigation_font'           => array(
									'font_family'       => 'Noto Sans',
									'selected_variants' => '400'
								),
								'navigation_font_size'      => '14.95',
								'navigation_text_transform' => 'Capitalize',
								'navigation_letter-spacing' => '0',

								'body_font'           => array(
									'font_family'       => 'Noto Sans',
									'selected_variants' => 'regular'
								),
								'page_titles_font'    => array( 'font_family'       => 'Noto Sans',
								                                'selected_variants' => '700'
								),
								'page_subtitles_font' => array( 'font_family'       => 'Noto Sans',
								                                'selected_variants' => '400'
								),
								'meta_font'           => array( 'font_family'       => 'Noto Sans',
								                                'selected_variants' => '400'
								),
								'card_font'           => array( 'font_family'       => 'Noto Sans',
								                                'selected_variants' => '400'
								),

								'card_title_font'           => array(
									'font_family'       => 'Noto Sans',
									'selected_variants' => '700'
								),
								'card_title_font_size'      => '24',
								'card_title_text_transform' => 'None',
								'card_title_letter-spacing' => '0',

							)
						),


						// Royal Preset
						'royal'    => array(
							'label'   => __( 'Royal', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#10324e',
								'background-label' => '#bdae6a',
								'font-main'        => 'Playfair Display',
								'font-alt'         => 'Source Sans Pro',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#ffffff',
								'site_title_color'            => '#10324e',
								'search_color'                => '#484848',
								'nav_link_color'              => '#86898c',
								'nav_active_color'            => '#10324e',
								'nav_button_color'            => '#ebebeb',

								// Main Content
								'content_background'          => '#ffffff',
								'page_background'             => '#f9f9f9',
								'page_titles_color'           => '#10324e',
								'page_subtitles_color'        => '#86898c',
								'text_color'                  => '#10324e',
								'buttons_color'               => '#bdae6a',

								// Cards
								'cards_background'            => '#ffffff',
								'cards_radius'                => '4',
								'thumbs_radius'               => '16',
								'cards_title_color'           => '#bdae6a',
								'cards_text_color'            => '#86898c',
								'cards_icon_color'            => '#ffffff',
								'cards_icon_border_color'     => '#bdae6a',
								'cards_icon_background_color' => '#bdae6a',
								'pin_background_color'        => '#ffffff',
								'pin_icon_color'              => '#bdae6a',

								// Pre Footer
								'prefooter_background'        => '#1c486d',
								'prefooter_text_color'        => '#ffffff',

								// Footer
								'footer_background'           => '#10324e',
								'footer_text_color'           => '#d9dae0',
								'footer_credits_color'        => '#b0b5b7',

								// Other Colors
								'accent_color'                => '#bdae6a',
								'fields_color'                => '#919191',
								'micro_color'                 => '#ababab',

								// FONTS
								'site_title_font'             => array( 'font_family'       => 'Playfair Display',
								                                        'selected_variants' => '700'
								),
								'site_title_font_size'        => '24',
								'site_title_text_transform'   => 'None',
								'site_title_letter-spacing'   => '0',

								'navigation_font'           => array(
									'font_family'       => 'Source Sans Pro',
									'selected_variants' => '200'
								),
								'navigation_font_size'      => '15',
								'navigation_text_transform' => 'Capitalize',
								'navigation_letter-spacing' => '0',

								'body_font' => 'Source Sans Pro',

								'page_titles_font'    => array(
									'font_family'       => 'Playfair Display',
									'selected_variants' => '700'
								),
								'page_subtitles_font' => array( 'font_family'       => 'Source Sans Pro',
								                                'selected_variants' => '300'
								),

								'card_title_font'           => array(
									'font_family'       => 'Playfair Display',
									'selected_variants' => 'regular'
								),
								'card_title_font_size'      => '24',
								'card_title_text_transform' => 'None',
								'card_title_letter-spacing' => '0',

								'card_font' => array( 'font_family'       => 'Source Sans Pro',
								                      'selected_variants' => '300'
								),

								'meta_font' => array( 'font_family'       => 'Source Sans Pro',
								                      'selected_variants' => '300'
								),

							)
						),
						//Silkberry Preset

						'silkberry' => array(
							'label'   => __( 'Silkberry', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#3D3235',
								'background-label' => '#FCC5BE',
								'font-main'        => 'Yeseva One',
								'font-alt'         => 'Source Sans Pro',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#161314',
								'site_title_color'            => '#fcc5be',
								'search_color'                => '#484848',
								'nav_link_color'              => '#b2b2b2',
								'nav_active_color'            => '#fcc5be',
								'nav_button_color'            => '#9e9e9e',

								// Main Content
								'content_background'          => '#3d3235',
								'page_background'             => '#2b2427',
								'page_titles_color'           => '#fcc5be',
								'page_subtitles_color'        => '#bf8989',
								'text_color'                  => '#f9f9f9',
								'buttons_color'               => '#fcc5be',

								// Cards
								'cards_background'            => '#312a2d',
								'cards_radius'                => '24',
								'thumbs_radius'               => '22',
								'cards_title_color'           => '#fcc5be',
								'cards_text_color'            => '#ffffff',
								'cards_icon_color'            => '#3c3133',
								'cards_icon_border_color'     => '#fcc5be',
								'cards_icon_background_color' => '#fcc5be',
								'pin_background_color'        => '#ffffff',
								'pin_icon_color'              => '#ffadad',

								// Pre Footer
								'prefooter_background'        => '#2F2929',
								'prefooter_text_color'        => '#ffffff',

								// Footer
								'footer_background'           => '#261E1E',
								'footer_text_color'           => '#868889',
								'footer_credits_color'        => '#a3a09b',

								// Other Colors
								'accent_color'                => '#fcc5be',
								'fields_color'                => '#6d6d6d',
								'micro_color'                 => '#dddddd',

								// FONTS
								'site_title_font'             => array( 'font_family'       => 'Libre Baskerville',
								                                        'selected_variants' => '700'
								),
								'navigation_font'             => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'navigation_font_size'        => '14.95',
								'navigation_text_transform'   => 'None',
								'navigation_letter-spacing'   => '0',
								'body_font'                   => 'Raleway',

								'page_titles_font'    => array(
									'font_family'       => 'Libre Baskerville',
									'selected_variants' => 'regular'
								),
								'page_subtitles_font' => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'meta_font'           => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'card_font'           => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),

								'card_title_font'           => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'card_title_font_size'      => '28',
								'card_title_text_transform' => 'None',
								'card_title_letter-spacing' => '0',

							)
						),

						//Orangina Preset
						'Orangina'  => array(
							'label'   => __( 'Orangina', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#01a7ba',
								'background-label' => '#e58500',
								'font-main'        => 'Delius',
								'font-alt'         => 'Lato',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#01a7ba',
								'site_title_color'            => '#ffffff',
								'search_color'                => '#ffffff',
								'nav_link_color'              => '#ffffff',
								'nav_active_color'            => '#3d3d3d',
								'nav_button_color'            => '#3d3d3d',

								// Main Content
								'content_background'          => '#ffffff',
								'page_background'             => '#ffffff',
								'page_titles_color'           => '#01a7ba',
								'page_subtitles_color'        => '#86898c',
								'text_color'                  => '#3d3d3d',
								'buttons_color'               => '#f79f07',

								// Cards
								'cards_background'            => '#ffffff',
								'cards_radius'                => '20',
								'thumbs_radius'               => '6',
								'cards_title_color'           => '#f79f07',
								'cards_text_color'            => '#9b9b9b',
								'cards_icon_color'            => '#ffffff',
								'cards_icon_border_color'     => '#01a7ba',
								'cards_icon_background_color' => '#01a7ba',
								'pin_background_color'        => '#ffffff',
								'pin_icon_color'              => '#01a7ba',

								// Pre Footer
								'prefooter_background'        => '#01a7ba',
								'prefooter_text_color'        => '#ffffff',

								// Footer
								'footer_background'           => '#f79f07',
								'footer_text_color'           => '#ffffff',
								'footer_credits_color'        => '#000000',

								// Other Colors
								'accent_color'                => '#f79f07',
								'fields_color'                => '#848484',
								'micro_color'                 => '#000000',

								// FONTS
								'site_title_font'             => array( 'font_family'       => 'Delius',
								                                        'selected_variants' => '700'
								),
								'navigation_font'             => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'navigation_font_size'        => '14.95',
								'navigation_text_transform'   => 'None',
								'navigation_letter-spacing'   => '0',
								'body_font'                   => array(
									'font_family'       => 'Raleway',
									'selected_variants' => 'regular'
								),
								'page_titles_font'            => array(
									'font_family'       => 'Delius',
									'selected_variants' => 'regular'
								),
								'meta_font'                   => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'page_subtitles_font'         => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'card_font'                   => array(
									'font_family'       => 'Raleway',
									'selected_variants' => 'regular'
								),

								'card_title_font'           => array(
									'font_family'       => 'Delius',
									'selected_variants' => 'regular'
								),
								'card_title_font_size'      => '28',
								'card_title_text_transform' => 'None',
								'card_title_letter-spacing' => '0',


							)
						),
						//Jolly Preset

						'Jolly' => array(
							'label'   => __( 'Jolly', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#ea6f4d',
								'background-label' => '#ea481c',
								'font-main'        => 'Flamenco',
								'font-alt'         => 'Lato',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#ea6f4d',
								'site_title_color'            => '#ffffff',
								'search_color'                => '#474747',
								'nav_link_color'              => '#ffffff',
								'nav_active_color'            => '#9b3030',
								'nav_button_color'            => '#000000',

								// Main Content
								'content_background'          => '#ffffff',
								'page_background'             => '#ffffff',
								'page_titles_color'           => '#ea6f4d',
								'page_subtitles_color'        => '#86898c',
								'text_color'                  => '#494949',
								'buttons_color'               => '#ea6f4d',

								// Cards
								'cards_background'            => '#ffffff',
								'cards_radius'                => '6',
								'thumbs_radius'               => '6',
								'cards_title_color'           => '#ea481c',
								'cards_text_color'            => '#666666',
								'cards_icon_color'            => '#ffffff',
								'cards_icon_border_color'     => '#ea6f4d',
								'cards_icon_background_color' => '#ea6f4d',
								'pin_background_color'        => '#ffffff',
								'pin_icon_color'              => '#ea481c',

								// Pre Footer
								'prefooter_background'        => '#ea481c',
								'prefooter_text_color'        => '#ffffff',

								// Footer
								'footer_background'           => '#ea6f4d',
								'footer_text_color'           => '#ffffff',
								'footer_credits_color'        => '#ffbaba',

								// Other Colors
								'accent_color'                => '#ea481c',
								'fields_color'                => '#898989',
								'micro_color'                 => '#777777',

								// FONTS
								'site_title_font'             => array( 'font_family'       => 'Flamenco',
								                                        'selected_variants' => '700'
								),
								'navigation_font'             => array(
									'font_family'       => 'Lato',
									'selected_variants' => '300'
								),
								'navigation_font_size'        => '14.95',
								'navigation_text_transform'   => 'None',
								'navigation_letter-spacing'   => '0',
								'body_font'                   => array(
									'font_family'       => 'Lato',
									'selected_variants' => '300'
								),

								'page_titles_font' => array(
									'font_family'       => 'Flamenco',
									'selected_variants' => 'regular'
								),

								'page_subtitles_font' => array(
									'font_family'       => 'Lato',
									'selected_variants' => '300'
								),

								'meta_font' => array(
									'font_family'       => 'Lato',
									'selected_variants' => '300'
								),
								'card_font' => array(
									'font_family'       => 'Lato',
									'selected_variants' => '300'
								),

								'card_title_font'           => array(
									'font_family'       => 'Flamenco',
									'selected_variants' => 'regular'
								),
								'card_title_font_size'      => '28',
								'card_title_text_transform' => 'None',
								'card_title_letter-spacing' => '0',


							)
						),
						//Navy Preset

						'Navy' => array(
							'label'   => __( 'Navy', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#001f63',
								'background-label' => '#3a3a3a',
								'font-main'        => 'Vidaloka',
								'font-alt'         => 'Lato',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#ffffff',
								'site_title_color'            => '#001f63',
								'search_color'                => '#3a3a3a',
								'nav_link_color'              => '#001f63',
								'nav_active_color'            => '#4a5463',
								'nav_button_color'            => '#000000',

								// Main Content
								'content_background'          => '#ffffff',
								'page_background'             => '#ffffff',
								'page_titles_color'           => '#161400',
								'page_subtitles_color'        => '#86898c',
								'text_color'                  => '#3d3d3d',
								'buttons_color'               => '#000144',

								// Cards
								'cards_background'            => '#ffffff',
								'cards_radius'                => '6',
								'thumbs_radius'               => '6',
								'cards_title_color'           => '#001f63',
								'cards_text_color'            => '#666666',
								'cards_icon_color'            => '#001f63',
								'cards_icon_border_color'     => '#001f63',
								'cards_icon_background_color' => '#ffffff',
								'pin_background_color'        => '#ffffff',
								'pin_icon_color'              => '#001f63',

								// Pre Footer
								'prefooter_background'        => '#002847',
								'prefooter_text_color'        => '#ffffff',

								// Footer
								'footer_background'           => '#00133f',
								'footer_text_color'           => '#ffffff',
								'footer_credits_color'        => '#c1c1c1',

								// Other Colors
								'accent_color'                => '#001f63',
								'fields_color'                => '#848484',
								'micro_color'                 => '#000000',

								// FONTS
								'site_title_font'             => 'Vidaloka',
								'navigation_font'             => array(
									'font_family'       => 'Lato',
									'selected_variants' => 'regular'
								),
								'body_font'                   => 'Lato',
								'page_titles_font'            => 'Vidaloka',
								'meta_font'                   => array(
									'font_family'       => 'Lato',
									'selected_variants' => 'regular'
								),
								'page_subtitles_font'         => 'Lato',
								'meta_font'                   => array(
									'font_family'       => 'Lato',
									'selected_variants' => 'regular'

								),
								'card_title_font'             => 'Vidaloka',

							)
						),
						//Grass Preset

						'Grass' => array(
							'label'   => __( 'Grass', 'bitcoin' ),
							'preview' => array(
								'color-text'       => '#ffffff',
								'background-card'  => '#64a97b',
								'background-label' => '#213847',
								'font-main'        => 'Copse',
								'font-alt'         => 'Raleway',
							),
							'options' => array(

								// COLORS
								// Site Header
								'header_transparent'          => true,
								'header_background_color'     => '#ffffff',
								'site_title_color'            => '#3a5465',
								'search_color'                => '#231e1c',
								'nav_link_color'              => '#090c11',
								'nav_active_color'            => '#64a97b',
								'nav_button_color'            => '#3d3931',

								// Main Content
								'content_background'          => '#ffffff',
								'page_background'             => '#ffffff',
								'page_titles_color'           => '#2f4859',
								'page_subtitles_color'        => '#86898c',
								'text_color'                  => '#3d3d3d',
								'buttons_color'               => '#64a97b',

								// Cards
								'cards_background'            => '#ffffff',
								'cards_radius'                => '36',
								'thumbs_radius'               => '36',
								'cards_title_color'           => '#3a5465',
								'cards_text_color'            => '#474747',
								'cards_icon_color'            => '#ffffff',
								'cards_icon_border_color'     => '#64a97b',
								'cards_icon_background_color' => '#64a97b',
								'pin_background_color'        => '#ffffff',
								'pin_icon_color'              => '#64a97b',

								// Pre Footer
								'prefooter_background'        => '#64a97b',
								'prefooter_text_color'        => '#ffffff',

								// Footer
								'footer_background'           => '#3a5465',
								'footer_text_color'           => '#ffffff',
								'footer_credits_color'        => '#0c0c0c',

								// Other Colors
								'accent_color'                => '#001f63',
								'fields_color'                => '#848484',
								'micro_color'                 => '#000000',

								// FONTS
								'site_title_font'             => 'Copse',

								'navigation_font'           => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'navigation_font_size'      => '14.95',
								'navigation_text_transform' => 'None',
								'navigation_letter-spacing' => '0',

								'body_font'           => 'Raleway',
								'page_titles_font'    => array(
									'font_family'       => 'Copse',
									'selected_variants' => '700'
								),
								'meta_font'           => array(
									'font_family'       => 'Raleway',
									'selected_variants' => 'regular'
								),
								'page_subtitles_font' => 'Raleway',
								'card_font'           => array(
									'font_family'       => 'Raleway',
									'selected_variants' => '300'
								),
								'card_title_font'     => 'Montserrat',

							)
						)


					)
				),
			)
		);

		$options['panels']['theme_options'] = array(
			'title'    => '&#x1f506; ' . esc_html__( 'Theme Options', 'bitcoin' ),
			'sections' => array(
				'general'     => array(
					'title'   => esc_html__( 'General', 'bitcoin' ),
					'options' => array(
						'footer_copyright' => array(
							'type'              => 'textarea',
							'label'             => esc_html__( 'Footer Copyright Text', 'bitcoin' ),
							//'desc' => esc_html__( 'The copyright text which should appear in footer.', 'bitcoin' ),
							'default'           => esc_html__( 'Copyright &copy; 2017 Company Inc.   &bull;   Address  &bull;   Tel: 42-898-4363', 'bitcoin' ),
							'sanitize_callback' => 'wp_kses_post',
							'live'              => array( '.site-info .site-copyright-area' )
						),
					)
				),
				'map_options' => array(
					'title'   => esc_html__( 'Map Options', 'bitcoin' ),
					'options' => array(
						'mapbox_token'        => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Mapbox Integration (optional)', 'bitcoin' ),
							'default' => '',
							// 'desc'      => __( 'Bitcoin uses the Mapbox API for different reasons. <a href="https://www.mapbox.com/help/create-api-access-token/">Get an API token</a> for best performances.', 'bitcoin' ),
							'desc'    => sprintf(
								'<p>%s <a href="https://www.mapbox.com/" target="_blank">%s</a> %s.</p><p><a href="https://www.mapbox.com/help/create-api-access-token/" target="_blank">%s</a> %s.</p>',
								esc_html__( 'We are offering integration with the', 'bitcoin' ),
								esc_html__( 'Mapbox', 'bitcoin' ),
								esc_html__( 'service, so you can have better looking and highly performance maps', 'bitcoin' ),
								esc_html__( 'Get a FREE token', 'bitcoin' ),
								esc_html__( 'and paste it below. If there is nothing added, we will fallback to the Google Maps service', 'bitcoin' )
							)
						),
						'mapbox_style'        => array(
							'type'    => 'radio_image',
							'label'   => esc_html__( 'Mapbox Style', 'bitcoin' ),
							'default' => 'mapbox.streets-basic',
							'choices' => array(
								'mapbox.streets-basic' => get_template_directory_uri() . '/assets/img/streets-basic.png',
								'mapbox.streets'       => get_template_directory_uri() . '/assets/img/streets.png',
								'mapbox.outdoors'      => get_template_directory_uri() . '/assets/img/outdoors.png',
								'mapbox.light'         => get_template_directory_uri() . '/assets/img/light.png',
								'mapbox.emerald'       => get_template_directory_uri() . '/assets/img/emerald.png',
								'mapbox.satellite'     => get_template_directory_uri() . '/assets/img/satellite.png',
								'mapbox.pencil'        => get_template_directory_uri() . '/assets/img/pencil.png',
								'mapbox.pirates'       => get_template_directory_uri() . '/assets/img/pirates.png'
							),
							'desc'    => esc_html__( 'Custom styles works only if you have set a valid Mapbox API token in the field above.', 'bitcoin' ),
						),
						'google_maps_api_key' => array(
							'type'    => 'text',
							'label'   => esc_html__( '(Deprecated) Google Maps API key', 'bitcoin' ),
							'default' => '',
							'desc'    => sprintf(
								'<p>%s </p> <a href="%3s" target="_blank">%s</a>',
								esc_html__( 'WP Job Manager has it\'s own field for Google Maps API key and since version 1.8.9 we recommend you to use their option.', 'bitcoin' ),
								get_admin_url( null,'edit.php?post_type=job_listing&page=job-manager-settings#setting-job_manager_google_maps_api_key'),
								esc_html__( 'Go there', 'bitcoin' )
							),
							'input_attrs' => array(
								'min'          => 20,
								'max'          => 100,
								'disabled'         => 1,
							),
						)

					)
				),
				'custom_js'   => array(
					'title'   => esc_html__( 'Custom JavaScript', 'bitcoin' ),
					'options' => array(
						'custom_js'        => array(
							'type'        => 'ace_editor',
							'label'       => esc_html__( 'Header', 'bitcoin' ),
							'desc'        => esc_html__( 'Easily add Custom Javascript code. This code will be loaded in the <head> section.', 'bitcoin' ),
							'editor_type' => 'javascript',
						),
						'custom_js_footer' => array(
							'type'        => 'ace_editor',
							'label'       => esc_html__( 'Footer', 'bitcoin' ),
							'desc'        => esc_html__( 'You can paste here your Google Analytics tracking code (or for what matters any tracking code) and we will put it on every page.', 'bitcoin' ),
							'editor_type' => 'javascript',
						),
					)
				),

				'import_demo_data' => array(
					'title'       => __( 'Demo Data', 'bitcoin' ),
					'description' => esc_html__( 'If you would like to have a "ready to go" website as the Bitcoin\'s demo page here is the button', 'bitcoin' ),
					'priority'    => 999999,
					'options'     => array(
						'import_demodata_button' => array(
							'title' => 'Import',
							'type'  => 'html',
							'html'  => '<input type="hidden" name="wpGrade-nonce-import-posts-pages" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_posts_pages' ) . '" />
										<input type="hidden" name="wpGrade-nonce-import-theme-options" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_theme_options' ) . '" />
										<input type="hidden" name="wpGrade-nonce-import-widgets" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_widgets' ) . '" />
										<input type="hidden" name="wpGrade_import_ajax_url" value="' . admin_url( "admin-ajax.php" ) . '" />' .
							           '<span class="description customize-control-description">' . esc_html__( '(Note: We cannot serve you the original images due the ', 'bitcoin' ) . '<strong>&copy;</strong>)</span></br>' .
							           '<a href="#" class="button button-primary" id="wpGrade_import_demodata_button" style="width: 70%; text-align: center; padding: 10px; display: inline-block; height: auto;  margin: 0 15% 10% 15%;">
											' . __( 'Import demo data', 'bitcoin' ) . '
										</a>

										<div class="wpGrade-loading-wrap hidden">
											<span class="wpGrade-loading wpGrade-import-loading"></span>
											<div class="wpGrade-import-wait">' .
							           esc_html__( 'Please wait a few minutes (between 1 and 3 minutes usually, but depending on your hosting it can take longer) and ', 'bitcoin' ) .
							           '<strong>' . esc_html__( 'don\'t reload the page', 'bitcoin' ) . '</strong>.' .
							           esc_html__( 'You will be notified as soon as the import has finished!', 'bitcoin' ) . '
											</div>
										</div>

										<div class="wpGrade-import-results hidden"></div>
										<div class="hr"><div class="inner"><span>&nbsp;</span></div></div>'
						)
					)
				)
			)
		);

		$options['panels']['layouts_panel'] = array(
			'title'    => __( '&#x1f4bb; Layout', 'bitcoin' ),
			'sections' => array(

				'header_layouts_section'     => array(
					'title'   => __( 'Header', 'bitcoin' ),
					'options' => array(

						'header_logo_height' => array(
							'type'        => 'range',
							'label'       => __( 'Logo Height', 'bitcoin' ),
							'desc'        => 'This setting only applies to images',
							'default'     => 32,
							'live'        => true,
							'input_attrs' => array(
								'min'          => 20,
								'max'          => 100,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property'        => 'max-height',
									'selector'        => '.site-branding img',
									'unit'            => 'px',
									'callback_filter' => 'bitcoin_update_header_height'
								)
							)
						),

						'header_vertical_margins' => array(
							'type'        => 'range',
							'label'       => __( 'Header Vertical Margins', 'bitcoin' ),
							'default'     => 0,
							'live'        => true,
							'input_attrs' => array(
								'min'          => 0,
								'max'          => 100,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property'        => 'padding-top',
									'selector'        => '.site-header',
									'unit'            => 'px',
									'media'           => ' screen and (min-width: 900px)',
									'callback_filter' => 'bitcoin_update_header_height'
								),
								array(
									'property' => 'padding-bottom',
									'selector' => '.site-header',
									'unit'     => 'px',
									'media'    => 'screen and (min-width: 900px) '
								)
							)
						),

						'navigation_menu_items_spacing' => array(
							'type'        => 'range',
							'label'       => __( 'Menu Items Spacing', 'bitcoin' ),
							'default'     => 20,
							'live'        => true,
							'input_attrs' => array(
								'min'          => 12,
								'max'          => 40,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'padding-left',
									'selector' => 'ul.primary-menu > .menu-item > a, .header--transparent ul.primary-menu > .menu-item > a',
									'unit'     => 'px',
									'media'    => ' screen and (min-width: 900px)'
								),
								array(
									'property' => 'padding-right',
									'selector' => 'ul.primary-menu > .menu-item > a',
									'unit'     => 'px',
									'media'    => 'screen and (min-width: 900px) '
								)
							)
						),

						'navigation_menu_items_spacing_content_width'   => array(
							'type'        => 'range',
							'label'       => __( 'Container Width', 'bitcoin' ),
							// 'desc'        => __( 'Set the width of the container.', 'bitcoin' ),
							'live'        => true,
							'default'     => 1140,
							'input_attrs' => array(
								'min'          => 600,
								'max'          => 2700,
								'step'         => 10,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'max-width',
									'selector' => '.site-header__in',
									'unit'     => 'px',
								)
							)
						),
					)
				),
				'front_page_layouts_section' => array(
					'title'   => __( 'Front Page', 'bitcoin' ),
					'options' => array(

						'frontpage_content_width'   => array(
							'type'        => 'range',
							'label'       => __( 'Container Width', 'bitcoin' ),
							// 'desc'        => __( 'Set the width of the container.', 'bitcoin' ),
							'live'        => true,
							'default'     => 1140,
							'input_attrs' => array(
								'min'          => 600,
								'max'          => 2700,
								'step'         => 10,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'max-width',
									'selector' => '.section-wrap',
									'unit'     => 'px',
								)
							)
						),
						'sections_vertical_margins' => array(
							'type'        => 'range',
							'label'       => __( 'Sections Vertical Margins', 'bitcoin' ),
							'live'        => true,
							'default'     => 90,
							'input_attrs' => array(
								'min'          => 30,
								'max'          => 120,
								'step'         => 6,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'padding-top',
									'selector' => '.front-page-section',
									'unit'     => 'px',
									'media'    => ' only screen and (min-width: 900px)',
								),
								array(
									'property' => 'padding-bottom',
									'selector' => '.front-page-section',
									'unit'     => 'px',
									'media'    => 'only screen and (min-width: 900px) ',
								)
							)
						)
					),
				),
				'content_layouts_section'    => array(
					'title'   => __( 'Content', 'bitcoin' ),
					'options' => array(

						'content_width' => array(
							'type'        => 'range',
							'label'       => __( 'Container Width', 'bitcoin' ),
							// 'desc'        => __( 'Set the width of the container.', 'bitcoin' ),
							'live'        => true,
							'default'     => 1140,
							'input_attrs' => array(
								'min'          => 600,
								'max'          => 2700,
								'step'         => 10,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'max-width',
									'selector' => '.site-content__wrapper',
									'unit'     => 'px',
								)
							)
						),
						'sections_vertical_margins' => array(
							'type'        => 'range',
							'label'       => __( 'Sections Vertical Margins', 'bitcoin' ),
							'live'        => true,
							'default'     => 60,
							'input_attrs' => array(
								'min'          => 30,
								'max'          => 120,
								'step'         => 6,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'padding-top',
									'selector' => '.postcards',
									'unit'     => 'px',
									'media'    => ' only screen and (min-width: 900px)',
								),
								array(
									'property' => 'padding-bottom',
									'selector' => '.postcards',
									'unit'     => 'px',
									'media'    => 'only screen and (min-width: 900px) ',
								)
							)
							
						),
						'blog_type_style' => array(
							'type' => 'radio_image',
							'label' => esc_html__('Blog type', 'bitcoin'),
							'default' => 'tile',
							'choices' => array(
								'tile' => get_template_directory_uri() . '/assets/img/tile.png',
								'list' => get_template_directory_uri() . '/assets/img/list.png'
							),
						),
					),
				)
			)
		);

		$options['panels']['colors'] = array(
			'title'    => '&#x1f3a8; ' . esc_html__( 'Colors', 'bitcoin' ),
			'sections' => array(
				'site_header' => array(
					'title'   => esc_html__( 'Site Header', 'bitcoin' ),
					'options' => array(
						'header_transparent'      => array(
							'type'    => 'checkbox',
							'default' => true,
							'label'   => esc_html__( 'Transparent on Front Page Hero', 'bitcoin' ),
						),
						'header_transparent_blog'      => array(
							'type'    => 'checkbox',
							'default' => true,
							'label'   => esc_html__( 'Transparent on Blog Posts Page', 'bitcoin' ),
						),
						'header_background_color' => array(
							'type'    => 'color',
							'label'   => esc_html__( 'Header Background Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#FFFFFF',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.site-header,
									.primary-menu ul .children, ul.primary-menu .sub-menu'
								),
								array(
									'property' => 'background-color',
									'media'    => 'not screen and (min-width: 900px)',
									'selector' => '.menu-wrapper',
								),
								array(
									'property' => 'background-color',
									'media'    => 'not screen and (min-width: 900px)',
									'selector' => '.primary-menu ul .children, ul.primary-menu .sub-menu',
									'callback_filter' => 'bitcoin_customify_darker_callback'
								),
							)
						),
						'site_title_color'        => array(
							'type'    => 'color',
							'label'   => __( 'Site Title Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#32325d',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.site-header .site-title,
									.menu-trigger, .search-trigger--mobile, 
									ul.primary-menu li a, .menu-wrapper a,  ul.primary-menu .menu-item a, ul.primary-menu > .menu-item-has-children'								
								),
								array(
									'property' => 'fill',
									'selector' => '.menu-trigger svg',
								),
								array(
									'property' => 'border-color',
									'selector' => '.primary-menu > .menu-item-has-children:before, .primary-menu > .menu-item-has-children:before, ul.primary-menu >.menu-item-has-children:before',
								)
							),
						),
						'nav_link_color'          => array(
							'type'    => 'color',
							'label'   => __( 'Nav Link Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#6b7c93',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.header--transparent .primary-menu ul .children a, .header--transparent ul.primary-menu .sub-menu a,    .primary-menu ul .header--transparent .children a,
										.primary-menu ul a, .menu-wrapper a,
										
										.primary-menu ul .page_item a,
										ul.primary-menu ul .menu-item a,
										.primary-menu ul .page_item_has_children > a,
										ul.primary-menu ul .menu-item-has-children > a,
										ul.primary-menu ul .menu-item-has-children'
								),
								array(
									'property' => 'border-color',
									'selector' => '.menu-item-has-children:before',
									
								)
								// array(
								// 	'property'        => 'border-color',
								// 	'media'           => 'only screen and  (min-width: 900px)',
								// 	'selector'        => '.primary-menu ul .children, ul.primary-menu .sub-menu',
								// 	'callback_filter' => 'bitcoin_customify_lighter_callback'
								// ),
							)
						),
						'nav_active_color'        => array(
							'type'    => 'color',
							'label'   => __( 'Nav Active Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#2d7df0',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '
												 .primary-menu li.current_page_item a, ul.primary-menu li.current_page_item a'
								)
							)
						),
						'nav_button_color'        => array(
							'type'    => 'color',
							'label'   => __( 'Nav Button Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#EBEBEB',
							'css'     => array(
								array(
									'property' => 'border-color',
									'media'    => 'screen and (min-width: 900px) ',
									'selector' => '.primary-menu ul > .cta.page_item, ul.primary-menu > .cta.menu-item',
								),
							)
						),
					)
				),

				'site_blogheroarea' => array(
					'title'   => esc_html__( 'Blog Hero Area', 'bitcoin' ),
					'options' => array(
						'site_blogheroarea_textcolor' => array(
							'type'    => 'color',
							'label'   => __( 'Text Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#fff',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.hero-header__content'
								),
								array(
									'property' => 'border-color',
									'selector' => '.hero-category__list li'
								),
								array(
									'property' => 'background-color',
									'selector' => '.hero-category__list li a:hover',
									'callback_filter' => 'bitcoin_site_blogheroarea_color_with_opacity_callback'
								)
							)
						),
						'site_blogheroarea_gradover' => array(
							'type'    => 'checkbox',
							'default' => false,
							'label'   => __( 'Apply Gradient Overlay', 'bitcoin' ),
						),
						'site_blogheroarea_color1' => array(
							'type'    => 'color',
							'label'   => __( 'Gradient Background Color From Left Top Corner', 'bitcoin' ),
							'live'    => true,
							'default' => '#6d42ef',
							'css'     => array(
								array(
									'property' => 'fill',
									'selector' => '#hero-header__overlay1'						
								)
							)
						),
						'site_blogheroarea_color2' => array(
							'type'    => 'color',
							'label'   => __( 'Gradient Background Color From Right Bottom Corner', 'bitcoin' ),
							'live'    => true,
							'default' => '#00eaf8',
							'css'     => array(
								array(
									'property' => 'fill',
									'selector' => '#hero-header__overlay2'								
								)
							)
						),
						'site_blogheroarea_linover' => array(
							'type'    => 'checkbox',
							'default' => false,
							'label'   => __( 'Apply Gradient Overlay', 'bitcoin' ),
						),
						'site_blogheroarea_color3' => array(
							'type'    => 'color',
							'label'   => __( 'Linear Overlay Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#fff',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.hero-header__overlay3'
								)
							)
						),
						'site_blogheroarea_color3_opacity' => array(
							'type'    => 'range',
							'label'       => 'It\'s opacity',
							'live'        => true,
							'default'     => .5,
							'input_attrs' => array(
								'min'  => 0,
								'max'  => 1,
								'step' => .1,
							),
							'css' => array(
								array(
									'property' => 'opacity',
									'selector' => '.hero-header__overlay3',
								)
							)
						),
						'site_blogheroarea_line_width' => array(
							'type' => 'range',
							'label' => __('Line Width', 'bitcoin'),
							'live' => true,
							'default' => 1,
							'input_attrs' => array(
								'min' => 0,
								'max' => 5,
								'step' => 1,
								'data-preview' => true
							),
							'css' => array(
								array(
									'property' => 'border-width',
									'selector' => '.hero-category__list li',
									'unit' => 'px'
								)
							)
						),
					'site_blogheroarea_cat_radius' => array(
						'type' => 'range',
						'label' => __('Line Width', 'bitcoin'),
						'live' => true,
						'default' => 20,
						'input_attrs' => array(
							'min' => 0,
							'max' => 20,
							'step' => 1,
							'data-preview' => true
						),
						'css' => array(
							array(
								'property' => 'border-radius',
								'selector' => '.hero-category__list li',
								'unit' => 'px'
							)
						)
					),
					)
				),

				'main_content' => array(
					'title'   => esc_html__( 'Main Content', 'bitcoin' ),
					'options' => array(
						'content_background' => array(
							'type'    => 'color',
							'label'   => __( 'Content Background', 'bitcoin' ),
							'live'    => true,
							'default' => '#FFFFFF',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => 'html',
								)
							)
						),
						'page_background'    => array(
							'type'    => 'color',
							'label'   => __( 'Page Background', 'bitcoin' ),
							'live'    => true,
							'default' => '#F9F9F9',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.front-page-section:nth-child(2n),
										.blog, .archive,
										site-content__wrapper,
										.error404 .entry-header, .search-no-results .entry-header',
								)

							)
						),
						'page_titles_color'  => array(
							'type'    => 'color',
							'label'   => __( 'Page Titles Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#484848',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.page-title,
										.widget_title--frontpage,
										.single:not(.single-job_listing) .entry-title, .page .entry-title,
										.card__title,
										h1, h2, h3, h4, h5, h6,
										.results,
										.intro,
										.listing-sidebar .widget_sidebar_title',
								),
							)
						),

						'page_subtitles_color' => array(
							'type'    => 'color',
							'label'   => __( 'Page Subtitles Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#919191',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.widget_subtitle--frontpage',
								),
							)
						),
						'text_color'           => array(
							'type'    => 'color',
							'label'   => __( 'Text Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#484848',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.comment-content a:hover,
										label, html, body,
										.listing-sidebar,
										.widget_listing_content,
										.widget_listing_comments h3.pixrating_title,
										.widget_listing_sidebar_categories .category-text,
										.widget_listing_tags .tag__text'
								),

								array(
									'property' => 'border-color',
									'selector' => 'input[type="checkbox"]:focus,
													.package__btn.package__btn:hover',
								)
							)
						),
						'buttons_color'        => array(
							'type'    => 'color',
							'label'   => __( 'Buttons Color', 'bitcoin' ),
							'live'    => true,
							'	default' => '#FF4D55',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.btn, input[type="submit"],
										.page-template-front_page .search-form .search-submit,
										.job-manager-form fieldset .job-manager-uploaded-files .job-manager-uploaded-file .job-manager-uploaded-file-preview a,
										.woocommerce-account:not(.logged-in) .woocommerce form.login input[type="submit"],
										.woocommerce .button,
										.popup__rating,
										.single-action-buttons .action:hover .action__icon,
										.action--favorite.bookmarked .action__icon,
										.package--labeled .package__btn.package__btn,
										.featured-label,
										.product .product__tag,
										.wc-bookings-date-picker .ui-datepicker td > a:hover,
										.wc-bookings-date-picker .ui-datepicker table .bookable-range a,
										.wc-bookings-date-picker .ui-datepicker table .ui-datepicker-current-day a,
										.block-picker > li a.selected,
										.block-picker > li a:hover,
										.lwa-form input[type="submit"]:hover,
										.no-results .clear-results-btn',
								),
								array(
									'property' => 'color',
									'selector' => '.package__btn.package__btn',
								),
								array(
									'property' => 'fill',
									'selector' => '.heart.is--bookmarked #hearshape',
								),
							)
						),
					)
				),

				'cards' => array(
					'title'   => esc_html__( 'Cards', 'bitcoin' ),
					'options' => array(
						'cards_background'            => array(
							'type'    => 'color',
							'label'   => __( 'Cards Background', 'bitcoin' ),
							'live'    => true,
							'default' => '#FFFFFF',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.card'
								),
								array(
									'property' => 'color',
									'selector' => '.card.format-quote .card-meta,
													.card.format-quote .card__content,
													.card.format-quote blockquote a'
								),
								array(
									'property' => 'fill',
									'selector' => '.card.format-quote .bitcoin-icon svg use'
								)
							)
						),
						'cards_radius'                => array(
							'type'        => 'range',
							'label'       => 'Cards Radius',
							'live'        => true,
							'default'     => 8,
							'input_attrs' => array(
								'min'  => 0,
								'max'  => 36,
								'step' => 2,
							),
							'css'         => array(
								array(
									'selector' => '.postcard',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.card .card__image',
									'property' => 'border-top-left-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.grid--tile .card .card__image',
									'property' => 'border-top-right-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.card .card__sticky',
									'property' => 'border-bottom-left-radius',
									'unit' => 'px'
								)
							)
						),
						'thumbs_radius'               => array(
							'type'        => 'range',
							'label'       => 'Categories Radius',
							'live'        => true,
							'default'     => 14,
							'input_attrs' => array(
								'min'  => 0,
								'max'  => 36,
								'step' => 2,
							),
							'css'         => array(
								array(
									'selector' => '.card__categories li a',
									'property' => 'border-radius',
									'unit'     => 'px'
								)
							)
						),
						'cards_title_color'           => array(
							'type'    => 'color',
							'label'   => __( 'Title Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#484848',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.card__title',
								),
							)
						),
						'cards_text_color'            => array(
							'type'    => 'color',
							'label'   => __( 'Text Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#6b7c93',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.card, .card__content, .card--post.sticky',
								),
							)
						),
						'cards_hover'            => array(
							'type'    => 'color',
							'label'   => __( 'Hover Color On Links', 'bitcoin' ),
							'live'    => true,
							'default' => '#22dfb5',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '
										.card__title:hover,
										.card__title:focus,
										.posted-on:hover, 
										.posted-on:focus, 
										.posted-by:hover,
										.posted-by:focus,
										.card-gallery__slider .slick-arrow'
								),
								array(
									'property' => 'background-color',
									'selector' => '
										.format-standard.card .card__toplink:before,
										.format-link.card .card__toplink:before,
										.card .card__sticky'
								),
								array(
									'property' => 'background-color',
									'selector' => '.format-quote .card__toplink',
									'unit' => '.95',
									'callback_filter' => 'bitcoin_color_with_opacity_callback'
								),
								array(
									'property' => 'fill',
									'selector' => '.postcard .bitcoin-icon use'
								)
							)
						)

					)
				),

				'prefooter' => array(
					'title'   => esc_html__( 'Pre Footer', 'bitcoin' ),
					'options' => array(
						'prefooter_background' => array(
							'type'    => 'color',
							'label'   => __( 'Background', 'bitcoin' ),
							'live'    => true,
							'default' => '#2F2929',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.footer-widget-area'
								),
							)
						),
						'prefooter_text_color' => array(
							'type'    => 'color',
							'label'   => __( 'Text Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#FFFFFF',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.widget--footer',
								)
							)
						),
					)
				),

				'footer' => array(
					'title'   => esc_html__( 'Footer', 'bitcoin' ),
					'options' => array(
						'footer_background'    => array(
							'type'    => 'color',
							'label'   => __( 'Background', 'bitcoin' ),
							'live'    => true,
							'default' => '#261E1E',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.site-footer'
								),
							)
						),
						'footer_text_color'    => array(
							'type'    => 'color',
							'label'   => __( 'Text Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#ADADB2',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.site-info',
								),
							)
						),
						'footer_credits_color' => array(
							'type'    => 'color',
							'label'   => __( 'Credits Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#706C6C',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.theme-info',
								),
							)
						),
					)
				),

				'other_color' => array(
					'title'   => esc_html__( 'Other Colors', 'bitcoin' ),
					'options' => array(
						'accent_color' => array(
							'type'    => 'color',
							'label'   => __( 'Accent Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#22dfb5',
							'css'     => array(
								array(
									'property' => 'background-color',
									'selector' => '.card__links li a,
									html body .mejs-container, html body .mejs-container .mejs-controls, html body .mejs-embed, html body .mejs-embed body',
								),
								array(
									'property' => 'background',
									'selector' => 'progress::-webkit-progress-bar',
								),
								array(
									'property' => 'background',
									'selector' => 'progress::-webkit-progress-value',
								),
								array(
									'property' => 'background',
									'selector' => 'progress::-moz-progress-bar',
								),
								array(
									'property' => 'color',
									'selector' => 'a:not(.btn)',
								),
								array(
									'property' => 'border-top-color',
									'selector' => '.page-template-front_page .is--active .search-field-wrapper.has--menu:after,
									                ul.secondary-menu > .menu-item.menu-item-has-children > .sub-menu:after,
									                .search_jobs--frontpage .chosen-with-drop.chosen-container-active .chosen-single:after',
								)
							)
						),

						'fields_color' => array(
							'type'    => 'color',
							'label'   => __( 'Meta Fields Color', 'bitcoin' ),
							'live'    => true,
							'default' => '#8fa0ad',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.description, .entry-meta a, .page .entry-meta a,
														.card-meta'
								),
								array(
									'property' => 'background-color',
									'selector' => 'html body .mejs-controls .mejs-time-rail .mejs-time-current '
								),
								array(
									'property' => 'color',
									'selector' => '.page-template-front_page .search-form .search-field::-webkit-input-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.page-template-front_page .search-form .search-field::-moz-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.page-template-front_page .search-form .search-field::-moz-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.select-tags .chosen-container-multi .chosen-choices li.search-field::-webkit-input-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.select-tags .chosen-container-multi .chosen-choices li.search-field:-moz-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.select-tags .chosen-container-multi .chosen-choices li.search-field::-moz-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.select-tags .chosen-container-multi .chosen-choices li.search-field:-ms-input-placeholder'
								),
								array(
									'property' => 'color',
									'selector' => '.description_tooltip',
								),
							)
						),
						'micro_color'  => array(
							'type'    => 'color',
							'label'   => __( 'Micro Elements', 'bitcoin' ),
							'live'    => true,
							'default' => '#ABABAB',
							'css'     => array(
								array(
									'property' => 'color',
									'selector' => '.job_filters .showing_jobs,
										.tax-job_listing_category div.job_listings .load_more_jobs strong,
										.tax-job_listing_tag div.job_listings .load_more_jobs strong'
								),
								array(
									'property' => 'border-top-color',
									'selector' => '
										.chosen-container-single .chosen-single div b:after,
										.select-tags .chosen-container-multi .chosen-choices:after'
								),
								array(
									'property' => 'background-color',
									'selector' => '
										.remove-tag:before,
										.remove-tag:after'
								),
							)
						),

					)
				),
			)
		);

		$options['panels']['fonts'] = array(
			'title'    => '&#x1f4dd; ' . esc_html__( 'Fonts', 'bitcoin' ),
			'sections' => array(
				'site_title_font' => array(
					'title'   => esc_html__( 'Site Title', 'bitcoin' ),
					'options' => array(
						'site_title_font'           => array(
							'type'        => 'typography',
							// 'label'            => esc_html__( 'Site Title', 'bitcoin' ),
							'desc'        => __( 'The font used for Site Title when you do not have a Logo image.', 'bitcoin' ),
							'selector'    => '.site-header .site-title',
							'default'     => array( 'Noto Sans', '700' ),
							'recommended' => $recommended_headings_fonts
						),

						// Font Size
						'site_title_font_size'      => array(
							'type'        => 'range',
							'label'       => __( 'Font Size', 'bitcoin' ),
							'live'        => true,
							'default'     => 24,
							'input_attrs' => array(
								'min'          => 8,
								'max'          => 40,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'font-size',
									'selector' => '.site-header .site-title',
									'unit'     => 'px',
								)
							)
						),

						// Text Transform
						'site_title_text_transform' => array(
							'label'   => __( 'Text Transform', 'bitcoin' ),
							'live'    => true,
							'type'    => 'select',
							'choices' => array(
								'none'       => 'None',
								'capitalize' => 'Capitalize',
								'uppercase'  => 'Uppercase',
								'lowercase'  => 'Lowercase',
							),
							'default' => 'None',
							'css'     => array(
								array(
									'property' => 'text-transform',
									'selector' => '.site-header .site-title',
								)
							)
						),

						// Letter Spacing
						'site_title_letter-spacing' => array(
							'type'        => 'range',
							'label'       => __( 'Letter Spacing', 'bitcoin' ),
							'live'        => true,
							'default'     => 0,
							'input_attrs' => array(
								'min'          => - 2,
								'max'          => 20,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'letter-spacing',
									'selector' => '.site-header .site-title',
									'unit'     => 'px',
								)
							)
						),
					)
				),

				'navigation_font' => array(
					'title'   => esc_html__( 'Navigation Text', 'bitcoin' ),
					'options' => array(
						'navigation_font'           => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Navigation Text', 'bitcoin' ),
							'selector'         => '.primary-menu > ul, ul.primary-menu a',
							'load_all_weights' => false,
							'subsets'          => false,
							'default'          => array( 'Noto Sans', '400' ),
							'recommended'      => $recommended_body_fonts
						),

						// Font Size
						'navigation_font_size'      => array(
							'type'        => 'range',
							'label'       => __( 'Font Size', 'bitcoin' ),
							'live'        => true,
							'default'     => 16,
							'input_attrs' => array(
								'min'          => 10,
								'max'          => 20,
								'step'         => 0.1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'font-size',
									'selector' => '.primary-menu > ul a, ul.primary-menu a,
											.search-suggestions-menu li a',
									'unit'     => 'px',
								)
							)
						),

						// Text Transform
						'navigation_text_transform' => array(
							'label'   => __( 'Text Transform', 'bitcoin' ),
							'live'    => true,
							'type'    => 'select',
							'choices' => array(
								'none'       => 'None',
								'capitalize' => 'Capitalize',
								'uppercase'  => 'Uppercase',
								'lowercase'  => 'Lowercase',
							),
							'default' => 'Capitalize',
							'css'     => array(
								array(
									'property' => 'text-transform',
									'selector' => '.primary-menu > ul a, ul.primary-menu a,
											.search-suggestions-menu li a',
								)
							)
						),

						// Letter Spacing
						'navigation_letter-spacing' => array(
							'type'        => 'range',
							'label'       => __( 'Letter Spacing', 'bitcoin' ),
							'live'        => true,
							'default'     => 0,
							'input_attrs' => array(
								'min'          => - 2,
								'max'          => 20,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'letter-spacing',
									'selector' => '.primary-menu > ul a, ul.primary-menu a,
											.search-suggestions-menu li a',
									'unit'     => 'px',
								)
							)
						),
					)
				),

				'body_font'        => array(
					'title'   => esc_html__( 'Body Text', 'bitcoin' ),
					'options' => array(
						'body_font' => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Body Font', 'bitcoin' ),
							'selector'         => 'input,
								textarea,
								label,
								html,
								.entry-content blockquote cite,
								.comment-content blockquote cite,
								.card--feature .card__content,
								.rating,
								.widget--footer .search-form .search-field,
								.featured-label,
								.package__description,
								.footer-text-area,
								.widget_listing_comments h3.pixrating_title,
								',
							'load_all_weights' => true,
							'default'          => array(
								'type'        => 'google',
								'font_family' => 'Noto Sans',
								'variants'    => 'regular'
							),
							'recommended'      => $recommended_body_fonts
						),
					)
				),

				// $Headings
				'page_titles_font' => array(
					'title'   => esc_html__( 'Page Titles', 'bitcoin' ),
					'options' => array(
						'page_titles_font' => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Headings', 'bitcoin' ),
							'selector'         => '.entry-title, .archive-title,
									h1, h2, h3,
									.card--feature .card__title,
									.results,
									.page-title,
									.search_location input,
									.package__title, .package__price,
									.package__subscription-period,
									h2.comments-title,
									.page-add-listing fieldset:first-child label,
									.product-content .price',
							'load_all_weights' => false,
							'subsets'          => true,
							'default'          => array( 'Noto Sans', '700' ),
							'recommended'      => $recommended_headings_fonts
						),
					)
				),

				'page_subtitles_font' => array(
					'title'   => esc_html__( 'Page Subtitles', 'bitcoin' ),
					'options' => array(
						'page_subtitles_font' => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Page Subtitles', 'bitcoin' ),
							'selector'         => '.intro,
									.description,
									.tabs.wc-tabs,
									.job-manager-form fieldset .job-manager-uploaded-files .job-manager-uploaded-file .job-manager-uploaded-file-preview a,
									.widget_subtitle--frontpage,
									.category-list a,
									.single:not(.single-job_listing) .entry-subtitle,
									.page .entry-subtitle,
									.single-job_listing .entry-subtitle',
							'load_all_weights' => false,
							'subsets'          => false,
							'default'          => array( 'Noto Sans', '400' ),
							'recommended'      => $recommended_headings_fonts
						),
					)
				),

				'card_title_font' => array(
					'title'   => esc_html__( 'Card Title Font', 'bitcoin' ),
					'options' => array(
						'card_title_font'           => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Card Title Font', 'bitcoin' ),
							'selector'         => '.card__title, ul.categories--widget .category-count,
									ul.categories--widget .category-text',
							'load_all_weights' => false,
							'default'          => array( 'Noto Sans', '700' ),
							'recommended'      => $recommended_headings_fonts
						),
						// Font Size
						'card_title_font_size'      => array(
							'type'        => 'range',
							'label'       => __( 'Font Size', 'bitcoin' ),
							'live'        => true,
							'default'     => 24,
							'input_attrs' => array(
								'min'          => 10,
								'max'          => 40,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'font-size',
									'selector' => '.card__title',
									'unit'     => 'px',
								)
							)
						),

						// Text Transform
						'card_title_text_transform' => array(
							'label'   => __( 'Text Transform', 'bitcoin' ),
							'live'    => true,
							'type'    => 'select',
							'choices' => array(
								'none'       => 'None',
								'capitalize' => 'Capitalize',
								'uppercase'  => 'Uppercase',
								'lowercase'  => 'Lowercase',
							),
							'default' => 'None',
							'css'     => array(
								array(
									'property' => 'text-transform',
									'selector' => '.card__title',
								)
							)
						),

						// Letter Spacing
						'card_title_letter-spacing' => array(
							'type'        => 'range',
							'label'       => __( 'Letter Spacing', 'bitcoin' ),
							'live'        => true,
							'default'     => 0,
							'input_attrs' => array(
								'min'          => - 2,
								'max'          => 20,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'letter-spacing',
									'selector' => '.card__title',
									'unit'     => 'px',
								)
							)
						),
					)
				),

				'card_font' => array(
					'title'   => esc_html__( 'Card Font', 'bitcoin' ),
					'options' => array(
						'card_font' => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Card Font', 'bitcoin' ),
							'selector'         => '.card',
							'load_all_weights' => false,
							'default'          => array( 'Noto Sans', '400' ),
							'recommended'      => $recommended_body_fonts
						),
					)
				),

				'btn_font' => array(
					'title'   => esc_html__( 'Buttons & Forms', 'bitcoin' ),
					'options' => array(
						'btn_font' => array(
							'type'             => 'typography',
							'label'            => esc_html__( 'Buttons & Forms', 'bitcoin' ),
							'selector'         => '
								.entry-meta,
								.page .entry-meta,
								input[type="submit"],
								button[type="submit"],
								input[type="submit"],
								.btn',
							'load_all_weights' => false,
							'default'          => array( 'Noto Sans', '400' ),
							'subsets'          => false,
							'recommended'      => $recommended_body_fonts
						),
						// Font Size
						'btn_title_font_size'      => array(
							'type'        => 'range',
							'label'       => __( 'Font Size', 'bitcoin' ),
							'live'        => true,
							'default'     => 15,
							'input_attrs' => array(
								'min'          => 10,
								'max'          => 40,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'font-size',
									'selector' => '.entry-meta,
								.page .entry-meta,
								input[type="submit"],
								button[type="submit"],
								input[type="submit"],
								.btn',
									'unit'     => 'px',
								)
							)
						),

						// Text Transform
						'btn_title_text_transform' => array(
							'label'   => __( 'Text Transform', 'bitcoin' ),
							'live'    => true,
							'type'    => 'select',
							'choices' => array(
								'none'       => 'None',
								'capitalize' => 'Capitalize',
								'uppercase'  => 'Uppercase',
								'lowercase'  => 'Lowercase',
							),
							'default' => 'None',
							'css'     => array(
								array(
									'property' => 'text-transform',
									'selector' => '.entry-meta,
								.page .entry-meta,
								input[type="submit"],
								button[type="submit"],
								input[type="submit"],
								.btn',
								)
							)
						),

						// Letter Spacing
						'btn_title_letter-spacing' => array(
							'type'        => 'range',
							'label'       => __( 'Letter Spacing', 'bitcoin' ),
							'live'        => true,
							'default'     => 0,
							'input_attrs' => array(
								'min'          => - 2,
								'max'          => 20,
								'step'         => 1,
								'data-preview' => true
							),
							'css'         => array(
								array(
									'property' => 'letter-spacing',
									'selector' => '.entry-meta,
								.page .entry-meta,
								input[type="submit"],
								button[type="submit"],
								input[type="submit"],
								.btn',
									'unit'     => 'px',
								)
							)
						),
					)
				),
				
				'meta_font' => array(
					'title' => esc_html__('Meta', 'bitcoin'),
					'options' => array(
						'meta_font' => array(
							'type' => 'typography',
							'label' => esc_html__('Meta', 'bitcoin'),
							'selector' => '.card-meta',
							'load_all_weights' => false,
							'default' => array('Noto Sans', '400'),
							'subsets' => false,
							'recommended' => $recommended_body_fonts
						),
							// Font Size
						'meta_title_font_size' => array(
							'type' => 'range',
							'label' => __('Font Size', 'bitcoin'),
							'live' => true,
							'default' => 14,
							'input_attrs' => array(
								'min' => 10,
								'max' => 40,
								'step' => 1,
								'data-preview' => true
							),
							'css' => array(
								array(
									'property' => 'font-size',
									'selector' => '.card-meta',
									'unit' => 'px',
								)
							)
						),

							// Text Transform
						'meta_title_text_transform' => array(
							'label' => __('Text Transform', 'bitcoin'),
							'live' => true,
							'type' => 'select',
							'choices' => array(
								'none' => 'None',
								'capitalize' => 'Capitalize',
								'uppercase' => 'Uppercase',
								'lowercase' => 'Lowercase',
							),
							'default' => 'None',
							'css' => array(
								array(
									'property' => 'text-transform',
									'selector' => '.card-meta',
								)
							)
						),

							// Letter Spacing
						'meta_title_letter-spacing' => array(
							'type' => 'range',
							'label' => __('Letter Spacing', 'bitcoin'),
							'live' => true,
							'default' => 0,
							'input_attrs' => array(
								'min' => -2,
								'max' => 20,
								'step' => 1,
								'data-preview' => true
							),
							'css' => array(
								array(
									'property' => 'letter-spacing',
									'selector' => '.card-meta',
									'unit' => 'px',
								)
							)
						),
					)
				),
			)
		);

		/**
		 * Register a second logo option which will be moved in the title_tagline section
		 */
		$options['sections']['to_be_removed'] = array(
			'options' => array(
				'logo_invert' => array(
					'type'    => 'media',
					'label'   => esc_html__( 'Logo while on Transparent Hero Area', 'bitcoin' ),
					'desc'    => esc_html__( 'Replace the default logo on the Front Page Hero.', 'bitcoin' ),
					'show_on' => array( 'header_transparent' ),
				),
			)
		);

		// $options['panels'] = array();
		return $options;
	}
endif;


add_action( 'customize_register', 'listabe_adjust_cusotmizer_settings', 35 );

/**
 * Use this function to move or reorder options between sections
 *
 * @param $wp_customize
 */
function listabe_adjust_cusotmizer_settings( $wp_customize ) {

	// move the `logo_invert` option to the title_tagline section(just to keep the well grouped)
	$logo_invert           = $wp_customize->get_control( 'bitcoin_options[logo_invert]_control' );

	if ( ! empty( $logo_invert ) ) {
		$logo_invert->section  = 'title_tagline';
		$logo_invert->priority = 9;
	}
}

add_filter( 'customify_filter_fields', 'bitcoin_add_customify_options' );

if ( ! function_exists( 'isLight' ) ) {
	function isLight( $color = '#ffffff' ) {
		// Get our color
		$color = ( $color ) ? $color : '#ffffff';
		// Calculate straight from rbg
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		return ( ( $r * 299 + $g * 587 + $b * 114 ) / 1000 > 90 );
	}
}

if ( ! function_exists( 'bitcoin_getRGB' ) ) {
	function bitcoin_getRGB( $color = '#ffffff' ) {
		// Get our color
		$color = ( $color ) ? $color : '#ffffff';
		// Calculate straight from rbg
		$r = hexdec( $color[1] . $color[2] );
		$g = hexdec( $color[3] . $color[4] );
		$b = hexdec( $color[5] . $color[6] );

		return $r . ',' . $g . ',' . $b . ',';
	}
}

if ( ! function_exists( 'bitcoin_color_darken' ) ) {
	function bitcoin_color_darken( $color, $dif = 20 ) {

		$color = str_replace( '#', '', $color );
		if ( strlen( $color ) != 6 ) {
			return '000000';
		}
		$rgb = '';

		for ( $x = 0; $x < 3; $x ++ ) {
			$c = hexdec( substr( $color, ( 2 * $x ), 2 ) ) - $dif;
			$c = ( $c < 0 ) ? 0 : dechex( $c );
			$rgb .= ( strlen( $c ) < 2 ) ? '0' . $c : $c;
		}

		return '#' . $rgb;
	}
}

if ( ! function_exists( 'bitcoin_customify_darker_callback' ) ) {
	function bitcoin_customify_darker_callback( $value, $selector, $property, $unit ) {
		$darkenValue = 30;
		if ( $value == '#ffffff' ) {
			$darkenValue = 6;
		} // #FFFFFF -> #F9F9F9
		$output = $selector . '{' . $property . ': ' . bitcoin_color_darken( $value, $darkenValue ) . '}';

		return $output;
	}
}

if ( ! function_exists( 'bitcoin_customify_lighter_callback' ) ) {
	function bitcoin_customify_lighter_callback( $value, $selector, $property, $unit ) {
		$lighenValue = -100;
		if ( $value == '#ffffff' ) {
			$lighenValue = -100;
		} // #FFFFFF -> #F9F9F9
		$output = $selector . '{' . $property . ': ' . bitcoin_color_darken( $value, $lighenValue ) . '}';

		return $output;
	}
}


if ( ! function_exists( 'bitcoin_site_blogheroarea_color1_callback' ) ) {
	function bitcoin_site_blogheroarea_color1_callback( $value, $selector, $property, $unit ) {

		$output = $selector . ' { '. $property .': '. 'linear-gradient(-38deg, rgba(' . bitcoin_getRGB( $value ) . '0.9) 0%, rgba(255, 255, 255, 0) 100%)'. '; } \n';
	
		return $output;
	}
}

if ( ! function_exists( 'bitcoin_site_blogheroarea_color2_callback' ) ) {
	function bitcoin_site_blogheroarea_color2_callback( $value, $selector, $property, $unit ) {
	
		$output = $selector . ' { '. $property .': '. 'linear-gradient(-38deg, rgba(255, 255, 255, 0) 0%, rgba(' . bitcoin_getRGB( $value ) . ' 0.9) 100%)'. '; } \n';
	
		return $output;
	}
}

if ( ! function_exists( 'bitcoin_site_blogheroarea_color_with_opacity_callback' ) ) {
	function bitcoin_site_blogheroarea_color_with_opacity_callback( $value, $selector, $property, $unit ) {
	
		$output = $selector . ' { '. $property . ': rgba(' . bitcoin_getRGB( $value )  . ' 0.3);} ' ;
	
		return $output;
	}
}

if (!function_exists('bitcoin_color_with_opacity_callback')) {
	function bitcoin_color_with_opacity_callback($value, $selector, $property, $unit)
	{

		$output = $selector . ' { ' . $property . ': rgba(' . bitcoin_getRGB($value) . ' '. $unit . ');} ';
 
		return $output;
	}
}

function bitcoin_add_customify_font_std_font( $fonts ) {
	$new_fonts           = array();
	$new_fonts['Noto Sans'] = array(
		'font_family' => 'Noto Sans',
		'variants'    => array( '400', '700' )
	);

	return $new_fonts + $fonts;
}

add_filter( 'customify_filter_standard_fonts_list', 'bitcoin_add_customify_font_std_font' );

if ( ! function_exists( 'bitcoin_update_header_height' ) ) {
	function bitcoin_update_header_height( $value, $selector, $property, $unit ) {
		$output = $selector . '{' . $property . ': ' . $value . $unit . '}';

		return $output;
	}
}

function bitcoin_update_header_height_customizer_preview() {
	/**
	 * The WP-Job-Manager ajax requsets captures this output for some reason, which is wrong
	 * But also WP-Job-Manager defines the DOING_AJAX constant too late for the customizer preview hook
	 * As solution we can check the request uri if it contains a `/jm-ajax/`.It is definetly a job manager ajax request
	 */
	if ( strpos( $_SERVER['REQUEST_URI'], '/jm-ajax/' ) !== false ) {
		return;
	} ?>
	<script type="text/javascript">

		function bitcoin_update_header_height( value, selector, property, unit ) {
			(function ( $ ) {
				var headerPaddingBottom = parseInt($('.site-header').css('paddingTop'));
				$('.site-header').css('paddingBottom', headerPaddingBottom);
			})( jQuery );
		}
	</script>
<?php }



add_action( 'customize_preview_init', 'bitcoin_update_header_height_customizer_preview', 11 );
