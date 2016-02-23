<?php

class Actions {

	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'wp_footer', array( __CLASS__, 'hubspot_tracking_code' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		// only adding a filter here because it pertains to an action
		add_action( 'script_loader_tag', array( __CLASS__, 'enqueue_additions' ), 10, 2 );


	}

	public static function enqueue_additions( $tag, $handle ) {

		if ( 'hubspot-ie8-script' === $handle ) {
			$tag = '<!--[if lte IE 8]>' . $tag . '<![endif]-->';
		}

		return $tag;

	}

	public static function enqueue() {
		wp_enqueue_script( 'hubspot-ie8-script', '//js.hsforms.net/forms/v2-legacy.js' );
		wp_enqueue_script( 'hubspot-script', '//js.hsforms.net/forms/v2.js' );

	}


}

Actions::init();