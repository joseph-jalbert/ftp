<?php


class Location_Taxonomy {

	const LOCATION_TAXONOMY = 'office_location';
	const CATEGORY_TAXONOMY = 'location_category';
	const LOCATION_TERM_OPTION_NAME = 'office_location_locations';
	const LOCATION_POST_ID_OPTION_NAME = 'office_location_locations';
	const LOCATION_TAXONOMY_SLUG = 'news';
	const CATEGORY_TAXONOMY_SLUG = 'category';
	const POST_TYPE = 'post';


	public static function init() {
		self::attach_hooks();
	}

	public static function attach_hooks() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
		add_action( 'init', array( __CLASS__, 'taxonomy_rewrite_rule' ) );

		add_action( 'create_term', array( __CLASS__, 'flush_location_option' ), 10, 3 );
		add_action( 'edit_term', array( __CLASS__, 'flush_location_option' ), 10, 3 );
		add_action( 'delete_term', array( __CLASS__, 'flush_location_option' ), 10, 3 );
		add_filter( 'term_link', array( __CLASS__, 'filter_location_term_link' ), 10, 3 );
		add_action( 'save_post', array( __CLASS__, 'flush_location_post_id_option' ), 10, 3 );
		add_action( 'pre_get_posts', array( __CLASS__, 'remove_location_posts_from_main_blog' ) );
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


	public static function taxonomy_rewrite_rule() {

		$terms = self::get_location_terms();
		if ( ! is_array( $terms ) ) {
			return;
		}
		foreach ( $terms as $slug ) {
			add_rewrite_rule( '^' . $slug . '/news/?', 'index.php?office_location=' . $slug, 'top' );
		}

	}

	public static function get_location_terms() {

		$location_term_option_names = get_option( self::LOCATION_TERM_OPTION_NAME );
		if ( ! $location_term_option_names ) {
			$location_term_option_names = self::build_location_term_option();
		}

		return $location_term_option_names;

	}

	public static function build_location_term_option() {

		self::kill_location_term_option();
		$terms = get_terms( self::LOCATION_TAXONOMY, array( 'hide_empty' => false ) );
		$slugs = array();
		foreach ( $terms as $term ) {
			$slugs[] = $term->slug;
		}

		$location_term_option_names = update_option( self::LOCATION_TERM_OPTION_NAME, $slugs );

		return $location_term_option_names;
	}

	public static function kill_location_term_option() {

		delete_option( self::LOCATION_TERM_OPTION_NAME );

	}

	public static function flush_location_option( $_, $_, $taxonomy ) {
		if ( self::LOCATION_TAXONOMY === $taxonomy ) {
			self::build_location_term_option();
			flush_rewrite_rules();
		}

	}

	public static function get_location_post_ids() {

		$location_post_ids_option_names = get_option( self::LOCATION_POST_ID_OPTION_NAME );
		if ( ! $location_post_ids_option_names ) {
			$location_post_ids_option_names = self::build_location_post_id_option();
		}

		return $location_post_ids_option_names;

	}

	public static function build_location_post_id_option() {

		self::kill_location_post_id_option();
		$post_ids = array();
		$term_ids = get_terms(
			self::LOCATION_TAXONOMY,
			array(
				'fields' => 'ids'
			)
		);
		if ( $term_ids
		     && ! is_wp_error( $term_ids )
		) {
			$args  = array(
				'tax_query' => array(
					array(
						'taxonomy' => self::LOCATION_TAXONOMY,
						'terms'    => $term_ids,
					)
				),
			);
			$posts = new WP_Query( $args );
			if ( $posts->have_posts() ) {
				while ( $posts->have_posts() ) {
					$posts->the_post();
					$post_ids[] = get_the_ID();


				}
				wp_reset_postdata();

			}

		}
		$location_post_ids_option_names = update_option( self::LOCATION_POST_ID_OPTION_NAME, $post_ids );

		return $location_post_ids_option_names;


	}

	public static function kill_location_post_id_option() {

		delete_option( self::LOCATION_POST_ID_OPTION_NAME );

	}

	public static function flush_location_post_id_option( $post_id, $post, $update ) {

		if ( $post->post_type === self::POST_TYPE && ( $update === true || wp_get_post_terms( $post_id, self::LOCATION_TAXONOMY ) ) ) {
			self::build_location_post_id_option();
		}

	}

	public static function remove_location_posts_from_main_blog( WP_Query $query ) {

		if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
			$post_not_in_extant = $query->get( 'post__not_in' );
			$query->set( 'post__not_in', array_merge( (array) $post_not_in_extant, (array) self::get_location_post_ids() ) );
		}

	}

	public static function filter_location_term_link( $term_link, $term, $taxonomy ) {

		if ( self::LOCATION_TAXONOMY === $taxonomy ) {

			$term_link = str_replace( array( '/' . self::LOCATION_TAXONOMY_SLUG . '/', '/' . $term->slug . '/' ), array(
				'/%SLUG%/',
				'/%TERM%/'
			), $term_link );
			$term_link = str_replace( array( '%SLUG%', '%TERM%' ), array(
				$term->slug,
				self::LOCATION_TAXONOMY_SLUG
			), $term_link );

		}

		return $term_link;

	}

}

Location_Taxonomy::init();