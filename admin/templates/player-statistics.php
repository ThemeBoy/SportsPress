<?php
if ( !function_exists( 'sportspress_player_statistics' ) ) {
	function sportspress_player_statistics( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();

		$leagues = get_the_terms( $id, 'sp_league' );

		$output = '';

		// Loop through statistics for each league
		if ( is_array( $leagues ) ):
			foreach ( $leagues as $league ):
				$output .= sportspress_player_league_statistics( $league, $id );
			endforeach;
		endif;

		return apply_filters( 'sportspress_player_statistics',  $output );

	}
}
