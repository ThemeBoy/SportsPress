<?php
/**
 * Team Events
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$format = get_option( 'sportspress_team_events_format', 'blocks' );
if ( 'calendar' === $format )
	sp_get_template( 'event-calendar.php', array( 'team' => $id ) );
elseif ( 'list' === $format )
	sp_get_template( 'event-list.php', array( 'team' => $id, 'order' => 'DESC', 'title_format' => 'homeaway', 'time_format' => 'separate', 'columns' => array( 'event', 'time', 'results' ) ) );
else
	sp_get_template( 'event-fixtures-results.php', array( 'team' => $id ) );
