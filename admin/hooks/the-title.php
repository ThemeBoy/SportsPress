<?php
function sportspress_the_title( $title, $id ) {
	if ( is_singular( 'sp_player' ) && in_the_loop() && $id == get_the_ID() ):
		$number = get_post_meta( $id, 'sp_number', true );
		if ( $number != null ):
			$title = '<strong>' . $number . '</strong> ' . $title;
		endif;
	endif;
	return $title;
}
add_filter( 'the_title', 'sportspress_the_title', 10, 2 );
