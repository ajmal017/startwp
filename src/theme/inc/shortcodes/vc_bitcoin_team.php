<?php


class WPBakeryShortCode_Bitcoin_Team extends  WPBakeryShortCode
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
        $css = $shadow_color = $bg_color = $image = $name = $position = $excerpt = $position_color = $fb = $in ='';


        extract(shortcode_atts(array(
            'css'           => '',
            'image'         => '',
            'name'          => '',
            'position'      => '',
            'excerpt'       => '',
            'position_color'=> '#5e6ee0',
            'shadow_color'  => '#474e65',
            'bg_color'      => '#f6f9fc',
            'fb'            => '',
            'in'            => ''
        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

        $output = '';
        $shadow_color = implode(',', $this->hex2rgb(str_replace('#','',$shadow_color)));

        $extra_class = '';
        if(!empty($excerpt)):
            $extra_class = 'team__in--excerpt';
        endif;

        $output .= '<div class="team '.$css_class.' wpb_content_element"><div class="team__in ' . $extra_class . '" style="box-shadow: 0 20px 26px 10px rgba(' . $shadow_color . ',.12); background: ' . $bg_color . '">';
        $output .= '<div class="team__picture" style="box-shadow: 0 20px 26px rgba(' . $shadow_color . ',.2)">' . wp_get_attachment_image( $image, 'full') . '</div>';

        $output .= '<div class="team-info">';
        
        $output .=      '<h5 class="team-info__name">' . $name . '</h5>';
        $output .=      '<h5 class="team-info__position" style="color: ' . $position_color . '">' . $position . '</h5>';

        
        if( !empty($fb) || !empty($in) ):

            $output .= '<ul class="team-info__social">';

                if( !empty($fb) ):
                    $output .= '<li><a href="' . esc_url( $fb ) . '"><i class="bitcoin__icon">' . file_get_contents(locate_template('assets/svg/sc-team-fb.php')) . '</i></a></li>';
                endif;
                
                if( !empty($in) ):
                    $output .= '<li><a href="' . esc_url( $in ) . '"><i class="bitcoin__icon ">' .  file_get_contents(locate_template('assets/svg/sc-team-in.php')) . '</i></a></li>';
                endif;

            $output .= '</ul>';
        endif;
        $output .= '</div>';
        
        $output .= '<div class="team__excerpt">' . $excerpt . '</div>';

        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
}


$opts = array(
    'name'          => esc_html__( 'Team', 'bitcoin'),
    'base'          => 'bitcoin_team',
    'controls'      => 'edit_popup_delete',
    'icon'          => get_template_directory_uri() . '/assets/img/vc/bitcoin_team.png',
    'category'      => esc_html__('Developed for Bitcoin', 'bitcoin'),
    'params'        => array(
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'bitcoin' ),
            'param_name' => 'image',
            'admin_label'=> true,
            'vc_single_param_edit_holder_class'=> array("vc_col-xs-12","wpb_el_type_attach_image","vc_wrapper-param-type-attach_image","vc_shortcode-param","vc_column"),
            'description' => esc_html__( 'Select image (200x200)', 'bitcoin' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'First & last name', 'bitcoin' ),
            'param_name' => 'name'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Position held', 'bitcoin' ),
            'param_name' => 'position'
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Color of position held', 'bitcoin' ),
            'param_name' => 'position_color',
            "value" => '#5e6ee0',
        ),
        array(
            'type' => 'textarea',
            'heading' => esc_html__( 'Excerpt', 'bitcoin' ),
            'param_name' => 'excerpt',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Shadows in card', 'bitcoin' ),
            'param_name' => 'shadow_color',
            "value" => '#474e65',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Background color', 'bitcoin' ),
            'param_name' => 'bg_color',
            "value" => '#f6f9fc',
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Facebok (optional)', 'bitcoin' ),
            'param_name' => 'fb',
            'group' => esc_html__( 'Social networks', 'bitcoin' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Linkedin (optional)', 'bitcoin' ),
            'param_name' => 'in',
            'group' => esc_html__( 'Social networks', 'bitcoin' ),
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitcoin' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitcoin' ),
        )
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitcoin_Team($opts);