<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Bitstarter
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */


function bitstarter_body_classes( $classes ) {
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
		$show_on_posts_page = bitstarter_get_option('blog_sidebar_posts', 'sidebar__right');
	}

	$sidebar = bitstarter_get_option('blog_sidebar', 'sidebar__none');
	if( isset( $sidebar ) && $sidebar != 'sidebar__none' && $show_on_posts_page ){
		$classes[] = $sidebar;
	}

	if((is_page_template( 'page-templates/front_page.php' ) && bitstarter_get_option( 'header_transparent', true)) || ( is_home() && bitstarter_get_option( 'header_transparent', false)) ) {
		$classes[] = 'header--transparent'; 
	}

	return $classes;
	
}
add_filter( 'body_class', 'bitstarter_body_classes' );



if ( ! function_exists( 'bitstarter_display_image' ) ) {
	/**
	 * Display an image from the given url
	 * We use this function when the url may contain a svg file
	 *
	 * @param $url
	 * @param string $class A CSS class
	 * @param bool|true $wrap_as_img If the function should wrap the url in an image tag or not
	 */
	function bitstarter_display_image( $url, $class = '', $wrap_as_img = true, $attachment_id = null ) {
		if ( ! empty( $url ) && is_string( $url ) ) {

			//we try to inline svgs
			if ( substr( $url, - 4 ) === '.svg' ) {
				global $wp_filesystem;
				//if not let's get the file contents using WP_Filesystem
				WP_Filesystem();
				require_once( ABSPATH . 'wp-admin/includes/file.php' );


				//first let's see if we have an attachment and inline it in the safest way - with WP_Filesystem
				//include is a little dangerous because if one has short_open_tags active, the svg header that starts with <? will be seen as PHP code
				if ( ! empty( $attachment_id ) && false !== $wp_filesystem->get_contents( get_attached_file( $attachment_id ) ) ) {
					//all good
				} elseif ( false !== ( $svg_code = get_transient( md5( $url ) ) ) ) {
					//now try to get the svg code from cache
					echo wp_kses($svg_code, bitstarter_allowed_html());
				} else {

					$svg_code = $wp_filesystem->get_contents( $url );

					if ( ! empty( $svg_code ) ) {
						set_transient( md5( $url ), $svg_code, 12 * HOUR_IN_SECONDS );

						echo wp_kses($svg_code, bitstarter_allowed_html());
					}
				}

			} elseif ( $wrap_as_img ) {

				if ( ! empty( $class ) ) {
					$class = ' class="' . $class . '"';
				}

				echo '<img src="' . $url . '"' . $class . '/>';

			} else {
				echo esc_url( $url );
			}
		}
	}
}

if ( ! function_exists( 'bitstarter_get_listing_gallery_ids' ) ) {
	/**
	 * Return the gallery of images attached to the listing
	 *
	 * @param null $id
	 *
	 * @return array|bool
	 */
	function bitstarter_get_listing_gallery_ids( $id = null ) {

		if ( empty( $id ) ) {
			$id = get_the_ID();
		}

		//bail if we have no valid listing ID
		if ( empty( $id ) ) {
			return false;
		}

		$gallery_string = trim( get_post_meta( $id, 'main_image', true ) );
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


function bitstarter_is_edit_page( $new_edit = null ) {
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


function bitstarter_sort_array_by_priority( $a, $b ) {
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
function bitstarter_add_comments_placeholders( $args ) {
	$args['fields']['author'] = str_replace( 'name="author"', 'placeholder="' . esc_attr__( 'Enter your name', 'bitstarter' ) . '" name="author"', $args['fields']['author'] );
	$args['fields']['email']  = str_replace( 'name="email"', 'placeholder="' . esc_attr__( 'Enter your mail', 'bitstarter' ) . '" name="email"', $args['fields']['email'] );
	$args['fields']['url']  = str_replace( 'name="url"', 'placeholder="' . esc_attr__( 'Enter your website', 'bitstarter' ) . '" name="url"', $args['fields']['url'] );
	return $args;
}
add_action( 'comment_form_defaults', 'bitstarter_add_comments_placeholders' );


if ( ! function_exists( 'bitstarter_get_random_hero_object' ) ) {
	/**
	 * A post / page can hold images or videos, but sometimes we want to use only a random one as featured hero
	 * This is what this functions returns
	 * @param null $post_id
	 *
	 * @return array|null|WP_Post
	 */
	function bitstarter_get_random_hero_object( $post_id = null ) {
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
function bitstarter_get_inline_background_image( $url ) {
	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) && function_exists( 'jetpack_photon_url' ) ) {
		return apply_filters( 'jetpack_photon_url', $url );
	}
	return $url;
}

/**
 * Add descriptions to menu items
 */
function bitstarter_nav_description( $item_output, $item, $depth, $args ) {

	if ( 'search_suggestions' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;

}
add_filter( 'walker_nav_menu_start_el', 'bitstarter_nav_description', 10, 4 );



function bitstarter_string_to_bool( $value ) {
	return ( is_bool( $value ) && $value ) || in_array( $value, array( '1', 'true', 'yes' ) ) ? true : false;
}

function bitstarter_get_shortcode_param_value( $content, $shortcode, $param, $default ) {
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
				$value = bitstarter_preg_match_array_get_value_by_key( $result, $param );

				if ( null !== $value ) {
					//just in case someone has magic_quotes activated
					$param_value = stripslashes_deep( $value );
				}
			}
		}
	}

	return $param_value;
}

function bitstarter_preg_match_array_get_value_by_key( $arrs, $searched ) {
	foreach ( $arrs as $arr ) {
		foreach ( $arr as $key => $value ) {
			if (  $key == $searched ) {
				return $value;
			}
		}
	}

	return null;
}

function bitstarter_map_get_posts($item){
	return array(
		'name' => $item->post_title,
		'value' => $item->ID,
	);
}

function bitstarter_get_posts_array($options)
{
	if (!isset($options) || !is_array($options))
		$options = array();

	
	if (isset($options['post_type']) && !post_type_exists($options['post_type']))
		return array();

	$posts_list = get_posts($options);

	if (!empty($posts_list)) {
		$posts_list = array_map('bitstarter_map_get_posts', $posts_list);
	} else {
		$post_type = get_post_type_object($options['post_type']);
		$posts_list = array(sprintf(esc_html__("No %s found", 'bitstarter'), $post_type->labels->name) => 0);
	}

	return $posts_list;
}

/**
 * Retrive the relative post
 * @return WP_Post
 */
function bitstarter_get_related_posts(){
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
			'posts_per_page' => 3,
			/** TODO: Customazer**/
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



function bitstarter_get_likes_number() {

	$likes = get_post_meta(get_the_ID(), 'post_likes', true);

	return $likes;
}

/**
 * Modify the output for our custom User Menu items
 */
class Bitstarter_Walker_Nav_Menu extends Walker_Nav_Menu {

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
				//Custom URL for the Current Username menu item since right now it should be #bitstartercurrentusername

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
		if( isset( $item->url ) && 'custom' == $item->type  && '#bitstartercurrentusername' == $item->url ) {
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

} //Bitstarter_Walker_Nav_Menu



add_filter('comment_form_fields', 'bitstarter_reorder_comment_fields' );
function bitstarter_reorder_comment_fields( $fields ){

	$new_fields = array(); 
	// Set new order 
	$myorder = array('author','email','url','comment'); 

	// Reorder
	foreach( $myorder as $key ){
		$new_fields[ $key ] = $fields[ $key ];
		unset( $fields[ $key ] );
	}

	// If there are remained some fields add them to end 
	if( $fields )
		foreach( $fields as $key => $val )
			$new_fields[ $key ] = $val;

	return $new_fields;
}


/**
 * Generate unique random slug
 * 
 * @return string unique id   
*/
function bitstarter_get_unique_id() {
    return mt_rand(1000,9000) . '_' . mt_rand(1000,9999);
}