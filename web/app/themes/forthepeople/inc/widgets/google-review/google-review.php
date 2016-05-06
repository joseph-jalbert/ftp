<?php

require_once __DIR__ . '/google-settings.php';
require_once __DIR__ . '/google-helper.php';

class Google_Review extends WP_Widget {

	protected static $text_domain = 'google_review';
	protected static $ver = '0.1';
	
	/**
	 * Initialization method
	 */
	public static function init() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "Google_Review" );' ) );
		add_action( 'delete_transient', array('Google_Helper', 'remove_transient' ), 10, 1 );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'Google_Review', // Base ID
			'Google Review', // Name
			array( 'description' => __( 'Google Review', self::$text_domain ), ) // Args
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
		global $post;

		$review = Google_Helper::get_review( $post->ID );
		$address = Google_Helper::get_office_address( $post->ID );

		if ( ! empty( $review ) ) :
			wp_enqueue_style( 'google-review', get_template_directory_uri() . '/inc/widgets/google-review/google-review.css' );

			echo $before_widget; ?>
			<div class="execphpwidget">
				<div class="widgetWrap aside row-leading">
					<div class="title">
						<span>Review</span>
					</div>
					<div class="body google-review">
						<div class="schema-block">
							<div class="schema-review" typeof="schema:Review">
								<div property="schema:author" typeof="schema:Person">
									<span class="author" property="schema:name"><?php echo $review->author_name; ?></span>
								</div>
								<div class="schema-review-body" property="schema:reviewBody">
									<?php echo $review->text; ?>
								</div>
								<span class="review-stars">
									<?php for ( $i = 1; $i <= $review->rating; $i ++ ) :
										echo '★ ';
									endfor; ?>
								</span>
								<div property="schema:reviewRating" typeof="schema:Rating">
									<meta property="schema:worstRating" content="1">
									<span property="schema:ratingValue">
										<?php echo $review->rating; ?>
										/ <span property="schema:bestRating">5</span> stars
									</span>
								</div>
								<div class="item-reviewed" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
									<span itemprop="name">Morgan and Morgan Office at <?php echo $address; ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="foot"></div>
				</div>
			</div>
			<?php echo $after_widget;
		endif;
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
		$instance                    = array();
		$instance['google-place-id'] = sanitize_text_field( $new_instance['google-place-id'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$defaults = array(
			'google-place-id' => null
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="google-form">
			<p>
				<label for="<?php echo $this->get_field_id( 'google-place-id' ); ?>"><?php _e( 'Place ID Override:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'google-place-id' ); ?>" name="<?php echo $this->get_field_name( 'google-place-id' ); ?>" type="text" value="<?php echo $instance['google-place-id']; ?>"/>
			</p>
		</div>
		<?php
	}
}

Google_Review::init();