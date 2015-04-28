<?php
/*
Plugin Name: SportsPress Pro Updater
Plugin URI: http://sportspresspro.com/
Description: Allow SportsPress Pro to be updated directly from the dashboard.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Pro_Updater' ) ) :

/**
 * Main SportsPress Pro Updater Class
 *
 * @class SportsPress_Pro_Updater
 * @version	1.8.3
 */
class SportsPress_Pro_Updater {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->check();

		// Hooks
		//add_action( 'admin_init', 'edd_sl_sample_plugin_updater', 0 );

		//add_filter( 'sportspress_general_options', array( $this, 'add_options' ) );
		//add_action( 'sportspress_save_general_settings', array( $this, 'activate' ) );
		add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_PRO_UPDATER_VERSION' ) )
			define( 'SP_PRO_UPDATER_VERSION', '1.8.3' );

		if ( !defined( 'SP_PRO_UPDATER_URL' ) )
			define( 'SP_PRO_UPDATER_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PRO_UPDATER_DIR' ) )
			define( 'SP_PRO_UPDATER_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Check for updates.
	 */
	private function check() {
		$license_key = '77e2f36e343cdbcce2c88e3113eec2af1cf824da';
		require_once( 'includes/wp-updates-plugin.php' );
		new WPUpdatesPluginUpdater_1013( 'http://wp-updates.com/api/2/plugin', plugin_basename( SP_PRO_PLUGIN_FILE ), $license_key );
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-license.php' );
		return $settings;
	}

	/**
	 * Add license key field to general options.
	 *
	 * @return array
	 */
	public function add_options( $options ) {
		array_unshift( $options, array(
			'title' 	=> __( 'License Key', 'sportspress' ),
			'id' 		=> 'sportspress_pro_license_key',
			'class' 	=> 'regular-text',
			'type' 		=> 'text',
		) );
		return $options;
	}
}

endif;

new SportsPress_Pro_Updater();
