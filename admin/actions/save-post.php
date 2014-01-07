<?php
function sportspress_save_post( $post_id ) {
	global $post, $typenow;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( !isset( $_POST['sportspress_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_nonce'], SPORTSPRESS_PLUGIN_BASENAME ) ) return $post_id;
	switch ( $_POST['post_type'] ):
		case ( 'sp_team' ):

			// Update columns
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

			break;

		case ( 'sp_column' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sportspress_array_value( $_POST, 'sp_format', 'integer' ) );

			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sportspress_array_value( $_POST, 'sp_precision', 1 ) );

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sportspress_array_value( $_POST, 'sp_equation', array() ) ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sportspress_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sportspress_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_statistic' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sportspress_array_value( $_POST, 'sp_format', 'integer' ) );

			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sportspress_array_value( $_POST, 'sp_precision', 1 ) );

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sportspress_array_value( $_POST, 'sp_equation', array() ) ) );
			
			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sportspress_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sportspress_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_result' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sportspress_array_value( $_POST, 'sp_format', 'integer' ) );

			break;

		case ( 'sp_player' ):

			// Update player statistics
			update_post_meta( $post_id, 'sp_statistics', sportspress_array_value( $_POST, 'sp_statistics', array() ) );

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			// Update player number
			update_post_meta( $post_id, 'sp_number', sportspress_array_value( $_POST, 'sp_number', '' ) );

			// Update player details array
			update_post_meta( $post_id, 'sp_details', sportspress_array_value( $_POST, 'sp_details', array() ) );

			break;

		case ( 'sp_staff' ):

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_table' ):

			// Update teams array
			update_post_meta( $post_id, 'sp_teams', sportspress_array_value( $_POST, 'sp_teams', array() ) );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update team array
			sportspress_update_post_meta_recursive( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_list' ):

			// Update players array
			update_post_meta( $post_id, 'sp_players', sportspress_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			update_post_meta( $post_id, 'sp_team', sportspress_array_value( $_POST, 'sp_team', array() ) );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sportspress_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			//Update player array
			sportspress_update_post_meta_recursive( $post_id, 'sp_player', sportspress_array_value( $_POST, 'sp_player', array() ) );

			break;

	endswitch;
}
add_action( 'save_post', 'sportspress_save_post' );
