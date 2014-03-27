<?php
global $sportspress_options;
$main_result = sportspress_array_value( $sportspress_options, 'main_result', null );

$defaults = array(
	'show_all_events_link' => false,
);

extract( $defaults, EXTR_SKIP );

$output = '<div class="sp-table-wrapper">' .
	'<table class="sp-event-list sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

list( $data, $usecolumns ) = sportspress_get_calendar_data( $id, true );

if ( isset( $columns ) )
	$usecolumns = $columns;

$output .= '<th class="data-date">' . SP()->text->string('Date', 'event') . '</th>';

if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
	$output .= '<th class="data-event">' . SP()->text->string('Event', 'event') . '</th>';

if ( $usecolumns == null || in_array( 'teams', $usecolumns ) )
	$output .= '<th class="data-teams">' . SP()->text->string('Teams', 'event') . '</th>';

if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
	$output .= '<th class="data-time">' . SP()->text->string('Time', 'event') . '</th>';

if ( $usecolumns == null || in_array( 'article', $usecolumns ) )
	$output .= '<th class="data-article">' . SP()->text->string('Article', 'event') . '</th>';

$output .= '</tr>' . '</thead>' . '<tbody>';

$i = 0;
foreach ( $data as $event ):
	$teams = get_post_meta( $event->ID, 'sp_team' );
	$results = get_post_meta( $event->ID, 'sp_results', true );
	$video = get_post_meta( $event->ID, 'sp_video', true );

	$output .= '<tr class="sp-row sp-post' . ( $i % 2 == 0 ? ' alternate' : '' ) . '">';

		$output .= '<td class="data-date">' . get_post_time( get_option( 'date_format' ), false, $event ) . '</td>';

		if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
			$output .= '<td class="data-event">' . $event->post_title . '</td>';
	
		if ( $usecolumns == null || in_array( 'teams', $usecolumns ) ):
			$output .= '<td class="data-teams">';

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
		$output .= '<td class="data-time">' . get_post_time( get_option( 'time_format' ), false, $event ) . '</td>';

	if ( $usecolumns == null || in_array( 'article', $usecolumns ) ):
		$output .= '<td class="data-article">
			<a href="' . get_permalink( $event->ID ) . '">';

			if ( $video ):
				$output .= '<div class="dashicons dashicons-video-alt"></div>';
			elseif ( has_post_thumbnail( $event->ID ) ):
				$output .= '<div class="dashicons dashicons-camera"></div>';
			endif;
			if ( $event->post_content !== null ):
				if ( $event->post_status == 'publish' ):
					$output .= SP()->text->string('Recap', 'event');
				else:
					$output .= SP()->text->string('Preview', 'event');
				endif;
			endif;

			$output .= '</a>
		</td>';
	endif;

	$output .= '</tr>';

	$i++;
endforeach;

$output .= '</tbody>' . '</table>';

if ( $id && $show_all_events_link )
	$output .= '<a class="sp-calendar-link" href="' . get_permalink( $id ) . '">' . SP()->text->string('View all events', 'event') . '</a>';

$output .= '</div>';

echo apply_filters( 'sportspress_event_list',  $output );
