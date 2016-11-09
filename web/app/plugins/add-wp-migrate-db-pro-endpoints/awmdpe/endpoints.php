<?php

namespace awmdpe;

class endpoints {

	private $permission = 'activate_plugins';

	public function init() {
		$this->permission = apply_filters( 'awmdpe', $this->permission );
		$this->attach_hooks();

	}

	public function attach_hooks() {

		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );

	}

	public function rest_api_init() {
		register_rest_route( 'addendpoints/v1', '/wpmdb-key/get', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'get_wpmdb_key' ),

			'permission_callback' => function () {
				return current_user_can( $this->permission );
			}
		) );
	}

	public function get_wpmdb_key() {
		$settings = get_site_option( 'wpmdb_settings' );

		return [
			'url' => site_url( '', 'https' ),
			'key' => $settings['key']
		];
	}

}