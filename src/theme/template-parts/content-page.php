<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitstarter
 */

?> 

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		$content = get_the_content();
		$css = $title = '';
		if ( has_shortcode( $content  , 'vc_row' ) ) {
			$css = 'shortcode';
		}else{
			$title = get_the_title();
			$content = '<p>' . $content . '</p>';
		}
	?>

	<div class="entry-content <?php echo esc_attr($css); ?>">
		<?php
			if( !empty($title) ){
				echo '<h1>' . $title . '</h1>';
			}
			echo do_shortcode($content);
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bitstarter' ),
				'after'  => '</div>',
			) );

			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'bitstarter' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

