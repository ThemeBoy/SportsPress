<?php
/**
 * Player Performance Table
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $league ) )
	return false;

if ( ! isset( $id ) )
	$id = get_the_ID();

$responsive = get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false;

$data = sp_get_player_performance_data( $id, $league->term_id );

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

// Skip if there are no rows in the table
if ( empty( $data ) )
	return false;

$output = '<h4 class="sp-table-caption">' . $league->name . '</h4>' .
	'<div class="sp-table-wrapper">' .
	'<table class="sp-player-performance sp-data-table' . ( $responsive ? ' sp-responsive-table' : '' ) . '">' . '<thead>' . '<tr>';

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

echo apply_filters( 'sportspress_player_league_performance',  $output );
