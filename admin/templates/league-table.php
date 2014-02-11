<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		$defaults = array(
			'columns' => null,
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

		foreach( $data as $team_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Rank
			$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';

			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
			$output .= '<td class="data-name"><a href="' . get_post_permalink( $team_id ) . '">' . $name . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				if ( ! is_array( $columns ) || in_array( $key, $columns ) )
					$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>' . '</div>';

		return apply_filters( 'sportspress_league_table',  $output, $id );

	}
}
