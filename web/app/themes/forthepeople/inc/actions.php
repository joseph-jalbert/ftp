<?php

class Actions {

	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'script_loader_tag', array( __CLASS__, 'enqueue_additions' ), 10, 2 );
		add_action( 'wp_head', array( __CLASS__, 'meta_fields' ) );
        add_action( 'wp_head', array( __CLASS__, 'social_profiles' ) );

	}

	public static function meta_fields(){
		?><meta name="p:domain_verify" content="9bef95dda85621e5f627bfddb6b3c0be"/><?php
	}

    public static function social_profiles() {
        if( is_page('Home') ) {
            echo '<script type="application/ld+json">
                    { "@context" : "http://schema.org",
                      "@type" : "Organization",
                      "name" : "Morgan & Morgan",
                      "url" : "https://www.forthepeople.com",
                      "sameAs" : [ "https://www.facebook.com/MMForthePeople",
                        "https://twitter.com/forthepeople",
                        "https://www.youtube.com/user/mmforthepeople"] 
                    }
                    </script>';
        }
    }
	public static function enqueue_additions( $tag, $handle ) {

		if ( 'hubspot-ie8-script' === $handle ) {
			$tag = '<!--[if lte IE 8]>' . $tag . '<![endif]-->';
		}

		return $tag;

	}

	public static function enqueue() {
		wp_enqueue_script( 'hubspot-ie8-script', '//js.hsforms.net/forms/v2-legacy.js' );
		wp_enqueue_script( 'hubspot-script', '//js.hsforms.net/forms/v2.js' );

	}


}

Actions::init();