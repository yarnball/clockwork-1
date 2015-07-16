<?php
/**
 * Plugin Name: Clockwork API Example
 * Plugin URI: http://github.com/chrismccoy/clockwork
 * Description: Send a Text Message via an Input form using the Clockwork API
 * Version: 1.0
 * Author: Chris McCoy
 * Author URI: http://github.com/chrismccoy

 * @copyright 2015
 * @author Chris McCoy
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package Clockwork_SMS
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Initiate Clockwork_SMS Class on plugins_loaded
 *
 * @since 1.0
 */

if ( !function_exists( 'clockwork_sms' ) ) {

	function clockwork_sms() {
		$clockwork_sms = new Clockwork_SMS();
	}

	add_action( 'plugins_loaded', 'clockwork_sms' );
}

/**
 * Clockwork SMS Class for ajax, shortcode and clockwork api
 *
 * @since 1.0
 */

if( !class_exists( 'Clockwork_SMS' ) ) {

	class Clockwork_SMS {

		/**
 		* Hook into hooks for ajax, shortcode, and clockwork api
 		*
 		* @since 1.0
 		*/
		public function __construct() {

			define( 'CLOCKWORK_DIR', plugin_dir_path( __FILE__ ) );

			require(CLOCKWORK_DIR . 'clockwork/class-Clockwork.php');
			require(CLOCKWORK_DIR . 'clockwork/class-ClockworkException.php');

			register_activation_hook( __FILE__, array( $this, 'activation'));

			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			add_action( 'wp_ajax_clockwork_send_sms', array( $this, 'wp_ajax_clockwork_send_sms' ));
			add_action( 'wp_ajax_nopriv_clockwork_send_sms', array( $this, 'wp_ajax_clockwork_send_sms'));
			add_shortcode( 'smsform', array( $this, 'smsform' ) );
			add_action('admin_menu', array( $this, 'settings_page'));
		}

		/**
	 	* Activation Hook to update_option for api key and message
	 	*
	 	*/
		public function activation() {
			update_option('clockwork_api_key', '', true);
			update_option('clockwork_default_message', '', true);
		}

		/**
	 	* Add Settings Page
	 	*
	 	*/
		public function settings_page() {
			add_submenu_page( 'options-general.php', 'Clockwork SMS', 'Clockwork SMS', 'manage_options', 'clockwork-settings', array( $this, 'api_settings_options' ));
		}

		/**
	 	* Callback for Settings Page
	 	*
	 	*/
		public function api_settings_options() {

			$notice_update = false;

			if (isset($_POST['clockwork-settings']) && check_admin_referer('clockwork-settings')) {
				update_option('clockwork_api_key', $_POST['clockwork_api_key'], true);
				update_option('clockwork_default_message', $_POST['clockwork_default_message'], true);
				$notice_update = true;
			}

			if ($notice_update == true) {
				echo '<div id="message" class="updated fade"><p><strong>'.__('Settings saved.','clockwork').'</strong></p></div>';
			}

			require(plugin_dir_path(__FILE__) . '/inc/html.inc.php');
		}

		/**
		 * enqueue ajax javascript
		 *
		 * @since 1.0
		 */
		public function wp_enqueue_scripts() {
			wp_enqueue_script('clockwork', plugins_url('js/clockwork.js', __FILE__), array( 'jquery' ), '1.0', true);
			wp_localize_script('clockwork', 'clockworkajax', array(
       				'ajaxurl' => admin_url('admin-ajax.php'),
       				'clockworkNonce' => wp_create_nonce('clockwork-nonce'),
       				'loading' => plugins_url('images/ajax-loader.gif', __FILE__)
			));
        	}

		/**
		 * html markup for the sms form
		 *
		 * @since 1.0
		 */
		public function smsform($atts, $content) {
			       $content = '<form id="clockworkform">
						<label for="phone" id="phone">Phone Number</label>
						<input type="text" style="width:35%" id="phone" name="phone" value="" />
						<input type="submit" id="submit" name="submit" value="Submit" />
      					</form>
       					<br/><br/>
					<div id="result"></div>
        			';

        			return $content;
		}

       		/**
       		* function to send a text message using the clockwork api
       		*
       		* @since 1.0
       		*/
		public function wp_ajax_clockwork_send_sms() {

                	$nonce = $_POST['clockworkNonce'];

                	if ( ! wp_verify_nonce( $nonce, 'clockwork-nonce' ) )
                        	die ( 'Access Denied!');

                	$phone = $_POST['phone'];

                	try {

				$options = array('ssl' => false);
                    		$clockwork = new Clockwork( get_option('clockwork_api_key'), $options);
                    		$message = array( 'to' => $phone, 'message' => get_option('clockwork_default_message' ));
                    		$result = $clockwork->send( $message );

                    		if($result['success']) {
                        		echo 'Message sent - ID: ' . $result['id'];
                    		} else {
                        		echo 'Message failed - Error: ' . $result['error_message'];
                    		}
            		}

            		catch (ClockworkException $e) {
                		echo 'Exception sending SMS: ' . $e->getMessage();
            		}

        		exit();
		}
	}
}
