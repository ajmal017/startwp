<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Listable
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */


function listable_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	global $post;

	$nav_locations = get_nav_menu_locations();
	if ( ! empty( $nav_locations['secondary'] ) ) {
		$wp_get_nav_menu_items = wp_get_nav_menu_items( $nav_locations['secondary'] );

		if ( has_nav_menu( 'secondary' ) && ! empty( $wp_get_nav_menu_items ) ) {
			$classes[] = 'has--secondary-menu';
		}
	}

	if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'shortcode' ) ) {
		$classes[] = 'shortcode';
	}

	$show_on_posts_page = true;
	if (is_home()) {
		$show_on_posts_page = false;
		$show_on_posts_page = pixelgrade_option('blog_sidebar_posts');
	}

	$sidebar = pixelgrade_option('blog_sidebar');
	if( isset( $sidebar ) && 'sidebar__none' != $sidebar && $show_on_posts_page ){
		$classes[] = $sidebar;
	}

	return $classes;
}
add_filter( 'body_class', 'listable_body_classes' );

/**
 * Add a custom body class when the request comes from a modal
 * @param $classes
 *
 * @return array
 */
function listable_login_body_class( $classes ) {
	if ( isset( $_REQUEST['modal_login'] ) && $_REQUEST['modal_login'] ) {
		$classes[] = 'page-login-modal';
	}

	return $classes;
}
add_filter( 'login_body_class', 'listable_login_body_class' );

function listable_force_display_the_excerpt_box( $hidden ) {
	//this filter is fired from get_hidden_meta_boxes()
	//make sure that 'postexcerpt' is not in the default hidden boxes
	$hidden = array_diff( $hidden, array( 'postexcerpt' ) );

	return $hidden;
}
add_filter( 'default_hidden_meta_boxes', 'listable_force_display_the_excerpt_box' );

if ( ! function_exists('listable_post_excerpt_meta_box' ) ) {
	/**
	 * Custom callback function for the page excerpt meta box - it changes the strings in the form
	 *
	 * @param $post
	 */
	function listable_post_excerpt_meta_box( $post ) { ?>
		<label class="screen-reader-text" for="excerpt"><?php esc_html_e( 'Page Subtitle', 'listable' ) ?></label>
		<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
		<p><?php esc_html_e( 'This is the subtitle that will be shown in the page\'s Hero Area, below the page title.', 'listable' ); ?></p>
		<?php
	}
}

function listable_change_page_excerpt_box_title() {
	global $wp_meta_boxes; // array of defined meta boxes

	//Change the page excerpt meta box title
	$wp_meta_boxes['page']['normal']['core']['postexcerpt']['title'] = esc_html__( 'Page Subtitle', 'listable' );
	//and it's callback
	$wp_meta_boxes['page']['normal']['core']['postexcerpt']['callback'] = 'listable_post_excerpt_meta_box';
}
add_action( 'add_meta_boxes', 'listable_change_page_excerpt_box_title' );


if ( ! function_exists( 'listable_display_image' ) ) {
	/**
	 * Display an image from the given url
	 * We use this function when the url may contain a svg file
	 *
	 * @param $url
	 * @param string $class A CSS class
	 * @param bool|true $wrap_as_img If the function should wrap the url in an image tag or not
	 */
	function listable_display_image( $url, $class = '', $wrap_as_img = true, $attachment_id = null ) {
		if ( ! empty( $url ) && is_string( $url ) ) {

			//we try to inline svgs
			if ( substr( $url, - 4 ) === '.svg' ) {

				//first let's see if we have an attachment and inline it in the safest way - with readfile
				//include is a little dangerous because if one has short_open_tags active, the svg header that starts with <? will be seen as PHP code
				if ( ! empty( $attachment_id ) && false !== @readfile( get_attached_file( $attachment_id ) ) ) {
					//all good
				} elseif ( false !== ( $svg_code = get_transient( md5( $url ) ) ) ) {
					//now try to get the svg code from cache
					echo $svg_code;
				} else {

					//if not let's get the file contents using WP_Filesystem
					require_once( ABSPATH . 'wp-admin/includes/file.php' );

					WP_Filesystem();

					global $wp_filesystem;

					$svg_code = $wp_filesystem->get_contents( $url );

					if ( ! empty( $svg_code ) ) {
						set_transient( md5( $url ), $svg_code, 12 * HOUR_IN_SECONDS );

						echo $svg_code;
					}
				}

			} elseif ( $wrap_as_img ) {

				if ( ! empty( $class ) ) {
					$class = ' class="' . $class . '"';
				}

				echo '<img src="' . $url . '"' . $class . '/>';

			} else {
				echo $url;
			}
		}
	}
}

if ( ! function_exists( 'listable_get_listing_gallery_ids' ) ) {
	/**
	 * Return the gallery of images attached to the listing
	 *
	 * @param null $listing_ID
	 *
	 * @return array|bool
	 */
	function listable_get_listing_gallery_ids( $listing_ID = null ) {

		if ( empty( $listing_ID ) ) {
			$listing_ID = get_the_ID();
		}

		//bail if we have no valid listing ID
		if ( empty( $listing_ID ) ) {
			return false;
		}

		$gallery_string = trim( get_post_meta( $listing_ID, 'main_image', true ) );
		//no spaces are allowed
		$gallery_string = str_replace( ' ', '', $gallery_string );
		//a little bit of sanity cleanup because sometimes (mainly during preview) an empty entry can be added at the end
		if ( ',' === substr( $gallery_string, - 1, 1 ) ) {
			$gallery_string = substr( $gallery_string, 0, - 1 );
		}

		if ( ! empty( $gallery_string ) ) {
			$gallery_ids = explode( ',', $gallery_string );

			//now ensure that each entry is a valid ID (positive int)
			$filter_options = array(
				'options' => array( 'min_range' => 1 )
			);
			foreach ( $gallery_ids as $key => $value ) {
				if ( false === filter_var( $value, FILTER_VALIDATE_INT, $filter_options ) ) {
					unset( $gallery_ids[ $key ] );
				}
			}

			//normalize the array, just in case we've deleted something
			$gallery_ids = array_values( $gallery_ids );
		}

		if ( ! empty( $gallery_ids ) ) {
			return $gallery_ids;
		}

		return false;
	}
}

if ( ! function_exists( 'listable_get_post_image_id' ) ) {
	/**
	 * Return the ID of the first image found in the post meta (featured image). In case of listings first we will look into the gallery (main_image) and then for the featured image
	 *
	 * @param null $post_ID
	 *
	 * @return array|bool|string
	 */
	function listable_get_post_image_id( $post_ID = null ) {

		if ( empty( $post_ID ) ) {
			$post_ID = get_the_ID();
		}

		//get the presentation gallery if present
		$gallery_ids = listable_get_listing_gallery_ids( $post_ID );

		//now lets get the image (either from the presentation gallery or the featured image
		// if there are second images, use them
		if ( ! empty( $gallery_ids ) ) {
			return $gallery_ids[0];
		} else {
			// fallback to featured image
			return esc_sql( get_post_thumbnail_id( $post_ID ) );
		}

		return false;
	}
}

if ( ! function_exists( 'listable_get_post_image_src' ) ) {
	/**
	 * Return the src of the post image. In the case of listings we will try and get the first image of the gallery first, then the featured image.
	 *
	 * @param null $post_id
	 * @param string $size
	 *
	 * @return bool
	 */
	function listable_get_post_image_src( $post_id = null, $size = 'thumbnail' ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$attach_id = listable_get_post_image_id( $post_id );

		if ( empty( $attach_id ) || is_wp_error( $attach_id ) ) {
			return false;
		}

		$data = wp_get_attachment_image_src( $attach_id, $size );
		// if this attachment has an url for this size, return it
		if ( isset( $data[0] ) && ! empty ( $data ) ) {
			return listable_get_inline_background_image( $data[0] );
		}

		return false;
	}
}

if ( ! function_exists( 'listable_get_attachment_id_from_url' ) ) {
	/**
	 * Given an URL we will try to find and return the ID of the attachment, if present
	 *
	 * @param string $attachment_url
	 *
	 * @return bool|null|string
	 */
	function listable_get_attachment_id_from_url( $attachment_url = '' ) {

		global $wpdb;
		$attachment_id = false;

		// If there is no url, bail.
		if ( '' == $attachment_url ) {
			return false;
		}

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

		}

		return $attachment_id;
	}
}

function listable_is_edit_page( $new_edit = null ) {
	global $pagenow;
	//make sure we are on the backend
	if ( ! is_admin() ) {
		return false;
	}

	if ( $new_edit == "edit" ) {
		return in_array( $pagenow, array( 'post.php', ) );
	} elseif ( $new_edit == "new" ) { //check for new post page
		return in_array( $pagenow, array( 'post-new.php' ) );
	} else { //check for either new or edit
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}
}


function listable_sort_array_by_priority( $a, $b ) {
	if ( $a['priority'] == $b['priority'] ) {
		return 0;
	}

	return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
}

/**
 * Fill the comment form with placeholders
 * @param $args
 *
 * @return mixed
 */
function listable_add_comments_placeholders( $args ) {
	$args['fields']['author'] = str_replace( 'name="author"', 'placeholder="' . esc_attr__( 'Your name', 'listable' ) . '" name="author"', $args['fields']['author'] );
	$args['fields']['email']  = str_replace( 'name="email"', 'placeholder="' . esc_attr__( 'your@email.com', 'listable' ) . '" name="email"', $args['fields']['email'] );

	return $args;
}
add_action( 'comment_form_defaults', 'listable_add_comments_placeholders' );

function listable_search_template_chooser( $template ) {
	global $wp_query;
	$post_type = get_query_var( 'post_type' );
	if ( $wp_query->is_search && $post_type == 'job_listing' ) {
		return locate_template( 'search-job_listing.php' );  //  redirect to archive-search.php
	}

	return $template;
}
add_filter( 'template_include', 'listable_search_template_chooser' );

if ( ! function_exists( 'listable_get_random_hero_object' ) ) {
	/**
	 * A post / page can hold images or videos, but sometimes we want to use only a random one as featured hero
	 * This is what this functions returns
	 * @param null $post_id
	 *
	 * @return array|null|WP_Post
	 */
	function listable_get_random_hero_object( $post_id = null ) {
		if ( $post_id === null ) {
			global $post;
			$post_id = $post->ID;
		}

		$image_backgrounds  = get_post_meta( $post_id, 'blog_image_backgrounds', true );

		if ( ! empty( $image_backgrounds ) ) {
			$image_backgrounds = explode( ',', $image_backgrounds );
		} else {
			$image_backgrounds = array();
		}

		if ( ! empty( $image_backgrounds ) ) {
			$random = array_rand( $image_backgrounds, 1 );

			return get_post( $image_backgrounds[ $random ] );
		}
	}
}

/**
 * Check if there is a photon version of the required image
 * @param $url
 *
 * @return mixed|void
 */
function listable_get_inline_background_image( $url ) {
	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) && function_exists( 'jetpack_photon_url' ) ) {
		return apply_filters( 'jetpack_photon_url', $url );
	}
	return $url;
}

/**
 * Add descriptions to menu items
 */
function listable_nav_description( $item_output, $item, $depth, $args ) {

	if ( 'search_suggestions' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;

}
add_filter( 'walker_nav_menu_start_el', 'listable_nav_description', 10, 4 );



function listable_string_to_bool( $value ) {
	return ( is_bool( $value ) && $value ) || in_array( $value, array( '1', 'true', 'yes' ) ) ? true : false;
}

function listable_get_shortcode_param_value( $content, $shortcode, $param, $default ) {
	$param_value = $default;
	if ( has_shortcode( $content, $shortcode ) ) {
		$pattern = get_shortcode_regex( array( $shortcode ) );

		if ( preg_match_all( '/'. $pattern .'/s', $content, $matches ) ) {
			$keys = array();
			$result = array();
			foreach( $matches[0] as $key => $value) {
				// $matches[3] return the shortcode attribute as string
				// replace space with '&' for parse_str() function
				$get = str_replace(" ", "&" , $matches[3][$key] );
				parse_str($get, $output);

				//get all shortcode attribute keys
				$keys = array_unique( array_merge(  $keys, array_keys($output)) );
				$result[] = $output;

			}

			if ( ! empty( $result ) ) {
				$value = listable_preg_match_array_get_value_by_key( $result, $param );

				if ( null !== $value ) {
					//just in case someone has magic_quotes activated
					$param_value = stripslashes_deep( $value );
				}
			}
		}
	}

	return $param_value;
}

function listable_preg_match_array_get_value_by_key( $arrs, $searched ) {
	foreach ( $arrs as $arr ) {
		foreach ( $arr as $key => $value ) {
			if (  $key == $searched ) {
				return $value;
			}
		}
	}

	return null;
}

function bitcoin_map_get_posts($item){
	return array(
		'name' => $item->post_title,
		'value' => $item->ID,
	);
}

function bitcoin_get_posts_array($options)
{
	if (!isset($options) || !is_array($options))
		$options = array();

	
	if (isset($options['post_type']) && !post_type_exists($options['post_type']))
		return array();

	$posts_list = get_posts($options);

	if (!empty($posts_list)) {
		$posts_list = array_map('bitcoin_map_get_posts', $posts_list);
	} else {
		$post_type = get_post_type_object($options['post_type']);
		$posts_list = array(sprintf(esc_html__("No %s found", 'forit'), $post_type->labels->name) => 0);
	}

	return $posts_list;
}

/**
 * Retrive the relative post
 * @return WP_Post
 */
function bitcoin_get_related_posts(){
	$tags = get_the_tags();
	$cats = get_the_category();

	$tags = !empty($tags) ? array_map(function ($tag) {
		return $tag->term_id;
	}, $tags) : array();
	$cats = !empty($cats) ? array_map(function ($cat) {
		return $cat->term_id;
	}, $cats) : array();

	$posts = new WP_Query(
		array(
			'post_type' => 'post',
			'posts_per_page' => 4,
			/** TODO: Theme option**/
			'post__not_in' => array(get_the_ID()),
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'post_tag',
					'field' => 'id',
					'terms' => $tags
				),
				array(
					'taxonomy' => 'category',
					'field' => 'term_id',
					'terms' => $cats
				)
			)
		)
	);

	return $posts;
}

/**
 * Modify the output for our custom User Menu items
 */
class Listable_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 * @since 4.4.0 'nav_menu_item_args' filter was added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = (object) apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts['class']  =  ' ';

		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				//Custom URL for the Current Username menu item since right now it should be #listablecurrentusername

				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = '';

		if ( isset( $args->before ) ) {
			$item_output = $args->before;
		}

		$item_output .= '<a'. $attributes .'>';

		//If this is a Current Username item
		if( isset( $item->url ) && 'custom' == $item->type  && '#listablecurrentusername' == $item->url ) {
			//Get the current user display name
			global $current_user;

			wp_get_current_user();

			$avatar_args = array(
				// get_avatar_data() args.
				'class'         => 'user-avatar',
			);
			$avatar = get_avatar( $current_user->ID , 32, '','', $avatar_args );
			$item_output .= $avatar;

			if ( ! empty( $current_user->display_name ) ) {
				$item_output .= '<span class="user-display-name">' . $current_user->display_name . '</span>';
			} else {
				$item_output .= '<span class="user-display-name">' . $item->title . '</span>';
			}

		} else {
			//do the regular WP thing
			$item_output .= $args->link_before . $title . $args->link_after;
		}

		$item_output .= '</a>';

		if ( isset( $args->after ) ) {
			$item_output .= $args->after;
		}

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

} //Listable_Walker_Nav_Menu

function archive_wpml_language_switcher() {
	// a functions to remove the footer	language switcher added by WPML plugin
	global $post;
	global $icl_language_switcher;

	if ( ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'jobs' ) )
		|| is_search()
		|| is_tax( array( 'job_listing_category', 'job_listing_tag', 'job_listing_region' ) )
	) {
		remove_action( 'wp_footer', array( $icl_language_switcher , 'language_selector_footer' ), 19 );
	}
}

add_action('wp_footer', 'archive_wpml_language_switcher', 11);