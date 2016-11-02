<?php

class Custom_Evaluation_Page_Template {

	public static function init() {
		self::register_settings();
	}

	public static function register_settings() {
		if ( function_exists( 'acf_add_local_field_group' ) ):

			acf_add_local_field_group( array(
				'key'                   => 'group_5817ab8ad364d',
				'title'                 => 'Custom Evaluation Page Template',
				'fields'                => array(
					array(
						'key'               => 'field_5817abb999c6c',
						'label'             => 'Video URL',
						'name'              => 'video_url',
						'type'              => 'url',
						'instructions'      => 'must be from youtube embed url like this: https://www.youtube.com/embed/jpt-J_JHBKs?feature=oembed',
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
					array(
						'key'               => 'field_5fadsfabb999c6c',
						'label'             => 'Custom Hubspot Form ID',
						'name'              => 'custom_hubspot_form_id',
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
					),

					array(
						'key'               => 'field_5fadsfabb999c6c',
						'label'             => 'Gray Box Title',
						'name'              => 'gray_box_title',
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
					),

					array(
						'key'               => 'field_5fafasdfbb999c6c',
						'label'             => 'Gray Box Content',
						'name'              => 'gray_box_content',
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
					),

					array(
						'key'               => 'field_5fadfasdfaaebb999c6c',
						'label'             => 'Testimonials Title',
						'name'              => 'testimonials_title',
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
					),

					array(
						'key'               => 'field_5faddadbb999c6c',
						'label'             => 'Verdicts and Settlements Title',
						'name'              => 'verdicts_and_settlements_title',
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
					),

					array(
						'key'               => 'field_5817fadsf99c70',
						'label'             => 'Verdicts & Settlements',
						'name'              => 'verdicts_settlements',
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
						'button_label'      => 'Add V&S',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5adaad3fasfc2a99c71',
								'label'             => 'Verdict Text',
								'name'              => 'verdict_text',
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
							),
							array(
								'key'               => 'field_5817ac2a99c71',
								'label'             => 'Litigation Type',
								'name'              => 'litigation_type',
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
							),
							array(
								'key'               => 'field_5817ac3299c72',
								'label'             => 'Litigation Value',
								'name'              => 'litigation_value',
								'type'              => 'text',
								'instructions'      => 'include the $, e.g. $90,200,000',
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
							),
							array(
								'key'               => 'field_5817ac4399c73',
								'label'             => 'Text',
								'name'              => 'text',
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
							),
						),
					),
					array(
						'key'               => 'field_5817abc999c6d',
						'label'             => 'Client Testimonials',
						'name'              => 'client_testimonials',
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
						'button_label'      => 'Add Testimonial',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5817abe099c6e',
								'label'             => 'Testimonial',
								'name'              => 'testimonial',
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
							),
							array(
								'key'               => 'field_5817abef99c6f',
								'label'             => 'Quote Source',
								'name'              => 'quote_source',
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
							),

						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'custom-evaluation-page-template.php',
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

Custom_Evaluation_Page_Template::init();