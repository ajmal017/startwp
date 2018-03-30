<?php

/**
 * Extend VC Empty Space
 */

vc_remove_param( "vc_empty_space", "height" );
vc_remove_param( "vc_empty_space", "el_id" );

$attributes = array(
    'type' => 'bitstarter_responsive_param',
    'heading' => esc_html__( 'Responsive Options', 'bitstarter' ),
    'param_name' => 'bitstarter_responsive_height',
    'params' => array(
        'height' => array(
            'type' => 'size',
            'heading' => esc_html__( 'Height', 'bitstarter'),
        ),
    ),
    'description' => esc_html__( 'Adjust block height for different screen sizes.', 'bitstarter' )
);
vc_add_param( 'vc_empty_space', $attributes );

$attributes = array(
    'type' => 'bitstarter_helptext',
    'heading' => esc_html__( 'For layouts responsiveness below are some CSS classes to display this shortcode only on certain devices.', 'bitstarter' ),
    'param_name' => 'empty_space_bitstarter_helptext',
    'description' => sprintf('
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p>
                              <p><b>%s</b> - %s</p>
                              <p><b>%s</b> - %s</p>
                              <small>%s<small/>',
                    esc_html__( 'only-xs', 'bitstarter' ),
                    esc_html__( 'Extra small devices (portrait phones, less than 576px)', 'bitstarter' ),
                    esc_html__( 'only-sm', 'bitstarter' ),
                    esc_html__( 'Small devices (landscape phones, 576px and up)', 'bitstarter' ),
                    esc_html__( 'only-md', 'bitstarter' ),
                    esc_html__( 'Medium devices (tablets, 768px and up)', 'bitstarter' ),
                    esc_html__( 'only-lg', 'bitstarter' ),
                    esc_html__( 'Large devices (desktops, 900px and up)', 'bitstarter' ),
                    esc_html__( 'only-xl', 'bitstarter' ),
                    esc_html__( 'Extra large devices (large desktops, 1200px and up)', 'bitstarter' ),
                    esc_html__( 'Put some css class(e.g. only-sm) in the Extra class name field above, in case you have to add many class separate them by empty space', 'bitstarter' )
                )
);
//vc_add_param( 'vc_empty_space', $attributes );




/**
 * Extend VC Column
 */
$attributes = array(
    'type' => 'bitstarter_helptext',
    'heading' => esc_html__( 'Text utils CSS class you may use to align some inline element(e.g. buttons)', 'bitstarter' ),
    'param_name' => 'empty_vc_column_helptext',
    'description' => sprintf('
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p>
                              <small>%s<small/>',
                    esc_html__( 'u-text-left', 'bitstarter' ),
                    esc_html__( 'Align inline element, that inside container(like column) to the left', 'bitstarter' ),
                    esc_html__( 'u-text-center', 'bitstarter' ),
                    esc_html__( 'Align inline element, that inside container(like column) to the center', 'bitstarter' ),
                    esc_html__( 'u-text-right', 'bitstarter' ),
                    esc_html__( 'AAlign inline element, that inside container(like column) to the right', 'bitstarter' ),
                    esc_html__( 'Put some css class(e.g. u-text-center) in the Extra class name field above, in case you have to add many class separate them by empty space', 'bitstarter' )
                    )
);
vc_add_param( 'vc_column', $attributes );