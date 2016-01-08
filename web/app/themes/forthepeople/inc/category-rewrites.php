<?php

class BlogCategoryRedirect {

	public static function init() {
		self::attach_hooks();
	}

	public static function attach_hooks () {
		add_filter( 'category_link', array( __CLASS__, 'no_category_parents' ), 1000, 2 );
		add_filter( 'category_rewrite_rules', array( __CLASS__, 'update_category_links') );
		add_filter( 'query_vars', array( __CLASS__, 'no_category_parents_query_vars' ) );
		add_filter( 'request', array( __CLASS__, 'no_category_parents_request' ) );
	}

	// Set the desired URL
	public function update_category_links( $catlink, $category_id ) {
		$category = &get_category( $category_id );
		if ( is_wp_error( $category ) ) :
			return $category;
		endif;

		$category_nicename = $category->slug;

		if ( 0 === $category->parent ) {
			$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $category_nicename, 'category' );
		} else {
			$catlink = trailingslashit( get_option( 'home' ) ) . 'blog/category/' . user_trailingslashit( $category_nicename, 'category' );
		}

		return $catlink;
	}

	// Add our custom category rewrite rules
	public function no_category_parents_rewrite_rules() {
		$category_rewrite = array();
		$categories       = get_categories( array( 'hide_empty' => false ) );
		foreach ( $categories as $category ) {
			$category_nicename                                                                        = $category->slug;

			$category_rewrite[ 'blog\/category(\/' . $category_nicename . ')\/(?:feed/)?(feed|rdf|rss|rss2|atom)\/?$' ] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
			$category_rewrite[ 'blog\/category(\/' . $category_nicename . ')\/page\/?([0-9]{1,})\/?$' ]                  = 'index.php?category_name=$matches[1]&paged=$matches[2]';
			$category_rewrite[ 'blog\/category(\/' . $category_nicename . ')\/?$' ]                                    = 'index.php?category_name=$matches[1]';
		}
		// Redirect support from Old Category Base
		$category_rewrite[ 'category\/blog\/(.+)$' ] = 'index.php?category_redirect=blog/category/$matches[1]';

		return $category_rewrite;
	}

	// Add 'category_redirect' query variable
	public function no_category_parents_query_vars( $public_query_vars ) {
		$public_query_vars[] = 'category_redirect';

		return $public_query_vars;
	}

	// Redirect if 'category_redirect' is set
	public function no_category_parents_request( $query_vars ) {

		if ( isset( $query_vars['category_redirect'] ) ) {
			$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category');
			status_header( 301 );
			header( "Location: $catlink" );
			exit();
		}

		return $query_vars;
	}
}

BlogCategoryRedirect::init();
