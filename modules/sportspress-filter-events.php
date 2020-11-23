<?php
/*
Plugin Name: SportsPress Filter Events (Admin)
Plugin URI: http://themeboy.com/
Description: More filters for events in admin edit page.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.8
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Filter_Events' ) ) :

/**
 * Main SportsPress Filter Events Class
 *
 * @class SportsPress_Filter_Events
 * @version	2.8
 */
class SportsPress_Filter_Events {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_FILTER_EVENTS_VERSION' ) )
			define( 'SP_FILTER_EVENTS_VERSION', '2.8' );

		if ( !defined( 'SP_FILTER_EVENTS_URL' ) )
			define( 'SP_FILTER_EVENTS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_FILTER_EVENTS_DIR' ) )
			define( 'SP_FILTER_EVENTS_DIR', plugin_dir_path( __FILE__ ) );
	}

}

endif;

new SportsPress_Filter_Events();
