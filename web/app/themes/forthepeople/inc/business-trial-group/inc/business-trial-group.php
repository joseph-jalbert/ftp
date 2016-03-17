<?php


class BTG_News {

	const POST_TYPE = 'btg_news';

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
				'name'               => __( 'BTG', 'forthepeople' ),
				'singular_name'      => __( 'BTG', 'forthepeople' ),
				'add_new'            => __( 'Add New', 'forthepeople' ),
				'add_new_item'       => __( 'Add New BTG', 'forthepeople' ),
				'edit_item'          => __( 'Edit BTG', 'forthepeople' ),
				'new_item'           => __( 'New BTG', 'forthepeople' ),
				'all_items'          => __( 'All BTG', 'forthepeople' ),
				'view_item'          => __( 'View BTG', 'forthepeople' ),
				'search_items'       => __( 'Search BTG', 'forthepeople' ),
				'not_found'          => __( 'No BTG Found', 'forthepeople' ),
				'not_found_in_trash' => __( 'No BTG found in Trash', 'forthepeople' ),
				'menu_name'          => __( 'BTG', 'forthepeople' )
			),
			'menu_icon'          => 'dashicons-welcome-learn-more',
			'publicly_queryable' => true,
			'public'             => true,
			'supports'           => array( 'title', 'thumbnail', 'editor', 'author' ),
			'rewrite'            => array( 'slug' => 'btg_news' ),

		) );

	}

	public static function add_rewrite_rule() {
		add_rewrite_rule( '^business-trial-group/blog/([^/]*)?$', 'index.php?post_type=' . preg_quote( BTG_News::POST_TYPE ) . '&name=$matches[1]', 'top' );
	}

	public static function post_type_permalink( $permalink, $post ) {

		if ( $post->post_type == BTG_News::POST_TYPE ) {




		}

		return $permalink;


	}



}

BTG_News::init();


