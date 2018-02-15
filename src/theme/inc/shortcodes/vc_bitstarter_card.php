<?php

class WPBakeryShortCode_Bitstarter_Card extends  WPBakeryShortCode
{

    public function hex2rgb($color){
        list($r, $g, $b) = array($color[0].$color[1],
        $color[2].$color[3],
        $color[4].$color[5]);
        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
        return array($r, $g, $b);
    }
    
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $bg_color1 = $bg_color2 = $css = $title_color = $line_color = $title = $shadow = '';

        extract(shortcode_atts(array(
            'title'        => 'Tokens',
            'bg_color1'    => '#00eaf8',
            'bg_color2'    => '#6d42ef',
            'title_color'  => '#ffffff',
            'line_color'   => '#e1e9f0',
            'shadow'       => '#474e65',
            'css'          => '',

        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
      

        $output = '';

        

        $output .= '<div class="bitstarterCard"> <div class="bitstarterCard__in '. $css_class.'"
        style="box-shadow: 0 20px 80px rgba(' . esc_attr(implode(',', $this->hex2rgb(str_replace('#','',$shadow)))) . ', 0.4); background-image: linear-gradient(134deg, ' . esc_attr($bg_color1) . ' 0%, ' . esc_attr($bg_color2) . ' 100%)"
        >';
                
        $output .= '<div class="bitstarterCard__title" style="color: ' . esc_attr($title_color) . '"><span>' . wp_kses( $title, bitstarter_allowed_html()) . '</span></div>';
    
        $output .= '<div class="bitstarterCard__content" style="border-color: ' . esc_attr($line_color) . '">' . wpb_js_remove_wpautop($content, true) . '</div>';

        $output .= '</div></div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Detailed Card', 'bitstarter' ),
    'base'		=> 'bitstarter_card',
    'controls'	=> 'edit_popup_delete',    
    'icon'      => get_template_directory_uri() . '/assets/img/vc/bitstarter_card.png',
    'category'  => esc_html__( 'Developed for Bitstarter', 'bitstarter' ),
    'params'	=> array(

        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title', 'bitstarter' ),
            'param_name' => 'title',
            'value' => esc_html__( 'Tokens', 'bitstarter')
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Color of title', 'bitstarter' ),
            'param_name' => 'title_color',
            "value" => '#ffffff',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Color of the line leftward from the title', 'bitstarter' ),
            'param_name' => 'line_color',
            "value" => '#e1e9f0',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Background color (first value to make a gradient)', 'bitstarter' ),
            'param_name' => 'bg_color1',
            "value" => '#00eaf8',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Background color (second value to make a gradient)', 'bitstarter' ),
            'param_name' => 'bg_color2',
            "value" => '#6d42ef',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Shadow of container', 'bitstarter' ),
            'param_name' => 'shadow',
            "value" => '#474e65',
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => esc_html__( 'Content', 'bitstarter' ),
            'param_name' => 'content',
            'description' => esc_html__( 'Select image', 'bitstarter' ),
            'value' => __( '
            <table> 
                <tr><td>Token:</td><td>CC</td></tr>
                <tr><td>Maximum amount of tokens to be issued:</td><td>30 000 000 CC</td></tr>
                <tr><td>Token type:</td><td>ERC20</td></tr>
            </table>',
             'bitstarter' ),
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
new WPBakeryShortCode_Bitstarter_Card($opts);