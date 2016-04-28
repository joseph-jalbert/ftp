<?php

class Google_Review extends WP_Widget {

	protected static $google_api_key = 'AIzaSyAMGuUlmQWdi4LdIT7x4diTG_dvvBWfxTo';
	protected static $text_domain = 'google_review';
	protected static $ver = '0.1';

	protected static $place_search_url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=%s&key=%s';
	protected static $place_details_url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=%s&key=%s';

	
	/**
	 * Initialization method
	 */
	public static function init() {
		$api_key_settings = get_option('google_api_key');
		if ( ! empty( $api_key_settings['google_api_key'] ) ) :
			self::$google_api_key = $api_key_settings['google_api_key'];
		endif;

		add_action( 'widgets_init', create_function( '', 'register_widget( "Google_Review" );' ) );
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
	public function widget( $args, $instance )
	{
		$review = self::get_review();

		if ( ! empty( $review ) ) :
			wp_enqueue_style( 'google-review', get_template_directory_uri() . '/inc/widgets/google-review/google-review.css');

			echo $before_widget; ?>
			<div class="execphpwidget">
				<div class="widgetWrap aside row-leading">
					<div class="title"><span>Review</span></div>
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
									<?php for ( $i = 1; $i <= $review->rating; $i++ ) {
										echo '★ ';
									} ?>
								</span>

								<div property="schema:reviewRating" typeof="schema:Rating">
									<meta property="schema:worstRating" content="1">
									<span property="schema:ratingValue">
										<?php echo $review->rating; ?>/ <span property="schema:bestRating">5</span> stars
									</span>
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
		$instance                      = array();
		$instance['google-place-id']   = sanitize_text_field( $new_instance['google-place-id'] );
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
			'google-place-id'   => null
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="hubspot-form">
			<p>
				<label
					for="<?php echo $this->get_field_id( 'google-place-id' ); ?>"><?php _e( 'Place ID Override:', self::$text_domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'google-place-id' ); ?>"
				       name="<?php echo $this->get_field_name( 'google-place-id' ); ?>" type="text"
				       value="<?php echo $instance['google-place-id']; ?>"/>
			</p>
		</div>

		<?php
	}


	private static function get_review() {
		$place_id = '';
		$gr = new Google_Review();
		$settings = $gr->get_settings();
		$rreviews = array();

		if (!empty($settings[2])) :
			$place_id = $settings[2]['google-place-id'];
		endif;

		$office_address = self::get_office_address();
		if ( empty ( $office_address ) ) :
			return false;
		endif;

		$the_place = array();
		$address = urlencode('Morgan and Morgan ' . $office_address);
		$placesearchurl = sprintf(self::$place_search_url, $address, self::$google_api_key);

		if ( empty( $place_id ) ) :

			$data = wp_remote_get($placesearchurl, array('timeout' => 5));

			if ( is_wp_error( $data ) || empty( $data['body'] ) ) :
				return false;
			endif;

			$place_info = json_decode($data['body']);
			if (!empty($place_info->results)) :
				$place_id = $place_info->results[0]->place_id;
			endif;

			if (empty($place_id)) :
				return false;
			endif;
		endif;

		$placeurl = sprintf(self::$place_details_url, $place_id, self::$google_api_key);
		$place_data = wp_remote_get($placeurl, array( 'timeout' => 5 ) );

		if ( ! is_wp_error( $place_data ) && ! empty($place_data['body'] ) ) :
			$place_data_info = json_decode($place_data['body']);
			if ( ! empty( $place_data_info->result ) ) :
				$the_place = $place_data_info->result;
			endif;
		endif;

		if ( ! empty( $the_place ) ) :
			$reviews = ! empty( $the_place->reviews ) ? $the_place->reviews : false;

			if ( ! empty( $reviews ) ) :
				foreach ( $reviews as $review ) :
					if ( $review->rating >= 4 ) :
						$rreviews[] = $review;
					endif;
				endforeach;
			endif;
		endif;

		if ( ! empty( $rreviews ) ) :
			return $rreviews[array_rand($rreviews, 1)];
		else:
			return false;
		endif;
	}

	private static function get_office_address() {
		global $post;

		$office_info = array();
		$address = '';
		$parents = get_post_ancestors($post->ID);
		$id = ($parents) ? $parents[count($parents) - 1] : $post->ID;
		$parent = get_post($id);
		$parentslug = $parent->post_name;
		$officeinfo = new WP_Query('post_type=office&name=' . $parentslug);

		if ($officeinfo->have_posts()) {
			while ($officeinfo->have_posts()) {
				$officeinfo->the_post();

				$office_info['title'] = get_the_title();
				$office_info['state'] = get_field('state');
				$office_info['address'] = get_field('street_address');
				$office_info['suite'] = get_field('suite_information');
				$office_info['zipcode'] = get_field('zip_code');
				if ($locality = get_field('state_override')) {
					$office_info['state'] = esc_html($locality);
				}
			}

			$address = $office_info['address'] . ' ' . $office_info['suite'] . ', ' . $office_info['state'] . ' ' . $office_info['zipcode'];
		}
		wp_reset_postdata();

		return $address;
	}

}

Google_Review::init();