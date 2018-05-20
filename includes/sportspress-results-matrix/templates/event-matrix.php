<?php
/**
 * Event Matrix
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.6.4
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
	'date_format' => get_option( 'sportspress_event_matrix_date_format', 'M j' ),
	'show_team_logo' => get_option( 'sportspress_event_matrix_show_logos', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
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
	'orderby' => array(
		'menu_order' => 'ASC',
		'title' => 'ASC',
	),
);

$teams = array_filter( get_post_meta( $id, 'sp_team', false ) );

if ( ! empty( $teams ) ) {
	$args['include'] = $teams;
} else {
	$leagues = sp_get_the_term_ids( $id, 'sp_league' );
	$seasons = sp_get_the_term_ids( $id, 'sp_season' );

	if ( $leagues ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'sp_league',
			'field' => 'term_id',
			'terms' => $leagues,
		);
	}

	if ( $seasons ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'sp_season',
			'field' => 'term_id',
			'terms' => $seasons,
		);
	}
}

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
	if ( ! array_key_exists( $event_teams[0], $team_names ) ) continue;
	if ( ! array_key_exists( $event_teams[1], $team_names ) ) continue;
	$rows[ $event_teams[0] ][ $event_teams[1] ][] = $event->ID;
}

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
<div class="sp-template sp-template-event-matrix">
	<div class="sp-table-wrapper">
		<table class="sp-event-matrix sp-data-table<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
			<thead>
				<th class="sp-event-matrix-home-label"><?php _e( 'Home', 'sportspress' ); ?> \ <?php _e( 'Away', 'sportspress' ); ?></th>
				<?php foreach ( $rows as $team_id => $row ) { ?>
					<th class="sp-event-matrix-label">
						<?php
						if ( $show_team_logo && has_post_thumbnail( $team_id ) ) {
							$name = '<span class="sp-event-matrix-team-logo">' . sp_get_logo( $team_id, 'mini', array( 'itemprop' => 'url' ) ) . '</span>';
						} else {
							$name = sp_team_abbreviation( $team_id, true );
						}

						if ( $link_teams ) {
							echo '<a href="' . get_post_permalink( $team_id ) . '" title="' . $team_names[ $team_id ] . '">' . $name . '</a>';
						} else {
							echo '<span title="' . $team_names[ $team_id ] . '">' . $name . '</span>';
						}
						?>	
					</th>
				<?php } ?>
			<tbody>
				<?php foreach ( $rows as $home_id => $row ) { ?>
					<tr>
						<td class="sp-event-matrix-home-label">
							<?php
							$name = sp_team_short_name( $home_id );
							
							if ( $show_team_logo && has_post_thumbnail( $home_id ) ) {
								$name = '<span class="sp-event-matrix-team-logo">' . sp_get_logo( $home_id, 'mini', array( 'itemprop' => 'url' ) ) . '</span>' . $name;
							}

							if ( $link_teams ) {
								echo '<a href="' . get_post_permalink( $home_id ) . '" title="' . $team_names[ $home_id ] . '">' . $name . '</a>';
							} else {
								echo '<span title="' . $team_names[ $home_id ] . '">' . $name . '</span>';
							}
							?>
						</td>
						<?php foreach ( $row as $away_id => $events ) { ?>
							<?php if ( $home_id === $away_id ) { ?>
								<td class="sp-event-matrix-empty-cell">
									&nbsp;
								</td>
							<?php } else { ?>
								<td class="sp-event-matrix-cell">
									<?php
									$results = array();
									foreach ( $events as $event ) {
										$outcome = sp_array_value( sp_get_outcome( $event ), $home_id, null );
										$result = implode( '-', sp_get_main_results_or_date( $event, $date_format ) );
										$color = sp_array_value( $colors, $outcome, '#888888' );

										if ( $link_events ) {
											if ( $outcome ) {
												$result = '<a class="sp-event-matrix-event sp-event-matrix-event-' . $outcome . '" style="background-color: ' . $color . ';" href="' . get_post_permalink( $event, false, true ) . '">' . $result . '</a>';
											} else {
												$result = '<a class="sp-event-matrix-future-event" href="' . get_post_permalink( $event, false, true ) . '">' . $result . '</a>';
											}
										} else {
											$result = '<span class="sp-event-matrix-event sp-event-matrix-event-' . $outcome . '" style="background-color: ' . $color . ';">' . $result . '</span>';
										}

										$results[] = $result;
									}
									echo sizeof( $results ) ? implode( ' ', $results ) : '<span class="sp-event-matrix-empty-event">&mdash;</span>';
									?>
								</td>
							<?php } ?>
						<?php } ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php
	if ( $id && $show_all_events_link )
		echo '<div class="sp-calendar-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a></div>';
	?>
</div>