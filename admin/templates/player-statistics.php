<?php
if ( !function_exists( 'sportspress_player_statistics' ) ) {
	function sportspress_player_statistics( $id = null ) {

		if ( ! $id ):
			global $post;
			$id = $post->ID;
		endif;

		$leagues = get_the_terms( $id, 'sp_league' );

		$output = '';

		// Loop through statistics for each league
		foreach ( $leagues as $league ):

			$output .= sportspress_player_league_statistics( $league, $id );

		endforeach;

		return apply_filters( 'sportspress_player_statistics',  $output );

	}
}
