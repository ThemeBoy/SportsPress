<?php
function sportspress_admin_head() {
	global $typenow;
	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic', 'sp_metric' ) ) )
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
add_action( 'admin_head-edit.php', 'sportspress_admin_head', 10, 2 );
add_action( 'admin_head-post.php', 'sportspress_admin_head', 10, 2 );
add_action( 'admin_head-post-new.php', 'sportspress_admin_head', 10, 2 );
