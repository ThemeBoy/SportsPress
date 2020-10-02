<?php
/*
Plugin Name: SportsPress Past Meetings
Plugin URI: http://themeboy.com/
Description: Show past meetings between two teams of a SportsPress event.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Past_Meetings' ) ) :

/**
 * Main SportsPress Past Meetings Class
 *
 * @class SportsPress_Past_Meetings
 * @version	2.6.9
 */
class SportsPress_Past_Meetings {

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
		add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_PAST_MEETINGS_VERSION' ) )
			define( 'SP_PAST_MEETINGS_VERSION', '2.6.9' );

		if ( !defined( 'SP_PAST_MEETINGS_URL' ) )
			define( 'SP_PAST_MEETINGS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PAST_MEETINGS_DIR' ) )
			define( 'SP_PAST_MEETINGS_DIR', plugin_dir_path( __FILE__ ) );
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
			'default' => 'no',
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
		$format = get_option( 'sportspress_past_meetings_format', 'blocks' );
		$teams = get_post_meta( get_the_ID(),'sp_team' );
		$show_event = get_option( 'sportspress_past_meetings_show_event', 'yes' ) == 'yes' ? true : false;
		$show_time = get_option( 'sportspress_past_meetings_show_time', 'yes' ) == 'yes' ? true : false;
		$show_results = get_option( 'sportspress_past_meetings_show_results', 'yes' ) == 'yes' ? true : false;
		$show_league = get_option( 'sportspress_past_meetings_show_league', 'no' ) == 'yes' ? true : false;
		$show_season = get_option( 'sportspress_past_meetings_show_season', 'no' ) == 'yes' ? true : false;
		$show_venue = get_option( 'sportspress_past_meetings_show_venue', 'no' ) == 'yes' ? true : false;
		
		$columns = array();
		if ( $show_event )
			$columns[] = 'event';
		if ( $show_time )
			$columns[] = 'time';
		if ( $show_results )
			$columns[] = 'results';
		if ( $show_league )
			$columns[] = 'league';
		if ( $show_season )
			$columns[] = 'season';
		if ( $show_venue )
			$columns[] = 'venue';
		
		if ( 'list' === $format ):
			sp_get_template( 'event-list.php', array(
				'title' => __( 'Past Meetings', 'sportspress' ),
				'show_title' => true,
				'teams_past' => $teams,
				'date_before' => get_post_time('Y-m-d', true),
				'title_format' => 'homeaway',
				'time_format' => 'separate',
				'columns' => $columns,
				'order' => 'DESC',
				'hide_if_empty' => true,
			) );
		else:
			sp_get_template( 'event-blocks.php', array(
				'title' => __( 'Past Meetings', 'sportspress' ),
				'show_title' => true,
				'teams_past' => $teams,
				'date_before' => get_post_time('Y-m-d', true),
				'order' => 'DESC',
				'hide_if_empty' => true,
			) );
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
	
	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Past Meetings', 'sportspress' ), 'type' => 'title', 'id' => 'past_meetings_options' ),
			),

			apply_filters( 'sportspress_past_meetings_options', array(
				array(
					'title' 	=> __( 'Layout', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_format',
					'default'	=> 'blocks',
					'type' 		=> 'radio',
					'options' => array(
						'blocks'=> __( 'Blocks', 'sportspress' ),
						'list'	=> __( 'List', 'sportspress' ),
					),
				),
				array(
					'title'     => __( 'Details', 'sportspress' ),
					'desc' 		=> __( 'Display event', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_show_event',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> 'start',
				),
				array(
					'desc' 		=> __( 'Display time', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_show_time',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),
				array(
					'desc' 		=> __( 'Display results', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_show_results',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),
				array(
					'desc' 		=> __( 'Display league', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_show_league',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),
				array(
					'desc' 		=> __( 'Display season', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_show_season',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),
				array(
					'desc' 		=> __( 'Display venue', 'sportspress' ),
					'id' 		=> 'sportspress_past_meetings_show_venue',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'past_meetings_options' ),
			)
		);
		return $settings;
	}

}

endif;

if ( get_option( 'sportspress_load_past_meetings_module', 'yes' ) == 'yes' ) {
	new SportsPress_Past_Meetings();
}
