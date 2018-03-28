<?php
/*
Plugin Name: SportsPress Event Charts
Plugin URI: http://tboy.co/pro
Description: Add Event Charts to SportsPress events.
Author: Savvas
Author URI: http://themeboy.com
Version: 2.6.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Charts' ) ) :

/**
 * Main SportsPress Event Charts Class
 *
 * @class SportsPress_Event_Charts
 * @version	2.6.0
 *
 */
class SportsPress_Event_Charts {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_EVENT_CHARTS_VERSION' ) )
			define( 'SP_EVENT_CHARTS_VERSION', '2.2' );

		if ( !defined( 'SP_EVENT_CHARTS_URL' ) )
			define( 'SP_EVENT_CHARTS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_CHARTS_DIR' ) )
			define( 'SP_EVENT_CHARTS_DIR', plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( get_option( 'sportspress_load_event_charts_module', 'no' ) == 'yes' ) {
	new SportsPress_Event_Charts();
}
