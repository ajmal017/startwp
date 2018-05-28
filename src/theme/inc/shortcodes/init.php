<?php
if(class_exists('WPBakeryShortCode' )) {
    add_action('init', 'bitstarter_init_vc_params', 49);
    add_action('init', 'bitstarter_init_vc_shortcodes', 50);
}

function bitstarter_init_vc_params(){
    include_once( get_template_directory() . '/inc/shortcodes/params/vc_helptext.php' );
    
    include_once( get_template_directory() . '/inc/shortcodes/params/vc_responsive_param.php' );
}


function bitstarter_init_vc_shortcodes(){

    include_once( get_template_directory() . '/inc/shortcodes/extend_vc.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_plot.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_blog.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_testimonials.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_container.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_calc.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_counter.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_team.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_card.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_mark.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_distribution.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitstarter_counterdown.php' );
}
