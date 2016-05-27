<?php

class Contact_Page_Video {


	public static function init() {

		self::settings();

	}

	public static function settings() {
		if ( function_exists( 'acf_add_local_field_group' ) ):

			acf_add_local_field_group( array(
				'key'                   => 'group_5747112e90efd',
				'title'                 => 'Contact Page Video',
				'fields'                => array(
					array(
						'key'               => 'field_57471139d0067',
						'label'             => 'Contact Page Video',
						'name'              => 'contact_page_video',
						'type'              => 'url',
						'instructions'      => 'YouTube URL (e.g. https://www.youtube.com/watch?v=jpt-J_JHBKs)',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'contact-page.php',
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


}

Contact_Page_Video::init();
