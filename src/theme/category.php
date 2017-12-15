<?php
/**
 * Category archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php get_template_part('template-parts/content', 'hero'); ?>	

 
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>

			<div class="postcards">
				<div class="grid grid--<?php echo pixelgrade_option('blog_type_style'); ?>">
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

<?php get_footer(); ?>
