<?php

class WPBakeryShortCode_Bitstarter_Distribution extends  WPBakeryShortCode
{

    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $param_to_parse = $css = '';

        extract(shortcode_atts(array(
            'param_to_parse'         => '#fc3868|23,#ffd529|1,#47f15a|20,#9575f4|12,#c24078|24,#3023ae|6,#00eaf8|14',
            'css'           => '',

        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
      
        

        $output = '';

        $colorValuePairArray = explode(',', $param_to_parse);
        if( !count($colorValuePairArray) ){
            return '';
        }

        $line_html = $line_perc = '';
        foreach ($colorValuePairArray as $key => $value) {
            $part = explode('|', $value);
            $percent = preg_replace( '/[^\d]/', '', $part[1] );
            $line_html .= '<div style="background-color: ' . esc_attr($part[0]) . '; width: ' . esc_attr($percent) . '%"> </div>';
            $line_perc .= '<div style="width: ' . esc_attr($percent) . '%"> <span>' .  $percent . '%</span></div>';
        }
        
        $output .= '<div class="bitstarterDistibution ' . $css_class . '" >';
                
        $output .= '<div class="bitstarterDistibution__line">' . $line_html . '</div>';
        $output .= '<div class="bitstarterDistibution__percentage">' . $line_perc . '</div>';

        $output .= '</div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Percentage Distribution', 'bitstarter' ),
    'base'		=> 'bitstarter_distribution',
    'controls'	=> 'edit_popup_delete',
    'icon'      => get_template_directory_uri() . '/assets/img/vc/bitstarter_distribution.png',
    'category'  => esc_html__( 'Developed for Bitstarter', 'bitstarter' ),
    'params'	=> array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Distribution', 'bitstarter' ),
            'param_name' => 'param_to_parse',
            'value' =>  esc_html__( '#fc3868|23,#ffd529|1,#47f15a|20,#9575f4|12,#c24078|24,#3023ae|6,#00eaf8|14', 'bitstarter' ),
            'description' =>  esc_html__( 'Enter the params of distribution line in format "color|value,color|value", where color is in hex format (ex. #000000) and value is a number (ex. 10)', 'bitstarter' )
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
new WPBakeryShortCode_Bitstarter_Distribution($opts);