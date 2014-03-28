<?php
global $sportspress_options;
$primary_result = sportspress_array_value( $sportspress_options, 'sportspress_primary_result', null );

$defaults = array(
	'number' => -1,
	'show_all_events_link' => false,
);

extract( $defaults, EXTR_SKIP );
?>
<div class="sp-table-wrapper">
	<table class="sp-event-list sp-data-table sp-responsive-table">
		<thead>
			<tr>
				<?php
				list( $data, $usecolumns ) = sportspress_get_calendar_data( $id, true );

				if ( isset( $columns ) )
					$usecolumns = $columns;

				echo '<th class="data-date">' . SP()->text->string('Date', 'event') . '</th>';

				if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
					echo '<th class="data-event">' . SP()->text->string('Event', 'event') . '</th>';

				if ( $usecolumns == null || in_array( 'teams', $usecolumns ) )
					echo '<th class="data-teams">' . SP()->text->string('Teams', 'event') . '</th>';

				if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
					echo '<th class="data-time">' . SP()->text->string('Time', 'event') . '</th>';

				if ( $usecolumns == null || in_array( 'venue', $usecolumns ) )
					echo '<th class="data-venue">' . SP()->text->string('Venue', 'event') . '</th>';

				if ( $usecolumns == null || in_array( 'article', $usecolumns ) )
					echo '<th class="data-article">' . SP()->text->string('Article', 'event') . '</th>';
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0;

			if ( is_int( $number ) && $number > 0 )
				$limit = $number;

			foreach ( $data as $event ):
				if ( isset( $limit ) && $i >= $limit ) continue;

				$teams = get_post_meta( $event->ID, 'sp_team' );
				$results = get_post_meta( $event->ID, 'sp_results', true );
				$video = get_post_meta( $event->ID, 'sp_video', true );

				echo '<tr class="sp-row sp-post' . ( $i % 2 == 0 ? ' alternate' : '' ) . '">';

					echo '<td class="data-date">' . get_post_time( get_option( 'date_format' ), false, $event ) . '</td>';

					if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
						echo '<td class="data-event">' . $event->post_title . '</td>';
				
					if ( $usecolumns == null || in_array( 'teams', $usecolumns ) ):
						echo '<td class="data-teams">';

							$teams = get_post_meta( $event->ID, 'sp_team', false );
							if ( $teams ):
								foreach ( $teams as $team ):
									$name = get_the_title( $team );
									if ( $name ):
										$team_results = sportspress_array_value( $results, $team, null );

										if ( $primary_result ):
											$team_result = sportspress_array_value( $team_results, $primary_result, null );
										else:
											if ( is_array( $team_results ) ):
												end( $team_results );
												$team_result = prev( $team_results );
											else:
												$team_result = null;
											endif;
										endif;

										echo $name;

										if ( $team_result != null ):
											echo ' (' . $team_result . ')';
										endif;

										echo '<br>';
									endif;
								endforeach;
							else:
								echo '&mdash;';
							endif;

						echo '</td>';
					endif;

				if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
					echo '<td class="data-time">' . get_post_time( get_option( 'time_format' ), false, $event ) . '</td>';

				if ( $usecolumns == null || in_array( 'venue', $usecolumns ) ):
					echo '<td class="data-venue">';
					the_terms( $event->ID, 'sp_venue' );
					echo '</td>';
				endif;

				if ( $usecolumns == null || in_array( 'article', $usecolumns ) ):
					echo '<td class="data-article">
						<a href="' . get_permalink( $event->ID ) . '">';

						if ( $video ):
							echo '<div class="dashicons dashicons-video-alt"></div>';
						elseif ( has_post_thumbnail( $event->ID ) ):
							echo '<div class="dashicons dashicons-camera"></div>';
						endif;
						if ( $event->post_content !== null ):
							if ( $event->post_status == 'publish' ):
								echo SP()->text->string('Recap', 'event');
							else:
								echo SP()->text->string('Preview', 'event');
							endif;
						endif;

						echo '</a>
					</td>';
				endif;

				echo '</tr>';

				$i++;
			endforeach;
			?>
		</tbody>
	</table>
	<?php
	if ( $id && $show_all_events_link )
		echo '<a class="sp-calendar-link" href="' . get_permalink( $id ) . '">' . SP()->text->string('View all events', 'event') . '</a>';
	?>
</div>