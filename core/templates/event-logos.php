<?php
/**
 * Event Logos
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

if ( get_option( 'sportspress_event_show_logos', 'yes' ) == 'yes' ):
	$teams = get_post_meta( $id, 'sp_team' );
	$teams = array_filter( $teams );
	if ( $teams ):
		$team_logos = array();
		foreach ( $teams as $team ):
			$team_logos[] = get_the_post_thumbnail( $team, 'sportspress-fit-icon' );
		endforeach;
		$team_logos = array_filter( $team_logos );
		if ( ! empty( $team_logos ) ):
			echo '<div class="sp-template sp-template-event-logos sp-event-logos">';
			$delimiter = get_option( 'sportspress_event_teams_delimiter', 'vs' );
			echo implode( ' ' . $delimiter . ' ', $team_logos );
			echo '</div>';
		endif;
	endif;
endif;