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
			$output .= '<th class="column-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		foreach( $data as $team_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 1 ? 'odd' : 'even' ) . '">';

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
			$output .= '<th class="column-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		foreach( $data as $player_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 1 ? 'odd' : 'even' ) . '">';

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

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		return $output;

	}
}

if ( !function_exists( 'sportspress_player_metrics' ) ) {
	function sportspress_player_metrics( $id ) {

		global $sportspress_countries;

		$number = get_post_meta( $id, 'sp_number', true );
		$nationality = get_post_meta( $id, 'sp_nationality', true );
		$metrics = sportspress_get_player_metrics_data( $id );

		$flag_image = '<img src="' . SPORTSPRESS_PLUGIN_URL . 'assets/images/flags/' . strtolower( $nationality ) . '.png" class="sp-flag">';

		$common = array(
			__( 'Number', 'sportspress' ) => $number,
			__( 'Nationality', 'sportspress' ) => $flag_image . ' ' . sportspress_array_value( $sportspress_countries, $nationality, '—' ),
		);

		$data = array_merge( $common, $metrics );

		$output = '<table class="sp-player-metrics sp-data-table">' . '<tbody>';

		$i = 0;

		foreach( $data as $label => $value ):

			$output .= '<tr class="' . ( $i % 2 == 1 ? 'odd' : 'even' ) . '"><th>' . $label . '</th><td>' . $value . '</td></tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';


		return $output;

	}
}

if ( !function_exists( 'sportspress_player_statistics' ) ) {
	function sportspress_player_statistics( $id ) {

		$team_ids = (array)get_post_meta( $id, 'sp_team', false );

		// First one is empty
		unset( $team_ids[0] );

		$output = '';

		// Loop through statistics for each team
		foreach ( $team_ids as $team_id ):

			if ( sizeof( $team_ids ) > 1 )
				$output .= '<h4 class="sp-player-team-name">' . get_the_title( $team_id ) . '</h4>';

			$data = sportspress_get_player_statistics_data( $id, $team_id );

			// The first row should be column labels
			$labels = $data[0];

			// Remove the first row to leave us with the actual data
			unset( $data[0] );

			$output .= '<table class="sp-player-statistics sp-data-table">' . '<thead>' . '<tr>';

			foreach( $labels as $key => $label ):
				$output .= '<th class="column-' . $key . '">' . $label . '</th>';
			endforeach;

			$output .= '</tr>' . '</thead>' . '<tbody>';

			$i = 0;

			foreach( $data as $season_id => $row ):

				$output .= '<tr class="' . ( $i % 2 == 1 ? 'odd' : 'even' ) . '">';

				foreach( $labels as $key => $value ):
					$output .= '<td class="column-' . $key . '">' . sportspress_array_value( $row, $key, '—' ) . '</td>';
				endforeach;

				$output .= '</tr>';

				$i++;

			endforeach;

			$output .= '</tbody>' . '</table>';


		endforeach;

		return $output;

	}
}
