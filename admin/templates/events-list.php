<?php
if ( !function_exists( 'sportspress_events_list' ) ) {
	function sportspress_events_list( $id = null, $args = '' ) {

		global $sportspress_options;
		$main_result = sportspress_array_value( $sportspress_options, 'main_result', null );

		$defaults = array(
			'show_all_events_link' => false,
		);

		$r = wp_parse_args( $args, $defaults );

		$output = '<div class="sp-table-wrapper">' .
			'<table class="sp-events-list sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

		list( $data, $usecolumns ) = sportspress_get_calendar_data( $id, true );

		if ( isset( $r['columns'] ) )
			$usecolumns = $r['columns'];

		$output .= '<th class="column-date">' . __( 'Date', 'sportspress' ). '</th>';

		if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
			$output .= '<th class="column-event">' . __( 'Event', 'sportspress' ). '</th>';

		if ( $usecolumns == null || in_array( 'teams', $usecolumns ) )
			$output .= '<th class="column-teams">' . __( 'Teams', 'sportspress' ). '</th>';

		if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
			$output .= '<th class="column-time">' . __( 'Time', 'sportspress' ). '</th>';

		if ( $usecolumns == null || in_array( 'article', $usecolumns ) )
			$output .= '<th class="column-article">' . __( 'Article', 'sportspress' ). '</th>';

		$output .= '</tr>' . '</thead>' . '<tbody>';

		$i = 0;
		foreach ( $data as $event ):
			$teams = get_post_meta( $event->ID, 'sp_team' );
			$results = get_post_meta( $event->ID, 'sp_results', true );
			$video = get_post_meta( $event->ID, 'sp_video', true );

			$output .= '<tr class="sp-row sp-post' . ( $i % 2 == 0 ? ' alternate' : '' ) . '">';

				$output .= '<td class="column-date">' . get_post_time( get_option( 'date_format' ), false, $event ) . '</td>';

				if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
					$output .= '<td class="column-event">' . $event->post_title . '</td>';
			
				if ( $usecolumns == null || in_array( 'teams', $usecolumns ) ):
					$output .= '<td class="column-teams">';

						$teams = get_post_meta( $event->ID, 'sp_team', false );
						if ( $teams ):
							foreach ( $teams as $team ):
								$name = get_the_title( $team );
								if ( $name ):
									$team_results = sportspress_array_value( $results, $team, null );

									if ( $main_result ):
										$team_result = sportspress_array_value( $team_results, $main_result, null );
									else:
										if ( is_array( $team_results ) ):
											end( $team_results );
											$team_result = prev( $team_results );
										else:
											$team_result = null;
										endif;
									endif;

									$output .= $name;

									if ( $team_result != null ):
										$output .= ' (' . $team_result . ')';
									endif;

									$output .= '<br>';
								endif;
							endforeach;
						else:
							$output .= '&mdash;';
						endif;

					$output .= '</td>';
				endif;

			if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
				$output .= '<td class="column-time">' . get_post_time( get_option( 'time_format' ), false, $event ) . '</td>';

			if ( $usecolumns == null || in_array( 'article', $usecolumns ) ):
				$output .= '<td class="column-article">
					<a href="' . get_permalink( $event->ID ) . '#sp_articlediv">';

					if ( $video ):
						$output .= '<div class="dashicons dashicons-video-alt"></div>';
					elseif ( has_post_thumbnail( $event->ID ) ):
						$output .= '<div class="dashicons dashicons-camera"></div>';
					endif;
					if ( $event->post_content !== null ):
						if ( $event->post_status == 'publish' ):
							$output .= __( 'Recap', 'sportspress' );
						else:
							$output .= __( 'Preview', 'sportspress' );
						endif;
					endif;

					$output .= '</a>
				</td>';
			endif;

			$output .= '</tr>';

			$i++;
		endforeach;

		$output .= '</tbody>' . '</table>';

		if ( $id && $r['show_all_events_link'] )
			$output .= '<a class="sp-calendar-link" href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a>';

		$output .= '</div>';

		return apply_filters( 'sportspress_events_list',  $output );

	}
}

function sportspress_events_list_shortcode( $atts ) {
	if ( isset( $atts['id'] ) ):
		$id = $atts['id'];
		unset( $atts['id'] );
	elseif( isset( $atts[0] ) ):
		$id = $atts[0];
		unset( $atts[0] );
	else:
		$id = null;
	endif;
    return sportspress_events_list( $id, $atts );
}
add_shortcode('events-list', 'sportspress_events_list_shortcode');
