<?php
/**
 * Event Blocks
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$primary_result = get_option( 'sportspress_primary_result', null );

$defaults = array(
	'status' => 'default',
	'date' => 'default',
	'number' => -1,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_event_blocks_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_event_blocks_rows', 10 ),
	'order' => 'default',
	'show_all_events_link' => false,
	'show_league' => get_option( 'sportspress_event_blocks_show_league', 'no' ) == 'yes' ? true : false,
	'show_season' => get_option( 'sportspress_event_blocks_show_season', 'no' ) == 'yes' ? true : false,
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

if ( isset( $columns ) )
	$usecolumns = $columns;
?>
<div class="sp-template sp-template-event-blocks">
	<div class="sp-table-wrapper">
		<table class="sp-event-blocks sp-data-table<?php if ( $paginated ) { ?> sp-paginated-table<?php } ?>" data-sp-rows="<?php echo $rows; ?>">
			<thead><tr><th></th></tr></thead> <?php # Required for DataTables ?>
			<tbody>
				<?php
				$i = 0;

				if ( intval( $number ) > 0 )
					$limit = $number;

				foreach ( $data as $event ):
					if ( isset( $limit ) && $i >= $limit ) continue;

					$results = get_post_meta( $event->ID, 'sp_results', true );

					$teams = array_unique( get_post_meta( $event->ID, 'sp_team' ) );
					$logos = array();
					$main_results = array();

					$j = 0;
					foreach( $teams as $team ):
						$j++;
						if ( has_post_thumbnail ( $team ) ):
							if ( $link_teams ):
								$logo = '<a href="' . get_post_permalink( $team ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'sportspress-fit-icon', array( 'class' => 'team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) ) ) . '</a>';
							else:
								$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon', array( 'class' => 'team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) ) );
							endif;
							$logos[] = $logo;
						endif;
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
						if ( $team_result != null )
							$main_results[] = $team_result;

					endforeach;
					?>
					<tr class="sp-row sp-post<?php echo ( $i % 2 == 0 ? ' alternate' : '' ); ?>">
						<td>
							<?php echo implode( $logos, ' ' ); ?>
							<time class="event-date"><?php echo get_the_time( get_option( 'date_format' ), $event ); ?></time>
							<?php if ( ! empty( $main_results ) ): ?>
								<h5 class="event-results"><?php echo implode( $main_results, ' - ' ); ?></h5>
							<?php else: ?>
								<h5 class="event-time"><?php echo get_the_time( get_option( 'time_format' ), $event ); ?></h5>
							<?php endif; ?>
							<?php if ( $show_league ): $leagues = get_the_terms( $event, 'sp_league' ); if ( $leagues ): $league = array_shift( $leagues ); ?>
								<div class="event-league"><?php echo $league->name; ?></div>
							<?php endif; endif; ?>
							<?php if ( $show_season ): $seasons = get_the_terms( $event, 'sp_season' ); if ( $seasons ): $season = array_shift( $seasons ); ?>
								<div class="event-season"><?php echo $season->name; ?></div>
							<?php endif; endif; ?>
							<h4 class="event-title"><a href="<?php echo get_post_permalink( $event ); ?>"><?php echo $event->post_title; ?></a></h4>
						</td>
					</tr>
					<?php
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