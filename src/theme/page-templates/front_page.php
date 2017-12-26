<?php
/**
 * Template Name: Front Page
 *
 * @package Bitcoin
 */

get_header();

global $post; ?> 

	<div id="primary" class="frontpage-area">
		<main id="main" class="site-main" role="main">
			<div class="frontpage-area__wrapper">	
				<?php
				while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if ( $post->post_content ): ?>
							<div class="entry-content">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>
						<!-- .entry-content -->

					</article><!-- #post-## -->

				<?php endwhile; // End of the loop. ?>
			</div>
		</main>
		<!-- #main -->
	</div><!-- #primary -->
	
<?php
get_footer();