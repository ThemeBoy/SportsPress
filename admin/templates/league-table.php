<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		$defaults = array(
			'number_label' => __( 'Pos', 'sportspress' ),
			'thumbnails' => 0,
			'thumbnail_size' => 'thumbnail'
		);

		$r = wp_parse_args( $args, $defaults );

		$leagues = get_the_terms( $id, 'sp_league' );
		$seasons = get_the_terms( $id, 'sp_season' );

		$terms = array();
		if ( sizeof( $leagues ) ):
			$league = reset( $leagues );
			$terms[] = $league->name;
		endif;
		if ( sizeof( $seasons ) ):
			$season = reset( $seasons );
			$terms[] = $season->name;
		endif;

		$title = sizeof( $terms ) ? implode( ' &mdash; ', $terms ) : get_the_title( $id );

		$output = '<h4 class="sp-table-caption">' . $title . '</h4>' .
			'<div class="sp-table-wrapper">' .
			'<table class="sp-league-table sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

		$data = sportspress_get_league_table_data( $id );

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

		$output .= '</tbody>' . '</table>' . '</div>';

		return apply_filters( 'sportspress_league_table',  $output );

	}
}
