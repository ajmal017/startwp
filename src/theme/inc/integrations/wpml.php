<?php

/**
 * @param $atts
 *
 * @return mixed
 */
function bitcoin_add_search_lang_params( $atts = null ) {
	global $sitepress;
	if ( !empty( $_GET['lang'] ) && $sitepress->get_current_language() !==  $sitepress->get_default_language() ) {
		echo '<input type="hidden" name="lang" value="' . $sitepress->get_current_language() . '">';
	}

	return $atts;
}
add_action('job_manager_job_filters_start', 'bitcoin_add_search_lang_params');
add_action('bitcoin_header_search_hidden_fields', 'bitcoin_add_search_lang_params');