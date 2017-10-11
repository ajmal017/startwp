<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			$page_for_posts = get_option( 'page_for_posts' );
			$the_random_hero = listable_get_random_hero_object( $page_for_posts );
			$has_image       = false;	
			?>

			<?php if ( ( empty( $the_random_hero ) || property_exists( $the_random_hero, 'post_mime_type' ) || strpos( $the_random_hero->post_mime_type, 'video' ) !== false ) && is_object( $the_random_hero ) && property_exists( $the_random_hero, 'post_mime_type' ) && strpos( $the_random_hero->post_mime_type, 'image' ) !== false ) {
					$has_image = wp_get_attachment_url( $the_random_hero->ID );
				} ?>

			<header class="page-header<?php if($has_image) echo ' has-featured-image'; ?>" >
				<div class="page-header-background"<?php if ( ! empty( $has_image ) ) {
					echo ' style="background-image: url(' . listable_get_inline_background_image( $has_image ) . ');"';
				} ?>>
 
					<?php if ( pixelgrade_option( 'site_blogheroarea_transparent', true ) == true ):?>
					
						<div class="page-header__overlay1">
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
								<rect id="page-header__overlay1" x="0" y="0" width="100%" height="100%"  mask="url(#Mask1)" />
							</svg>
						</div>
						<div class="page-header__overlay2">
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
								<rect id="page-header__overlay2" x="0" y="0" width="100%" height="100%" mask="url(#Mask2)" />
							</svg>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $the_random_hero ) && property_exists( $the_random_hero, 'post_mime_type' ) && strpos( $the_random_hero->post_mime_type, 'video' ) !== false ) {
						$mimetype = str_replace( 'video/', '', $the_random_hero->post_mime_type );
						if ( has_post_thumbnail( $the_random_hero->ID ) ) {
							$image = wp_get_attachment_url( get_post_thumbnail_id( $the_random_hero->ID ) );
							$poster = ' poster="' . $image . '" ';
						} else {
							$poster = ' ';
						}
						echo do_shortcode( '[video ' . $mimetype . '="' . wp_get_attachment_url( $the_random_hero->ID ) . '"' . $poster . 'loop="true" autoplay="true"][/video]' );
					} ?>
				</div>
				
				<div class="header-content">
					<h1 class="page-title"><?php echo get_the_title( $page_for_posts ); ?></h1>

					<?php
					$categories = get_categories();
					if( $categories ):
						echo '<ul class="category-list">';

						foreach ( $categories as $category ): ?>
							<li><a href="<?php echo esc_sql( get_category_link( $category->cat_ID ) ); ?>"><?php echo $category->cat_name; ?></a></li>
						<?php endforeach;

						echo '</ul>';
					endif; ?>
				</div>
			</header>
		
		

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>

		<div class="postcards">
			<div class="grid" id="posts-container">
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
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
				<?php endwhile; ?>
			</div>
			<?php the_posts_navigation(); ?>
		</div>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
