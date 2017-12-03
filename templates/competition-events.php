<?php
/**
 * Competition Events
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$format = get_option( 'sportspress_competition_events_format', 'blocks' );
$league = get_the_terms( $id, 'sp_league' )[0]->term_id;
$season = get_the_terms( $id, 'sp_season' )[0]->term_id;
if ( 'calendar' === $format )
	sp_get_template( 'event-calendar.php', array( 'competition' => $id ) );
elseif ( 'list' === $format )
	sp_get_template( 'event-list.php', array( 'competition' => $id, 'league'=> $league, 'season'=> $season, 'order' => 'DESC', 'title_format' => 'homeaway', 'time_format' => 'separate', 'columns' => array( 'event', 'time', 'results' ) ) );
else
	sp_get_template( 'event-fixtures-results.php', array( 'competition' => $id ) );
