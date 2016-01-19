<?php

class Block_Robots {


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'init', array( __CLASS__, 'block_robots_txt' ) );
	}


	public static function block_robots_txt() {
		$url        = get_site_url();
		$parsed_url = parse_url( $url );
		$host       = explode( '.', $parsed_url['host'] );
		if ( ( count( $host ) > 2 && ( $host[0] !== 'www' ) ) || 'dev' === $host[ count( $host ) - 1 ] ) {
			$value = apply_filters( 'mm_block_robots', false, $url, $parsed_url, $host );
			if ( ! $value ) {
				add_filter( 'option_blog_public', '__return_false', PHP_INT_MAX );
			}


		}
	}

}

Block_Robots::init();