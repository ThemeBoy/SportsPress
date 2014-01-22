<?php
if ( !function_exists( 'sportspress_event_staff' ) ) {
	function sportspress_event_staff( $id = null ) {

		if ( ! $id )
			$id = get_the_ID();
		$staff = (array)get_post_meta( $id, 'sp_staff', false );

		$output = '';

		return apply_filters( 'sportspress_event_staff',  $output );

	}
}
