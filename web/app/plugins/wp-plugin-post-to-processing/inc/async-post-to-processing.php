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

		return array(
			'data'               => $data,
			'inquiry_website_id' => $inquiry_website_id,
			'post_url'           => $post_url,
			'entry'              => $entry
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

		do_action( 'wp_async_' . $this->action, $data, $inquiry_website_id, $post_url, $entry );

		update_option('ptp_submission_' . time(), array(
			'data'               => $data,
			'inquiry_website_id' => $inquiry_website_id,
			'post_url'           => $post_url,
			'entry'              => $entry
		));


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


		$ch = curl_init( $post_url );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER'] );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $d );

		$cookie_string = '';
		foreach ( $_COOKIE as $name => $value ) {
			if ( $cookie_string != '' ) {
				$cookie_string .= '; ';
			}
			$value = urlencode( $value );
			$cookie_string .= "{$name}={$value}";
		}
		curl_setopt( $ch, CURLOPT_COOKIE, $cookie_string );

		$result = curl_exec( $ch );

		$response = curl_getinfo( $ch );

		curl_close( $ch );

		$response_code = $response['http_code'];

		if ( $response_code != '302' ) {
			// something's wrong since we should be redirected to thank-you page
			error_log( "Error submitting lead to processing. \nResponse Code: $response_code \nBody: $result" );

			// Gravity Forms doesn't give us a lot of options for metadata
			// for now we'll just start the entry if submission fails
			// TODO: longer term we should do something more obvious
			$entry['is_starred'] = 1;
			GFAPI::update_entry( $entry );

		}

	}

}

new PTP;