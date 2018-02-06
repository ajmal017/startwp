<?php
        $query = bitstarter_get_related_posts();

		if ($query->have_posts()) : ?>
			<h4 class="entry-related__title"><?php echo esc_html('Related Posts','bitstarter'); ?></h4>
			<div class="entry-related grid grid--tile">

				<?php while ($query->have_posts()) : $query->the_post(); ?>
					
					<div class="grid__item  postcard">
						<?php get_template_part('template-parts/content', get_post_format()); ?>
					</div>
				
				<?php endwhile; ?>
				
			</div>
		<?php endif;
        wp_reset_postdata();