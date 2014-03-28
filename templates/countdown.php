<?php
if ( isset( $id ) ):
	$post = get_post( $id );
else:
	$args = array();
	if ( isset( $team ) )
		$args = array( 'key' => 'sp_team', 'value' => $team );
	$post = sp_get_next_event( $args );
endif;

$output = '';

if ( isset( $post ) ):
	$output .= '<div id="sp-countdown-wrapper">';
	$output .= '<h3 class="event-name"><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h3>';

	if ( isset( $show_league ) && $show_league ):
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		if ( $leagues ):
			foreach( $leagues as $league ):
				$term = get_term( $league->term_id, 'sp_league' );
				$output .= '<h5 class="event-league">' . $term->name . '</h5>';
			endforeach;
		endif;
	endif;

	$now = new DateTime( current_time( 'mysql', 0 ) );
	$date = new DateTime( $post->post_date );
	$interval = date_diff( $now, $date );

	$output .= '<p class="countdown sp-countdown"><time datetime="' . $post->post_date . '" data-countdown="' . str_replace( '-', '/', $post->post_date ) . '">' .
		'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->days ) ) . ' <small>' . __( 'days', 'sportspress' ) . '</small></span> ' .
		'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->h ) ) . ' <small>' . __( 'hrs', 'sportspress' ) . '</small></span> ' .
		'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->i ) ) . ' <small>' . __( 'mins', 'sportspress' ) . '</small></span> ' .
		'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->s ) ) . ' <small>' . __( 'secs', 'sportspress' ) . '</small></span>' .
	'</time></p>';

	$output .= '</div>';
else:
	return false;
endif;

echo apply_filters( 'sportspress_countdown', $output );
