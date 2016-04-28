<?php



class GoogleSettings {
	/**
	* Holds the values to be used in the fields callbacks
	*/
	private $options;

	/**
	* Start up
	*/
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	* Add options page
	*/
	public function add_plugin_page() {
		add_options_page(
			'Settings Admin',
			'Google API Settings',
			'manage_options',
			'google-api-settings-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	* Options page callback
	*/
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'google_api_key' );
		?>
		<div class="wrap">
			<h2>Google API Settings</h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'my_option_group' );
				do_settings_sections( 'google-api-settings-admin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'my_option_group', // Option group
			'google_api_key', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'API Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'google-api-settings-admin' // Page
		);

		add_settings_field(
			'google_api_key', // ID
			'Google API Key', // Title
			array( $this, 'api_key_callback' ), // Callback
			'google-api-settings-admin', // Page
			'setting_section_id' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['google_api_key'] ) )
			$new_input['google_api_key'] = $input['google_api_key'];

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function api_key_callback()
	{
		printf(
			'<input type="text" id="google_api_key" name="google_api_key[google_api_key]" value="%s" style="width: 335px;"/>',
			isset( $this->options['google_api_key'] ) ? esc_attr( $this->options['google_api_key']) : ''
		);
	}
}

if( is_admin() )
	$my_settings_page = new GoogleSettings();