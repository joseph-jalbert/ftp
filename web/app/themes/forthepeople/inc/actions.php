<?php

class Actions {

	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'script_loader_tag', array( __CLASS__, 'enqueue_additions' ), 10, 2 );
		add_action( 'wp_footer', array( __CLASS__, 'footer_scripts' ) );
		add_action( 'wp_head', array( __CLASS__, 'meta_fields' ) );

	}

	public static function meta_fields(){
		?><meta name="p:domain_verify" content="9bef95dda85621e5f627bfddb6b3c0be"/><?php
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

	public static function footer_scripts(){
		?>
		<script type='text/javascript'>
			window.__lo_site_id = 57538;

			(function() {
				var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
				wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://cdn') + '.luckyorange.com/w.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
			})();
		</script>
		<?php
	}


}

Actions::init();