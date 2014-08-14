<?php
/**
 * Event List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$primary_result = get_option( 'sportspress_primary_result', null );

$defaults = array(
	'id' => null,
	'status' => 'default',
	'date' => 'default',
	'number' => -1,
	'link_teams' => get_option( 'sportspress_calendar_link_teams', 'no' ) == 'yes' ? true : false,
	'link_venues' => get_option( 'sportspress_calendar_link_venues', 'yes' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_calendar_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_calendar_rows', 10 ),
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
if ( $order != 'default' )
	$calendar->order = $order;
$data = $calendar->data();
$usecolumns = $calendar->columns;

if ( isset( $columns ) ):
	if ( is_array( $columns ) )
		$usecolumns = $columns;
	else
		$usecolumns = explode( ',', $columns );
endif;
?>
<div class="sp-table-wrapper sp-scrollable-table-wrapper">
	<table class="sp-event-list sp-data-table<?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $paginated ) { ?> sp-paginated-table<?php } ?>" data-sp-rows="<?php echo $rows; ?>">
		<thead>
			<tr>
				<?php
				echo '<th class="data-date">' . __( 'Date', 'sportspress' ) . '</th>';

				if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
					echo '<th class="data-event">' . __( 'Event', 'sportspress' ) . '</th>';

				if ( $usecolumns == null || in_array( 'teams', $usecolumns ) )
					echo '<th class="data-teams">' . __( 'Teams', 'sportspress' ) . '</th>';

				if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
					echo '<th class="data-time">' . __( 'Time/Results', 'sportspress' ) . '</th>';

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
				$results = get_post_meta( $event->ID, 'sp_results', true );
				$video = get_post_meta( $event->ID, 'sp_video', true );

				$main_results = array();
				$teams_output = '';

				if ( $teams ):
					foreach ( $teams as $team ):
						$name = get_the_title( $team );
						if ( $name ):
							$team_results = sp_array_value( $results, $team, null );

							if ( $primary_result ):
								$team_result = sp_array_value( $team_results, $primary_result, null );
							else:
								if ( is_array( $team_results ) ):
									unset( $team_results['outcome'] );
									$team_result = end( $team_results );
								else:
									$team_result = null;
								endif;
							endif;

							if ( $link_teams ):
								$teams_output .= '<a href="' . get_post_permalink( $team ) . '">' . $name . '</a>';
							else:
								$teams_output .= $name;
							endif;

							if ( $team_result != null ):
								$main_results[] = $team_result;
								$teams_output .= ' (' . $team_result . ')';
							endif;

							$teams_output .= '<br>';
						endif;
					endforeach;
				else:
					$teams_output .= '&mdash;';
				endif;

				echo '<tr class="sp-row sp-post' . ( $i % 2 == 0 ? ' alternate' : '' ) . '">';

					echo '<td class="data-date"><a href="' . get_permalink( $event->ID ) . '">' . get_post_time( get_option( 'date_format' ), false, $event, true ) . '</a></td>';

					if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
						echo '<td class="data-event">' . $event->post_title . '</td>';

					if ( $usecolumns == null || in_array( 'teams', $usecolumns ) ):
						echo '<td class="data-teams">';
							echo $teams_output;
						echo '</td>';
					endif;

				if ( $usecolumns == null || in_array( 'time', $usecolumns ) ):
					echo '<td class="data-time">';
					if ( ! empty( $main_results ) ):
						echo implode( ' - ', $main_results );
					else:
						echo get_post_time( get_option( 'time_format' ), false, $event, true );
					endif;
					echo '</td>';
				endif;

				if ( $usecolumns == null || in_array( 'venue', $usecolumns ) ):
					echo '<td class="data-venue">';
					if ( $link_venues ):
						the_terms( $event->ID, 'sp_venue' );
					else:
						$venues = get_the_terms( $event->ID, 'sp_venue' );
						foreach ( $venues as $venue ):
							echo $venue->name;
						endforeach;
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
<?php
if ( $id && $show_all_events_link )
	echo '<a class="sp-calendar-link sp-view-all-link" href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a>';
?>