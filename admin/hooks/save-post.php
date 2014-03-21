<?php
function sportspress_save_post( $post_id ) {
	global $post, $typenow;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( !isset( $_POST['sportspress_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_nonce'], SPORTSPRESS_PLUGIN_BASENAME ) ) return $post_id;
	switch ( $_POST['post_type'] ):

		case ( 'sp_team' ):

			// Update leagues seasons to show
			update_post_meta( $post_id, 'sp_leagues_seasons', sportspress_array_value( $_POST, 'sp_leagues_seasons', array() ) );

			// Update player statistics array
			if ( current_user_can( 'edit_sp_tables' ) )
				update_post_meta( $post_id, 'sp_columns', sportspress_array_value( $_POST, 'sp_columns', array() ) );

			break;

		case ( 'sp_event' ):

			// Get results
			$results = (array)sportspress_array_value( $_POST, 'sp_results', array() );

			// Update results
			update_post_meta( $post_id, 'sp_results', $results );

			// Update player statistics
			update_post_meta( $post_id, 'sp_players', sportspress_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			// Update player array
			sportspress_update_post_meta_recursive( $post_id, 'sp_player', sportspress_array_value( $_POST, 'sp_player', array() ) );

			// Update staff array
			sportspress_update_post_meta_recursive( $post_id, 'sp_staff', sportspress_array_value( $_POST, 'sp_staff', array() ) );

			// Update format
			update_post_meta( $post_id, 'sp_format', sportspress_array_value( $_POST, 'sp_format', 'league' ) );

			// Update league taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update venue taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_venue', 0 ), 'sp_venue' );

			// Update video
			update_post_meta( $post_id, 'sp_video', sportspress_array_value( $_POST, 'sp_video', null ) );

			break;

		case ( 'sp_calendar' ):

			// Update columns array
			update_post_meta( $post_id, 'sp_columns', sportspress_array_value( $_POST, 'sp_columns', array() ) );

			// Update format
			update_post_meta( $post_id, 'sp_format', sportspress_array_value( $_POST, 'sp_format', 'calendar' ) );

			// Update league taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update venue taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_venue', 0 ), 'sp_venue' );

			// Update team
			update_post_meta( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', 0 ) );

			break;

		case ( 'sp_result' ):

			// Delete posts with duplicate key
			sportspress_delete_duplicate_post( $_POST );

			break;

		case ( 'sp_outcome' ):

			// Delete posts with duplicate key
			sportspress_delete_duplicate_post( $_POST );

			break;

		case ( 'sp_column' ):

			// Delete posts with duplicate key
			sportspress_delete_duplicate_post( $_POST );
		
			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sportspress_array_value( $_POST, 'sp_equation', array() ) ) );
		
			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sportspress_array_value( $_POST, 'sp_precision', 1 ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sportspress_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sportspress_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_statistic' ):

			// Delete posts with duplicate key
			sportspress_delete_duplicate_post( $_POST );

			// Update calculation method as string
			update_post_meta( $post_id, 'sp_calculate', sportspress_array_value( $_POST, 'sp_calculate', 'DESC' ) );

			break;

		case ( 'sp_player' ):

			// Update teams to show
			update_post_meta( $post_id, 'sp_leagues', sportspress_array_value( $_POST, 'sp_leagues', array() ) );

			// Update current team
			update_post_meta( $post_id, 'sp_current_team', sportspress_array_value( $_POST, 'sp_current_team', null ) );

			// Update past team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_past_team', sportspress_array_value( $_POST, 'sp_past_team', array() ) );

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', array_merge( array( sportspress_array_value( $_POST, 'sp_current_team', null ) ), sportspress_array_value( $_POST, 'sp_past_team', array() ) ) );

			// Update player number
			update_post_meta( $post_id, 'sp_number', sportspress_array_value( $_POST, 'sp_number', '' ) );

			// Update nationality
			update_post_meta( $post_id, 'sp_nationality', sportspress_array_value( $_POST, 'sp_nationality', '' ) );

			// Update player metrics array
			update_post_meta( $post_id, 'sp_metrics', sportspress_array_value( $_POST, 'sp_metrics', array() ) );

			// Update player statistics array
			if ( current_user_can( 'edit_sp_teams' ) )
				update_post_meta( $post_id, 'sp_statistics', sportspress_array_value( $_POST, 'sp_statistics', array() ) );

			break;

		case ( 'sp_staff' ):

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_table' ):

			// Update columns array
			update_post_meta( $post_id, 'sp_columns', sportspress_array_value( $_POST, 'sp_columns', array() ) );

			// Update teams array
			update_post_meta( $post_id, 'sp_teams', sportspress_array_value( $_POST, 'sp_teams', array() ) );

			// Update league taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_list' ):

			// Update statistics array
			update_post_meta( $post_id, 'sp_columns', sportspress_array_value( $_POST, 'sp_columns', array() ) );

			// Update players array
			update_post_meta( $post_id, 'sp_players', sportspress_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			update_post_meta( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			// Update format
			update_post_meta( $post_id, 'sp_format', sportspress_array_value( $_POST, 'sp_format', 'list' ) );

			// Update league taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update orderby
			update_post_meta( $post_id, 'sp_orderby', sportspress_array_value( $_POST, 'sp_orderby', array() ) );

			// Update order
			update_post_meta( $post_id, 'sp_order', sportspress_array_value( $_POST, 'sp_order', array() ) );

			//Update player array
			sportspress_update_post_meta_recursive( $post_id, 'sp_player', sportspress_array_value( $_POST, 'sp_player', array() ) );

			break;

	endswitch;
}
add_action( 'save_post', 'sportspress_save_post' );
