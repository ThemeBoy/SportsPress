<?php
if ( !function_exists( 'sportspress_player_metrics' ) ) {
	function sportspress_player_metrics( $id = null ) {

		if ( ! $id ):
			global $post;
			$id = $post->ID;
		endif;

		global $sportspress_countries;

		$number = get_post_meta( $id, 'sp_number', true );
		$nationality = get_post_meta( $id, 'sp_nationality', true );
		$metrics = sportspress_get_player_metrics_data( $id );

		$common = array(
			__( 'Number', 'sportspress' ) => $number,
			__( 'Nationality', 'sportspress' ) => sportspress_array_value( $sportspress_countries, $nationality, '&mdash;' ),
		);

		$data = array_merge( $common, $metrics );

		$output = '<table class="sp-player-metrics sp-data-table">' . '<tbody>';

		$i = 0;

		foreach( $data as $label => $value ):

			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '"><th>' . $label . '</th><td>' . $value . '</td></tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		return apply_filters( 'sportspress_player_metrics',  $output );

	}
}
