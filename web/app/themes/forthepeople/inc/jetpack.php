<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package ForThePeople
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function forthepeople_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'forthepeople_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function forthepeople_jetpack_setup
add_action( 'after_setup_theme', 'forthepeople_jetpack_setup' );

function forthepeople_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function forthepeople_infinite_scroll_render