<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Listable
 */

if ( ! function_exists( 'listable_get_option' ) ) {
	/**
	 * Get option from the database
	 *
	 * @param string
	 * @deprecated 1.8.1
	 *
	 * @return mixed
	 */
	function listable_get_option( $option, $default = null ) {
		_deprecated_function('listable_get_option', '1.8.4', 'pixelgrade_option');
		return pixelgrade_option($option, $default);
	} //end function
} // end if listable_get_option exists

// This function should come from Customify, but we need to do our best to make things happen
if ( ! function_exists( 'pixelgrade_option') ) {
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
	function pixelgrade_option( $option, $default = null, $force_default = true ) {
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

if ( ! function_exists( 'listable_display_logo' ) ) {
	/**
	 * Function to display the logo added by the theme support 'custom-logo'.
	 * This was implemented in 4.5, to use the old logo install jetpack
	 */
	function listable_display_logo() {
		// Display the inverted logo if all the requirements are met
		$logo_invert = wp_get_attachment_image_src( pixelgrade_option('logo_invert') );
		$header_transparent = pixelgrade_option( 'header_transparent' );
		$header_transparent_blog = pixelgrade_option( 'header_transparent_blog' );
		
		if ( ( $header_transparent && is_page_template( 'page-templates/front_page.php' ) ) || ( !is_front_page() && is_home()  &&  $header_transparent_blog ) && ! empty( $logo_invert[0] ) ) {
			$html = sprintf( '<div class="site-branding  site-branding--image"><a href="%1$s" class="custom-logo-link  custom-logo-link--light" rel="home" itemprop="url">%2$s</a></div>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image( pixelgrade_option('logo_invert'), 'full', false, array(
					'class'    => 'custom-logo',
					'itemprop' => 'logo',
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
		$btn = '<div><a class="'. $settings['classes'] . '" href="' . get_permalink() .'">';
		$btn .= $settings['title'];
		$btn .= '</a></div>';
		echo $btn;

	}
endif;

if( ! function_exists( 'bitcoin_comments_number' ) ):
	/** 
	 * Prints HTML with icon for comments
	*/

	function bitcoin_comments_number() {
		$comment_html  = sprintf(
			'<span class="comments-count"><i class="bitcoin-icon bitcoin-icon--comment"></i>%1$s</span>',
			get_comments_number_text( __('0','bitcoin'), __('1','bitcoin'), __('%','bitcoin'))
		);
		echo $comment_html;
	}

endif;

if ( ! function_exists( 'listable_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function listable_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'listable' ) );
			if ( $categories_list && listable_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'listable' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'listable' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'listable' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'listable' ), esc_html__( '1 Comment', 'listable' ), esc_html__( '% Comments', 'listable' ) );
			echo '</span>';
		}
//
//	edit_post_link(
//		sprintf(
//			/* translators: %s: Name of current post */
//			esc_html__( 'Edit %s', 'listable' ),
//			the_title( '<span class="screen-reader-text">"', '"</span>', false )
//		),
//		'<span class="edit-link">',
//		'</span>'
//	);
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function bitcoin_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'bitcoin_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'bitcoin_categories', $all_the_cool_cats );
	}


	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so bitcoin_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so bitcoin_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in bitcoin_categorized_blog.
 */
function bitcoin_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'bitcoin_categories' );
}

add_action( 'edit_category', 'bitcoin_category_transient_flusher' );
add_action( 'save_post', 'bitcoin_category_transient_flusher' );

if ( ! function_exists( 'listable_display_term_icon' ) ) {
	function listable_display_term_icon( $term_id = null, $size = 'thumbnail' ) {
		$img_src = listable_get_term_icon_url( $term_id, $size );

		if ( ! empty( $img_src ) ) { ?>
			<div class="icon_wrapper">
				<img src="<?php echo $img_src; ?>">
			</div>
		<?php }
	}
}

if ( ! function_exists( 'listable_listing_slug_input' ) ) {
	function listable_listing_slug_input() {
		$permalinks = get_option( 'listable_permalinks_settings' ); ?>
		<input name="listable_listing_base_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['listing_base'] ) ) {
			echo esc_attr( $permalinks['listing_base'] );
		} ?>" placeholder="<?php echo esc_attr_x( 'listings', 'slug', 'listable' ) ?>"/>
		<?php
	}
}

if ( ! function_exists( 'listable_listing_category_slug_input' ) ) {
	function listable_listing_category_slug_input() {
		$permalinks = get_option( 'listable_permalinks_settings' ); ?>
		<input name="listable_listing_category_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['category_base'] ) ) {
			echo esc_attr( $permalinks['category_base'] );
		} ?>" placeholder="<?php echo esc_attr_x( 'listing-category', 'slug', 'listable' ) ?>"/>
		<?php
	}
}

if ( ! function_exists( 'listable_listing_tag_slug_input' ) ) {
	function listable_listing_tag_slug_input() {
		$permalinks = get_option( 'listable_permalinks_settings' ); ?>
		<input name="listable_listing_tag_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['tag_base'] ) ) {
			echo esc_attr( $permalinks['tag_base'] );
		} ?>" placeholder="<?php echo esc_attr_x( 'listing-tag', 'slug', 'listable' ) ?>"/>
		<?php
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

/**
 * Returns the rating score for the current post
 *
 * @param null $post_id
 * @param int $decimals
 *
 * @return bool
 */
function get_average_listing_rating( $post_id = null, $decimals = 2 ) {

	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	global $pixreviews_plugin;
	if ( method_exists( $pixreviews_plugin, 'get_average_rating' ) ) {
		return $pixreviews_plugin->get_average_rating( $post_id, $decimals );
	}

	return false;
}

if ( ! function_exists( 'listable_shape_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Listable
	 */
	function listable_shape_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' : ?>
				<li class="post pingback">
				<p><?php esc_html_e( 'Pingback:', 'listable' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'listable' ), ' ' ); ?></p>
				<?php
				break;
			default :
				if ( 'job_listing' == get_post_type() ) : ?>
					<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>" itemprop="review" itemscope itemtype="http://schema.org/Review">
					<div class="comment-wrapper" id="div-comment-<?php comment_ID(); ?>">
						<header class="comment-header">
							<div class="comment-author vcard" itemprop="author" itemscope itemtype="http://schema.org/Person">
								<?php
								echo get_avatar( $comment, 75 );
								echo sprintf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
							</div><!-- .comment-author .vcard -->
							<?php if ( $comment->comment_approved == '0' ) : ?>
								<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'listable' ); ?></em>
								<br/>
							<?php endif; ?>
						</header>
						<div class="comment-content" itemprop="reviewBody">
							<?php comment_text(); ?>
						</div>
						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
							) ) ); ?>
						</div><!-- .reply -->
					</div>
				<?php else : ?>
					<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<div class="comment-wrapper" id="div-comment-<?php comment_ID(); ?>">
						<div class="comment-avatar"><?php echo get_avatar( $comment, 75 ); ?></div>
						<header class="comment-header">
							<div class="comment-author vcard">
								<?php echo sprintf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
							</div><!-- .comment-author .vcard -->
							<?php if ( $comment->comment_approved == '0' ) : ?>
								<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'listable' ); ?></em>
								<br/>
							<?php endif; ?>
							<div class="comment-meta commentmetadata">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
									<time pubdate datetime="<?php comment_time( 'c' ); ?>">
										<?php printf( esc_html__( 'on %1$s', 'listable' ), get_comment_date() ); ?>
									</time>
								</a><?php edit_comment_link( esc_html__( '(Edit)', 'listable' ), ' ' ); ?>
							</div>
						</header>
						<div class="comment-content">
							<?php comment_text(); ?>
						</div>
						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
							) ) ); ?>
						</div><!-- .reply -->
					</div>
				<?php endif;
				break;
		endswitch;
	}
endif; // ends check for listable_shape_comment()

if ( ! function_exists( 'listable_move_comment_date' ) ) {
	function listable_move_comment_date( $comment_content ) {
		global $comment;

		$commentDateTime = new DateTime( $comment->comment_date );
		$commentIsoDate = $commentDateTime->format(DateTime::ISO8601);

		ob_start(); ?>
	<div class="comment-meta commentmetadata" itemprop="datePublished" content = "<?php echo $commentIsoDate; ?>">
	<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>"><?php
			/* translators: 1: date, 2: time */
			printf( esc_html__( 'on %1$s', 'listable' ), get_comment_date() ); ?></time>
		</a><?php edit_comment_link( esc_html__( '(Edit)', 'listable' ), ' ' ); ?></div><?php

		return ob_get_clean() . $comment_content;
	}
}

/**
 * Due to the fact that we need a wrapper for center aligned images and for the ones with alignnone, we need to wrap the images without a caption
 * The images with captions already are wrapped by the figure tag
 *
 * @param string $content
 *
 * @return string
 */
function listable_wrap_images_in_figure( $content ) {
	$classes = array( 'aligncenter', 'alignnone' );

	foreach ( $classes as $class ) {

		//this regex basically tells this
		//match all the images that are not in captions and that have the X class
		//when an image is wrapped by an anchor tag, match that too
		$regex = '~\[caption[^\]]*\].*\[\/caption\]|((?:<a[^>]*>\s*)?<img.*class="[^"]*' . $class . '[^"]*[^>]*>(?:\s*<\/a>)?)~i';

		// php 5.2 valid
		$callback = new ListableWrapImagesInFigureCallback( $class );
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
add_filter( 'the_content', 'listable_wrap_images_in_figure' );

//We need to use a class so we can pass the $class variable to the callback function
class ListableWrapImagesInFigureCallback {
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




function listable_single_post_style () {
	echo apply_filters( 'listable_single_post_image', '');
}