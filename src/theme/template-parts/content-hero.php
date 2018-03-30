<?php
/**
 * Template part for displaying a hero header
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitstarter
 */

?>
<?php

    $page_for_posts = get_option( 'page_for_posts' );
    $the_random_hero = bitstarter_get_random_hero_object( $page_for_posts );
    $has_image       = false;	

    if ( ( empty( $the_random_hero ) || property_exists( $the_random_hero, 'post_mime_type' ) ) && is_object( $the_random_hero ) && property_exists( $the_random_hero, 'post_mime_type' ) && strpos( $the_random_hero->post_mime_type, 'image' ) !== false ) {
            $has_image = wp_get_attachment_image_src( $the_random_hero->ID, 'full' );
        }
?>

<header class="hero-header<?php 
        if($has_image) echo ' has__featured__image'; 
        if(bitstarter_categorized_blog() && !is_category() && !is_search()) echo ' has__categories';
             ?>">
    <div class="hero-header__background">

        <?php if ( ! empty( $has_image ) ) { 
            $hero_image_srcset  = wp_get_attachment_image_srcset( $the_random_hero->ID, 'full');
            $hero_image_sizes =  wp_get_attachment_image_sizes($the_random_hero->ID, 'full');
            echo '<img class="hero-header__background__img" alt="hero" src="' . $has_image[0] . '"  srcset="' . $hero_image_srcset . '" sizes="' . $hero_image_sizes  . '"/>';
        } ?>

        <?php if ( bitstarter_get_option( 'site_blogheroarea_gradover', false ) == true ):?>
            <!--  Gradient start -->
            <div class="hero-header__overlay1">
                <svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <linearGradient id="Gradient1" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="white" stop-opacity="1" />
                            <stop offset="1" stop-color="transparent" stop-opacity="0" />
                        </linearGradient>
                        <mask id="Mask1">
                            <rect x="0" y="0" width="100%" height="100%" fill="url(#Gradient1)"  />
                        </mask>
                    </defs>
                    <rect id="hero-header__overlay1" x="0" y="0" width="100%" height="100%"  mask="url(#Mask1)" />
                </svg>
            </div>
            <div class="hero-header__overlay2">
                <svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <linearGradient id="Gradient2" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0" stop-color="transparent" stop-opacity="0" />
                                <stop offset="1" stop-color="white" stop-opacity="1" />
                            </linearGradient>
                            <mask id="Mask2">
                                <rect x="0" y="0" width="100%" height="100%" fill="url(#Gradient2)"  />
                            </mask>
                        </defs>
                        <rect id="hero-header__overlay2" x="0" y="0" width="100%" height="100%" mask="url(#Mask2)" />
                </svg>
            </div>
            <!--  Gradient end -->
            
        <?php endif; ?>

        <?php if ( bitstarter_get_option( 'site_blogheroarea_linover', false ) == true ):?>
            <!-- Trasparancy Overlay-->
            <div class="hero-header__overlay3">
                <!-- Should be empty-->
            </div>
            <!-- Trasparancy Overlay end-->

        <?php endif; ?>
    
        <div class="hero-header__content">
            <?php if( is_category() ) { ?>

                <h1 class="hero-title"><?php single_cat_title(esc_html__('Category: ', 'bitstarter')); ?></h1>

            <?php } elseif ( is_archive() ) {

                the_archive_title('<h1 class="hero-title">', '</h1>');
                the_archive_description('<div class="hero-description">', '</div>');

            } elseif ( is_search()  ) { ?>

                <div class="hero-header__content-area__wrapper">
                    <h1 class="hero-title"><?php printf( esc_html__( 'Search results for: %s', 'bitstarter' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                    <?php if( ! have_posts() ) : ?>	
                        <?php get_template_part( 'template-parts/content', 'none' ); ?>
                    <?php endif; ?>

                </div>
            <?php } elseif ( is_404()  ) { ?>

            <div class="hero-header__content-area__wrapper hero-header__content--404 ">
               
                <h1 class="hero-title title-404"><?php esc_html_e( '404', 'bitstarter' ); ?></h1>
                <?php get_template_part( 'template-parts/content', 'none' ); ?>
               
            </div>

            <?php } else { ?>


                
                <h1 class="hero-title"><?php echo get_the_title($page_for_posts); ?></h1>
            
            <?php } ?>

            <?php
            if( bitstarter_categorized_blog() && !is_category() && !is_archive() && !is_search() && !is_404()):
                $categories = get_categories();
                if( $categories ):
                    $cats_num_more_than_five = count($categories) > 5 ;
                    echo '<ul class="hero-category__list ' . ($cats_num_more_than_five ? 'hero-category__list--extended' : 'hero-category__list--simple') . '">';
                    if( $cats_num_more_than_five ):
                       
                        $showcats = array_slice($categories, 0, 5);

                        foreach ( $showcats as $category ): ?>
                        <li><a href="<?php echo esc_attr( get_category_link( $category->cat_ID ) ); ?>"><?php echo esc_html($category->cat_name); ?></a></li>
                        <?php endforeach;

                        echo '<span class="hero-category__list__more">&bull;&bull;&bull;</span>';

                        echo '<div class="hero-category__list__additional">';
                            
                            $showcats = array_slice($categories, 5);

                            foreach ( $showcats as $category ): ?>
                            <li><a href="<?php echo esc_attr( get_category_link( $category->cat_ID ) ); ?>"><?php echo esc_html($category->cat_name); ?></a></li>
                            <?php endforeach;

                        echo '</div>';

                    else:
                        
                    foreach ( $categories as $category ): ?>
                        <li><a href="<?php echo esc_attr( get_category_link( $category->cat_ID ) ); ?>"><?php echo esc_html($category->cat_name); ?></a></li>
                    <?php endforeach;

                    endif;
                    echo '</ul>';
                endif;
            endif; ?>

        </div>
    </div>
</header>