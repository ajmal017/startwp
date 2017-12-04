<?php

/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

?>
<article id="post-<?php the_ID();?>" <?php post_class( 'card card--post' );?>>

	<?php 

	$format = get_post_format();



	if( is_sticky() ):
		printf('<div class="card__sticky">%1$s</div>',
		 	file_get_contents(locate_template('assets/svg/star.php')) 
		);
	endif;


	switch ($format) {
		case 'gallery':
			$post_meta = get_post_meta(get_the_ID(), 'gallery_post_input', false);
			reset($post_meta);

			if( !empty( $post_meta) ) :
				$images_id = explode(',', current( $post_meta ));
				$image_size = array(350, 250);

				echo '<div class="card-gallery__slider">';
				foreach( $images_id as $image_id){
				
					$image = wp_get_attachment_image_src($image_id, $image_size);

					printf(
						'<img class="card-gallery__item" src="%s" width="%s" height="%s" srcset="%s" alt="%s">',
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

		case 'audio' :
			$url = trim( get_post_meta(get_the_ID(), 'post_audio_file', true) );
			$type = get_post_meta(get_the_ID(), 'post_audio_type', true);
			$audio = '';
			

			if( $type == 'wp' ) {
				
				if (filter_var($url, FILTER_VALIDATE_URL)) {
					$audio = sprintf('[audio preload="metadata" src="%s"]', $url);
				}

			} elseif( $type == 'sc') {

				if (filter_var($url, FILTER_VALIDATE_URL)) {
					$audio = sprintf('[soundcloud  url="%s" %s ]', $url, 'params="color=#ff5500&auto_play=false&visual=true" width="100%" height="250" iframe="true"');
				}

			}
				

			if($type == 'sc'):
			?>

				<div class="card-player" >
					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<aside class="card__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')">
						</aside>
						
					</a>
					<div class="card-player__wrapper card-player__wrapper--sc"><?php echo do_shortcode($audio); ?></div>
				</div>

			<?php elseif (has_post_thumbnail() && $type == 'wp') :

				$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'listable-card-image'); ?>
				<div class="card-player" >
					<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
						<aside class="card__image" style="background-image: url('<?php echo listable_get_inline_background_image($image[0]); ?>');"></aside>
					</a>
					<div class="card-player__wrapper"><?php echo do_shortcode($audio); ?></div>
				</div>
			<?php elseif($type == 'wp') : ?>
				<div class="card-player" >
					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<aside class="card__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')">
						</aside>
						
					</a>
					<div class="card-player__wrapper"><?php echo do_shortcode($audio); ?></div>
				</div>
			<?php endif; 
		
		break;

		case('video'):
			$url = trim( get_post_meta(get_the_ID(), 'post_video_file', true) );
			$type = get_post_meta(get_the_ID(), 'post_video_type', true);
			$video = '';
			

			if ($type == 'vi') {
				if (filter_var($url, FILTER_VALIDATE_URL)) {
				
					$video = sprintf('[vimeo %s %s ]', $url, 'w=350%&h=250%');
				}
				

			} elseif ($type == 'yt') {

				if (filter_var($url, FILTER_VALIDATE_URL)) {
					$video = sprintf('[youtube %s%s ]', $url, '&w=350&h=250&showinfo=0&rel=0');
				}

			}
			?>
			
			<div class="card-player" > 
				<a class="card__toplink" href="<?php the_permalink(); ?>">
						<aside class="card__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')">
						</aside>
				</a>
				<div class="card-player__wrapper"><?php echo do_shortcode( $video ); ?></div>
			</div>
			<?php
		break;

		default:
			if (has_post_thumbnail()) :

					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'listable-card-image'); ?>
				<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<aside class="card__image" style="background-image: url('<?php echo listable_get_inline_background_image($image[0]); ?>');"></aside>
				</a>

			<?php else : ?>

				<a class="card__toplink" href="<?php the_permalink(); ?>">
					<aside class="card__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"></aside>
				</a>

			<?php endif; 

	}


	
	?>

	<div class="card__content">
		<?php if ( 'post' === get_post_type() ) { ?>
			<div class="card__categories">
				<?php $categories = get_the_category();

				if ( count( $categories ) ) { ?>
					<ul class="card__links">
						<?php foreach ( $categories as $category ) { ?>
							<li><a href="<?php echo esc_sql( get_category_link($category->cat_ID) );?>"><?php echo $category->name;?></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div><!-- .card__categories -->
		<?php } ?>
		<?php the_title( sprintf( '<h2 class="card__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );?>
		<div class="card__excerpt">
			<?php the_excerpt(); ?>
		</div>
		<!-- READ MORE-->
		<?php bitcoin_permabutton(
			array(
			'classes' => 'btn btn-primary',
			'title' =>  __( 'READ MORE', 'bitcoin')
			)
		);?>

		<?php if ( 'post' === get_post_type() ) {?>
			<div class="card-meta">
				<?php bitcoin_posted_by();?>
				<span class="card-meta__delimiter" > | </span>
				<?php bitcoin_posted_on();?>
				<?php bitcoin_comments_number();?>
			</div><!-- .card-meta -->
		<?php } ?>
	</div>
	<!-- .card__content -->
</article><!-- #post-## -->

