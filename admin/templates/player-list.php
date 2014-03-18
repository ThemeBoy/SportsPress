<?php
if ( !function_exists( 'sportspress_player_list' ) ) {
	function sportspress_player_list( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		global $sportspress_options;

		$defaults = array(
			'number' => -1,
			'statistics' => null,
			'orderby' => 'default',
			'order' => 'ASC',
			'show_all_players_link' => false,
			'link_posts' => sportspress_array_value( $sportspress_options, 'player_list_link_posts', true ),
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

		if ( in_array( $r['orderby'], array( 'number', 'name' ) ) ):
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

		if ( is_int( $r['number'] ) && $r['number'] > 0 )
			$limit = $r['number'];

		foreach( $data as $player_id => $row ):
			if ( isset( $limit ) && $i >= $limit ) continue;
		
			$name = sportspress_array_value( $row, 'name', null );
			if ( ! $name ) continue;

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

			// Rank or number
			if ( isset( $r['orderby'] ) && $r['orderby'] != 'number' ):
				$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';
			else:
				$number = get_post_meta( $player_id, 'sp_number', true );
				$output .= '<td class="data-number">' . ( $number ? $number : '&nbsp;' ) . '</td>';
			endif;

			if ( $r['link_posts'] ):
				$permalink = get_post_permalink( $player_id );
				$name = '<a href="' . $permalink . '">' . $name . '</a>';
			endif;

			$output .= '<td class="data-name">' . $name . '</td>';

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

		if ( $r['show_all_players_link'] )
			$output .= '<a class="sp-player-list-link" href="' . get_permalink( $id ) . '">' . __( 'View all players', 'sportspress' ) . '</a>';

		return apply_filters( 'sportspress_player_list',  $output );

	}
}

function sportspress_player_list_shortcode( $atts ) {
	if ( isset( $atts['id'] ) ):
		$id = $atts['id'];
		unset( $atts['id'] );
	elseif( isset( $atts[0] ) ):
		$id = $atts[0];
		unset( $atts[0] );
	else:
		$id = null;
	endif;
    return sportspress_player_list( $id, $atts );
}
add_shortcode('player-list', 'sportspress_player_list_shortcode');