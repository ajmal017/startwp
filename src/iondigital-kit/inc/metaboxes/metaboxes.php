<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category WPGRADE_THEMENAME
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */


function load_metaboxes_fromdb( $meta_boxes ) {
	// make sure we are in good working order
	if ( empty( $meta_boxes ) ) {
		$meta_boxes = array();
	}
	$config = Enhanced::config();
	$options = get_option($config['settings-key']);
	
	if ( empty( $options['themes'] ) ) {
		return $meta_boxes;
	}
	
	$current_theme = '';
	if ( class_exists('ThemeConfig') ) {
		$current_theme = ThemeConfig::shortname();
	}else{
		$theme_support =  get_theme_support( 'iondigital_kit');
		if(is_array($theme_support)){
			$theme_support  = reset( $theme_support );
			if( isset($theme_support['theme_config']['shortname']) ){
				$current_theme = $theme_support['theme_config']['shortname'];
			}
		}
	}
	
	
	if ( empty( $options['themes'][ $current_theme ]['metaboxes'] ) ) {
		return $meta_boxes;
	}
	
	$theme_metaboxes = $options['themes'][ $current_theme ]['metaboxes'];
	if ( ! empty( $theme_metaboxes ) && is_array( $theme_metaboxes ) ) {
		$meta_boxes = array_merge( $meta_boxes, $theme_metaboxes );
	}
	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'load_metaboxes_fromdb', 1 );

/**
 * This is just a wrapper that provides an agnostic filter name, rather than relying on CMB's hook.
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function gather_metaboxes_dynamically( $meta_boxes ) {
	// make sure we are in good working order
	if ( empty( $meta_boxes ) ) {
		$meta_boxes = array();
	}

	return apply_filters( 'pixelgrade_filter_metaboxes', $meta_boxes );
}
add_filter( 'cmb_meta_boxes', 'gather_metaboxes_dynamically', 10 );

/*
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) ) {
		require_once 'init.php';

		require_once 'cmb-field-select2/cmb-field-select2.php';
		require_once 'cmb-field-select2-v2/cmb-field-select2.php';
	}

}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
