<?php
function sportspress_admin_menu( $position ) {

	if ( ! current_user_can( 'manage_options' ) )
		return;
	
	global $menu, $submenu;

	// Find where our placeholder is in the menu
	foreach( $menu as $key => $data ) {
		if ( is_array( $data ) && array_key_exists( 2, $data ) && $data[2] == 'edit.php?post_type=sp_separator' )
			$position = $key;
	}

	// Swap our placeholder post type with a menu separator
	if ( $position ):
		$menu[ $position ] = array( '', 'read', 'separator-sportspress', '', 'wp-menu-separator sportspress' );
	endif;

    // Remove "Venues" and "Positions" links from Media submenu
	$submenu['upload.php'] = array_filter( $submenu['upload.php'], 'sportspress_admin_menu_remove_venues' );
	$submenu['upload.php'] = array_filter( $submenu['upload.php'], 'sportspress_admin_menu_remove_positions' );

    // Remove "Leagues" and "Seasons" links from Events submenu
	$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], 'sportspress_admin_menu_remove_leagues' );
	$submenu['edit.php?post_type=sp_event'] = array_filter( $submenu['edit.php?post_type=sp_event'], 'sportspress_admin_menu_remove_seasons' );

    // Remove "Leagues" and "Seasons" links from Players submenu
	$submenu['edit.php?post_type=sp_player'] = array_filter( $submenu['edit.php?post_type=sp_player'], 'sportspress_admin_menu_remove_leagues' );
	$submenu['edit.php?post_type=sp_player'] = array_filter( $submenu['edit.php?post_type=sp_player'], 'sportspress_admin_menu_remove_seasons' );

    // Remove "Leagues" and "Seasons" links from Staff submenu
	$submenu['edit.php?post_type=sp_staff'] = array_filter( $submenu['edit.php?post_type=sp_staff'], 'sportspress_admin_menu_remove_leagues' );
	$submenu['edit.php?post_type=sp_staff'] = array_filter( $submenu['edit.php?post_type=sp_staff'], 'sportspress_admin_menu_remove_seasons' );

}
add_action( 'admin_menu', 'sportspress_admin_menu' );

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