<?php

class WPBakeryShortCode_Bitcoin_Testimonials extends  WPBakeryShortCode
{
 
    public function content($atts, $content = null)
    {
        $color_text = $image  = $css = '';

        extract(shortcode_atts(array(
            'image' => '',
            'css' => '',

        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
      

        $output = '';

        //var_dump($content);


        $output .= '<div class="testimonials"> <div class="testimonials__in '.$css_class.'">';
                
        $output .= '<div class="testimonials__image">' .wp_get_attachment_image( $image, 'full') . '</div>';
    
        $output .= '<div class="testimonials__content">' . $content . '</div>';

        $output .= '</div></div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Testimonials', 'bitcoin' ),
    'base'		=> 'bitcoin_testimonials',
    'controls'	=> 'edit_popup_delete',
    'category'  => esc_html__( 'Developed for Bitcoin', 'bitcoin' ),
    'icon' => get_template_directory_uri() . '/assets/img/vc/bitcoin_testimonials.png',
    'params'	=> array(

        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'bitcoin' ),
            'param_name' => 'image',
            'admin_label'=> true,
            'vc_single_param_edit_holder_class'=> array("vc_col-xs-12","wpb_el_type_attach_image","vc_wrapper-param-type-attach_image","vc_shortcode-param","vc_column"),
            'description' => esc_html__( 'Select image', 'bitcoin' )
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => esc_html__( 'Content', 'bitcoin' ),
            'param_name' => 'content',
            'description' => esc_html__( 'Select image', 'bitcoin' ),
            'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' ),
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
new WPBakeryShortCode_Bitcoin_Testimonials($opts);