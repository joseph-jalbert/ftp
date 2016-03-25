<?php

class Attorneys_Caching {

	const POST_TYPE = 'attorney';
	const TRANSIENT_NAME = 'attorney-transient';


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	/**
	 * @param $post_id
	 * @param $post
	 */
	public static function save_post( $post_id, $post ) {

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

		if ( self::POST_TYPE === get_post_type( $post ) ) {
			self::bust_cache();
		}

	}

	public static function bust_cache() {

		delete_transient( self::TRANSIENT_NAME );

	}

	public static function get_cache() {
		return get_transient( self::TRANSIENT_NAME );
	}

	public static function set_cache( $data ) {

		return set_transient( self::TRANSIENT_NAME, $data );

	}

}

Attorneys_Caching::init();