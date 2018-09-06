<?php
/*
Plugin Name: SportsPress Event Past Meetings
Plugin URI: http://themeboy.com/
Description: Show past meetings between two teams of a SportsPress Event.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Past_Meetings' ) ) :

/**
 * Main SportsPress Event Past Meetings Class
 *
 * @class SportsPress_Event_Past_Meetings
 * @version	2.7.0
 */
class SportsPress_Event_Past_Meetings {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		
		// Filters
		add_filter( 'sportspress_event_templates', array( $this, 'templates' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_EVENT_PAST_MEETINGS_VERSION' ) )
			define( 'SP_EVENT_PAST_MEETINGS_VERSION', '2.7.0' );

		if ( !defined( 'SP_EVENT_PAST_MEETINGS_URL' ) )
			define( 'SP_EVENT_PAST_MEETINGS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_PAST_MEETINGS_DIR' ) )
			define( 'SP_EVENT_PAST_MEETINGS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add templates to event layout.
	 *
	 * @return array
	 */
	public function templates( $templates = array() ) {
		$templates['past_meetings'] = array(
			'title' => __( 'Past Meetings', 'sportspress' ),
			'option' => 'sportspress_event_show_past_meetings',
			'action' => array( $this, 'output' ),
			'default' => 'yes',
		);
		
		return $templates;
	}
	
	/**
	 * Output Past Meetings.
	 *
	 * @access public
	 * @return void
	 */
	public function output() {
		// Get timelines format option
		$format = get_option( 'sportspress_team_events_format', 'blocks' );
		if ( 'calendar' === $format ):
			sp_get_template( 'event-calendar.php', array( 'team' => $id ) );
		elseif ( 'list' === $format ):
			sp_get_template( 'event-list.php', array(
				'team' => $id,
				'league' => apply_filters( 'sp_team_events_league', 0 ),
				'season' => apply_filters( 'sp_team_events_season', 0 ),
				'title_format' => 'homeaway',
				'time_format' => 'separate',
				'columns' => array( 'event', 'time', 'results' ),
				'order' => 'DESC',
			) );
		else:
			sp_get_template( 'event-fixtures-results.php', array( 'team' => $id ) );
		endif;
	}
	
	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Past Meetings', 'sportspress' ),
		) );
	}

}

endif;

new SportsPress_Event_Past_Meetings();
