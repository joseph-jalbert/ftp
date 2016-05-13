<?php

class Add_Arbitrary_Image {


	public static function get( $image_url, $post_id ) {
		$tmp = download_url( $image_url );
		if ( ! $post_id ) {
			$post_id = md5(time());
		}

		// Set variables for storage
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $image_url, $matches );
		$file_array['name']     = basename( $matches[0] );
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';
		}
		$file_parts                 = pathinfo( $file_array['tmp_name'] );
		$new_file_array['tmp_name'] = sprintf( '%s/%s.%s.%s', $file_parts['dirname'], basename( get_permalink( $post_id ) ), $post_id, $file_parts['extension'] );
		$file_parts                 = pathinfo( $file_array['name'] );
		$new_file_array['name']     = sprintf( '%s.%s', $post_id, $file_parts['extension'] );
		rename( $file_array['tmp_name'], $new_file_array['tmp_name'] );
		$file_array = $new_file_array;

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id );

		// If error storing permanently, unlink
		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );

			return $id;
		}
		return $id;

	}


}