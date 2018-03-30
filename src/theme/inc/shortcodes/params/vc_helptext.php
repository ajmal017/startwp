<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

WpbakeryShortcodeParams::addField( 'bitstarter_helptext', 'bitstarter_helptext_field' );
function bitstarter_helptext_field( $settings, $value ) {
    return '';
}


