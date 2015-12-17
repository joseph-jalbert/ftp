<?php


class Location_Taxonomy {

	const TAXONOMY = 'office_location';
	const TERM_OPTION_NAME = 'office_location_locations';
	const SLUG = 'news';

	public static function init() {
		self::attach_hooks();
	}

	public static function attach_hooks() {
		add_action( 'init', array( __CLASS__, 'register_taxonomy' ) );
		add_action( 'init', array( __CLASS__, 'taxonomy_rewrite_rule' ) );

		add_action( 'create_term', array( __CLASS__, 'flush_option' ), 10, 3 );
		add_action( 'edit_term', array( __CLASS__, 'flush_option' ), 10, 3 );
		add_action( 'delete_term', array( __CLASS__, 'flush_option' ), 10, 3 );
		add_filter( 'term_link', array( __CLASS__, 'filter_term_link' ), 10, 3 );
	}

	public static function register_taxonomy() {
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
				'slug'       => self::SLUG,
				'with_front' => true
			),
		);

		register_taxonomy( self::TAXONOMY, array( 'post' ), $args );
	}


	public static function taxonomy_rewrite_rule() {

		$terms = self::get_terms();
		foreach ( $terms as $slug ) {
			add_rewrite_rule( '^' . $slug . '/news/?', 'index.php?office_location=' . $slug, 'top' );
		}

	}

	public static function get_terms() {

		$term_option_names = get_option( self::TERM_OPTION_NAME );
		if ( ! $term_option_names ) {
			$term_option_names = self::build_term_option();
		}

		return $term_option_names;

	}

	public static function build_term_option() {

		self::kill_term_option();
		$terms = get_terms( self::TAXONOMY, array( 'hide_empty' => false ) );
		$slugs = array();
		foreach ( $terms as $term ) {
			$slugs[] = $term->slug;
		}

		$term_option_names = update_option( self::TERM_OPTION_NAME, $slugs );

		return $term_option_names;
	}

	public static function kill_term_option() {

		delete_option( self::TERM_OPTION_NAME );

	}

	public static function flush_option( $term_id, $tt_id, $taxonomy ) {
		if ( self::TAXONOMY === $taxonomy ) {
			self::build_term_option();
			flush_rewrite_rules();
		}

	}

	public static function filter_term_link( $term_link, $term, $taxonomy ) {

		if ( self::TAXONOMY === $taxonomy ) {

			$term_link = str_replace( array( '/' . self::SLUG . '/', '/' . $term->slug . '/' ), array( '/%SLUG%/', '/%TERM%/' ), $term_link );
			$term_link = str_replace( array( '%SLUG%', '%TERM%' ), array($term->slug, self::SLUG), $term_link );

		}

		return $term_link;

	}

}

Location_Taxonomy::init();