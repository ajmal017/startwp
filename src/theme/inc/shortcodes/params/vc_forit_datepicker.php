<?php

WpbakeryShortcodeParams::addField( 'forit_datepicker', 'forit_datepicker_field' );
function forit_datepicker_field( $settings, $value ) {
    return '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="date" value="' . esc_attr( $value ) . '" />';
}
