<?php

class Related_Content extends WP_Widget {

	protected static $text_domain = 'related_content';
	protected static $ver = '0.1';
	protected static $transient_key = 'ftp-related-posts'; // Will stick the post id at the end
	protected static $save_for_post_types = array( 'local_news' );

	/**
	 * Initialization method
	 */
	public static function init() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "related_content" );' ) );
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'related_content',
			'Related Content',
			array( 'description' => __( 'Related Content', self::$text_domain ), )
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
		$current_post = get_queried_object();
		$post_id      = $current_post ? $current_post->ID : null;

		if ( ! $post_id ) {
			return;
		}

		$related_posts = self::get_related_posts( $post_id );
		if ( empty( $related_posts ) ) :
			return;
		endif;

		echo $before_widget; ?>
		<div class="execphpwidget">
			<div class="widgetWrap aside row-leading">
				<div class="title">
					<span>Related Content</span>
				</div>
				<div class="body related-content">
					<ul class="related-content"><?php
						if ( $related_posts->have_posts() ) :
							while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
								<li>
								<p class="title"><?php the_title(); ?></p>
								<p class="content"><?php echo rtrim( substr( get_the_content( null, true ), 0, 150 ) ); ?>
									...<br/><a href="<?php the_permalink(); ?>" alt="<?php echo the_title(); ?>">Read More</a>
								</p>
								</li><?php
							endwhile;
						endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php echo $after_widget;
	}

	/**
	 * Get related content based on location_category custom taxonomy
	 *
	 * @param $post_id
	 *
	 * @return bool|mixed|WP_Query
	 */
	public static function get_related_posts( $post_id ) {
		$related_posts = get_transient( self::$transient_key );

		/**
		 * See if we have this element in the array.
		 * If so, return it.
		 */
		if ( !empty ( $related_posts[$post_id] ) ) :
			return $related_posts[$post_id];
		endif;

		$local_category = get_the_terms( $post_id, 'location_category' );
		if ( ! empty( $local_category ) ) :
			$categories = array();
			foreach ( $local_category as $category ) :
				if ( 'location_category' === $category->taxonomy ) :
					$categories[] = array(
						'taxonomy' => 'location_category',
						'field'    => 'slug',
						'terms'    => $category->slug
					);
				endif;
			endforeach;

			$args = array(
				'posts_per_page' => 3,
				'orderby'        => 'rand',
				'post_type'      => 'local_news',
				'post_status'    => 'publish',
				'post__not_in'   => array( $post_id ),
				'tax_query'      => $categories
			);

			$posts = new WP_Query( $args );
			if ( empty ( $posts ) ) :
				return false;
			endif;

			/**
			 * Save element to global array
			 */
			$related_posts[$post_id] = $posts;
			set_transient( self::$transient_key, $related_posts );

			return $related_posts[$post_id];
		endif;

		return false;
	}

	/**
	 * Delete the transient for the related content
	 *
	 * @param $post_id
	 * @param $post
	 *
	 * @return bool
	 */
	public function save_post( $post_id, $post ) {

		if ( ! in_array( $post->post_type, self::$save_for_post_types ) ) :
			return false;
		endif;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
			return false;
		endif;

		if ( 'auto-draft' === $post->post_status ) :
			return false;
		endif;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) :
			return false;
		endif;

		if ( ! current_user_can( 'edit_post', $post->ID ) ) :
			return false;
		endif;

		return delete_transient( self::$transient_key );
	}
}

Related_Content::init();