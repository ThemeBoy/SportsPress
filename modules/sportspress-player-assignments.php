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
		$old = get_post_meta($post_id, 'sp_player_assignments', true);
		$new = array();
		
		$leagues = $post_data['sp_leagues'];
		$transfers = get_post_meta($post_id, 'sp_player_assignments', true);
		
		foreach ( $leagues as $l_id => $season ) {
			foreach ( $season as $s_id => $team_id ) {
				if ( $team_id != '-1' ) {
					$new[$l_id][$s_id][] = $team_id;
				}
				//Check if there are any Mid-Season transfers
				if ( isset( $transfers[$l_id][$s_id] ) ){
					foreach ( $transfers[$l_id][$s_id] as $t_id => $performance ) {
						$new[$l_id][$s_id][] = $t_id;
					}
				}
			}
		}
		
		if ( !empty( $new ) && $new != $old ) {
			update_post_meta( $post_id, 'sp_player_assignments', $new );
		}
		elseif ( empty($new) && $old ) {
			delete_post_meta( $post_id, 'sp_player_assignments', $old );
		}
	}
}
endif;
if ( get_option( 'sportspress_load_player_assignments_module', 'yes' ) == 'yes' ) { //Is it needed?
	new SportsPress_Player_Assignments();
}