<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $height
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Empty_space
 */

$el_class = $css = $bitstarter_responsive_height = $id = $output1 = '';
$bitstarter_responsive_array = []
;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


$class = 'vc_empty_space ' . $this->getExtraClass( $el_class ) . vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );

$id = bitstarter_get_unique_id('empty-space');
$bitstarter_responsive_height = json_decode($bitstarter_responsive_height, true);

if(!empty($bitstarter_responsive_height['height'])){

    $smaller = '';
    
    if( !isset($bitstarter_responsive_height['height']['lg']) ){
        $bitstarter_responsive_height['height']['lg'] =  '';
    }
    if( !isset($bitstarter_responsive_height['height']['md']) ){
        $bitstarter_responsive_height['height']['md'] =  '';
    }
    if( !isset($bitstarter_responsive_height['height']['sm']) ){
        $bitstarter_responsive_height['height']['sm'] =  '';
    }
    if( !isset($bitstarter_responsive_height['height']['xs']) ){
        $bitstarter_responsive_height['height']['xs'] =  '';
    }

    foreach($bitstarter_responsive_height['height'] as $key => $value) {
        if(empty($value) && !empty($smaller)){
            $value = $smaller;
        }
        if( $key === 'lg' && (!empty($value) || !empty($smaller)) ){
            $output1 .= '@media (min-width: 900px) { #' . esc_attr( trim( $id ) ) . ' { height: ' . $value . '} }';
        }
        if( $key === 'md' && (!empty($value) || !empty($smaller))){
            $output1 .= '@media (min-width: 768px) and (max-width: 899px) { #' . esc_attr( trim( $id ) ) . ' { height: ' . $value . '} }';
            $smaller = $value;
        }
        if( $key === 'sm' && (!empty($value) || !empty($smaller))){
            $output1 .= ' @media (min-width: 576px) and (max-width: 767px){  #' . esc_attr( trim( $id ) ) . ' { height: ' . $value . ' } }';
            $smaller = $value;
        }
        if( $key === 'xs' && !empty($value)){
            $output1 .= ' @media (max-width: 575px){  #' . esc_attr( trim( $id ) ) . ' { height: ' . $value . '} }';
            $smaller = $value;
        
        }
    }

    add_action('wp_footer', function() use ( $output1 ) {
        echo '<style type="text/css">' . $output1 . '</style>';
    });
}

?>
<div id="<?php echo esc_attr( trim( $id ) ); ?>" class="<?php echo esc_attr( trim( $css_class ) ); ?>"><span class="vc_empty_space_inner"></span></div>