<?php
/*
Plugin Name: SportsPress Player Transfers
Plugin URI: http://tboy.co/pro
Description: Add a Player Transfers to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Player_Transfers' ) ) :

/**
 * Main SportsPress Player Transfers Class
 *
 * @class SportsPress_Player_Transfers
 * @version	2.6.0
 *
 */
class SportsPress_Player_Transfers {

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
		if ( !defined( 'SP_PLAYER_TRANSFERS_VERSION' ) )
			define( 'SP_PLAYER_TRANSFERS_VERSION', '2.6.0' );

		if ( !defined( 'SP_PLAYER_TRANSFERS_URL' ) )
			define( 'SP_PLAYER_TRANSFERS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PLAYER_TRANSFERS_DIR' ) )
			define( 'SP_PLAYER_TRANSFERS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
}
endif;

if ( get_option( 'sportspress_load_player_transfers_module', 'no' ) == 'yes' ) {
	new SportsPress_Player_Transfers();
}
