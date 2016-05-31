<?php

class Hubspot_Form extends WP_Widget {

	protected static $text_domain = 'hubspot_form';
	protected static $ver = '0.1';

	/**
	 * Initialization method
	 */
	public static function init() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "Hubspot_Form" );' ) );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'Hubspot_Form', // Base ID
			'Hubspot Form', // Name
			array( 'description' => __( 'Hubspot Form', self::$text_domain ), ) // Args
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
		$hubspot_form_id   = esc_attr( $instance['hubspot-form-id'] );
		$hubspot_portal_id = esc_attr( $instance['hubspot-portal-id'] );
		$hubspot_target    = esc_attr( $instance['hubspot-target'] );
		$current_post      = get_queried_object();

		if ( is_a( $current_post, 'WP_Post' ) ) :
			$post_id = $current_post->ID;

			if ( get_field( 'hubspot_form_id', $post_id ) ) :
				$hubspot_form_id = get_field( 'hubspot_form_id', $post_id );
			endif;
			if ( get_field( 'hubspot_portal_id', $post_id ) ) :
				$hubspot_portal_id = get_field( 'hubspot_portal_id', $post_id );
			endif;
			if ( get_field( 'hubspot_target', $post_id ) ) :
				$hubspot_target = get_field( 'hubspot_target', $post_id );
			endif;
		elseif ( is_a( $current_post, 'WP_Term' ) ) :
			$term_id = $current_post->term_id;

			if ( ! empty( get_term_meta( $term_id, 'hubspot_form_id', true ) ) ) :
				$hubspot_form_id = get_term_meta( $term_id, 'hubspot_form_id', true );
			endif;
			if ( ! empty( get_term_meta( $term_id, 'hubspot_portal_id', true ) ) ) :
				$hubspot_portal_id = get_term_meta( $term_id, 'hubspot_portal_id', true );
			endif;
			if ( ! empty( get_term_meta( $term_id, 'hubspot_target', true ) ) ) :
				$hubspot_target = get_term_meta( $term_id, 'hubspot_target', true );
			endif;
		endif;

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
							target: '<?php echo esc_js( $hubspot_target );?>',
                            onFormReady: <?php echo forthepeople_render_hubspot_field_accessibility_callback();?>,
							onFormSubmit: <?php echo forthepeople_render_hubspot_text_filter_callback(); ?>
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
		$instance['hubspot-target'] = sanitize_text_field( $new_instance['hubspot-target'] );


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
			'hubspot-target' => null,

		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="hubspot-form">
			<p>
				<label
					for="<?php echo $this->get_field_id( 'hubspot-form-id' ); ?>"><?php _e( 'Default Hubspot Form ID:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hubspot-form-id' ); ?>"
				       name="<?php echo $this->get_field_name( 'hubspot-form-id' ); ?>" type="text"
				       value="<?php echo $instance['hubspot-form-id']; ?>"/>
			</p>

			<p>
				<label
					for="<?php echo $this->get_field_id( 'hubspot-portal-id' ); ?>"><?php _e( 'Default Hubspot Portal ID:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hubspot-portal-id' ); ?>"
				       name="<?php echo $this->get_field_name( 'hubspot-portal-id' ); ?>" type="text"
				       value="<?php echo $instance['hubspot-portal-id']; ?>"/>
			</p>
			<p>
				<label
					for="<?php echo $this->get_field_id( 'hubspot-target' ); ?>"><?php _e( 'Default Hubspot Target:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hubspot-target' ); ?>"
				       name="<?php echo $this->get_field_name( 'hubspot-target' ); ?>" type="text"
				       value="<?php echo $instance['hubspot-target']; ?>"/>
			</p>

		</div>

		<?php
	}


}

Hubspot_Form::init();