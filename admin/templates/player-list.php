<?php
if ( !function_exists( 'sportspress_player_list' ) ) {
	function sportspress_player_list( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		$defaults = array(
			'statistics' => null,
			'orderby' => 'number',
			'order' => 'ASC',
		);

		$r = wp_parse_args( $args, $defaults );

		$output = '<div class="sp-table-wrapper">' .
			'<table class="sp-player-list sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

		$data = sportspress_get_player_list_data( $id );

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$statistics = sportspress_array_value( $r, 'statistics', null );

		if ( $r['orderby'] != 'number' || $r['order'] != 'ASC' ):
			global $sportspress_statistic_priorities;
			$sportspress_statistic_priorities = array(
				array(
					'statistic' => $r['orderby'],
					'order' => $r['order'],
				),
			);
			uasort( $data, 'sportspress_sort_list_players' );
		endif;

		if ( $r['orderby'] == 'number' ):
			$output .= '<th class="data-number">#</th>';
		else:
			$output .= '<th class="data-rank">' . __( 'Rank', 'sportspress' ) . '</th>';
		endif;

		foreach( $labels as $key => $label ):
			if ( ! is_array( $statistics ) || $key == 'name' || in_array( $key, $statistics ) )
			$output .= '<th class="data-' . $key . '">'. $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		foreach( $data as $player_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Rank or number
			if ( isset( $r['orderby'] ) && $r['orderby'] != 'number' ):
				$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';
			else:
				$number = get_post_meta( $player_id, 'sp_number', true );
				$output .= '<td class="data-number">' . ( $number ? $number : '&nbsp;' ) . '</td>';
			endif;

			// Name as link
			$permalink = get_post_permalink( $player_id );
			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
			$output .= '<td class="data-name">' . '<a href="' . $permalink . '">' . $name . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				if ( ! is_array( $statistics ) || in_array( $key, $statistics ) )
				$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>' . '</div>';

		return apply_filters( 'sportspress_player_list',  $output );

	}
}
