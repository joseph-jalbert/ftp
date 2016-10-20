<?php
namespace cwmdp;

class Enqueues {

	const NONCE = 'cwmdp-enqueues';

	public function init() {

		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	public function enqueue( $hook ) {
		if ( strpos( $hook, Screen::MENU_SLUG ) !== false ) {
			wp_enqueue_script( Screen::MENU_SLUG, plugin_dir_url( dirname( __FILE__ ) ) . 'js/cwmdp.js', array(
				'jquery'
			) );
			wp_localize_script( Screen::MENU_SLUG, 'cwmdp', array( 'security' => wp_create_nonce( self::NONCE ) ) );
		}
	}


}