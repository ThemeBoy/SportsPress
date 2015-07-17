<?php
/*
Plugin Name: SportsPress: Updater
Plugin URI: http://tboy.co/pro
Description: Allow SportsPress Pro to be updated directly from the dashboard.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.8.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Updater' ) ) :

/**
 * Main SportsPress Updater Class
 *
 * @class SportsPress_Updater
 * @version	1.8.6
 */
class SportsPress_Updater {

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
		if ( !defined( 'SP_UPDATER_VERSION' ) )
			define( 'SP_UPDATER_VERSION', '1.8.6' );

		if ( !defined( 'SP_UPDATER_URL' ) )
			define( 'SP_UPDATER_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_UPDATER_DIR' ) )
			define( 'SP_UPDATER_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Check for updates.
	 */
	public function check_for_updates() {
		$license_key = get_site_option( 'sportspress_pro_license_key' );
		if ( $license_key ) {
			require_once( 'includes/class-sp-updater.php' );
			new SP_Updater( 'http://wp-updates.com/api/2/plugin', plugin_basename( SP_PRO_PLUGIN_FILE ), $license_key );
		}
	}

	/**
	 * Add settings page
	 */
	public static function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-license.php' );
		return $settings;
	}
}

endif;

new SportsPress_Updater();
