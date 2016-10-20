<?php

namespace cwmdp;

class Screen {
	const MENU_SLUG = 'clean-wp-migrate-db-pro';

	public function init() {
		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );

	}

	function add_menu_item() {
		$page_title = 'Clean WP Migrate DB Pro';
		$menu_title = 'Clean WP Migrate DB Pro';
		$capability = 'manage_options';
		$menu_slug  = self::MENU_SLUG;
		$function   = array( $this, 'output_screen' );
		add_management_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	}


	function output_screen() {
		?>
		<div id="message" class="updated fade" style="display:none"></div>

		<div class="wrap charge-users">
			<h2><?php echo _x( 'Clean Out WP Migrate DB Pro', 'clean wp migrate db pro options screen', 'cwmdp' ); ?></h2>

			<span class="spinner"></span>
			<input class="button-primary" type="submit" id="clean-wp-migrate-db-pro"
			       value="<?php echo _x( wp_kses_post( 'Clean WP Migrate DB Pro' ), 'clean wp migrate db pro options screen', 'cwmdp' ); ?>">
			<div class="cwmdp-log"></div>
		</div>

		<?php
	}

}