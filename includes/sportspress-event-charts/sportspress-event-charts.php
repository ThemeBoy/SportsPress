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
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );
		
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ), 9 );
		//add_filter( 'sportspress_event_templates', array( $this, 'event_templates' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_EVENT_CHARTS_VERSION' ) )
			define( 'SP_EVENT_CHARTS_VERSION', '2.6.0' );

		if ( !defined( 'SP_EVENT_CHARTS_URL' ) )
			define( 'SP_EVENT_CHARTS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_CHARTS_DIR' ) )
			define( 'SP_EVENT_CHARTS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_event']['charts'] = array(
			'title' => __( 'Event Charts', 'sportspress' ),
			'output' => 'SP_Meta_Box_Event_Charts::output',
			'save' => 'SP_Meta_Box_Event_Charts::save',
			'context' => 'normal',
			'priority' => 'low',
		);
		return $meta_boxes;
	}
	
	/**
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'includes/class-sp-meta-box-event-charts.php' );
	}

}

endif;

if ( get_option( 'sportspress_load_event_charts_module', 'yes' ) == 'yes' ) {
	new SportsPress_Event_Charts();
}
