<?php
/**
 * Template Name: Front Page
 *
 * @package Bitstarter
 */

get_header();

global $post; ?> 

<div class="frontpage-area__wrapper">	

	<div id="primary" class="frontpage-area">
		<main id="main" class="site-main" role="main">
				<?php
				while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if ( $post->post_content ): ?>
							
								<?php the_content(); ?>
							
						<?php endif; ?>
						<!-- .entry-content -->

					</article><!-- #post-## -->

				<?php endwhile; // End of the loop. ?>
		</main>
		<!-- #main -->
	</div><!-- #primary -->
	
</div>
<?php
get_footer();