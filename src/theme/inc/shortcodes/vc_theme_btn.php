<?php

class WPBakeryShortCode_ForIT_Theme_Btn extends  WPBakeryShortCode
{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $contact_form = $link = $modal = $type = $title = $link = $color = $align = $size = $css = $css_animation = '';
        $add_icon = $i_align = $icon_lib = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypo = $icon_linecons = '';
        $output = '';

        $css_classes = array();
        $wrap_classes = array();

        extract(shortcode_atts(array(
            'link' => '',
            'modal' => '',
            'type' => '',
            'title' => '',
            'color' => 'color',
            'align' => 'left',
            'size' => '',
            'contact_form' => '',
            'css_animation' => '',
            'add_icon' => '',
            'i_align' => 'left',
            'icon_lib' => 'fontawesome',
            'icon_fontawesome' => '',
            'icon_openiconic' => '',
            'icon_typicons' => '',
            'icon_entypo' => '',
            'icon_linecons' => '',
            'css' => ''
        ), $atts));

        $css_classes[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
        $css_classes[] = 'wpb_content_element';



        if( !empty($css_animation) ){
            $wrap_classes[] = 'theme__animation';
            $wrap_classes[] = '-'.$css_animation;
            forit_enqueue_custom('css-animation');
        }
        $wrap_classes[] = 'text-'.$align;

        $link = vc_build_link($link);
        $icon = $href = $target = '';

        if($add_icon) {
            if(!empty(${'icon_'.$icon_lib})) {
                vc_icon_element_fonts_enqueue($icon_lib);
                $icon = '    <i class="' . ${'icon_' . $icon_lib} . '"></i>';
            }
        }

        if($type && $contact_form) {
            forit_enqueue_custom('form');

            $modal_content = '<h4 class="text-center">'. get_the_title($contact_form) .'</h4>' . do_shortcode('[contact-form-7 id="'. $contact_form .'"]');
            $modal_id = 'modal-form-' . forit_get_unique_id();
            $href = '#'.$modal_id;

            $css_classes[] = 'modal-trigger';

            forit_get_modal_form($modal_content, $modal_id);
        }

        $title = $type?$title:$link['title'];
        $href = $type?$href:$link['url'];
        $target = $type?$target:($link['target'] ? ('target="' . esc_attr($link['target']) . '"') : '');

        $color = explode('|', $color);

        $css_classes[] = 'btn waves-effect';
        $css_classes[] = '_animation_' . $i_align;
        $css_classes[] = 'waves-' . reset($color);
        $css_classes[] = 'btn-' . implode(' btn-', $color);
        $css_classes[] = $size;

        $output .= '<div class="' . implode(' ', $wrap_classes) . '">';
        $output .= '    <a href="'. $href .'" class="' . implode( ' ', $css_classes ) . '" '. $target .'>' . ($i_align == 'left' ? ($icon . esc_html($title)) : (esc_html($title) . $icon)) . '</a>';
        $output .= '</div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__('Theme Button', 'forit'),
    'base'		=> 'forit_theme_btn',
    'controls'	=> 'edit_popup_delete',
    'category'  => esc_html__('Developed for ForIT', 'forit'),
    'icon' =>   get_template_directory() .'/assets/img/vc/theme_button.png',
    'params'	=> array(
        array(
            'type' => 'forit_layout_select',
            'heading' => esc_html__('Color', 'forit'),
            'param_name' => 'color',
            'description' => esc_html__('Select button color.', 'forit'),
            'layouts'   => array(
                'color' => get_template_directory()  . '/assets/img/vc/blue_btn.png',
                'white' => get_template_directory()  . '/assets/img/vc/white_btn.png',
            ),
            'value' => 'color'
        ),
        
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Modal Box Button', 'forit'),
            'param_name' => 'type',
            'value' => array( esc_html__( 'Yes', 'forit' ) => 'yes' ),
            'description' => esc_html__('If checked button will call Modal Box.', 'forit'),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Button text', 'forit'),
            'param_name' => 'title',
            'value' => '',
            'dependency' => array(
                'element' => 'type',
                'not_empty'   => true
            ),
            'description' => esc_html__('Type name', 'forit')
        ),
        array(
            'type' => 'vc_link',
            'heading' => esc_html__('Link', 'forit'),
            'param_name' => 'link',
            'value' => '',
            'dependency' => array(
                'element' => 'type',
                'is_empty' => true
            ),
            'description' => esc_html__('Type link', 'forit')
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Select contact form', 'forit' ),
            'param_name' => 'contact_form',
            'dependency' => array(
                'element' => 'type',
                'not_empty' => true
            ),
            'value' => forit_get_posts_list( array('post_type' => 'wpcf7_contact_form', 'numberposts' => -1) ),
            'description' => esc_html__( 'Choose previously created contact form from the drop down list.', 'forit' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Size', 'forit'),
            'param_name' => 'size',
            'value' => array(
                esc_html__('default','forit') => '',
                esc_html__('large','forit') => 'btn-md'
            ),
            'description' => esc_html__('Choose from specified options.', 'forit'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Align', 'forit'),
            'param_name' => 'align',
            'value' => array(
                esc_html__('left','forit') => 'left',
                esc_html__('center','forit') => 'center',
                esc_html__('right','forit') => 'right'
            ),
            'description' => esc_html__('Choose from specified options.', 'forit'),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Add icon?', 'forit' ),
            'param_name' => 'add_icon',
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon Alignment', 'forit' ),
            'description' => esc_html__( 'Select icon alignment.', 'forit' ),
            'param_name' => 'i_align',
            'value' => array(
                esc_html__( 'Left', 'forit' ) => 'left',
                esc_html__( 'Right', 'forit' ) => 'right',
            ),
            'dependency' => array(
                'element' => 'add_icon',
                'not_empty' => true
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'forit' ),
            'value' => array(
                esc_html__( 'Font Awesome', 'forit' )   => 'fontawesome',
                esc_html__( 'Open Iconic', 'forit' )    => 'openiconic',
                esc_html__( 'Typicons', 'forit' )       => 'typicons',
                esc_html__( 'Entypo', 'forit' )         => 'entypo',
                esc_html__( 'Linecons', 'forit' )       => 'linecons',
            ),
            'dependency' => array(
                'element' => 'add_icon',
                'not_empty' => true
            ),
            'param_name' => 'icon_lib',
            'description' => esc_html__( 'Select icon library.', 'forit' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'forit' ),
            'param_name' => 'icon_fontawesome',
            'value' => 'fa fa-adjust', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true,
                'iconsPerPage' => 4000,
            ),
            'dependency' => array(
                'element' => 'icon_lib',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'forit' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'forit' ),
            'param_name' => 'icon_openiconic',
            'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an 'EMPTY' icon?
                'type' => 'openiconic',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib',
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'forit' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'forit' ),
            'param_name' => 'icon_typicons',
            'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an 'EMPTY' icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib',
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'forit' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'forit' ),
            'param_name' => 'icon_entypo',
            'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an 'EMPTY' icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'forit' ),
            'param_name' => 'icon_linecons',
            'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an 'EMPTY' icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib',
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'forit' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'CSS Animation', 'forit' ),
            'param_name' => 'css_animation',
            'value' => array(
                esc_html__( 'No', 'forit' ) => '',
                esc_html__( 'Top to bottom', 'forit' ) => 'fadeInDown',
                esc_html__( 'Bottom to top', 'forit' ) => 'fadeInUp',
                esc_html__( 'Left to right', 'forit' ) => 'fadeInRight',
                esc_html__( 'Right to left', 'forit' ) => 'fadeInLeft',
                esc_html__( 'Appear from center', 'forit' ) => 'zoomIn',
            ),
            'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'forit' ),
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'forit' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'forit' ),
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_ForIT_Theme_Btn($opts);