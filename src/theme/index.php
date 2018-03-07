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
 * @package Bitstarter
 */

get_header();
?>

<?php 
	if(!( is_front_page() && is_home() )) {
		get_template_part( 'template-parts/content', 'hero' ); 
	}
?>	


<div class="content-area__wrapper">
	
	<?php do_action('bitstarter_before_posts_loop'); ?>
	
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">	
			
			<?php if (have_posts()) : ?>

			<div class="postcards">
				<div class="grid grid--<?php bitstarter_blog_style(); ?>" id="posts-container">
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
<?php do_action('bitstarter_after_posts_loop'); ?>
</div>

<?php
get_footer();
