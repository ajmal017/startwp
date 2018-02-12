<?php

/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitstarter
 */

?>
<article id="post-<?php the_ID();?>" <?php post_class( 'card card--post' );?>>

	<?php 

	if( is_sticky() ):

		global $wp_filesystem;
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		WP_Filesystem();
		
		printf('<div class="card__sticky">%1$s</div>',
		$wp_filesystem->get_contents(locate_template('assets/svg/star.php')) 
	);
	endif;

	$format = get_post_format();

	switch ($format) {
		case 'gallery':
			$post_meta = get_post_meta(get_the_ID(), 'post_gallery_input', false);
			reset($post_meta);

			if( !empty( $post_meta) ) :
				$images_id = explode(',', current( $post_meta ));
				$image_size = array(700, 500);

				echo '<div class="card-gallery__slider js-gallery__slider">';
				foreach( $images_id as $image_id){
				
					$image = wp_get_attachment_image_src($image_id, $image_size);

					printf(
						'<img class="card-gallery__item" src="%s" width="%s" height="%s" srcset="%s" alt="%s">',
						$image[0],
						$image[1],
						$image[2],
						wp_get_attachment_image_srcset($image_id),
						get_post_meta($image_id, '_wp_attachment_image_alt', true)
					);

				}
				echo '</div>';
			else:
				if (has_post_thumbnail()) :
	
					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'bitstarter-card-image');

					$image_srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id());
				?>
				<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<img class="card__image" src="<?php echo $image[0]; ?>" srcset="<?php echo $image_srcset; ?>" />
				</a>

				<?php else : ?>

					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<img class="card__image"  src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"/>
					</a>

				<?php endif; 

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
					$audio = sprintf('[soundcloud  url="%s" %s ]', $url, 'params="color=#ff5500&auto_play=false&visual=true"  iframe="true"');
				}

			}
				

			if($type == 'sc'):
			?>

				<div class="card-player" >
					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<img class="card__image"  src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"/>
						
					</a>
					<div class="card-player__wrapper card-player__wrapper--sc"><?php echo do_shortcode($audio); ?></div>
				</div>

			<?php elseif (has_post_thumbnail() && $type == 'wp') :

				$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'bitstarter-card-image');

				$image_srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id());
				
				?>
				<div class="card-player" >
					<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
						<img class="card__image" src="<?php echo $image[0]; ?>" srcset="<?php echo $image_srcset; ?>" />
					</a>
					<div class="card-player__wrapper card-player__wrapper--wp"><?php echo do_shortcode($audio); ?></div>
				</div>
			<?php elseif($type == 'wp') : ?>
				<div class="card-player" >
					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<img class="card__image" src="<?php echo $image[0]; ?>" srcset="<?php echo $image_srcset; ?>"/>
						
					</a>
					<div class="card-player__wrapper card-player__wrapper--wp"><?php echo do_shortcode($audio); ?></div>
				</div>
			<?php elseif( has_post_thumbnail() ): 

					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'bitstarter-card-image');

					$image_srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id());

				?>
				<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<img class="card__image" src="<?php echo $image[0]; ?>" srcset="<?php echo $image_srcset; ?>"/>
				</a>

			<?php else : ?>
		
					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<img class="card__image" src="<?php echo $image[0]; ?>" srcset="<?php echo $image_srcset; ?>"/>
					</a>

			<?php endif;
		break;

		case('video'):
			$url = trim( get_post_meta(get_the_ID(), 'post_video_file', true) );
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
			
			<div class="card-player" > 
				<a class="card__toplink" href="<?php the_permalink(); ?>">
					<img class="card__image"  src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"/>
				</a>
				<div class="card-player__wrapper"><?php echo do_shortcode( $video ); ?></div>
			</div>
			<?php
		break;

		// standard
		default:
			if (has_post_thumbnail()) :
				
					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'bitstarter-card-image');

					$image_srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id());
					
				?>
				<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<img class="card__image" src="<?php echo $image[0]; ?>" srcset="<?php echo $image_srcset; ?>"/>
				</a>

			<?php else : 
				?>

				<a class="card__toplink" href="<?php the_permalink(); ?>">
					<img class="card__image"  src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"/>
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
			<?php 
				if($format == 'quote'){
				  $c = get_the_excerpt();
				  if( strpos($c, 'blockquote') > 0 ){
					  echo $c;
				  }else{
					echo '<blockquote><strong>' . $c . '</strong></blockquote>';
				  }
				}else{
					 $c = get_the_excerpt();
					 echo '<p>' . $c . '</p>'; 
				 } ?>
		</div>
		<!-- READ MORE-->
		<?php bitstarter_permabutton(
			array(
			'classes' => 'btn btn-primary',
			'title' =>  __( 'READ MORE', 'bitstarter')
			)
		);?>

		<?php if ( 'post' === get_post_type() ) {?>
			<div class="card-meta">
				<?php bitstarter_posted_by();?>
				<span class="card-meta__delimiter" > | </span>
				<?php bitstarter_posted_on();?>
				<?php bitstarter_comments_number( $format == 'quote' ? '5':'2' );?>
			</div><!-- .card-meta -->
		<?php } ?>
	</div>
	<!-- .card__content -->
</article><!-- #post-## -->

