<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Bitcoin
 */


// This function should come from Customify, but we need to do our best to make things happen
if ( ! function_exists( 'bitcoin_get_option') ) {
	/**
	 * Get option from the database
	 *
	 * @param string $option The option name.
	 * @param mixed $default Optional. The default value to return when the option was not found or saved.
	 * @param bool $force_default Optional. When true, we will use the $default value provided for when the option was not saved at least once.
	 *                          When false, we will let the option's default set value (in the Customify settings) kick in first, than our $default.
	 *                          It basically, reverses the order of fallback, first the option's default, then our own.
	 *                          This is ignored when $default is null.
	 *
	 * @return mixed
	 */
	function bitcoin_get_option( $option, $default = null, $force_default = true ) {
		/** @var PixCustomifyPlugin $pixcustomify_plugin */
		global $pixcustomify_plugin;

		if ( $pixcustomify_plugin !== null ) {
			// if there is a customify value get it here

			// First we see if we are not supposed to force over the option's default value
			if ( $default !== null && $force_default == false ) {
				// We will not pass the default here so Customify will fallback on the option's default value, if set
				$customify_value = $pixcustomify_plugin->get_option( $option );

				// We only fallback on the $default if none was given from Customify
				if ( $customify_value == null ) {
					return $default;
				}
			} else {
				$customify_value = $pixcustomify_plugin->get_option( $option, $default );
			}

			return $customify_value;
		}

		return $default;
	}
}

if ( ! function_exists( 'bitcoin_display_logo' ) ) {
	/**
	 * Function to display the logo added by the theme support 'custom-logo'.
	 * This was implemented in 4.5, to use the old logo install jetpack
	 */
	function bitcoin_display_logo() {
		// Display the inverted logo if all the requirements are met
		$logo_invert = wp_get_attachment_image_src( bitcoin_get_option('logo_invert') );
		$header_transparent = bitcoin_get_option( 'header_transparent' );
		$header_transparent_blog = bitcoin_get_option( 'header_transparent_blog' );
		
		if ((( $header_transparent && is_page_template( 'page-templates/front_page.php' ) ) || ( !is_front_page() && is_home()  &&  $header_transparent_blog )) && ! empty( $logo_invert[0] ) ) {
			
			$html = sprintf( '<div class="site-branding  site-branding--image"><a href="%1$s" class="custom-logo-link  custom-logo-link--light" rel="home" >%2$s</a></div>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image( bitcoin_get_option('logo_invert'), 'full', false, array(
					'class'    => 'custom-logo'
				) )
			);

			echo $html;
		}
		// or else display the regular logo
		elseif ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			// For transferring existing site logo from Jetpack -> Core
			if ( ! get_theme_mod( 'custom_logo' ) && $jp_logo = get_option( 'site_logo' ) ) {
				set_theme_mod( 'custom_logo', $jp_logo['id'] );
				delete_option( 'site_logo' );
			}

			echo '<div class="site-branding  site-branding--image">';
			the_custom_logo();
			echo '</div>';
		}
		// or else display the text logo.
		else { ?>
		
			<div class="site-branding">
				<h1 class="site-title  site-title--text"><a class="site-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			</div><!-- .site-branding -->
		<?php }
	}
}


if ( ! function_exists( 'bitcoin_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function bitcoin_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			'<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		);
		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
	}
endif;


if ( ! function_exists( 'bitcoin_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function bitcoin_posted_by() {

		$posted_by = sprintf(
			'<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ),
			esc_html( get_the_author() )
		);
		echo '<span class="posted-by">' . $posted_by . '</span>'; // WPCS: XSS OK.
	}
endif;

if ( ! function_exists('bitcoin_permabutton') ) :
	
	// Print the button with title and css classe
	function bitcoin_permabutton($opt) {
		$default = array(
			'classes' => 'btn btn--primary',
			'title' => ''
		);
		$settings = array_merge($default, $opt);
		$btn = '<div class="permalink" ><a class="'. $settings['classes'] . '" href="' . get_permalink() .'">';
		$btn .= $settings['title'];
		$btn .= '</a></div>';
		echo $btn;

	}
endif;

if( ! function_exists( 'bitcoin_comments_number' ) ):
	/** 
	 * Prints HTML with icon for comments
	*/

	function bitcoin_comments_number($opacity = 2) {
		$comment_html  = sprintf(
		'<span class="comments-count"><i class="bitcoin__icon bitcoin__icon--opacity%3$s ">%1$s</i>%2$s</span>',
			file_get_contents(locate_template('assets/svg/comment-icon.php')),
			get_comments_number_text( __('0','bitcoin'), __('1','bitcoin'), __('%','bitcoin')),
			$opacity
		);
		echo $comment_html;
	}

endif;

if (!function_exists('bitcoin_likes')) :
/** 
 * Prints HTML with icon for comments
 */

function bitcoin_likes(){

	$post_id = get_the_ID();

	$html = sprintf(
		'<span data-post-id="%3$s" class="likes-count %4$s"><i class="bitcoin__icon bitcoin__icon--opacity2 ">%1$s</i>
		<span class="likes-count__number">%2$s</span></span>',
		file_get_contents(locate_template('assets/svg/likes-icon.php')),
		bitcoin_get_likes_number(),
		$post_id ,
		isset($_COOKIE['bitcoin_post_' . $post_id . '_liked'])?'likes-count--active':''
	);

	echo $html;
}

endif;




if ( ! function_exists('bitcoin_tags' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function bitcoin_tags() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '<li>', esc_html__( ' ', 'bitcoin' ), '</li>' );
			if ( $tags_list ) {
				printf( '<ul class="tags-links"> %1$s </ul>', $tags_list );
			}
		}

	}
endif;


if ( ! function_exists('bitcoin_blog_style' ) ) :
	/**
	 *  Blog style
	 */
	function bitcoin_blog_style() {
		
		$style = bitcoin_get_option('blog_type_style');
		
		if( empty($style) ){
			echo 'tile';
		}else{
			echo $style;
		}

	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function bitcoin_categorized_blog() {


	
	$all_the_cool_cats = get_categories( array(
		'fields'     => 'ids',
		'hide_empty' => 1,

		// We only need to know if there is more than one category.
		'number'     => 2,
	) );


	if ( $all_the_cool_cats[0] > 1 ) {
		// This blog has more than 1 category so bitcoin_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so bitcoin_categorized_blog should return false.
		return false;
	}
}



/**
 * @param null $post_id
 * @param int $decimalsdisplays the rating score for the current post
 */
function display_average_listing_rating( $post_id = null, $decimals = 2 ) {

	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	global $pixreviews_plugin;

	if ( method_exists( $pixreviews_plugin, 'get_average_rating' ) ) {
		$rating = $pixreviews_plugin->get_average_rating( $post_id, $decimals );
	}

	if ( empty( $rating ) ) {
		return;
	} ?>
	<a href="#comments" class="single-rating review_rate display-only" data-pixrating="<?php echo $rating ?>" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
		<span class="rating-value">(<span itemprop="reviewCount"><?php echo get_comments_number() ?></span>)</span>
		<meta itemprop="ratingValue" content = "<?php echo $rating ?>">
	</a>
	<?php
}


if ( ! function_exists( 'bitcoin_shape_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Bitcoin
	 */
	function bitcoin_shape_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' : ?>
				<li class="post pingback">
				<p><?php esc_html_e( 'Pingback:', 'bitcoin' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'bitcoin' ), ' ' ); ?></p>
				<?php
				break;
			default : ?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<div class="comment-wrapper" id="div-comment-<?php comment_ID(); ?>">
					<?php if ( $comment->comment_approved == '0' ) : ?>
							<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'bitcoin' ); ?></p>
					<?php endif; ?>
					<div class="comment-avatar"><?php echo get_avatar( $comment, 75 ); ?></div>
					<header class="comment-header">
						<div class="comment-author ">
							<?php echo sprintf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
						</div><!-- .comment-author -->
					</header>
					
					<div class="comment-content">
						<?php comment_text(); ?>
					</div>
					<div class="comment-meta">
						<a class="text" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time pubdate datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( esc_html__( 'on %1$s', 'bitcoin' ), get_comment_date() ); ?>
							</time>
						</a><?php edit_comment_link( esc_html__( '(Edit)', 'bitcoin' ), ' ' ); ?>
						<span class="comment-reply">
							<?php comment_reply_link( array_merge( $args, array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
							) ) ); ?>
						</span><!-- .reply -->
					</div>
		
				</div>
		
				<?php
				break;
		endswitch;
	}
endif; // ends check for bitcoin_shape_comment()


/**
 * Due to the fact that we need a wrapper for center aligned images and for the ones with alignnone, we need to wrap the images without a caption
 * The images with captions already are wrapped by the figure tag
 *
 * @param string $content
 *
 * @return string
 */
function bitcoin_wrap_images_in_figure( $content ) {
	$classes = array( 'aligncenter', 'alignnone' );

	foreach ( $classes as $class ) {

		//this regex basically tells this
		//match all the images that are not in captions and that have the X class
		//when an image is wrapped by an anchor tag, match that too
		$regex = '~\[caption[^\]]*\].*\[\/caption\]|((?:<a[^>]*>\s*)?<img.*class="[^"]*' . $class . '[^"]*[^>]*>(?:\s*<\/a>)?)~i';

		// php 5.2 valid
		$callback = new BitcoinWrapImagesInFigureCallback( $class );
		$content = preg_replace_callback(
				$regex,
				// in the callback function, if Group 1 is empty,
				// set the replacement to the whole match,
				// i.e. don't replace
				array( $callback, 'callback' ),
				$content );
	}


	return $content;
}
add_filter( 'the_content', 'bitcoin_wrap_images_in_figure' );



//We need to use a class so we can pass the $class variable to the callback function
class BitcoinWrapImagesInFigureCallback {
	private $class;
	function __construct( $class ) {
		$this->class = $class;
	}

	public function callback( $match ) {

		if ( empty( $match[1] ) ) {
			return $match[0];
		}
		return '<span class="' . $this->class . '">' . $match[1] . '</span>';
	}
}	
