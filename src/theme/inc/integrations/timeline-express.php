<?php

/**
 * Timeline Express compatibility
 * WE ensure that Bitstarter plays nicely with Timeline Express
 * See: https://wordpress.org/plugins/timeline-express/
 *
 * @package Bitstarter
 */

if ( ! function_exists( 'bitstarter_timeline_express_options' ) ) {
	function bitstarter_timeline_express_options( $options ) {

        array_merge($options, array(
            'read-more-visibility'                        =>  false,
            'default-announcement-color'                  => '#75CE66',
            'announcement-box-shadow-color'               => '#B9C5CD',
            'announcement-background-line-color'          => '#D7E4ED',
            'announcement-bg-color'                       => '#d7e4ed'
        ));
    
		return $options;
	}
}
add_filter( 'timeline_express_options', 'bitstarter_timeline_express_options', 100, 1 );
