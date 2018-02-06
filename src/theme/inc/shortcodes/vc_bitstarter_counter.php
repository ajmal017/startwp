<?php

class WPBakeryShortCode_Bitstarter_Counter extends  WPBakeryShortCode
{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */

    public function content($atts, $content = null)
    {
        $image = $percentage = $label = $number_color = $text_color = $counter_bg_color = $counter_active_color = $css = $desc = '';

        extract(shortcode_atts(array(
            'image'         => '',
            'label'         => '',
            'desc'          => '',
            'css'           => '',
            'number_color'  => '#32325d',
            'text_color'    => '#6b7c93',
        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

        $output = '';
        $percentage = intval($percentage);

        $layout = 'text';
        if( empty( $percentage ) && $layout != 'text' )
            return $output;

        $output .= '<div class="bitstarter-counter wpb_content_element bitstarter-counter--'. $layout .' ' .$css_class. '" data-percentage="'. $percentage .'" data-bg-color="'. $counter_bg_color .'" data-active-color="'. $counter_active_color .'" data-type="'. $layout .'"  style="color: '.$text_color.'">';

        if($layout == 'pie'):

            $output .= '<div class="bitstarter-counter__wrap">';
            $output .= '    <div class="bitstarter-counter-holder__square"></div>';
            $output .= '    <div class="bitstarter-counter__holder">';
            $output .= '        <svg width="400" height="400" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">';
            $output .= '            <circle stroke-width="10" stroke="'. $counter_bg_color.'" fill="none" cx="200" cy="200" r="195" class="part"/>';
            $output .= '            <circle stroke-width="11" stroke="'.$counter_active_color.'" fill="none" cx="200" cy="200" r="195" class="part active"/>';
            $output .= '        </svg>';
            $output .= '    </div>';
            $output .= '    <div class="bitstarter-counter-label__wrap">';
            $output .= '        <div class="bitstarter-counter-label__wrap__data">' . preg_replace( '#([0-9]+)#', "<span data-number=\"$1\" class=\"label__number\">0</span>", esc_html($label) ) . '</div>';
            $output .= '    </div>';
            $output .= '</div>';
            $output .= '<div class="bitstarter-counter__description">' . esc_html( $desc ) . '</div>';

        elseif($layout == 'linear'):

            $output .= '<div class="bitstarter-counter__description">' . esc_html( $desc ) . '</div>';
            $output .= '<div class="bitstarter-counter__wrap">';
            $output .= '    <div class="bitstarter-counter__holder">';

            $output .= '        <svg width="400" height="10" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">';
            $output .= '            <line stroke-width="10" stroke="'.$counter_bg_color.'" fill="none" x1="0" y1="5" x2="400" y2="5" class="part"/>';
            $output .= '            <line stroke-width="10" stroke="'.$counter_active_color.'" fill="none" x1="0" y1="5" x2="400" y2="5" class="part active"/>';
            $output .= '        </svg>';
            $output .= '    </div>';
            $output .= '    <div class="bitstarter-counter-label__wrap">';
            $output .= '        <div class="bitstarter-counter-label__wrap__data">' . preg_replace( '#([0-9]+)#', "<span data-number=\"$1\" class=\"label__number\">0</span>", esc_html($label) ) . '</div>';
            $output .= '    </div>';
            $output .= '</div>';

        else:

            $output .= '<div class="bitstarter-counter__wrap">';
            $output .= '<div class="bitstarter-counter__image">' .wp_get_attachment_image( $image, 'full') . '</div>';
            $output .= '    <div class="bitstarter-counter-label__wrap">';
            $output .= '        <div class="bitstarter-counter-label__wrap__data" style="color:'.$number_color.'">' . preg_replace( '#([0-9]+)#', "<span data-number=\"$1\" class=\"label__number\">0</span>", esc_html($label) ) . '</div>';
            $output .= '    </div>';
            $output .= '</div>';
            $output .= '<div class="bitstarter-counter__description"  style="color:'.$text_color.'">' . esc_html( $desc ) . '</div>';

        endif;

        $output .= '</div>';

        return $output;
    }
}


$opts = array(
    'name'		    => esc_html__( 'Dynamic Counter', 'bitstarter'),
    'base'		    => 'bitstarter_counter',
    'controls'	    => 'edit_popup_delete',
    'icon'          => get_template_directory_uri() . '/assets/img/vc/bitstarter_counter.png',
    'category'		=> esc_html__('Developed for Bitstarter', 'bitstarter'),
    'params'		=> array(

        // array(
        //     'type' => 'dropdown',
        //     'heading' => esc_html__('Layout Type', 'bitstarter'),
        //     'param_name' => 'layout',
        //     'description' => esc_html__('Choose counter layout.', 'bitstarter'),
        //     'value'   => array(
        //         esc_html__( 'Pie Counter', 'bitstarter' )  => 'pie',
        //         esc_html__( 'Linear Counter', 'bitstarter' ) => 'linear',
        //         esc_html__( 'Dynamic Text Numbers', 'bitstarter' ) => 'text',
        //     )
        // ),
        // array(
        //     'type' => 'textarea',
        //     'heading' => esc_html__( 'Progress percentage(ex. 40%)', 'bitstarter' ),
        //     'param_name' => 'percentage',
        //     'dependency' => array(
        //         'element' => 'layout',
        //         'value' => array( 'pie', 'linear' ),
        //     ),
        //     'value' => '40%',
        // ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'bitstarter' ),
            'param_name' => 'image',
            'admin_label'=> true,
            'vc_single_param_edit_holder_class'=> array("vc_col-xs-12","wpb_el_type_attach_image","vc_wrapper-param-type-attach_image","vc_shortcode-param","vc_column"),
            'description' => esc_html__( 'Select image', 'bitstarter' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Label(Ex. 100)', 'bitstarter' ),
            'description' => esc_html__('Set numeric values, will be dynamically changed.', 'bitstarter'),
            'param_name' => 'label',
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__( 'Description(Ex. Days since the start)', 'bitstarter' ),
            'param_name' => 'desc',
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitstarter' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitstarter' ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Label color', 'bitstarter' ),
            'param_name' => 'number_color',
            "value" => '#32325d',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Description color', 'bitstarter' ),
            'param_name' => 'text_color',
            "value" => '#6b7c93',
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitstarter_Counter($opts);