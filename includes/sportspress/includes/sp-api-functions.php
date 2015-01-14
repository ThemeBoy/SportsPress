<?php
/**
 * SportsPress API Functions
 *
 * API functions for admin and front-end templates.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     1.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * General functions
 */

function sp_get_time( $post = 0 ) {
	return get_post_time( get_option( 'time_format' ), false, $post, true );
}

function sp_the_time( $post = 0 ) {
	echo sp_get_time( $post );
}

/*
 * Event functions
 */

function sp_get_status( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->status();
}

function sp_get_results( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->results();
}

function sp_get_teams( $post = 0 ) {
	return get_post_meta( $post, 'sp_team' );
}

function sp_get_main_results( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->main_results();
}

function sp_the_main_results( $post = 0, $delimiter = '-' ) {
	$results = sp_get_main_results( $post );
	echo implode( $delimiter, $results );
}

function sp_get_main_results_or_time( $post = 0 ) {
	$results = sp_get_main_results( $post );
	if ( sizeof( $results ) ) {
		return $results;
	} else {
		return array( sp_get_time( $post ) );
	}
}

function sp_the_main_results_or_time( $post = 0, $delimiter = '-' ) {
	echo implode( $delimiter, sp_get_main_results_or_time( $post ) );
}

function sp_get_performance( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->performance();
}

function sp_event_logos( $post = 0 ) {
	sp_get_template( 'event-logos.php', array( 'id' => $post ) );
}

function sp_event_video( $post = 0 ) {
	sp_get_template( 'event-video.php', array( 'id' => $post ) );
}

function sp_event_results( $post = 0 ) {
	sp_get_template( 'event-results.php', array( 'id' => $post ) );
}

function sp_event_details( $post = 0 ) {
	sp_get_template( 'event-details.php', array( 'id' => $post ) );
}

function sp_event_venue( $post = 0 ) {
	sp_get_template( 'event-venue.php', array( 'id' => $post ) );
}

function sp_event_staff( $post = 0 ) {
	sp_get_template( 'event-staff.php', array( 'id' => $post ) );
}

function sp_event_performance( $post = 0 ) {
	sp_get_template( 'event-performance.php', array( 'id' => $post ) );
}

/*
 * Calendar functions
 */

function sp_get_calendar( $post = 0 ) {
	$calendar = new SP_Calendar( $post );
	return $calendar->data();
}

function sp_event_calendar( $post = 0 ) {
	sp_get_template( 'event-calendar.php', array( 'id' => $post ) );
}

function sp_event_list( $post = 0 ) {
	sp_get_template( 'event-list.php', array( 'id' => $post ) );
}

function sp_event_blocks( $post = 0 ) {
	sp_get_template( 'event-blocks.php', array( 'id' => $post ) );
}

/*
 * Team functions
 */

function sp_has_logo( $post = 0 ) {
	return has_post_thumbnail ( $post );
}

function sp_get_logo( $post = 0, $size = 'icon', $attr = array() ) {
	return get_the_post_thumbnail( $post, 'sportspress-fit-' . $size, $attr );
}

function sp_the_logo( $post = 0, $size = 'icon', $attr = array() ) {
	echo sp_get_logo( $post, $size, $attr );
}

function sp_team_logo( $post = 0 ) {
	sp_get_template( 'team-logo.php', array( 'id' => $post ) );
}

function sp_team_details( $post = 0 ) {
	sp_get_template( 'team-details.php', array( 'id' => $post ) );
}

function sp_team_link( $post = 0 ) {
	sp_get_template( 'team-link.php', array( 'id' => $post ) );
}

function sp_team_lists( $post = 0 ) {
	sp_get_template( 'team-lists.php', array( 'id' => $post ) );
}

function sp_team_tables( $post = 0 ) {
	sp_get_template( 'team-tables.php', array( 'id' => $post ) );
}

/*
 * League Table functions
 */

function sp_get_table( $post = 0 ) {
	$table = new SP_League_Table( $post );
	return $table->data();
}

function sp_league_table( $post = 0 ) {
	sp_get_template( 'league-table.php', array( 'id' => $post ) );
}

/*
 * Player functions
 */

function sp_player_details( $post = 0 ) {
	sp_get_template( 'player-details.php', array( 'id' => $post ) );
}

function sp_player_photo( $post = 0 ) {
	sp_get_template( 'player-photo.php', array( 'id' => $post ) );
}

function sp_player_statistics( $post = 0 ) {
	sp_get_template( 'player-statistics.php', array( 'id' => $post ) );
}

/*
 * Player List functions
 */

function sp_get_list( $post = 0 ) {
	$list = new SP_Player_List( $post );
	return $list->data();
}

function sp_player_list( $post = 0 ) {
	sp_get_template( 'player-list.php', array( 'id' => $post ) );
}

/*
 * Staff functions
 */

function sp_staff_details( $post = 0 ) {
	sp_get_template( 'staff-details.php', array( 'id' => $post ) );
}

function sp_staff_photo( $post = 0 ) {
	sp_get_template( 'staff-photo.php', array( 'id' => $post ) );
}

/*
 * Venue functions
 */

function sp_venue_map( $term = 0 ) {
    $meta = get_option( "taxonomy_$term" );
	sp_get_template( 'venue-map.php', array( 'meta' => $meta ) );
}
