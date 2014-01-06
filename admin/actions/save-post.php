<?php
function sp_save_post( $post_id ) {
	global $post, $typenow;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( !isset( $_POST['sportspress_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_nonce'], SPORTSPRESS_PLUGIN_BASENAME ) ) return $post_id;
	switch ( $_POST['post_type'] ):
		case ( 'sp_team' ):

			// Update columns
			update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );

			break;

		case ( 'sp_event' ):

			// Get results
			$results = (array)sp_array_value( $_POST, 'sp_results', array() );

			// Update results
			update_post_meta( $post_id, 'sp_results', $results );

			// Update player statistics
			update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update player array
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );

			// Update staff array
			sp_update_post_meta_recursive( $post_id, 'sp_staff', sp_array_value( $_POST, 'sp_staff', array() ) );

			break;

		case ( 'sp_column' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'integer' ) );

			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1 ) );

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sp_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_statistic' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'integer' ) );

			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1 ) );

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );
			
			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sp_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_result' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'integer' ) );

			break;

		case ( 'sp_player' ):

			// Update player statistics
			update_post_meta( $post_id, 'sp_statistics', sp_array_value( $_POST, 'sp_statistics', array() ) );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update player number
			update_post_meta( $post_id, 'sp_number', sp_array_value( $_POST, 'sp_number', '' ) );

			// Update player details array
			update_post_meta( $post_id, 'sp_details', sp_array_value( $_POST, 'sp_details', array() ) );

			break;

		case ( 'sp_staff' ):

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_table' ):

			// Update teams array
			update_post_meta( $post_id, 'sp_teams', sp_array_value( $_POST, 'sp_teams', array() ) );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_list' ):

			// Update players array
			update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			//Update player array
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );

			break;

	endswitch;
}
add_action( 'save_post', 'sp_save_post' );
?>