<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bitstarter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="manifest" href="<?php echo pwa_theme_get_manifest_path(); ?>">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="<?php bloginfo( 'name' ); ?>">
<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?>">
<meta name="theme-color" content="#FFF8F7">
<meta name="msapplication-navbutton-color" content="#FFF8F7">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="msapplication-starturl" content="/">
<link rel="icon" type="image/jpeg" sizes="512x512" href="<?php echo get_site_icon_url(); ?>">
<link rel="apple-touch-icon" type="image/jpeg" sizes="512x512" href="<?php echo get_site_icon_url(); ?>">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'bitstarter' ); ?></a>

	<?php	$preloader = bitstarter_get_option('preloader');
	if( true == $preloader  ){ ?>
	
		<div class="site-preloader">
			<div class="coin-circle">
				<div></div>
			</div>
		</div>

	<?php } ?>



	<header id="masthead" class="site-header  
		<?php if( is_page_template( 'page-templates/front_page.php' ) && (bitstarter_get_option( 'header_transparent', true ) == true) ) echo 'header--transparent'; ?>
		<?php if(  !is_front_page() && is_home() && (bitstarter_get_option( 'header_transparent_blog', true ) == true) ) echo 'header--transparent'; ?>
		" role="banner">
		<div class="site-header__in">
		
			<?php bitstarter_display_logo(); ?>

			<?php
			// Output the navigation and mobile nav button only if there is a nav
			if ( has_nav_menu( 'primary' ) || has_nav_menu( 'secondary') ): ?>
			<button class="menu-trigger  menu--open  js-menu-trigger">
			<?php get_template_part( 'assets/svg/menu-bars-svg' ); ?>
			</button>
			<nav id="site-navigation" class="menu-wrapper" role="navigation">
				<button class="menu-trigger  menu--close  js-menu-trigger">

					<?php get_template_part( 'assets/svg/close-icon-svg' ); ?>

				</button>

				<?php
				wp_nav_menu( array(
					'container' => false,
					'theme_location' => 'primary',
					'menu_class' => 'primary-menu',
					'fallback_cb' => false,
					'walker' => new Bitstarter_Walker_Nav_Menu(),
				) );
				?>

			</nav>
			<?php endif; ?>
		</div><!-- site-header__in -->
	</header><!-- #masthead -->
	
	<div id="content" class="site-content ">
