<?php

class Local_Social_Helper {

	private static $slug = 'office-location.php';


	/**
	 * Will return true if we're on a local page or a direct child of the local page
	 * Will return false otherwise
	 * @return bool|int
	 */
	public static function is_local() {

		global $post;
		if ( ! $post ) {
			return false;
		}
		$parent                 = $post->post_parent;
		$post_to_check_template = $post->ID;
		if ( $parent > 0 ) {
			$post_to_check_template = $post->post_parent;
		}

		if ( self::$slug === get_page_template_slug( $post_to_check_template ) ) {
			return $post_to_check_template;
		}


		return false;


	}

}