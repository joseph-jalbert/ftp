<?php
/*
Plugin Name: Add Slashes to Links
Plugin URI: http://www.forthepeople.com/
Description: using output buffering add slashes to the end of links anywhere on the page
Version: 1.0
Author: Mat Gargano
Author URI: http://www.forthepeople.com/
License: GPL

*/

function mm_add_slashes_to_links($page){

	return preg_replace( '#\bhref="(/[^"]+)(?<!/)(?<!(\.\w{3}))"#', 'href="$1/"', $page );


}

ob_start('mm_add_slashes_to_links');
