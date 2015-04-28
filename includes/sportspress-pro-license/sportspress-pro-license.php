<?php
/*
Plugin Name: SportsPress Pro License
Plugin URI: http://sportspresspro.com/
Description: Allow SportsPress Pro to be updated directly from the dashboard.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Pro_License' ) ) :

/**
 * Main SportsPress Pro License Class
 *
 * @class SportsPress_Pro_License
 * @version	1.8.3
 */
class SportsPress_Pro_License {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Check for updates
		add_action( 'sportspress_pro_loaded', array( $this, 'check_for_updates' ) );

		// Add settings
		add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_PRO_LICENSE_VERSION' ) )
			define( 'SP_PRO_LICENSE_VERSION', '1.8.3' );

		if ( !defined( 'SP_PRO_LICENSE_URL' ) )
			define( 'SP_PRO_LICENSE_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PRO_LICENSE_DIR' ) )
			define( 'SP_PRO_LICENSE_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Check for updates.
	 */
	public function check_for_updates() {
		$license_key = get_option( 'sportspress_pro_license_key' );
		if ( $license_key ) {
			require_once( 'includes/class-sp-pro-updater.php' );
			new SP_Pro_Updater( 'http://wp-updates.com/api/2/plugin', plugin_basename( SP_PRO_PLUGIN_FILE ), $license_key );
		}
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-license.php' );
		return $settings;
	}
}

endif;

new SportsPress_Pro_License();
