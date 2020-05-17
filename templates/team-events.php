<?php
/**
 * Team Events
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.6.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$format = get_option( 'sportspress_team_events_format', 'blocks' );
if ( 'calendar' === $format ) {
	sp_get_template( 'event-calendar.php', array( 'team' => $id ) );
}
elseif ( 'list' === $format ) {
	$args = array(
		'team' => $id,
		'league' => apply_filters( 'sp_team_events_league', 0 ),
		'season' => apply_filters( 'sp_team_events_season', 0 ),
		'title_format' => 'homeaway',
		'time_format' => 'separate',
		'columns' => array( 'event', 'time', 'results' ),
		'order' => 'DESC',
	);
	$args = apply_filters( 'sp_team_events_list_args', $args );
	sp_get_template( 'event-list.php', $args );
} else {
	sp_get_template( 'event-fixtures-results.php', array( 'team' => $id ) );
}
