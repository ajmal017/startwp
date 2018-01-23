<?php
if(class_exists('WPBakeryShortCode' )) {
    add_action('init', 'bitcoin_init_vc_params', 49);
    add_action('init', 'bitcoin_init_vc_shortcodes', 50);
}

function bitcoin_init_vc_params(){
    include_once( get_template_directory() . '/inc/shortcodes/params/vc_helptext.php' );
}

function bitcoin_init_vc_shortcodes(){
    
    include_once( get_template_directory() . '/inc/shortcodes/extend_vc.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitcoin_plot.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitcoin_blog.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitcoin_testimonials.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitcoin_container.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitcoin_calc.php' );
    include_once( get_template_directory() . '/inc/shortcodes/vc_bitcoin_counter.php' );

}