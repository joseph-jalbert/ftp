<?php

/**
 * @author David Smith
 * @author Mat Gargano
 * Class GFCookie
 */
class GFCookie {

	private $cookie_prefix = 'abg_';
	private $cookie_length = 2592000; // 30 days
	private $field_map;
	private $extant_cookies;
	private $overwrite_cookies_if_already_exist = true;
	private $preserve_keys = array();
	private $key_filter;

	/**
	 * GFCookie constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( Array $settings ) {

		$defaults = array(
			'cookie_prefix'                      => $this->cookie_prefix,
			'cookie_length'                      => $this->cookie_length,
			'overwrite_cookies_if_already_exist' => $this->overwrite_cookies_if_already_exist,
			'field_map'                          => array(),
			'preserve_keys'                      => $this->preserve_keys


		);

		$settings                                 = array_merge( $defaults, $settings );
		$this->field_map                          = $settings['field_map'];
		$this->cookie_prefix                      = $settings['cookie_prefix'];
		$this->cookie_length                      = (int) $settings['cookie_length'];
		$this->overwrite_cookies_if_already_exist = (int) $settings['overwrite_cookies_if_already_exist'];
		$this->preserve_keys                      = $settings['preserve_keys'];
		$this->key_filter                         = $settings['key_filter'];
		$this->preserve_keys                      = array_map( function ( $value ) {
			return $this->cookie_prefix . $value;
		}, $this->preserve_keys );

		$this->init();

	}

	/**
	 * setup our instance
	 */
	public function init() {

		//Check if cookies are already set, excluding the referer cookie
		$this->extant_cookies = array_filter_key( $this->get_cookie_data(), function ( $element ) {
			return strpos( $element, $this->cookie_prefix ) > - 1 && strpos( $element, 'HTTP_REFERER' ) === false;
		} );
		$this->attach_hooks();

	}

	/**
	 * Attach our hooks
	 */
	public function attach_hooks() {

		if ( ! is_admin() ) {
			add_action( 'parse_request', array( $this, 'set_cookies' ), 9 );
			add_filter( "salesforce_payload", array( $this, 'add_cookie_data_to_salesforce_post' ) );
		}

	}

	/**
	 * Save cookie process via $_GET and $_SERVER superglobals
	 */
	public function set_cookies() {

		// don't do anything with cookie if there is no data
		if ( ! empty( $_GET ) ) {

			$this->save_get_data();
		}

		$this->save_server_data();

	}


	public function get_cookie_data( $key = false ) {

		if ( ! $key ) {
			return $_COOKIE;
		}

		$prefixed_key = $this->cookie_prefix . $key;

		if ( isset( $_COOKIE[ $prefixed_key ] ) ) {
			return $_COOKIE[ $prefixed_key ];
		}

		return '';
	}


	public function save_server_data() {

		if ( array_key_exists( 'HTTP_REFERER', $_SERVER ) ) {
			$this->set_cookie( 'referer', $_SERVER['HTTP_REFERER'] );
		}

	}

	public function save_get_data() {

		if ( $this->extant_cookies && ! $this->overwrite_cookies_if_already_exist ) {
			return;
		}

		$cookies_cleared = false;

		foreach ( $_GET as $key => $value ) {
			$key = $this->map_field( $key );

			if ( $key ) {


				if ( ! $cookies_cleared ) {
					$this->delete_cookie_family();
					$cookies_cleared = true;
				}

				$this->set_cookie( $key, $value );
			}

		}
		$this->set_cookie( 'timestamp', time() );

	}

	public function set_cookie( $key, $value ) {

		// remap the field for things not set in the $_GET param
		// @todo rewrite it so there is no need for remapping?

		$key = $this->map_field( $key );

		$prefixed_key = $this->cookie_prefix . $key;

		if ( ! $this->cookie_preserved( $prefixed_key ) ) {
			$this->delete_cookie( $key );
			setcookie( $prefixed_key, $value, time() + YEAR_IN_SECONDS, '/' );

		}

	}


	/**
	 * delete all of our cookies
	 */
	private function delete_cookie_family() {

		foreach ( $_COOKIE as $key => $value ) {

			if ( ! $this->cookie_preserved( $key ) ) {
				$this->delete_cookie( $key );
			}


		}
	}

	/**
	 * delete individual cookie
	 * n.b. do not include prefix in argument
	 *
	 * @param $key
	 */
	public function delete_cookie( $key ) {

		if ( substr( $key, 0, strlen( $this->cookie_prefix ) ) === $this->cookie_prefix ) {
			$key = substr( $key, strlen( $this->cookie_prefix ) );
		}

		setcookie( $this->cookie_prefix . $key, null, time() - 3600, '/' );
	}


	/**
	 * Let's map our query parameter's key to the Salesforce ID
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public function map_field( $name ) {

		$keys = array_keys( $this->field_map );
		if ( array_key_exists( $name, $this->field_map ) ) {
			return $keys[ array_search( $name, $keys ) ];
		} else {
			$counter = 0;
			foreach ( $this->field_map as $field ) {

				if ( array_search( $name, $field['keys'] ) !== false ) {
					return $keys[ $counter ];
				}
				$counter ++;
			}
		}

		return false;
	}

	/**
	 * Add cookie data to the Salesforce payload
	 *
	 * @param array $payload
	 *
	 * @return array
	 */
	public function add_cookie_data_to_salesforce_post( $payload = array() ) {

		$cookie      = (array) $this->get_cookie_data( false );
		$our_cookies = array();
		foreach ( $cookie as $key => $value ) {

			// skip cookies that were not added by us
			if ( strpos( $key, $this->cookie_prefix ) === false ) {
				continue;
			}

			// load cookie value into $our_cookies for population into Gravity Forms
			$key = str_replace( $this->cookie_prefix, '', $key );
			if ( isset( $this->field_map[ $key ]['filter'] ) ) {

				$value = call_user_func( $this->field_map[ $key ]['filter'], $value );
			}

			if ( isset( $this->key_filter ) ) {
				$key = call_user_func( $this->key_filter, $key );
			}

			$our_cookies[ $key ] = $value;
		}


		$intake = array();
		if ( isset( $payload['intake'] ) ) {
			$intake = $payload['intake'];
		}

		if ( $our_cookies ) {
			$payload['intake'] = array_merge( $intake, $our_cookies );
		}


		return $payload;
	}

	public function cookie_preserved( $key ) {

		if ( in_array( $key, $this->preserve_keys ) && ! empty( $_COOKIE[ $key ] ) ) {
			return true;
		}

		return false;

	}

}
