<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		$options = get_option( 'sportspress' );

		$defaults = array(
			'number' => -1,
			'columns' => null,
			'show_full_table_link' => false,
			'show_team_logo' => sportspress_array_value( $options, 'league_table_show_team_logo', false ),
		);

		$r = wp_parse_args( $args, $defaults );
		
		$output = '<div class="sp-table-wrapper">' .
			'<table class="sp-league-table sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

		$data = sportspress_get_league_table_data( $id );

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$columns = sportspress_array_value( $r, 'columns', null );

		$output .= '<th class="data-number">' . __( 'Pos', 'sportspress' ) . '</th>';

		foreach( $labels as $key => $label ):
			if ( ! is_array( $columns ) || $key == 'name' || in_array( $key, $columns ) )
				$output .= '<th class="data-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		if ( is_int( $r['number'] ) && $r['number'] > 0 )
			$data = array_slice( $data, 0, $r['number'] );

		foreach( $data as $team_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Rank
			$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';

			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );

			if ( $r['show_team_logo'] )
				$name = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon' ) . ' ' . $name;

			$output .= '<td class="data-name">' . $name . '</td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				if ( ! is_array( $columns ) || in_array( $key, $columns ) )
					$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		if ( $r['show_full_table_link'] )
			$output .= '<a class="sp-league-table-link" href="' . get_permalink( $id ) . '">' . __( 'View full table', 'sportspress' ) . '</a>';

		$output .= '</div>';

		return apply_filters( 'sportspress_league_table',  $output, $id );

	}
}
