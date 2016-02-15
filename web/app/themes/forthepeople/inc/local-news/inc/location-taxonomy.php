<?php

class Location_Taxonomy {

	const LOCATION_TAXONOMY = 'office_location';
	const CATEGORY_TAXONOMY = 'location_category';
	const LOCATION_TERM_OPTION_NAME = 'office_location_locations';
	const LOCATION_POST_ID_OPTION_NAME = 'office_location_locations';
	const LOCATION_TAXONOMY_SLUG = 'location_taxonomy';
	const CATEGORY_TAXONOMY_SLUG = 'category';
	const POST_TYPE = Local_News::POST_TYPE;


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
		add_action( 'init', array( __CLASS__, 'add_rewrite_rules' ) );
		add_filter( 'term_link', array( __CLASS__, 'filter_term_link' ), 10, 3 );
		add_filter( 'query_vars', array( __CLASS__, 'local_blog_archive_query_var' ) );
		add_filter( 'request', array( __CLASS__, 'local_blog_archive_request' ), PHP_INT_MAX );


	}

	public static function filter_term_link( $url, $term, $taxonomy ) {

		if ( self::LOCATION_TAXONOMY === $taxonomy ) {

			$url = esc_url( home_url( $term->slug . '/blog/' ) );

		}

		return $url;

	}

	public static function register_taxonomies() {
		self::register_location_taxonomy();
		self::register_category_taxonomy();
	}

	public static function register_location_taxonomy() {
		$labels = array(
			'name'              => _x( 'Location', 'taxonomy general name' ),
			'singular_name'     => _x( 'Location', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Location' ),
			'all_items'         => __( 'All Location' ),
			'parent_item'       => __( 'Parent Location' ),
			'parent_item_colon' => __( 'Parent Location:' ),
			'edit_item'         => __( 'Edit Location' ),
			'update_item'       => __( 'Update Location' ),
			'add_new_item'      => __( 'Add New Location' ),
			'new_item_name'     => __( 'New Location Name' ),
			'menu_name'         => __( 'Location' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => self::LOCATION_TAXONOMY_SLUG,
				'with_front' => true
			),
		);

		register_taxonomy( self::LOCATION_TAXONOMY, array( self::POST_TYPE ), $args );
	}

	public static function register_category_taxonomy() {
		$labels = array(
			'name'              => _x( 'Local Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Local Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Local Category' ),
			'all_items'         => __( 'All Local Category' ),
			'parent_item'       => __( 'Parent Local Category' ),
			'parent_item_colon' => __( 'Parent Local Category:' ),
			'edit_item'         => __( 'Edit Local Category' ),
			'update_item'       => __( 'Update Local Category' ),
			'add_new_item'      => __( 'Add New Local Category' ),
			'new_item_name'     => __( 'New Local Category Name' ),
			'menu_name'         => __( 'Local Category' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => self::CATEGORY_TAXONOMY_SLUG,
				'with_front' => true
			),
		);

		register_taxonomy( self::CATEGORY_TAXONOMY, array( self::POST_TYPE ), $args );
	}


	public static function add_rewrite_rules() {

		add_rewrite_rule( '^([^\/]*)\/(blog)\/(?:feed\/)?(feed|rdf|rss|rss2|atom)\/?$', 'index.php?local_blog_archive=true&' . preg_quote( self::LOCATION_TAXONOMY ) . '=$matches[1]&feed=$matches[3]', 'top' );
		add_rewrite_rule( '^([^\/]*)\/(blog)\/page\/?([0-9]{1,})\/?$', 'index.php?local_blog_archive=true&' . preg_quote( self::LOCATION_TAXONOMY ) . '=$matches[1]&paged=$matches[3]', 'top' );
		add_rewrite_rule( '^([^\/]*)\/blog\/?$', 'index.php?local_blog_archive=true&' . preg_quote( self::LOCATION_TAXONOMY ) . '=$matches[1]', 'top' );
	}

	public function local_blog_archive_query_var( $public_query_vars ) {
		$public_query_vars[] = 'local_blog_archive';

		return $public_query_vars;
	}

	public function local_blog_archive_request( $query_vars ) {
		if ( isset( $query_vars['local_blog_archive'] ) ) {
			add_action( 'wp_head', function () {

				if ( ! have_posts() ) :
					?><meta name="robots" content="noindex"><?php
				endif;


			} );
		}

		return $query_vars;
	}


}

Location_Taxonomy::init();