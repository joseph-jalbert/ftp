<?php


class Social_Sharing {

    private static $page_slug = 'social-sharing';

    public static function init() {
        self::attach_hooks();
    }

    /**
     * Attach our hooks
     */
    public static function attach_hooks() {

        add_action( 'init', array( __CLASS__, 'add_menu_page' ) );
        add_action( 'init', array( __CLASS__, 'add_settings' ) );

	    add_filter('acf/load_field/name=post_types', array( __CLASS__, 'load_post_type_choices' ) );

    }

    public static function load_post_type_choices( $field ) {
	    $field['choices'] = array();

	    $args = array(
		    'public'   => true,
		    '_builtin' => false
	    );

	    $choices = $post_types = get_post_types( $args, 'objects' );

	    // loop through array and add to field 'choices'
	    if( is_array($choices) ) {
		    foreach( $choices as $choice ) {
			    $field['choices'][ $choice->query_var ] = $choice->label;
		    }
	    }

	    return $field;
    }

    public static function add_settings() {
        self::add_global_options();
    }

    /**
     * Add menu page for Site Options
     */
    public static function add_menu_page() {
        if ( function_exists( 'acf_add_options_sub_page' ) ) {
            acf_add_options_sub_page( array(
                'page_title'  => 'Social Sharing',
                'menu_title'  => 'Social Sharing',
                'menu_slug'   => self::$page_slug,
                'capability'  => 'edit_posts',
                'parent_slug' => 'options-general.php'

            ) );
        }

    }

    public static function add_global_options() {
        if( function_exists('acf_add_local_field_group') ):
	        acf_add_local_field_group(array (
		        'key' => 'group_581a17a71dc01',
		        'title' => 'Social Share Settings',
		        'fields' => array (
			        array (
				        'key' => 'field_581a17b04f229',
				        'label' => 'Categories',
				        'name' => 'category_pages',
				        'type' => 'taxonomy',
				        'instructions' => 'Select which category pages you would like the sharing icons to be displayed on.',
				        'required' => 0,
				        'conditional_logic' => 0,
				        'wrapper' => array (
					        'width' => '',
					        'class' => '',
					        'id' => '',
				        ),
				        'taxonomy' => 'category',
				        'field_type' => 'checkbox',
				        'allow_null' => 0,
				        'add_term' => 0,
				        'save_terms' => 0,
				        'load_terms' => 0,
				        'return_format' => 'id',
				        'multiple' => 0,
			        ),
			        array (
				        'key' => 'field_581a26a34d868',
				        'label' => 'Post Types',
				        'name' => 'post_types',
				        'type' => 'select',
				        'instructions' => 'Select which post type pages you would like the sharing icons to be displayed on.',
				        'required' => 0,
				        'conditional_logic' => 0,
				        'wrapper' => array (
					        'width' => '',
					        'class' => '',
					        'id' => '',
				        ),
				        'choices' => array (
					        'featured_news' => 'Featured News',
					        'attorney' => 'Attorneys',
					        'office' => 'Offices',
					        'verdict' => 'Verdicts',
					        'multimedia' => 'Multimedia',
					        'testimonial' => 'Testimonials',
					        'kads' => 'KADS',
					        'btg_news' => 'BTG Blog',
					        'local_news' => 'Local News',
					        'classactionlawyers' => 'Class Action Cases ',
				        ),
				        'default_value' => array (
				        ),
				        'allow_null' => 0,
				        'multiple' => 1,
				        'ui' => 1,
				        'ajax' => 0,
				        'return_format' => 'value',
				        'placeholder' => '',
			        ),
			        array (
				        'key' => 'field_5825e5366a780',
				        'label' => 'Buttons To Show',
				        'name' => 'buttons_to_show',
				        'type' => 'checkbox',
				        'instructions' => '',
				        'required' => 0,
				        'conditional_logic' => 0,
				        'wrapper' => array (
					        'width' => '',
					        'class' => '',
					        'id' => '',
				        ),
				        'choices' => array (
					        'facebook' => 'Facebook',
					        'twitter' => 'Twitter',
					        'pinterest' => 'Pinterest',
					        'google' => 'Google+',
					        'email' => 'Email',
				        ),
				        'default_value' => array (
				        ),
				        'layout' => 'horizontal',
				        'toggle' => 1,
				        'return_format' => 'value',
			        ),
		        ),
		        'location' => array (
			        array (
				        array (
					        'param' => 'options_page',
					        'operator' => '==',
					        'value' => 'social-sharing',
				        ),
			        ),
		        ),
		        'menu_order' => 0,
		        'position' => 'normal',
		        'style' => 'default',
		        'label_placement' => 'top',
		        'instruction_placement' => 'label',
		        'hide_on_screen' => '',
		        'active' => 1,
		        'description' => '',
	        ));

	        acf_add_local_field_group(array (
		        'key' => 'group_581a2d4aa9011',
		        'title' => 'Social Sharing',
		        'fields' => array (
			        array (
				        'key' => 'field_581a2d50fc8a9',
				        'label' => 'Custom URL',
				        'name' => 'custom_url',
				        'type' => 'text',
				        'instructions' => 'Custom URL to override default sharing functionality.',
				        'required' => 0,
				        'conditional_logic' => 0,
				        'wrapper' => array (
					        'width' => '',
					        'class' => '',
					        'id' => '',
				        ),
				        'default_value' => '',
				        'placeholder' => '',
				        'prepend' => '',
				        'append' => '',
				        'maxlength' => '',
			        ),
			        array (
				        'key' => 'field_581a2ddefc8aa',
				        'label' => 'Custom Title',
				        'name' => 'custom_title',
				        'type' => 'text',
				        'instructions' => 'Custom title to override default sharing functionality.',
				        'required' => 0,
				        'conditional_logic' => 0,
				        'wrapper' => array (
					        'width' => '',
					        'class' => '',
					        'id' => '',
				        ),
				        'default_value' => '',
				        'placeholder' => '',
				        'prepend' => '',
				        'append' => '',
				        'maxlength' => '',
			        ),
		        ),
		        'location' => array (
			        array (
				        array (
					        'param' => 'post_type',
					        'operator' => '!=',
					        'value' => 'omapi',
				        ),
			        ),
			        array (
				        array (
					        'param' => 'post_type',
					        'operator' => '!=',
					        'value' => 'slack_integration',
				        ),
			        ),
		        ),
		        'menu_order' => 0,
		        'position' => 'normal',
		        'style' => 'default',
		        'label_placement' => 'top',
		        'instruction_placement' => 'label',
		        'hide_on_screen' => '',
		        'active' => 1,
		        'description' => '',
	        ));
        endif;
    }

    public static function check_page() {
	    the_post();

	    if ( ! is_singular() ) {
		    return false;
	    }

	    $post_type  = get_post_type();
	    $categories = get_field( 'category_pages', 'option' );
	    $post_types = get_field( 'post_types', 'option' );

	    if ( ! empty( $categories ) ) {
		    foreach( $categories as $cat ) {
			    if ( has_category( $cat ) ) {
				    return true;
			    }
		    }
	    }

	    if  ( ! empty( $post_types ) ) {
		    foreach( $post_types as $type ) {
			    if ( $type === $post_type ) {
				    return true;
			    }
		    }
	    }

	    return false;
    }

}

Social_Sharing::init();




