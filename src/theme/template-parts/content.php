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
		
					$attachid = get_post_thumbnail_id();
					$image = wp_get_attachment_image_src($attachid, 'bitstarter-card-image');
					$image_srcset = wp_get_attachment_image_srcset($attachid);
					$image_sizes = wp_get_attachment_image_sizes($attachid, 'bitstarter-card-image');
				?>
				<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<img class="card__image" alt="card__image" src="<?php echo esc_attr( $image[0] ); ?>"  <?php if ($image_srcset != ''): ?> srcset="<?php echo esc_attr($image_srcset); ?>" sizes="<?php echo esc_attr($image_sizes); ?>" <?php endif; ?> />
				</a>

				<?php else : ?>

					<a class="card__toplink card__toplink--placeholder" href="<?php the_permalink(); ?>">
						<img class="card__image" alt="card__image" src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png"/>
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
					$audio = sprintf('[audio preload="metadata" src="%s"]', esc_url( $url ) );
				}

			} elseif( $type == 'sc') {

				if (filter_var($url, FILTER_VALIDATE_URL)) {
					$audio = sprintf('[soundcloud  url="%s" %s ]', esc_url( $url ), 'params="color=#ff5500&auto_play=false&visual=true"  iframe="true"');
				}

			}
				

			if($type == 'sc' &&  $audio !== ''):
			?>

				<div class="card-player" >
					<a class="card__toplink card__toplink--placeholder" href="<?php the_permalink(); ?>">
						<img class="card__image" alt="placeholder" src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png"/>
						
					</a>
					<div class="card-player__wrapper card-player__wrapper--sc"><?php echo do_shortcode($audio); ?></div>
				</div>

			<?php elseif (has_post_thumbnail() && $type == 'wp') :

				$attachid = get_post_thumbnail_id();
				$image = wp_get_attachment_image_src($attachid, 'bitstarter-card-image');
				$image_srcset = wp_get_attachment_image_srcset($attachid);
				$image_sizes = wp_get_attachment_image_sizes($attachid, 'bitstarter-card-image');
				
				?>
				<div class="card-player" >
					<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
						<img class="card__image" alt="card__image" src="<?php echo esc_attr( $image[0] ); ?>"   <?php if ($image_srcset != ''): ?> srcset="<?php echo esc_attr( $image_srcset ); ?>"  sizes="<?php echo esc_attr( $image_sizes ); ?>"<?php endif;?> />
					</a>
					<div class="card-player__wrapper card-player__wrapper--wp"><?php echo do_shortcode($audio); ?></div>
				</div>
			<?php elseif($type == 'wp') : ?>
				<div class="card-player" >
					<a class="card__toplink" href="<?php the_permalink(); ?>">
						<img class="card__image" alt="card__image" src="<?php echo esc_attr( $image[0] ); ?>" srcset="<?php echo esc_attr( $image_srcset ); ?>"/>
						
					</a>
					<div class="card-player__wrapper card-player__wrapper--wp"><?php echo do_shortcode($audio); ?></div>
				</div>
			<?php elseif( has_post_thumbnail() ): 
					$attachid = get_post_thumbnail_id();
					$image = wp_get_attachment_image_src($attachid, 'bitstarter-card-image');
					$image_srcset = wp_get_attachment_image_srcset($attachid);
					$image_sizes = wp_get_attachment_image_sizes($attachid, 'bitstarter-card-image');

				?>
				<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<img class="card__image" alt="card__image" src="<?php echo esc_attr( $image[0] ); ?>"  <?php if ($image_srcset != ''): ?> srcset="<?php echo esc_attr( $image_srcset ); ?>" sizes="<?php echo esc_attr( $image_sizes ); ?>"<?php endif;?>/>
				</a>

			<?php else : ?>
		
					<a class="card__toplink card__toplink--placeholder" href="<?php the_permalink(); ?>">
						<img class="card__image "  alt="placeholder" src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png"/>
					</a>

			<?php endif;
		break;

		case('video'):
			$url = trim( get_post_meta(get_the_ID(), 'post_video_file', true) );
			$type = get_post_meta(get_the_ID(), 'post_video_type', true);
			$video = '';
			

			if ($type == 'vi') {
				if (filter_var($url, FILTER_VALIDATE_URL)) {
				
					$video = sprintf('[vimeo %s %s ]', esc_url( $url ), '');
				}
				

			} elseif ($type == 'yt') {

				if (filter_var($url, FILTER_VALIDATE_URL)) {
					$video = sprintf('[youtube %s%s ]', esc_url( $url ), '&showinfo=0&rel=0');
				}

			}
			if($video !== '' ):
				?>
				<div class="card-player" > 
					<a class="card__toplink card__toplink--placeholder" href="<?php the_permalink(); ?>">
						<img class="card__image" alt="placeholder" src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png"/>
					</a>
					<div class="card-player__wrapper"><?php echo do_shortcode( $video ); ?></div>
				</div>
				
				<?php
			endif;
		break;

		// standard
		default:
			if (has_post_thumbnail()) :
				
					$attachid = get_post_thumbnail_id();
					$image = wp_get_attachment_image_src($attachid, 'bitstarter-card-image');
					$image_srcset = wp_get_attachment_image_srcset($attachid);
					$image_sizes = wp_get_attachment_image_sizes($attachid, 'bitstarter-card-image');

					if( is_array($image) && !is_wp_error( $image )):	
				?>
					<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink(); ?>">
					<img class="card__image" alt="card__image" src="<?php echo esc_attr( $image[0] ); ?>" <?php if ($image_srcset != ''): ?> srcset="<?php echo esc_attr( $image_srcset ); ?>" sizes="<?php echo esc_attr( $image_sizes ); ?>"<?php endif;?>/>
					</a>

				<?php endif;
			else : 
				?>

				<a class="card__toplink card__toplink--placeholder" href="<?php the_permalink(); ?>">
					<img class="card__image " alt="placeholder" src="<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png"/>
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
						<?php $category = array_shift($categories); ?>
							<li><a href="<?php echo esc_attr( get_category_link($category->cat_ID) );?>"><?php echo esc_html($category->name);?></a></li>
							<li>
								<?php if ( count( $categories ) ) { ?>
								<nav class="card-menu js-card-menu">
									<div class="card-menu__contents js-card-menu-contents">
										<button class="card-menu__toggle js-card-menu-toggle">
											<span class="card-menu__title">
											<?php echo esc_html__( 'More categories', 'bitstarter' ); ?></span>
											<span class="card-menu__points js-card-menu-points">&bull;&bull;&bull;</span>
										</button>
										<ul class="card-menu__items js-card-menu-items">
											<?php foreach ($categories as $category) { ?>
												<li><a href="<?php echo esc_attr( get_category_link($category->cat_ID) );?>"><?php echo esc_html($category->name);?></a></li>
											<?php } ?>
										</ul>
									</div>
								</nav>
								<?php } ?>
							</li>
					</ul>
				<?php } ?>
			</div><!-- .card__categories -->
		<?php } ?>
		<?php the_title( sprintf( '<h2 class="card__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );?>
		<div class="card__excerpt">
			<?php
				if($format == 'quote'){
				  $c = get_the_excerpt();
				  if( strpos($c , 'blockquote') > 0 ){
					  echo wp_kses($c , bitstarter_allowed_html());
				  }else{
					echo '<blockquote><strong>' . wp_kses($c , bitstarter_allowed_html()) . '</strong></blockquote>';
				  }
				}else{
					$c  = get_the_excerpt();
					echo '<p>' .  wp_kses($c , bitstarter_allowed_html()) . '</p>'; 
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
 