<?php
/**
 * Event List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'status' => 'default',
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'number' => -1,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_venues' => get_option( 'sportspress_link_venues', 'yes' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_event_list_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_event_list_rows', 10 ),
	'order' => 'default',
	'columns' => null,
	'show_all_events_link' => false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );
if ( $status != 'default' )
	$calendar->status = $status;
if ( $date != 'default' )
	$calendar->date = $date;
if ( $date_from != 'default' )
	$calendar->from = $date_from;
if ( $date_to != 'default' )
	$calendar->to = $date_to;
if ( $order != 'default' )
	$calendar->order = $order;
$data = $calendar->data();
$usecolumns = $calendar->columns;
$title_format = $calendar->title_format;

if ( isset( $columns ) ):
	if ( is_array( $columns ) )
		$usecolumns = $columns;
	else
		$usecolumns = explode( ',', $columns );
endif;
?>
<div class="sp-template sp-template-event-list">
	<div class="sp-table-wrapper<?php if ( $scrollable ) { ?> sp-scrollable-table-wrapper<?php } ?>">
		<table class="sp-event-list sp-data-table<?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $paginated ) { ?> sp-paginated-table<?php } if ( $sortable ) { ?> sp-sortable-table<?php } ?>" data-sp-rows="<?php echo $rows; ?>">
			<thead>
				<tr>
					<?php
					echo '<th class="data-date">' . __( 'Date', 'sportspress' ) . '</th>';

					if ( $usecolumns == null || in_array( 'event', $usecolumns ) ):
						if ( $title_format == 'homeaway' ):
							echo '<th class="data-home">' . __( 'Home', 'sportspress' ) . '</th>';
						elseif ( $title_format == 'teams' ):
							echo '<th class="data-teams">' . __( 'Teams', 'sportspress' ) . '</th>';
						else:
							echo '<th class="data-event">' . __( 'Event', 'sportspress' ) . '</th>';
						endif;
					endif;

					if ( $usecolumns == null || in_array( 'time', $usecolumns ) ):
						if ( $usecolumns == null || in_array( 'event', $usecolumns ) && $title_format == 'homeaway' )
							echo '<th class="data-time">&nbsp;</th>';
						else
							echo '<th class="data-time">' . __( 'Time/Results', 'sportspress' ) . '</th>';
					endif;

					if ( $usecolumns == null || in_array( 'event', $usecolumns ) && $title_format == 'homeaway' )
						echo '<th class="data-away">' . __( 'Away', 'sportspress' ) . '</th>';

					if ( $usecolumns == null || in_array( 'league', $usecolumns ) )
						echo '<th class="data-league">' . __( 'Competition', 'sportspress' ) . '</th>';

					if ( $usecolumns == null || in_array( 'season', $usecolumns ) )
						echo '<th class="data-season">' . __( 'Season', 'sportspress' ) . '</th>';

					if ( $usecolumns == null || in_array( 'venue', $usecolumns ) )
						echo '<th class="data-venue">' . __( 'Venue', 'sportspress' ) . '</th>';

					if ( $usecolumns == null || in_array( 'article', $usecolumns ) )
						echo '<th class="data-article">' . __( 'Article', 'sportspress' ) . '</th>';
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;

				if ( is_numeric( $number ) && $number > 0 )
					$limit = $number;

				foreach ( $data as $event ):
					if ( isset( $limit ) && $i >= $limit ) continue;

					$teams = get_post_meta( $event->ID, 'sp_team' );
					$video = get_post_meta( $event->ID, 'sp_video', true );

					$main_results = sp_get_main_results( $event );

					$teams_output = '';
					$teams_array = '';

					if ( $teams ):
						foreach ( $teams as $team ):
							$name = get_the_title( $team );
							if ( $name ):
								if ( $link_teams ):
									$team_output = '<a href="' . get_post_permalink( $team ) . '">' . $name . '</a>';
								else:
									$team_output = $name;
								endif;

								$team_result = sp_array_value( $main_results, $team, null );

								if ( $team_result != null ):
									if ( $usecolumns != null && ! in_array( 'time', $usecolumns ) ):
										$team_output .= ' (' . $team_result . ')';
									endif;
								endif;

								$teams_array[] = $team_output;

								$teams_output .= $team_output . '<br>';
							endif;
						endforeach;
					else:
						$teams_output .= '&mdash;';
					endif;

					echo '<tr class="sp-row sp-post' . ( $i % 2 == 0 ? ' alternate' : '' ) . '">';

						echo '<td class="data-date"><a href="' . get_permalink( $event->ID ) . '"><date>' . get_post_time( 'Y-m-d H:i:s', false, $event ) . '</date>' . get_post_time( get_option( 'date_format' ), false, $event, true ) . '</a></td>';

						if ( $usecolumns == null || in_array( 'event', $usecolumns ) ):
							if ( $title_format == 'homeaway' ):
								$team = array_shift( $teams_array );
								echo '<td class="data-home">' . $team . '</td>';
							else:
								if ( $title_format == 'teams' ):
									echo '<td class="data-event">' . $teams_output . '</td>';
								else:
									echo '<td class="data-event"><a href="' . get_permalink( $event->ID ) . '">' . $event->post_title . '</a></td>';
								endif;
							endif;
						endif;

						if ( $usecolumns == null || in_array( 'time', $usecolumns ) ):
							echo '<td class="data-time"><a href="' . get_permalink( $event->ID ) . '">';
							if ( ! empty( $main_results ) ):
								echo implode( ' - ', $main_results );
							else:
								echo '<date>&nbsp;' . get_post_time( 'H:i:s', false, $event ) . '</date>' . sp_get_time( $event );
							endif;
							echo '</a></td>';
						endif;

						if ( $usecolumns == null || in_array( 'event', $usecolumns ) && $title_format == 'homeaway' ):
							$team = array_shift( $teams_array );
							echo '<td class="data-away">' . $team . '</td>';
						endif;

						if ( $usecolumns == null || in_array( 'league', $usecolumns ) ):
							echo '<td class="data-league">';
							$leagues = get_the_terms( $event->ID, 'sp_league' );
							if ( $leagues ): foreach ( $leagues as $league ):
								echo $league->name;
							endforeach; endif;
							echo '</td>';
						endif;

						if ( $usecolumns == null || in_array( 'season', $usecolumns ) ):
							echo '<td class="data-season">';
							$seasons = get_the_terms( $event->ID, 'sp_season' );
							if ( $seasons ): foreach ( $seasons as $season ):
								echo $season->name;
							endforeach; endif;
							echo '</td>';
						endif;

						if ( $usecolumns == null || in_array( 'venue', $usecolumns ) ):
							echo '<td class="data-venue">';
							if ( $link_venues ):
								the_terms( $event->ID, 'sp_venue' );
							else:
								$venues = get_the_terms( $event->ID, 'sp_venue' );
								if ( $venues ): foreach ( $venues as $venue ):
									echo $venue->name;
								endforeach; endif;
							endif;
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
										_e( 'Recap', 'sportspress' );
									else:
										_e( 'Preview', 'sportspress' );
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
	</div>
</div>
<?php
if ( $id && $show_all_events_link )
	echo '<a class="sp-calendar-link sp-view-all-link" href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a>';
?>