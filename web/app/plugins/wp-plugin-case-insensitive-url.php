<?php 
/* 
* Plugin Name: Case Insensitive URL 
* Description: If the URI contains /pdf/, then redirect to the same request but lower case.
* Version: 0.01
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function case_insensitive_url() {


    if ( preg_match('/\.pdf$/i', $_SERVER['REQUEST_URI']) && preg_match('/[A-Z]/', $_SERVER['REQUEST_URI']) ) {

      $url = strtolower($_SERVER['REQUEST_URI']);		
			header( "Location: " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $url );
		
      die();
    }

}
add_action('init', 'case_insensitive_url');



