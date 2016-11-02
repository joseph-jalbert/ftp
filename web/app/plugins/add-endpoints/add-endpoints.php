<?php
/*
Plugin Name: Add Endpoints
Plugin URI: http://www.forthepeople.com/
Description: Add endpoints
Version: 1.0
Author: Mat Gargano
Author URI: http://www.forthepeople.com/
License: GPL

*/



add_action( 'jwt_auth_expire', function ( $time ) {
	return time() + ( DAY_IN_SECONDS * 365 );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'myplugin/v1', '/author', array(
		'methods'             => 'GET',
		'callback'            => 'my_awesome_func',
		'permission_callback' => function () {
			return current_user_can( 'edit_others_posts' );
		}
	) );
} );

function my_awesome_func() {
	$settings  = get_site_option( 'wpmdb_settings' );

	return array( 'key' => sprintf( "%s\r%s", site_url( '', 'https' ), $settings['key'] ) );
}