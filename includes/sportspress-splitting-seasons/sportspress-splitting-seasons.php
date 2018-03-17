<?php
/*
Plugin Name: SportsPress Splitting Seasons
Plugin URI: http://tboy.co/pro
Description: Add Splitting Seasons (Mid-Season Transfers) to SportsPress players.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Splitting_Seasons' ) ) :

/**
 * Main SportsPress Splitting Seasons Class
 *
 * @class SportsPress_Splitting_Seasons
 * @version	2.6.0
 *
 */
class SportsPress_Splitting_Seasons {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		//add_filter( 'sportspress_event_templates', array( $this, 'templates' ) );
	    //add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		//add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_SPLITTING_SEASONS_VERSION' ) )
			define( 'SP_SPLITTING_SEASONS_VERSION', '2.6.0' );

		if ( !defined( 'SP_SPLITTING_SEASONS_URL' ) )
			define( 'SP_SPLITTING_SEASONS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_SPLITTING_SEASONS_DIR' ) )
			define( 'SP_SPLITTING_SEASONS_DIR', plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( get_option( 'sportspress_load_splitting_seasons_module', 'yes' ) == 'yes' ) {
	new SportsPress_Splitting_Seasons();
}
