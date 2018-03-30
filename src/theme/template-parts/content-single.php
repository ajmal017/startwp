<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitstarter
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

						$image = wp_get_attachment_image_src($image_id, 'bitstarter-featured-image');
						$image_sizes = wp_get_attachment_image_sizes($image_id, 'bitstarter-featured-image');

						printf(
							'<img class="post-gallery__item" src="%s" width="%s" height="%s" srcset="%s" alt="%s" sizes="%s">',
							$image[0],
							$image[1],
							$image[2],
							wp_get_attachment_image_srcset($image_id, 'bitstarter-featured-image'),
							get_post_meta($image_id, '_wp_attachment_image_alt', true) || 'post',
							$image_sizes
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
							$audio = sprintf('[audio preload="metadata" src="%s"]', esc_url($url));
						}

					} elseif ($type == 'sc') {

						if (filter_var($url, FILTER_VALIDATE_URL)) {
							$audio = sprintf('[soundcloud  url="%s" %s ]', esc_url($url), 'params="color=#ff5500&auto_play=false&visual=true"  iframe="true"');
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
							$image_sizes = wp_get_attachment_image_sizes($image_id, 'bitstarter-featured-image');
							
							printf(
								'<img class="post__image" src="%s" width="%s" height="%s" srcset="%s" alt="%s">',
								$image[0],
								$image[1],
								$image[2],
								wp_get_attachment_image_srcset($image_id, 'large'),
								get_post_meta($image_id, '_wp_attachment_image_alt', true) || 'post'
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

							$video = sprintf('[vimeo %s %s ]', esc_url($url), '');
						}


					} elseif ($type == 'yt') {

						if (filter_var($url, FILTER_VALIDATE_URL)) {
							$video = sprintf('[youtube %s%s ]', esc_url($url), '&showinfo=0&rel=0');
						}

					}
					?>
							
					<div class="post-player" >
						<div class="post-player__wrapper"><?php echo do_shortcode($video); ?></div>
					</div>
				<?php	
				break;

				case ('quote'):
				
					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'bitstarter-featured-image'); ?>

					
					<div class="entry-featured__qoute" style="background-image: url('<?php echo bitstarter_get_inline_background_image($image[0]); ?>');">
						<aside class="entry-featured__qoute__bkgrnd">
							<?php 
								$c = get_the_excerpt();
								if( strpos($c, 'blockquote') > 0 ){
									echo wp_kses( $c, bitstarter_allowed_html()) ;
								}else{
									echo '<blockquote><strong>' . wp_kses( $c, bitstarter_allowed_html()) . '</strong></blockquote>';
								}
							?>
						</aside>
					</div>
					<?php
				break;
				default:
					if (has_post_thumbnail()) :					

						$image_id = get_post_thumbnail_id();
						$image = wp_get_attachment_image_src($image_id, 'large');
						$image_sizes = wp_get_attachment_image_sizes($image_id, 'bitstarter-featured-image');

							printf(
								'<img class="post__image" src="%s" width="%s" height="%s" srcset="%s" alt="%s" sizes="%s">',
								$image[0],
								$image[1],
								$image[2],
								wp_get_attachment_image_srcset($image_id, 'large'),
								get_post_meta($image_id, '_wp_attachment_image_alt', true) || 'post',
								$image_sizes
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
							echo '<li><a class="category-link" href="' . esc_attr( get_category_link( $cat->cat_ID ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
						} ?>
					</ul>
				<?php } ?>
			</div><!-- .entry-header-categories -->
		</div><!-- .entry-header__content -->
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

		the_content();

		?> <div class="entry-meta"> <?php
			?>  <div class="entry-meta__1stLine"> <?php
				bitstarter_posted_by();

				?> <span class="entry-meta__delimiter" > | </span> <?php

				bitstarter_posted_on();

				bitstarter_comments_number();

				if( class_exists('IodigitalThemeFunction') ){
					if ( bitstarter_get_option('likes', true) ){
						IodigitalThemeFunction::likes();
					}

					if ( bitstarter_get_option('shares', true) ){
						IodigitalThemeFunction::share();
					}
				}

			?> </div> <?php
		?> <hr/> <?php
										
		bitstarter_tags();

		?> </div> <?php
		
			wp_link_pages();
			
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

