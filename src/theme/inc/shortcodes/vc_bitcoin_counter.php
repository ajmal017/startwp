<?php

class WPBakeryShortCode_Bitcoin_Counter extends  WPBakeryShortCode
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

        $output .= '<div class="bitcoin-counter wpb_content_element bitcoin-counter--'. $layout .' ' .$css_class. '" data-percentage="'. $percentage .'" data-bg-color="'. $counter_bg_color .'" data-active-color="'. $counter_active_color .'" data-type="'. $layout .'"  style="color: '.$text_color.'">';

        if($layout == 'pie'):

            $output .= '<div class="bitcoin-counter__wrap">';
            $output .= '    <div class="bitcoin-counter-holder__square"></div>';
            $output .= '    <div class="bitcoin-counter__holder">';
            $output .= '        <svg width="400" height="400" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">';
            $output .= '            <circle stroke-width="10" stroke="'. $counter_bg_color.'" fill="none" cx="200" cy="200" r="195" class="part"/>';
            $output .= '            <circle stroke-width="11" stroke="'.$counter_active_color.'" fill="none" cx="200" cy="200" r="195" class="part active"/>';
            $output .= '        </svg>';
            $output .= '    </div>';
            $output .= '    <div class="bitcoin-counter-label__wrap">';
            $output .= '        <div class="bitcoin-counter-label__wrap__data">' . preg_replace( '#([0-9]+)#', "<span data-number=\"$1\" class=\"label__number\">0</span>", esc_html($label) ) . '</div>';
            $output .= '    </div>';
            $output .= '</div>';
            $output .= '<div class="bitcoin-counter__description">' . esc_html( $desc ) . '</div>';

        elseif($layout == 'linear'):

            $output .= '<div class="bitcoin-counter__description">' . esc_html( $desc ) . '</div>';
            $output .= '<div class="bitcoin-counter__wrap">';
            $output .= '    <div class="bitcoin-counter__holder">';

            $output .= '        <svg width="400" height="10" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">';
            $output .= '            <line stroke-width="10" stroke="'.$counter_bg_color.'" fill="none" x1="0" y1="5" x2="400" y2="5" class="part"/>';
            $output .= '            <line stroke-width="10" stroke="'.$counter_active_color.'" fill="none" x1="0" y1="5" x2="400" y2="5" class="part active"/>';
            $output .= '        </svg>';
            $output .= '    </div>';
            $output .= '    <div class="bitcoin-counter-label__wrap">';
            $output .= '        <div class="bitcoin-counter-label__wrap__data">' . preg_replace( '#([0-9]+)#', "<span data-number=\"$1\" class=\"label__number\">0</span>", esc_html($label) ) . '</div>';
            $output .= '    </div>';
            $output .= '</div>';

        else:

            $output .= '<div class="bitcoin-counter__wrap">';
            $output .= '<div class="bitcoin-counter__image">' .wp_get_attachment_image( $image, 'full') . '</div>';
            $output .= '    <div class="bitcoin-counter-label__wrap">';
            $output .= '        <div class="bitcoin-counter-label__wrap__data" style="color:'.$number_color.'">' . preg_replace( '#([0-9]+)#', "<span data-number=\"$1\" class=\"label__number\">0</span>", esc_html($label) ) . '</div>';
            $output .= '    </div>';
            $output .= '</div>';
            $output .= '<div class="bitcoin-counter__description"  style="color:'.$text_color.'">' . esc_html( $desc ) . '</div>';

        endif;

        $output .= '</div>';

        return $output;
    }
}


$opts = array(
    'name'		    => esc_html__( 'Dynamic Counter', 'bitcoin'),
    'base'		    => 'bitcoin_counter',
    'controls'	    => 'edit_popup_delete',
    'icon' => get_template_directory_uri() . '/assets/img/vc/bitcoin_counter.png',
    'category'		=> esc_html__('Developed for Bitcoin', 'bitcoin'),
    'params'		=> array(

        // array(
        //     'type' => 'dropdown',
        //     'heading' => esc_html__('Layout Type', 'bitcoin'),
        //     'param_name' => 'layout',
        //     'description' => esc_html__('Choose counter layout.', 'bitcoin'),
        //     'value'   => array(
        //         esc_html__( 'Pie Counter', 'bitcoin' )  => 'pie',
        //         esc_html__( 'Linear Counter', 'bitcoin' ) => 'linear',
        //         esc_html__( 'Dynamic Text Numbers', 'bitcoin' ) => 'text',
        //     )
        // ),
        // array(
        //     'type' => 'textarea',
        //     'heading' => esc_html__( 'Progress percentage(ex. 40%)', 'bitcoin' ),
        //     'param_name' => 'percentage',
        //     'dependency' => array(
        //         'element' => 'layout',
        //         'value' => array( 'pie', 'linear' ),
        //     ),
        //     'value' => '40%',
        // ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'bitcoin' ),
            'param_name' => 'image',
            'admin_label'=> true,
            'vc_single_param_edit_holder_class'=> array("vc_col-xs-12","wpb_el_type_attach_image","vc_wrapper-param-type-attach_image","vc_shortcode-param","vc_column"),
            'description' => esc_html__( 'Select image', 'bitcoin' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Dynamic Counter Label(Ex. 100)', 'bitcoin' ),
            'description' => esc_html__('Set numeric values, will be dynamically changed.', 'bitcoin'),
            'param_name' => 'label',
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__( 'Counter Description(Ex. Days since the start)', 'bitcoin' ),
            'param_name' => 'desc',
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitcoin' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitcoin' ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Dynamic Counter Label Color', 'bitcoin' ),
            'param_name' => 'number_color',
            "value" => '#32325d',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Counter Description Color', 'bitcoin' ),
            'param_name' => 'text_color',
            "value" => '#6b7c93',
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitcoin_Counter($opts);