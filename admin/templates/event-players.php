<?php
if ( !function_exists( 'sportspress_event_players' ) ) {
	function sportspress_event_players( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();

		$teams = (array)get_post_meta( $id, 'sp_team', false );
		$staff = (array)get_post_meta( $id, 'sp_staff', false );
		$stats = (array)get_post_meta( $id, 'sp_players', true );
		$statistic_labels = sportspress_get_var_labels( 'sp_statistic' );

		$output = '';

		foreach( $teams as $key => $team_id ):
			if ( ! $team_id ) continue;

			$totals = array();

			// Get results for players in the team
			$players = sportspress_array_between( (array)get_post_meta( $id, 'sp_player', false ), 0, $key );
			$data = sportspress_array_combine( $players, sportspress_array_value( $stats, $team_id, array() ) );

			$output .= '<h3>' . get_the_title( $team_id ) . '</h3>';

			$output .= '<div class="sp-table-wrapper">' .
				'<table class="sp-event-statistics sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

			$output .= '<th class="data-number">#</th>';
			$output .= '<th class="data-number">' . __( 'Player', 'sportspress' ) . '</th>';

			foreach( $statistic_labels as $key => $label ):
				$output .= '<th class="data-' . $key . '">' . $label . '</th>';
			endforeach;

			$output .= '</tr>' . '</thead>' . '<tbody>';

			$i = 0;

			foreach( $data as $player_id => $row ):

				if ( ! $player_id )
					continue;

				$name = get_the_title( $player_id );

				if ( ! $name )
					continue;

				$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

				$number = get_post_meta( $player_id, 'sp_number', true );

				// Player number
				$output .= '<td class="data-number">' . $number . '</td>';

				// Name as link
				$permalink = get_post_permalink( $player_id );
				$output .= '<td class="data-name">' . '<a href="' . $permalink . '">' . $name . '</a></td>';

				foreach( $statistic_labels as $key => $label ):
					if ( $key == 'name' )
						continue;
					if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
						$value = $row[ $key ];
					else:
						$value = 0;
					endif;
					if ( ! array_key_exists( $key, $totals ) ):
						$totals[ $key ] = 0;
					endif;
					$totals[ $key ] += $value;
					$output .= '<td class="data-' . $key . '">' . $value . '</td>';
				endforeach;

				$output .= '</tr>';

				$i++;

			endforeach;

			$output .= '</tbody>';

			if ( array_key_exists( 0, $data ) ):

				$output .= '<tfoot><tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

				$number = get_post_meta( $player_id, 'sp_number', true );

				// Player number
				$output .= '<td class="data-number">&nbsp;</td>';
				$output .= '<td class="data-name">' . __( 'Total', 'sportspress' ) . '</td>';

				$row = $data[0];

				foreach( $statistic_labels as $key => $label ):
					if ( $key == 'name' ):
						continue;
					endif;
					if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
						$value = $row[ $key ];
					else:
						$value = sportspress_array_value( $totals, $key, 0 );
					endif;
					$output .= '<td class="data-' . $key . '">' . $value . '</td>';
				endforeach;

				$output .= '</tr></tfoot>';

			endif;

			$output .= '</table>' . '</div>';

		endforeach;

		return apply_filters( 'sportspress_event_players',  $output );

	}
}
