<?php
/*
Plugin Name: SportsPress GoogleMaps Integration
Plugin URI: http://tboy.co/pro
Description: Integrate GoogleMaps to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_GoogleMaps' ) ) :

/**
 * Main SportsPress GoogleMaps Class
 *
 * @class SportsPress_GoogleMaps
 * @version	2.7
 */
class SportsPress_GoogleMaps {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'sp_venue_add_googlemaps', array( $this, 'add_venue_googlemaps' ), 10, 3 );
		add_action( 'sp_venue_edit_googlemaps', array( $this, 'edit_venue_googlemaps' ), 10, 3 );
		add_action( 'sp_venue_show_googlemaps', array( $this, 'show_venue_googlemaps' ), 10, 4 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_GOOGLEMAPS_VERSION' ) )
			define( 'SP_GOOGLEMAPS_VERSION', '2.7.0' );

		if ( !defined( 'SP_GOOGLEMAPS_URL' ) )
			define( 'SP_GOOGLEMAPS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_GOOGLEMAPS_DIR' ) )
			define( 'SP_GOOGLEMAPS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Integrate GoogleMaps (Add Venue)
	 *
	 * @return mix
	 */
	public function add_venue_googlemaps( $latitude, $longitude, $address ) {
		
	}

	/**
	 * Integrate GoogleMaps (Edit Venue)
	 *
	 * @return mix
	 */
	public function edit_venue_googlemaps( $latitude, $longitude, $address ) {
		
	}

	/**
	 * Integrate GoogleMaps (View Venue)
	 *
	 * @return mix
	 */
	public function view_venue_googlemaps( $latitude, $longitude, $address ) {
		
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		if ( $this->role_is_limited() && $this->limit_applies() ) {
			wp_enqueue_script( 'sportspress-locationpicker-jquery', SP_TEAM_ACCESS_URL . 'js/admin/locationpicker.jquery.js', array( 'jquery' ), SP_GOOGLEMAPS_VERSION, true );
			wp_enqueue_script( 'sportspress-locationpicker-admin', SP_TEAM_ACCESS_URL . 'js/admin/locationpicker.js', array( 'sportspress-locationpicker-jquery' ), SP_GOOGLEMAPS_VERSION, true );
		}
	}

	/** Helper functions ******************************************************/

	/**
	 * Determine if role is limited access.
	 */
	public function role_is_limited( $role = null ) {
		if ( ! $role ) {
			global $current_user;
			$roles = $current_user->roles;
			$role = array_shift( $roles );
		}

		if ( in_array( $role, apply_filters( 'sportspress_team_access_roles', array( 'sp_player', 'sp_staff', 'sp_team_manager', 'sp_event_manager' ) ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine if limit applies to the post type
	 */
	public function limit_applies( $typenow = null ) {
		if ( ! $typenow ) global $typenow;

		if ( in_array( $typenow, apply_filters( 'sportspress_team_access_post_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ) ) {
			return true;
		}

		return false;
	}

}

endif;

if ( get_option( 'sportspress_load_googlemaps_module', 'no' ) == 'yes' ) {
	new SportsPress_GoogleMaps();
}