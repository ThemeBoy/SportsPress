<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id = null, $args = '' ) {

		if ( ! $id ):
			global $post;
			$id = $post->ID;
		endif;

		$defaults = array(
			'number_label' => __( 'Pos', 'sportspress' ),
			'thumbnails' => 1,
			'thumbnail_size' => 'thumbnail'
		);

		$r = wp_parse_args( $args, $defaults );

		$data = sportspress_get_league_table_data( $id );

		$output = '<table class="sp-league-table sp-data-table">' .
		'<caption>' . get_the_title( $id ) . '</caption>' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$output .= '<th class="data-number">' . $r['number_label'] . '</th>';
		foreach( $labels as $key => $label ):
			$output .= '<th class="data-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		foreach( $data as $team_id => $row ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Position as number
			$output .= '<td class="data-number">' . $i . '</td>';

			// Thumbnail and name as link
			$permalink = get_post_permalink( $team_id );
			if ( $r['thumbnails'] ):
				$thumbnail = get_the_post_thumbnail( $team_id, $r['thumbnail_size'], array( 'class' => 'logo' ) );
			else:
				$thumbnail = null;
			endif;
			$name = sportspress_array_value( $row, 'name', sportspress_array_value( $row, 'name', '&nbsp;' ) );
			$output .= '<td class="data-name">' . ( $thumbnail ? $thumbnail . ' ' : '' ) . '<a href="' . $permalink . '">' . $name . '</a></td>';

			foreach( $labels as $key => $value ):
				if ( $key == 'name' )
					continue;
				$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		return apply_filters( 'sportspress_league_table',  $output );

	}
}
