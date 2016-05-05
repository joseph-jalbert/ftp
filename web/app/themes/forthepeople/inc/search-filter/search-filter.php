<?php

require __DIR__ . '/search-filter-settings.php';

class Search_Filter {

	protected static $exclude_posts_field = 'exclude_from_search';

	public static function init() {
		add_filter( 'pre_get_posts', array( __CLASS__, 'search_query' ) );
	}

	/**
	 * Add a meta query to filter out unwanted posts
	 * in search results
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public static function search_query( $query ) {

		if ( $query->is_search ) {
			$meta_query = array(
				'relation'    => 'OR',
				array(
					'key'     => self::$exclude_posts_field,
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => self::$exclude_posts_field,
					'value'   => 1,
					'compare' => '!='
				),
			);
			$query->set( 'meta_query', $meta_query );
		}

		return $query;
	}
}

Search_Filter::init();