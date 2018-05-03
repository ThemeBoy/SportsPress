<?php
/*
Plugin Name: SportsPress Player Assignments
Plugin URI: http://themeboy.com/
Description: Add player assignments support to SportsPress.
Author: Savvas
Author URI: http://themeboy.com/
Version: 2.6.0
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'SportsPress_Player_Assignments' ) ) :
/**
 * Main SportsPress Player Assignments Class
 *
 * @class SportsPress_Player_Assignments
 * @version	2.6.0
 */
class SportsPress_Player_Assignments {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Actions
		add_action( 'sportspress_save_meta_player_statistics', array( $this, 'save_additional_statistics' ), 10, 2 );
		
		// Filters
		add_filter( 'sportspress_player_list_options', array( $this, 'add_settings' ) );
	}
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_VERSION' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_VERSION', '2.6.0' );
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_URL' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_URL', plugin_dir_url( __FILE__ ) );
		if ( !defined( 'SP_PLAYER_ASSIGNMENTS_DIR' ) )
			define( 'SP_PLAYER_ASSIGNMENTS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Save Additional Statistics
	 */
	public function save_additional_statistics( $post_id, $post_data ) {
		$old = (array) get_post_custom_values( 'sp_assignments', $post_id );
		
		$leagues = $post_data['sp_leagues'];
		$transfers = get_post_meta($post_id, 'sp_assignments', true);
		
		foreach ( $leagues as $l_id => $season ) {
			foreach ( $season as $s_id => $team_id ) {
				if ( $team_id != '-1' ) {
					$serialized = $l_id.'_'.$s_id.'_'.$team_id;
					if( !in_array( $serialized, $old ) ){
						add_post_meta( $post_id, 'sp_assignments', $serialized, false );
					}
				}
				//Check if there are any Mid-Season transfers
				if ( isset( $transfers[$l_id][$s_id] ) ){
					foreach ( $transfers[$l_id][$s_id] as $t_id => $performance ) {
						$serialized = $l_id.'_'.$s_id.'_'.$t_id;
						if( !in_array( $serialized, $old ) ){
							add_post_meta( $post_id, 'sp_assignments', $serialized, false );
						}
					}
				}
			}
		}
	}
	
	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		
		$settings = array_merge( $settings,
			array(
				array(
					'title'     => __( 'Filter by player assignment', 'sportspress' ),
					'desc' 		=> __( 'Use a stronger connection between leagues, seasons and teams', 'sportspress' ),
					'id' 		=> 'sportspress_list_player_assignments',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
			array(
				array( 'type' => 'sectionend', 'id' => 'timelines_options' ),
			)
			)
		);
		return $settings;
	}
}
endif;
if ( get_option( 'sportspress_load_player_assignments_module', 'yes' ) == 'yes' ) { //Is it needed?
	new SportsPress_Player_Assignments();
}