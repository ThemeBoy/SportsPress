<?php
/*
Plugin Name: SportsPress Splitting Seasons
Plugin URI: http://tboy.co/pro
Description: Add Splitting Seasons (Mid-Season Transfers) to SportsPress players.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Splitting_Seasons' ) ) :

/**
 * Main SportsPress Splitting Seasons Class
 *
 * @class SportsPress_Splitting_Seasons
 * @version	2.6.0
 *
 */
class SportsPress_Splitting_Seasons {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_save_meta_player_statistics', array( $this, 'save_additional_statistics' ), 10, 2 );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_SPLITTING_SEASONS_VERSION' ) )
			define( 'SP_SPLITTING_SEASONS_VERSION', '2.6.0' );

		if ( !defined( 'SP_SPLITTING_SEASONS_URL' ) )
			define( 'SP_SPLITTING_SEASONS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_SPLITTING_SEASONS_DIR' ) )
			define( 'SP_SPLITTING_SEASONS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_player', 'edit-sp_player' ) ) ) {
		    wp_enqueue_script( 'sportspress-splitting-seasons', SP_SPLITTING_SEASONS_URL .'js/sportspress-splitting-seasons.js', array( 'jquery' ), SP_SPLITTING_SEASONS_VERSION, true );
		}
	}
	
	/**
	 * Save Additional Statistics
	 */
	public function save_additional_statistics( $post_id, $post_data ) {
		$old = get_post_meta($post_id, 'sp_additional_statistics', true);
		$new = array();
		
		$leagues = $post_data['sp_add_league'];
		$seasons = $post_data['sp_add_season'];
		$teams = $post_data['sp_add_team'];
		$columns = $post_data['sp_add_columns'];
		$labels = array_keys($columns);
		
		$i = 0;
		foreach ( $leagues as $league ) {
			if ( $league != '-99' ) {
				foreach ( $labels as $label ) {
					$new[$league][$seasons[$i]][$teams[$i]][$label] = $columns[$label][$i];
				}
			}
			$i++;
		}
		if ( !empty( $new ) && $new != $old ) {
			update_post_meta( $post_id, 'sp_additional_statistics', $new );
		}
		elseif ( empty($new) && $old ) {
			delete_post_meta( $post_id, 'sp_additional_statistics', $old );
		}
	}

}

endif;

if ( get_option( 'sportspress_load_splitting_seasons_module', 'yes' ) == 'yes' ) {
	new SportsPress_Splitting_Seasons();
}
