<?php

/**
 * @param $atts
 *
 * @return mixed
 */
function bitstarter_add_search_lang_params( $atts = null ) {
	global $sitepress;
	if ( !empty( $_GET['lang'] ) && $sitepress->get_current_language() !==  $sitepress->get_default_language() ) {
		echo '<input type="hidden" name="lang" value="' . $sitepress->get_current_language() . '">';
	}

	return $atts;
}
add_action('job_manager_job_filters_start', 'bitstarter_add_search_lang_params');
add_action('bitstarter_header_search_hidden_fields', 'bitstarter_add_search_lang_params');