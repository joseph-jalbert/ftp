<?php

class Filters {

	private static $old_meta = array();

	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'wp_footer', array( __CLASS__, 'reset_meta_data' ) );
		add_filter( 'user_can_richedit' , '__return_false' , 50 );
		add_filter( 'wpseo_canonical', array( __CLASS__, 'canonical_filter' ), PHP_INT_MAX, 1 );
		add_filter( 'wpseo_title', array( __CLASS__, 'remove_override' ), 10, 1 );
	}

	/**
	 * Removes the Yoast Canonical override option.
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	public static function remove_override( $title ) {
		$meta = self::$old_meta = get_option('wpseo_taxonomy_meta');
		$term = get_queried_object();
		if ( ! empty( $meta[$term->taxonomy][$term->term_id] ) ) {
			unset( $meta[$term->taxonomy][$term->term_id] );
		}
		update_option( 'wpseo_taxonomy_meta', $meta );
		return $title;
	}

	/**
	 * Re-add the old meta-data that was removed earlier
	 */
	public static function reset_meta_data() {
		update_option( 'wpseo_taxonomy_meta', self::$old_meta );
		self::$old_meta = array();
	}

	/**
	 * Put the new canonical link
	 * 
	 * @param $canonical
	 *
	 * @return mixed
	 */
	public static function canonical_filter( $canonical ) {
		if ( is_ssl() ) {
			$canonical = preg_replace("/^http:/i", "https:", $canonical);
		}
		return $canonical;
	}


}

Filters::init();