<?php
if ( !function_exists( 'sportspress_countdown' ) ) {
	function sportspress_countdown( $args = array() ) {

		$id = sportspress_array_value( $args, 'id', null );

		if ( $id ):
			$post = get_post( $id );
		else:
			$options = array(
				'post_type' => 'sp_event',
				'posts_per_page' => 1,
				'order' => 'ASC',
				'post_status' => 'future',
				'meta_query' => array(),
			);
			if ( isset( $args['team'] ) )
				$options['meta_query'][] = array( 'key' => 'sp_team', 'value' => $args['team'] );

			$posts = get_posts( $options );
			$post = array_pop( $posts );
		endif;

		$output = '';

		if ( isset( $post ) ):
			$output .= '<div id="sp_countdown_wrap">';
			$output .= '<h3 class="event-name"><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h3>';

			$leagues = get_the_terms( $post->ID, 'sp_league' );
			if ( $leagues ):
				foreach( $leagues as $league ):
					$term = get_term( $league->term_id, 'sp_league' );
					$output .= '<h5 class="event-league">' . $term->name . '</h5>';
				endforeach;
			endif;

			$now = new DateTime( current_time( 'mysql', 0 ) );
			$date = new DateTime( $post->post_date );
			$interval = date_diff( $now, $date );

			$output .= '<h3 class="countdown sp-countdown"><time datetime="' . $post->post_date . '" data-countdown="' . str_replace( '-', '/', $post->post_date ) . '">' .
				'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->d ) ) . ' <small>' . __( 'days', 'sportspress' ) . '</small></span> ' .
				'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->h ) ) . ' <small>' . __( 'hrs', 'sportspress' ) . '</small></span> ' .
				'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->i ) ) . ' <small>' . __( 'mins', 'sportspress' ) . '</small></span> ' .
				'<span>' . sprintf( '%02s', ( $interval->invert ? 0 : $interval->s ) ) . ' <small>' . __( 'secs', 'sportspress' ) . '</small></span>' .
			'</time></h3>';

			$output .= '</div>';
		else:
			return false;
		endif;

		return apply_filters( 'sportspress_countdown', $output );

	}
}
