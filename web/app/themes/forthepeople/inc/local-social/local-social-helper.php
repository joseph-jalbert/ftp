<?php

class Local_Social_Helper {

	private static $slug = 'office-location.php';


	public static function is_local() {
		$local_page    = self::is_local_page();
		if ( $local_page ) :
			return $local_page;
		endif;

		$local_news    = self::is_local_news();
		if ( $local_news ) :
			return $local_news;
		endif;

		$local_archive = self::is_local_archive();
		if ( $local_archive ) :
			return $local_archive;
		endif;

		return false;
	}

	/**
	 * Will return true if we're on a local page or a direct child of the local page
	 * Will return false otherwise
	 * @return bool|int
	 */
    private static function is_local_page( $post = null ) {
        if ( null === $post ) :
            $post = get_queried_object();
        endif;
        if ( ! $post || ! $post instanceof \WP_Post ) :
            return false;
        endif;

        $parent                 = $post->post_parent;
        $post_to_check_template = $post->ID;
        if ( $parent > 0 ) :
            $post_to_check_template = $post->post_parent;
        endif;

        if ( self::$slug === get_page_template_slug( $post_to_check_template ) ) :
            return $post_to_check_template;
        elseif ( 0 === $post->post_parent ) :
            return false;
        else :
            return self::is_local_page( get_post( $parent ) );
        endif;
    }

	private static function is_local_news() {
		$post = get_queried_object();
		if ( ! $post || ! $post instanceof \WP_Post ) :
			return false;
		endif;

		$local_news_post_id = $post->ID;
		if ( Local_News::POST_TYPE === $post->post_type ) :
			$locations = wp_get_object_terms( $local_news_post_id, Location_Taxonomy::LOCATION_TAXONOMY );
			$location = array_shift( $locations );
			$post_slug = $location->slug;
			return self::get_id_from_slug( $post_slug );
		endif;

		return false;
	}

	private static function is_local_archive() {
		if ( get_query_var( 'local_blog_archive' ) ) :
			$location      = get_query_var( 'office_location' );
			return self::get_id_from_slug( $location );
		endif;

		return false;
	}

	public static function get_field( $field_name, $post_id ) {
		$post_meta        = get_field( $field_name, $post_id );
		if ( $post_meta ) :
			return $post_meta;
		endif;

		$location_page_id = get_field( 'location_page_id', $post_id );
		if ( $location_page_id ) :
			$location_page_meta = get_field( $field_name, $location_page_id );
			if ( $location_page_meta ) :
				return $location_page_meta;
			endif;
		endif;

		return false;
	}

	private function get_id_from_slug( $slug ) {
		$post = get_page_by_path( '/' . $slug );
		if ( $post ) :
			return $post->ID;
		endif;

		return false;

	}

}