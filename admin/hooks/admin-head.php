<?php
function sportspress_admin_head() {
	global $typenow;
	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic', 'sp_metric' ) ) )
		sportspress_highlight_admin_menu();
}
add_action( 'admin_head-edit.php', 'sportspress_admin_head', 10, 2 );
add_action( 'admin_head-post.php', 'sportspress_admin_head', 10, 2 );
add_action( 'admin_head-post-new.php', 'sportspress_admin_head', 10, 2 );
