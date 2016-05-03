<?php


class Google_Helper {

	private static $bInitialized = false;

	protected static $google_api_key = 'AIzaSyBh7MqzTTLjjMzGpiWUylshG72U_FAPPkc';
	protected static $place_search_url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=%s&key=%s';
	protected static $place_details_url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=%s&key=%s';
	protected static $place_meta_field = 'google_place_id';
	protected static $place_md5_field = 'google_place_id_md5';

	public function init() {
		if (self::$bInitialized) {
			return;
		}

		$api_key_settings = get_option( 'google_api_key' );
		if ( ! empty( $api_key_settings['google_api_key'] ) ) :
			self::$google_api_key = $api_key_settings['google_api_key'];
		endif;

		self::$bInitialized = true;
	}

	public static function get_office_address( $post_id ) {
		self::init();

		$office_info = array();
		$address     = '';
		$parents     = get_post_ancestors( $post_id );
		$id          = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $post_id;
		$parent      = get_post( $id );
		$parentslug  = $parent->post_name;
		$officeinfo  = new WP_Query( 'post_type=office&name=' . $parentslug );

		if ( $officeinfo->have_posts() ) {
			while ( $officeinfo->have_posts() ) {
				$officeinfo->the_post();

				$office_info['title']   = get_the_title();
				$office_info['state']   = get_field( 'state' );
				$office_info['address'] = get_field( 'street_address' );
				$office_info['suite']   = get_field( 'suite_information' );
				$office_info['zipcode'] = get_field( 'zip_code' );
				if ( $locality = get_field( 'state_override' ) ) {
					$office_info['state'] = esc_html( $locality );
				}
			}

			$address = $office_info['address'] . ' ' . $office_info['suite'] . ', ' . $office_info['state'] . ' ' . $office_info['zipcode'];
		}
		wp_reset_postdata();

		return $address;
	}

	public static function get_place_id( $post_id ) {
		self::init();

		$gr        = new Google_Review();
		$settings  = $gr->get_settings();

		/**
		 * This will override all place ids that are set.
		 */
		if ( ! empty( $settings[2] ) && ! empty( $settings[2]['google-place-id'] ) ) :
			 return $settings[2]['google-place-id'];
		endif;


		$office_address = self::get_office_address( $post_id );
		if ( empty ( $office_address ) ) :
		endif;

		$place_id  = get_post_meta( $post_id, self::$place_meta_field, true );
		$place_md5 = get_post_meta( $post_id, self::$place_md5_field, true );
		$address   = 'Morgan and Morgan ' . $office_address;

		if ( md5( $address ) === $place_md5 && ! empty( $place_id ) ) :
			return $place_id;
		endif;

		$place_search_url = sprintf( self::$place_search_url, urlencode( $address ), self::$google_api_key );

		/**
		 * Check to see if we already have this cached
		 */
		$place_id_key = 'google-place-id-' . md5( $place_search_url );
		$data         = get_transient( $place_id_key );

		/**
		 * We don't have this in cache. Get remote data
		 */
		if ( false === $data ) :
			$data = wp_remote_get( $place_search_url, array( 'timeout' => 5 ) );
			set_transient( $place_id_key, $data, 86400 );
		endif;

		if ( is_wp_error( $data ) || empty( $data['body'] ) ) :
			return false;
		endif;

		$place_info = json_decode( $data['body'] );
		if ( null === $place_info ) {
			return false;
		}

		if ( isset( $place_info->error_message ) ) :
			echo '<!-- Google Error: ' . $place_info->error_message . ' -->';
			return false;
		endif;

		if ( empty( $place_info->results ) || empty( $place_info->results[0]->place_id ) ) :
			return false;
		endif;

		$place_id = $place_info->results[0]->place_id;
		update_post_meta( $post_id, self::$place_meta_field, $place_id );
		update_post_meta( $post_id, self::$place_md5_field, md5( $address ) );

		return $place_id;
	}

	public static function get_review() {
		global $post;

		self::init();

		$place_id  = Google_Helper::get_place_id( $post->ID );

		/**
		 * If no place id return
		 */
		if ( empty( $place_id ) ) :
			return false;
		endif;

		/**
		 * Check to see if we already have cached results for this address.
		 */
		$place_review_key = 'google-place-reviews-' . md5( $place_id );
		$return_reviews   = get_transient( $place_review_key );

		if ( false === $return_reviews ) :
			$place_url = sprintf( self::$place_details_url, $place_id, self::$google_api_key );

			/**
			 * Check to see if we already have this cached
			 */
			$place_data_key = 'google-place-data-' . md5( $place_url );
			$the_place      = get_transient( $place_data_key );

			/**
			 * We don't have this in cache. Get remote data
			 */
			if ( false === $the_place ) :
				$place_data = wp_remote_get( $place_url, array( 'timeout' => 5 ) );
				if ( ! is_wp_error( $place_data ) && ! empty( $place_data['body'] ) ) :
					$place_data_info = json_decode( $place_data['body'] );
					if ( ! empty( $place_data_info->result ) ) :
						$the_place = $place_data_info->result;
						set_transient( $place_data_key, $the_place, 86400 );
					endif;
				endif;
			endif;

			if ( ! empty( $the_place ) ) :
				$reviews = ! empty( $the_place->reviews ) ? $the_place->reviews : false;

				if ( ! empty( $reviews ) ) :
					foreach ( $reviews as $review ) :
						if ( $review->rating >= 4 ) :
							$return_reviews[] = $review;
						endif;
					endforeach;

					set_transient( $place_review_key, $return_reviews, 86400 );
				endif;
			endif;
		endif;

		if ( ! empty( $return_reviews ) ) :
			return $return_reviews[ array_rand( $return_reviews, 1 ) ];
		endif;

		return false;
	}

	public static function remove_transients() {
		global $wpdb;
		$sql = "SELECT * FROM `wp_options` WHERE `option_name` LIKE ('_transient_google-place-%')";

		$results = $wpdb->get_results( $sql );

		foreach ( $results as $result ) :
			delete_transient( str_replace( '_transient_', '', $result->option_name) );
		endforeach;

		return true;
	}
}