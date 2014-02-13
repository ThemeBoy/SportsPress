<?php
function sportspress_parse_query( $query ) {
	global $pagenow, $typenow;

	if ( is_admin() && $pagenow == 'edit.php' ):

		if( in_array( $typenow, array( 'sp_event', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) && isset( $_GET['team'] ) && $_GET['team'] != null ):
			$query->query_vars['meta_key'] = 'sp_team';
			$query->query_vars['meta_value'] = $_GET['team'];
		endif;
	endif;
}
add_filter('parse_query', 'sportspress_parse_query');
