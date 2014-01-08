<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id ) {

		$data = sportspress_get_league_table_data( $id );

		$output = '<table class="sp-league-table sp-data-table">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$output .= '<th class="column-number">' . __( '#', 'sportspress' ) . '</th>';
		foreach( $labels as $key => $label ):
			$output .= '<th class="column-' . ( $key ? $key : 'name' ) . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</th>' . '</thead>' . '<tbody>';

		$i = 1;

		foreach( $data as $team_id => $row ):

			$output .= '<tr class="' . ( $i % 2 ? 'odd' : 'even' ) . '">';

			// Position as number
			$output .= '<td class="column-number">' . $i . '</td>';

			// Thumbnail and name as link
			$permalink = get_post_permalink( $team_id );
			$thumbnail = get_the_post_thumbnail( $team_id, 'thumbnail', array( 'class' => 'logo' ) );
			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
			$output .= '<td class="column-name">' . ( $thumbnail ? $thumbnail . ' ' : '' ) . '<a href="' . $permalink . '">' . $name . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				$output .= '<td class="column-' . $key . '">' . sportspress_array_value( $row, $key, '—' ) . '</td>';
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

		$data = sportspress_get_player_list_data( $id );

		$output = '<table class="sp-player-list sp-data-table">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$output .= '<th class="column-number">' . __( '#', 'sportspress' ) . '</th>';
		foreach( $labels as $key => $label ):
			$output .= '<th class="column-' . ( $key ? $key : 'name' ) . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</th>' . '</thead>' . '<tbody>';

		$i = 1;

		foreach( $data as $player_id => $row ):

			$output .= '<tr>';

			// Player number
			$number = get_post_meta( $player_id, 'sp_number', true );
			$output .= '<td class="column-number">' . ( $number ? $number : '&nbsp;' ) . '</td>';

			// Name as link
			$permalink = get_post_permalink( $player_id );
			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
			$output .= '<td class="column-name">' . '<a href="' . $permalink . '">' . $name . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				$output .= '<td class="column-' . $key . '">' . sportspress_array_value( $row, $key, '—' ) . '</td>';
			endforeach;

			$output .= '</tr>';

		endforeach;

		$output .= '</tbody>' . '</table>';

		return $output;

	}
}