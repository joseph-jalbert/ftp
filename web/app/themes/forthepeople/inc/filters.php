<?php

class Filters {


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_filter('user_can_richedit' , '__return_false' , 50);
	}


}

Filters::init();