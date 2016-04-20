<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_delete-me',
		'title' => 'Local Social URLs',
		'fields' => array (
			array (
				'key' => 'field_571799fcc571d',
				'label' => 'Facebook Local URL',
				'name' => 'facebook_local_url',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_57179a2ec571e',
				'label' => 'Google Plus Local URL',
				'name' => 'google_plus_local_url',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'office-location.php',
					'order_no' => 0,
					'group_no' => 0,
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
