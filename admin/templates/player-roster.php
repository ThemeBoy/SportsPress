<?php
if ( !function_exists( 'sportspress_player_roster' ) ) {
	function sportspress_player_roster( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		$defaults = array(
			'statistics' => null,
			'orderby' => 'default',
			'order' => 'ASC',
		);

		$r = wp_parse_args( $args, $defaults );

		$output = '';

		$data = sportspress_get_player_roster_data( $id );

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$statistics = sportspress_array_value( $r, 'statistics', null );

		if ( $r['orderby'] == 'default' ):
			$r['orderby'] = get_post_meta( $id, 'sp_orderby', true );
			$r['order'] = get_post_meta( $id, 'sp_order', true );
		else:
			global $sportspress_statistic_priorities;
			$sportspress_statistic_priorities = array(
				array(
					'statistic' => $r['orderby'],
					'order' => $r['order'],
				),
			);
			uasort( $data, 'sportspress_sort_list_players' );
		endif;

		$positions = get_terms ( 'sp_position' );

		foreach ( $positions as $position ):
			$rows = '';
			$i = 0;

			foreach ( $data as $player_id => $row ):

				if ( ! in_array( $position->term_id, $row['positions']) )
					continue;

				$rows .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

				// Rank or number
				if ( isset( $r['orderby'] ) && $r['orderby'] != 'number' ):
					$rows .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';
				else:
					$number = get_post_meta( $player_id, 'sp_number', true );
					$rows .= '<td class="data-number">' . ( $number ? $number : '&nbsp;' ) . '</td>';
				endif;

				// Name as link
				$permalink = get_post_permalink( $player_id );
				$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
				$rows .= '<td class="data-name">' . '<a href="' . $permalink . '">' . $name . '</a></td>';

				foreach( $labels as $key => $value ):
					if ( $key == 'name' )
						continue;
					if ( ! is_array( $statistics ) || in_array( $key, $statistics ) )
					$rows .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
				endforeach;

				$rows .= '</tr>';

				$i++;

			endforeach;

			if ( ! empty( $rows ) ):
				$output .= '<h4 class="sp-table-caption">' . $position->name . '</h4>';
				$output .= '<div class="sp-table-wrapper">' .
					'<table class="sp-player-list sp-player-roster sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';
				if ( in_array( $r['orderby'], array( 'number', 'name' ) ) ):
					$output .= '<th class="data-number">#</th>';
				else:
					$output .= '<th class="data-rank">' . __( 'Rank', 'sportspress' ) . '</th>';
				endif;

				foreach( $labels as $key => $label ):
					if ( ! is_array( $statistics ) || $key == 'name' || in_array( $key, $statistics ) )
					$output .= '<th class="data-' . $key . '">'. $label . '</th>';
				endforeach;
				$output .= '</tr>' . '</thead>' . '<tbody>' . $rows . '</tbody>' . '</table>' . '</div>';
			endif;

		endforeach;

		return apply_filters( 'sportspress_player_roster',  $output );

	}
}
