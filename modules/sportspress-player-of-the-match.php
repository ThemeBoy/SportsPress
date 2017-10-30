<?php
/*
Plugin Name: SportsPress Player Of The Match
Plugin URI: http://themeboy.com/
Description: Add player of the match to SportsPress events.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Player_Of_The_Match' ) ) :

/**
 * Main SportsPress Player Of The Match Class
 *
 * @class SportsPress_Player_Of_The_Match
 * @version	2.5
 */
class SportsPress_Player_Of_The_Match {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_PLAYER_OF_THE_MATCH_VERSION' ) )
			define( 'SP_PLAYER_OF_THE_MATCH_VERSION', '2.5' );

		if ( !defined( 'SP_PLAYER_OF_THE_MATCH_URL' ) )
			define( 'SP_PLAYER_OF_THE_MATCH_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PLAYER_OF_THE_MATCH_DIR' ) )
			define( 'SP_PLAYER_OF_THE_MATCH_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Player of the Match', 'sportspress' ),
		) );
	}
}

endif;

new SportsPress_Player_Of_The_Match();
