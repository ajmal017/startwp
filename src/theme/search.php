<?php
/**
 * Search results archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitstarter
 */

get_header(); 

?>
<?php get_template_part('template-parts/content', 'hero'); ?>

<div class="content-area__wrapper">

<div id="primary" class="content-area search-results">
	<main id="main" class="site-main" role="main">
		<?php if(have_posts()) : ?>								
					<div class="postcards">
						<div class="grid grid--<?php echo bitstarter_get_option('blog_type_style'); ?>" id="posts-container">
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<div class="grid__item  postcard">
								<?php

									/*
									* Include the Post-Format-specific template for the content.
									* If you want to override this in a child theme, then include a file
									* caslled content-___.php (where ___ is the Post Format name) and that will be used instead.
									*/
									get_template_part( 'template-parts/content', get_post_format() );
								?>
							</div>
						<?php endwhile; ?>
						</div>
							<?php the_posts_navigation(); ?>
						</div>

					</div>
					
				</div>	
			
	<?php endif; ?>
	</main><!-- #main -->
</div><!-- #primary -->

</div>


<?php get_footer(); ?>
