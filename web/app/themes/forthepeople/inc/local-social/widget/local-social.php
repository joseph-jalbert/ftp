<?php

class Local_Social_Widget extends WP_Widget {

	protected static $text_domain = 'local_social';
	protected static $ver = '0.1';

	/**
	 * Initialization method
	 */
	public static function init() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "' . get_class() . '" );' ) );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'local_social_widget', // Base ID
			'Local Social', // Name
			array( 'description' => __( 'Will output a visit us social widget if on a local page or a child of a local page. Local page is defined as a page using the `Office Location Main` or office-location.php  template', self::$text_domain ), ) // Args
		);
	}


	/**
	 * Front-end display of widget.
	 *
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$post_id = Local_Social_Helper::is_local();

		if ( ! $post_id ) { // if we're not on a local page, let's exit
			return;
		}

		$facebook_default    = esc_attr( $instance['facebook-default'] );
		$google_plus_default = esc_attr( $instance['google-plus-default'] );

		if ( $post_id && get_field( 'facebook_local_url', $post_id ) ) {
			$facebook_default = get_field( 'facebook_local_url', $post_id );
		}
		if ( $post_id && get_field( 'google_plus_local_url', $post_id ) ) {
			$google_plus_default = get_field( 'google_plus_local_url', $post_id );
		}

		if ( ! $google_plus_default && ! $facebook_default ) {
			return;
		}


		?>

		<?php echo $before_widget; ?>
		<div class="widgetWrap aside local-social-widget">
			<div class="title text-center"><span>Visit Us</span></div>
			<div class="body">

				<div class="wrapper">
					<ul class="social-icons icon-zoom icon-circle list-unstyled list-inline">
						<?php if ( $facebook_default ) : ?>
							<li><a target="_BLANK" href="<?php echo esc_url( $facebook_default ); ?>"><i class="fa fa-facebook"></i></a>
							</li>
						<?php endif; ?>
						<?php if ( $google_plus_default ) : ?>
							<li><a target="_BLANK" href="<?php echo esc_url( $google_plus_default ); ?>"><i
										class="fa fa-google-plus"></i></a></li>
						<?php endif; ?>

					</ul>


				</div>


			</div>
			<div class="foot"></div>
		</div>

		<?php echo $after_widget; ?>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                        = array();
		$instance['facebook-default']    = esc_url_raw( $new_instance['facebook-default'] );
		$instance['google-plus-default'] = esc_url_raw( $new_instance['google-plus-default'] );


		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$defaults = array(
			'facebook-default'    => null,
			'google-plus-default' => null,


		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="hubspot-form">
			<p>
				<label
					for="<?php echo $this->get_field_id( 'facebook-default' ); ?>"><?php _e( 'Default Facebook URL:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'facebook-default' ); ?>"
				       name="<?php echo $this->get_field_name( 'facebook-default' ); ?>" type="text"
				       value="<?php echo $instance['facebook-default']; ?>"/>
			</p>

			<p>
				<label
					for="<?php echo $this->get_field_id( 'google-plus-default' ); ?>"><?php _e( 'Default Google Plus URL:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'google-plus-default' ); ?>"
				       name="<?php echo $this->get_field_name( 'google-plus-default' ); ?>" type="text"
				       value="<?php echo $instance['google-plus-default']; ?>"/>
			</p>


		</div>

		<?php
	}


}

Local_Social_Widget::init();