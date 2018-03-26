<?php
/*
Plugin Name: SportsPress Player Assignments
Plugin URI: http://themeboy.com/
Description: Add player assignments support to SportsPress.
Author: Savvas
Author URI: http://themeboy.com/
Version: 2.6.0
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'SportsPress_Player_Assignments' ) ) :
/**
 * Main SportsPress Player Assignments Class
 *
 * @class SportsPress_Player_Assignments
 * @version	2.6.0
 */
class SportsPress_Player_Assignments {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		// Actions
		// Filters
	}
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_VERSION' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_VERSION', '2.6.0' );
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_URL' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_URL', plugin_dir_url( __FILE__ ) );
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_DIR' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_DIR', plugin_dir_path( __FILE__ ) );
	}
}
endif;
if ( get_option( 'sportspress_load_player_assignments_module', 'yes' ) == 'yes' ) { //Is it needed?
	new SportsPress_Player_Assignments();
}