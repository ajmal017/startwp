<?php
/**
* Require files that deal with various plugin integrations.
*
* @package Bitstarter
*/

/**
 * Load Jetpack compatibility file.
 * http://jetpack.me/
 */
require get_template_directory() . '/inc/integrations/jetpack.php';


/**
 * Load Timeline Express compatibility file.
 */
if ( class_exists( 'TimelineExpressBase' ) ) {
	require get_template_directory() . '/inc/integrations/timeline-express.php';
}

/**
 * Load ShortCodes .
 */
if ( class_exists( 'WPBakeryShortCode' ) ) {
	require get_template_directory() . '/inc/shortcodes/init.php';
}



