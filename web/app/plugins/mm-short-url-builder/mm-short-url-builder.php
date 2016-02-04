<?php
/**
 * Plugin Name: Morgan & Morgan Short URL Builder
 * Description: Builds Tracking URLs and shortens them
 * Version:     1.2
 * Author:      Morgan & Morgan (credit: Eric Savadian)
 * Author URI:  https://github.com/Morgan-and-Morgan
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once __DIR__ . '/inc/mmshorturl.php';
MMShortURL::init();