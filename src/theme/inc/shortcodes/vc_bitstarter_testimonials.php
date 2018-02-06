<?php

class WPBakeryShortCode_Bitstarter_Testimonials extends  WPBakeryShortCode
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

        $output .= '<div class="testimonials"> <div class="testimonials__in '.$css_class.'">';
                
        $output .= '<div class="testimonials__image">' .wp_get_attachment_image( $image, 'full') . '</div>';
    
        $output .= '<div class="testimonials__content">' .  wpb_js_remove_wpautop($content, true) . '</div>';

        $output .= '</div></div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Testimonials', 'bitstarter' ),
    'base'		=> 'bitstarter_testimonials',
    'controls'	=> 'edit_popup_delete',
    'category'  => esc_html__( 'Developed for Bitstarter', 'bitstarter' ),
    'icon' => get_template_directory_uri() . '/assets/img/vc/bitstarter_testimonials.png',
    'params'	=> array(

        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'bitstarter' ),
            'param_name' => 'image',
            'admin_label'=> true,
            'vc_single_param_edit_holder_class'=> array("vc_col-xs-12","wpb_el_type_attach_image","vc_wrapper-param-type-attach_image","vc_shortcode-param","vc_column"),
            'description' => esc_html__( 'Select image', 'bitstarter' )
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => esc_html__( 'Content', 'bitstarter' ),
            'param_name' => 'content'
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
new WPBakeryShortCode_Bitstarter_Testimonials($opts);