<?php
if ( !function_exists( 'sportspress_player_performance' ) ) {
	function sportspress_player_performance( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();

		$leagues = get_the_terms( $id, 'sp_league' );

		$output = '';

		// Loop through performance for each league
		if ( is_array( $leagues ) ):
			foreach ( $leagues as $league ):
				$output .= sportspress_player_league_performance( $league, $id );
			endforeach;
		endif;

		return apply_filters( 'sportspress_player_performance',  $output );

	}
}
