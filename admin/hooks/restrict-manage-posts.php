<?php
function sportspress_restrict_manage_posts() {
	sportspress_highlight_admin_menu();
	global $typenow, $wp_query;
	if ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list' ) ) ):
		$selected = isset( $_REQUEST['sp_team'] ) ? $_REQUEST['sp_team'] : null;
		$args = array(
			'show_option_none' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
			'post_type' => 'sp_team',
			'name' => 'sp_team',
			'selected' => $selected
		);
		// wp_dropdown_pages( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_player' ) ) ):
		$selected = isset( $_REQUEST['sp_position'] ) ? $_REQUEST['sp_position'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
			'taxonomy' => 'sp_position',
			'name' => 'sp_position',
			'selected' => $selected
		);
		sportspress_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list' ) ) ):
		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sportspress_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_event', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ):
		$selected = isset( $_REQUEST['team'] ) ? $_REQUEST['team'] : null;
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'team',
			'show_option_all' => sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
			'selected' => $selected,
			'values' => 'ID',
		);
		sportspress_dropdown_pages( $args );
	endif;
}
add_action( 'restrict_manage_posts', 'sportspress_restrict_manage_posts' );
