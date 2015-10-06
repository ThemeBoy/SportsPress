<?php
/**
 * Event Blocks
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
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_event_blocks_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_event_blocks_rows', 10 ),
	'order' => 'default',
	'show_all_events_link' => false,
	'show_title' => get_option( 'sportspress_event_blocks_show_title', 'no' ) == 'yes' ? true : false,
	'show_league' => get_option( 'sportspress_event_blocks_show_league', 'no' ) == 'yes' ? true : false,
	'show_season' => get_option( 'sportspress_event_blocks_show_season', 'no' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_event_blocks_show_venue', 'no' ) == 'yes' ? true : false,
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

if ( $show_title && false === $title && $id ):
	$caption = $calendar->caption;
	if ( $caption )
		$title = $caption;
	else
		$title = get_the_title( $id );
endif;

if ( isset( $columns ) ) {
	$usecolumns = $columns;
}

if ( $title )
	echo '<h4 class="sp-table-caption">' . $title . '</h4>';
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

					$permalink = get_post_permalink( $event, false, true );
					$results = get_post_meta( $event->ID, 'sp_results', true );

					$teams = array_unique( get_post_meta( $event->ID, 'sp_team' ) );
					$teams = array_filter( $teams, 'sp_filter_positive' );
					$logos = array();

					$j = 0;
					foreach( $teams as $team ):
						$j++;
						if ( has_post_thumbnail ( $team ) ):
							if ( $link_teams ):
								$logo = '<a class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" href="' . get_permalink( $team, false, true ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'sportspress-fit-icon' ) . '</a>';
							else:
								$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon', array( 'class' => 'team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) ) );
							endif;
							$logos[] = $logo;
						endif;
					endforeach;
					?>
					<tr class="sp-row sp-post<?php echo ( $i % 2 == 0 ? ' alternate' : '' ); ?>">
						<td>
							<?php echo implode( $logos, ' ' ); ?>
							<time class="sp-event-date" datetime="<?php echo $event->post_date; ?>">
								<?php echo sp_add_link( get_the_time( get_option( 'date_format' ), $event ), $permalink, $link_events ); ?>
							</time>
							<h5 class="sp-event-results">
								<?php echo sp_add_link( '<span class="sp-result">' . implode( '</span>-<span class="sp-result">', apply_filters( 'sportspress_event_blocks_team_result_or_time', sp_get_main_results_or_time( $event ), $event->ID ) ), $permalink, $link_events . '</span>' ); ?>
							</h5>
							<?php if ( $show_league ): $leagues = get_the_terms( $event, 'sp_league' ); if ( $leagues ): $league = array_shift( $leagues ); ?>
								<div class="sp-event-league"><?php echo $league->name; ?></div>
							<?php endif; endif; ?>
							<?php if ( $show_season ): $seasons = get_the_terms( $event, 'sp_season' ); if ( $seasons ): $season = array_shift( $seasons ); ?>
								<div class="sp-event-season"><?php echo $season->name; ?></div>
							<?php endif; endif; ?>
							<?php if ( $show_venue ): $venues = get_the_terms( $event, 'sp_venue' ); if ( $venues ): $venue = array_shift( $venues ); ?>
								<div class="sp-event-venue"><?php echo $venue->name; ?></div>
							<?php endif; endif; ?>
							<h4 class="sp-event-title">
								<?php echo sp_add_link( $event->post_title, $permalink, $link_events ); ?>
							</h4>

						</td>
					</tr>
					<?php
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