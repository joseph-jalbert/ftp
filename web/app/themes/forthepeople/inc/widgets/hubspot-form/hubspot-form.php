<?php

class Hubspot_Form extends WP_Widget {

	protected static $text_domain = 'hubspot_form';
	protected static $ver = '0.1';

	/**
	 * Initialization method
	 */
	public static function init() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "Super_recent_posts_widget" );' ) );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'super_recent_posts_widget', // Base ID
			'Super Recent Posts Widget', // Name
			array( 'description' => __( 'A prettier and more functional recent posts widget', self::$text_domain ), ) // Args
		);
	}


	/**
	 * Front-end display of widget.
	 *
	 * Filter 'srpw_template' - template allowing a theme to use its own template file
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$hubspot_form_id   = esc_attr( $instance['hubspot-form-id'] );
		$hubspot_portal_id = esc_attr( $instance['hubspot-portal-id'] );
		$current_post      = get_queried_object();
		$post_id           = $current_post ? $current_post->ID : null;

		if ( $post_id && get_field( 'hubspot_form_id', $post_id ) ) {
			$hubspot_form_id = get_field( 'hubspot_form_id', $post_id );
		}
		if ( $post_id && get_field( 'hubspot_portal_id', $post_id ) ) {
			$hubspot_portal_id = get_field( 'hubspot_portal_id', $post_id );
		}

		?>

		<?php echo $before_widget; ?>
		<div class="hs-wrapper">
			<div class="hs-form-title">Free Case Evaluation</div>
			<div class="hs-form-body">
				<div class="hs-form-description">Fill out this form for a FREE, Immediate, Case Evaluation</div>
				<div class="hp-hs-form">

					<script>
						hbspt.forms.create({
							portalId: '<?php echo esc_js( $hubspot_portal_id );?>',
							formId: '<?php echo esc_js( $hubspot_form_id );?>',
							target: '.cp-hs-form'
						});
					</script>
				</div>
			</div>
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
		$instance                      = array();
		$instance['hubspot-form-id']   = sanitize_text_field( $new_instance['hubspot-form-id'] );
		$instance['hubspot-portal-id'] = absint( $new_instance['hubspot-portal-id'] );


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
			'hubspot-form-id'   => null,
			'hubspot-portal-id' => null,

		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="srpw-form">
			<p>
				<label
					for="<?php echo $this->get_field_id( 'hubspot-form-id' ); ?>"><?php _e( 'Hubspot Form ID:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hubspot-form-id' ); ?>"
				       name="<?php echo $this->get_field_name( 'hubspot-form-id' ); ?>" type="text"
				       value="<?php echo $instance['hubspot-form-id']; ?>"/>
			</p>

			<p>
				<label
					for="<?php echo $this->get_field_id( 'hubspot-portal-id' ); ?>"><?php _e( 'Hubspot Portal ID:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hubspot-portal-id' ); ?>"
				       name="<?php echo $this->get_field_name( 'hubspot-portal-id' ); ?>" type="text"
				       value="<?php echo $instance['hubspot-portal-id']; ?>"/>
			</p>

		</div>

		<?php
	}


}