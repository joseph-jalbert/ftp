<?php

namespace cwmdp;

class AJAX {

	public function init() {

		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_action( 'wp_ajax_cwmdp_get_options', array( $this, 'get_options' ) );
		add_action( 'wp_ajax_cwmdp_clear_option', array( $this, 'clear_option' ) );
	}

	public function get_options() {

		$nonce_check = check_ajax_referer( Enqueues::NONCE, 'security', false );
		if ( ! $nonce_check ) {
			wp_send_json_error( array(
				'message' => 'Nonce Failure'
			) );
		}

		global $wpdb;

		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wpmdb_state_%'" );

		$plugin_options = array_map( function ( $option ) {
			return $option->option_name;
		}, $plugin_options );

		wp_send_json_success( array( 'options' => $plugin_options ) );


	}

	public function clear_option() {
		$nonce_check = check_ajax_referer( Enqueues::NONCE, 'security', false );
		if ( ! $nonce_check ) {
			wp_send_json_error( array(
				'message' => 'Nonce Failure'
			) );
		}
		$option = sanitize_text_field( $_POST['option'] );
		delete_option( $option );
		wp_send_json_success( array( 'option' => $option ) );

	}

}