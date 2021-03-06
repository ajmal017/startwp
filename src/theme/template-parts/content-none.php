<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bitstarter
 */

?>

<div class="entry-content">
	<h3 class="hero-title"><?php esc_html_e( 'Nothing Found', 'bitstarter' ); ?></h3>
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'bitstarter' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'bitstarter' ); ?></p>
	
		<?php get_search_form(); ?>
		

	<?php else : ?>

		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'bitstarter' ); ?></p>
		
		<a href="<?php echo esc_url(home_url()) ?>" class="btn"><?php esc_html_e('GO HOME','bitstarter'); ?></a>


	<?php endif; ?>
</div>
