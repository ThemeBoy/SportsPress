<?php
/*
Plugin Name: SportsPress Next Team Preset
Plugin URI: http://themeboy.com/
Description: Add a Next preset to SportsPress league table column equations.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Next_Team_Preset' ) ) :

/**
 * Main SportsPress Next Team Preset Class
 *
 * @class SportsPress_Next_Team_Preset
 * @version	2.6.3
 */
 
 class SportsPress_Next_Team_Preset {

 	/** @var bool The link events setting. */
 	public $link_events = true;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		$this->link_events = get_option( 'sportspress_link_events', 'yes' ) === 'yes' ? true : false;

		// Filters
		add_filter( 'sportspress_equation_options', array( $this, 'add_options' ) );
		add_filter( 'sportspress_equation_presets', array( $this, 'presets' ) );
		add_filter( 'sportspress_equation_solve_for_presets', array( $this, 'solve' ), 10, 3 );

	}
	
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_NEXT_TEAM_PRESET_VERSION' ) )
			define( 'SP_NEXT_TEAM_PRESET_VERSION', '2.6.3' );

		if ( !defined( 'SP_NEXT_TEAM_PRESET_URL' ) )
			define( 'SP_NEXT_TEAM_PRESET_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_NEXT_TEAM_PRESET_DIR' ) )
			define( 'SP_NEXT_TEAM_PRESET_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add additional options.
	 *
	 * @return array
	 */
	public function add_options( $options ) {
		$options[ 'Presets' ]['$nextteam'] = __( 'Next Team', 'sportspress' );
		return $options;
	}
	
	/**
	 * Add preset
	 *
	 * @return array
	 */
	public function presets( $presets ) {
		$presets[] = '$nextteam';
		return $presets;
	}
	
	/**
	 * Solve preset
	 *
	 * @return mixed
	 */
	public function solve( $input, $equation, $post_id ) {
		if ( strpos( $equation, '$nextteam' ) !== false ) {
			$args = array(
				'post_type' => 'sp_event',
				'numberposts' => 1,
				'posts_per_page' => 1,
				'post_status' => 'future',
				'meta_query' => array(
					array(
						'key' => 'sp_team',
						'value' => $post_id,
						'compare' => 'IN',
					),
				),
				'order' => 'ASC',
			);
			$events = get_posts( $args );

			if ( $events ) {
				$event = reset( $events );
				$teams = array_filter( (array) get_post_meta( $event->ID, 'sp_team', false ) );
				if ( ( $key = array_search( $post_id, $teams ) ) !== false ) {
					unset( $teams[ $key ] );
				} else {
					return '-';
				}

				$team_id = reset( $teams );

				if ( ! $team_id ) return '-';

				if ( has_post_thumbnail( $team_id ) ) {
					$logo = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon' );
					$icon = '<span class="team-logo">' . $logo . '</span>';
				} else {
					$icon = sp_team_abbreviation( $team_id, true );
				}

				if ( $this->link_events ) {
					return '<a title="' . $event->post_title . '" href="' . get_post_permalink( $event->ID, false, true ) . '">' . $icon . '</a>';
				} else {
					return '<span title="' . $event->post_title . '">' . $icon . '</a>';
				}
			} else {
				return '-';
			}
		} else {
			return $input;
		}
	}
}

endif;

new SportsPress_Next_Team_Preset();
