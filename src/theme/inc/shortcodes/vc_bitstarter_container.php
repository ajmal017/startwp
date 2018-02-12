<?php

class WPBakeryShortCode_Bitstarter_Slider_Container extends  WPBakeryShortCodesContainer
{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $slidestoshow = $slidestoscroll = $dots = $centermode = $arrows = $css = $focusonselect = $adaptiveheight = $autoplay = $autoplayspeed = $centerpadding = $draggable = $fadeel = $variablewidth = $vertical = $speed  = "";

        $prefix = 'bitstarter__slider-';

        extract(shortcode_atts(array(
            'css'   => '',
            'slidestoshow' => 1,
            'slidestoscroll' => 1,
            'dots' => 'yes',
            'centermode' => '',
            'focusonselect' => '',
            'arrows' => 'yes',
            'adaptiveheight' => '',
            'autoplay' => '',
            'autoplayspeed' => 3000,
            'centerpadding' => 50,
            'draggable' => 'yes',
            'fadeel' => '',
            'variablewidth' => '',
            'vertical' => '',
            'speed' => 300
        ), $atts));


        $id = bitstarter_get_unique_id();
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

        $output = '';

        $output .= '<div class="bitstarter__slider " 
                    data-slider=\'{"id" : "'. $prefix . esc_attr($id) .'","slidesToShow" : "'. esc_attr($slidestoshow) .'","slidesToScroll" : "'. esc_attr($slidestoscroll) .'","dots" : "'. esc_attr($dots) .'", "centerMode" : "'. esc_attr($centermode) .'","focusOnSelect" : "'. esc_attr($focusonselect) .'","arrows" : "'. esc_attr($arrows) .'","adaptiveHeight" : "'. esc_attr($adaptiveheight) .'","autoplay" : "'. esc_attr($autoplay) .'","autoplaySpeed" : "'. esc_attr($autoplayspeed) .'","centerPadding" : "'. esc_attr($centerpadding) .'","draggable" : "'. esc_attr($draggable) .'","fade" : "'. esc_attr($fadeel) .'","variableWidth" : "'. esc_attr($variablewidth) .'","vertical" : "'. esc_attr($vertical) .'","speed" : "'. esc_attr($speed) .'"}\' >';
        $output .= '    <div  id=bitstarter__slider-'. esc_attr($id) .' class="bitstarter__slider-in">';
        $output .=          do_shortcode($content);
        $output .= '    </div>';
        $output .= '</div>';

        return $output;
    }
}

$opts = array(
    'name' => esc_html__('Slider Container', 'bitstarter'),
    'base' => 'bitstarter_slider_container',
    'show_settings_on_create' => true,
    'is_container' => true,
    'as_parent' => array('only' => 'bitstarter_testimonials'),
    'category'  => esc_html__( 'Developed for Bitstarter', 'bitstarter' ),
    'icon' => get_template_directory_uri() . '/assets/img/vc/bitstarter_container.png',
    'js_view' => 'VcColumnView',
    'params' => array(
        
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Slides To Show', 'bitstarter' ),
            'param_name' => 'slidestoshow',
            'description' => esc_html__( 'Enter a number of slides to show on screen (min - 1)', 'bitstarter' ),
            'value' => 1
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Slides To Scroll', 'bitstarter' ),
            'param_name' => 'slidestoscroll',
            'description' => esc_html__( 'Enter a number of slides to scroll (min - 1)', 'bitstarter' ),
            'value' => 1
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Speed', 'bitstarter' ),
            'param_name' => 'speed',
            'description' => esc_html__( 'Slide/Fade animation speed in milliseconds( ex. 500)', 'bitstarter' ),
            'value' => 300,
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Dots?', 'bitstarter' ),
            'param_name' => 'dots',
            'description' => esc_html__( 'Show dot indicators?', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'yes',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Center mode?', 'bitstarter' ),
            'param_name' => 'centermode',
            'description' => esc_html__( 'Enables centered view with partial prev/next slides. Use with odd numbered Slides To Show counts', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Set Center Padding', 'bitstarter' ),
            'param_name' => 'centerpadding',
            'description' => esc_html__( 'Side padding when in center mode in px (min - 0)', 'bitstarter' ),
            'value' => 50,
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Focus On Select?', 'bitstarter' ),
            'param_name' => 'focusonselect',
            'description' => esc_html__( 'Enables centered view with partial prev/next slides. Use with odd numbered Slides To Show counts', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Arrows?', 'bitstarter' ),
            'param_name' => 'arrows',
            'description' => esc_html__( 'Prev/Next Arrows', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'yes',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Adaptive Height?', 'bitstarter' ),
            'param_name' => 'adaptiveheight',
            'description' => esc_html__( 'Enables adaptive height for single slide horizontal carousels.', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'autoplay?', 'bitstarter' ),
            'param_name' => 'autoplay',
            'description' => esc_html__( 'Enables Autoplay', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'bitstarter_range',
            'heading' => esc_html__( 'Autoplay Speed', 'bitstarter' ),
            'param_name' => 'autoplayspeed',
            'description' => esc_html__( 'Enter amount of time in milliseconds to slide change(ex. 3000)', 'bitstarter' ),
            'value' => 3000,
        ),

        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Draggable?', 'bitstarter' ),
            'param_name' => 'draggable',
            'description' => esc_html__( 'Enable mouse dragging', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'yes',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Fade?', 'bitstarter' ),
            'param_name' => 'fadeel',
            'description' => esc_html__( 'Enable fade', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Variable Width?', 'bitstarter' ),
            'param_name' => 'variablewidth',
            'description' => esc_html__( 'Variable width slides', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Vertical?', 'bitstarter' ),
            'param_name' => 'vertical',
            'description' => esc_html__( 'Vertical slide mode', 'bitstarter' ),
            'value' => array( esc_html__( 'Yes', 'bitstarter' ) => 'yes' ),
            'std' => 'no',
        ),

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitstarter' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitstarter' ),
        ),
    )
);
vc_map($opts);
new WPBakeryShortCode_Bitstarter_Slider_Container($opts);
