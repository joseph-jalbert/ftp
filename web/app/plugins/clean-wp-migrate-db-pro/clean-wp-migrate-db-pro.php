<?php
/*
Plugin Name: Clean up WP Migrate DB Pro options
Plugin URI: http://www.forthepeople.com/
Description: Removes extra options added to the database and not removed
Version: 0.1.0
Author: Mat Gargano
Author URI: http://www.forthepeople.com/
License: GPL
*/


use cwmdp\AJAX;
use cwmdp\Enqueues;
use cwmdp\Screen;

spl_autoload_register( function ( $class ) {
	$base = explode( '\\', $class );
	if ( 'cwmdp' === $base[0] ) {
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

$cwmdp = new Screen();
$cwmdp->init();
$enqueues = new Enqueues();
$enqueues->init();
$ajax = new AJAX();
$ajax->init();