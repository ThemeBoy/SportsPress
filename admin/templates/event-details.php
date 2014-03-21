<?php
if ( !function_exists( 'sportspress_event_details' ) ) {
	function sportspress_event_details( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();

		$date = get_the_time( get_option('date_format'), $id );
		$time = get_the_time( get_option('time_format'), $id );
		$leagues = get_the_terms( $id, 'sp_league' );
		$seasons = get_the_terms( $id, 'sp_season' );

		$data = array( __( 'Date', 'sportspress' ) => $date, __( 'Time', 'sportspress' ) => $time );

		if ( $leagues ):
			$league = array_pop( $leagues );
			$data[ __( 'League', 'sportspress' ) ] = $league->name;
		endif;

		if ( $seasons ):
			$season = array_pop( $seasons );
			$data[ __( 'Season', 'sportspress' ) ] = $season->name;
		endif;

		$output = '<h3>' . __( 'Details', 'sportspress' ) . '</h3>';

		$output .= '<div class="sp-table-wrapper">' .
			'<table class="sp-event-details sp-data-table"><tbody>';
		
		$i = 0;

		foreach( $data as $label => $value ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';
			$output .= '<th>' . $label . '</th>';
			$output .= '<td>' . $value . '</td>';
			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody></table></div>';

		return apply_filters( 'sportspress_event_details',  $output );

	}
}
