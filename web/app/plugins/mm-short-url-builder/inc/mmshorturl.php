<?php

/**
 * @author Eric Savadian
 * Class MMShortURL
 */
class MMShortURL {

	const APIKEY_OPTION_NAME = 'mm_shorturl_apikey';
	const DOMAIN_OPTION_NAME = 'mm_shorturl_domain';
	const SERVICE_OPTION_NAME = 'mm_shorturl_service';
	public static $mm_shorturl_campaign_source = array(
		"facebook" => "Facebook",
		"twitter"  => "Twitter",
		"google"   => "Google",
		"linkedin" => "LinkedIn"
	);
	public static $mm_shorturl_campaign_medium = array(
		"organic" => "Organic",
		"cpc"     => "CPC"
	);

	public static function init() {
		self::attach_hooks();
	}

	private static function attach_hooks() {
		add_action( 'admin_init', array( __CLASS__, 'add_apikey_setting' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_domain_setting' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_service_setting' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_tools_page' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_plugin_scripts' ) );
		add_action( 'edit_form_after_title', array( __CLASS__, 'render_plugin_form' ) );
		add_action( 'wp_ajax_shorten_url', array( __CLASS__, 'shorten_url' ) );
		add_action( 'wp_ajax_shorten_any_url', array( __CLASS__, 'shorten_any_url' ) );
	}

	public static function enqueue_plugin_scripts( $hook ) {
		if ( ! self::check_all_settings_filled() ) {
			return;
		}

		if ( 'post.php' !== $hook || 'tools_page_mm_shorturl_any' !== $hook ) {
			if ( 'post.php' === $hook ) {
				wp_enqueue_script( 'shorten_url', plugins_url( 'mmshortposturl.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_style( 'shorten_url_css', plugins_url( 'mmshortposturl.css', __FILE__ ) );
			} else if ( 'tools_page_mm_shorturl_any' === $hook ) {
				wp_enqueue_script( 'shorten_any_url', plugins_url( 'mmshortanyurl.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_style( 'shorten_any_url_css', plugins_url( 'mmshortanyurl.css', __FILE__ ) );
			}
		} else {
			return;
		}
	}

	public static function render_plugin_form( $post ) {
		if ( ! self::check_all_settings_filled() ) {
			self::render_settings_notice();
			return;
		}

		?><a href="#" class="button button-small" id="url_shortener_start">Build Campaign Tracking URL</a>
		<ul id="mm_shorturl_form" class="hidden">
			<li>
				<label for="mm_shorturl_output_url"><strong>Shortened URL:</strong>
					<input name="mm_shorturl_output_url" id="mm_shorturl_output_url" readonly>
				</label>
			</li>
			<br/>

			<li>
				<label for="mm_short_url_output_full_url"><strong>Full Tracking URL:</strong>
					<textarea name="mm_short_url_output_full_url" id="mm_shorturl_output_full_url" readonly></textarea>
				</label>
			</li>
			<br/>

			<input type="hidden" name="url_shortener_nonce"
			       id="url_shortener_nonce" value="<?php echo wp_create_nonce( 'url_shortener_nonce' ); ?>">

			<input type="hidden" name="url_shortener_permalink" id="url_shortener_permalink"
			       value="<?php echo get_permalink( $post->ID ) ?>">

			<li><label for="url_shortener_source"><strong>Campaign Source</strong>
					<select name="url_shortener_source" id="url_shortener_source"
					        class="url_shortener_input url_shortener_required_input">
						<option disabled selected id="url_shortener_source_default"></option>
						<?php foreach ( self::$mm_shorturl_campaign_source as $key => $value ) {
							echo '<option value ="' . $key . '">' . $value . '</option>';
						} ?>
					</select>
				</label></li>
			<br/>

			<li><label for="url_shortener_medium"><strong>Campaign Medium</strong>
					<select name="url_shortener_medium" id="url_shortener_medium"
					        class="url_shortener_input url_shortener_required_input">
						<option disabled selected id="url_shortener_medium_default"></option>
						<?php foreach ( self::$mm_shorturl_campaign_medium as $key => $value ) {
							echo '<option value ="' . $key . '">' . $value . '</option>';
						} ?>
					</select>
				</label></li>
			<br/>

			<li><label for="url_shortener_term"><strong>Campaign Term</strong>&nbsp;<em>(optional)</em>
					<input type="text" name="url_shortener_term" id="url_shortener_term" class="url_shortener_input">
				</label></li>
			<br/>

			<li><label for="url_shortener_content"><strong>Campaign Content</strong>&nbsp;<em>(optional)</em>
					<input type="text" name="url_shortener_content" id="url_shortener_content"
					       class="url_shortener_input">
				</label></li>
			<br/>

			<li><label for="url_shortener_name"><strong>Campaign Name</strong>
					<input type="text" name="url_shortener_name" id="url_shortener_name"
					       class="url_shortener_input url_shortener_required_input">
				</label></li>
			<br/>

			<a href="#" id="mm_shorturl_submit_button" class="button button-small">Retrieve Tracking Short URL</a>
			<a href="#" id="mm_shorturl_clear_button" class="button button-small">Clear Form</a>
		</ul>
		<?php
	}

	public static function render_tools_form() {
		?><div class="wrap"><h2>Shorten any URL</h2>
			<form action="" id="mm_shorturl_any_form">
				<ul>
					<li>
						<label for="mm_shorturl_any_output_url"><strong>Shortened URL:</strong>
							<input name="mm_shorturl_any_output_url" id="mm_shorturl_any_output_url" readonly>
						</label>
					</li>
					<br/>

					<li>
						<label for="mm_shorturl_any_input_url"><strong>Input URL:</strong>
							<input name="mm_shorturl_any_input_url" id="mm_shorturl_any_input_url">
						</label>
					</li>
					<br/>

					<input type="hidden" name="url_shortener_nonce"
					       id="url_shortener_nonce" value="<?php echo wp_create_nonce( 'url_shortener_nonce' ); ?>">

					<a href="#" id="mm_shorturl_any_submit_button" class="button button-small">Shorten URL</a>
					<a href="#" id="mm_shorturl_any_clear_button" class="button button-small">Clear</a>
				</ul>
			</form>
		</div><?php
	}

	private static function render_settings_notice() {
		?><a href="#" class="button button-small disabled" id="url_shortener_start">Configure URL Shortener Settings to Generate Tracking URLs</a><?php
	}

	public static function shorten_url() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'url_shortener_nonce' ) ) {
			wp_send_json_error( array( 'message' => 'Failed to verify nonce.' ) );
		} elseif ( empty( $_POST['source'] ) || empty( $_POST['medium'] ) || empty( $_POST['name'] ) ) {
			wp_send_json_error( array( 'message' => 'One of the required fields is missing. This should not happen.' ) );
		} else {
			self::run();
		}

		wp_die();
	}

	private static function run() {
		/* GoDaddy's URL Shortener tries to process encoded URLs and causes the API to choke on
		 * space and escaped spaces.  You have to escape space and then escape the percent to pass it.
		 * esc_url trims spaces and sanitize_text_field trims escaped spaces, so you need to build
		 * source_url and replace replace the space first, then we can escape ampersands and escape the url.
		 *
		 * Everything (especially multi-word query params) WILL BREAK if you change this without careful testing.
		 */
		$source_url = str_replace( " ", "%2520", self::build_source_url() );
		$source_url = str_replace( "&", "%26", $source_url );
		$source_url = esc_url( $source_url );

		$service_url      = self::build_service_url( $source_url );

		$service_response = wp_remote_get( $service_url );

		$arr = json_decode( $service_response['body']);

		$message = array(
			'response' => array( 'code' => $arr->statusCode ),
			'body' => $arr->shorturl
		);

		if ( is_wp_error( $service_response ) ) {
			wp_send_json_error( array(
				'message'   => $service_response->get_error_message(),
				'new_nonce' => wp_create_nonce( 'url_shortener_nonce' )
			) );
		} else {
			wp_send_json_success( array(
				'message'   => $message,
				'full_url'  => str_replace( "%2520", "%20", $source_url ), // see note above.
				'new_nonce' => wp_create_nonce( 'url_shortener_nonce' )
			) );
		}
	}


	public static function shorten_any_url() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'url_shortener_nonce' ) ) {
			wp_send_json_error( array( 'message' => 'Failed to verify nonce.' ) );
		} elseif ( empty( $_POST['targeturl'] ) ) {
			wp_send_json_error( array( 'message' => 'A target URL must be provided.' ) );
		} else {
			/* GoDaddy's URL Shortener tries to process encoded URLs and causes the API to choke on
			 * space and escaped spaces.  You have to escape space and then escape the percent to pass it.
			 * esc_url trims spaces and sanitize_text_field trims escaped spaces, so you need to build
			 * source_url and replace replace the space first, then we can escape ampersands and escape the url.
			 *
			 * Everything (especially multi-word query params) WILL BREAK if you change this without careful testing.
			 */
			$source_url = str_replace( "%20", "%2520", $_POST['targeturl'] );
			$source_url = str_replace( "%2B", "%252B", $source_url);
			$source_url = str_replace( "+", "%252B", $source_url);
			$source_url = str_replace( "&", "%26", $source_url );
			$source_url = esc_url( $source_url );

			$service_url      = self::build_service_url( $source_url );
			$service_response = wp_remote_get( $service_url );

			$arr = json_decode( $service_response['body']);

			$message = array(
				'response' => array( 'code' => $arr->statusCode ),
				'body' => $arr->shorturl
			);


			if ( is_wp_error( $service_response ) ) {
				wp_send_json_error( array(
					'message'   => $service_response->get_error_message(),
					'new_nonce' => wp_create_nonce( 'url_shortener_nonce' )
				) );
			} else {
				wp_send_json_success( array(
					'message'   => $message,
					'new_nonce' => wp_create_nonce( 'url_shortener_nonce' )
				) );
			}
		}

		wp_die();
	}

	private static function build_source_url() {
		$source_data     = array(
			'utm_source'   => sanitize_text_field( $_POST['source'] ),
			'utm_medium'   => sanitize_text_field( $_POST['medium'] ),
			'utm_term'     => sanitize_text_field( $_POST['term'] ),
			'utm_content'  => sanitize_text_field( $_POST['content'] ),
			'utm_campaign' => sanitize_text_field( $_POST['name'] )
		);
		$source_url_data = array_filter( $source_data );

		return add_query_arg( $source_url_data, sanitize_text_field( $_POST['permalink'] ) );
	}

	private static function build_service_url( $source_url ) {
		$service_url_data = array(
			'keyword' => self::generate_keyword(),
			'url'    => $source_url,
			'signature' => get_option( self::APIKEY_OPTION_NAME ),
			'action' => 'shorturl',
			'format' => 'json'
		);

		return add_query_arg( $service_url_data, get_option( self::SERVICE_OPTION_NAME ) );
	}

	private function generate_keyword() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 3; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return 'q' . $randomString;
	}

	public static function add_apikey_setting() {
		self::add_setting( self::APIKEY_OPTION_NAME, 'mm_shorturl_apikey', 'URL Shortener API Key', array(
			__CLASS__,
			'output_apikey_setting'
		) );
	}

	public static function add_domain_setting() {
		self::add_setting( self::DOMAIN_OPTION_NAME, 'mm_shorturl_domain', 'URL Shortener Domain', array(
			__CLASS__,
			'output_domain_setting'
		) );
	}

	public static function add_service_setting() {
		self::add_setting( self::SERVICE_OPTION_NAME, 'mm_shorturl_service', 'URL Shortener Service', array(
			__CLASS__,
			'output_service_setting'
		) );
	}

	private static function add_setting( $option_name, $handle, $description, $callback ) {
		register_setting(
			'general',
			$option_name
		);
		add_settings_field(
			$handle,
			$description,
			$callback,
			'general'
		);
	}

	public static function add_tools_page() {
		add_management_page( 'Shorten Any URL', 'URL Shortener', 'publish_pages', 'mm_shorturl_any', array( __CLASS__, 'render_tools_form' ) );
	}

	public static function output_apikey_setting() {
		self::output_setting( self::APIKEY_OPTION_NAME );
	}

	public static function output_domain_setting() {
		self::output_setting( self::DOMAIN_OPTION_NAME );
	}

	public static function output_service_setting() {
		self::output_setting( self::SERVICE_OPTION_NAME );
	}

	private static function output_setting( $setting ) {
		$option      = get_option( $setting );
		$option_name = esc_attr( $setting );

		?><label for="<?php echo $option_name ?>">
		<input type="text" name="<?php echo $option_name ?>" id="<?php echo $option_name ?>"
		       value="<?php echo $option ?>" class="regular-text">
		</label><?php
	}

	private static function check_all_settings_filled() {
		if ( empty( get_option( self::DOMAIN_OPTION_NAME ) ) ||
		     empty( get_option( self::SERVICE_OPTION_NAME ) )
		) {
			return false;
		}

		return true;
	}
}
