<?php

class Related_Content extends WP_Widget {

	protected static $text_domain = 'related_content';
	protected static $ver = '0.1';

	/**
	 * Initialization method
	 */
	public static function init() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "related_content" );' ) );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'related_content', // Base ID
			'Related Content', // Name
			array( 'description' => __( 'Related Content', self::$text_domain ), ) // Args
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
		$post_id = $current_post ? $current_post->ID : null;
		$related_posts = array();

		$local_category = get_the_terms( $post_id, 'location_category' );
		if ( ! empty( $local_category ) ) :
			$categories = array();
			foreach ( $local_category as $category ) :
				if ( 'location_category' === $category->taxonomy ) :
					$categories[] = array(
						'taxonomy' => 'location_category',
						'field' => 'slug',
						'terms' => $category->slug
					);
				endif;
			endforeach;

			$args = array(
				'posts_per_page' => 3,
				'orderby' => 'rand',
				'post_type' => 'local_news',
				'post_status' => 'publish',
				'post__not_in' => array($post_id),
				'tax_query' => $categories
			);

			//$related_posts = get_posts( $args );
			$related_posts = new WP_Query( $args );
			if ( empty ( $related_posts ) ) :
				return false;
			endif;
		endif;

		?>

		<?php echo $before_widget; ?>
			<div class="execphpwidget">
				<div class="widgetWrap aside row-leading">
					<div class="title"><span>Related Content</span></div>
					<div class="body related-content">
						<ul class="related-content"><?php
						if ($related_posts->have_posts()) :
							while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
								<li>
									<p class="title"><?php the_title(); ?></p>
									<p class="content"><?php echo rtrim( substr( get_the_content( null, true ), 0, 150 ) ); ?>...<br/><a href="<?php the_permalink(); ?>" alt="<?php echo the_title(); ?>">Read More</a></p>
								</li><?php
							endwhile;
						endif; ?>
						</ul>
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
		$instance = array();
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
		<div class="related-content-form"></div>
		<?php
	}
}

Related_Content::init();