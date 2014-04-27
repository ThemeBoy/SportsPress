<?php
/**
 * Event List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$primary_result = get_option( 'sportspress_primary_result', null );

$defaults = array(
	'status' => 'default',
	'number' => -1,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_calendar_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_calendar_rows', 10 ),
	'order' => 'default',
	'show_all_events_link' => false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );
if ( $status != 'default' )
	$calendar->status = $status;
if ( $order != 'default' )
	$calendar->order = $order;
$data = $calendar->data();
$usecolumns = $calendar->columns;

if ( isset( $columns ) )
	$usecolumns = $columns;
?>
<div class="sp-table-wrapper">
	<table class="sp-event-list sp-data-table<?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $paginated ) { ?> sp-paginated-table<?php } ?>" data-sp-rows="<?php echo $rows; ?>">
		<thead>
			<tr>
				<?php
				echo '<th class="data-date">' . SP()->text->string('Date') . '</th>';

				if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
					echo '<th class="data-event">' . SP()->text->string('Event') . '</th>';

				if ( $usecolumns == null || in_array( 'teams', $usecolumns ) )
					echo '<th class="data-teams">' . SP()->text->string('Teams') . '</th>';

				if ( $usecolumns == null || in_array( 'time', $usecolumns ) )
					echo '<th class="data-time">' . SP()->text->string('Time') . '</th>';

				if ( $usecolumns == null || in_array( 'venue', $usecolumns ) )
					echo '<th class="data-venue">' . SP()->text->string('Venue') . '</th>';

				if ( $usecolumns == null || in_array( 'article', $usecolumns ) )
					echo '<th class="data-article">' . SP()->text->string('Article') . '</th>';
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

					echo '<td class="data-date"><a href="' . get_permalink( $event->ID ) . '">' . get_post_time( get_option( 'date_format' ), false, $event ) . '</a></td>';

					if ( $usecolumns == null || in_array( 'event', $usecolumns ) )
						echo '<td class="data-event">' . $event->post_title . '</td>';
				
					if ( $usecolumns == null || in_array( 'teams', $usecolumns ) ):
						echo '<td class="data-teams">';

							$teams = get_post_meta( $event->ID, 'sp_team', false );
							if ( $teams ):
								foreach ( $teams as $team ):
									$name = get_the_title( $team );
									if ( $name ):
										$team_results = sp_array_value( $results, $team, null );

										if ( $primary_result ):
											$team_result = sp_array_value( $team_results, $primary_result, null );
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
								echo SP()->text->string('Recap');
							else:
								echo SP()->text->string('Preview');
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
		echo '<a class="sp-calendar-link sp-view-all-link" href="' . get_permalink( $id ) . '">' . SP()->text->string('View all events') . '</a>';
	?>
</div>