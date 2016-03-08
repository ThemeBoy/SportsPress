<?php
/*
Plugin Name: SportsPress Updater
Plugin URI: http://tboy.co/pro
Description: Allow SportsPress Pro to be updated directly from the dashboard.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.9.16
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Updater' ) ) :

/**
 * Main SportsPress Updater Class
 *
 * @class SportsPress_Updater
 * @version	1.9.16
 */
class SportsPress_Updater {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();
		
		// Check for updates
		add_action( 'admin_init', array( $this, 'check_for_updates' ), 0 );

		// Display on settings page
		add_action( 'sportspress_modules_sidebar', array( $this, 'sidebar' ) );
		add_action( 'sportspress_settings_save_modules', array( $this, 'activate_license' ) );
		add_action( 'sportspress_settings_save_modules', array( $this, 'deactivate_license' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_UPDATER_VERSION' ) )
			define( 'SP_UPDATER_VERSION', '1.9.16' );

		if ( !defined( 'SP_UPDATER_URL' ) )
			define( 'SP_UPDATER_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_UPDATER_DIR' ) )
			define( 'SP_UPDATER_DIR', plugin_dir_path( __FILE__ ) );

		if ( !defined( 'SP_UPDATER_STORE_URL' ) )
			define( 'SP_UPDATER_STORE_URL', 'https://account.themeboy.com' );

		if ( !defined( 'SP_UPDATER_ITEM_NAME' ) )
			define( 'SP_UPDATER_ITEM_NAME', 'SportsPress Pro' );
	}

	/**
	 * Include required files
	*/
	private function includes() {
		if ( !class_exists( 'SP_Plugin_Updater' ) ) {
			// load our custom updater
			include( dirname( __FILE__ ) . '/includes/class-sp-plugin-updater.php' );
		}
	}

	/**
	 * Add license to sidebar
	*/
	public function sidebar() {
		if (
			( ! is_multisite() && current_user_can( 'manage_options' ) ) ||
			( is_multisite() && current_user_can( 'manage_network_options' ) )
		) {
			$key 	= get_site_option( 'sportspress_pro_license_key' );
			$key 	= trim( $key );
			$status = get_site_option( 'sportspress_pro_license_status' );
			
			if ( strlen( $key ) > 35 ) {
				$key 	= '';
				$status = false;
			}
		} else {
			return;
		}
		?>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr><th>
					<strong><?php _e( 'License', 'sportspress' ); ?></strong>
				</th></tr>
			</thead>
			<tbody>
				<tr><td>
					<p>
						<strong><?php _e( 'License Key', 'sportspress' ); ?></strong>
						<?php if ( $key && $status && 'valid' == $status ) { ?>
							<i class="dashicons dashicons-yes sp-desc-active"></i>
						<?php } else { ?>
							<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Enter the license key from your purchase receipt.', 'sportspress' ); ?>"></i>
						<?php } ?>
					</p>
					<?php if ( false !== $status && 'valid' == $status ) { ?>
						<p>
							<input type="text" name="sportspress_pro_license_key" class="widefat" value="<?php esc_attr_e( $key ); ?>" readonly="readonly">
						</p>
						<p>
							<?php wp_nonce_field( 'sp_license_nonce', 'sp_license_nonce' ); ?>
							<input name="sp_license_deactivate" class="button button-secondary" type="submit" value="<?php esc_attr_e( 'Deactivate', 'sportspress' ); ?>" />
						</p>
					<?php } else { ?>
						<p>
							<input type="text" name="sportspress_pro_license_key" class="widefat">
						</p>
						<p>
							<?php wp_nonce_field( 'sp_license_nonce', 'sp_license_nonce' ); ?>
							<input name="sp_license_activate" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Activate', 'sportspress' ); ?>" />
						</p>
						<p class="sp-module-actions">
							<span><?php _e( 'Need a license key?', 'sportspress' ); ?></span>
							<a class="button" href="http://tboy.co/pro" target="_blank"><?php _e( 'Purchase', 'sportspress' ); ?></a>
						</p>
					<?php } ?>
				</td></tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Check for updates
	 */
	public function check_for_updates() {
		// retrieve our license key from the DB
		$license_key = trim( get_site_option( 'sportspress_pro_license_key' ) );

		// setup the updater
		$edd_updater = new SP_Plugin_Updater( SP_UPDATER_STORE_URL, SP_PRO_PLUGIN_FILE, array(
				'version' 	=> SP_PRO_VERSION, 			// current version number
				'license' 	=> $license_key, 			// license key (used get_site_option above to retrieve from DB)
				'item_name' => SP_UPDATER_ITEM_NAME, 	// name of this plugin
				'author' 	=> 'ThemeBoy' 				// author of this plugin
			)
		);
	}

	/**
	 * Activate license
	 */
	public function activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['sp_license_activate'] ) && isset( $_POST['sportspress_pro_license_key'] ) ) {

			// Prevent default module saving
			remove_all_actions( 'sportspress_settings_save_modules' );
			unset( $_POST['sportspress_update_modules'] );

			// run a quick security check
		 	if ( ! check_admin_referer( 'sp_license_nonce', 'sp_license_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( $_POST['sportspress_pro_license_key'] );

			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( SP_UPDATER_ITEM_NAME ), // the name of our product in EDD
				'url'       => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( SP_UPDATER_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// Make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
				return false;
			}

			// Decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// Update license status
			update_site_option( 'sportspress_pro_license_status', $license_data->license );
			
			// Update License or display error
			if ( 'valid' == $license_data->license ) {
				update_site_option( 'sportspress_pro_license_key', $license );
				SP_Admin_Settings::add_override( __( 'License activated.', 'sportspress' ) );
			} else {
				SP_Admin_Settings::add_error( __( 'License invalid.', 'sportspress' ) );
			}
		}
	}

	/**
	 * Deactivate license
	 */
	public function deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['sp_license_deactivate'] ) && isset( $_POST['sportspress_pro_license_key'] ) ) {

			// Prevent default module saving
			remove_all_actions( 'sportspress_settings_save_modules' );
			unset( $_POST['sportspress_update_modules'] );

			// run a quick security check
		 	if ( ! check_admin_referer( 'sp_license_nonce', 'sp_license_nonce' ) )
				return; // get out if we didn't click the Deactivate button

			// retrieve the license from the database
			$license = trim( $_POST['sportspress_pro_license_key'] );

			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'deactivate_license',
				'license' 	=> $license,
				'item_name' => urlencode( SP_UPDATER_ITEM_NAME ), // the name of our product in EDD
				'url'       => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( SP_UPDATER_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' ) {
				delete_site_option( 'sportspress_pro_license_status' );
				SP_Admin_Settings::add_override( __( 'License deactivated.', 'sportspress' ) );
			} else {
				SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
			}
		}
	}
}

endif;

new SportsPress_Updater();
