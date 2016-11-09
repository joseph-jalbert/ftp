<?php
/*
Plugin Name: Add WP Migrate DB Pro Endpoints
Plugin URI: http://www.forthepeople.com/
Description: Add WP Migrate DB Pro endpoint
Version: 0.1.0
Author: Mat Gargano
Author URI: http://www.forthepeople.com/
License: GPL

*/


use awmdpe\endpoints;

$namespace = 'awmdpe';

spl_autoload_register( function ( $class ) use ( $namespace ) {
	$base = explode( '\\', $class );
	if ( $namespace === $base[0] ) {
		$file = __DIR__ . '/' . strtolower( str_replace( [ '\\', '_' ], [
					DIRECTORY_SEPARATOR,
					'-'
				], $class ) . '.php' );
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			die( sprintf( 'File %s not found', $file ) );
		}
	}

} );

$endpoints = new endpoints();
$endpoints->init();