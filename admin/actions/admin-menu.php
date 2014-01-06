<?php
function sp_admin_menu( $position ) {
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

    // Remove "Add Configuration" link under SportsPress
    unset( $submenu['edit.php?post_type=sp_config'][10] );

    // Remove "Seasons" link under Events
    unset( $submenu['edit.php?post_type=sp_event'][15] );

    // Remove "Seasons" link under Players
    unset( $submenu['edit.php?post_type=sp_player'][15] );

    // Remove "Seasons" link under Staff
    unset( $submenu['edit.php?post_type=sp_staff'][15] );
}
add_action( 'admin_menu', 'sp_admin_menu' );
?>