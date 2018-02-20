<?php
/**
 * Bitstarter Theme Customizer.
 *
 * @package Bitstarter
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function bitstarter_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action( 'customize_register', 'bitstarter_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function bitstarter_customize_preview_js() {
	wp_enqueue_script( 'bitstarter_customizer_preview', get_template_directory_uri() . '/assets/js/admin/customizer_preview.js', array( 'customize-preview' ), '201711', true );
}
add_action( 'customize_preview_init', 'bitstarter_customize_preview_js' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function bitstarter_customize_js() {


	wp_enqueue_script('bitstarter_admin_general', get_template_directory_uri() . '/assets/js/admin/admin-general.js', array('wp-ajax-response'), '201711', true);

	wp_enqueue_script( 'bitstarter_customizer', get_template_directory_uri() . '/assets/js/admin/customizer.js', array( 'wp-ajax-response', 'bitstarter_admin_general' ), '201711', true );

	wp_enqueue_style( 'bitstarter-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/customizer.css' );

	
	$translation_array = array (
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

	wp_localize_script( 'bitstarter_admin_general', 'bitstarter_admin_js_texts', $translation_array );

}
add_action( 'customize_controls_enqueue_scripts', 'bitstarter_customize_js' );


