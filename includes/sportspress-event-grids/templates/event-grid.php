<?php
/**
 * Event Grid
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'status' => 'default',
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'date_past' => 'default',
	'date_future' => 'default',
	'date_relative' => 'default',
	'day' => 'default',
	'league' => null,
	'season' => null,
	'date_format' => get_option( 'sportspress_event_grid_date_format', 'M j' ),
	'show_team_logo' => get_option( 'sportspress_event_grid_show_logos', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'abbreviate_teams' => get_option( 'sportspress_abbreviate_teams', 'yes' ) === 'yes' ? true : false,
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
if ( $date_past != 'default' )
	$calendar->past = $date_past;
if ( $date_future != 'default' )
	$calendar->future = $date_future;
if ( $date_relative != 'default' )
	$calendar->relative = $date_relative;
if ( $league )
	$calendar->league = $league;
if ( $season )
	$calendar->season = $season;
if ( $day != 'default' )
	$calendar->day = $day;

$data = $calendar->data();
if ( ! $data ) return;

$args = array(
	'post_type' => 'sp_team',
	'numberposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'title',
	'order' => 'ASC',
);
$teams = get_posts( $args );

$rows = array();
$team_names = array();
foreach ( $teams as $team ) {
	$rows[ $team->ID ] = array();
	$team_names[ $team->ID ] = $team->post_title;
}

foreach ( $data as $event ) {
	$event_teams = array_filter( (array) get_post_meta( $event->ID, 'sp_team', false ) );
	if ( sizeof( $event_teams ) <= 1 ) continue;
	$rows[ $event_teams[0] ][ $event_teams[1] ][] = $event->ID;
}

$rows = array_filter( $rows );

$keys = array_fill_keys( array_keys( $rows ), array() );
foreach ( $rows as $team_id => $row ) {
	$rows[ $team_id ] = array_replace_recursive( $keys, $row );
}
		
// Get outcomes colors
$colors = array();

$args = array(
	'post_type' => 'sp_outcome',
	'post_status' => 'publish',
	'posts_per_page' => -1,
);
$posts = get_posts( $args );

if ( $posts ) {
	foreach ( $posts as $post ) {
		$id = $post->ID;
		$color = get_post_meta( $id, 'sp_color', true );
		if ( '' == $color ) $color = '#888888';
		$colors[ $post->post_name ] = $color;
	}
}
?>
<div class="sp-template sp-template-event-grid">
	<div class="sp-event-grid-wrapper">
		<div class="sp-event-grid-content">
			<table class="sp-event-grid sp-data-table">
				<thead>
					<th>&nbsp;</th>
					<?php foreach ( $rows as $team_id => $row ) { ?>
						<th><?php sp_short_name( $team_id ); ?></th>
					<?php } ?>
				<tbody>
					<?php foreach ( $rows as $home_id => $row ) { ?>
						<tr>
							<th>
								<?php if ( $show_team_logo && has_post_thumbnail( $home_id ) ) { ?>
									<span class="team-logo"><?php echo sp_get_logo( $home_id, 'mini', array( 'itemprop' => 'url' ) ); ?></span>
								<?php } ?>
								<?php echo $team_names[ $home_id ]; ?>
								</th>
							<?php foreach ( $row as $away_id => $events ) { ?>
								<td>
									<?php if ( $home_id === $away_id ) { ?>
										&nbsp;
									<?php
									} else {
										$results = array();
										foreach ( $events as $event ) {
											$result = implode( '-', sp_get_main_results_or_date( $event, $date_format ) );

											$outcome = sp_array_value( sp_get_outcome( $event ), $home_id, null );
											$color = sp_array_value( $colors, $outcome, '#888888' );

											if ( $link_events ) {
												$result = '<a class="sp-event-grid-event sp-event-grid-event-' . $outcome . '" style="color: ' . $color . ';" href="' . get_post_permalink( $event, false, true ) . '">' . $result . '</a>';
											} else {
												$result = '<span class="sp-event-grid-event-' . $outcome . '" style="color: ' . $color . ';">' . $result . '</span>';
											}

											$results[] = $result;
										}
										echo sizeof( $results ) ? implode( '<br>', $results ) : '<span class="sp-event-grid-empty-event">&mdash;</span>';
									}
									?>
								</td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
	if ( $id && $show_all_events_link )
		echo '<div class="sp-calendar-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a></div>';
	?>
</div>