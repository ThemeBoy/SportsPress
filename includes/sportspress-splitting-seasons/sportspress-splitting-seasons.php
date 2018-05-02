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
		
		// Add new Class
		require_once( 'includes/class-sp-player-additional.php' );
		require_once( 'includes/class-sp-meta-box-player-statistics.php' );

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_player_statistics_league_template', array( $this, 'template' ) );
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
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
			wp_enqueue_style( 'jquery-ui-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' ); 
			wp_enqueue_style( 'sportspress-admin-datepicker-styles', SP()->plugin_url() . '/assets/css/datepicker.css', array( 'jquery-ui-style' ), SP_VERSION );
		}
	}
	
		/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_player']['statistics'] = array(
					'title' => __( 'Statistics', 'sportspress' ),
					'save' => 'SP_Meta_Box_Player_Add_Statistics::save',
					'output' => 'SP_Meta_Box_Player_Add_Statistics::output',
					'context' => 'normal',
					'priority' => 'high',
				);
		return $meta_boxes;
	}
	
	/**
	 * Render player statistics per league
	 */
	public function template( $args ) {
		sp_get_template( 'player-statistics-league-additional.php', $args, '', SP_SPLITTING_SEASONS_DIR . 'templates/' );
	}
}

endif;

if ( get_option( 'sportspress_load_splitting_seasons_module', 'yes' ) == 'yes' ) {
	new SportsPress_Splitting_Seasons();
}
