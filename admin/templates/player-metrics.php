<?php
if ( !function_exists( 'sportspress_player_metrics' ) ) {
	function sportspress_player_metrics( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();

		global $sportspress_countries;

		$number = get_post_meta( $id, 'sp_number', true );
		$nationality = get_post_meta( $id, 'sp_nationality', true );
		$current_team = get_post_meta( $id, 'sp_current_team', true );
		$past_teams = get_post_meta( $id, 'sp_past_team', false );
		$metrics = sportspress_get_player_metrics_data( $id );

		$common = array();

		if ( $number != null )
			$common[ __( 'Number', 'sportspress' ) ] = $number;
		if ( $nationality )
			$common[ __( 'Nationality', 'sportspress' ) ] = sportspress_array_value( $sportspress_countries, $nationality, '&mdash;' );

		$data = array_merge( $common, $metrics );

		if ( $current_team )
			$data[ __( 'Current Team', 'sportspress' ) ] = '<a href="' . get_post_permalink( $current_team ) . '">' . get_the_title( $current_team ) . '</a>';

		if ( $past_teams ):
			$teams = array();
			foreach ( $past_teams as $team ):
				$teams[] = '<a href="' . get_post_permalink( $team ) . '">' . get_the_title( $team ) . '</a>';
			endforeach;
			$data[ __( 'Past Teams', 'sportspress' ) ] = implode( ', ', $teams );
		endif;

		$output = '<div class="sp-table-wrapper">' .
			'<table class="sp-player-metrics sp-data-table sp-responsive-table">' . '<tbody>';

		$i = 0;

		foreach( $data as $label => $value ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '"><th>' . $label . '</th><td>' . $value . '</td></tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>' . '</div>';

		return apply_filters( 'sportspress_player_metrics',  $output );

	}
}
