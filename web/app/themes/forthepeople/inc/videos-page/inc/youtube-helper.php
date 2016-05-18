<?php

class YouTube_Helper {

	public static function init() {

		self::attach_hooks();
	}

	public static function attach_hooks() {

		add_action( 'wp_ajax_get_youtube_data', array( __CLASS__, 'get_youtube_data' ) );

	}

	public static function get_youtube_data() {
		$youtube_id      = sanitize_text_field( $_POST['youtubeId'] );
		$post_id         = sanitize_text_field( $_POST['postid'] );
		$response_object = new YouTube_API_Call( $youtube_id, YouTube_Global_Settings::get_youtube_api_key() );
		$response        = $response_object->get();
		if ( ! isset( $response->items ) || ! is_array( $response->items ) || ! $response->items || 0 === count( $response->items ) ) {
			wp_send_json_error( array(
				'message' => 'Invalid YouTube ID'
			) );
		}
		$first_item_of_response = array_shift( $response->items );


		$url        = $first_item_of_response->snippet->thumbnails->high->url;
		$image_id   = Add_Arbitrary_Image::get( $url, $post_id );
		$image_data = array( 'image_id' => $image_id, 'image_url' => wp_get_attachment_url( $image_id ) );
		wp_send_json_success( array(
			'video'      => $first_item_of_response,
			'post_id'    => $post_id,
			'image_data' => $image_data
		) );

	}

	public static function get_youtube_url( $id ) {


		$template = 'https://www.youtube.com/watch?v=%s';

		return esc_url( sprintf( $template, $id ) );


	}


}

YouTube_Helper::init();