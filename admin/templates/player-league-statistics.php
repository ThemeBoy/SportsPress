<?php
if ( !function_exists( 'sportspress_player_league_statistics' ) ) {
	function sportspress_player_league_statistics( $league, $id = null ) {

		if ( ! $id ):
			global $post;
			$id = $post->ID;
		endif;

		$data = sportspress_get_player_statistics_data( $id, $league->term_id );

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$output = '<table class="sp-player-statistics sp-data-table">' .
		'<caption>' . $league->name . '</caption>' . '<thead>' . '<tr>';

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

		$output .= '</tbody>' . '</table>';

		return apply_filters( 'sportspress_player_league_statistics',  $output );
		
	}
}
