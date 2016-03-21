<?php

class BTG_Settings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_settings_page() {
		// This page will be under "Settings"
		add_submenu_page(
			'edit.php?post_type=' . BTG_News::POST_TYPE,
			'Settings Admin',
			'BTG Settings',
			'manage_options',
			'btg-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'btg_options' );
		?>
		<div class="wrap">
			<h2>BTG Settings</h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'my_option_group' );
				do_settings_sections( 'my-setting-admin' );
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
			'btg_options', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'', // Title
			array( $this, 'print_section_info' ), // Callback
			'my-setting-admin' // Page
		);
		add_settings_field(
			'title',
			'Archive Page title',
			array( $this, 'title_callback' ),
			'my-setting-admin',
			'setting_section_id'
		);
	}
		public function title_callback()
		{
			printf(
				'<input type="text" id="title" class="widefat" name="btg_options[title]" value="%s" />',
				isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
			);
		}





	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['title'] ) ) {
			$new_input['title'] = sanitize_text_field( $input['title'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {

	}



}

if ( is_admin() ) {
	$my_settings_page = new BTG_Settings;
}