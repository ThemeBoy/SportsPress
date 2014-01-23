<?php
if ( !function_exists( 'sportspress_player_league_statistics' ) ) {
	function sportspress_player_league_statistics( $league, $id = null ) {

		if ( ! $league )
			return false;

		if ( ! $id )
			$id = get_the_ID();

		$data = sportspress_get_player_statistics_data( $id, $league->term_id );

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		// Skip if there are no rows in the table
		if ( empty( $data ) )
			return false;

		$output = '<h4 class="sp-table-caption">' . $league->name . '</h4>' .
			'<div class="sp-table-wrapper">' .
			'<table class="sp-player-statistics sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

		foreach( $labels as $key => $label ):
			$output .= '<th class="data-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		foreach( $data as $season_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			foreach( $labels as $key => $value ):
				$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>' . '</div>';

		return apply_filters( 'sportspress_player_league_statistics',  $output );
		
	}
}
