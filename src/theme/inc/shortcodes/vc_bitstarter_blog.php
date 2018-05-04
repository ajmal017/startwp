<?php

class WPBakeryShortCode_Bitstarter_Blog extends  WPBakeryShortCode
{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $data_query = $columns = $gap = $grid_type = $css = '';

        extract(shortcode_atts(array(
            'data_query'    => '',
            'css'           => ''
        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );



        $output = '';
        
        if(! strpos($data_query, 'size')){
            $data_query .= '|size:3';
        }
        if(! strpos($data_query, 'order_by')){
            $data_query .= '|order_by:date';
        }
        if(! strpos($data_query, 'order')){
            $data_query .= '|order:ASC';
        }

        $data_query .= '|post_type:post';

        list($query_args, $query_body)  = vc_build_loop_query( $data_query );

        if ($query_body->have_posts()):


            $output .= '<div class="wpb_content_element ' . $css_class . '">';
            $output .= '<div class="postcards postcards--frontpage">';
            $output .= '<div class="grid grid--tile">';

            ob_start();

            while($query_body->have_posts()):$query_body->the_post();
                if(is_sticky())
                    continue;
            ?>
        
                    <div class="grid__item  postcard">
                        <?php

                            /*
                            * Include the Post-Format-specific template for the content.
                            * If you want to override this in a child theme, then include a file
                            * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                            */
                            get_template_part( 'template-parts/content', get_post_format() );
                        ?>
                    </div>
               <?php
            endwhile;

            $output .= ob_get_contents();
            ob_clean();


            $output .= '</div> </div> </div>';

        endif;

        wp_reset_postdata();

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Post Feed', 'bitstarter'),
    'base'		=> 'bitstarter_blog',
    'controls'		=> 'edit_popup_delete',
    'category'		=> esc_html__('Developed for Bitstarter', 'bitstarter'),
    'icon'		=> get_template_directory_uri() . '/assets/img/vc/bitstarter_blog.png',

    'params'		=> array(
        array(
            'type' => 'loop',
            'heading' => esc_html__( 'Posts', 'bitstarter' ),
            'param_name' => 'data_query',
            'value' => '',
            'settings' => array(
                'post_type'     => array( 'hidden' => true ),
                'authors'       => array( 'hidden' => true ),
                'tax_query'     => array( 'hidden' => true ),
            ),
            'description' => esc_html__( 'Create WordPress loop, to populate content from your posts.', 'bitstarter' ),
        ),

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitstarter' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitstarter' ),
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitstarter_Blog($opts);