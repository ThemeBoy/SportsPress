<?php
/**
 * Player Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_player_show_details', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$defaults = array(
	'show_nationality_flags' => get_option( 'sportspress_player_show_flags', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$countries = SP()->countries->countries;

$player = new SP_Player( $id );

$nationality = $player->nationality;
$positions = $player->positions();
$current_teams = $player->current_teams();
$past_teams = $player->past_teams();
$metrics_before = $player->metrics( true );
$metrics_after = $player->metrics( false );

$common = array();
if ( $nationality ):
	if ( 2 == strlen( $nationality ) ):
		$legacy = SP()->countries->legacy;
		$nationality = strtolower( $nationality );
		$nationality = sp_array_value( $legacy, $nationality, null );
	endif;
	$country_name = sp_array_value( $countries, $nationality, null );
	$common[ __( 'Nationality', 'sportspress' ) ] = $country_name ? ( $show_nationality_flags ? '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/flags/' . strtolower( $nationality ) . '.png" alt="' . $nationality . '"> ' : '' ) . $country_name : '&mdash;';
endif;

if ( $positions ):
	$position_names = array();
	foreach ( $positions as $position ):
		$position_names[] = $position->name;
	endforeach;
	$common[ __( 'Position', 'sportspress' ) ] = implode( ', ', $position_names );
endif;

$data = array_merge( $metrics_before, $common, $metrics_after );

if ( $current_teams ):
	$teams = array();
	foreach ( $current_teams as $team ):
		$team_name = get_the_title( $team );
		if ( $link_teams ) $team_name = '<a href="' . get_post_permalink( $team ) . '">' . $team_name . '</a>';
		$teams[] = $team_name;
	endforeach;
	$data[ __( 'Current Team', 'sportspress' ) ] = implode( ', ', $teams );
endif;

if ( $past_teams ):
	$teams = array();
	foreach ( $past_teams as $team ):
		$team_name = get_the_title( $team );
		if ( $link_teams ) $team_name = '<a href="' . get_post_permalink( $team ) . '">' . $team_name . '</a>';
		$teams[] = $team_name;
	endforeach;
	$data[ __( 'Past Teams', 'sportspress' ) ] = implode( ', ', $teams );
endif;

$data = apply_filters( 'sportspress_player_details', $data, $id );

if ( sizeof( $data ) ) {
	$output = '<div class="sp-template sp-template-player-details sp-template-details"><div class="sp-list-wrapper"><dl class="sp-player-details">';

	foreach( $data as $label => $value ):

		$output .= '<dt>' . $label . '</dt><dd>' . $value . '</dd>';

	endforeach;

	$output .= '</dl></div></div>';

	echo $output;
}
