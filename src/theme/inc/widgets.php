<?php
/**
 * Register our sidebars and widgetized areas.
 *
 */
function listable_register_widget_areas() {

	register_sidebar( array(
		'name'          => '&#x1f537; ' . esc_html__( 'Sidebar', 'bitcoin' ),
		'id'            => 'sidebar',
		'before_widget' => '<div id="%1$s" class="widget  %2$s">',
		'after_widget' => '	</div>',
		'before_title'  => '<h5 class="widget-sidebar-title">',
		'after_title'   => '</h5>'
	) );


	register_sidebar( array(
		'name'          => '&#x1f535; ' . esc_html__('Footer Area Bottom Info', 'bitcoin' ),
		'id'            => 'footer-widget-area-social',
		'before_widget' => '<div id="%1$s" class="widget  %2$s">',
		'after_widget' => '	</div>',
		'before_title'  => '<h5 class="widget-sidebar-title">',
		'after_title'   => '</h5>'
	) );


	$footer_sidebar_number = (int) pixelgrade_option('footer_sidebar_number', 4, false);

	for($i = 0; $i < $footer_sidebar_number; $i++){
		register_sidebar(array(
			'name' => '&#x1f536; ' .  esc_html__('Footer Area ' . ($i + 1), 'bitcoin'),
			'id' => 'footer-widget-area-' . ($i + 1),
			'before_widget' => '<aside id="%1$s" class="widget  widget--footer  %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h5 class="widget-footer-title">',
			'after_title' => '</h5>',
		));
	}


	if( class_exists('WPCF7')){
		register_widget('ForIT_Footer_Forms_Widget' );
	}


}

add_action('widgets_init', 'listable_register_widget_areas' );


function bitcoin_sidebar(){
	// Output the sidebar.php
	global $post;
	$sidebar = pixelgrade_option('blog_sidebar');
	$show_on_posts_page = true;


	if( is_home() ){
		$show_on_posts_page = false;
		$show_on_posts_page = pixelgrade_option('blog_sidebar_posts');
	}

	if ( 'sidebar__none' != $sidebar && $show_on_posts_page ) {

		get_sidebar();

	}
	
}

add_action('bitcoin_after_posts_loop', 'bitcoin_sidebar');


class Listing_Comments_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'bitcoin_comments', // Base ID
			'&#x1F536; ' . esc_html__( 'Listing', 'listable' ) . ' &raquo; ' . esc_html__( 'Reviews', 'listable' ), // Name
			array( 'description' => esc_html__( 'A list of the recent reviews and the submission form.', 'listable' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $post;
		// If comments are open or we have at least one comment, load up the comment template.
		if ( ( comments_open() || get_comments_number() ) && 'preview' !== $post->post_status ) {
			echo $args['before_widget'];

			comments_template();

			echo $args['after_widget'];
		}
	}

	public function form( $instance ) {
		echo '<p>' . $this->widget_options['description'] . '</p>';
	}
} // class Listing_Comments_Widget

/**
 *  Display CF7 in footer sidebar
 */
class ForIT_Footer_Forms_Widget extends WP_Widget
{


	function __construct() {
		parent::__construct(
			'bitcoin_footer_form', // Base ID
			'&#x1F536; ' . esc_html__( 'Bitcoin', 'bitcoin' ) . ' &raquo; ' . esc_html__( 'Footer', 'bitcoin' ), // Name
			array( 'description' => esc_html__( 'Add a Contact Form 7 to your footer.', 'listable' ), ) // Args
		);
	}

	public function widget($args, $instance)
	{
		$title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
		$contact_form = !empty($instance['contact_form']) ? $instance['contact_form'] : false;

		if (!$contact_form)
			return;

		echo $args['before_widget'];

		if (!empty($title)) :
			echo $args['before_title'] . $title . $args['after_title'];
		endif;
		$output = do_shortcode('[contact-form-7 id="'. $contact_form.'"]');

		echo $output;

		echo $args['after_widget'];

	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		if (!empty($new_instance['title'])) {
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		}
		if (!empty($new_instance['contact_form'])) {
			$instance['contact_form'] = (int)$new_instance['contact_form'];
		}
		return $instance;
	}

	public function form($instance)
	{
		$title = isset($instance['title']) ? $instance['title'] : '';
		$contact_form = isset($instance['contact_form']) ? $instance['contact_form'] : '';

        // Get CF7 enties
		$cf = bitcoin_get_posts_array(array('post_type' => 'wpcf7_contact_form', 'numberposts' => -1));
        // If no menus exists, direct the user to go and create some.
		if (!$cf) {
			echo '<p>' . esc_html__('No forms have been created yet. Create some.', 'bitcoin') . '</p>';
			return;
		}

		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'forit') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('contact_form'); ?>"><?php esc_html_e('Select Menu:', 'forit'); ?></label>
            <select id="<?php echo $this->get_field_id('contact_form'); ?>" name="<?php echo $this->get_field_name('contact_form'); ?>">
                <option value="0"><?php esc_html_e('&mdash; Select &mdash;', 'forit') ?></option>
				<?php
					array_map(function($item){
						echo '<option value="' . $item['value'] . '"'
							. selected($contact_form,  $item['value'], false)
							. '>' . esc_html($item['name']) . '</option>';
					}, $cf);
				?>
            </select>
        </p>
    <?php

		}
	}