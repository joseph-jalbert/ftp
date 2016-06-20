<?php

class Selective_Disable_WPAutoP {

	public static function init() {
		self::add_settings();
	}

	public static function add_settings() {

		if ( function_exists( 'acf_add_local_field_group' ) ):

			acf_add_local_field_group( array(
				'key'                   => 'group_5761872c7e9d5',
				'title'                 => 'Automatic Paragraph Tags',
				'fields'                => array(
					array(
						'key'               => 'field_576187539091d',
						'label'             => 'Disable the automatic wrapping of paragraph tags',
						'name'              => 'remove_wpautop',
						'type'              => 'true_false',
						'instructions'      => 'This is only available for Pages that use the Default Template',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'message'           => '',
						'default_value'     => 0,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'default',
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


Selective_Disable_WPAutoP::init();