<?php


class Local_News {

	const POST_TYPE = 'local_news';

	/**
	 *
	 */
	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'init', array( __CLASS__, 'add_rewrite_rule' ) );
		add_filter( 'post_type_link', array( __CLASS__, 'post_type_permalink' ), 10, 2 );

	}


	/**
	 *
	 */
	public static function register_post_type() {

		register_post_type( self::POST_TYPE, array(
			'labels'             => array(
				'name'               => __( 'Local News', 'forthepeople' ),
				'singular_name'      => __( 'Local News', 'forthepeople' ),
				'add_new'            => __( 'Add New', 'forthepeople' ),
				'add_new_item'       => __( 'Add New Local News', 'forthepeople' ),
				'edit_item'          => __( 'Edit Local News', 'forthepeople' ),
				'new_item'           => __( 'New Local News', 'forthepeople' ),
				'all_items'          => __( 'All Local News', 'forthepeople' ),
				'view_item'          => __( 'View Local News', 'forthepeople' ),
				'search_items'       => __( 'Search Local News', 'forthepeople' ),
				'not_found'          => __( 'No Local News Found', 'forthepeople' ),
				'not_found_in_trash' => __( 'No Local News found in Trash', 'forthepeople' ),
				'menu_name'          => __( 'Local News', 'forthepeople' )
			),
			'menu_icon'          => 'dashicons-visibility',
			'publicly_queryable' => true,
			'public'             => true,
			'supports'           => array( 'title', 'thumbnail', 'editor' ),
			'rewrite'            => array( 'slug' => 'blog' ),

		) );

	}

	public static function add_rewrite_rule() {
//		add_rewrite_rule( '^([^/]*)/blog/([^/]*)?$', 'index.php?post_type=' . preg_quote( Local_News::POST_TYPE ) . '&location_taxonomy=$matches[1]&name=$matches[2]', 'top' );
	}

	public static function post_type_permalink( $permalink, $post ) {
		if ( $post->post_type == Local_News::POST_TYPE ) {
			global $post;
			$terms     = get_the_terms( $post->id, Location_Taxonomy::LOCATION_TAXONOMY );
			$term      = $terms[0]->slug;
			$permalink = str_replace( 'blog/', $term . '/blog/', $permalink );
		}

		return $permalink;
	}


}

Local_News::init();


