<?php
/*
Plugin Name: SportsPress Individual Mode
Plugin URI: http://themeboy.com/
Description: Modify SportsPress to work with individual (player vs player) sports.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Individual_Mode' ) ) :

/**
 * Main SportsPress Individual Mode Class
 *
 * @class SportsPress_Individual_Mode
 * @version	1.9
 */
class SportsPress_Individual_Mode {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		//add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		//add_action( 'sportspress_process_sp_event_meta', array( $this, 'save_player_meta' ), 99, 2 );

		// Filters
		add_filter( 'gettext', array( $this, 'gettext' ), 99, 3 );
		add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		add_filter( 'sportspress_player_team_statistics', '__return_false' );
		add_filter( 'sportspress_has_teams', '__return_false' );
	}
	/** 
	 * Modify all team-related strings for players.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( 'sportspress' !== $domain ) return $translated_text;

		switch ( $untranslated_text ) {
			case 'Teams':
				return __( 'Players', 'sportspress' );
				break;
			case 'Team':
				return __( 'Player', 'sportspress' );
				break;
			case 'teams':
				return __( 'players', 'sportspress' );
				break;
		}
		
		return $translated_text;
	}

	/**
	 * Modify all team post type queries for players.
	 */
	public function pre_get_posts( $query ) {
		if ( 'sp_team' !== $query->get( 'post_type' ) ) return $query;

		$query->set( 'post_type', 'sp_player' );

		return $query;
	}
}

endif;

if ( get_option( 'sportspress_load_individual_mode_module', 'no' ) == 'yes' ) {
	new SportsPress_Individual_Mode();
}
