<?php
function sportspress_current_screen() {
	$screen = get_current_screen();
	if ( $screen->id == 'dashboard' )
		include_once( dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/tools/dashboard.php' );
}
add_action( 'current_screen', 'sportspress_current_screen' );
