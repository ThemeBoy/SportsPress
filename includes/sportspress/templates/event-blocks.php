<?php
/**
 * Event Blocks
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.7.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'event' => null,
	'title' => false,
	'status' => 'default',
	'format' => 'all',
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'date_past' => 'default',
	'date_future' => 'default',
	'date_relative' => 'default',
	'day' => 'default',
	'league' => null,
	'season' => null,
	'venue' => null,
	'team' => null,
	'teams_past' => null,
	'date_before' => null,
	'player' => null,
	'number' => -1,
	'show_team_logo' => get_option( 'sportspress_event_blocks_show_logos', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_event_blocks_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_event_blocks_rows', 5 ),
	'orderby' => 'default',
	'order' => 'default',
	'columns' => null,
	'show_all_events_link' => false,
	'show_title' => get_option( 'sportspress_event_blocks_show_title', 'no' ) == 'yes' ? true : false,
	'show_league' => get_option( 'sportspress_event_blocks_show_league', 'no' ) == 'yes' ? true : false,
	'show_season' => get_option( 'sportspress_event_blocks_show_season', 'no' ) == 'yes' ? true : false,
	'show_matchday' => get_option( 'sportspress_event_blocks_show_matchday', 'no' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_event_blocks_show_venue', 'no' ) == 'yes' ? true : false,
	'hide_if_empty' => false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );

if ( $status != 'default' )
	$calendar->status = $status;
if ( $format != 'default' )
	$calendar->event_format = $format;
if ( $date != 'default' )
	$calendar->date = $date;
if ( $date_from != 'default' )
	$calendar->from = $date_from;
if ( $date_to != 'default' )
	$calendar->to = $date_to;
if ( $date_past != 'default' )
	$calendar->past = $date_past;
if ( $date_future != 'default' )
	$calendar->future = $date_future;
if ( $date_relative != 'default' )
	$calendar->relative = $date_relative;
if ( $event ) 
	$calendar->event = $event;
if ( $league )
	$calendar->league = $league;
if ( $season )
	$calendar->season = $season;
if ( $venue )
	$calendar->venue = $venue;
if ( $team )
	$calendar->team = $team;
if ( $teams_past )
	$calendar->teams_past = $teams_past;
if ( $date_before )
	$calendar->date_before = $date_before;
if ( $player )
	$calendar->player = $player;
if ( $order != 'default' )
	$calendar->order = $order;
if ( $orderby != 'default' )
	$calendar->orderby = $orderby;
if ( $day != 'default' )
	$calendar->day = $day;
$data = $calendar->data();
$usecolumns = $calendar->columns;

if ( isset( $columns ) ):
	if ( is_array( $columns ) )
		$usecolumns = $columns;
	else
		$usecolumns = explode( ',', $columns );
endif;

if ( $hide_if_empty && empty( $data ) ) return false;

if ( $show_title && false === $title && $id ):
	$caption = $calendar->caption;
	if ( $caption )
		$title = $caption;
	else
		$title = get_the_title( $id );
endif;

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
					$results = sp_get_main_results_or_time( $event );

					$teams = array_unique( get_post_meta( $event->ID, 'sp_team' ) );
					$teams = array_filter( $teams, 'sp_filter_positive' );
					$logos = array();
					$event_status = get_post_meta( $event->ID, 'sp_status', true );

					if ( get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ) {
						$teams = array_reverse( $teams , true );
						$results = array_reverse( $results , true );
					}

					if ( $show_team_logo ):
						$j = 0;
						foreach( $teams as $team ):
							$j++;
							$team_name = get_the_title( $team );
							if ( has_post_thumbnail ( $team ) ):
								$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon', array( 'itemprop' => 'logo' ) );

								if ( $link_teams ):
									$team_permalink = get_permalink( $team, false, true );
									$logo = '<a href="' . $team_permalink . '" itemprop="url" content="' . $team_permalink . '">' . $logo . '</a>';
								endif;

								$logo = '<span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . $team_name . '" itemprop="competitor" itemscope itemtype="http://schema.org/SportsTeam"><meta itemprop="name" content="' . $team_name . '">' . $logo . '</span>';
							else:
								$logo = '<span itemprop="competitor" itemscope itemtype="http://schema.org/SportsTeam"><meta itemprop="name" content="' . $team_name . '"></span>';
							endif;

							$logos[] = $logo;
						endforeach;
					endif;
					
					if ( 'day' === $calendar->orderby ):
						$event_group = get_post_meta( $event->ID, 'sp_day', true );
						if ( ! isset( $group ) || $event_group !== $group ):
							$group = $event_group;
							echo '<tr><th><strong class="sp-event-group-name">', __( 'Match Day', 'sportspress' ), ' ', $group, '</strong></th></tr>';
						endif;
					endif;
					?>
					<tr class="sp-row sp-post<?php echo ( $i % 2 == 0 ? ' alternate' : '' ); ?>" itemscope itemtype="http://schema.org/SportsEvent">
						<td>
							<?php do_action( 'sportspress_event_blocks_before', $event, $usecolumns ); ?>
							<?php echo implode( ' ', $logos ); ?>
							<time class="sp-event-date" datetime="<?php echo $event->post_date; ?>" itemprop="startDate" content="<?php echo mysql2date( 'Y-m-d\TH:iP', $event->post_date ); ?>">
								<?php echo sp_add_link( get_the_time( get_option( 'date_format' ), $event ), $permalink, $link_events ); ?>
							</time>
							<?php if ( $show_matchday ): $matchday = get_post_meta( $event->ID, 'sp_day', true ); if ( $matchday != '' ): ?>
								<div class="sp-event-matchday">(<?php echo $matchday; ?>)</div>
							<?php endif; endif; ?>
							<h5 class="sp-event-results">
								<?php echo sp_add_link( '<span class="sp-result '.$event_status.'">' . implode( '</span> - <span class="sp-result">', apply_filters( 'sportspress_event_blocks_team_result_or_time', $results, $event->ID ) ) . '</span>', $permalink, $link_events ); ?>
							</h5>
							<?php if ( $show_league ): $leagues = get_the_terms( $event, 'sp_league' ); if ( $leagues ): $league = array_shift( $leagues ); ?>
								<div class="sp-event-league"><?php echo $league->name; ?></div>
							<?php endif; endif; ?>
							<?php if ( $show_season ): $seasons = get_the_terms( $event, 'sp_season' ); if ( $seasons ): $season = array_shift( $seasons ); ?>
								<div class="sp-event-season"><?php echo $season->name; ?></div>
							<?php endif; endif; ?>
							<?php if ( $show_venue ): $venues = get_the_terms( $event, 'sp_venue' ); if ( $venues ): $venue = array_shift( $venues ); ?>
								<div class="sp-event-venue" itemprop="location" itemscope itemtype="http://schema.org/Place"><div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $venue->name; ?></div></div>
							<?php endif; endif; ?>
							<?php if ( !$show_venue || !$venues ): ?>
								<div style="display:none;" class="sp-event-venue" itemprop="location" itemscope itemtype="http://schema.org/Place"><div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php _e( 'N/A', 'sportspress' ); ?></div></div>
							<?php endif; ?>
							<h4 class="sp-event-title" itemprop="name">
								<?php echo sp_add_link( $event->post_title, $permalink, $link_events ); ?>
							</h4>
							<?php do_action( 'sportspress_event_blocks_after', $event, $usecolumns ); ?>

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
