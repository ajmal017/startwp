<?php

if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
	define( 'WP_LOAD_IMPORTERS', true );
}

// Load Importer API file
require_once ABSPATH . 'wp-admin/includes/import.php';
//no errors yet
$Iondigital_importerError = false;
//the path to the demo files including the file name without the extension


//check if wp_importer, the base importer class is available, otherwise include it
if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) ) {
		require_once( $class_wp_importer );
	} else {
		$Iondigital_importerError = true;
	}
}

//check if the wp import class is available, this class handles the wordpress XML files. If not, include it
if ( ! class_exists( 'Iondigital_WP_Import' ) ) {
	$class_wp_import =  ABSPATH . 'wp-content/plugins/iondigital-kit/inc/import/wordpress-importer/wordpress-importer.php';
	if ( file_exists( $class_wp_import ) ) {
		require_once( $class_wp_import );
	} else {
		$Iondigital_importerError = true;
	}
}

if ( $Iondigital_importerError !== false ) {
	$response['id'] = new WP_Error( 'import_posts_pages_noscript', 'The Auto importing script could not be loaded. Please use the <a href="' . admin_url( 'import.php' ) . '">WordPress default import</a> and import the .XML file provided in the archive you\'ve received on purchase manually.' );
} else {
	if ( class_exists( 'Iondigital_WP_Import' ) ) {
		include_once( 'wordpress-importer/iondigital-import-class.php' );
	}
	if ( ! is_file( $import_filepath . '.xml' ) ) {
		$response['id'] = new WP_Error( 'import_posts_pages_nofile', 'The XML file containing the demo data could not be found or could not be read in <pre>' . $import_filepath . '</pre> You might want to try to set the file permission to 777.<br/>If this doesn\'t work please use the <a href="' . admin_url( 'import.php' ) . '">WordPress default import</a> and import the .XML file provided in the archive you\'ve received on purchase manually.' );
	} else {
		ob_start();
		$wp_import                    = new Iondigital_import();
		$wp_import->fetch_attachments = true;
		$response['id']               = $wp_import->import_posts_pages( $import_filepath . '.xml', $import_filepath . '.php', $response['supplemental']['stepNumber'], $response['supplemental']['numberOfSteps'] );
		//after the last step we assign the menus to the proper locations
		if ( $response['supplemental']['stepNumber'] == 1 ) {
			$wp_import->set_menus( $import_filepath . '.php' );
		}
		$response['data'] = ob_get_contents();
		ob_end_clean();
	}
}