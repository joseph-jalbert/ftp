<?php

class MM_RobotsTXT {

	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_filter( 'robots_txt', array( __CLASS__, 'update_robots_txt' ) );

	}

	public static function update_robots_txt( $output ) {

		$output .= PHP_EOL . 'User-agent: *' . PHP_EOL .
		           'Disallow: /wp-admin/' . PHP_EOL .
		           'Disallow: /Landing/' . PHP_EOL .
		           'Disallow: /landing/' . PHP_EOL .
		           'Disallow: /thank-*/' . PHP_EOL .
		           'User-agent: Baiduspider-video' . PHP_EOL .
		           'Disallow: /' . PHP_EOL .
		           'User-agent: Baiduspider' . PHP_EOL .
		           'Disallow: /' . PHP_EOL .
		           'User-agent: Baiduspider-image' . PHP_EOL .
		           'Disallow: /' . PHP_EOL .
		           'User-agent: Yandex' . PHP_EOL .
		           'Disallow: /' . PHP_EOL .
		           'Sitemap: ' . get_home_url() . '/sitemap_index.xml' . PHP_EOL;


		return $output;


	}

}

MM_RobotsTXT::init();


