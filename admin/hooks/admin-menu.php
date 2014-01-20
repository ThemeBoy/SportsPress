<?php
function sportspress_admin_menu( $position ) {
	add_options_page(
		__( 'SportsPress', 'sportspress' ),
		__( 'SportsPress', 'sportspress' ),
		'manage_options',
		'sportspress',
		'sportspress_settings'
	);

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
	
    // Remove "Positions" link from Media submenu
	unset( $submenu['upload.php'][17] );

    // Remove "Leagues" link from Players submenu
    unset( $submenu['edit.php?post_type=sp_player'][15] );

    // Remove "Seasons" link from Players submenu
    unset( $submenu['edit.php?post_type=sp_player'][16] );

    // Remove "Leagues" link from Staff submenu
    unset( $submenu['edit.php?post_type=sp_staff'][15] );

    // Remove "Seasons" link from Staff submenu
    unset( $submenu['edit.php?post_type=sp_staff'][16] );

}
add_action( 'admin_menu', 'sportspress_admin_menu' );
