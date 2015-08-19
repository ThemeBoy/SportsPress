<?php
/*
Plugin Name: SportsPress Event Videos
Plugin URI: http://themeboy.com/
Description: Add videos to SportsPress events.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Videos' ) ) :

/**
 * Main SportsPress Event Videos Class
 *
 * @class SportsPress_Event_Videos
 * @version	1.8.3
 */
class SportsPress_Event_Videos {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_box' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_EVENT_VIDEOS_VERSION' ) )
			define( 'SP_EVENT_VIDEOS_VERSION', '1.8.3' );

		if ( !defined( 'SP_EVENT_VIDEOS_URL' ) )
			define( 'SP_EVENT_VIDEOS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_VIDEOS_DIR' ) )
			define( 'SP_EVENT_VIDEOS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add meta box to events.
	 *
	 * @return array
	 */
	public function add_meta_box( $meta_boxes ) {
		$meta_boxes['sp_event']['video'] = array(
			'title' => __( 'Video', 'sportspress' ),
			'output' => 'SP_Meta_Box_Event_Video::output',
			'save' => 'SP_Meta_Box_Event_Video::save',
			'context' => 'side',
			'priority' => 'low',
		);
		return $meta_boxes;
	}
}

endif;

new SportsPress_Event_Videos();
