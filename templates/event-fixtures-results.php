<?php
/**
 * Event Blocks
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'league' => null,
	'season' => null,
	'team' => null,
	'player' => null,
	'number' => -1,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_event_blocks_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_event_blocks_rows', 5 ),
	'show_league' => get_option( 'sportspress_event_blocks_show_league', 'no' ) == 'yes' ? true : false,
	'show_season' => get_option( 'sportspress_event_blocks_show_season', 'no' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_event_blocks_show_venue', 'no' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );
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
if ( $team )
	$calendar->team = $team;
if ( $player )
	$calendar->player = $player;

$args = array(
	'id' => $id,
	'title' => __( 'Fixtures', 'sportspress' ),
	'status' => 'future',
	'date' => $date,
	'date_from' => $date_from,
	'date_to' => $date_to,
	'league' => $league,
	'season' => $season,
	'team' => $team,
	'player' => $player,
	'number' => $number,
	'link_teams' => $link_teams,
	'link_events' => $link_events,
	'paginated' => $paginated,
	'rows' => $rows,
	'order' => 'ASC',
	'show_all_events_link' => false,
	'show_title' => true,
	'show_league' => $show_league,
	'show_season' => $show_season,
	'show_venue' => $show_venue,
	'hide_if_empty' => true,
);

echo '<div class="sp-fixtures-results">';

ob_start();
sp_get_template( 'event-blocks.php', $args );
$fixtures = ob_get_clean();

$args['title'] = __( 'Results', 'sportspress' );
$args['status'] = 'publish';
$args['order'] = 'DESC';

ob_start();
sp_get_template( 'event-blocks.php', $args );
$results = ob_get_clean();

if ( false == $fixtures || false == $results ) {

	echo $fixtures;
	echo $results;
	
} else {

	echo '<div class="sp-widget-align-left">';
	echo $fixtures;
	echo '</div>';

	echo '<div class="sp-widget-align-right">';
	echo $results;
	echo '</div>';
}

echo '</div>';