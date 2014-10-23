<?php
/**
 * Event Logos
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_event_show_logos', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$teams = get_post_meta( $id, 'sp_team' );
$teams = array_filter( $teams );
if ( $teams ):
	$team_logos = array();
	foreach ( $teams as $team ):
		if ( ! has_post_thumbnail( $team ) ) continue;
		$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon' );
		if ( get_option( 'sportspress_link_teams', 'no' ) == 'yes' ) $logo = '<a href="' . get_post_permalink( $team ) . '">' . $logo . '</a>';
		$team_logos[] = $logo;
	endforeach;
	$team_logos = array_filter( $team_logos );
	if ( ! empty( $team_logos ) ):
		echo '<div class="sp-template sp-template-event-logos sp-event-logos">';
		$delimiter = get_option( 'sportspress_event_teams_delimiter', 'vs' );
		echo implode( ' ' . $delimiter . ' ', $team_logos );
		echo '</div>';
	endif;
endif;