<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bitstarter
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php wp_enqueue_script( 'comment-reply' ); ?>

	<?php if ( have_comments() ) : ?>
		<div class="comments-header">
		<h4 class="comments-title"><?php

			echo esc_html('Comments', 'bitstarter');
			
		?></h4>

		<span class="comments-number"><?php

			printf( // WPCS: XSS OK.
				esc_html( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'bitstarter' ) ),
				number_format_i18n( get_comments_number() ),
				'<span>' . get_the_title() . '</span>'
			);
		
		?></span>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?> 
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h4 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'bitstarter' ); ?></h4>

				<div class="nav-links">

					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'bitstarter' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'bitstarter' ) ); ?></div>

				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'callback' => 'bitstarter_shape_comment'
			) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
				<h4 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'bitstarter' ); ?></h4>

				<div class="nav-links">

					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'bitstarter' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'bitstarter' ) ); ?></div>

				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>

	<?php endif;

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'bitstarter' ); ?></p>
	<?php }

	$comment_args = array(
		'title_reply_to'       => __( 'Leave a Reply to %s', 'bitstarter' ),
		'title_reply_before'   => '<h4 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h4>',
	);

	comment_form( $comment_args );
	?>
</div><!-- #comments -->
