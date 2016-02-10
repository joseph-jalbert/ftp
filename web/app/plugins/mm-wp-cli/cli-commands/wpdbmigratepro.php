<?php

if ( ! class_exists( 'WP_CLI_Command' ) ):
	return;
endif;

class MM_WP_CLI extends WP_CLI_Command {

	/**
	 * Sets blacklists
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp example set_blacklists
	 *
	 * @synopsis
	 */

	public function set_blacklists() {
		$turn_off_plugins = array();
		$option_name      = 'wpmdb_settings';
		foreach ( get_plugins() as $key => $plugin ) {
			if ( 0 === strpos( $key, 'wp-migrate-db' ) ) {
				continue;
			}
			if ( $key ) {
				$turn_off_plugins[] = $key;
			}

		}
		$settings                      = get_site_option( $option_name );
		$settings['blacklist_plugins'] = (array) $turn_off_plugins;
		update_site_option( $option_name, $settings );
		WP_CLI::success('Great success!' . implode( ', ', $turn_off_plugins ) );


	}


	/**
	 * Adds compatibility plugin
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp example add_compatibility_plugin
	 *
	 * @synopsis
	 */
	public function add_compatibility_plugin(){

		$mu_dir = ( defined( 'WPMU_PLUGIN_DIR' ) && defined( 'WPMU_PLUGIN_URL' ) ) ? WPMU_PLUGIN_DIR : trailingslashit( WP_CONTENT_DIR ) . 'mu-plugins';
		$source = trailingslashit( WP_PLUGIN_DIR ) . 'wp-migrate-db-pro/compatibility/wp-migrate-db-pro-compatibility.php';
		$dest   = trailingslashit( $mu_dir ) . 'wp-migrate-db-pro-compatibility.php';
		if ( ! wp_mkdir_p( $mu_dir ) ) {
			WP_CLI::error(esc_html__( 'The following directory could not be created: %s', 'wp-migrate-db' ), $mu_dir );
			exit;
		}

		if ( ! copy( $source, $dest ) ) {
			WP_CLI::error( sprintf( 'Could not copy the compatibility plugin from %1$s to %2$s', 'wp-migrate-db' , $source, $dest ) );
			exit;
		}
		WP_CLI::success('Great success!');
	}


}

WP_CLI::add_command( 'mm', 'MM_WP_CLI' );