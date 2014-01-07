<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id ) {

		$data = sportspress_get_table( $id );

		$output = '<table class="sp-league-table">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		foreach( $labels as $label ):
			$output .= '<th>' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</th>' . '</thead>' . '<tbody>';

		$i = 1;

		foreach( $data as $team_id => $row ):

			$output .= '<tr class="' . ( $i % 2 ? 'odd' : 'even' ) . '">';

			// Thumbnail and name as link
			$permalink = get_post_permalink( $team_id );
			$thumbnail = get_the_post_thumbnail( $team_id, 'sp_icon' );
			$output .= '<td>' . $i . '. ' . ( $thumbnail ? $thumbnail . ' ' : '' ) . '<a href="' . $permalink . '">' . sportspress_array_value( $row, 'name', '&nbsp;' ) . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				$output .= '<td>' . sportspress_array_value( $row, $key, '—' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		return $output;

	}
}

if ( !function_exists( 'sportspress_player_list' ) ) {
	function sportspress_player_list( $id ) {

		$data = sportspress_get_list( $id );

		$output = '<table class="sp-player-list">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		foreach( $labels as $label ):
			$output .= '<th>' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</th>' . '</thead>' . '<tbody>';

		foreach( $data as $player_id => $row ):

			$output .= '<tr>';

			// Name as link
			$permalink = get_post_permalink( $player_id );
			$number = get_post_meta( $player_id, 'sp_number', true );
			$output .= '<td>' . ( $number ? $number . '. ' : '' ) . '<a href="' . $permalink . '">' . sportspress_array_value( $row, 'name', '&nbsp;' ) . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				$output .= '<td>' . sportspress_array_value( $row, $key, '—' ) . '</td>';
			endforeach;

			$output .= '</tr>';

		endforeach;

		$output .= '</tbody>' . '</table>';

		return $output;

	}
}