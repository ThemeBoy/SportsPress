<?php
/**
 * Setup menus in WP admin.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.2
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
		add_filter( 'admin_menu', array( $this, 'menu_clean' ), 5 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 6 );
		add_action( 'admin_menu', array( $this, 'overview_menu' ), 7 );
		add_action( 'admin_menu', array( $this, 'leagues_menu' ), 8 );
		add_action( 'admin_menu', array( $this, 'seasons_menu' ), 9 );

		if ( current_user_can( 'manage_options' ) )
			add_action( 'admin_menu', array( $this, 'status_menu' ), 20 );

		if ( apply_filters( 'sportspress_show_addons_page', false ) ) // Make true to display by default
			add_action( 'admin_menu', array( $this, 'addons_menu' ), 70 );

		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		add_action( 'parent_file', array( $this, 'parent_file' ) );
		add_filter( 'menu_order', array( $this, 'menu_order' ) );
		add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
	}

	/**
	 * Add menu item
	 */
	public function admin_menu() {
		global $menu;

	    if ( current_user_can( 'manage_sportspress' ) )
	    	$menu[] = array( '', 'read', 'separator-sportspress', '', 'wp-menu-separator sportspress' );

		$main_page = add_menu_page( __( 'SportsPress', 'sportspress' ), __( 'SportsPress', 'sportspress' ), 'manage_sportspress', 'sportspress', array( $this, 'settings_page' ), apply_filters( 'sportspress_menu_icon', null ), '51.5' );
	}

	/**
	 * Add menu item
	 */
	public function overview_menu() {
		add_submenu_page( 'sportspress', __( 'Overview', 'sportspress' ), __( 'Overview', 'sportspress' ), 'manage_sportspress', 'sp-overview', array( $this, 'overview_page' ) );
	}

	/**
	 * Add menu item
	 */
	public function leagues_menu() {
		add_submenu_page( 'sportspress', __( 'Leagues', 'sportspress' ), __( 'Leagues', 'sportspress' ), 'manage_sportspress', 'edit-tags.php?taxonomy=sp_league');
	}

	/**
	 * Add menu item
	 */
	public function seasons_menu() {
		add_submenu_page( 'sportspress', __( 'Seasons', 'sportspress' ), __( 'Seasons', 'sportspress' ), 'manage_sportspress', 'edit-tags.php?taxonomy=sp_season');
	}

	/**
	 * Add menu item
	 */
	public function status_menu() {
		add_submenu_page( 'sportspress', __( 'System Status', 'sportspress' ),  __( 'System Status', 'sportspress' ) , 'manage_sportspress', 'sp-status', array( $this, 'status_page' ) );
		register_setting( 'sportspress_status_settings_fields', 'sportspress_status_options' );
	}

	/**
	 * Addons menu item
	 */
	public function addons_menu() {
		add_submenu_page( 'sportspress', __( 'SportsPress Add-ons/Extensions', 'sportspress' ),  __( 'Add-ons', 'sportspress' ) , 'manage_sportspress', 'sp-addons', array( $this, 'addons_page' ) );
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @access public
	 * @return void
	 */
	public function menu_highlight() {
		global $typenow, $submenu;
		if ( is_sp_config_type( $typenow ) )
			$this->highlight_admin_menu();
		elseif ( $typenow == 'sp_calendar' )
			$this->highlight_admin_menu( 'edit.php?post_type=sp_event', 'edit.php?post_type=sp_calendar' );
		elseif ( $typenow == 'sp_table' )
			$this->highlight_admin_menu( 'edit.php?post_type=sp_team', 'edit.php?post_type=sp_table' );
		elseif ( $typenow == 'sp_list' )
			$this->highlight_admin_menu( 'edit.php?post_type=sp_player', 'edit.php?post_type=sp_list' );
		elseif ( $typenow == 'sp_staff' )
			$this->highlight_admin_menu( 'edit.php?post_type=sp_player', 'edit.php?post_type=sp_staff' );

		if ( isset( $submenu['sportspress'] ) && isset( $submenu['sportspress'][0] ) && isset( $submenu['sportspress'][0][0] ) ) {
			$submenu['sportspress'][0][0] = __( 'Settings', 'sportspress' );
		}
	}

	public function parent_file( $parent_file ) {
		global $current_screen;
		$taxonomy = $current_screen->taxonomy;
		if ( in_array( $taxonomy, array( 'sp_league', 'sp_season' ) ) )
			$parent_file = 'sportspress';
		return $parent_file;
	}

	/**
	 * Reorder the SP menu items in admin.
	 *
	 * @param mixed $menu_order
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array
		$sportspress_menu_order = array();

		// Get the index of our custom separator
		$sportspress_separator = array_search( 'separator-sportspress', $menu_order );

		// Get index of menu items
		$sportspress_event = array_search( 'edit.php?post_type=sp_event', $menu_order );
		$sportspress_team = array_search( 'edit.php?post_type=sp_team', $menu_order );
		$sportspress_player = array_search( 'edit.php?post_type=sp_player', $menu_order );

		// Loop through menu order and do some rearranging
		foreach ( $menu_order as $index => $item ) :

			if ( ( ( 'sportspress' ) == $item ) ) :
				$sportspress_menu_order[] = 'separator-sportspress';
				$sportspress_menu_order[] = $item;
				$sportspress_menu_order[] = 'edit.php?post_type=sp_event';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_team';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_player';
				unset( $menu_order[$sportspress_separator] );
				unset( $menu_order[$sportspress_event] );
				unset( $menu_order[$sportspress_team] );
				unset( $menu_order[$sportspress_player] );
			elseif ( !in_array( $item, array( 'separator-sportspress' ) ) ) :
				$sportspress_menu_order[] = $item;
			endif;

		endforeach;

		// Return order
		return $sportspress_menu_order;
	}

	/**
	 * custom_menu_order
	 * @return bool
	 */
	public function custom_menu_order() {
		if ( ! current_user_can( 'manage_sportspress' ) )
			return false;
		return true;
	}

	/**
	 * Init the status page
	 */
	public function status_page() {
		$page = include( 'class-sp-admin-status.php' );
		$page->output();
	}

	/**
	 * Init the addons page
	 */
	public function addons_page() {
		$page = include( 'class-sp-admin-addons.php' );
		$page->output();
	}

	/**
	 * Clean the SP menu items in admin.
	 */
	public function menu_clean() {
		global $menu, $submenu, $current_user;

		// Find where our separator is in the menu
		foreach( $menu as $key => $data ):
			if ( is_array( $data ) && array_key_exists( 2, $data ) && $data[2] == 'edit.php?post_type=sp_separator' )
				$separator_position = $key;
		endforeach;

		// Swap our separator post type with a menu separator
		if ( isset( $separator_position ) ):
			$menu[ $separator_position ] = array( '', 'read', 'separator-sportspress', '', 'wp-menu-separator sportspress' );
		endif;

	    // Remove "Leagues" and "Seasons" links from Events submenu
		if ( isset( $submenu['edit.php?post_type=sp_event'] ) ):
			$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], array( $this, 'remove_leagues' ) );
			$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], array( $this, 'remove_seasons' ) );
		endif;

	    // Remove "Venues", "Leagues" and "Seasons" links from Teams submenu
		if ( isset( $submenu['edit.php?post_type=sp_team'] ) ):
			$submenu['edit.php?post_type=sp_team'] = array_filter( $submenu['edit.php?post_type=sp_team'], array( $this, 'remove_venues' ) );
			$submenu['edit.php?post_type=sp_team'] = array_filter( $submenu['edit.php?post_type=sp_team'], array( $this, 'remove_leagues' ) );
			$submenu['edit.php?post_type=sp_team'] = array_filter( $submenu['edit.php?post_type=sp_team'], array( $this, 'remove_seasons' ) );
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

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		if ( in_array( $user_role, array( 'sp_player', 'sp_staff', 'sp_event_manager', 'sp_team_manager' ) ) ):
			remove_menu_page( 'upload.php' );
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'tools.php' );
		endif;
	}

	/**
	 * Init the overview page
	 */
	public function overview_page() {
		include_once( 'class-sp-admin-overview.php' );
		SP_Admin_Overview::output();
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

	public static function highlight_admin_menu( $p = 'sportspress', $s = 'sportspress' ) {
		global $parent_file, $submenu_file;
		$parent_file = $p;
		$submenu_file = $s;
	}
}

endif;

return new SP_Admin_Menus();