<?php

class WPBakeryShortCode_BitstarterPlot extends  WPBakeryShortCode{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $css = '';
        $output = '';

        $css_classes = array();
        $wrap_classes = array();

        extract(shortcode_atts(array(
            'css' => ''
        ), $atts));

        $css_classes[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
        $css_classes[] = 'wpb_content_element';


        wp_enqueue_script('highcharts');

        $output = '<div class="bitstarter-shortcode__plot" style="height: 340px">
                    <div id="bitstarter-plot" class="bitstarter-shortcode__plot__in" style="height: 300px; width: 100%;"></div>';
        $output .= '<button class="bitstarter-shortcode__plot-change__timeframe" data-timeframe="7day">'. esc_html__('7 day','bitstarter') . '</button>';
        $output .= '<button class="bitstarter-shortcode__plot-change__timeframe" data-timeframe="1month">' . esc_html__('1 month','bitstarter') . '</button>';
        $output .= '<button class="bitstarter-shortcode__plot-change__timeframe" data-timeframe="3month">' . esc_html__('3 month','bitstarter') . '</button>';
        $output .= '<button class="bitstarter-shortcode__plot-change__timeframe" data-timeframe="1year">' . esc_html__('1 year','bitstarter') . '</button>';
        $output .= '<button class="bitstarter-shortcode__plot-change__timeframe active" data-timeframe="all">' . esc_html__('All time','bitstarter') . '</button>';

        $output .=  '</div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__('Bitstarter Theme Plot', 'bitstarter'),
    'base'		=> 'bitstarterplot',
    'controls'	=> 'edit_popup_delete',
    'category'  => esc_html__('Developed for Bitstarter', 'bitstarter'),
    'icon'      => get_template_directory_uri() . '/assets/img/vc/bitstarter_plot.png',
    'params'	=> array(
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitstarter' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitstarter' ),
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_BitstarterPlot($opts);