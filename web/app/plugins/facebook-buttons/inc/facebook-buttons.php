<?php

class MM_Facebook_Buttons {

	const SHORTCODE   = 'facebook-buttons';
	const OPTION_NAME = 'facebook_global_settings';

	private static $facebook_app_id_setting_name = 'facebook_app_id';
	private static $has_script                   = false;
	private static $has_root                     = false;

	public static function init() {
		self::attach_hooks();
	}

	public static function attach_hooks() {
		add_shortcode( self::SHORTCODE, array( __CLASS__, 'shortcode' ) );
		add_action( 'wp_footer', array( __CLASS__, 'add_script' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ), PHP_INT_MAX );
	}

	public static function register_settings() {
		register_setting(
			'general',
			self::OPTION_NAME,
			array( __CLASS__, 'validate' )
		);

		add_settings_field(
			'facebook_app_id',
			'Facebook App ID',
			array( __CLASS__, 'output_setting' ),
			'general',
			'default'
		);
	}

	public static function output_setting() {
		$options = get_option( self::OPTION_NAME );
		$app_id = $options[ self::$facebook_app_id_setting_name ];
		?>
		<input type="text" class="regular-text" value="<?php echo esc_attr( $app_id ); ?>"
		       name='<?php echo esc_attr( self::OPTION_NAME ); ?>[<?php echo esc_attr( self::$facebook_app_id_setting_name ); ?>]'
		       id="<?php echo esc_attr( self::OPTION_NAME ); ?>">
		<?php
	}

	public static function validate( $value ) {
		$value[ self::$facebook_app_id_setting_name ] = sanitize_text_field( $value[ self::$facebook_app_id_setting_name ] );
		return $value;
	}

	public static function shortcode( $atts ) {
		$options  = get_option( self::OPTION_NAME );
		$app_id   = $options[ self::$facebook_app_id_setting_name ];
		$qry_args = array();

		$atts = shortcode_atts( array(
			'url'        => null,
			'layout'     => null,
			'action'     => null,
			'show_faces' => null,
			'share'      => null,
			'width'      => null,
			'height'     => null,
			'class'      => null,
			'app_id'     => $app_id
		), $atts );

		$class                  = ! empty( $atts['class'] )      ? esc_attr__( $atts['class'] )            : null;
		$qry_args['href']       = ! empty( $atts['url'] )        ? urlencode( esc_attr__( $atts['url'] ) ) : urlencode( get_permalink() );
		$qry_args['layout']     = ! empty( $atts['layout'] )     ? esc_attr__( $atts['layout'] )           : 'standard';
		$qry_args['action']     = ! empty( $atts['action'] )     ? esc_attr__( $atts['action'] )           : 'like';
		$qry_args['show_faces'] = ! empty( $atts['show_faces'] ) ? esc_attr__( $atts['show_faces'] )       : 'true';
		$qry_args['share']      = ! empty( $atts['share'] )      ? esc_attr__( $atts['share'] )            : 'true';
		$qry_args['width']      = ! empty( $atts['width'] )      ? esc_attr__( $atts['width'] )            : null;
		$qry_args['height']     = ! empty( $atts['height'] )     ? esc_attr__( $atts['height'] )           : null;
		$qry_args['appId']      = $app_id;

		$fb_url = add_query_arg($qry_args, 'https://www.facebook.com/plugins/like.php');

		ob_start();
		?>
		<iframe
			src="<?php echo $fb_url; ?>"
			style="border:none;overflow:hidden"
			scrolling="no"
			frameborder="0"
			allowTransparency="true"

			<?php if ( ! empty( $qry_args['width'] ) ) : ?>
				height="<?php echo $qry_args['width']; ?>"
			<?php endif; ?>

			<?php if ( ! empty( $qry_args['width'] ) ) : ?>
				width="<?php echo $qry_args['width']; ?>"
			<?php endif; ?>

			<?php if ( ! empty( $class ) ) : ?>
				class="<?php echo $class; ?>"
			<?php endif; ?>
		></iframe>
		<?php

		return ob_get_clean();
	}
}

MM_Facebook_Buttons::init();