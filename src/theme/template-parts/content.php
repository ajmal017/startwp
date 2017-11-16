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
	<?php if ( has_post_thumbnail() ) {
	
	$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'listable-card-image' ); ?>
		<a class="card__toplink card__toplink--hasPic" href="<?php the_permalink();?>">
			<aside class="card__image" style="background-image: url('<?php echo listable_get_inline_background_image( $image[0] );?>');"></aside>
		</a>
	<?php } else {?>
		<a class="card__toplink" href="<?php the_permalink();?>">
			<aside class="card__image"  style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.png')"></aside>
		</a>
	<?php } ?>
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
			<?php the_excerpt();?>
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
			</div><!-- .entry-meta -->
		<?php } ?>
	</div>
	<!-- .entry-header -->
</article><!-- #post-## -->

