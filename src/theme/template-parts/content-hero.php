<?php
/**
 * Template part for displaying a hero header
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitcoin
 */

?>
<?php

    $page_for_posts = get_option( 'page_for_posts' );
    $the_random_hero = bitcoin_get_random_hero_object( $page_for_posts );
    $has_image       = false;	

    if ( ( empty( $the_random_hero ) || property_exists( $the_random_hero, 'post_mime_type' ) ) && is_object( $the_random_hero ) && property_exists( $the_random_hero, 'post_mime_type' ) && strpos( $the_random_hero->post_mime_type, 'image' ) !== false ) {
            $has_image = wp_get_attachment_url( $the_random_hero->ID );
        } 
?>

<header class="hero-header<?php 
        if($has_image) echo ' has__featured__image'; 
        if(bitcoin_categorized_blog() && !is_category() && !is_search()) echo ' has__categories';
        if( is_search() && have_posts())  echo ' has__searchpost';
        if( is_404() ) echo ' has__404';     ?>">
    <div class="hero-header__background"<?php if ( ! empty( $has_image ) ) {
        echo ' style="background-image: url(' . bitcoin_get_inline_background_image( $has_image ) . ');"';
    } ?>>

        <?php if ( bitcoin_get_option( 'site_blogheroarea_gradover', false ) == true ):?>
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

        <?php if ( bitcoin_get_option( 'site_blogheroarea_linover', false ) == true ):?>
            <!-- Trasparancy Overlay-->
            <div class="hero-header__overlay3">
                <!-- Should be empty-->
            </div>
            <!-- Trasparancy Overlay end-->

        <?php endif; ?>
    
        <div class="hero-header__content">
            <?php if( is_category() ) { ?>

                <h1 class="hero-title"><?php single_cat_title(esc_html__('Category: ', 'bitcoin')); ?></h1>

            <?php } elseif ( is_archive() ) {

                the_archive_title('<h1 class="hero-title">', '</h1>');
                the_archive_description('<div class="hero-description">', '</div>');

            } elseif ( is_search()  ) { ?>

                <div class="content-area__wrapper">
                    <h1 class="hero-title"><?php printf( esc_html__( 'Search results for: %s', 'bitcoin' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                    <?php if( ! have_posts() ) : ?>	
                        <?php get_template_part( 'template-parts/content', 'none' ); ?>
                    <?php endif; ?>
                </div>
            <?php } elseif ( is_404()  ) { ?>

            <div class="content-area__wrapper">

                <h1 class="hero-title"><?php esc_html_e( '404', 'bitcoin' ); ?></h1>
                <?php get_template_part( 'template-parts/content', 'none' ); ?>
               
            </div>

            <?php } else { ?>


            
                <h1 class="hero-title"><?php echo get_the_title($page_for_posts); ?></h1>
            
            <?php } ?>

            <?php
            if( bitcoin_categorized_blog() && !is_category() && !is_archive() && !is_search() && !is_404()):
                $categories = get_categories();
                if( $categories ):
                    echo '<ul class="hero-category__list">';

                    foreach ( $categories as $category ): ?>
                        <li><a href="<?php echo esc_sql( get_category_link( $category->cat_ID ) ); ?>"><?php echo $category->cat_name; ?></a></li>
                    <?php endforeach;

                    echo '</ul>';
                endif;
            endif; ?>

        </div>
    </div>
</header>