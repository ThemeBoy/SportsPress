<?php
function sportspress_admin_menu( $position ) {

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
		$submenu['upload.php'] = array_filter( $submenu['upload.php'], 'sportspress_admin_menu_remove_venues' );
		$submenu['upload.php'] = array_filter( $submenu['upload.php'], 'sportspress_admin_menu_remove_positions' );
	endif;

    // Remove "Leagues" and "Seasons" links from Schedule submenu
	if ( isset( $submenu['edit.php?post_type=sp_event'] ) ):
		$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], 'sportspress_admin_menu_remove_leagues' );
		$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], 'sportspress_admin_menu_remove_seasons' );
	endif;

    // Remove "Leagues" and "Seasons" links from Players submenu
	if ( isset( $submenu['edit.php?post_type=sp_player'] ) ):
		$submenu['edit.php?post_type=sp_player'] = array_filter( $submenu['edit.php?post_type=sp_player'], 'sportspress_admin_menu_remove_leagues' );
		$submenu['edit.php?post_type=sp_player'] = array_filter( $submenu['edit.php?post_type=sp_player'], 'sportspress_admin_menu_remove_seasons' );
	endif;

}
add_action( 'admin_menu', 'sportspress_admin_menu' );

if ( ! function_exists( 'sportspress_admin_menu_remove_add_new' ) ) {
	function sportspress_admin_menu_remove_add_new( $arr = array() ) {
		return $arr[0] != __( 'Add New', 'sportspress' );
	}
}

if ( ! function_exists( 'sportspress_admin_menu_remove_leagues' ) ) {
	function sportspress_admin_menu_remove_leagues( $arr = array() ) {
		return $arr[0] != __( 'Leagues', 'sportspress' );
	}
}

if ( ! function_exists( 'sportspress_admin_menu_remove_positions' ) ) {
	function sportspress_admin_menu_remove_positions( $arr = array() ) {
		return $arr[0] != __( 'Positions', 'sportspress' );
	}
}

if ( ! function_exists( 'sportspress_admin_menu_remove_seasons' ) ) {
	function sportspress_admin_menu_remove_seasons( $arr = array() ) {
		return $arr[0] != __( 'Seasons', 'sportspress' );
	}
}

if ( ! function_exists( 'sportspress_admin_menu_remove_venues' ) ) {
	function sportspress_admin_menu_remove_venues( $arr = array() ) {
		return $arr[0] != __( 'Venues', 'sportspress' );
	}
}
