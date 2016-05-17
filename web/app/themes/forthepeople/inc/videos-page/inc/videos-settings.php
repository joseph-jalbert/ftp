<?php

class Videos_Settings {

	private static $youtube_key = 'youtube_key';
	private static $template_file = 'videos-page.php';
	private static $videos_option = 'videos_page';
	private static $videos_field = 'youtube_videos';


	public static function init() {

		self::attach_hooks();
		self::add_settings();

	}

	public static function attach_hooks() {

		add_action( 'acf/render_field', array( __CLASS__, 'add_markup' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );

	}


	public static function enqueue( $page ) {
		if ( is_page_template( self::$template_file ) ) {
			wp_enqueue_script( 'youtube-iframe', 'https://www.youtube.com/iframe_api' );
		}
	}

	public static function enqueue_admin( $page ) {

		if ( ! in_array( $page, array( 'post.php', 'edit.php' ) ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];
		if ( ! $post_id ) {
			return;
		}
		$template = get_post_meta( $post_id, '_wp_page_template' );
		if ( self::$template_file !== array_shift( $template ) ) {
			return;
		}

		wp_enqueue_script( 'youtube-importer', get_stylesheet_directory_uri() . '/inc/videos-page/js/script.js', array( 'jquery' ) );
		wp_enqueue_style( 'youtube-importer-css', get_stylesheet_directory_uri() . '/inc/videos-page/css/style.css' );

	}

	public static function add_markup( $field ) {
		if ( self::$youtube_key === $field['key'] ) {
			?>
			<div class="video-error"></div>
			<p class="button youtube-importer">Get YouTube Data</p><?php
		}

	}

	public static function add_settings() {
		if ( function_exists( 'acf_add_local_field_group' ) ):

			acf_add_local_field_group( array(
				'key'                   => 'group_5735fdce5dfbc',
				'title'                 => 'YouTube',
				'fields'                => array(
					array(
						'key'               => 'youtube_channel_link',
						'label'             => 'YouTube Channel Link',
						'name'              => 'youtube_channel_link',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'youtube_channel_link_title',
						'label'             => 'YouTube Channel Link Title',
						'name'              => 'youtube_channel_link_title',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_5735fdee2bd5b',
						'label'             => 'YouTube Videos',
						'name'              => self::$videos_field,
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'collapsed'         => '',
						'min'               => '',
						'max'               => '',
						'layout'            => 'block',
						'button_label'      => 'Add Video',
						'sub_fields'        => array(
							array(
								'key'               => self::$youtube_key,
								'label'             => 'YouTube ID',
								'name'              => 'youtube_id',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
								'readonly'          => 0,
								'disabled'          => 0,
							),
							array(
								'key'               => 'youtube_title',
								'label'             => 'Title',
								'name'              => 'title',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
								'readonly'          => 0,
								'disabled'          => 0,
							),
							array(
								'key'               => 'youtube_description',
								'label'             => 'Description',
								'name'              => 'description',
								'type'              => 'textarea',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'maxlength'         => '',
								'rows'              => '',
								'new_lines'         => 'wpautop',
								'readonly'          => 0,
								'disabled'          => 0,
							),
							array(
								'key'               => 'youtube_thumbnail',
								'label'             => 'Thumbnail',
								'name'              => 'thumbnail',
								'type'              => 'image',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
								'readonly'          => 0,
								'disabled'          => 0,
							),
							array(
								'key'               => 'upload_date',
								'label'             => 'Upload Date',
								'name'              => 'upload_date',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
								'readonly'          => 0,
								'disabled'          => 0,
							),
							array(
								'key'               => 'transcript',
								'label'             => 'Transcript',
								'name'              => 'transcript',
								'type'              => 'textarea',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
								'readonly'          => 0,
								'disabled'          => 0,
							),

						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => self::$template_file,
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
			) );

		endif;
	}

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

		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		$template = get_post_meta( $post_id, '_wp_page_template' );
		if ( self::$template_file !== $template ) {
			self::bust_cache();
		}


	}

	private static function bust_cache() {

		delete_option( self::$videos_option );
		self::get_videos( true );

	}

	public static function get( $field ) {
		return get_field( $field );
	}


	public static function get_videos( $force = false ) {

		$videos = get_option( self::$videos_option );

		if ( $force || ! $videos ) {

			$videos = get_field( self::$videos_field );
			update_option( self::$videos_option, $videos );

		}

		return $videos;
	}

}

Videos_Settings::init();