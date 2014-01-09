<?php
function sportspress_insert_post_data( $data, $postarr ) {
  
	if( $data['post_type'] == 'sp_event' && $data['post_title'] == '' ):

			$teams = sportspress_array_value( $postarr, 'sp_team', array() );

			$team_names = array();
			foreach( $teams as $team ):
				$team_names[] = get_the_title( $team );
			endforeach;

			$data['post_title'] = implode( ' ' . __( 'vs', 'sportspress' ) . ' ', $team_names );

	endif;

	return $data;
}
add_filter( 'wp_insert_post_data' , 'sportspress_insert_post_data' , '99', 2 );
