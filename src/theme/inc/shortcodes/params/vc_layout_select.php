<?php

WpbakeryShortcodeParams::addField( 'forit_layout_select', 'forit_layout_select_field', FORIT_SCRIPTS . '/admin/vc_params/forit_layout_select.js' );
function forit_layout_select_field( $settings, $value ) {
    $layouts = isset( $settings[ 'layouts' ] ) && is_array( $settings[ 'layouts' ] ) ? $settings[ 'layouts'] : array();
    $columns = count($layouts);

    $return = '<div class="forit-layout-select">';

    foreach( $layouts as $layout => $image ) {
        $checked = $value == $layout ? 'active' : '';

        if(is_string( $layout )) {
            $return .= '<div class="forit-layout-item ' . $checked . '" data-value="' . esc_attr($layout) . '">';
            $return .= '   <img src="' . $image . '" alt="' . esc_attr(ucfirst($layout)) . '" />';
            $return .= '</div>';
        }
        if(is_numeric( $layout )){
            $return .= '<div class="forit-layout-item ' . $checked . '" data-value="' . esc_attr($layout) . '">';
            $return .=  '<input id="' . $settings['param_name'] . $layout .'" type="radio" name="' . $settings['param_name'] . '" ' .($checked?'checked':''). ' />' . '<label  for="' . $settings['param_name'] . $layout . '">' . $image . '</label >';
            $return .= '</div>';
        }
    }

    $return .= '</div>';    

    $return .= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field hidden" type="text" value="' . esc_attr( $value ) . '" />';

    return $return;
}
