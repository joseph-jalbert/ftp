<?php
/**
 * Save class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */
class OMAPI_Save {

	/**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $instance;

	/**
     * Path to the file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds any save errors.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $errors = array();

    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

	    // Set our object.
	    $this->set();

		// Possibly save settings.
		$this->maybe_save();

    }

    /**
     * Sets our object instance and base class instance.
     *
     * @since 1.0.0
     */
    public function set() {

        self::$instance = $this;
        $this->base 	= OMAPI::get_instance();
        $this->view     = isset( $_GET['optin_monster_api_view'] ) ? stripslashes( $_GET['optin_monster_api_view'] ) : $this->base->get_view();

    }

    /**
     * Maybe save options if the action has been requested.
     *
     * @since 1.0.0
     */
    public function maybe_save() {

	    // If we are missing our save action, return early.
	    if ( empty( $_POST['omapi_save'] ) ) {
		    return;
	    }

	    // If the subkey is empty, return early.
	    if ( empty( $_POST['omapi'][ $this->view ] ) ) {
		    return;
	    }

	    // Verify the nonce field.
	    check_admin_referer( 'omapi_nonce_' . $this->view, 'omapi_nonce_' . $this->view );

	    // Save the settings.
	    $this->save();

	    // Provide action to save settings.
	    do_action( 'optin_monster_api_save_settings', $this->view );

    }

    /**
     * Save the plugin options.
     *
     * @since 1.0.0
     */
    public function save() {

	    // Prepare variables.
	    $data = stripslashes_deep( $_POST['omapi'][ $this->view ] );

		// Save the data.
	    switch ( $this->view ) {
		    case 'api' :
				// Create a new API instance to verify API credentials.
				$option   = $this->base->get_option();
				$user     = isset( $data['user'] ) ? $data['user'] : false;
				$key      = isset( $data['key'] ) ? $data['key'] : false;
				$old_user = isset( $option['api']['user'] ) ? $option['api']['user'] : false;
				$old_key  = isset( $option['api']['key'] ) ? $option['api']['key'] : false;

				// If one or both items are missing, fail.
				if ( ! $user || ! $key ) {
					// If it had been stored and it is now empty, reset the keys altogether.
					if ( ! $user && $old_user || ! $key && $old_key ) {
						$option['api']['user'] = '';
						$option['api']['key']  = '';

						// Allow option to be filtered before saving.
						$option = apply_filters( 'optin_monster_api_save', $option, $data, $this->view );

						// Save the option.
						update_option( 'optin_monster_api', $option );
					} else {
						$this->errors['error'] = __( 'You must provide a valid API Username and API Key to authenticate to OptinMonster.', 'optin-monster-api' );
					}
				} else {
					if ( $user != $old_user || $key != $old_key ) {
						$api = new OMAPI_Api( 'verify', array( 'user' => $user, 'key' => $key ) );
						$ret = $api->request();
						if ( is_wp_error( $ret ) ) {
							$this->errors['error'] = $ret->get_error_message();
						} else {
							// This user and key are good to go!
							$option['api']['user'] = $user;
							$option['api']['key']  = $key;

							// Remove any error messages.
							$option['is_invalid']  = false;
							$option['is_expired']  = false;
							$option['is_disabled'] = false;

							// Store the optin data.
							$this->store_optins( $ret );

							// Allow option to be filtered before saving.
							$option = apply_filters( 'optin_monster_api_save', $option, $data, $this->view );

							// Save the option.
							update_option( 'optin_monster_api', $option );
						}
					}
				}
		    break;

		    case 'optins' :
		    	// Prepare variables.
		    	$data['categories']   = isset( $_POST['post_category'] ) ? stripslashes_deep( $_POST['post_category'] ) : array();
		    	$data['taxonomies']	  = isset( $_POST['tax_input'] ) ? stripslashes_deep( $_POST['tax_input'] ) : array();
		    	$optin_id 		      = absint( $_GET['optin_monster_api_id'] );
		    	$fields				  = array();
		    	$fields['enabled']    = isset( $data['enabled'] ) ? 1 : 0;
		    	$fields['global']     = isset( $data['global'] ) ? 1 : 0;
		    	$fields['automatic']  = isset( $data['automatic'] ) ? 1 : 0;
		    	$fields['users']      = isset( $data['users'] ) ? esc_attr( $data['users'] ) : 'all';
		    	$fields['never']      = isset( $data['never'] ) ? explode( ',', $data['never'] ) : array();
		    	$fields['only']       = isset( $data['only'] ) ? explode( ',', $data['only'] ) : array();
		    	$fields['categories'] = isset( $data['categories'] ) ? $data['categories'] : array();
		    	$fields['taxonomies'] = isset( $data['taxonomies'] ) ? $data['taxonomies'] : array();
		    	$fields['show']		  = isset( $data['show'] ) ? $data['show'] : array();
		    	$fields['shortcode']  = isset( $data['shortcode'] ) ? 1 : 0;
		    	$fields['shortcode_output']  = isset( $data['shortcode_output'] ) ? trim( strip_tags( htmlentities( $data['shortcode_output'], ENT_COMPAT ) ) ) : '';

			    if ( class_exists( 'WYSIJA' ) ) {
			    	$fields['mailpoet'] 	 = isset( $data['mailpoet'] ) ? 1 : 0;
			    	$fields['mailpoet_list'] = isset( $data['mailpoet_list'] ) ? esc_attr( $data['mailpoet_list'] ) : 'none';
		    	}

		    	// Allow fields to be filtered.
		    	$fields = apply_filters( 'optin_monster_save_fields', $fields, $optin_id );

		    	// Loop through each field and save the data.
		    	foreach ( $fields as $key => $val ) {
			    	update_post_meta( $optin_id, '_omapi_' . $key, $val );
		    	}
		    break;

		    case 'settings' :
		    	$option = $this->base->get_option();
		    	$option['settings']['cookies'] = isset( $data['cookies'] ) ? 1 : 0;

		    	// Allow option to be filtered before saving.
				$option = apply_filters( 'optin_monster_api_save', $option, $data, $this->view );

				// Save the option.
				update_option( 'optin_monster_api', $option );
		    break;
	    }

	    // If selected, clear out all local cookies.
	    if ( $this->base->get_option( 'settings', 'cookies' ) ) {
		    $this->base->actions->cookies();
	    }

	    // Add message to show error or success messages.
	    if ( ! empty( $this->errors ) ) {
		    add_action( 'optin_monster_api_messages_' . $this->view, array( $this, 'errors' ) );
	    } else {
			// Add a success message.
			add_action( 'optin_monster_api_messages_' . $this->view, array( $this, 'message' ) );
	    }

    }

    /**
     * Store the optin data locally on the site.
     *
     * @since 1.0.0
     *
     * @param array $optins Array of optin objects to store.
     */
    public function store_optins( $optins ) {

	    // Do nothing if this is just a success message.
	    if ( isset( $optins->success ) ) {
		    return;
	    }

	    // Loop through all of the local optins so we can try to match and update.
	    $local_optins = $this->base->get_optins();
	    if ( $local_optins ) {
		    $data = array();
		    foreach ( $local_optins as $optin ) {
			    if ( isset( $optins->{$optin->post_name} ) ) {
				    $data['ID'] 		  = $optin->ID;
					$data['post_title']   = $optins->{$optin->post_name}->title;
					$data['post_content'] = $optins->{$optin->post_name}->output;
					$data['post_status']  = 'publish';
					wp_update_post( $data );
					update_post_meta( $optin->ID, '_omapi_type', $optins->{$optin->post_name}->type );
					update_post_meta( $optin->ID, '_omapi_ids', $optins->{$optin->post_name}->ids );
					unset( $optins->{$optin->post_name} );
			    } else {
				    // Delete the local optin. It does not exist remotely.
				    wp_delete_post( $optin->ID, true );
				    unset( $optins->{$optin->post_name} );
			    }
			    unset( $data );
		    }

		    // If we still have optins, they are new and we need to add them.
		    if ( ! empty( $optins ) ) {
			    foreach ( (array) $optins as $slug => $optin ) {
				    $data				  = array();
					$data['post_name']    = $slug;
					$data['post_title']   = $optin->title;
					$data['post_excerpt'] = $optin->id;
					$data['post_content'] = $optin->output;
					$data['post_status']  = 'publish';
					$data['post_type']	  = 'omapi';
					$post_id = wp_insert_post( $data );
					update_post_meta( $post_id, '_omapi_type', $optin->type );
					update_post_meta( $post_id, '_omapi_ids', $optin->ids );
				}
		    }
	    } else {
		    foreach ( (array) $optins as $slug => $optin ) {
				// Maybe update an optin rather than add a new one.
				$local = $this->base->get_optin_by_slug( $slug );
				$data  = array();
				if ( $local ) {
					$data['ID'] 		  = $local->ID;
					$data['post_title']   = $optin->title;
					$data['post_content'] = $optin->output;
					$data['post_status']  = 'publish';
					wp_update_post( $data );
					update_post_meta( $local->ID, '_omapi_type', $optin->type );
					update_post_meta( $local->ID, '_omapi_ids', $optin->ids );
				} else {
					$data['post_name']    = $slug;
					$data['post_title']   = $optin->title;
					$data['post_excerpt'] = $optin->id;
					$data['post_content'] = $optin->output;
					$data['post_status']  = 'publish';
					$data['post_type']	  = 'omapi';
					$post_id = wp_insert_post( $data );
					update_post_meta( $post_id, '_omapi_type', $optin->type );
					update_post_meta( $post_id, '_omapi_ids', $optin->ids );
				}
			}
	    }

    }

    /**
     * Output any error messages.
     *
     * @since 1.0.0
     */
    public function errors() {

		foreach ( $this->errors as $id => $message ) :
	    ?>
	    <div class="<?php echo sanitize_html_class( $id, 'error' ); ?>"><p><?php echo $message; ?></p></div>
	    <?php
		endforeach;

    }

    /**
     * Output a save message.
     *
     * @since 1.0.0
     */
    public function message() {

	    ?>
	    <div class="updated"><p><?php _e( 'Your settings have been saved successfully.', 'optin-monster-api' ); ?></p></div>
	    <?php

    }

}