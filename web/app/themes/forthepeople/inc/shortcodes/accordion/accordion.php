<?php

class Accordion_Shortcode {

	private static $parent_unique_id;
	private static $counter = 0;


	public static function init() {

		self::attach_hooks();

	}

	public static function set_parent_unique_id( $id ) {
		self::$parent_unique_id = $id . self::$counter;
		self::$counter ++;
	}

	public static function get_parent_unique_id() {
		return self::$parent_unique_id;
	}

	public static function attach_hooks() {
		add_shortcode( 'accordion', array( __CLASS__, 'accordion' ) );
		add_shortcode( 'accordion_element', array( __CLASS__, 'accordion_element' ) );
	}

	public static function accordion_element( $atts, $content ) {
		$atts = shortcode_atts( array(
			'title' => '',

		), $atts );

		$individual_unique_id = 'prefix' . md5( $atts['title'] );
		$parent_unique_id     = self::get_parent_unique_id();


		ob_start();
		?>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="btn btn-accordion collapsed" rel="nofollow"
				   data-target="#<?php esc_attr_e( $individual_unique_id ); ?>"
				   data-parent="#<?php esc_attr_e( $parent_unique_id ); ?>" data-toggle="collapse">


				</a>
				<a class="accordion-toggle collapsed" rel="nofollow"
				   data-target="#<?php esc_attr_e( $individual_unique_id ); ?>"
				   data-parent="#<?php esc_attr_e( $parent_unique_id ); ?>"
				   data-toggle="collapse"><?php esc_html_e( $atts['title'] ); ?> </a>

			</div>


			<div id="<?php esc_attr_e( $individual_unique_id ); ?>" class="accordion-body collapse"
			     style="height: 0px;">
				<div class="accordion-inner">
					<?php echo do_shortcode( wpautop( wp_kses_post( $content ) ) ); ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function accordion( $atts, $content ) {

		self::set_parent_unique_id( 'prefix' . md5( microtime( true ) ) );

		ob_start();
		?>
		<div id="<?php esc_attr_e( self::get_parent_unique_id() ); ?>" class="widgetWrap accordian section">
			<?php echo do_shortcode( wp_kses_post( $content ) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

}

Accordion_Shortcode::init();
