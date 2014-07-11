<?php
/*
Plugin Name: SportsPress Referees
Plugin URI: http://themeboy.com/sportspress/referees/
Description: Add referees to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main SportsPress Referees Class
 *
 * @class SportsPress_Referees
 * @version	1.0
 */

class SportsPress_Referees {

	public function __construct() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! is_plugin_active( 'sportspress/sportspress.php' ) )
			return;

		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Hooks
		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
		add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_REFEREES_VERSION' ) )
			define( 'SP_REFEREES_VERSION', '1.0' );

		if ( !defined( 'SP_REFEREES_URL' ) )
			define( 'SP_REFEREES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_REFEREES_DIR' ) )
			define( 'SP_REFEREES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();

		// Get label
		$this->label = get_option( 'sportspress_branding_label' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress' );
		
		// Global + Frontend Locale
		load_plugin_textdomain( 'sportspress', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
	}

	/** 
	 * Text filter.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		global $pagenow;

		if ( $pagenow != 'plugins.php' && $domain == 'sportspress' && ! empty( $this->label ) && strpos( $translated_text, 'SportsPress' ) !== false ):
				$translated_text = str_replace( 'SportsPress', $this->label, $translated_text );
			endif;
		endif;
		
		return $translated_text;
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-branding.php' );
		return $settings;
	}

	/**
	 * Enqueue styles
	 */
	public function admin_enqueue_scripts() {
		global $wp_scripts;

		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'toplevel_page_sportspress' ) ) ) {
			wp_enqueue_style( 'sportspress-branding-admin', SP_REFEREES_URL . 'css/admin.css', array(), SP_REFEREES_VERSION );
			wp_enqueue_script( 'sportspress-branding-admin', SP_REFEREES_URL . 'js/admin.js', array( 'jquery' ), SP_REFEREES_VERSION );
			wp_enqueue_media();
			wp_enqueue_script( 'custom-header' );
		}
	}
}

new SportsPress_Referees();
