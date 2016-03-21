<?php


class BTG_News {

	const POST_TYPE = 'btg_news';
	private static $root_plugin_directory;
	private static $archive_query_var = 'btg_archive';
	private static $post_query_var = 'btg_post';
	const OPTION_NAME = 'btg_news_settings';

	/**
	 *
	 */
	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		self::$root_plugin_directory = dirname( __DIR__ );
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'init', array( __CLASS__, 'add_rewrite_rule' ), 1 );
		add_filter( 'post_type_link', array( __CLASS__, 'post_type_permalink' ), 10, 2 );
		add_filter( 'wpseo_breadcrumb_links', array( __CLASS__, 'update_breadcrumbs' ) );
		add_filter( 'template_include', array( __CLASS__, 'template_include' ) );
		add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
		add_action( 'init', array( __CLASS__, 'register_sidebar' ) );
		add_filter( 'wpseo_title', array( __CLASS__, 'wp_title' ), PHP_INT_MAX );


	}


	public static function wp_title( $title ) {

		if ( self::get_query_var_value() ) {
			$custom_title_option = get_option( 'btg_options' );
			$custom_title        = $custom_title_option['title'];
			if ( $custom_title ) {
				return $custom_title;
			}
		}

		return $title;

	}

	public static function query_vars( $query_vars ) {
		$query_vars[] = self::$archive_query_var;
		$query_vars[] = self::$post_query_var;

		return $query_vars;
	}


	public static function template_include( $template ) {

		if ( self::get_query_var_value() ) {
			$template = self::$root_plugin_directory . '/views/index.php';
		}

		return $template;


	}

	/**
	 *
	 */
	public static function register_post_type() {

		register_post_type( self::POST_TYPE, array(
			'labels'             => array(
				'name'               => __( 'BTG Blog', 'forthepeople' ),
				'singular_name'      => __( 'BTG Blog', 'forthepeople' ),
				'add_new'            => __( 'Add New', 'forthepeople' ),
				'add_new_item'       => __( 'Add New BTG Blog', 'forthepeople' ),
				'edit_item'          => __( 'Edit BTG Blog', 'forthepeople' ),
				'new_item'           => __( 'New BTG Blog', 'forthepeople' ),
				'all_items'          => __( 'All BTG Blog', 'forthepeople' ),
				'view_item'          => __( 'View BTG Blog', 'forthepeople' ),
				'search_items'       => __( 'Search BTG Blog', 'forthepeople' ),
				'not_found'          => __( 'No BTG Found', 'forthepeople' ),
				'not_found_in_trash' => __( 'No BTG found in Trash', 'forthepeople' ),
				'menu_name'          => __( 'BTG Blog', 'forthepeople' )
			),
			'menu_icon'          => 'dashicons-welcome-learn-more',
			'publicly_queryable' => true,
			'public'             => true,
			'supports'           => array( 'title', 'thumbnail', 'editor', 'author' ),
			'rewrite'            => array( 'slug' => self::POST_TYPE ),

		) );

	}

	public static function add_rewrite_rule() {

		add_rewrite_rule( '^business-litigation-lawyers\/blog\/page\/?([0-9]{1,})\/?$', 'index.php?' . preg_quote( self::$archive_query_var ) . '=true&post_type=' . preg_quote( self::POST_TYPE ) . '&paged=$matches[1]', 'top' );
		add_rewrite_rule( '^business-litigation-lawyers\/blog\/(?:feed\/)?(feed|rdf|rss|rss2|atom)\/?$', 'index.php?' . preg_quote( self::$archive_query_var ) . '=true&post_type=' . preg_quote( self::POST_TYPE ) . '&feed=$matches[2]', 'top' );
		add_rewrite_rule( '^business-litigation-lawyers\/blog\/([^/]*)?$', 'index.php?' . preg_quote( self::$post_query_var ) . '=true&post_type=' . preg_quote( self::POST_TYPE ) . '&name=$matches[1]', 'top' );
		add_rewrite_rule( '^business-litigation-lawyers\/blog?$', 'index.php?' . preg_quote( self::$archive_query_var ) . '=true&post_type=' . preg_quote( self::POST_TYPE ), 'top' );
		add_rewrite_rule( '^business-litigation-lawyers\/blog\/?$', 'index.php?' . preg_quote( self::$archive_query_var ) . '=true&post_type=' . preg_quote( self::POST_TYPE ), 'top' );


	}

	public static function post_type_permalink( $permalink, $post ) {

		if ( $post->post_type == self::POST_TYPE ) {

			$permalink = str_replace( 'btg_news/', 'business-litigation-lawyers/blog/', $permalink );


		}

		return $permalink;


	}

	public static function update_breadcrumbs( $links ) {
		global $post;
		$post_type = isset( $post->post_type ) ? $post->post_type : null;
		if ( self::get_query_var_value() ) {
			$post_type = self::POST_TYPE;
		}

		if ( $post_type && self::POST_TYPE === $post_type ) {


			$link_element = sprintf( '<a href="%s">%s</a>', esc_html( home_url( '/business-litigation-lawyers/blog' ) ), esc_html( 'Business Trial Group Blog' ) );
			$link_to_add  = array(
				array(
					'text'       => $link_element,
					'allow_html' => true
				)
			);
			array_splice( $links, count( $links ) - 1, 0, $link_to_add );


		}

		return $links;

	}

	public static function register_sidebar() {
		register_sidebar( array(
			'name'          => esc_html__( 'BTG Archive Sidebar', 'forthepeople' ),
			'id'            => 'sidebar-btg',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="cf widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>',
		) );

	}

	private static function get_query_var_value() {
		return get_query_var( self::$archive_query_var, false );
	}


}

BTG_News::init();


