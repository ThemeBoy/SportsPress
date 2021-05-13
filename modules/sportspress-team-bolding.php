<?php
/*
Plugin Name: SportsPress Team Marking
Plugin URI: https://themeboy.com/
Description: Add an option to bold or/and highlight a team based on the outcome of the event.
Author: ThemeBoy
Author URI: https://themeboy.com/
Version: 2.8.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Team_Bolding' ) ) :

/**
 * Main SportsPress Team Marking Class
 *
 * @class SportsPress_Team_Marking
 * @version	2.8.0
 */
class SportsPress_Team_Marking {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Filters
		add_action( 'sportspress_before_single_calendar', array( $this, 'add_team_css' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TEAM_BOLDING_VERSION' ) )
			define( 'SP_TEAM_BOLDING_VERSION', '2.8.0' );

		if ( !defined( 'SP_TEAM_BOLDING_URL' ) )
			define( 'SP_TEAM_BOLDING_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TEAM_BOLDING_DIR' ) )
			define( 'SP_TEAM_BOLDING_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add meta box to events.
	 *
	 * @return array
	 */
	public function add_team_css() {
		if ( ! isset( $id ) )
			$id = get_the_ID();
		$args = array(
					'numberposts' => 10,
					'post_type'   => 'sp_outcome',
					);
		$outcomes = get_posts( $args );
		$inline_css = null;
		foreach ( $outcomes as $outcome ) {
			$inline_css .= '<style> .sp-event-list span.sp-outcome-' . $outcome->post_name . ' {';
			$team_bolding = get_post_meta( $outcome->ID, 'sp_team_bolding', true );
			$team_highlight = get_post_meta( $outcome->ID, 'sp_team_highlight', true );
			if ( $team_bolding ) {
				$inline_css .= 'font-weight: bold;';
			}
			if ( $team_highlight ) {
				$highlight_color = get_post_meta( $outcome->ID, 'sp_color', true );
				$inline_css .= 'color: ' . $highlight_color . ';';
			}
			$inline_css .= '} </style>';
		}
		echo $inline_css;
	}
}

endif;

new SportsPress_Team_Marking();
