<?php
/*
Plugin Name: SportsPress API
Plugin URI: http://themeboy.com/
Description: REST API for SportsPress
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_API' ) ) :

/**
 * Main SportsPress API Class
 *
 * @class SportsPress_API
 * @version	2.0
 */
class SportsPress_API {

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
		if ( !defined( 'SP_API_VERSION' ) )
			define( 'SP_API_VERSION', '2.0' );

		if ( !defined( 'SP_API_URL' ) )
			define( 'SP_API_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_API_DIR' ) )
			define( 'SP_API_DIR', plugin_dir_path( __FILE__ ) );
	}
}

endif;

new SportsPress_API();
