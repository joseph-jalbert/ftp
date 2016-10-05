<?php


class Banner_Image {

    private static $page_slug = 'banner-image';

    public static function init() {
        self::attach_hooks();
    }

    /**
     * Attach our hooks
     */
    public static function attach_hooks() {

        add_action( 'init', array( __CLASS__, 'add_menu_page' ) );
        add_action( 'init', array( __CLASS__, 'add_settings' ) );

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
                'page_title'  => 'Banner Image',
                'menu_title'  => 'Banner Image',
                'menu_slug'   => self::$page_slug,
                'capability'  => 'edit_posts',
                'parent_slug' => 'options-general.php'

            ) );
        }

    }

    public static function add_global_options() {
        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array (
                'key' => 'group_57ebf087aa2f9',
                'title' => 'Banner Image',
                'fields' => array (
                    array (
                        'key' => 'field_57ebf0967da5c',
                        'label' => 'Banner Image',
                        'name' => 'banner_image',
                        'type' => 'image',
                        'instructions' => 'Set custom banner image here. If nothing is set, header will fallback to default image. Must be a transparent .png, 565 X 137 EXACTLY',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => 565,
                        'min_height' => 137,
                        'min_size' => '',
                        'max_width' => 565,
                        'max_height' => 137,
                        'max_size' => '',
                        'mime_types' => '.png',
                    ),
                    array (
                        'key' => 'field_57ebfe817da5d',
                        'label' => 'top gradient',
                        'name' => 'top_gradient',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                    ),
                    array (
                        'key' => 'field_57ebfe9b7da5e',
                        'label' => 'bottom gradient',
                        'name' => 'bottom_gradient',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'banner-image',
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

}

Banner_Image::init();




