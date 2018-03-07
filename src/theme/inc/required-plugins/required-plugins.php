<?php
/**
 * Bitstarter required or recommended plugins
 *
 */

require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'bitstarter_register_required_plugins', 999 );

function bitstarter_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	//	$plugins = array(
	//		array(
	//			'name'     				=> 'PLUGIN NAME', // The plugin name
	//			'slug'     				=> 'PLUGINSLUG', // The plugin slug (typically the folder name)
	//			'source'   				=> 'PLUGIN_LOCATION', // The plugin source
	//			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
	//			'version' 				=> '1.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented. If the plugin version is higher than the plugin version installed , the user will be notified to update the plugin
	//			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
	//			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
	//			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
	//		)
	//	);
	//
	$plugins = array(
		array(
			'name'      => 'Bitstarter Core',
			'slug'      => 'bitstarter-core',
			'source'    => get_template_directory() . '/inc/required-plugins/bitstarter-plugin/bitstarter-core.zip',
			'required'  => true,
		),
		array(
			'name'               => 'Customify',
			'slug'               => 'customify',
			'required'           => true
		),

		array(
			'name'      		 => 'Jetpack',
			'slug'      		 => 'jetpack',
			'required'  		 => false
		),

		array(
			'name'      		 => 'Timeline Express',
			'slug'      		 => 'timeline-express',
			'required'  		 => false
		),

		array(
			'name' => 'Contact Form 7',
			'slug' => 'contact-form-7',
			'required' => false
		),

		array(
			'name'      => 'WPBakery Visual Composer',
			'slug'      => 'js_composer',
			'source'    => get_template_directory() . '/inc/required-plugins/visual-composer/js_composer.zip',
			'required'  => true,
		)
		
	);

	$config = array(
		'id'           => 'bitstarter',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config ); 

} ?>