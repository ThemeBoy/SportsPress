<?php
/**
 * Setup menus in WP admin.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Menus' ) ) :

/**
 * SP_Admin_Menus Class
 */
class SP_Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_filter( 'admin_menu', array( $this, 'menu_clean' ) );
		add_action( 'admin_menu', array( $this, 'settings_menu' ), 50 );
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
	}

	/**
	 * Add menu item
	 */
	public function settings_menu() {
		$settings_page = add_options_page( __( 'SportsPress', 'sportspress' ), __( 'SportsPress', 'sportspress' ), 'manage_options', 'sportspress', array( $this, 'settings_page' ) );
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @access public
	 * @return void
	 */
	public function menu_highlight() {
		global $typenow;
		if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance', 'sp_metric' ) ) )
			sportspress_highlight_admin_menu();
		elseif ( $typenow == 'sp_table' )
			sportspress_highlight_admin_menu( 'edit.php?post_type=sp_team', 'edit.php?post_type=sp_table' );
		elseif ( $typenow == 'sp_list' )
			sportspress_highlight_admin_menu( 'edit.php?post_type=sp_player', 'edit.php?post_type=sp_list' );
		elseif ( $typenow == 'sp_staff' )
			sportspress_highlight_admin_menu( 'edit.php?post_type=sp_player', 'edit.php?post_type=sp_staff' );
		elseif ( $typenow == 'sp_directory' )
			sportspress_highlight_admin_menu( 'edit.php?post_type=sp_player', 'edit.php?post_type=sp_directory' );
	}

	/**
	 * Clean the SP menu items in admin.
	 */
	public function menu_clean() {
		global $menu, $submenu;

		// Find where our separator is in the menu
		foreach( $menu as $key => $data ):
			if ( is_array( $data ) && array_key_exists( 2, $data ) && $data[2] == 'edit.php?post_type=sp_separator' )
				$separator_position = $key;
		endforeach;

		// Swap our separator post type with a menu separator
		if ( isset( $separator_position ) ):
			$menu[ $separator_position ] = array( '', 'read', 'separator-sportspress', '', 'wp-menu-separator sportspress' );
		endif;

	    // Remove "Venues" and "Positions" links from Media submenu
		if ( isset( $submenu['upload.php'] ) ):
			$submenu['upload.php'] = array_filter( $submenu['upload.php'], array( $this, 'remove_venues' ) );
			$submenu['upload.php'] = array_filter( $submenu['upload.php'], array( $this, 'remove_positions' ) );
		endif;

	    // Remove "Leagues" and "Seasons" links from Events submenu
		if ( isset( $submenu['edit.php?post_type=sp_event'] ) ):
			$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], array( $this, 'remove_leagues' ) );
			$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], array( $this, 'remove_seasons' ) );
		endif;

	    // Remove "Leagues" and "Seasons" links from Players submenu
		if ( isset( $submenu['edit.php?post_type=sp_player'] ) ):
			$submenu['edit.php?post_type=sp_player'] = array_filter( $submenu['edit.php?post_type=sp_player'], array( $this, 'remove_leagues' ) );
			$submenu['edit.php?post_type=sp_player'] = array_filter( $submenu['edit.php?post_type=sp_player'], array( $this, 'remove_seasons' ) );
		endif;

	    // Remove "Leagues" and "Seasons" links from Staff submenu
		if ( isset( $submenu['edit.php?post_type=sp_staff'] ) ):
			$submenu['edit.php?post_type=sp_staff'] = array_filter( $submenu['edit.php?post_type=sp_staff'], array( $this, 'remove_leagues' ) );
			$submenu['edit.php?post_type=sp_staff'] = array_filter( $submenu['edit.php?post_type=sp_staff'], array( $this, 'remove_seasons' ) );
		endif;
	}

	/**
	 * Init the settings page
	 */
	public function settings_page() {
		include_once( 'class-sp-admin-settings.php' );
		SP_Admin_Settings::output();
	}

	public function remove_add_new( $arr = array() ) {
		return $arr[0] != __( 'Add New', 'sportspress' );
	}

	public function remove_leagues( $arr = array() ) {
		return $arr[0] != __( 'Leagues', 'sportspress' );
	}

	public function remove_positions( $arr = array() ) {
		return $arr[0] != __( 'Positions', 'sportspress' );
	}

	public function remove_seasons( $arr = array() ) {
		return $arr[0] != __( 'Seasons', 'sportspress' );
	}

	public function remove_venues( $arr = array() ) {
		return $arr[0] != __( 'Venues', 'sportspress' );
	}
}

endif;

return new SP_Admin_Menus();