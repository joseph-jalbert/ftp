<?php
/**
 * ForThePeople functions and definitions
 *
 * @package ForThePeople
 */

require __DIR__ . '/inc/business-trial-group/business-trial-group.php';
require __DIR__ . '/inc/local-news/local-news.php';
require __DIR__ . '/inc/hubspot-settings/hubspot-settings.php';
require __DIR__ . '/inc/robots.txt.php';
require __DIR__ . '/inc/actions.php';
require __DIR__ . '/inc/widgets/widgets.php';
require __DIR__ . '/inc/videos-page/videos-page.php';
require __DIR__ . '/inc/attorneys-caching/attorneys-caching.php';
require __DIR__ . '/inc/filters.php';
require __DIR__ . '/inc/roles/roles.php';

if ( ! function_exists( 'forthepeople_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function forthepeople_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ForThePeople, use a find and replace
	 * to change 'forthepeople' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'forthepeople', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'forthepeople' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'forthepeople_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // forthepeople_setup
add_action( 'after_setup_theme', 'forthepeople_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function forthepeople_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'forthepeople_content_width', 640 );
}
add_action( 'after_setup_theme', 'forthepeople_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function forthepeople_widgets_init() {

	register_sidebar(

		array(
			'name' => esc_html__('Footer Contact Widget', 'forthepeople'),
			'id'   => 'footer_contact_widget',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="cf widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>',
		)
	);

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Contact Form', 'forthepeople' ),
		'id'            => 'sidebar_contact_form',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="cf widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Contact Form', 'forthepeople' ),
		'id'            => 'contact_form',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="form-wrapper widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Attorney Profile Form', 'forthepeople' ),
		'id'            => 'attorney_profile_form',
		'description'   => '',
		'before_widget' => '<div class="attorneyform-wrapper widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	) );


}
add_action( 'widgets_init', 'forthepeople_widgets_init' );

/**
 * Enqueue styles
 */
function forthepeople_styles() {
  if (is_page_template('landing-page.php')) {
    return;
  }
  
  wp_enqueue_style( 'forthepeople-style', get_stylesheet_uri() );

  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css');

  wp_enqueue_style( 'borrowed', get_template_directory_uri() . '/assets/css/borrowed.css', array(), '20150413');
  
  wp_enqueue_style( 'custom', get_template_directory_uri() . '/assets/css/custom.css');

  wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

  if (is_page_template('business-litigation.php') || is_page_template('attorney-referrals.php')) {
      wp_enqueue_style( 'siteinsite', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.css');
      wp_enqueue_style( 'siteinsitebtg', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.btg.css');
  }
  
  if (is_page_template('securities-litigation.php')) {
      wp_enqueue_style( 'siteinsite', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.css');
      wp_enqueue_style( 'siteinsitesecurities', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.securities.css');
  }

  if (is_page_template('whistleblower-qui-tam.php')) {
      wp_enqueue_style( 'siteinsite', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.css');
      wp_enqueue_style( 'whistleblower', get_template_directory_uri() . '/assets/media/siteinsite/css/practice.whistleblower.css');
  }
  
  if (is_page_template('mesothelioma.php')) {
      wp_enqueue_style( 'siteinsite', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.css');
      wp_enqueue_style( 'meso', get_template_directory_uri() . '/assets/media/siteinsite/css/practice.meso.css');
  }
  
  if (is_page_template('tampa-alternate.php')) {
      wp_enqueue_style( 'siteinsite', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.css');
      wp_enqueue_style( 'siteinsitetampa', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.tampa.css');
  }
  
  if (is_page_template('empty-page.php')) {
      wp_enqueue_style( 'siteinsite', get_template_directory_uri() . '/assets/media/siteinsite/css/siteinsite.css');
  }
  
  if (is_singular('attorney')) {
    wp_enqueue_style( 'journalfont', get_template_directory_uri() . '/assets/fonts/journal/font.css');
  }
  
  if (is_page_template('videos-page.php') || is_page_template('contact-page.php') || is_page_template('homepage.php') || is_page('casey-anthony-case')) {
    wp_enqueue_style( 'videojscss', get_template_directory_uri() . '/assets/plugins/videos/assets/css/video-js.css');
  }

  if (is_page_template('diabetes-page.php')) {
    wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/media/interactive/diabetes/css/main.css');
    wp_enqueue_style( 'bxslider', get_template_directory_uri() . '/assets/media/interactive/diabetes/css/jquery.bxslider.css');
    wp_enqueue_style( 'circliful', get_template_directory_uri() . '/assets/media/interactive/diabetes/css/jquery.circliful.css');
  }
}
	add_action( 'wp_enqueue_scripts', 'forthepeople_styles' );
/**
 * Enqueue scripts
 */
function forthepeople_scripts() {
  if (is_page_template('landing-page.php')) {
    return;
  }

	wp_enqueue_script( 'forthepeople-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'forthepeople-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
		
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

  wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array('jquery'), '', true );

  wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/libs/modernizr.2.6.2.min.js', false, '', true);

  wp_enqueue_script( 'slides', get_template_directory_uri() . '/assets/js/jquery.slides.js', false, '', true);

  wp_enqueue_script( 'sorting', get_template_directory_uri() . '/assets/js/plugins/jquery.sortAllTheThings.min.js', false, '', true);

  wp_enqueue_script( 'scotchpanels', get_template_directory_uri() . '/assets/js/plugins/scotchPanels.min.js', false, '', true);

  wp_enqueue_script( 'global', get_template_directory_uri() . '/assets/js/scripts/global.js', false, '', true);

	if (is_singular(array('post', 'classactionlawyers', 'local_news', BTG_News::POST_TYPE))) {
    		wp_enqueue_script( 'sharethis', '//w.sharethis.com/button/buttons.js', false, '', true);
	}	
	
	if (is_page_template('business-litigation.php') || is_page_template('securities-litigation.php')) {
    	wp_enqueue_script( 'stellar', get_template_directory_uri() . '/assets/js/plugins/jquery.stellar.min.js', false, '', true);
    	wp_enqueue_script( 'scrollreveal', get_template_directory_uri() . '/assets/js/plugins/scrollReveal.min.js', false, '', true);
	}
	
	if (is_page_template('videos-page.php') || is_page_template('contact-page.php') || is_page_template('homepage.php') || is_page('casey-anthony-case')) {
    	wp_enqueue_script( 'videojs', get_template_directory_uri() . '/assets/plugins/videos/assets/js/video.js', false, '', true);
    	wp_enqueue_script( 'videoplaylist', get_template_directory_uri() . '/assets/plugins/videos/assets/js/videoplaylist.js', false, '', true);
	}
	
	if (is_page_template('diabetes-page.php')) {
    	wp_enqueue_script( 'bxslider', get_template_directory_uri() . '/assets/media/interactive/diabetes/js/jquery.bxslider.min.js', false, '', true);
    	wp_enqueue_script( 'circliful', get_template_directory_uri() . '/assets/media/interactive/diabetes/js/jquery.circliful.min.js', false, '', true);
    	wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/media/interactive/diabetes/js/main.js', false, '', true);
	}
	
	if (is_single('matt-morgan')) {
    	wp_enqueue_script( 'twittertimeline', get_template_directory_uri() . '/assets/js/plugins/twitter.timeline.js', false, '', true);
	}
	
	if (is_singular('attorney')) {
    	wp_enqueue_script( 'badges', '//www.avvo.com/assets/badges-v2.js', false, '', true);

	}
	wp_localize_script( 'global', 'attorneyData', array(
		'attorneyEmail' => get_field( 'email' )
	) );

}
add_action( 'wp_enqueue_scripts', 'forthepeople_scripts' );

function wpmice_scripts() {
  if (!is_page_template('landing-page.php')) { 
    
//    wp_enqueue_style( 'wpmice-style', get_stylesheet_uri() );
 
    wp_enqueue_script( 'wpmice-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

    wp_enqueue_script( 'wpmice-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
 
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
  }
}
add_action( 'wp_enqueue_scripts', 'wpmice_scripts' );


/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

class My_Sub_Menu extends Walker_Nav_Menu {

	
  function start_lvl(&$output, $depth = 0, $args = Array()) {

      $indent = str_repeat("\t", $depth);

      $output .= "\n$indent<ul class=\"dropdown-menu transition\">\n";

  }

  
  function start_el ( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

    global $wp_query, $wpdb;

    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    $class_names = $value = '';
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    $classes[] = 'menu-item-' . $item->ID;
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

    $class_names_comp = ' class="' . esc_attr( $class_names ) . '"';

	

    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );

    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
    $has_children = $wpdb->get_var("SELECT COUNT(meta_id)

                            FROM wp_postmeta

                            WHERE meta_key='_menu_item_menu_item_parent'

                            AND meta_value='".$item->ID."'");

							

    if ( $depth == 0 && $has_children > 0  ) {

    $class_names_comp = ' class="dropdown ' . esc_attr( $class_names ) . '"';

    }
    $output .= $indent . '<li' . $id . $value . $class_names_comp .'>';
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';

    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';

    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';

    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    if ( $depth == 0 && $has_children > 0  ) {


		$classes[] = 'has_children';

    }
    $item_output = $args->before;

    $item_output .= '<a'. $attributes .'>';

    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    if ( $depth == 0 && $has_children > 0  ) {

        $item_output .= ' <div class="dd"></div>';

    }

	

    $item_output .= '</a>';

    $item_output .= $args->after;

	

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

  }
}
// START GREG FUNCTIONS

function mm_ftp_setup() {
	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );	
	//set_post_thumbnail_size( $width, $height, $crop ); //false - Soft proportional crop mode; true - Hard crop mode.
	
	//Enable support for Post Formats.
	add_theme_support( 'post-formats', array(
		'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'
	) );
	//Enable the use of HTML5 markup for the comment forms, search forms, comment lists, galleries, and captions.
	add_theme_support( 'html5', array( 
		'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' 
	) );
	// Enable excerpts for pages, posts, and custom post types.
	add_post_type_support( 'page', 'excerpt' );
	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus( array(
		'primary'   => 'Primary menu',
		'footer' => 'Footer menu',
	) );

}
add_action( 'after_setup_theme', 'mm_ftp_setup' );

/**
 * Register MM_FTP widget areas, custom post types, and taxonomies.
 *
 */

function mm_ftp_widgets_init() {
	
	
	
	/** Custom Post Types **/

	function my_labels_for_custom_post_type($singular, $plural) {
		$labels = array(
	    	'name' => $plural,
	    	'singular_name' => $singular,
	    	'search_items' =>  'Search '.$plural,
	    	'all_items' => 'All '.$plural,
	    	'parent_item' => 'Parent '.$singular.' Page',
	    	'parent_item_colon' => 'Parent '.$singular.' Page:',
	    	'edit_item' => 'Edit '.$singular, 
	    	'update_item' => 'Update '.$singular,
	    	'add_new_item' => 'Add New '.$singular,
	    	'new_item_name' => 'New '.$singular.' Page',
	    	'menu_name' => $plural,
		);

		return $labels;
	}
	
	register_post_type('featured_news', array(
		'labels' => my_labels_for_custom_post_type('Featured News', 'Featured News'),
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'menu_position' => 20,
	    'capability_type' => 'page',
	    'hierarchical' => true,
	    'query_var' => true,
	    'rewrite' => array(
	    	'slug' => 'featured',
			'with_front' => false
	    ),
	    'has_archive' => false, 
	    'supports' => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt'
		)
	));

	
	register_post_type('attorney', array(
		'labels' => my_labels_for_custom_post_type('Attorney', 'Attorneys'),
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'menu_position' => 20,
	    'capability_type' => 'page',
	    'hierarchical' => true,
	    'query_var' => true,
	    'rewrite' => array(
	    	'slug' => 'attorneys',
			'with_front' => false
	    ),
	    'has_archive' => false, 
	    'supports' => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
		)
	));

	register_post_type('office', array(
		'labels' => my_labels_for_custom_post_type('Office', 'Offices'),
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'menu_position' => 20,
	    'capability_type' => 'page',
	    'hierarchical' => false,
	    'query_var' => true,
	    'rewrite' => array(
	    	'slug' => 'office',
			'with_front' => false
	    ),
	    'has_archive' => 'office_locations', 
	    'supports' => array(
			'title',
			'editor',
			'author',
		)
	));
	
		register_post_type('verdict', array(
		'labels' => my_labels_for_custom_post_type('Verdict', 'Verdicts'),
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'menu_position' => 20,
	    'capability_type' => 'page',
	    'hierarchical' => true,
	    'query_var' => true,
	    'rewrite' => array(
	    	'slug' => 'verdict',
			'with_front' => false
	    ),
	    'has_archive' => false,
	    'supports' => array(
			'title',
			'editor',
			'author',
		)
	));


	
	register_post_type('multimedia', array(
		'labels' => my_labels_for_custom_post_type('File', 'Multimedia'),
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'menu_position' => 20,
	    'capability_type' => 'page',
	    'hierarchical' => false,
	    'query_var' => true,
	    'rewrite' => array(
	    	'slug' => 'media-library',
			'with_front' => false
	    ),
	    'has_archive' => false, 
	    'supports' => array(
			'title',
			'editor',
			'author',
		)
	));
	
	register_post_type('testimonial', array(
		'labels' => my_labels_for_custom_post_type('Testimonial', 'Testimonials'),
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'menu_position' => 20,
	    'capability_type' => 'page',
	    'hierarchical' => false,
	    'query_var' => true,
	    'rewrite' => array(
	    	'slug' => 'testimonials',
			'with_front' => false
	    ),
	    'has_archive' => false,
	    'supports' => array(
			'title',
			'editor',
			'excerpt',
		)
	));


	/** Taxonomies **/

	//Generate an array of standardized labels used when registering a custom taxonomy.
	
	function my_labels_for_custom_taxonomy($singular, $plural) {
		$labels = array(
	    'name' => $plural,
	    'singular_name' => $singular,
	    'search_items' =>  'Search '.$plural,
	    'popular_items' => 'Popular '.$plural,
	    'all_items' => 'All '.$plural,
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => 'Edit '.$singular, 
	    'update_item' => 'Update '.$singular,
	    'add_new_item' => 'Add New '.$singular,
	    'new_item_name' => 'New '.$singular.' Name',
	    'separate_items_with_commas' => 'Separate '.$plural.' with commas',
	    'add_or_remove_items' => 'Add or Remove '.$plural,
	    'choose_from_most_used' => 'Choose from the Most Used '.$plural,
	    'menu_name' => $plural,
		);

		return $labels;
	}

	// Register taxonomies.

	register_taxonomy('practice_area', array('attorney','multimedia','verdict','testimonial'), array(
		'labels' => my_labels_for_custom_taxonomy('Practice Area', 'Practice Areas'),
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'practice-area',
			'with_front' => true,
			'hierarchical' => false
		)
	) );
	
	register_taxonomy('related_attorney', array('verdict','multimedia','testimonial'), array(
		'labels' => my_labels_for_custom_taxonomy('Related Attorney', 'Related Attorneys'),
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'attorney',
			'with_front' => true,
			'hierarchical' => false
		)
	) );
	
	register_taxonomy('location', array('attorney', 'office','verdict','testimonial'), array(
		'labels' => my_labels_for_custom_taxonomy('Location', 'Related Office Locations'),
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'location',
			'with_front' => true,
			'hierarchical' => false
		)
	) );
	
	register_taxonomy('media_type', array('multimedia'), array(
		'labels' => my_labels_for_custom_taxonomy('Media Type', 'Media Types'),
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'multimedia',
			'with_front' => true,
			'hierarchical' => false
		)
	) );
	
	register_taxonomy('video_playlist', array('multimedia'), array(
		'labels' => my_labels_for_custom_taxonomy('Video Playlist', 'Video Playlists'),
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'video_playlist',
			'with_front' => true,
			'hierarchical' => false
		)
	) );
	
	register_taxonomy('page_type', array('page'), array(
		'labels' => my_labels_for_custom_taxonomy('Page Type', 'Page Types'),
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'page-type',
			'with_front' => true,
			'hierarchical' => false
		)
	) );
}

add_action( 'widgets_init', 'mm_ftp_widgets_init' );
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

//Add a custom css to back-end 

function custom_back_end_css() {
	wp_enqueue_style( 'custom_back_end_css', get_template_directory_uri() . '/back-end.css', __FILE__ );
}
add_action( 'admin_head', 'custom_back_end_css' );
/**
* Custom Meta Boxes
*/
function custom_meta_boxes() {
	// post_types where you want specific custom meta boxes to appear
	$personal_info_screens = array( 'attorney' ); 
	$verdict_screens = array( 'verdict' );
	$media_screens = array( 'multimedia' );
	$preview_screens = array( 'multimedia' );
	$office_screens = array( 'office' );
	$testimonial_screens = array( 'testimonial' );
	$args = array();


	foreach ( $personal_info_screens as $screen ) {
		add_meta_box( 
			'profile_info', 
			'Additional Information', 
			'show_profile_custom_meta_box', 
			$screen, 
			'normal', 
			'high', 
			$args
		);
	}
	
	foreach ( $verdict_screens as $screen ) {
		add_meta_box( 
			'verdict_details', 
			'Verdict Details', 
			'show_verdict_custom_meta_box', 
			$screen, 
			'normal', 
			'high', 
			$args
		);
	}
	
	foreach ( $media_screens as $screen ) {
		add_meta_box( 
			'media_file_details', 
			'Media File Details', 
			'show_media_custom_meta_box', 
			$screen, 
			'normal', 
			'high', 
			$args
		);
	}
	
	foreach ( $preview_screens as $screen ) {
		add_meta_box( 
			'preview_and_file_info', 
			'Preview and File Information', 
			'show_preview_and_file_info_custom_meta_box', 
			'normal', 
			'high', 
			$args
		);
	}
	
	foreach ( $office_screens as $screen ) {
		add_meta_box( 
			'office_location_details', 
			'Office Location Details', 
			'show_office_location_custom_meta_box', 
			$screen, 
			'normal', 
			'high', 
			$args
		);
	}
	
	foreach ( $testimonial_screens as $screen ) {
		add_meta_box( 
			'testimonial_details', 
			'Testimonial Details', 
			'show_testimonial_custom_meta_box', 
			$screen, 
			'normal', 
			'high', 
			$args
		);
	}
}
add_action( 'add_meta_boxes', 'custom_meta_boxes' );


function show_profile_custom_meta_box( $post ) {
	wp_nonce_field( 'profile_info','profile_info_nonce' );

	$job_title = get_post_meta( $post->ID, '_job_title', true );
	$phone_number = get_post_meta( $post->ID, '_phone_number', true );
	$fax = get_post_meta( $post->ID, '_fax', true );
	$email_address = get_post_meta( $post->ID, '_email_address', true );
	$fb_profile = get_post_meta( $post->ID, '_fb_profile', true );
	$twitter_account = get_post_meta( $post->ID, '_twitter_account', true );
	$gplus_account = get_post_meta( $post->ID, '_gplus_account', true );
	$linkedin_account = get_post_meta( $post->ID, '_linkedin_account', true );

	echo '<label class="be-label" for="job_title"><strong>Title</strong> (Partner, Associate, etc…)</label>';
	echo '<input class="be-input" type="text" id="job_title" name="job_title" value="' . esc_attr( $job_title ) . '" />';
	echo '<label class="be-label" for="email_address"><strong>Email Address</strong> (Publicly displayed email)</label>';
	echo '<input class="be-input" type="text" id="email_address" name="email_address" value="' . esc_attr( $email_address ) . '" />';
	echo '<label class="be-label" for="phone_number"><strong>Phone</strong> (10 digits, numbers only. For extension, use <strong>Ext.</strong> following by the extension number)</label>';
	echo '<input class="be-input" type="text" id="phone_number" name="phone_number" value="' . esc_attr( $phone_number ) . '" />';
	echo '<label class="be-label" for="fax"><strong>Fax</strong> (Personal or Firm Number)</label>';
	echo '<input class="be-input" type="text" id="fax" name="fax" value="' . esc_attr( $fax ) . '" />';
	echo '<label class="be-label" for="fb_profile"><strong>Facebook Profile</strong></label>';
	echo '<input class="be-input" type="text" id="phone_number" name="phone_number" value="' . esc_attr( $fax ) . '" />';
	echo '<label class="be-label" for="twitter_account"><strong>Twitter Account</strong></label>';
	echo '<input class="be-input" type="text" id="twitter_account" name="twitter_account" value="' . esc_attr( $twitter_account ) . '" />';
	echo '<label class="be-label" for="gplus_account"><strong>Google + Account</strong></label>';
	echo '<input class="be-input" type="text" id="gplus_account" name="gplus_account" value="' . esc_attr( $gplus_account ) . '" />';
	echo '<label class="be-label" for="linkedin_account"><strong>LinkedIn Account</strong></label>';
	echo '<input class="be-input" type="text" id="linkedin_account" name="linkedin_account" value="' . esc_attr( $linkedin_account ) . '" />';
	
}


function show_verdict_custom_meta_box( $post ) {
	wp_nonce_field( 'verdict_details','verdict_details_nonce' );
	
	$ruling_type = get_post_meta( $post->ID, '_ruling_type', true );
	$presuit_offer = get_post_meta( $post->ID, '_presuit_offer', true );
	$practice_area_text = get_post_meta( $post->ID, '_practice_area_text', true );
	$type_of_damages = get_post_meta( $post->ID, '_type_of_damages', true );
	$state = get_post_meta( $post->ID, '_state', true );
	$venue = get_post_meta( $post->ID, '_venue', true );
	$case_style = get_post_meta( $post->ID, '_case_style', true );
	$date = get_post_meta( $post->ID, '_date', true );
	
	echo '<label class="be-label" for="ruling_type"><strong>Ruling Type</strong> (Verdict or Settlement)</label>';
	echo '<select class="be-select" name="ruling_type">
				<option value="verdict">Verdict</option>
				<option value="settlement">Settlement</option>
		</select>';
	echo '<label class="be-label" for="presuit_offer"><strong>Presuit Offer</strong> (Valid numbers only)</label>';
	echo '<input class="be-input" type="text" id="presuit_offer" name="presuit_offer" value="' . esc_attr( $presuit_offer ) . '" />';
	echo '<label class="be-label" for="practice_area_text"><strong>Practice Area Text</strong></label>';
	echo '<input class="be-input" type="text" id="practice_area_text" name="practice_area_text" value="' . esc_attr( $practice_area_text ) . '" />';
	echo '<label class="be-label" for="type_of_damages"><strong>Type of Damages</strong> (Injury, property, wages, etc.)</label>';
	echo '<input class="be-input" type="text" id="type_of_damages" name="type_of_damages" value="' . esc_attr( $type_of_damages ) . '" />';
	echo '<label class="be-label" for="state"><strong>State</strong></label>';
	echo '<input class="be-input" type="text" id="state" name="state" value="' . esc_attr( $state ) . '" />';
	echo '<label class="be-label" for="venue"><strong>Venue</strong> (Location or Court Type)</label>';
	echo '<input class="be-input" type="text" id="venue" name="venue" value="' . esc_attr( $venue ) . '" />';
	echo '<label class="be-label" for="case_style"><strong>Case Style</strong></label>';
	echo '<input class="be-input" type="text" id="case_style" name="case_style" value="' . esc_attr( $case_style ) . '" />';
	echo '<label class="be-label" for="verdict_date"><strong>Date</strong> (mm/dd/yyyy or leave blank)</label>';
	echo '<input class="be-input" type="text" id="verdict_date" name="verdict_date" value="' . esc_attr( $date ) . '" />';
}

function show_media_custom_meta_box( $post ) {
	wp_nonce_field( 'media_file_details','media_file_details_nonce' );
	
	$transcript = get_post_meta( $post->ID, '_file_url_path', true );
	
	echo '<label class="be-label" for="transcript"><strong>Transcript</strong></label>';
	echo '<textarea class="be-textarea" rows="5" type="text" id="transcript" name="transcript">' . esc_attr( $transcript ) . '</textarea>';
}

function show_preview_and_file_info_custom_meta_box( $post ) {
	wp_nonce_field( 'preview_and_file_info','preview_and_file_info_nonce' );
	
	$file_url_path = get_post_meta( $post->ID, '_file_url_path', true );
	
	echo '<label class="be-label" for="file_url_path"><strong>File URL Path</strong></label>';
	echo '<input class="be-input" type="text" id="file_url_path" name="file_url_path" value="' . esc_attr( $file_url_path ) . '" />';
	echo '<div class="media-placeholder"></div>';
}

function show_office_location_custom_meta_box( $post ) {
	wp_nonce_field( 'office_location_details','office_location_details_nonce' );

	$short_description = get_post_meta( $post->ID, '_short_description', true );
	$street_address = get_post_meta( $post->ID, '_street_address', true );
	$suite_information = get_post_meta( $post->ID, '_suite_information', true );
	$state = get_post_meta( $post->ID, '_state', true );
	$zip_code = get_post_meta( $post->ID, '_zip_code', true );
	$telephone = get_post_meta( $post->ID, '_telephone', true );
	$map_link = get_post_meta( $post->ID, '_map_link', true );
	$latitude = get_post_meta( $post->ID, '_latitude', true );
	$longitude = get_post_meta( $post->ID, '_longitude', true );
	
	echo '<p class="alert-msg">All fields are required except for the Suite Information and Short Description. <a href="http://itouchmap.com/latlong.html" target="_blank">Click here</a> for a website that can be used to get the latitude and longitude.</p>';
	
	echo '<label class="be-label" for="short_description"><strong>Short Description</strong></label>';
	echo '<textarea class="be-textarea" type="text" id="short_description" name="short_description">' . esc_attr( $short_description ) . '</textarea>';
	echo '<label class="be-label" for="street_address"><strong>Street Address</strong></label>';
	echo '<input class="be-input" type="text" id="street_address" name="street_address" value="' . esc_attr( $street_address ) . '" />';
	echo '<label class="be-label" for="suite_information"><strong>Suite Information</strong></label>';
	echo '<input class="be-input" type="text" id="suite_information" name="suite_information" value="' . esc_attr( $suite_information ) . '" />';
	echo '<label class="be-label" for="state"><strong>State</strong></label>';
	echo '<input class="be-input" type="text" id="state" name="state" value="' . esc_attr( $state ) . '" />';
	echo '<label class="be-label" for="zip_code"><strong>Zip Code</strong></label>';
	echo '<input class="be-input" type="text" id="zip_code" name="zip_code" value="' . esc_attr( $zip_code ) . '" />';
	echo '<label class="be-label" for="telephone"><strong>Telephone</strong></label>';
	echo '<input class="be-input" type="text" id="telephone" name="telephone" value="' . esc_attr( $telephone ) . '" />';
	echo '<label class="be-label" for="map_link"><strong>Map/Directions URL</strong> (Google Maps Link)</label>';
	echo '<input class="be-input" type="text" id="map_link" name="map_link" value="' . esc_attr( $map_link ) . '" />';
	echo '<label class="be-label" for="latitude"><strong>Latitude</strong></label>';
	echo '<input class="be-input" type="text" id="latitude" name="latitude" value="' . esc_attr( $latitude ) . '" />';
	echo '<label class="be-label" for="longituden"><strong>Longitude</strong></label>';
	echo '<input class="be-input" type="text" id="longitude" name="longitude" value="' . esc_attr( $longitude ) . '" />';
}

function show_testimonial_custom_meta_box( $post ) {
	wp_nonce_field( 'testimonial_details','testimonial_details_nonce' );
	
	$city = get_post_meta( $post->ID, '_city', true );
	$state = get_post_meta( $post->ID, '_state', true );
	$date = get_post_meta( $post->ID, '_date', true );

	echo '<label class="be-label" for="city"><strong>City</strong></label>';
	echo '<input class="be-input" type="text" id="city" name="city" value="' . esc_attr( $city ) . '" />';	
	echo '<label class="be-label" for="state"><strong>State</strong></label>';
	echo '<input class="be-input" type="text" id="state" name="state" value="' . esc_attr( $state ) . '" />';
	echo '<label class="be-label" for="date"><strong>Date</strong></label>';
	echo '<input class="be-input" type="date" id="date" name="date" value="' . esc_attr( $date ) . '" />';
	
}


//Custom meta boxes for attorney pages.

function save_profile_custom_meta_box_data( $post_id ) {

	//Verify this came from our screen and with proper authorization

	if ( ! isset( $_POST['profile_info_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['profile_info_nonce'], 'profile_info' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( ! isset( $_POST['job_title'] ) ) {
		return;
	}
	if ( ! isset( $_POST['email_address'] ) ) {
		return;
	}
	if ( ! isset( $_POST['phone_number'] ) ) {
		return;
	}
	if ( ! isset( $_POST['fb_profile'] ) ) {
		return;
	}
	if ( ! isset( $_POST['twitter_account'] ) ) {
		return;
	}
	if ( ! isset( $_POST['gplus_account'] ) ) {
		return;
	}
	if ( ! isset( $_POST['linkedin_account'] ) ) {
		return;
	}	

	// Sanitize user input.
	$job_title_data = sanitize_text_field( $_POST['job_title'] );
	$email_address_data = sanitize_text_field( $_POST['email_address'] );
	$phone_number_data = sanitize_text_field( $_POST['phone_number'] );
	$fb_profile_data = sanitize_text_field( $_POST['fb_profile'] );
	$twitter_account_data = sanitize_text_field( $_POST['twitter_account'] );
	$gplus_account_data = sanitize_text_field( $_POST['gplus_account'] );
	$linkedin_account_data = sanitize_text_field( $_POST['linkedin_account'] );

	// Update the meta fields in the database.
	update_post_meta( $post_id, '_job_title', $job_title_data );
	update_post_meta( $post_id, '_email_address', $email_address_data );
	update_post_meta( $post_id, '_phone_number', $phone_number_data );
	update_post_meta( $post_id, '_fb_profile', $fb_profile_data );
	update_post_meta( $post_id, '_twitter_account', $twitter_account_data);
	update_post_meta( $post_id, '_gplus_account', $gplus_account_data );
	update_post_meta( $post_id, '_linkedin_account', $linkedin_account_data );

}
add_action( 'save_post', 'save_profile_custom_meta_box_data' );

function save_testimonial_custom_meta_box_data( $post_id ) {
	
	//Verify this came from our screen and with proper authorization

	if ( ! isset( $_POST['testimonial_details_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['testimonial_details_nonce'], 'testimonial_details' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( ! isset( $_POST['date'] ) ) {
		return;
	}
	if ( ! isset( $_POST['city'] ) ) {
		return;
	}
	if ( ! isset( $_POST['state'] ) ) {
		return;
	}

	// Sanitize user input.
	$date_data = sanitize_text_field( $_POST['date'] );
	$city_data = sanitize_text_field( $_POST['city'] );
	$state_data = sanitize_text_field( $_POST['state'] );

	// Update the meta fields in the database.
	update_post_meta( $post_id, '_date', $date_data );
	update_post_meta( $post_id, '_city', $city_data );
	update_post_meta( $post_id, '_state', $state_data );
}
add_action( 'save_post', 'save_testimonial_custom_meta_box_data' );

//Remove top level admin menus

function remove_menus() {     
   	//remove_menu_page( 'index.php' );                  //Dashboard
	//remove_menu_page( 'edit.php' );                   //Posts
	//remove_menu_page( 'upload.php' );                 //Media
	//remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit-comments.php' );          	//Comments
	//remove_menu_page( 'themes.php' );                 //Appearance
	//remove_menu_page( 'plugins.php' );                //Plugins
	//remove_menu_page( 'users.php' );                  //Users
	//remove_menu_page( 'tools.php' );                  //Tools
	//remove_menu_page( 'options-general.php' );        //Settings
}

add_action('admin_menu', 'remove_menus');


//Remove non-essential metadata from the Wordpress <head>

function head_optimization() {  
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'rsd_link'); 
	remove_action('wp_head', 'feed_links', 2);  
	remove_action('wp_head', 'feed_links_extra', 3);  
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);   
}  
add_action('init', 'head_optimization');

add_filter('widget_text', 'do_shortcode');

add_filter('wp_list_categories', 'cat_count_span');
function cat_count_span($links) {
  $links = str_replace('</a> (', '</a> <span class="badge">', $links);
  $links = str_replace(')', '</span>', $links);
  return $links;
}
add_action( 'init', 'classactions' );
function classactions() {
	$classactionlabels = array(
		'name'                => _x( 'Class Action Cases ', 'Post Type General Name', 'forthepeople' ),
		'singular_name'       => _x( 'Class Action Case ', 'Post Type Singular Name', 'forthepeople' ),
		'menu_name'           => __( 'Class Action Cases ', 'forthepeople' ),
	);
	$classaction = array(
		'label'               => __( 'Class Action Cases ', 'forthepeople' ),
		'description'         => __( 'Class Action Cases ', 'forthepeople' ),
		'labels'              => $classactionlabels,
		'supports'            => array( 'title', 'editor', 'revisions', 'page-attributes', 'excerpt', 'thumbnail' ),
		'public'              => true,
		'menu_position'       => 20,
		'has_archive'         => true,
		'capability_type'     => 'page',
		'menu_icon'		      => 'dashicons-groups',
		'rewrite'             => array( 'slug' => 'class-action-lawyers' ),
		'taxonomies' 		  => array('post_tag'),
		'hierarchical'		  => true
	);
	
	register_post_type( 'Class Action Lawyers', $classaction );
}

function forthepeople_add_rewrite_rule($rewrite_rules, $permalink) {
  global $wp_rewrite;
  $page_uri = $permalink;
  $page_structure = $wp_rewrite->get_page_permastruct();
  $wp_rewrite->add_rewrite_tag('%pagename%', "({$page_uri})", 'pagename=');
  $page_rewrite_rules = $wp_rewrite->generate_rewrite_rules($page_structure, EP_PAGES);
  return array_merge($page_rewrite_rules, $rewrite_rules);
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_dangerous_drugs');
function add_verbose_portfolio_dangerous_drugs($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/dangerous-drugs');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_consumer_fraud');
function add_verbose_portfolio_consumer_fraud($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/consumer-fraud');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_defective_medical_devices');
function add_verbose_portfolio_defective_medical_devices($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/defective-medical-devices');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_securities');
function add_verbose_portfolio_securities($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/securities');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_open_lawsuits');
function add_verbose_portfolio_open_lawsuits($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/open-lawsuits');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_archived_cases');
function add_verbose_portfolio_archived_cases($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/archived-cases');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_class_action');
function add_verbose_portfolio_class_action($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_atlanta_attorneys');
function add_verbose_portfolio_atlanta_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/atlanta');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_orlando_attorneys');
function add_verbose_portfolio_orlando_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/orlando');
}
add_filter('rewrite_rules_array', 'add_verbose_portfolio_daytona_beach_attorneys');
function add_verbose_portfolio_daytona_beach_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/daytona-beach');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_fort_lauderdale_attorneys');
function add_verbose_portfolio_fort_lauderdale_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/fort-lauderdale');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_fort_meyers_attorneys');
function add_verbose_portfolio_fort_meyers_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/fort-myers');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_jackson_attorneys');
function add_verbose_portfolio_jackson_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/jackson');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_jacksonville_attorneys');
function add_verbose_portfolio_jacksonville_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/jacksonville');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_lexington_attorneys');
function add_verbose_portfolio_lexington_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/lexington');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_memphis_attorneys');
function add_verbose_portfolio_memphis_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/memphis');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_new_york_attorneys');
function add_verbose_portfolio_new_york_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/new-york');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_tampa_attorneys');
function add_verbose_portfolio_tampa_attorneys($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'attorneys/tampa');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_additional_class_actions');
function add_verbose_portfolio_additional_class_actions($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/additional-cases');
}

add_filter('rewrite_rules_array', 'add_verbose_portfolio_defective_construction_products');
function add_verbose_portfolio_defective_construction_products($rewrite_rules)
{
  return forthepeople_add_rewrite_rule($rewrite_rules, 'class-action-lawyers/defective-construction-products');
}

function is_child($pageID) { 
	global $post; 
	if( is_page() && ($post->post_parent==$pageID) ) {
               return true;
	} else { 
               return false; 
	}
}





/*

add_filter('really_simple_csv_importer_class', function() {
    return "ImporterCustomize";
});

class ImporterCustomize
{
    public function save_post($post,$meta,$terms,$thumbnail,$is_update)
    {
        $post_name = $post['post_name'];
        $args = array(
            'post_type' => 'any',
            'post_status' => 'any',
            'name' => $post['post_name']
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            $original_post = $query->next_post();
            $post['ID'] = $original_post->ID;
            $is_update = true;
        }
        if (isset($post['post_tags']) && !empty($post['post_tags'])) {
            $post_tags = $post['post_tags'];
            unset($post['post_tags']);
        }
        if ($is_update) {
            $h = RSCSV_Import_Post_Helper::getByID($post['ID']);
            $h->update($post);
        } else {
            $h = RSCSV_Import_Post_Helper::add($post);
        }
        if (isset($post_tags)) {
            $h->setPostTags($post_tags);
        }
        $h->setMeta($meta);
        foreach ($terms as $key => $value) {
            $h->setObjectTerms($key, $value);
        }
        if ($thumbnail) {
            $h->addThumbnail($thumbnail);
        }
        return $h;
    }
}

*/





function more_verdicts_ajax(){
    $postoffset = $_POST["postoffset"];
    $args = array(
        'post_type' => 'verdict',
        'posts_per_page' => 10,
		'meta_key' => 'amount',
		'orderby' => 'meta_value_num',
        'offset' => $postoffset,
    );
	
    $loop = new WP_Query($args);
    while ($loop->have_posts()) { $loop->the_post(); 
		echo '<li>';
		  echo '<div class="type">';
		    echo '<span>' . get_field('ruling_type') . '</span>';
		    echo get_field('practice_area_tag');
		  echo '</div>';
							
		  echo '<div class="result">';
		  $price = get_field('amount');
		    echo  is_numeric($price) ? '$' . number_format($price) : $price;
		  echo '</div>';
		  echo '<p>' . get_field('description') . '</p>';
		echo '</li>';
    }
    exit; wp_reset_postdata();
}

add_action('wp_ajax_nopriv_more_verdicts_ajax', 'more_verdicts_ajax'); 
add_action('wp_ajax_more_verdicts_ajax', 'more_verdicts_ajax');



function more_testimonials_ajax(){
    $postoffset = $_POST["postoffset"];
    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => 10,
		'orderby' => 'date',
		'order' => 'asc',
        'offset' => $postoffset,
    );
	
    $loop = new WP_Query($args);
    while ($loop->have_posts()) { $loop->the_post(); 
		echo '<div class="testimonial">';
			echo '<p>' . get_field('testimonial_text') . '</p>';
			echo '<small>' . get_the_title() . ' from ' . get_field('city') . ', ' . get_field('state') . '</small>';
		echo '</div>';
    }
    exit; wp_reset_postdata();
}

add_action('wp_ajax_nopriv_more_testimonials_ajax', 'more_testimonials_ajax'); 
add_action('wp_ajax_more_testimonials_ajax', 'more_testimonials_ajax');



/**
 * Creates the [contact_attorney] shortcode
 *
 * @return 	string	Contact [First & Last Name]
 */
function show_contact_name() {

	return sprintf( '<h5 class="text-center">Contact %s</h5>', get_the_title() );
	
}

add_shortcode('contact_attorney', 'show_contact_name');

function exclude_category( $query ) {
    if ( !is_admin() && $query->is_main_query() ) {
        $query->set( 'cat', '-120' );
		$query->set( 'ignore_sticky_posts', '1' );
    }
}

add_action( 'pre_get_posts', 'exclude_category' );

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function new_posts_navigation() {
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
    <hr />
    <ul class="pager no-margin-no-pad">
      <?php if ( get_previous_posts_link() ) : ?>
      <li class="previous"><?php previous_posts_link( esc_html__( '← Newer', 'forthepeople' ) ); ?></li>
      <?php endif; ?>
      <?php if ( get_next_posts_link() ) : ?>
      <li class="next"><?php next_posts_link( esc_html__( 'Older →', 'forthepeople' ) ); ?></li>
      <?php endif; ?>
    </ul>
	<?php
}

add_filter( 'get_the_archive_title', function ($title) {
    if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;
        }
		  elseif ( is_month() ) {
        	$title = sprintf(get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
			$title .= ' Archive';
    	}
    return $title;
});

function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');


/**
* Creates a [getfivestars_widget] shortcode for Morgan & Morgan
* 
* @return 		string	An embed widget <div id="e2wget5widget">
*/

function getfivestars() {
	
	$output = '';
	$e2wget5 = 0; 
	$url = 'https://getfivestars.com/reviews/19596.82AeudCgFchM';

	if(function_exists('curl_exec')){ 
		$c=curl_init($url); 
		curl_setopt($c,CURLOPT_CONNECTTIMEOUT,5); 
		curl_setopt($c,CURLOPT_TIMEOUT,10); 
		curl_setopt($c,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($c,CURLOPT_SSL_VERIFYHOST,0); 
		$output .= @curl_exec($c);$e2wget5 = 1; 
	} elseif( ini_get('allow_url_fopen')){ 
		$output .= @file_get_contents($url);$e2wget5 = 2; 
	}
	
	return '<div id="e2wget5widget" class="mmftp">' . $output . '<script src="https://getfivestars.com/reviews.js/19596.82AeudCgFchM"></script></div>';
}
add_shortcode( 'getfivestars_widget', 'getfivestars' );

function rewritearchives_init(){
    global $wp_rewrite;
    $wp_rewrite->date_structure = 'blog/archive/%year%/%monthnum%';
}
add_action( 'init', 'rewritearchives_init' );

/*
  Prevent WP Search functionality by redirecting to home url
*/
function forthepeople_filter_query( $wp_query ) {
 if (!is_admin()) {
  if ($wp_query->is_search()) {
    wp_redirect( home_url() );
    exit;
  }
 }
}

add_action( 'parse_query', 'forthepeople_filter_query' );


//Insert the CTA button on mobile after the first paragraph of the location and practice area pages.

add_filter( 'the_content','insert_cta_btn_on_mobile' );


function insert_cta_btn_on_mobile( $content ) {

	$cta_btn = '<div class="cta-btn-for-mobile"><a href="#free-immediate-consultation">Free Immediate Consultation</a></div>';
	
	if ( wp_is_mobile() ) {
		if ( is_page_template('office-location.php') || is_page_template('practice-area.php') )  {

			return insert_cta_after_paragraph( $cta_btn, 1,$content );
		}
	}
	return $content;
}


function insert_cta_after_paragraph( $insertion,$paragraph_id, $content ) {

	$closing_p = '</p>';

	$paragraphs = explode( $closing_p, $content );

	foreach ($paragraphs as $index => $paragraph) {

		if ( trim( $paragraph ) ) {

			$paragraphs[$index] .= $closing_p;

		}

		if ( $paragraph_id == $index + 1 ) {

			$paragraphs[$index] .= $insertion;
		}

		return implode( '', $paragraphs );
		
	}

}

/**
 * Include filters for category rewrites.
 */
require get_template_directory() . '/inc/blog-category-redirect.php';


//fix for issue with wpmdbpro
add_filter('wpmdb_after_response', function($response){
	return trim($response);
});

function is_page_or_is_child_of( $slug ) {
	global $post;
	if ( ! $post ) {
		return false;
	}
	if ( is_page( $slug ) ) {
		return true;
	}
	if ( (int) $post->post_parent > 0 ) {
		$parent = get_post( $post->post_parent );
		if ( $parent->post_name === $slug ) {
			return true;
		}

	}

	return false;


}