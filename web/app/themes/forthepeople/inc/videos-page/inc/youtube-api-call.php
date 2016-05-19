<?php

class YouTube_API_Call {

	private static $default_api_key = 'AIzaSyDkoWvbya6ZdjgMxFDm53T6XaAd1QdKcHc';
	private static $data_endpoint = 'https://www.googleapis.com/youtube/v3/videos?id=%s&key=%s&part=snippet';
	private $video_id = null;
	private $api_key;
	private $response;


	public function __construct( $video_id, $api_key = null ) {
		if ( ! $api_key ) {
			$this->api_key = self::$default_api_key;
		} else {
			$this->api_key = $api_key;
		}
		$this->video_id = $video_id;

	}

	private function build_api_url() {

		return sprintf( self::$data_endpoint, $this->video_id, $this->get_api_key() );

	}

	private function get_api_key() {
		return $this->api_key;
	}

	public function get() {

		$request = wp_remote_get( $this->build_api_url() );
		if ( is_wp_error( $request ) ) {
			throw new Exception( 'Error grabbing API' );
		}
		$this->response = json_decode( wp_remote_retrieve_body( $request ) );
		return $this->response;

	}


}