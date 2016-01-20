<?php

class PTP extends WP_Async_Task {

	protected $action = 'post_to_processing';

	/**
	 * Prepare data for the asynchronous request
	 *
	 * @throws Exception If for any reason the request should not happen
	 *
	 * @param array $data An array of data sent to the hook
	 *
	 * @return array
	 */
	protected function prepare_data( $data_sent ) {
		$data               = $data_sent[0];
		$inquiry_website_id = $data_sent[1];
		$post_url           = $data_sent[2];
		$entry              = $data_sent[3];
		$referer            = $data_sent[4];


		return array(
			'data'               => $data,
			'inquiry_website_id' => $inquiry_website_id,
			'post_url'           => $post_url,
			'entry'              => $entry,
			'referer'            => $referer
		);


	}

	/**
	 * Run the async task action
	 */
	protected function run_action() {

		$data               = $_POST['data'];
		$inquiry_website_id = $_POST['inquiry_website_id'];
		$post_url           = $_POST['post_url'];
		$entry              = $_POST['entry'];
		$referer            = $_POST['referer'];

		do_action( 'wp_async_' . $this->action, $data, $inquiry_website_id, $post_url, $entry );


		$d = array(
			'FirstName'        => $data['first_name'],
			'LastName'         => $data['last_name'],
			'Phone'            => $data['phone'],
			'Email'            => $data['email'],
			'Zip'              => $data['zip'],
			'CaseDetails'      => $data['description'],
			'joinNewsletter'   => ( $data['newsletter_signup'] ? '1' : 'false' ),
			'processPlease'    => 1,
			'inquiryWebsiteID' => $inquiry_website_id,
			'validate1'        => '1111',
			'validate2'        => '1111'
		);

		if ( array_key_exists( 'aid', $data ) && is_numeric( $data['aid'] ) ) {
			$d['attorney'] = $data['aid'];
		}
		if ( array_key_exists( 'is_landing', $data ) ) {
			$d['isLanding'] = $data['is_landing'];
		}

		$cookies = array();

		foreach ( $_COOKIE as $name => $value ) {

			$cookie        = new WP_Http_Cookie( $name );
			$cookie->name  = $name;
			$cookie->value = $value;
			$cookies[]     = $cookie;

		}

		$post_args = array(
			'method' => 'POST',
			'body'   => $d,
		);

		if ( $cookies ) {
			$post_args = array_merge( $post_args, array( 'cookies' => $cookies ) );
		}
		add_action( 'http_api_curl', array( __CLASS__, 'set_referer' ), 10, 3 );
		$post = wp_remote_post( $post_url, $post_args );
		remove_action( 'http_api_curl', array( __CLASS__, 'set_referer' ), 10 );


		$response_code = $post['response']->code;


		if ( $response_code != '302' ) {
			// something's wrong since we should be redirected to thank-you page
			error_log( "Error submitting lead to processing. \nResponse Code: $response_code \nBody: " . serialize( $post ) );

			// Gravity Forms doesn't give us a lot of options for metadata
			// for now we'll just start the entry if submission fails
			// TODO: longer term we should do something more obvious
			$entry['is_starred'] = 1;
			GFAPI::update_entry( $entry );

		}
		update_option( 'ptp_submission_' . time(), array(
			'data'               => $data,
			'posted_fields'      => $d,
			'inquiry_website_id' => $inquiry_website_id,
			'post_url'           => $post_url,
			'entry'              => $entry,
			'response_code'      => $response_code,
			'result'             => $post ? 'success' : 'failure',
			'result_body'        => serialize( $post ),
			'referer'            => $referer
		) );

	}

	public static function set_referer(&$handle, $r, $url){

		$referer = 'http://www.forthepeople.dev/';
		curl_setopt( $handle, CURLOPT_REFERER, $referer );

	}


}

new PTP;



