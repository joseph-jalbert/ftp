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

        endif;
    }

    public static function check_page() {
	    the_post();

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
		    return true;
	    }

	    return false;
    }

}

Social_Sharing::init();




