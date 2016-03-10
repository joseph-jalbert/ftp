<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_override-hubspot-form-code',
		'title' => 'Override Hubspot Form Fields (if you do not set these, they will fall back to their defaults)',
		'fields' => array (
			array (
				'key' => 'field_56dfgaa92c58b',
				'label' => 'Hubspot Form ID',
				'name' => 'hubspot_form_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'none',
			),
			array (
				'key' => 'field_56df0fa9dda58b',
				'label' => 'Hubspot Portal ID',
				'name' => 'hubspot_portal_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'none',
			),
			array (
				'key' => 'field_iididiiiadb',
				'label' => 'Hubspot Target',
				'name' => 'hubspot_target',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'none',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'contact-page.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'open-class-actions.php',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
