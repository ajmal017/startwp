<?php

class WPBakeryShortCode_Bitcoin_Slider_Container extends  WPBakeryShortCodesContainer
{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $slidestoshow = $slidestoscroll = $dots = $centermode = $arrows = $css = $focusonselect = $adaptiveheight = $autoplay = $autoplayspeed = $centerpadding = $draggable = $fadeel = $variablewidth = $vertical = $speed  = "";

        $prefix = 'bitcoin__slider-';

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


        $id = bitcoin_get_unique_id();
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

        $output = '';

        $output .= '<div class="bitcoin__slider " 
                    data-slider=\'{"id" : "'. $prefix . $id .'","slidesToShow" : "'. $slidestoshow .'","slidesToScroll" : "'. $slidestoscroll .'","dots" : "'. $dots .'", "centerMode" : "'. $centermode .'","focusOnSelect" : "'. $focusonselect .'","arrows" : "'. $arrows .'","adaptiveHeight" : "'. $adaptiveheight .'","autoplay" : "'. $autoplay .'","autoplaySpeed" : "'. $autoplayspeed .'","centerPadding" : "'. $centerpadding .'","draggable" : "'. $draggable .'","fade" : "'. $fadeel .'","variableWidth" : "'. $variablewidth .'","vertical" : "'. $vertical .'","speed" : "'. $speed .'"}\' >';
        $output .= '    <div  id=bitcoin__slider-'. $id .' class="bitcoin__slider-in">';
        $output .=          do_shortcode($content);
        $output .= '    </div>';
        $output .= '</div>';

        return $output;
    }
}

$opts = array(
    'name' => esc_html__('Slider Container', 'bitcoin'),
    'base' => 'bitcoin_slider_container',
    'show_settings_on_create' => true,
    'is_container' => true,
    'as_parent' => array('only' => 'bitcoin_testimonials'),
    'category'  => esc_html__( 'Developed for Bitcoin', 'bitcoin' ),
    'icon' => get_template_directory_uri() . '/assets/img/vc/bitcoin_container.png',
    'js_view' => 'VcColumnView',
    'params' => array(
        
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Slides To Show', 'bitcoin' ),
            'param_name' => 'slidestoshow',
            'description' => esc_html__( 'Enter a number of slides to show on screen (min - 1)', 'bitcoin' ),
            'value' => 1
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Slides To Scroll', 'bitcoin' ),
            'param_name' => 'slidestoscroll',
            'description' => esc_html__( 'Enter a number of slides to scroll (min - 1)', 'bitcoin' ),
            'value' => 1
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Speed', 'bitcoin' ),
            'param_name' => 'speed',
            'description' => esc_html__( 'Slide/Fade animation speed in milliseconds( ex. 500)', 'bitcoin' ),
            'value' => 300,
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Dots?', 'bitcoin' ),
            'param_name' => 'dots',
            'description' => esc_html__( 'Show dot indicators?', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'yes',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Center mode?', 'bitcoin' ),
            'param_name' => 'centermode',
            'description' => esc_html__( 'Enables centered view with partial prev/next slides. Use with odd numbered Slides To Show counts', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Set Center Padding', 'bitcoin' ),
            'param_name' => 'centerpadding',
            'description' => esc_html__( 'Side padding when in center mode in px (min - 0)', 'bitcoin' ),
            'value' => 50,
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Focus On Select?', 'bitcoin' ),
            'param_name' => 'focusonselect',
            'description' => esc_html__( 'Enables centered view with partial prev/next slides. Use with odd numbered Slides To Show counts', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Arrows?', 'bitcoin' ),
            'param_name' => 'arrows',
            'description' => esc_html__( 'Prev/Next Arrows', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'yes',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Adaptive Height?', 'bitcoin' ),
            'param_name' => 'adaptiveheight',
            'description' => esc_html__( 'Enables adaptive height for single slide horizontal carousels.', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'autoplay?', 'bitcoin' ),
            'param_name' => 'autoplay',
            'description' => esc_html__( 'Enables Autoplay', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'bitcoin_range',
            'heading' => esc_html__( 'Autoplay Speed', 'bitcoin' ),
            'param_name' => 'autoplayspeed',
            'description' => esc_html__( 'Enter amount of time in milliseconds to slide change(ex. 3000)', 'bitcoin' ),
            'value' => 3000,
        ),

        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Draggable?', 'bitcoin' ),
            'param_name' => 'draggable',
            'description' => esc_html__( 'Enable mouse dragging', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'yes',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Fade?', 'bitcoin' ),
            'param_name' => 'fadeel',
            'description' => esc_html__( 'Enable fade', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Variable Width?', 'bitcoin' ),
            'param_name' => 'variablewidth',
            'description' => esc_html__( 'Variable width slides', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Vertical?', 'bitcoin' ),
            'param_name' => 'vertical',
            'description' => esc_html__( 'Vertical slide mode', 'bitcoin' ),
            'value' => array( esc_html__( 'Yes', 'bitcoin' ) => 'yes' ),
            'std' => 'no',
        ),

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitcoin' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitcoin' ),
        ),
    )
);
vc_map($opts);
new WPBakeryShortCode_Bitcoin_Slider_Container($opts);
