<?php

class WPBakeryShortCode_BitcoinPlot extends  WPBakeryShortCode{
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

        $output = '<div class="bitcoin-shortcode__plot" style="height: 340px">
                    <div id="bitcoin-plot" class="bitcoin-shortcode__plot__in" style="height: 300px; width: 100%;"></div>';
        $output .= '<button class="bitcoin-shortcode__plot-change__timeframe" data-timeframe="7day">'. esc_html('7 day','bitcoin') . '</button>';
        $output .= '<button class="bitcoin-shortcode__plot-change__timeframe" data-timeframe="1month">' . esc_html('1 month','bitcoin') . '</button>';
        $output .= '<button class="bitcoin-shortcode__plot-change__timeframe" data-timeframe="3month">' . esc_html('3 month','bitcoin') . '</button>';
        $output .= '<button class="bitcoin-shortcode__plot-change__timeframe" data-timeframe="1year">' . esc_html('1 year','bitcoin') . '</button>';
        $output .= '<button class="bitcoin-shortcode__plot-change__timeframe active" data-timeframe="all">' . esc_html('All time','bitcoin') . '</button>';

        $output .=  '</div>';

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__('Bitcoin Theme Plot', 'bitcoin'),
    'base'		=> 'bitcoinplot',
    'controls'	=> 'edit_popup_delete',
    'category'  => esc_html__('Developed for Bitcoin', 'bitcoin'),
    'icon'      => get_template_directory_uri() . '/assets/img/vc/bitcoin_plot.png',
    'params'	=> array(
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitcoin' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitcoin' ),
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_BitcoinPlot($opts);