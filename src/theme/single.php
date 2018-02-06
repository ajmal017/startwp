<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Bitstarter
 */

get_header(); ?>
<div class="content-area__wrapper">

	<?php do_action('bitstarter_before_posts_loop'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>
					
							<?php get_template_part( 'template-parts/content', 'single' ); ?>

							<?php get_template_part('template-parts/related', 'posts'); ?>
							<?php
								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
							?>

					<?php endwhile; // End of the loop.

					wp_link_pages();
					
					?>
		

		</main><!-- #main -->
	</div><!-- #primary -->
<?php do_action('bitstarter_after_posts_loop'); ?>
</div>
<?php get_footer(); ?>
