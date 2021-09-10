<?php
/*
Plugin Name: SportsPress Team Assignments
Plugin URI: https://themeboy.com/
Description: Add team assignments support to SportsPress.
Author: Savvas
Author URI: https://themeboy.com/
Version: 2.8.0
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'SportsPress_Team_Assignments' ) ) :
/**
 * Main SportsPress Team Assignments Class
 *
 * @class SportsPress_Team_Assignments
 * @version	2.8.0
 */
class SportsPress_Team_Assignments {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Actions
		//add_action( 'sportspress_process_sp_event_meta', array( $this, 'save' ) );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TEAM_ASSIGNMENTS_VERSION' ) )
			define( 'SP_TEAM_ASSIGNMENTS_VERSION', '2.8.0' );
		if ( !defined( 'SP_TEAM_ASSIGNMENTS_URL' ) )
			define( 'SP_TEAM_ASSIGNMENTS_URL', plugin_dir_url( __FILE__ ) );
		if ( !defined( 'SP_TEAM_ASSIGNMENTS_DIR' ) )
			define( 'SP_TEAM_ASSIGNMENTS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add meta boxes to trophies.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_team']['assignments'] = array(
					'title' => __( 'Team Assignments', 'sportspress' ),
					'save' => 'SP_Meta_Box_Team_Assignments::save',
					'output' => 'SP_Meta_Box_Team_Assignments::output',
					'context' => 'normal',
					'priority' => 'default',
				);
		return $meta_boxes;
	}
}
endif;

new SportsPress_Team_Assignments();
