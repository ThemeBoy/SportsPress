<?php
if ( !function_exists( 'sportspress_player_list' ) ) {
	function sportspress_player_list( $id = null ) {

		if ( ! $id ):
			global $post;
			$id = $post->ID;
		endif;

		$data = sportspress_get_player_list_data( $id );

		$output = '<table class="sp-player-list sp-data-table">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$output .= '<th class="data-number">#</th>';
		foreach( $labels as $key => $label ):
			$output .= '<th class="data-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		foreach( $data as $player_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Player number
			$number = get_post_meta( $player_id, 'sp_number', true );
			$output .= '<td class="data-number">' . ( $number ? $number : '&nbsp;' ) . '</td>';

			// Name as link
			$permalink = get_post_permalink( $player_id );
			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
			$output .= '<td class="data-name">' . '<a href="' . $permalink . '">' . $name . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		return apply_filters( 'sportspress_player_list',  $output );

	}
}
