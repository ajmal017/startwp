<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

$has_image = false; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-featured">
			<?php
				$format = get_post_format();

				switch ($format) {
					case 'gallery':
						$post_meta = get_post_meta(get_the_ID(), 'post_gallery_input', false);
						reset($post_meta);

						if (!empty($post_meta)) :
							$images_id = explode(',', current($post_meta));
						$image_size = array(350, 250);

						echo '<div class="post-gallery__slider js-gallery__slider">';
						foreach ($images_id as $image_id) {

							$image = wp_get_attachment_image_src($image_id, $image_size);

							printf(
								'<img class="post-gallery__item" src="%s" width="%s" height="%s" srcset="%s" alt="%s">',
								$image[0],
								$image[1],
								$image[2],
								wp_get_attachment_image_srcset($image_id, $image_size),
								get_post_meta($image_id, '_wp_attachment_image_alt', true)
							);

						}
						echo '</div>';
						endif;
						break;

					case 'audio':
						$url = trim(get_post_meta(get_the_ID(), 'post_audio_file', true));
						$type = get_post_meta(get_the_ID(), 'post_audio_type', true);
						$audio = '';


						if ($type == 'wp') {

							if (filter_var($url, FILTER_VALIDATE_URL)) {
								$audio = sprintf('[audio preload="metadata" src="%s"]', $url);
							}

						} elseif ($type == 'sc') {

							if (filter_var($url, FILTER_VALIDATE_URL)) {
								$audio = sprintf('[soundcloud  url="%s" %s ]', $url, 'params="color=#ff5500&auto_play=false&visual=true" width="100%" height="250" iframe="true"');
							}

						}


						if ($type == 'sc') :
						?>

						<div class="post-player" >
							<a class="post__toplink" href="<?php the_permalink(); ?>">
								<aside class="post__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')">
								</aside>
								
							</a>
							<div class="post-player__wrapper post-player__wrapper--sc"><?php echo do_shortcode($audio); ?></div>
						</div>

					<?php elseif (has_post_thumbnail() && $type == 'wp') :

					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'listable-post-image'); ?>
						<div class="post-player" >
							<a class="post__toplink post__toplink--hasPic" href="<?php the_permalink(); ?>">
								<aside class="post__image" style="background-image: url('<?php echo listable_get_inline_background_image($image[0]); ?>');"></aside>
							</a>
							<div class="post-player__wrapper post-player__wrapper--wp"><?php echo do_shortcode($audio); ?></div>
						</div>
					<?php elseif ($type == 'wp') : ?>
						<div class="post-player" >
							<a class="post__toplink" href="<?php the_permalink(); ?>">
								<aside class="post__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')">
								</aside>
								
							</a>
							<div class="post-player__wrapper post-player__wrapper--wp"><?php echo do_shortcode($audio); ?></div>
						</div>
					<?php endif;

				break;

			case ('video'):
				$url = trim(get_post_meta(get_the_ID(), 'post_video_file', true));
				$type = get_post_meta(get_the_ID(), 'post_video_type', true);
				$video = '';


				if ($type == 'vi') {
					if (filter_var($url, FILTER_VALIDATE_URL)) {

						$video = sprintf('[vimeo %s %s ]', $url, '');
					}


				} elseif ($type == 'yt') {

					if (filter_var($url, FILTER_VALIDATE_URL)) {
						$video = sprintf('[youtube %s%s ]', $url, '&showinfo=0&rel=0');
					}

				}
				?>
					
					<div class="post-player" > 
						<a class="post__toplink" href="<?php the_permalink(); ?>">
								<aside class="post__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')">
								</aside>
						</a>
						<div class="post-player__wrapper"><?php echo do_shortcode($video); ?></div>
					</div>
					<?php
				break;

			default:
				if (has_post_thumbnail()) :

					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'listable-post-image'); ?>
						<a class="post__toplink post__toplink--hasPic" href="<?php the_permalink(); ?>">
							<aside class="post__image" style="background-image: url('<?php echo listable_get_inline_background_image($image[0]); ?>');"></aside>
						</a>

					<?php else : ?>

						<a class="post__toplink" href="<?php the_permalink(); ?>">
							<aside class="post__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"></aside>
						</a>

					<?php endif;

		}

			?>
		</div>
		<div class="header-content">
			<div class="entry-meta">
				<?php
				$post_categories = wp_get_post_categories( $post->ID );
				if ( ! is_wp_error( $post_categories ) ) {
					foreach ( $post_categories as $c ) {
						$cat = get_category( $c );
						echo '<a class="category-link" href="' . esc_sql( get_category_link( $cat->cat_ID ) ) . '">' . $cat->name . '</a>';
					}
				} ?>

			</div><!-- .entry-meta -->
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<span class="entry-subtitle"><?php echo get_the_excerpt(); ?></span>

			<?php if ( function_exists( 'sharing_display' ) ) : ?>
				<?php sharing_display( '', true ); ?>
			<?php endif; ?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

		the_content();

		bitcoin_posted_on();
		$query = bitcoin_get_related_posts();

		if ($query->have_posts()) : ?>
			<div class="entry-related grid grid--<?php echo pixelgrade_option('blog_type_style'); ?>">

				<?php while ($query->have_posts()) : $query->the_post(); ?>
					
					<div class="grid__item  postcard">
						<?php get_template_part('template-parts/content', get_post_format()); ?>
					</div>
				
				<?php endwhile; ?>
				
			</div>
		<?php endif;
		wp_reset_postdata();
		
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listable' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

