<?php

class Office_Page_Redirect {

	const POST_TYPE = 'office';


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'template_redirect', array( __CLASS__, 'redirect' ) );
	}

	public static function redirect() {

		global $post;
		if ( $post->post_type !== self::POST_TYPE ) {
			return;
		}

		wp_redirect( home_url( '/' . $post->post_name ), 301 );


	}

}

Office_Page_Redirect::init();