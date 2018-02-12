<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bitstarter
 */
?>

	</div><!-- #content -->

	<footer class="site-footer" role="contentinfo">
		<?php if ( is_active_sidebar( 'footer-widget-area-1' ) ) : ?>
			<?php
				
				$footer_sidebar_number = (int) bitstarter_get_option('footer_sidebar_number', 0, false);
				if($footer_sidebar_number > 0):
			
			?>
				
					<div id="footer-sidebar" class="widget-area__footer" role="complementary">
						
						<?php
							for($i = 0; $i < $footer_sidebar_number; $i++){
								echo '<div class="widget-area__footer__col widget-area__footer__col--' . $footer_sidebar_number . '">'; 
								dynamic_sidebar('footer-widget-area-' . ($i + 1));
								echo '</div>';
							}
						?>
						
					</div><!-- #primary-sidebar -->

				<?php endif; ?>
			
		<?php endif; ?>
		<div class="footer-infoarea">
			<div class="site-info">
				<?php
				$footer_copyright = bitstarter_get_option('footer_copyright');
				if ( $footer_copyright ) : ?>
					<div class="site-copyright-area">
						<?php echo $footer_copyright; ?>
					</div>
				<?php else: ?>
					<div class="site-copyright-area">
						<?php echo  esc_html__('Copyright © 2018 Bitstarter theme. All Rights Reserved.','bitstarter') ?>
					</div>
				<?php endif; ?>
			</div><!-- .site-info -->
			<div class="social-info">
				<?php dynamic_sidebar('footer-widget-area-social');?>
			</div>
		</div>
	</footer><!-- .site-footer -->
</div><!-- #page -->


<?php wp_footer(); ?>

</body>
</html>