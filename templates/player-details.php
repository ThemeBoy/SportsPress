<?php
/**
 * Player Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
$current_teams = $player->current_teams();
$past_teams = $player->past_teams();
$metrics_before = $player->metrics( true );
$metrics_after = $player->metrics( false );

$common = array();
if ( $nationality ):
	$country_name = sp_array_value( $countries, $nationality, null );
	$common[ __( 'Nationality', 'sportspress' ) ] = $country_name ? ( $show_nationality_flags ? '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . '/assets/images/flags/' . strtolower( $nationality ) . '.png" alt="' . $nationality . '"> ' : '' ) . $country_name : '&mdash;';
endif;

$position_names = array();
$positions = get_the_terms( $id, 'sp_position' );
if ( $positions ): foreach ( $positions as $position ):
	$position_names[] = $position->name;
endforeach; endif;

$common[ __( 'Position', 'sportspress' ) ] = implode( ', ', $position_names );

$data = array_merge( $metrics_before, $common, $metrics_after );

if ( $current_teams ):
	$teams = array();
	foreach ( $current_teams as $team ):
		$teams[] = '<a href="' . get_post_permalink( $team ) . '">' . get_the_title( $team ) . '</a>';
	endforeach;
	$label = _n( 'Current Team', 'Current Teams', count( $teams ), 'sportspress' );
	$data[ $label ] = implode( ', ', $teams );
endif;

if ( $past_teams ):
	$teams = array();
	foreach ( $past_teams as $team ):
		$teams[] = '<a href="' . get_post_permalink( $team ) . '">' . get_the_title( $team ) . '</a>';
	endforeach;
	$data[ __( 'Past Teams', 'sportspress' ) ] = implode( ', ', $teams );
endif;

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-player-details">';

foreach( $data as $label => $value ):

	$output .= '<dt>' . $label . '<dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';

echo $output;
