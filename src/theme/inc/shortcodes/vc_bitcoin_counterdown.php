<?php


class WPBakeryShortCode_Bitcoin_CounterDown extends  WPBakeryShortCode
{
    
    public function hex2rgb($color){
        list($r, $g, $b) = array($color[0].$color[1],
        $color[2].$color[3],
        $color[4].$color[5]);
        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
        return array($r, $g, $b);
    }
    
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $css = $title = $title1 = $bg_color1 = $time = $date = $position  = $text_color = $shadow_color = $raised = $bakers = $start = $stop = $progress_color = '';


        extract(shortcode_atts(array(
            'css'           => '',
            'title'         => 'ICO ENDS IN',
            'time'          => '23',
            'date'      => '2018-03-31',
            'text_color'=> '#ffffff',
            'shadow_color'  => '#474e65',
            'bg_color1'      => '#323a5a',
            'title1' => 'Raised',
            'raised' => '19564867 | raised',
            'bakers' => '8756 | bakers',
            'start' => '2000000 | Softcap in just 10 hours',
            'stop' => '20000000 | Hardcup',
            'progress_color' => '#0de8e8'
        ), $atts));



        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

        $time = intval( $time );
        $time = $time > 23?23:abs($time);

        $date = trim($date);
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return 'Wrong date format. Should be: YYYY-MM-DD ';
        }

        $output = '';
        $shadow_color = implode(',', $this->hex2rgb(str_replace('#','',$shadow_color)));

        $output .= '<div class="counterDown '.$css_class.'"><div class="counterDown__in" style="box-shadow: 0 20px 80px rgba(' . $shadow_color . ',.5); background: ' . $bg_color1 . '">';
        
        $output .= '<div class="counterDown__title " style="color: ' . $text_color . '; ">' . $title . '</div>';

        $output .= '<div class="counterDown__timer " style="color:'. $bg_color1 .'" data-date="' . $date . ' ' . $time . ':00">';
        $output .= '    <div class="counterDown__timer__in" data-mark="years">';
        $output .= '        <h2 class="counterDown__timer__datatime" style="background-color:' . $text_color . '; color:'. $bg_color1 .'"></h2>';
        $output .= '        <h2 class="counterDown__timer__datamark" style="color:' . $text_color . '; " data-labels="'.esc_html__('0: years | 1:year | 2:years', 'bitcoin').'"></h2>';
        $output .= '    </div>';
        $output .= '    <div class="counterDown__timer__in" data-mark="months">';
        $output .= '        <h2 class="counterDown__timer__datatime" style="background-color:' . $text_color . '; color:'. $bg_color1 .'"></h2>';
        $output .= '        <h2 class="counterDown__timer__datamark" style="color:' . $text_color . '; " data-labels="'.esc_html__('0:months | 1:month | 2:months', 'bitcoin').'"></h2>';
        $output .= '    </div>';
        $output .= '    <div class="counterDown__timer__in" data-mark="days">';
        $output .= '        <h2 class="counterDown__timer__datatime" style="background-color:' . $text_color . '; color:'. $bg_color1 .'"></h2>';
        $output .= '        <h2 class="counterDown__timer__datamark" style="color:' . $text_color . '; " data-labels="'.esc_html__('0:days | 1:day | 2:days', 'bitcoin').'"></h2>';
        $output .= '    </div>';
        $output .= '    <div class="counterDown__timer__in" data-mark="hours">';
        $output .= '        <h2 class="counterDown__timer__datatime" style="background-color:' . $text_color . '; color:'. $bg_color1 .'"></h2>';
        $output .= '        <h2 class="counterDown__timer__datamark" style="color:' . $text_color . '; " data-labels="'.esc_html__('0:hours | 1:hour | 2:hours', 'bitcoin').'"></h2>';
        $output .= '    </div>';
        $output .= '    <div class="counterDown__timer__in" data-mark="minutes">';
        $output .= '        <h2 class="counterDown__timer__datatime" style="background-color:' . $text_color . '; color:'. $bg_color1 .'"></h2>';
        $output .= '        <h2 class="counterDown__timer__datamark" style="color:' . $text_color . '; " data-labels="'.esc_html__('0 :minutes | 1:minute | 2:minutes', 'bitcoin').'"></h2>';
        $output .= '    </div>';
        $output .= '    <div class="counterDown__timer__in" data-mark="seconds">';
        $output .= '        <h2 class="counterDown__timer__datatime" style="background-color:' . $text_color . '; color:'. $bg_color1 .'"></h2>';
        $output .= '        <h2 class="counterDown__timer__datamark" style="color:' . $text_color . '; " data-labels="'.esc_html__('0 :seconds | 1:second | 2:seconds', 'bitcoin').'"></h2>';
        $output .= '    </div>';
        $output .= '</div>';

        $output .= '<div class="counterDown__title " style="color: ' . $text_color . '; ">' . $title1 . '</div>';

        $output .= '<div class="counterDown__row">';

        if( !empty($raised)):
            $title = '';
            $subtitle = '';
            if( ($pos = strpos($raised, '|')) > 0 ){
                $progress_value = trim(substr($raised, 0, $pos));
                $title = '$' . number_format($progress_value); 
                $subtitle = substr($raised, $pos + 1); 
            
            }else{
                $progress_value = trim($raised);
                $title = '$' . number_format($progress_value);
            }

            $output .=      '<div class="counterDown-s1">  <div class="counterDown-s1__title"  style="color: ' . $text_color . '">' . $title . ' </div> <div class="counterDown-s1__subtitle"  style="color: ' . $text_color . '">' . $subtitle . ' </div>';
            $output .=  '</div>';

        endif;

        if( !empty($bakers)):
            $title = '';
            $subtitle = '';
            if( ($pos = strpos($bakers, '|')) > 0 ){
                
                $title = number_format(trim(substr($bakers, 0, $pos)), 0, '.', ' '); 
                $subtitle = substr($bakers, $pos + 1); 
            
            }else{

                $title = number_format(trim($bakers), 0, '.', ' ');

            }

            $output .=      '<div class="counterDown-s1">  <div class="counterDown-s1__title" style="color: ' . $text_color . '"> ' . $title . ' </div> <div class="counterDown-s1__subtitle" style="color: ' . $text_color . '" >' . $subtitle . ' </div>';
            $output .=  '</div>';

        endif;


        
        $output .= '</div>';
        $output .= '<div class="counterDown__row">';

        if( !empty($start)):
            $title = '';
            $subtitle = '';
            if( ($pos = strpos($start, '|')) > 0 ){
                $start_value = trim(substr($start, 0, $pos));

                if( strlen($start_value) > 6 ){
                    $start_value_html = substr($start_value, 0, -6) . 'M';
                }elseif( strlen($start_value) > 2 ){
                    $start_value_html = substr($start_value, 0, -3) . 'K';
                } 
                $title = '$' . $start_value_html; 
                $subtitle = substr($start, $pos + 1); 
            
            }else{

                $start_value = trim(substr($start, 0, $pos));
                if( strlen($start_value) > 6 ){
                    $start_value_html = substr($start_value, 0, -6) . 'M';
                }elseif( strlen($start_value) > 2 ){
                    $start_value_html = substr($start_value, 0, -3) . 'K';
                } 
                $title = '$' . $start_value_html; 

            }

            $output .=      '<div class="counterDown-s2 counterDown-s2--left">  <div class="counterDown-s2__title"  style="color: ' . $text_color . '">' . $title . ' </div> <div class="counterDown-s2__subtitle"  style="color: ' . $text_color . '">' . $subtitle . ' </div>';
            $output .=  '</div>';

        endif;

        if( !empty($stop) ):
            $title = '';
            $subtitle = '';
            if( ($pos = strpos($stop, '|')) > 0 ){
                
                $stop_value = trim(substr($stop, 0, $pos));
                if( strlen($stop_value) > 6 ){
                    $stop_value_html = substr($stop_value, 0, -6) . 'M';
                }elseif( strlen($stop_value) > 2 ){
                    $stop_value_html = substr($stop_value, 0, -3) . 'K';
                } 
                $title = '$' . $stop_value_html; 
                $subtitle = substr($start, $pos + 1);
            
            }else{

                $stop_value = trim(substr($stop, 0, $pos));
                if( strlen($stop_value) > 6 ){
                    $stop_value_html = substr($stop_value, 0, -6) . 'M';
                }elseif( strlen($stop_value) > 2 ){
                    $stop_value_html = substr($stop_value, 0, -3) . 'K';
                } 
                $title = '$' . $stop_value_html; 

            }

            $output .=  '<div class="counterDown-s2 counterDown-s2--right">  <div class="counterDown-s2__title" style="color: ' . $text_color . '"> ' . $title . ' </div> <div class="counterDown-s2__subtitle" style="color: ' . $text_color . '" >' . $subtitle . ' </div>';
            $output .=  '</div>';

        endif;
        if($raised != '' && $start != '' && $stop != '' && is_numeric( $progress_value ) && is_numeric( $start_value ) && is_numeric( $stop_value ) ){
            $output .= '<div class="counterDown-progress" style="background-color:'. $text_color .'"><div class="counterDown-progress__done" style="width: ' . (int) (($progress_value - $start_value) * 100 / ($stop_value - $start_value) ) . '%; background-color: ' . $progress_color . '"> </div></div>';
        }

        $output .= '</div>';
        $output .= '<div class="counterDown-contect">' . wpb_js_remove_wpautop($content, true) . '</div>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
}


$opts = array(
    'name'          => esc_html__( 'Counter Down', 'bitcoin'),
    'base'          => 'bitcoin_counterdown',
    'controls'      => 'edit_popup_delete',
    'icon'          => get_template_directory_uri() . '/assets/img/vc/bitcoin_counterdown.png',
    'category'      => esc_html__('Developed for Bitcoin', 'bitcoin'),
    'params'        => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title', 'bitcoin' ),
            'value' => esc_html__('ICO ENDS IN'),
            'param_name' => 'title'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Date', 'bitcoin' ),
            'param_name' => 'date',
            'edit_field_class' => 'vc_col-sm-6 vc_column vc_column-with-padding',
            'value'		=> '2018-03-31',
            'description'		=> esc_html__( 'Choose date when you complete in format YYYY-MM-DD (Ex. 2018-03-31)', 'bitcoin'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Time', 'bitcoin' ),
            'param_name' => 'time',
            'value' => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'),
            'description' => esc_html__( 'Choose hour when you complete,from 00 to 23.', '' )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Text Color', 'bitcoin' ),
            'param_name' => 'text_color',
            "value" => '#ffffff',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Background color', 'bitcoin' ),
            'param_name' => 'bg_color1',
            "value" => '#323a5a',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Shadows in card', 'bitcoin' ),
            'param_name' => 'shadow_color',
            "value" => '#474e65',
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title under counter', 'bitcoin' ),
            'value' => esc_html__('Raised'),
            'param_name' => 'title1'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Raised amount in dollars, also after " | " mark, you may denote subtitle', 'bitcoin' ),
            'value' => esc_html__('19564867 | raised', 'bitcoin'),
            'param_name' => 'raised'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Bakers amount, also after " | " mark, you may denote subtitle', 'bitcoin' ),
            'value' => esc_html__('8756 | bakers', 'bitcoin'),
            'param_name' => 'bakers'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Start progress from, in dollars, also after " | " mark, you may denote subtitle', 'bitcoin' ),
            'value' => esc_html__('2000000 | Softcap in just 10 hours', 'bitcoin'),
            'param_name' => 'start'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Stop progress till, in dollars, also after " | " mark, you may denote subtitle', 'bitcoin' ),
            'value' => esc_html__('20000000 | Hardcup', 'bitcoin'),
            'param_name' => 'stop'
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Progress bar color', 'bitcoin' ),
            'param_name' => 'progress_color',
            "value" => '#0de8e8',
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => esc_html__( 'Anding content', 'bitcoin' ),
            'param_name' => 'content',
            'value' => esc_html__( '','bitcoin' ),
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitcoin' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitcoin' ),
        )
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitcoin_CounterDown($opts);