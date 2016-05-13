<?php

/**
 * Upon saving of the post it sends a message to Facebook's OG scraping service to bust the cache for the permalink
 * Fixing any issues of an image not showing up when sharing on Facebook
 *
 * Class Bust_Facebook
 */
class Bust_Facebook {

	private static $post_types = array( 'post', Local_News::POST_TYPE );


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'save_post', array( __CLASS__, 'remove_from_facebook_cache' ), 10, 2 );
	}

	public function remove_from_facebook_cache( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! in_array( $post->post_type, self::$post_types ) ) {
			return;
		}

		$url      = get_permalink( $post_id );
		$post_url = 'https://graph.facebook.com/';
		$vars     = array(
			'id'     => $url,
			'scrape' => 'true'
		);

		wp_remote_post( $post_url, array(
			'body' => $vars
		) );


	}

}

Bust_Facebook::init();