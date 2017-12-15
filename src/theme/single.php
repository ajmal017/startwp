<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Listable
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="content-area__wrapper">
				<?php do_action('bitcoin_before_posts_loop'); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="content-area__wrapperIn">
							<?php get_template_part( 'template-parts/content', 'single' ); ?>

							<?php
								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
							?>
						</div>


					<?php endwhile; // End of the loop. ?>
				<?php do_action('bitcoin_after_posts_loop'); ?>
		
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->


<?php get_footer(); ?>
