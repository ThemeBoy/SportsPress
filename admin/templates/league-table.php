<?php
if ( !function_exists( 'sportspress_league_table' ) ) {
	function sportspress_league_table( $id = null, $args = '' ) {

		if ( ! $id || ! is_numeric( $id ) )
			$id = get_the_ID();

		global $sportspress_options;

		$defaults = array(
			'number' => -1,
			'columns' => null,
			'show_full_table_link' => false,
			'show_team_logo' => sportspress_array_value( $sportspress_options, 'league_table_show_team_logo', false ),
			'link_posts' => sportspress_array_value( $sportspress_options, 'league_table_link_posts', false ),
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

		if ( ! $columns )
			$columns = get_post_meta( $id, 'sp_columns', true );

		if ( ! is_array( $columns ) )
			$columns = explode( ',', $columns );

		$output .= '<th class="data-rank">' . __( 'Pos', 'sportspress' ) . '</th>';

		foreach( $labels as $key => $label ):
			if ( ! is_array( $columns ) || $key == 'name' || in_array( $key, $columns ) )
				$output .= '<th class="data-' . $key . '">' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;

		if ( is_int( $r['number'] ) && $r['number'] > 0 )
			$limit = $r['number'];

		foreach( $data as $team_id => $row ):

			if ( isset( $limit ) && $i >= $limit ) continue;

			$name = sportspress_array_value( $row, 'name', null );
			if ( ! $name ) continue;

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Rank
			$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';

			if ( $r['show_team_logo'] )
				$name = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon', array( 'class' => 'team-logo' ) ) . ' ' . $name;

			if ( $r['link_posts'] ):
				$permalink = get_post_permalink( $team_id );
				$name = '<a href="' . $permalink . '">' . $name . '</a>';
			endif;

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

function sportspress_league_table_shortcode( $atts ) {
	if ( isset( $atts['id'] ) ):
		$id = $atts['id'];
		unset( $atts['id'] );
	elseif( isset( $atts[0] ) ):
		$id = $atts[0];
		unset( $atts[0] );
	else:
		$id = null;
	endif;
    return sportspress_league_table( $id, $atts );
}
add_shortcode('league-table', 'sportspress_league_table_shortcode');
