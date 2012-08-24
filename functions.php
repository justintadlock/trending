<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package Trending
 * @subpackage Functions
 * @version 0.3.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2011 - 2012, Justin Tadlock
 * @link http://themehybrid.com/themes/trending
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'trending_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function trending_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'secondary', 'header', 'before-content', 'after-content', 'after-singular' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-drop-downs' );
	add_theme_support( 'hybrid-core-seo' );
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* Add theme support for framework extensions. */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r', '3c-l', '3c-r', '3c-c' ) );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'dev-stylesheet' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	/* Add support for a custom background. */
	add_theme_support( 
		'custom-background',
		array(
			'default-color' => 'f7f7f7',
			'default-image' => trailingslashit( get_template_directory_uri() ) . 'images/gray-cross.png',
			'wp-head-callback' => 'trending_custom_background_callback'
		)
	);

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'trending_embed_defaults' );

	/* Set content width. */
	hybrid_set_content_width( 600 );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'trending_disable_sidebars' );
	add_action( 'template_redirect', 'trending_one_column' );

	/* Add classes to the comments pagination. */
	add_filter( 'previous_comments_link_attributes', 'trending_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes', 'trending_next_comments_link_attributes' );

	/* Add custom image sizes. */
	add_action( 'init', 'trending_add_image_sizes' );
}

/**
 * Adds custom image sizes for featured images.  The 'feature' image size is used for sticky posts.
 *
 * @since 0.1.0
 */
function trending_add_image_sizes() {
	add_image_size( 'feature', 600, 200, true );
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since 0.1.0
 */
function trending_one_column() {

	if ( !is_active_sidebar( 'primary' ) && !is_active_sidebar( 'secondary' ) )
		add_filter( 'get_theme_layout', 'trending_theme_layout_one_column' );

	elseif ( is_attachment() && 'layout-default' == theme_layouts_get_layout() )
		add_filter( 'get_theme_layout', 'trending_theme_layout_one_column' );
}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since 0.1.0
 */
function trending_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since 0.1.0
 */
function trending_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;

	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {

		if ( 'layout-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['primary'] = false;
			$sidebars_widgets['secondary'] = false;
		}
	}

	return $sidebars_widgets;
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 0.1.0
 */
function trending_embed_defaults( $args ) {

	$args['width'] = 600;

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$layout = theme_layouts_get_layout();

		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout )
			$args['width'] = 470;
		elseif ( 'layout-1c' == $layout )
			$args['width'] = 950;
	}

	return $args;
}

/**
 * Adds 'class="prev" to the previous comments link.
 *
 * @since 0.1.0
 */
function trending_previous_comments_link_attributes( $attributes ) {
	return $attributes . ' class="prev"';
}

/**
 * Adds 'class="next" to the next comments link.
 *
 * @since 0.1.0
 */
function trending_next_comments_link_attributes( $attributes ) {
	return $attributes . ' class="next"';
}

/**
 * Returns a set of image attachment links based on size.
 *
 * @since 0.1.0
 */
function trending_get_image_size_links() {

	/* If not viewing an image attachment page, return. */
	if ( !wp_attachment_is_image( get_the_ID() ) )
		return;

	/* Set up an empty array for the links. */
	$links = array();

	/* Get the intermediate image sizes and add the full size to the array. */
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	/* Loop through each of the image sizes. */
	foreach ( $sizes as $size ) {

		/* Get the image source, width, height, and whether it's intermediate. */
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
		if ( !empty( $image ) && ( true === $image[3] || 'full' == $size ) )
			$links[] = "<a class='image-size-link' href='" . esc_url( $image[0] ) . "'>{$image[1]} &times; {$image[2]}</a>";
	}

	/* Join the links in a string and return. */
	return join( ' <span class="sep">/</span> ', $links );
}

/**
 * This is a fix for when a user sets a custom background color with no custom background image.  What 
 * happens is the theme's background image hides the user-selected background color.  If a user selects a 
 * background image, we'll just use the WordPress custom background callback.
 *
 * @since 0.1.0
 * @link http://core.trac.wordpress.org/ticket/16919
 */
function trending_custom_background_callback() {

	// $background is the saved custom image, or the default image.
	$background = get_background_image();

	// $color is the saved custom color.
	$color = get_background_color();

	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', 'scroll' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";

		$style .= $image . $repeat . $position . $attachment;
	}

?>
<style type="text/css">body.custom-background { <?php echo trim( $style ); ?> }</style>
<?php

}

?>