<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitcoin
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


					if ($type == 'sc') : ?>

						<div class="post-player" >
							<div class="post-player__wrapper post-player__wrapper--sc"><?php echo do_shortcode($audio); ?></div>
						</div>

					<?php elseif (has_post_thumbnail() && $type == 'wp') : ?>
						<div class="post-player" >
						<?php

							$image_id = get_post_thumbnail_id();
							$image = wp_get_attachment_image_src($image_id, 'large');

							
							printf(
								'<img class="post__image" src="%s" width="%s" height="%s" srcset="%s" alt="%s">',
								$image[0],
								$image[1],
								$image[2],
								wp_get_attachment_image_srcset($image_id, 'large'),
								get_post_meta($image_id, '_wp_attachment_image_alt', true)
							);
							?>
						
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
						<div class="post-player__wrapper"><?php echo do_shortcode($video); ?></div>
					</div>
				<?php	
				break;

				case ('quote'):
				
					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'bitcoin-featured-image'); ?>

					
					<div class="entry-featured__qoute" style="background-image: url('<?php echo bitcoin_get_inline_background_image($image[0]); ?>');">
						<aside class="entry-featured__qoute__bkgrnd">
							<?php the_excerpt (); ?>
						</aside>
					</div>
					<?php
				break;
				default:
					if (has_post_thumbnail()) :					

						$image_id = get_post_thumbnail_id();
						$image = wp_get_attachment_image_src($image_id, 'large');


							printf(
								'<img class="post__image" src="%s" width="%s" height="%s" srcset="%s" alt="%s">',
								$image[0],
								$image[1],
								$image[2],
								wp_get_attachment_image_srcset($image_id, 'large'),
								get_post_meta($image_id, '_wp_attachment_image_alt', true)
							);

					endif;

		}

			
			?>
		</div>
		<div class="entry-header__content">
			<div class="entry-header-categories">
				<?php
				$post_categories = wp_get_post_categories( $post->ID );
				if ( ! is_wp_error( $post_categories ) && count($post_categories) ) { ?>
					<ul class="entry-header-categories__links">
						<?php foreach ( $post_categories as $c ) {
							$cat = get_category( $c );
							echo '<li><a class="category-link" href="' . esc_sql( get_category_link( $cat->cat_ID ) ) . '">' . $cat->name . '</a></li>';
						} ?>
					</ul>
				<?php } ?>
			</div><!-- .entry-header__categories -->
		</div><!-- .entry-header__content -->
		<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

		the_content();

		?> <div class="entry-meta"> <?php
			?>  <div class="entry-meta__1stLine"> <?php
				bitcoin_posted_by();

				?> <span class="entry-meta__delimiter" > | </span> <?php

				bitcoin_posted_on();

				bitcoin_comments_number();

				bitcoin_likes();
				
				get_template_part('template-parts/social-share')

			?> </div> <?php
		?> <hr/> <?php
										
		bitcoin_tags();

		?> </div> <?php

		wp_link_pages( );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

