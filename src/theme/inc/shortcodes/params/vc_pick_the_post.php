<?php
WpbakeryShortcodeParams::addField( 'forit_pickpost', 'forit_pickpost_field', FORIT_SCRIPTS . '/admin/vc_params/forit_pickpost_field.js' );
function forit_pickpost_field( $settings, $value ) {
    if(empty($settings['post_type'])){
        return false;
    }
    global $id;
    $postvalue = "";
    $posts = new WP_Query(array(
        'post_type' => $settings['post_type'],
        'showposts'=> -1,
        ));

     if ( $posts->have_posts() ) :
         while ( $posts->have_posts() ) : $posts->the_post();
             $postvalue .= '<option '. (($value == get_the_title($id))?" selected ":"") . ' value="'. get_the_title($id) .'">'. get_the_title($id) .'</option>';
         endwhile;
     endif;
    $return  = '<div class="vc_row">';
    $return .= '    <div class="vc_col-sm-12">';
    $return .= '        <select  name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' "> '. $postvalue .' </select>';
    $return .= '    </div>';
    $return .= '</div>';
    return $return;
}
