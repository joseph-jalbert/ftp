<?php

class Videos_Page {

	const TRANSIENT_NAME = 'videos-page';
	const POST_TYPE = 'multimedia';
	private static $ids;
	private static $transient_time = DAY_IN_SECONDS;

	public static function init( Array $ids = array() ) {

		self::$ids = $ids;
		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	public static function bust_cache() {

		return delete_transient( self::TRANSIENT_NAME );

	}

	public static function get_cache() {

		return get_transient( self::TRANSIENT_NAME );

	}

	public static function set_cache( $output ) {
		set_transient( self::TRANSIENT_NAME, $output, self::$transient_time );
	}

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

		if ( $post->post_type === self::POST_TYPE || in_array( $post_id, self::$ids ) ) {

			self::bust_cache();

		}


	}

}

Videos_Page::init( array( 10579 ) );