<?php
/**
 * Player Statistics
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.7.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( 'no' === get_option( 'sportspress_player_show_statistics', 'yes' ) && 'no' === get_option( 'sportspress_player_show_total', 'no' ) ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$player = new SP_Player( $id );

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
$sections = get_option( 'sportspress_player_performance_sections', -1 );
$leagues = get_the_terms( $id, 'sp_league' );
$positions = $player->positions();
$player_sections = array();
foreach ( $positions as $position ) {
	$player_sections = array_merge( $player_sections, sp_get_term_sections( $position->term_id ) );
}

// Determine order of sections
if ( 1 == $sections ) {
	$section_order = array( 1 => __( 'Defense', 'sportspress' ), 0 => __( 'Offense', 'sportspress' ) );
} elseif ( 0 == $sections ) {
	$section_order = array( __( 'Offense', 'sportspress' ), __( 'Defense', 'sportspress' ) );
} else {
	$section_order = array( -1 => null );
}

// Loop through statistics for each league
if ( is_array( $leagues ) ):
	foreach ( $section_order as $section_id => $section_label ) {
		if ( -1 !== $section_id && ! in_array( $section_id, $player_sections ) ) continue;
		
		foreach ( $leagues as $league ):
			if ( null !== $section_label ) {
				if ( sizeof( $leagues ) > 1 ) {
					printf( '<h3 class="sp-post-caption sp-player-statistics-section">%s</h3>', $section_label );
					$caption = $league->name;
				} else {
					$caption = $section_label;
				}
			}
			sp_get_template( 'player-statistics-league.php', array(
				'data' => $player->data( $league->term_id, false, $section_id ),
				'league' => $league,
				'caption' => $caption,
				'scrollable' => $scrollable,
			) );
		endforeach;
	}
endif;