<?php

class Actions {

	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'wp_footer', array( __CLASS__, 'hubspot_tracking_code' ) );


	}


	public static function hubspot_tracking_code() {

		?>
		<!-- Start of Async HubSpot Analytics Code -->
		<!--noptimize--><script type="text/javascript">
			(function(d,s,i,r) {
				if (d.getElementById(i)){return;}
				var n=d.createElement(s),e=d.getElementsByTagName(s)[0];
				n.id=i;n.src='//js.hs-analytics.net/analytics/'+(Math.ceil(new Date()/r)*r)+'/1841598.js';
				e.parentNode.insertBefore(n, e);
			})(document,"script","hs-analytics",300000);
		</script><!--/noptimize-->
		<!-- End of Async HubSpot Analytics Code -->
		<?php

	}


}

Actions::init();
