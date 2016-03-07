<?php


class Local_News {

	const POST_TYPE = 'local_news';
	const LOCATION_OPTION = 'local_news_location';

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
		add_action( 'save_post', array( __CLASS__, 'save_
		meta_action' ), 10, 2 );
		add_filter( 'wpseo_breadcrumb_links', array( __CLASS__, 'update_breadcrumbs' ) );

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
			'supports'           => array( 'title', 'thumbnail', 'editor', 'author' ),
			'rewrite'            => array( 'slug' => 'local_news' ),

		) );

	}

	public static function add_rewrite_rule() {
		add_rewrite_rule( '^([^/]*)/blog/([^/]*)?$', 'index.php?post_type=' . preg_quote( Local_News::POST_TYPE ) . '&location_taxonomy=$matches[1]&name=$matches[2]', 'top' );
	}

	public static function post_type_permalink( $permalink, $post ) {

		if ( $post->post_type == Local_News::POST_TYPE ) {

			$terms     = get_the_terms( $post->ID, Location_Taxonomy::LOCATION_TAXONOMY );
			$term      = $terms[0]->slug;
			$permalink = str_replace( 'local_news/', $term . '/blog/', $permalink );


		}

		return $permalink;


	}

	public static function save_meta_action( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( $post->post_type === self::POST_TYPE ) {

			self::update_locations( $post_id );

		}


	}


	public static function update_locations( $post_id ) {

		$location_option = self::get_option( 'location' );
		$location_id     = null;
		$location        = wp_get_post_terms( get_the_ID(), Location_Taxonomy::LOCATION_TAXONOMY );
		if ( is_array( $location ) && $location ) {
			$location_option[ $post_id ] = array(
				'name' => $location[0]->name,
				'link' => get_term_link( $location[0], Location_Taxonomy::LOCATION_TAXONOMY )
			);
		}
		self::update_option( 'location', $location_option );


	}

	public static function get_option_name( $which ) {
		$option_name = false;
		switch ( $which ):
			case 'location':
				$option_name = self::LOCATION_OPTION;
				break;

		endswitch;

		return $option_name;
	}

	public static function get_option( $which ) {

		$option_name = self::get_option_name( $which );

		if ( ! $option_name ) {
			return false;
		}

		return (array) get_option( $option_name, array() );


	}

	public static function update_option( $which, Array $value ) {

		$option_name = self::get_option_name( $which );

		return update_option( $option_name, $value );


	}

	public static function update_breadcrumbs( $links ) {
		global $post;
		$post_type = isset( $post->post_type ) ? $post->post_type : null;
		if ( $post_type && self::POST_TYPE === $post_type ) {

			$links_option = self::get_option( 'location' );
			if ( array_key_exists( $post->ID, $links_option ) ) {
				$link_data    = $links_option[ $post->ID ];
				$link_element = sprintf( '<a href="%s">%s</a>', esc_html( $link_data['link'] ), esc_html( $link_data['name'] ) );
				$link_to_add  = array(
					array(
						'text'       => $link_element,
						'allow_html' => true
					)
				);
				array_splice( $links, count( $links ) - 1, 0, $link_to_add );


			}
		}
		if ( is_tax( Location_Taxonomy::LOCATION_TAXONOMY ) ) {

			array_pop( $links );

		}

		return $links;

	}


}

Local_News::init();


