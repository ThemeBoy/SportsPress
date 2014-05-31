<?php
/**
 * League Table
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'number' => -1,
	'columns' => null,
	'show_full_table_link' => false,
	'show_team_logo' => get_option( 'sportspress_table_show_logos', 'yes' ) == 'yes' ? true : false,
	'link_posts' => get_option( 'sportspress_table_link_teams', 'no' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_table_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_table_rows', 10 ),
);

extract( $defaults, EXTR_SKIP );

$output = '<h4 class="sp-table-caption">' . get_the_title( $id ) . '</h4>';

$output .= '<div class="sp-table-wrapper sp-scrollable-table-wrapper">';

$output .= '<table class="sp-league-table sp-data-table' . ( $responsive ? ' sp-responsive-table' : '' ) . ( $sortable ? ' sp-sortable-table' : '' ) . ( $paginated ? ' sp-paginated-table' : '' ) . '" data-sp-rows="' . $rows . '">' . '<thead>' . '<tr>';

$table = new SP_League_Table( $id );

$data = $table->data();

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

if ( ! $columns )
	$columns = get_post_meta( $id, 'sp_columns', true );

if ( ! is_array( $columns ) )
	$columns = explode( ',', $columns );

$output .= '<th class="data-rank">' . SP()->text->string('Pos') . '</th>';

foreach( $labels as $key => $label ):
	if ( ! is_array( $columns ) || $key == 'name' || in_array( $key, $columns ) )
		$output .= '<th class="data-' . $key . '">' . $label . '</th>';
endforeach;

$output .= '</tr>' . '</thead>' . '<tbody>';

$i = 0;

if ( is_int( $number ) && $number > 0 )
	$limit = $number;

foreach( $data as $team_id => $row ):

	if ( isset( $limit ) && $i >= $limit ) continue;

	$name = sp_array_value( $row, 'name', null );
	if ( ! $name ) continue;

	$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

	// Rank
	$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';

	$name_class = '';

	if ( $show_team_logo ):
		if ( has_post_thumbnail( $team_id ) ):
			$logo = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon', array( 'class' => 'team-logo' ) );
			$name = $logo . ' ' . $name;
			$name_class .= ' has-logo';
		endif;
	endif;

	if ( $link_posts ):
		$permalink = get_post_permalink( $team_id );
		$name = '<a href="' . $permalink . '">' . $name . '</a>';
	endif;

	$output .= '<td class="data-name' . $name_class . '">' . $name . '</td>';

	foreach( $labels as $key => $value ):
		if ( $key == 'name' )
			continue;
		if ( ! is_array( $columns ) || in_array( $key, $columns ) )
			$output .= '<td class="data-' . $key . '">' . sp_array_value( $row, $key, '&mdash;' ) . '</td>';
	endforeach;

	$output .= '</tr>';

	$i++;

endforeach;

$output .= '</tbody>' . '</table>';

$output .= '</div>';

if ( $show_full_table_link )
	$output .= '<a class="sp-league-table-link sp-view-all-link" href="' . get_permalink( $id ) . '">' . SP()->text->string('View full table') . '</a>';

echo $output;
