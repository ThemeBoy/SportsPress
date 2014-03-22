<?php
if ( !function_exists( 'sportspress_countdown' ) ) {
	function sportspress_countdown( $id = null, $args = array() ) {

		$show_league = sportspress_array_value( $args, 'show_league', null );

		if ( $id ):
			$post = get_post( $id );
		else:
			$args = array();
			if ( isset( $args['team'] ) )
				$args = array( 'key' => 'sp_team', 'value' => $args['team'] );
			$post = sportspress_get_next_event( $args );
		endif;

		$output = '';

		if ( isset( $post ) ):
			$output .= '<div id="sp-countdown-wrapper">';
			$output .= '<h3 class="event-name"><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h3>';

			if ( $show_league ):
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

		return apply_filters( 'sportspress_countdown', $output );

	}
}

function sportspress_countdown_shortcode( $atts ) {
	if ( isset( $atts['id'] ) ):
		$id = $atts['id'];
		unset( $atts['id'] );
	elseif( isset( $atts[0] ) ):
		$id = $atts[0];
		unset( $atts[0] );
	else:
		$id = null;
	endif;
    return sportspress_countdown( $id, $atts );
}
add_shortcode('countdown', 'sportspress_countdown_shortcode');
