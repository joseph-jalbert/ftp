<?php

/**
 * Class Menu_Override
 * @package forthepeople
 */

/**
 * Class Menu Selection
 */
class Menu_Override {

	const NONCE = 'menu_override';
	const META_FIELD_MENU = 'select-menu';
	private static $current_menu = null;
	/**
	 * Post types to register this menu selector to.
	 * @var string post type.
	 */
	static private $post_types;


	/**
	 * Initialize
	 */
	public static function init() {
		self::$post_types = array( 'post', 'page' );
		self::attach_hooks();

	}

	/**
	 * Attach WordPress hooks
	 */
	public static function attach_hooks() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'metabox' ) );
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );

	}

	/**
	 * Add metabox for menu options
	 *
	 * @param string $post_type Post Type.
	 */
	public static function metabox( $post_type ) {
		if ( in_array( $post_type, self::$post_types ) ) {
			add_meta_box( 'menu_data', 'Menu Options', array(
				__CLASS__,
				'menu_metabox',
			), $post_type, 'side', 'core' );
		}
	}

	/**
	 * Output Metabox
	 *
	 * @param WP_Post $post Post Object.
	 */
	public static function menu_metabox( $post ) {
		wp_nonce_field( self::NONCE, self::NONCE );
		self::$current_menu = get_post_meta( $post->ID, self::META_FIELD_MENU, true );
		?>
		<div class="inside">
		<p><strong><?php esc_html_e( 'Override default menu for this page:', 'forthepeople' ); ?></strong></p>
		<label class="screen-reader-text" for="<?php esc_attr_e( self::META_FIELD_MENU ); ?>">
			<?php esc_html_e( 'Override default menu for this page:', 'forthepeople' ); ?>
		</label> <?php

		self::output_options();


		?></div><?php

	}

	/**
	 * Hook on save post
	 *
	 * @param integer $post_id post id.
	 * @param WP_Post $post Post objet.
	 */
	public static function save_post( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}
		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! in_array( $post->post_type, self::$post_types ) ) {
			return;
		}
		if ( ! isset( $_POST[ self::NONCE ] ) ||  // Input var ok.
		     ! self::check_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE ] ) ) ) // Input var ok.
		) {
			return;
		}
		$menu = isset( $_POST[ self::META_FIELD_MENU ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::META_FIELD_MENU ] ) ) : null;
		if ( ! $menu ) {

			delete_post_meta( $post_id, self::META_FIELD_MENU );
		} else {
			update_post_meta( $post_id, self::META_FIELD_MENU, $menu );
		}


	}


	/**
	 * Check the nonce
	 *
	 * @param string $nonce Nonce to check.
	 *
	 * @return bool
	 */
	public static function check_nonce( $nonce ) {
		if ( ! wp_verify_nonce( $nonce, self::NONCE ) ) {
			die( 'Security check!' );
		}

		return true;
	}

	private static function output_options() {


		$nav_menus = wp_get_nav_menus();
		?>

	<select name="<?php esc_attr_e( self::META_FIELD_MENU ); ?>"
	        id="<?php esc_attr_e( self::META_FIELD_MENU ); ?>">
		<option value="">Default menu</option>
		<?php foreach ( (array) $nav_menus as $_nav_menu ) : ?>
			<option
				value="<?php echo esc_attr( $_nav_menu->slug ); ?>" <?php selected( $_nav_menu->slug, self::$current_menu ); ?>>
				<?php
				echo esc_html( $_nav_menu->name );

				if ( ! empty( $menu_locations ) && in_array( $_nav_menu->term_id, $menu_locations ) ) {
					$locations_assigned_to_this_menu = array();
					foreach ( array_keys( $menu_locations, $_nav_menu->term_id ) as $menu_location_key ) {
						if ( isset( $locations[ $menu_location_key ] ) ) {
							$locations_assigned_to_this_menu[] = $locations[ $menu_location_key ];
						}
					}

					/**
					 * Filter the number of locations listed per menu in the drop-down select.
					 *
					 * @since 3.6.0
					 *
					 * @param int $locations Number of menu locations to list. Default 3.
					 */
					$assigned_locations = array_slice( $locations_assigned_to_this_menu, 0, absint( apply_filters( 'wp_nav_locations_listed_per_menu', 3 ) ) );

					// Adds ellipses following the number of locations defined in $assigned_locations.
					if ( ! empty( $assigned_locations ) ) {
						printf( ' (%1$s%2$s)',
							implode( ', ', $assigned_locations ),
							count( $locations_assigned_to_this_menu ) > count( $assigned_locations ) ? ' &hellip;' : ''
						);
					}
				}
				?>
			</option>
		<?php endforeach; ?>
		</select><?php

	}
}

Menu_Override::init();


