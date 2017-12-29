<?php

/**
 * Extend VC Empty Space
 */


$attributes = array(
    'type' => 'bitcoin_helptext',
    'heading' => esc_html__( 'For layouts responsiveness below are some CSS classes to display this shortcode only on certain devices.', 'bitcoin' ),
    'param_name' => 'empty_space_bitcoin_helptext',
    'description' => sprintf('
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p>
                              <p><b>%s</b> - %s</p>
                              <p><b>%s</b> - %s</p>
                              <small>%s<small/>',
                    esc_html__( 'only-xs', 'bitcoin' ),
                    esc_html__( 'Extra small devices (portrait phones, less than 576px)', 'bitcoin' ),
                    esc_html__( 'only-sm', 'bitcoin' ),
                    esc_html__( 'Small devices (landscape phones, 576px and up)', 'bitcoin' ),
                    esc_html__( 'only-md', 'bitcoin' ),
                    esc_html__( 'Medium devices (tablets, 768px and up)', 'bitcoin' ),
                    esc_html__( 'only-lg', 'bitcoin' ),
                    esc_html__( 'Large devices (desktops, 900px and up)', 'bitcoin' ),
                    esc_html__( 'only-xl', 'bitcoin' ),
                    esc_html__( 'Extra large devices (large desktops, 1200px and up)', 'bitcoin' ),
                    esc_html__( 'Put some css class(e.g. only-sm) in the Extra class name field above, in case you have to add many class separate them by empty space', 'bitcoin' )
                    )
);
vc_add_param( 'vc_empty_space', $attributes );


/**
 * Extend VC Column
 */
$attributes = array(
    'type' => 'bitcoin_helptext',
    'heading' => esc_html__( 'Text utils CSS class you may use to align some inline element(e.g. buttons)', 'bitcoin' ),
    'param_name' => 'empty_vc_column_helptext',
    'description' => sprintf('
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p> 
                              <p><b>%s</b> - %s</p>
                              <small>%s<small/>',
                    esc_html__( 'u-text-left', 'bitcoin' ),
                    esc_html__( 'Align inline element, that inside container(like column) to the left', 'bitcoin' ),
                    esc_html__( 'u-text-center', 'bitcoin' ),
                    esc_html__( 'Align inline element, that inside container(like column) to the center', 'bitcoin' ),
                    esc_html__( 'u-text-right', 'bitcoin' ),
                    esc_html__( 'AAlign inline element, that inside container(like column) to the right', 'bitcoin' ),
                    esc_html__( 'Put some css class(e.g. u-text-center) in the Extra class name field above, in case you have to add many class separate them by empty space', 'bitcoin' )
                    )
);
vc_add_param( 'vc_column', $attributes );