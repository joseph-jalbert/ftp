<?php

if (!defined('ABSPATH')) die('Access denied.');

class Simba_Two_Factor_Authentication_Premium {

	private $tfa;
	private $frontend;

	public function __construct() {

		add_filter('simba_tfa_emergency_codes_user_settings', array($this, 'simba_tfa_emergency_codes_user_settings'), 10, 2);
		add_filter('simba_tfa_fetch_assort_vars', array($this, 'simba_tfa_fetch_assort_vars'), 10, 3);
		add_action('simba_tfa_adding_private_key', array($this, 'simba_tfa_adding_private_key'), 10, 4);
		add_action('simba_tfa_emergency_code_used', array($this, 'simba_tfa_emergency_code_used'), 10, 2);
		add_filter('simba_tfa_support_url', array($this, 'simba_tfa_support_url'));
		add_action('simba_tfa_users_settings', array($this, 'simba_tfa_users_settings'));
		add_action('wp_ajax_simbatfa_choose_user', array($this, 'wp_ajax_simbatfa_choose_user'));
		add_action('wp_ajax_simbatfa_user_get_codes', array($this, 'wp_ajax_simbatfa_user_get_codes'));
		add_action('wp_ajax_simbatfa_user_activation', array($this, 'wp_ajax_simbatfa_user_activation'));
		add_filter('simba_tfa_after_user_roles', array($this, 'simba_tfa_after_user_roles'));
		add_action('all_admin_notices', array($this, 'all_admin_notices'));
		add_action('admin_scripts', array($this, 'admin_scripts'), 11);

		add_shortcode('twofactor_user_settings_enabled', array($this, 'shortcode_twofactor_user_settings_enabled'));
		add_shortcode('twofactor_user_qrcode', array($this, 'shortcode_twofactor_user_qrcode'));
		add_shortcode('twofactor_user_emergencycodes', array($this, 'shortcode_twofactor_user_emergencycodes'));
		add_shortcode('twofactor_user_advancedsettings', array($this, 'shortcode_twofactor_user_advancedsettings'));
		add_shortcode('twofactor_user_privatekeys', array($this, 'shortcode_twofactor_user_privatekeys'));
		add_shortcode('twofactor_user_privatekeys_reset', array($this, 'shortcode_twofactor_user_privatekeys_reset'));
		add_shortcode('twofactor_user_currentcode', array($this, 'shortcode_twofactor_user_currentcode'));
		add_shortcode('twofactor_user_presstorefresh', array($this, 'shortcode_twofactor_user_presstorefresh'));
		add_shortcode('twofactor_conditional', array($this, 'shortcode_twofactor_conditional'));
	}

	public function simba_tfa_support_url($url) {
		return 'https://www.simbahosting.co.uk/s3/support/tickets/';
	}

	public function simba_tfa_after_user_roles($default) {

		global $simba_two_factor_authentication;

		$ret = '';
		$ret .= '<form method="post" action="options.php" style="margin-top: 12px">';
			
// 			settings_fields('tfa_user_roles_required_group');
		$ret .= "<input type='hidden' name='option_page' value='tfa_user_roles_required_group' />";
		$ret .= '<input type="hidden" name="action" value="update" />';
		$ret .= wp_nonce_field("tfa_user_roles_required_group-options", '_wpnonce', true, false);


		$ret .= __('Choose which user roles are required to have two-factor authentication active (remember to also make it available for any chosen roles).', SIMBA_TFA_TEXT_DOMAIN);
		$ret .= '<p>';

		if (is_multisite()) {
			// Not a real WP role; needs separate handling
			$id = '_super_admin';
			$name = __('Multisite Super Admin', SIMBA_TFA_TEXT_DOMAIN);
			$setting = (bool)$simba_two_factor_authentication->get_option('tfa_required_'.$id);
			
			$ret .= '<input type="checkbox" id="tfa_required_'.$id.'" name="tfa_required_'.$id.'" value="1" '.($setting ? 'checked="checked"' :'').'> <label for="tfa_required_'.$id.'">'.htmlspecialchars($name)."</label><br>\n";
		}

		global $wp_roles;
		if (!isset($wp_roles)) $wp_roles = new WP_Roles();
		
		foreach($wp_roles->role_names as $id => $name)
		{	
			$setting = (bool)$simba_two_factor_authentication->get_option('tfa_required_'.$id);
			
			$ret .= '<input type="checkbox" id="tfa_required_'.$id.'" name="tfa_required_'.$id.'" value="1" '.($setting ? 'checked="checked"' :'').'> <label for="tfa_required_'.$id.'">'.htmlspecialchars($name)."</label><br>\n";
		}

		$ret .= '</p><p>';

		$requireafter = $simba_two_factor_authentication->get_option('tfa_requireafter');
		if (false === $requireafter) {
			$requireafter = "10";
		} else {
			$requireafter = (string)absint($requireafter);
		}

		$ret .= sprintf(__('Enforce this requirement only for accounts at least %s days old', SIMBA_TFA_TEXT_DOMAIN), '<input type="number" style="width:60px;" step="1" min="0" name="tfa_requireafter" id="tfa_requireafter" value="'.$requireafter.'">');

		$ret .= '</p>'.get_submit_button().'</form>';

		return $ret;

	}

	public function wp_ajax_simbatfa_user_get_codes() {
		if (empty($_REQUEST['userid']) || !is_numeric($_REQUEST['userid']) || empty($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'simbatfa_user_get_codes')) die('Security check (4).');

		global $simba_two_factor_authentication;

		$tfa = $simba_two_factor_authentication->getTFA();

		if(!$tfa->isActivatedForUser($_REQUEST['userid'])){
			echo  '<p><em>'.__('Two factor authentication is not available for this user.', SIMBA_TFA_TEXT_DOMAIN).'</em></p>';;
		} else {
			if (!$tfa->isActivatedByUser($_REQUEST['userid'])) {
				echo '<p><em>'.__('Two factor authentication is not activated for this user.', SIMBA_TFA_TEXT_DOMAIN).'</em></p>';
			} else {
				$simba_two_factor_authentication->current_codes_box(true, $_REQUEST['userid']);
			}
		}

		exit;
	}

	public function wp_ajax_simbatfa_user_activation() {
		if (empty($_REQUEST['userid']) || !is_numeric($_REQUEST['userid']) || empty($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'simbatfa_user_activation')) die('Security check (5).');

		global $simba_two_factor_authentication;

		$tfa = $simba_two_factor_authentication->getTFA();

		if(!$tfa->isActivatedForUser($_REQUEST['userid'])){
			echo  '<p><em>'.__('Two factor authentication is not available for this user.', SIMBA_TFA_TEXT_DOMAIN).'</em></p>';
		} else {
			$activate_or_not = empty($_REQUEST['activate']) ? false : true;

			// TFA:changeEnableTFA() just checks on whether the parameter is (string)'true' or not.
			$activate_string = ($activate_or_not) ? 'true' : 'no';

			$tfa->changeEnableTFA($_REQUEST['userid'], $activate_string);

			if ($activate_or_not) {
				echo  '<p><em>'.__('Two factor authentication has been activated for this user.', SIMBA_TFA_TEXT_DOMAIN).'</em></p>';
			} else {
				echo  '<p><em>'.__('Two factor authentication has been de-activated for this user.', SIMBA_TFA_TEXT_DOMAIN).'</em></p>';
			}
		}
		exit;
	}

	public function wp_ajax_simbatfa_choose_user() {
		if (empty($_REQUEST['q']) || empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'simbatfa-choose-user')) die('Security check (6).');

		// https://codex.wordpress.org/Class_Reference/WP_User_Query

		$args = array(
			'search' => '*'.$_REQUEST['q'].'*',
			'fields' => array('ID', 'user_login', 'user_email', 'user_nicename'),
			'search_columns' => array('user_login', 'user_email')
		);

		$res = array();

		$user_query = new WP_User_Query($args);

		if ( ! empty( $user_query->results ) ) {
			foreach ( $user_query->results as $user ) {
				$res[] = array(
					'id' => $user->ID,
					'text' => sprintf("%s - %s (%s)", $user->user_nicename, $user->user_login, $user->user_email),
				);
			}
		}

/*
			array(
				array(
					'id' => 1,
					'text' => 'tharg'
				)
			)
*/

		$results = json_encode(array(
			'results' => $res
		));

		echo $results;
		die;
	}

	public function simba_tfa_users_settings() {
		$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
		wp_deregister_script('select2');
		wp_register_script('select2', SIMBA_TFA_PLUGIN_URL . '/includes/select2'.$suffix.'.js', array('jquery'), '4.0.2');
		wp_enqueue_script('select2');
		wp_enqueue_style('select2', SIMBA_TFA_PLUGIN_URL . '/includes/select2.css', array(), '4.0.2');
		add_action('admin_footer', array($this, 'admin_footer_select2'));
		?>
		<div class="simba_tfa_users">
		<p>
		<h3><?php _e('Show codes for a particular user', SIMBA_TFA_TEXT_DOMAIN);?></h3>
		<select class="simba_tfa_choose_user" style="width: 240px;">
		</select>
		<button class="simba_tfa_user_get_codes button button-primary"><?php _e('Get codes', SIMBA_TFA_TEXT_DOMAIN);?></button>
		<button class="simba_tfa_user_deactivate button button-primary"><?php _e('De-activate TFA', SIMBA_TFA_TEXT_DOMAIN);?></button>
		<button class="simba_tfa_user_activate button button-primary"><?php _e('Activate TFA', SIMBA_TFA_TEXT_DOMAIN);?></button>
		</p>
		<p class="simba_tfa_user_results">
		</p>
		</div>
		<?php
		// Enqueue jquery qrcode
		global $simba_two_factor_authentication;
		$simba_two_factor_authentication->add_footer(true);
		/*
		<button class="simba_tfa_user_reset button button-primary"><?php _e('Reset', SIMBA_TFA_TEXT_DOMAIN);?></button>
		*/
	}

	public function admin_footer_select2() {
		?>
		<script>
			jQuery(document).ready(function($) {
				$('.simba_tfa_user_get_codes').click(function(e) {
					e.preventDefault();
					var $area = $(this);
					var whichuser = $(this).siblings('.simba_tfa_choose_user').val();
					if (null == whichuser || '' == whichuser) {
						alert('<?php echo esc_js(__('You must first choose a valid user.', SIMBA_TFA_TEXT_DOMAIN));?>');
						return;
					};
					$.post(ajaxurl, {
						action: "simbatfa_user_get_codes",
						userid: whichuser,
						nonce: "<?php echo wp_create_nonce("simbatfa_user_get_codes");?>"
					}, function(response) {
						$area.parents('.simba_tfa_users').find('.simba_tfa_user_results').html(response);
						$('.simba_tfa_user_results .simbaotp_qr_container').qrcode({
							"render": "image",
							"text": $('.simbaotp_qr_container:first').data('qrcode'),
						});
					});
				});
				$('.simba_tfa_user_deactivate').click(function(e) {
					e.preventDefault();
					var $area = $(this);
					var whichuser = $(this).siblings('.simba_tfa_choose_user').val();
					if (null == whichuser || '' == whichuser) {
						alert('<?php echo esc_js(__('You must first choose a valid user.', SIMBA_TFA_TEXT_DOMAIN));?>');
						return;
					};
					$.post(ajaxurl, {
						action: "simbatfa_user_activation",
						userid: whichuser,
						activate: 0,
						nonce: "<?php echo wp_create_nonce("simbatfa_user_activation");?>"
					}, function(response) {
						$area.parents('.simba_tfa_users').find('.simba_tfa_user_results').html(response);
					});
				});
				$('.simba_tfa_user_activate').click(function(e) {
					e.preventDefault();
					var $area = $(this);
					var whichuser = $(this).siblings('.simba_tfa_choose_user').val();
					if (null == whichuser || '' == whichuser) {
						alert('<?php echo esc_js(__('You must first choose a valid user.', SIMBA_TFA_TEXT_DOMAIN));?>');
						return;
					};
					$.post(ajaxurl, {
						action: "simbatfa_user_activation",
						userid: whichuser,
						activate: 1,
						nonce: "<?php echo wp_create_nonce("simbatfa_user_activation");?>"
					}, function(response) {
						$area.parents('.simba_tfa_users').find('.simba_tfa_user_results').html(response);
					});
				});
				$('.simba_tfa_choose_user').select2({
					 ajax: {
						url: "<?php echo addslashes(admin_url('admin-ajax.php?action=simbatfa_choose_user&_wpnonce=').wp_create_nonce('simbatfa-choose-user')) ; ?>",
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term, // search term
								page: params.page
							};
						},
						processResults: function (data) {
							return data;
						},
						cache: true
					},
					
// 					escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
					minimumInputLength: 2,
// 					templateResult: formatRepo, // omitted for brevity, see the source of this page
// 					templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
				});
			});
		</script>
		<?php
	}


	public function all_admin_notices() {
		// Test for whether they're require to have TFA active and haven't yet done so.
		
		global $current_user, $simba_two_factor_authentication;
		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();
		if ($this->tfa->isActivatedForUser($current_user->ID) && $this->tfa->isRequiredForUser($current_user->ID) && !$this->tfa->isActivatedByUser($current_user->ID)) {
			$simba_two_factor_authentication->show_admin_warning('<strong>'.__('Please set up two-factor authentication', SIMBA_TFA_TEXT_DOMAIN).'</strong><br> <a href="'.admin_url('admin.php').'?page=two-factor-auth-user">'.__('You will need to set up and use two-factor authentication to login in future.</a>', SIMBA_TFA_TEXT_DOMAIN), 'error');
		}
	}


	// This function is intended for use by third party developers
	public function tfa_is_available_and_active() {
		global $current_user, $simba_two_factor_authentication;
		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();
		return ($this->tfa->isActivatedForUser($current_user->ID) && $this->tfa->isActivatedByUser($current_user->ID)) ? true : false;
	}

	public function shortcode_twofactor_conditional($atts, $content = null) {

		global $current_user, $simba_two_factor_authentication;

		// Valid: available, unavailable, active, inactive (which implies available)
		$atts = shortcode_atts( array(
			'onlyif' => 'active'
		), $atts );

		if (!in_array($atts['onlyif'], array('active', 'inactive', 'available', 'unavailable'))) return '';

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		$condition = $atts['onlyif'];
		$condition_fulfilled = false;

		if ($this->tfa->isActivatedForUser($current_user->ID)){
			if ('available' == $condition) {
				$condition_fulfilled = true;
			} elseif ('inactive' == $condition && !$this->tfa->isActivatedByUser($current_user->ID)) {
				$condition_fulfilled = true;
			} elseif ('active' == $condition  && $this->tfa->isActivatedByUser($current_user->ID)) {
				$condition_fulfilled = true;
			}
		} elseif ('unavailable' == $condition) {
			$condition_fulfilled = true;
		}

		return ($condition_fulfilled) ? do_shortcode($content) : '';

	}

	public function shortcode_twofactor_user_presstorefresh($atts, $content = null) {
		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			return __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			$simba_two_factor_authentication->add_footer(false);
			return '<span class="simbaotp_refresh">'.do_shortcode($content).'</span>';
		}
	}

	public function shortcode_twofactor_user_currentcode($atts, $content = null) {
		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			return __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			return $simba_two_factor_authentication->current_otp_code($this->tfa);
		}

	}

	public function shortcode_twofactor_user_privatekeys($atts, $content = null) {
		global $simba_two_factor_authentication, $current_user;

		// Valid: full, plain, base32, base64
		$atts = shortcode_atts( array(
			'type' => 'full'
		), $atts );

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			return __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			ob_start();
			$simba_two_factor_authentication->print_private_keys(false, $atts['type']);
			return ob_get_clean();
		}
	}

	public function shortcode_twofactor_user_privatekeys_reset($atts, $content = null) {
		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			return __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			return $simba_two_factor_authentication->reset_link(false);
		}
	}

	public function shortcode_twofactor_user_advancedsettings($atts, $content = null) {
		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			return __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			ob_start();
			$simba_two_factor_authentication->advanced_settings_box(array($this, 'save_settings_button'));
			$simba_two_factor_authentication->load_frontend()->save_settings_javascript_output();
			return ob_get_clean();
			
		}
	}

	public function save_settings_button() {
		echo '<button style="margin-left: 4px;margin-bottom: 10px" class="simbatfa_settings_save button button-primary">'.__('Save Settings', SIMBA_TFA_TEXT_DOMAIN).'</button>';
	}

	public function shortcode_twofactor_user_emergencycodes($atts, $content = null) {
		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			return __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			return $this->get_emergency_codes_as_string();
		}
		
	}

	public function shortcode_twofactor_user_qrcode($atts, $content = null) {

		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		$simba_two_factor_authentication->add_footer(false);
		
		ob_start();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			echo __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {

			$url = preg_replace('/^https?:\/\//', '', site_url());
			
			$tfa_priv_key_64 = get_user_meta($current_user->ID, 'tfa_priv_key_64', true);
			
			if(!$tfa_priv_key_64) $tfa_priv_key_64 = $this->tfa->addPrivateKey($current_user->ID);

			$tfa_priv_key = trim($this->tfa->getPrivateKeyPlain($tfa_priv_key_64, $current_user->ID), "\x00..\x1F");

			$tfa_priv_key_32 = Base32::encode($tfa_priv_key);

			$algorithm_type = $this->tfa->getUserAlgorithm($current_user->ID);

			?>

			<p title="<?php echo sprintf(__("Private key: %s (base 32: %s)", SIMBA_TFA_TEXT_DOMAIN), $tfa_priv_key, $tfa_priv_key_32);?>">
				<?php $qr_url = $simba_two_factor_authentication->tfa_qr_code_url($algorithm_type, $url, $tfa_priv_key) ?>
				<div class="simbaotp_qr_container" data-qrcode="<?php echo esc_attr($qr_url); ?>"></div>
			</p>

			<?php
		}

		return ob_get_clean();
		
	}

	public function shortcode_twofactor_user_settings_enabled($atts, $content = null) {

		global $simba_two_factor_authentication, $current_user;

		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();

		ob_start();

		if(!$this->tfa->isActivatedForUser($current_user->ID)){
			echo __('Two factor authentication is not available for your user.', SIMBA_TFA_TEXT_DOMAIN);
		} else {
			$simba_two_factor_authentication->load_frontend()->settings_enable_or_disable_output();
		}

		return ob_get_clean();

	}

	// Let the user know that an emergency code was used, and that they may need to generate some more.
	public function simba_tfa_emergency_code_used($user_id, $emergency_codes) {

		$extra = empty($emergency_codes) ? "\r\n".__('Your must now go to the Two Factor Authentication settings and generated a new private key if you wish to use any emergency codes in future.', SIMBA_TFA_TEXT_DOMAIN) : '';

		$user = get_userdata($user_id);
		if (!is_object($user) || empty($user->user_email)) return;
		wp_mail(
			$user->user_email,
			home_url().': '.__('emergency login code used', SIMBA_TFA_TEXT_DOMAIN), 
			sprintf(__('An emergency code was used to login (username: %s) on this website: ', SIMBA_TFA_TEXT_DOMAIN), $user->user_login).home_url()."\r\n\r\n".
			sprintf(__('You now have %s emergency code(s) remaining.', SIMBA_TFA_TEXT_DOMAIN), count($emergency_codes))."\r\n".
			$extra
		);
		
	}

	private function get_otp($alg, $user_ID, $code, $tfa, $counter = false) {
		if ($alg == 'hotp') {
			return $tfa->encryptString($tfa->generateOTP($user_ID, $code, 8, $counter), $user_ID);
		} else {
			return $tfa->encryptString($tfa->generateOTP($user_ID, $code, 8, $counter), $user_ID);
		}
	}

	public function simba_tfa_adding_private_key($alg, $user_ID, $code, $tfa) {
		if ($alg == 'hotp') {
			//Add some emergency codes as well. Take 8 digits from events 1,2,3
			update_user_meta($user_ID, 'simba_tfa_emergency_codes_64', array(
				$this->get_otp($alg, $user_ID, $code, $tfa, 1),
				$this->get_otp($alg, $user_ID, $code, $tfa, 2),
				$this->get_otp($alg, $user_ID, $code, $tfa, 3),
			));
		} else {
			//Add some emergency codes as well. Take 8 digits from time window 1,2,3
			update_user_meta($user_ID, 'simba_tfa_emergency_codes_64', array(
				$this->get_otp($alg, $user_ID, $code, $tfa, 1),
				$this->get_otp($alg, $user_ID, $code, $tfa, $tfa->time_window_size+1),
				$this->get_otp($alg, $user_ID, $code, $tfa, $tfa->time_window_size*2+1),
			));
		}
	}

	private function get_emergency_codes_as_string($user_id = false) {
		global $current_user, $simba_two_factor_authentication;
		if (false == $user_id) $user_id = $current_user->ID;
		if (empty($this->tfa)) $this->tfa = $simba_two_factor_authentication->getTFA();
		$emergencies = get_user_meta($user_id, 'simba_tfa_emergency_codes_64', true);
		return $this->tfa->getPanicCodesString($emergencies, $user_id);
	}

	public function simba_tfa_emergency_codes_user_settings($m, $user_id) {

		$m = __("You have three emergency codes that can be used. Keep them in a safe place; if you lose your authentication device, then you can use them to log in.", SIMBA_TFA_TEXT_DOMAIN).' '.__("These can only be used once each - after using them all, you must reset your private key.", SIMBA_TFA_TEXT_DOMAIN);
		$m .= '<br><br>';
		$m .= '<strong>'.__('Your emergency codes are:', SIMBA_TFA_TEXT_DOMAIN).'</strong> '.$this->get_emergency_codes_as_string($user_id);

		return $m;
	}

	public function simba_tfa_fetch_assort_vars($vars, $tfa, $current_user) {
		$emergencies = get_user_meta($current_user->ID, 'simba_tfa_emergency_codes_64', true);
		$emergency_str = $tfa->getPanicCodesString($emergencies, $current_user->ID);
		$vars['emergency_str'] = $emergency_str;
		return $vars;
	}

}

global $simba_two_factor_authentication_premium;
$simba_two_factor_authentication_premium = new Simba_Two_Factor_Authentication_Premium();
