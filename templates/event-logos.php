<?php
/**
 * Event Logos
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9.12
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_event_show_logos', 'yes' ) === 'no' ) return;

$show_team_names = get_option( 'sportspress_event_logos_show_team_names', 'no' ) === 'yes' ? true : false;
$show_results = get_option( 'sportspress_event_logos_show_results', 'no' ) === 'yes' ? true : false;

if ( ! isset( $id ) )
	$id = get_the_ID();

if ( $show_results ) {
	$results = sp_get_main_results( $id );
	if ( empty( $results ) ) {
		$show_results = false;
	}
}

$teams = get_post_meta( $id, 'sp_team' );
$teams = array_filter( $teams, 'sp_filter_positive' );
if ( $teams ):
	$team_logos = array();
	$i = 0;
	foreach ( $teams as $team ):
		if ( ! has_post_thumbnail( $team ) ) {
			$logo = '';
		} else {
			$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon' );
		}

		$alt = sizeof( $teams ) == 2 && $i % 2;

		// Add team name
		if ( $show_team_names ) {
			if ( $alt ) {
				$logo .= ' <strong class="sp-team-name">' . get_the_title( $team ) . '</strong>';
			} else {
				$logo = '<strong class="sp-team-name">' . get_the_title( $team ) . '</strong> ' . $logo;
			}
		}

		// Add link
		if ( get_option( 'sportspress_link_teams', 'no' ) == 'yes' ) $logo = '<a href="' . get_post_permalink( $team ) . '">' . $logo . '</a>';

		// Add result
		if ( $show_results ) {
			$team_result = array_shift( $results );
			$team_result = apply_filters( 'sportspress_event_logos_team_result', $team_result, $id, $team );
			if ( $alt ) {
				$logo = '<strong class="sp-team-result">' . $team_result . '</strong> ' . $logo;
			} else {
				$logo .= ' <strong class="sp-team-result">' . $team_result . '</strong>';
			}
		}

		$team_logos[] = '<span class="sp-team-logo">' . $logo . '</span>';
		$i++;
	endforeach;
	$team_logos = array_filter( $team_logos );
	if ( ! empty( $team_logos ) ):
		echo '<div class="sp-template sp-template-event-logos"><div class="sp-event-logos sp-event-logos-' . sizeof( $teams ) . '">';
		$delimiter = get_option( 'sportspress_event_teams_delimiter', 'vs' );
		echo implode( ' ' . $delimiter . ' ', $team_logos );
		echo '</div></div>';
	endif;
endif;