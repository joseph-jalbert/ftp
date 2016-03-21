<?php

class BTG_Taxonomy {

	const CATEGORY_TAXONOMY = 'btg_category';
	const CATEGORY_TAXONOMY_SLUG = 'btg_category';
	const POST_TYPE = BTG_News::POST_TYPE;


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
		add_filter( 'query_vars', array( __CLASS__, 'blog_archive_query_var' ) );
		add_filter( 'request', array( __CLASS__, 'blog_archive_request' ), PHP_INT_MAX );

	}


	public static function register_taxonomies() {
		self::register_category_taxonomy();
	}



	public static function register_category_taxonomy() {
		$labels = array(
			'name'              => _x( 'BTG Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'BTG Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search BTG Category' ),
			'all_items'         => __( 'All BTG Category' ),
			'parent_item'       => __( 'Parent BTG Category' ),
			'parent_item_colon' => __( 'Parent BTG Category:' ),
			'edit_item'         => __( 'Edit BTG Category' ),
			'update_item'       => __( 'Update BTG Category' ),
			'add_new_item'      => __( 'Add New BTG Category' ),
			'new_item_name'     => __( 'New BTG Category Name' ),
			'menu_name'         => __( 'BTG Category' ),
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


	public function blog_archive_query_var( $public_query_vars ) {
		$public_query_vars[] = 'btg_archive';

		return $public_query_vars;
	}

	public function blog_archive_request( $query_vars ) {
		if ( isset( $query_vars['btg_archive'] ) ) {
			add_action( 'wp_head', function () {

				$template = '<meta name="robots" content="%s">';
				if ( have_posts() ) :
					$content = 'follow';
				else:
					$content = 'noindex, follow';
				endif;
				echo sprintf( $template, esc_attr( $content ) );


			} );
		}

		return $query_vars;
	}


}

// turned off for now
//BTG_Taxonomy::init();