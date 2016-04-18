<?php

class Super_Editor {

	private static $role_name = 'super-editor';
	private static $role_display_name = 'Super Editor';
	private static $clone_role = 'editor';


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_action( 'admin_init', array( __CLASS__, 'create_new_role' ) );


	}

	public static function create_new_role() {

		if ( get_role( self::$role_name ) ) {
			return;
		}
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		$clone_role = $wp_roles->get_role( self::$clone_role );
		$new_role   = $wp_roles->add_role( self::$role_name, self::$role_display_name, $clone_role->capabilities );
		if ( ! $new_role->has_cap( 'edit_theme_options' ) ) {
			$new_role->add_cap( 'edit_theme_options' );
		}


	}

}

Super_Editor::init();