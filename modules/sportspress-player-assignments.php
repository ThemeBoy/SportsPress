<?php
/*
Plugin Name: SportsPress Player Assignments
Plugin URI: http://themeboy.com/
Description: Add player assignments support to SportsPress.
Author: Savvas
Author URI: http://themeboy.com/
Version: 2.7.3
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'SportsPress_Player_Assignments' ) ) :
/**
 * Main SportsPress Player Assignments Class
 *
 * @class SportsPress_Player_Assignments
 * @version	2.7.3
 */
class SportsPress_Player_Assignments {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Actions
		add_action( 'sportspress_process_sp_player_meta', array( $this, 'save' ) );

		// Filters
		add_filter( 'sportspress_player_list_args', array( $this, 'add_args' ), 10, 2 );
		add_filter( 'sportspress_player_list_players', array( $this, 'add_players' ), 10, 4 );
	}
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_VERSION' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_VERSION', '2.7.3' );
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_URL' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_URL', plugin_dir_url( __FILE__ ) );
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_DIR' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Save Additional Statistics
	 */
	public function save( $post_id ) {
		delete_post_meta( $post_id, 'sp_assignments' );
		
		$leagues = sp_array_value( $_POST, 'sp_leagues', array() );

		if ( ! is_array( $leagues ) ) return;
		
		foreach ( $leagues as $l_id => $season ) {
			if ( 0 === $l_id ) continue;
			foreach ( $season as $s_id => $team_id ) {
				if ( 0 >= $team_id ) continue;
				$serialized = intval($l_id).'_'.intval($s_id).'_'.intval($team_id);
				add_post_meta( $post_id, 'sp_assignments', $serialized, false );
			}
		}
	}
	
	/**
	 * Add args to filter out assigned players
	 */
	public function add_args( $args = array(), $team = false ) {
		if ( ! $team ) return $args;

		$tax_query = (array) sp_array_value( $args, 'tax_query', array() );
		$league_ids = array();
		$season_ids = array();

		foreach ( $tax_query as $param ) {
			if ( 'sp_league' === sp_array_value( $param, 'taxonomy' ) ) $league_ids = sp_array_value( $param, 'terms', array() );
			if ( 'sp_season' === sp_array_value( $param, 'taxonomy' ) ) $season_ids = sp_array_value( $param, 'terms', array() );
		}

		if ( empty( $league_ids ) && empty( $season_ids ) ) return $args;

		$args['meta_query'][] = array(
			'key' => 'sp_assignments',
			'value' => '',
			'compare' => 'NOT EXISTS',
		);

		$args['meta_query']['relation'] = 'AND';

		return $args;
	}
	
	/**
	 * Add assigned players to player list
	 */
	public function add_players( $players = array(), $args = array(), $team = false, $team_key = 'sp_team' ) {
		if ( ! $team ) return $players;

		$tax_query = (array) sp_array_value( $args, 'tax_query', array() );
		$league_ids = array();
		$season_ids = array();

		foreach ( $tax_query as $param ) {
			if ( 'sp_league' === sp_array_value( $param, 'taxonomy' ) ) $league_ids = sp_array_value( $param, 'terms', array() );
			if ( 'sp_season' === sp_array_value( $param, 'taxonomy' ) ) $season_ids = sp_array_value( $param, 'terms', array() );
		}

		if ( empty( $league_ids ) && empty( $season_ids ) ) return $players;

		$assignments = array();
		
		if ( !empty( $league_ids ) && !empty( $season_ids ) ) {
			foreach ( $league_ids as $l_id ) {
				foreach ( $season_ids as $s_id ) {
					if ( $team && $team != '0' ) {
						$assignments[] = $l_id.'_'.$s_id.'_'.$team;
						$compare = 'IN';
					}
				}
			}
		}
		
		if ( empty( $league_ids ) && !empty( $season_ids ) ) {
			foreach ( $season_ids as $s_id ) {
				if ( $team && $team != '0' ) {
					$assignments[] = '_'.$s_id.'_'.$team;
					$compare = 'LIKE';
				}
			}
		}
		
		if ( !empty( $league_ids ) && empty( $season_ids ) ) {
			foreach ( $league_ids as $l_id ) {
				if ( $team && $team != '0' ) {
					$assignments[] = $l_id.'_%_'.$team;
					$compare = 'LIKE';
				}
			}
		}

		if ( sizeof( $assignments ) ) {
			if ( 'IN' == $compare ) {
				$args['meta_query'] = array(
					'relation' => 'AND',

					array(
						'key' => 'sp_assignments',
						'value' => $assignments,
						'compare' => $compare,
					),

					array(
						'key' => $team_key,
						'value' => $team,
					),
				);
			}
			if ( 'LIKE' == $compare ) {
				$args['meta_query'] = array(
					'relation' => 'AND',

					array(
						'key' => $team_key,
						'value' => $team,
					),
					
					array(
						'relation' => 'OR',
					),
				);
				foreach( $assignments as $assignment ) {
					$args['meta_query'][1][] = array(							
							'key'     => 'sp_assignments',
							'value'   => $assignment,
							'compare' => $compare,
							);
				}
			}
		}

		$assigned_players = (array) get_posts( $args );

		$players = array_merge( $assigned_players, $players );

		$players = array_map( 'unserialize', array_unique( array_map( 'serialize', $players ) ) );

		foreach ( $players as $i => $player ) {
			$player->sp_number = get_post_meta( $player->ID, 'sp_number', true );
		}

		uasort( $players, array( $this, 'sort' ) );

		return $players;
	}

	public function sort( $a, $b ) {
    if ($a->sp_number == $b->sp_number) {
        return 0;
    }
    return ($a->sp_number < $b->sp_number) ? -1 : 1;
	}
}
endif;

new SportsPress_Player_Assignments();
