<?php
/**
 * Event List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'title' => false,
	'status' => 'default',
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'league' => null,
	'season' => null,
	'number' => -1,
	'show_team_logo' => get_option( 'sportspress_event_list_show_logos', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_venues' => get_option( 'sportspress_link_venues', 'yes' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_event_list_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_event_list_rows', 10 ),
	'order' => 'default',
	'columns' => null,
	'show_all_events_link' => false,
	'show_title' => get_option( 'sportspress_event_list_show_title', 'yes' ) == 'yes' ? true : false,
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
if ( $league )
	$calendar->league = $league;
if ( $season )
	$calendar->season = $season;
if ( $order != 'default' )
	$calendar->order = $order;
$data = $calendar->data();
$usecolumns = $calendar->columns;
$title_format = get_option( 'sportspress_event_list_title_format', 'title' );
$time_format = get_option( 'sportspress_event_list_time_format', 'combined' );

if ( isset( $columns ) ):
	if ( is_array( $columns ) )
		$usecolumns = $columns;
	else
		$usecolumns = explode( ',', $columns );
endif;

if ( $show_title && false === $title && $id ):
	$caption = $calendar->caption;
	if ( $caption )
		$title = $caption;
	else
		$title = get_the_title( $id );
endif;
?>
<div class="sp-template sp-template-event-list">
	<?php if ( $title ) { ?>
		<h4 class="sp-table-caption"><?php echo $title; ?></h4>
	<?php } ?>
	<div class="sp-table-wrapper">
		<table class="sp-event-list sp-data-table<?php if ( $paginated ) { ?> sp-paginated-table<?php } if ( $sortable ) { ?> sp-sortable-table<?php } if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>" data-sp-rows="<?php echo $rows; ?>">
			<thead>
				<tr>
					<?php
					echo '<th class="data-date">' . __( 'Date', 'sportspress' ) . '</th>';

					switch ( $title_format ) {
						case 'homeaway':
							if ( sp_column_active( $usecolumns, 'event' ) ) {
								echo '<th class="data-home">' . __( 'Home', 'sportspress' ) . '</th>';

								if ( 'combined' == $time_format && sp_column_active( $usecolumns, 'time' ) ) {
									echo '<th class="data-time">' . __( 'Time/Results', 'sportspress' ) . '</th>';
								} elseif ( in_array( $time_format, array( 'separate', 'results' ) ) && sp_column_active( $usecolumns, 'results' ) ) {
									echo '<th class="data-results">' . __( 'Results', 'sportspress' ) . '</th>';
								}

								echo '<th class="data-away">' . __( 'Away', 'sportspress' ) . '</th>';

								if ( in_array( $time_format, array( 'separate', 'time' ) ) && sp_column_active( $usecolumns, 'time' ) ) {
									echo '<th class="data-time">' . __( 'Time', 'sportspress' ) . '</th>';
								}
							}
							break;
						default:
							if ( sp_column_active( $usecolumns, 'event' ) ) {
								if ( $title_format == 'teams' )
									echo '<th class="data-teams">' . __( 'Teams', 'sportspress' ) . '</th>';
								else
									echo '<th class="data-event">' . __( 'Event', 'sportspress' ) . '</th>';
							}

							switch ( $time_format ) {
								case 'separate':
									if ( sp_column_active( $usecolumns, 'time' ) )
										echo '<th class="data-time">' . __( 'Time', 'sportspress' ) . '</th>';
									if ( sp_column_active( $usecolumns, 'results' ) )
										echo '<th class="data-results">' . __( 'Results', 'sportspress' ) . '</th>';
									break;
								case 'time':
									if ( sp_column_active( $usecolumns, 'time' ) )
										echo '<th class="data-time">' . __( 'Time', 'sportspress' ) . '</th>';
									break;
								case 'results':
									if ( sp_column_active( $usecolumns, 'results' ) )
										echo '<th class="data-results">' . __( 'Results', 'sportspress' ) . '</th>';
									break;
								default:
									if ( sp_column_active( $usecolumns, 'time' ) )
										echo '<th class="data-time">' . __( 'Time/Results', 'sportspress' ) . '</th>';
							}
					}

					if ( sp_column_active( $usecolumns, 'league' ) )
						echo '<th class="data-league">' . __( 'Competition', 'sportspress' ) . '</th>';

					if ( sp_column_active( $usecolumns, 'season' ) )
						echo '<th class="data-season">' . __( 'Season', 'sportspress' ) . '</th>';

					if ( sp_column_active( $usecolumns, 'venue' ) )
						echo '<th class="data-venue">' . __( 'Venue', 'sportspress' ) . '</th>';

					if ( sp_column_active( $usecolumns, 'article' ) )
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

					$main_results = apply_filters( 'sportspress_event_list_main_results', sp_get_main_results( $event ), $event->ID );

					$teams_output = '';
					$teams_array = array();
					$team_logos = array();

					if ( $teams ):
						foreach ( $teams as $team ):
							$name = get_the_title( $team );
							if ( $name ):

								if ( $show_team_logo ):
									$name = sp_get_logo( $team, 'mini' ) . ' ' . $name;
									$team_logos[] = sp_get_logo( $team, 'mini' );
								endif;

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

						$date_html = '<date>' . get_post_time( 'Y-m-d H:i:s', false, $event ) . '</date>' . get_post_time( get_option( 'date_format' ), false, $event, true );

						if ( $link_events ) $date_html = '<a href="' . get_post_permalink( $event->ID, false, true ) . '">' . $date_html . '</a>';

						echo '<td class="data-date">' . $date_html . '</td>';

						switch ( $title_format ) {
							case 'homeaway':
								if ( sp_column_active( $usecolumns, 'event' ) ) {
									$team = array_shift( $teams_array );
									echo '<td class="data-home">' . $team . '</td>';

									if ( 'combined' == $time_format && sp_column_active( $usecolumns, 'time' ) ) {
										echo '<td class="data-time">';
										if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
										if ( ! empty( $main_results ) ):
											echo implode( ' - ', $main_results );
										else:
											echo '<date>&nbsp;' . get_post_time( 'H:i:s', false, $event ) . '</date>' . sp_get_time( $event );
										endif;
										if ( $link_events ) echo '</a>';
										echo '</td>';
									} elseif ( in_array( $time_format, array( 'separate', 'results' ) ) && sp_column_active( $usecolumns, 'results' ) ) {
										echo '<td class="data-results">';
										if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
										if ( ! empty( $main_results ) ):
											echo implode( ' - ', $main_results );
										else:
											echo '-';
										endif;
										if ( $link_events ) echo '</a>';
										echo '</td>';
									}

									$team = array_shift( $teams_array );
									echo '<td class="data-away">' . $team . '</td>';

									if ( in_array( $time_format, array( 'separate', 'time' ) ) && sp_column_active( $usecolumns, 'time' ) ) {
										echo '<td class="data-time">';
										if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
										echo '<date>&nbsp;' . get_post_time( 'H:i:s', false, $event ) . '</date>' . sp_get_time( $event );
										if ( $link_events ) echo '</a>';
										echo '</td>';
									}
								}
								break;
							default:
								if ( sp_column_active( $usecolumns, 'event' ) ) {
									if ( $title_format == 'teams' ) {
										echo '<td class="data-event data-teams">' . $teams_output . '</td>';
									} else {
										$title_html = implode( ' ', $team_logos ) . ' ' . $event->post_title;
										if ( $link_events ) $title_html = '<a href="' . get_post_permalink( $event->ID, false, true ) . '">' . $title_html . '</a>';
										echo '<td class="data-event">' . $title_html . '</td>';
									}
								}

								switch ( $time_format ) {
									case 'separate':
										if ( sp_column_active( $usecolumns, 'time' ) ) {
											echo '<td class="data-time">';
											if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
											echo '<date>&nbsp;' . get_post_time( 'H:i:s', false, $event ) . '</date>' . sp_get_time( $event );
											if ( $link_events ) echo '</a>';
											echo '</td>';
										}
										if ( sp_column_active( $usecolumns, 'results' ) ) {
											echo '<td class="data-results">';
											if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
											if ( ! empty( $main_results ) ):
												echo implode( ' - ', $main_results );
											else:
												echo '-';
											endif;
											if ( $link_events ) echo '</a>';
											echo '</td>';
										}
										break;
									case 'time':
										if ( sp_column_active( $usecolumns, 'time' ) ) {
											echo '<td class="data-time">';
											if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
											echo '<date>&nbsp;' . get_post_time( 'H:i:s', false, $event ) . '</date>' . sp_get_time( $event );
											if ( $link_events ) echo '</a>';
											echo '</td>';
										}
										break;
									case 'results':
										if ( sp_column_active( $usecolumns, 'results' ) ) {
											echo '<td class="data-results">';
											if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
											if ( ! empty( $main_results ) ):
												echo implode( ' - ', $main_results );
											else:
												echo '-';
											endif;
											if ( $link_events ) echo '</a>';
											echo '</td>';
										}
										break;
									default:
										if ( sp_column_active( $usecolumns, 'time' ) ) {
											echo '<td class="data-time">';
											if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';
											if ( ! empty( $main_results ) ):
												echo implode( ' - ', $main_results );
											else:
												echo '<date>&nbsp;' . get_post_time( 'H:i:s', false, $event ) . '</date>' . sp_get_time( $event );
											endif;
											if ( $link_events ) echo '</a>';
											echo '</td>';
										}
								}
						}

						if ( sp_column_active( $usecolumns, 'league' ) ):
							echo '<td class="data-league">';
							$leagues = get_the_terms( $event->ID, 'sp_league' );
							if ( $leagues ): foreach ( $leagues as $league ):
								echo $league->name;
							endforeach; endif;
							echo '</td>';
						endif;

						if ( sp_column_active( $usecolumns, 'season' ) ):
							echo '<td class="data-season">';
							$seasons = get_the_terms( $event->ID, 'sp_season' );
							if ( $seasons ): foreach ( $seasons as $season ):
								echo $season->name;
							endforeach; endif;
							echo '</td>';
						endif;

						if ( sp_column_active( $usecolumns, 'venue' ) ):
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

						if ( sp_column_active( $usecolumns, 'article' ) ):
							echo '<td class="data-article">';
								if ( $link_events ) echo '<a href="' . get_post_permalink( $event->ID, false, true ) . '">';

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

								if ( $link_events ) echo '</a>';
							echo '</td>';
						endif;

					echo '</tr>';

					$i++;
				endforeach;
				?>
			</tbody>
		</table>
	</div>
	<?php
	if ( $id && $show_all_events_link )
		echo '<div class="sp-calendar-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a></div>';
	?>
</div>