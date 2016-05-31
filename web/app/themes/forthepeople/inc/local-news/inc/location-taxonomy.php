<?php

class Location_Taxonomy {

	const LOCATION_TAXONOMY = 'office_location';
	const CATEGORY_TAXONOMY = 'location_category';
	const LOCATION_TERM_OPTION_NAME = 'office_location_locations';
	const LOCATION_POST_ID_OPTION_NAME = 'office_location_locations';
	const LOCATION_TAXONOMY_SLUG = 'location_taxonomy';
	const CATEGORY_TAXONOMY_SLUG = 'category';
	const POST_TYPE = Local_News::POST_TYPE;


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
		add_action( 'init', array( __CLASS__, 'add_rewrite_rules' ) );
		add_filter( 'term_link', array( __CLASS__, 'filter_term_link' ), 10, 3 );
		add_filter( 'query_vars', array( __CLASS__, 'local_blog_archive_query_var' ) );
		add_filter( 'request', array( __CLASS__, 'local_blog_archive_request' ), PHP_INT_MAX );
		add_action( self::LOCATION_TAXONOMY . "_edit_form", array( __CLASS__, 'render_fields_edit' ), 10, 2 );
		add_action( self::LOCATION_TAXONOMY . "_add_form_fields", array( __CLASS__, 'render_fields_new' ) );
		add_action( "edited_" . self::LOCATION_TAXONOMY, array( __CLASS__, 'save_fields' ), 10, 2 );
		add_action( "created_" . self::LOCATION_TAXONOMY, array( __CLASS__, 'save_fields' ), 10, 2 );
		add_action( 'admin_notices', array( __CLASS__, 'location_error_check' ) );

	}

	public static function filter_term_link( $url, $term, $taxonomy ) {

		if ( self::LOCATION_TAXONOMY === $taxonomy ) {

			$url = esc_url( home_url( $term->slug . '/blog/' ) );

		}

		return $url;

	}

	public static function register_taxonomies() {
		self::register_location_taxonomy();
		self::register_category_taxonomy();
	}

	public static function register_location_taxonomy() {
		$labels = array(
			'name'              => _x( 'Location', 'taxonomy general name' ),
			'singular_name'     => _x( 'Location', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Location' ),
			'all_items'         => __( 'All Location' ),
			'parent_item'       => __( 'Parent Location' ),
			'parent_item_colon' => __( 'Parent Location:' ),
			'edit_item'         => __( 'Edit Location' ),
			'update_item'       => __( 'Update Location' ),
			'add_new_item'      => __( 'Add New Location' ),
			'new_item_name'     => __( 'New Location Name' ),
			'menu_name'         => __( 'Location' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => self::LOCATION_TAXONOMY_SLUG,
				'with_front' => true
			),
		);

		register_taxonomy( self::LOCATION_TAXONOMY, array( self::POST_TYPE ), $args );
	}

	public static function register_category_taxonomy() {
		$labels = array(
			'name'              => _x( 'Local Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Local Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Local Category' ),
			'all_items'         => __( 'All Local Category' ),
			'parent_item'       => __( 'Parent Local Category' ),
			'parent_item_colon' => __( 'Parent Local Category:' ),
			'edit_item'         => __( 'Edit Local Category' ),
			'update_item'       => __( 'Update Local Category' ),
			'add_new_item'      => __( 'Add New Local Category' ),
			'new_item_name'     => __( 'New Local Category Name' ),
			'menu_name'         => __( 'Local Category' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => self::CATEGORY_TAXONOMY_SLUG,
				'with_front' => true
			),
		);

		register_taxonomy( self::CATEGORY_TAXONOMY, array( self::POST_TYPE ), $args );
	}


	public static function add_rewrite_rules() {

		add_rewrite_rule( '^([^\/]*)\/(blog)\/(?:feed\/)?(feed|rdf|rss|rss2|atom)\/?$', 'index.php?local_blog_archive=true&' . preg_quote( self::LOCATION_TAXONOMY ) . '=$matches[1]&feed=$matches[3]', 'top' );
		add_rewrite_rule( '^([^\/]*)\/(blog)\/page\/?([0-9]{1,})\/?$', 'index.php?local_blog_archive=true&' . preg_quote( self::LOCATION_TAXONOMY ) . '=$matches[1]&paged=$matches[3]', 'top' );
		add_rewrite_rule( '^([^\/]*)\/blog\/?$', 'index.php?local_blog_archive=true&' . preg_quote( self::LOCATION_TAXONOMY ) . '=$matches[1]', 'top' );
	}

	public function local_blog_archive_query_var( $public_query_vars ) {
		$public_query_vars[] = 'local_blog_archive';

		return $public_query_vars;
	}

	public function local_blog_archive_request( $query_vars ) {
		if ( isset( $query_vars['local_blog_archive'] ) ) {
			add_action( 'wp_head', function () {

				$template = '<meta name="robots" content="%s">';
				if ( have_posts() ) :
					$content = 'follow';
				else:
					$content = 'noindex, follow';
				endif;
				echo sprintf( $template, esc_attr( $content ) );


			} );
		}

		return $query_vars;
	}

	public static function render_fields_edit( $tag, $taxonomy ) {
		if ( ! self::term_meta_available() ) :
			self::render_term_meta_unavailable_notice();
			return;
		endif;

		$headline = get_term_meta( $tag->term_id, 'headline', true );
		$subheadline = get_term_meta( $tag->term_id, 'subheadline', true );
		$hubspot_form_id = get_term_meta( $tag->term_id, 'hubspot_form_id', true );
		$hubspot_portal_id = get_term_meta( $tag->term_id, 'hubspot_portal_id', true );
		$hubspot_target = get_term_meta( $tag->term_id, 'hubspot_target', true );

		?><table class="form-table">
		<tbody>
		<tr class="form-field term-headline-wrap">
			<th scope="row">
				<label for="headline">Headline</label>
			</th>
			<td>
				<input name="headline" id="headline" type="text" value="<?php echo esc_attr( $headline ); ?>" size="40">
			</td>
		</tr>
		<tr class="form-field term-subheadline-wrap">
			<th scope="row">
				<label for="subheadline">Subheadline</label>
			</th>
			<td>
				<input name="subheadline" id="subheadline" type="text" value="<?php echo esc_attr( $subheadline ); ?>" size="40">
			</td>
		</tr>
		<tr class="form-field term-hubspot-form-id-wrap">
			<th scope="row">
				<label for="hubspot-form-id">Hubspot Form ID</label>
			</th>
			<td>
				<input name="hubspot-form-id" id="hubspot-form-id" type="text" value="<?php echo esc_attr( $hubspot_form_id ); ?>" size="40">
			</td>
		</tr>
		<tr class="form-field term-hubspot-portal-id-wrap">
			<th scope="row">
				<label for="hubspot-portal-id">Hubspot Portal ID</label>
			</th>
			<td>
				<input name="hubspot-portal-id" id="hubspot-portal-id" type="text" value="<?php echo esc_attr( $hubspot_portal_id ); ?>" size="40">
			</td>
		</tr>
		<tr class="form-field term-hubspot-target-wrap">
			<th scope="row">
				<label for="hubspot-target">Hubspot Target</label>
			</th>
			<td>
				<input name="hubspot-target" id="hubspot-target" type="text" value="<?php echo esc_attr( $hubspot_target ); ?>" size="40">
			</td>
		</tr>
		</tbody>
		</table><?php
	}

	public static function render_fields_new() {
		if ( ! self::term_meta_available() ) :
			self::render_term_meta_unavailable_notice();
			return;
		endif;

		?>
		<div class="form-field term-headline-wrap">
			<label for="headline">Headline</label>
			<input name="headline" id="headline" type="text" size="40">
		</div>
		<div class="form-field term-subheadline-wrap">
			<label for="subheadline">Subheadline</label>
			<input name="subheadline" id="subheadline" type="text" size="40">
		</div>
		<div class="form-field term-hubspot-form-id-wrap">
			<label for="hubspot-form-id">Hubspot Form ID</label>
			<input name="hubspot-form-id" id="hubspot-form-id" type="text" size="40">
		</div>
		<div class="form-field term-hubspot-portal-id-wrap">
			<label for="hubspot-portal-id">Hubspot Portal ID</label>
			<input name="hubspot-portal-id" id="hubspot-portal-id" type="text" size="40">
		</div>
		<div class="form-field term-hubspot-target-wrap">
			<label for="hubspot-target">Hubspot Target</label>
			<input name="hubspot-target" id="hubspot-target" type="text" size="40">
		</div><?php
	}

	private static function render_term_meta_unavailable_notice() {
		echo '<h1><strong>Notice: Term Metadata Unavailbale in this version of WordPress</strong></h1>';
	}

	public static function save_fields( $term_id, $tt_id ) {
		update_term_meta( $term_id, 'headline', wp_kses_post( $_REQUEST['headline'] ) );
		update_term_meta( $term_id, 'subheadline', wp_kses_post( $_REQUEST['subheadline'] ) );
		update_term_meta( $term_id, 'hubspot_form_id', sanitize_text_field( $_REQUEST['hubspot-form-id'] ) );
		update_term_meta( $term_id, 'hubspot_portal_id', sanitize_text_field( $_REQUEST['hubspot-portal-id'] ) );
		update_term_meta( $term_id, 'hubspot_target', sanitize_text_field( $_REQUEST['hubspot-target'] ) );
	}

	public static function location_error_check() {
		$screen = get_current_screen();
		if ( 'edit-' . self::LOCATION_TAXONOMY === $screen->id && ! self::term_meta_available() ) {
			?>
			<div class="error notice">
				<p><?php _e( 'You must be using a version of WordPress after 4.4 to use location taxonomy meta.', 'my_plugin_textdomain' ); ?></p>
			</div>

			<?php
		}
	}

	private static function term_meta_available(){

		return function_exists( 'get_term_meta' );
	}

}

Location_Taxonomy::init();