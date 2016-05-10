<?php

if ( function_exists( "register_field_group" ) ) {

	register_field_group( array(
		'id'         => 'acf_search-filter',
		'title'      => 'Search Filter',
		'fields'     => array(
			array(
				'key'           => 'field_572b58ebc6eab',
				'label'         => 'Exclude',
				'name'          => 'exclude_from_search',
				'type'          => 'true_false',
				'message'       => "Exclude this from WordPress' internal search, this does not have anything to do with Google or other SERPs",
				'default_value' => 0,
			),
		),
		'location'   => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '!=',
					'value'    => 'omapi',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'page_type',
					'operator' => '!=',
					'value' => 'front_page',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options'    => array(
			'position'       => 'acf_after_title',
			'layout'         => 'no_box',
			'hide_on_screen' => array(),
		)
	) );

}
