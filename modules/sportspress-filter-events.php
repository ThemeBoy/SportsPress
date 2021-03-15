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
		
		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ), 11 );
		add_filter( 'parse_query', array( $this, 'filters_query' ), 11 );

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
	
	public function filters() {
		global $typenow, $wp_query;

		if ( $typenow != 'sp_event' )
			return;
		
		$selected = isset( $_REQUEST['today_events'] ) ? $_REQUEST['today_events'] : null;
		
		echo '<input type="checkbox"  id="today_events" name="today_events" value="1" ' . checked( $selected, 1, false ) .' /><label for="today_events"> Show only events need results </label>';
	}
	
  /**
   * Filter in admin based on options
   *
   * @param mixed $query
   */
	public function filters_query( $query ) {
		global $typenow, $wp_query;

		if ( $typenow == 'sp_event' ) {
			
			if ( isset( $_GET['today_events'] ) && $_GET['today_events'] == '1' ) {
				$query->query_vars['post_status']  = 'publish';
				$query->query_vars['meta_query'][] = array(
									'key'     => 'sp_results',
									'value'   => 'outcome',
									'compare' => 'NOT LIKE',
									);
			}

		}
	}

}

endif;

new SportsPress_Filter_Events();
