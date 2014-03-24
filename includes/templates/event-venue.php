<?php
if ( !function_exists( 'sportspress_event_venue' ) ) {
	function sportspress_event_venue( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();

		$venues = get_the_terms( $id, 'sp_venue' );

		$output = '';

		if ( ! $venues )
			return $output;

		foreach( $venues as $venue ):

			$t_id = $venue->term_id;
			$term_meta = get_option( "taxonomy_$t_id" );

			$address = sportspress_array_value( $term_meta, 'sp_address', '' );
			$latitude = sportspress_array_value( $term_meta, 'sp_latitude', 0 );
			$longitude = sportspress_array_value( $term_meta, 'sp_longitude', 0 );
		
			$output .= '<h3>' . __( 'Venue', 'sportspress' ) . '</h3>';
			$output .= '<p><a href="' . get_term_link( $t_id, 'sp_venue' ) . '">' . $venue->name . '</a><br><small>' . $address . '</small></p>';
			if ( $latitude != null && $longitude != null )
				$output .= '<div class="sp-google-map" data-address="' . $address . '" data-latitude="' . $latitude . '" data-longitude="' . $longitude . '"></div>';

		endforeach;

		return apply_filters( 'sportspress_event_venue',  $output );

	}
}
