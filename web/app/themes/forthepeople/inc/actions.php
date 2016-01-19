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
		wp_enqueue_script( 'fooooo', 'http://google.com' );

	}


	public static function hubspot_tracking_code() {

		?>
		<!-- Start of Async HubSpot Analytics Code -->
		<!--noptimize-->
		<script type="text/javascript">
			(function (d, s, i, r) {
				if (d.getElementById(i)) {
					return;
				}
				var n = d.createElement(s), e = d.getElementsByTagName(s)[0];
				n.id = i;
				n.src = '//js.hs-analytics.net/analytics/' + (Math.ceil(new Date() / r) * r) + '/1841598.js';
				e.parentNode.insertBefore(n, e);
			})(document, "script", "hs-analytics", 300000);
		</script><!--/noptimize-->
		<!-- End of Async HubSpot Analytics Code -->
		<?php

	}


}

Actions::init();
