<?php
/**
 * Team Columns
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$leagues = get_the_terms( $id, 'sp_league' );

if ( ! $leagues )
	return false;

$output = '';

// Loop through data for each league
foreach ( $leagues as $league ):

	$team = new SP_Team( $id );
	$data = $team->data( $league->term_id );

	if ( sizeof( $data ) <= 1 )
		continue;

	// The first row should be column labels
	$labels = $data[0];

	// Remove the first row to leave us with the actual data
	unset( $data[0] );

	$output .= '<h4 class="sp-table-caption">' . $league->name . '</h4>' .
		'<div class="sp-table-wrapper">' .
		'<table class="sp-team-columns sp-data-table sp-responsive-table">' . '<thead>' . '<tr>';

	foreach( $labels as $key => $label ):
		$output .= '<th class="data-' . $key . '">' . $label . '</th>';
	endforeach;

	$output .= '</tr>' . '</thead>' . '<tbody>';

	$i = 0;

	foreach( $data as $season_id => $row ):

		$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

		foreach( $labels as $key => $value ):
			$output .= '<td class="data-' . $key . '">' . sp_array_value( $row, $key, '&mdash;' ) . '</td>';
		endforeach;

		$output .= '</tr>';

		$i++;

	endforeach;

	$output .= '</tbody>' . '</table>' . '</div>';


endforeach;

echo apply_filters( 'sportspress_team_columns',  $output );
