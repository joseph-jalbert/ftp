<?php
/* 
* Plugin Name: Post to Processing
* Description: Posts leads into processing.forthepeople.com
* Version: 0.01
*/

require __DIR__ . '/inc/wp-async-task.php';
require __DIR__ . '/inc/async-post-to-processing.php';



defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class MM_Post_To_Processing {
  private $settings;

  function __construct() {
    $this->settings = get_option( 'post_to_processing_settings' );
    add_action( 'admin_menu', array($this, 'wp_mm_post_to_processing_tab') );
    add_action( 'admin_init', array($this, 'wp_post_to_processing_setting') );
    add_action( 'gform_after_submission', array($this, 'post_form_to_processing'), 10, 2 );
    add_filter( 'gform_field_validation', array($this, 'five_digit_zipcode'), 10, 4);
  }

  // Admin Page
  function wp_mm_post_to_processing_page() {

    //must check that the user has the required capability
    if (!current_user_can('manage_options')) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    ob_start(); ?>

    <div class="wrap">

      <h2>Morgan & Morgan Post to Processing Settings</h2>

      <form action="options.php" method="POST">

        <?php settings_fields('post_to_processing_group'); ?>

        <table class="form-table">

          <tbody>

            <tr>

              <th><label for="category_base">Processing Endpoint:</label></th>

              <td><input name="post_to_processing_settings[post_url]" id="post_url" class="regular-text code" type="text" value="<?php echo $this->settings['post_url']; ?>" /></td>

            </tr>
            <tr>

              <th><label for="category_base">Inquiry Source ID:</label></th>

              <td><input name="post_to_processing_settings[inquiry_source_id]" id="inquiry_source_id" class="regular-text code" type="text" value="<?php echo $this->settings['inquiry_source_id']; ?>" /></td>

            </tr>
            <tr>

              <th><label for="category_base">Inquiry Website ID:</label></th>

              <td><input name="post_to_processing_settings[inquiry_website_id]" id="inquiry_website_id" class="regular-text code" type="text" value="<?php echo $this->settings['inquiry_website_id']; ?>" /></td>

            </tr>
          </tbody>

        </table>

        <p class="submit">

          <input class="button button-primary" type="submit" value="Save Changes">

        </p>

      </form>

    </div>

    <?php

    echo ob_get_clean();
  }

  // Admin Tab
  function wp_mm_post_to_processing_tab() {
    add_options_page( 'Post to Processing Settings', 'Post to Processing Settings', 'manage_options', 'wp_mm_post_to_processing', array($this, 'wp_mm_post_to_processing_page') );
  }

  // Register Settings
  function wp_post_to_processing_setting() {
    register_setting( 'post_to_processing_group', 'post_to_processing_settings' );
  }

  function post_form_to_processing($entry, $form) {
    $data = array();
    foreach($form["fields"] as &$field) {

      if ($field['adminLabel'] == '') {
        continue;
      }

      $fieldId = strval($field['id']);

      if (array_key_exists($fieldId, $entry)) {
        $data[$field['adminLabel']] = $entry[$fieldId];
      } else {
        $data[$field['adminLabel']] = $field['defaultValue'];
      }
    }

    $this->do_post($data, $entry);


  }

  function do_post($data, $entry) {
    // this is what processing.forthepeople.com/processinquiry.cfm wants:
	  do_action( 'post_to_processing', $data, $this->settings['inquiry_website_id'], $this->settings['post_url'], $entry, $_SERVER['HTTP_REFERER'] );
  }

  function five_digit_zipcode( $result, $value, $form, $field ) {

    if( $field["adminLabel"] == 'zip' ) {
        //allow only 5 digits
        $is_5_digits = preg_match("/^[0-9]{5}$/", $value);
        if (!$is_5_digits /* 0 means no match, FALSE means error, either are invalid conditions */) {
            $result["is_valid"] = false;
            $result["message"] = "Please enter a valid 5 digit zipcode.";
        }
    }
    return $result;
  }
}

 new MM_Post_To_Processing();