<?php
/**
 * Event Logos Inline
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.6
 */

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
			$logo .= ' <strong class="sp-team-name">' . sp_team_short_name( $team ) . '</strong>';
		} else {
			$logo = '<strong class="sp-team-name">' . sp_team_short_name( $team ) . '</strong> ' . $logo;
		}
	}

	// Add link
	if ( $link_teams ) $logo = '<a href="' . get_post_permalink( $team ) . '">' . $logo . '</a>';

	// Add result
	if ( $show_results && ! empty( $results ) ) {
		$team_result = array_shift( $results );
		$team_result = apply_filters( 'sportspress_event_logos_team_result', $team_result, $id, $team );
		if ( $alt ) {
			$logo = '<strong class="sp-team-result">' . $team_result . '</strong> ' . $logo;
		} else {
			$logo .= ' <strong class="sp-team-result">' . $team_result . '</strong>';
		}
	}

	// Add logo to array
	if ( '' !== $logo ) {
		$team_logos[] = '<span class="sp-team-logo">' . $logo . '</span>';
		$i++;
	}
endforeach;
$team_logos = array_filter( $team_logos );
if ( ! empty( $team_logos ) ):
	echo '<div class="sp-template sp-template-event-logos sp-template-event-logos-inline"><div class="sp-event-logos sp-event-logos-' . sizeof( $teams ) . '">';

	// Assign delimiter
	if ( $show_time && sizeof( $teams ) <= 2 ) {
		$delimiter = '<strong class="sp-event-logos-time sp-team-result">' . apply_filters( 'sportspress_event_time', get_the_time( get_option('time_format'), $id ), $id ) . '</strong>';
	} else {
		$delimiter = get_option( 'sportspress_event_teams_delimiter', 'vs' );
	}

	echo implode( ' ' . $delimiter . ' ', $team_logos );
	echo '</div></div>';
endif;