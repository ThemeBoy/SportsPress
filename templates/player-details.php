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
);

extract( $defaults, EXTR_SKIP );

$countries = SP()->countries->countries;

$nationality = get_post_meta( $id, 'sp_nationality', true );
$current_team = get_post_meta( $id, 'sp_current_team', true );
$past_teams = get_post_meta( $id, 'sp_past_team', false );
$metrics = sp_get_player_metrics_data( $id );

$common = array();
if ( $nationality ):
	$country_name = sp_array_value( $countries, $nationality, null );
	$common[ SP()->text->string('Nationality', 'player') ] = $country_name ? ( $show_nationality_flags ? '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . '/assets/images/flags/' . strtolower( $nationality ) . '.png" alt="' . $nationality . '"> ' : '' ) . $country_name : '&mdash;';
endif;

$data = array_merge( $common, $metrics );

if ( $current_team )
	$data[ SP()->text->string('Current Team', 'player') ] = '<a href="' . get_post_permalink( $current_team ) . '">' . get_the_title( $current_team ) . '</a>';

if ( $past_teams ):
	$teams = array();
	foreach ( $past_teams as $team ):
		$teams[] = '<a href="' . get_post_permalink( $team ) . '">' . get_the_title( $team ) . '</a>';
	endforeach;
	$data[ SP()->text->string('Past Teams', 'player') ] = implode( ', ', $teams );
endif;

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-player-details">';

foreach( $data as $label => $value ):

	$output .= '<dt>' . $label . '<dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';

echo apply_filters( 'sportspress_player_details',  $output );
