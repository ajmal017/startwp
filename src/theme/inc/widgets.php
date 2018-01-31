<?php
/**
 * Register our sidebars and widgetized areas.
 *
 */
function bitcoin_register_widget_areas() {

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


	$footer_sidebar_number = (int) bitcoin_get_option('footer_sidebar_number', 4, false);

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
		register_widget('Bitcoin_Footer_Forms_Widget' );
	}
	
	register_widget('Bitcoin_Coinmarketcap' );

	register_widget('Bitcoin_WPCOM_social_media_icons_widget' );

}


class Bitcoin_WPCOM_social_media_icons_widget extends WP_Widget {

	/**
	 * Defaults
	 *
	 * @var mixed
	 * @access private
	 */
	private $defaults;

	/**
	 * Services
	 *
	 * @var mixed
	 * @access private
	 */
	private $services;


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			'bitcoin_socials', // Base ID
			'&#x1f535; ' . esc_html__( 'Bitcoin', 'bitcoin' ) . ' &raquo; ' . esc_html__( 'Social Media Icons', 'bitcoin' ), // Name
			array( 'description' => esc_html__( 'A simple widget that displays social media icons.', 'bitcoin' ), ) // Args
		);

		$this->defaults = array(
			'facebook_username'   => '',
			'twitter_username'    => '',
			'linkedin_username'   => '',
			'instagram_username'  => ''
		);

		$this->services = array(
			'facebook'   => array( 'Facebook', 'https://www.facebook.com/%s/', file_get_contents(locate_template('assets/svg/social-fb.php', false, false )) ),
			'twitter'    => array( 'Twitter', 'https://twitter.com/%s/', file_get_contents(locate_template('assets/svg/social-tw.php', false, false ))),
			'linkedin'   => array( 'LinkedIn', 'https://www.linkedin.com/in/%s/',  file_get_contents(locate_template('assets/svg/social-in.php', false, false )) ),
			'instagram'  => array( 'Instagram', 'https://www.instagram.com/%s/',  file_get_contents(locate_template('assets/svg/social-ig.php', false, false )) )
		);
	
	}


	/**
	 * Widget Front End.
	 *
	 * @access public
	 * @param mixed $args Arguments.
	 * @param mixed $instance Instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
	

		$index = 10;
		$html = array();
		$alt_text = esc_attr__( 'View %1$s&#8217;s profile on %2$s', 'bitcoin' );
		foreach ( $this->services as $service => $data ) {
			list( $service_name, $url, $svg ) = $data;
			if ( ! isset( $instance[ $service . '_username' ] ) ) {
				continue;
			}
			$username = $link_username = $instance[ $service . '_username' ];
			if ( empty( $username ) ) {
				continue;
			}
			$index += 10;
			$predefined_url = false;

			/** Check if full URL entered in configuration, use it instead of tinkering **/
			if (
				in_array(
					parse_url( $username, PHP_URL_SCHEME ),
					array( 'http', 'https' )
				)
			) {
				$predefined_url = $username;

				// In case of a predefined link we only display the service name
				// for screen readers
				$alt_text = '%2$s';
			}


			if ( 'googleplus' === $service
				&& ! is_numeric( $username )
				&& substr( $username, 0, 1 ) !== '+'
			) {
				$link_username = '+' . $username;
			}
			if ( 'youtube' === $service && 'UC' === substr( $username, 0, 2 ) ) {
				$link_username = 'channel/' . $username;
			} else if ( 'youtube' === $service ) {
				$link_username = 'user/' . $username;
			}

			if ( ! $predefined_url ) {
				$predefined_url = sprintf( $url, $link_username );
			}

			$link = apply_filters(
				'bitcoin_social_media_icons_widget_profile_link',
				$predefined_url,
				$service
			);
			$html[ $index ] = sprintf(
				'<a href="%1$s" class="widget_bitcoin_socials__item" target="_blank"><span class="screen-reader-text">%3$s</span><i class="bitcoin-icon">%4$s</i></a>',
				esc_attr( $link ),
				esc_attr( $service ),
				sprintf( $alt_text, esc_html( $username ), $service_name ),
				$svg
			);
		}
		ksort( $html );
		$html = '<ul><li>' . join( '</li><li>', $html ) . '</li></ul>';

		$html = $args['before_widget'] . $html . $args['after_widget'];


		echo  $html;
	}

	/**
	 * Widget Settings.
	 *
	 * @access public
	 * @param mixed $instance Instance.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		
		foreach ( $this->services as $service => $data ) {
			list( $service_name, $url ) = $data;
			?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( $service . '_username' ) ); ?>">
					<?php
						/* translators: %s is a social network name, e.g. Facebook. */
						printf( __( '%s username:', 'bitcoin' ), $service_name );
					?>
				</label>
				<input
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( $service . '_username' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( $service . '_username' ) ); ?>"
						type="text"
						value="<?php echo esc_attr( $instance[ $service . '_username' ] ); ?>"
					/>
				</p>
			<?php
		}
	}

	/**
	 * Update Widget Settings.
	 *
	 * @access public
	 * @param mixed $new_instance New Instance.
	 * @param mixed $old_instance Old Instance.
	 * @return Instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = (array) $old_instance;
		foreach ( $new_instance as $field => $value ) {
			$instance[ $field ] = sanitize_text_field( $new_instance[ $field ] );
		}

		return $instance;
	}


} 







add_action('widgets_init', 'bitcoin_register_widget_areas' );




function bitcoin_sidebar(){
	// Output the sidebar.php
	global $post;
	$sidebar = bitcoin_get_option('blog_sidebar');
	$show_on_posts_page = true;


	if( is_home() ){
		$show_on_posts_page = false;
		$show_on_posts_page = bitcoin_get_option('blog_sidebar_posts');
	}

	if ( 'sidebar__none' != $sidebar && $show_on_posts_page ) {

		get_sidebar();

	}
	
}

add_action('bitcoin_after_posts_loop', 'bitcoin_sidebar');


class Bitcoin_Coinmarketcap extends WP_Widget {
	function __construct()
	{
		parent::__construct(
			'bitcoin_coinmarketcap', // Base ID
			'&#x1F536; ' . esc_html__( 'Bitcoin', 'bitcoin' ) . ' &raquo; ' . esc_html__( 'Cryptocurrency Monitor', 'bitcoin' ), // Name
			array( 'description' => esc_html__( 'A list of the Coin Market Cap cryptocurrencies', 'bitcoin' ), ) // Args
		);
	
	}


	public function widget($args, $instance)
	{
		$title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
		$currencies = !empty($instance['currencies']) ? $instance['currencies'] : false;

		echo $args['before_widget'];

		if (!empty($title)) :
			echo $args['before_title'] . $title . $args['after_title'];
		endif;


		$output = '';

		if( is_array( $currencies ) ){

			$output .= "<ul class='widget-coinmarketcap' id='coinmarketcap' >";
			foreach( $currencies as $coin ){

				// $response = wp_remote_get('https://api.coinmarketcap.com/v1/ticker/' . trim($coin) );
				
				// if( is_wp_error($response) || $response['response']['code'] !== 200 ){
				// 	continue;
				// }
				// $data =  json_decode($response['body']);
				// if( !is_array($data) ){
				// 	continue;
				// }

				$output .= sprintf(
					'<li class="widget-coinmarketcap__item "  data-url="%1$s" ><img class="widget-coinmarketcap__icon" alt="%2$s" src="%3$s" /> <span class="widget-coinmarketcap__name"> </span> <span class="widget-coinmarketcap__space"> </span>
					<span class="widget-coinmarketcap__price"></span>
					</li>',
					'https://api.coinmarketcap.com/v1/ticker/'. trim($coin) . '/',
					$coin,
					'https://files.coinmarketcap.com/static/img/coins/64x64/' . $coin . '.png'

				);
			}

			$output .= "</ul>";
		
		}

		echo $output;

		echo $args['after_widget'];

	}

	public function update($new_instance, $old_instance)
	{
	
		$instance = $old_instance;
		if (!empty($new_instance['title'])) {
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		}
		if (!empty($new_instance['currencies'])) {
			$instance['currencies'] = esc_sql($new_instance['currencies']);
		}
		return $instance;
	}

	public function form( $instance ) {

		$response = wp_remote_get('https://api.coinmarketcap.com/v1/ticker/');
		
		if( $response['response']['code'] !== 200 ){

			return $response['response']['code'];
		
		}
		$data =  json_decode($response['body']);
		

		if( !is_array($data) ){
			return -1;
		}

		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'bitcoin') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( isset($instance['title'])?$instance['title']:''); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('currencies'); ?>"><?php esc_html_e('Currencies:', 'bitcoin') ?></label>
				<select class="widefat" multiple="multiple" id="<?php echo $this->get_field_id('currencies'); ?>" name="<?php echo $this->get_field_name('currencies') . '[]'; ?>"  size="10">
					<?php foreach($data as $coin ){ ?>
						
						<option 
							<?php 
							if(array_key_exists('currencies', $instance )){
							echo in_array($coin->id ,$instance['currencies']) ? 'selected="selected"' : ''; }?> value="<?php echo $coin->id; ?>">
							<?php echo $coin->name; ?>
						</option>

					<?php } ?>
				</select>
			</p>
	<?php
	}


}

/**
 *  Display CF7 in footer sidebar
 */
class Bitcoin_Footer_Forms_Widget extends WP_Widget
{


	function __construct() {
		parent::__construct(
			'bitcoin_footer_form', // Base ID
			'&#x1F536; ' . esc_html__( 'Bitcoin', 'bitcoin' ) . ' &raquo; ' . esc_html__( 'CF7 Form', 'bitcoin' ), // Name
			array( 'description' => esc_html__( 'Add a Contact Form 7 to your footer.', 'bitcoin' ), ) // Args
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
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'bitcoin') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('contact_form'); ?>"><?php esc_html_e('Select Menu:', 'bitcoin'); ?></label>
            <select id="<?php echo $this->get_field_id('contact_form'); ?>" name="<?php echo $this->get_field_name('contact_form'); ?>">
                <option value="0"><?php esc_html_e('&mdash; Select &mdash;', 'bitcoin') ?></option>
				<?php foreach($cf as $item ){ 
						echo '<option value="' . $item['value'] . '"'
							. selected($contact_form,  $item['value'], false)
							. '>' . esc_html($item['name']) . '</option>';

				 } ?>
            </select>
        </p>
    <?php

		}
	}