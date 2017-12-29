<?php
WpbakeryShortcodeParams::addField( 'forit_offset_box', 'forit_offset_box_field', FORIT_SCRIPTS . '/admin/vc_params/forit_offset_box_field.js' );

class ForITWPBakeryVisualComposerBoxEditor extends WPBakeryVisualComposerCssEditor {

    /**
     * @var array
     */
    protected $settings = array();
    /**
     * @var string
     */
    protected $value = '';

    function __construct($settings, $value = '') {
        $this->settings = $settings;
        $this->value = $value;
    }

    public function render() {
        $disable_value = '';

        $return  = '<div class="forit-offset-box-field vc_row">';
        $return .= '    <input name="' . esc_attr( $this->settings['param_name'] ) . '" class="offset-box-value wpb_vc_param_value ' . esc_attr( $this->settings['param_name'] ) . ' ' . esc_attr( $this->settings['type'] ) . '" type="hidden" value="' . esc_attr( $this->value ) . '"/>';
//        $return .= '    <div class="vc_css-editor vc_col-sm-4">';
//        $return .= '        <div class="vc_layout-onion">';
//        $return .= '            <div class="vc_margin">';
//        $return .= '            <div class="vc_border">';
//        $return .= '                <div class="vc_padding">' . $this->layerControls( 'position' );
//        $return .= '                    <div class="vc_content"><input type="text" data-name="z-index" placeholder="-" class="z-index-position"></div>';
//        $return .= '                </div>';
//        $return .= '            </div>';
//        $return .= '            </div>';
//        $return .= '        </div>';
//        $return .= '    </div>';
//
//        $return .= '    <div class="vc_col-sm-4">';
//        $return .=          vc_dropdown_form_field(array(
//                                    'type' => 'dropdown',
//                                    'param_name'  => 'offset-box-disable_position',
//                                    'value'		=> array(
//                                        esc_html__( 'No', 'forit' )    => '',
//                                        esc_html__( 'Extra Small (767px)', 'forit' )    => 'xs',
//                                        esc_html__( 'Small (991px)', 'forit' )  => 'sm',
//                                        esc_html__( 'Medium (1199px)', 'forit' )    => 'md'
//                                    )
//                                ),
//                                $disable_value
//                            );
//        $return .= '        <span class="vc_description">'.esc_html__( 'Disable custom position', 'forit').'</span>';
//        $return .= '    </div>';

        $return .= '    <div class="vc_col-sm-4">';
        $return .= vc_textfield_form_field(array(
            'heading'   => esc_html__( 'Vertical Offset', 'forit' ),
            'type'  =>  'textfield',
            'param_name'    => 'top',
            'edit_field_class' => 'vc_col-sm-4 vc_column',
        ), 0);
        $return .= '        <span class="vc_description vc_clearfix">' .esc_html__( 'Set vertical offset, negative value shifts to top', 'forit' ). '</span>';
        $return .= '    </div>';

        $return .= '    <div class="vc_col-sm-4">';
        $return .= vc_textfield_form_field(array(
            'heading'   => esc_html__( 'Horizontal Offset', 'forit' ),
            'type'  =>  'textfield',
            'param_name'    => 'left'
        ), 0);
        $return .= '        <span class="vc_description vc_clearfix">'. esc_html__( 'Set horizontal offset, negative value shifts to left', 'forit' ) .'</span>';
        $return .= '    </div>';

        $return .= '    <div class="vc_col-sm-4">';
        $return .= vc_textfield_form_field(array(
            'heading'   => esc_html__( 'Layer Index', 'forit' ),
            'type'  =>  'textfield',
            'param_name'    => 'z-index',
        ), 0);
        $return .= '        <span class="vc_description vc_clearfix">' .esc_html__( 'Set layer index, bigger value moves block on top', 'forit' ). '</span>';
        $return .= '    </div>';

        $return .= '</div>';

        return $return;
    }
}

function forit_offset_box_field( $settings, $value ) {
    $box_editor = new ForITWPBakeryVisualComposerBoxEditor($settings, $value);

    return $box_editor->render();
}
