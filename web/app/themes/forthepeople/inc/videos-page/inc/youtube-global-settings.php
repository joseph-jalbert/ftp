<?php

class YouTube_Global_Settings {

	const OPTION_NAME = 'youtube_global_settings';
	private static $youtube_api_setting_name = 'youtube_api_key';

	public static function init() {
		self::attach_hooks();
	}

	public static function attach_hooks() {
		add_action( 'admin_init', array( __CLASS__, 'add_setting' ) );
		add_filter( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	public static function add_setting() {
		register_setting(
			'general',
			self::OPTION_NAME,
			array( __CLASS__, 'validate' )
		);
		add_settings_field(
			'youtube_api_key',
			'YouTube API Key',
			array( __CLASS__, 'output_setting' ),
			'general',
			'default'
		);
	}

	public static function output_setting() {

		$options = get_option( self::OPTION_NAME );

		$youtube_api_key = $options[ self::$youtube_api_setting_name ];

		?>
		<input type="text" class="regular-text" value="<?php echo $youtube_api_key; ?>"
		       name='<?php echo esc_attr( self::OPTION_NAME ); ?>[<?php echo esc_attr( self::$youtube_api_setting_name ); ?>]'
		       id="<?php echo esc_attr( self::OPTION_NAME ); ?>">
		<?php
	}

	public static function validate( $value ) {

		$value[ self::$youtube_api_setting_name ] = sanitize_text_field( $value[ self::$youtube_api_setting_name ] );

		return $value;

	}

	public static function get_youtube_api_key() {

		$options = get_option( self::OPTION_NAME );

		if ( isset( $options[ self::$youtube_api_setting_name ] ) ) {
			return $options[ self::$youtube_api_setting_name ];
		}

		return false;

	}

	public static function admin_notices() {

		if ( ! self::get_youtube_api_key() ) {
			$class   = 'notice notice-error';
			$message = __( 'Please enter a YouTube API Key! Set it in the <a href="%s">General Settings Page</a>', 'videos-page' );

			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, sprintf( $message, admin_url( '/options-general.php' ) ) );


		}


	}


}

YouTube_Global_Settings::init();