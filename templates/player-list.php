<?php
/**
 * Player List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'number' => -1,
	'grouptag' => 'h4',
	'columns' => null,
	'grouping' => null,
	'orderby' => 'default',
	'order' => 'ASC',
	'show_all_players_link' => false,
	'link_posts' => get_option( 'sportspress_list_link_players', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_list_link_teams', 'no' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_list_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_list_rows', 10 ),
);

extract( $defaults, EXTR_SKIP );

// Backward compatibility
if ( isset( $performance ) )
	$columns = $performance;

$list = new SP_Player_List( $id );
if ( isset( $columns ) && null !== $columns ):
	$list->columns = $columns;
endif;
$data = $list->data();


// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

if ( $grouping === null || $grouping === 'default' ):
	$grouping = $list->grouping;
endif;

if ( $orderby == 'default' ):
	$orderby = $list->orderby;
	$order = $list->order;
else:
	$list->priorities = array(
		array(
			'key' => $orderby,
			'order' => $order,
		),
	);
	uasort( $data, array( $list, 'sort' ) );
endif;

if ( $grouping === 'position' ):
	$groups = get_terms( 'sp_position' );
else:
	$group = new stdClass();
	$group->term_id = null;
	$group->name = null;
	$group->slug = null;
	$groups = array( $group );
endif;

$output = '';

foreach ( $groups as $group ):
	if ( ! empty( $group->name ) ):
		$output .= '<a name="group-' . $group->slug . '" id="group-' . $group->slug . '"></a>';
		$output .= '<' . $grouptag . ' class="sp-table-caption player-group-name player-list-group-name">' . $group->name . '</' . $grouptag . '>';
	endif;

	$output .= '<div class="sp-table-wrapper sp-scrollable-table-wrapper">' .
		'<table class="sp-player-list sp-data-table' . ( $responsive ? ' sp-responsive-table' : '' ) . ( $sortable ? ' sp-sortable-table' : '' ) . ( $paginated ? ' sp-paginated-table' : '' ) . '" data-sp-rows="' . $rows . '">' . '<thead>' . '<tr>';

	if ( in_array( $orderby, array( 'number', 'name' ) ) ):
		$output .= '<th class="data-number">#</th>';
	else:
		$output .= '<th class="data-rank">' . __( 'Rank', 'sportspress' ) . '</th>';
	endif;

	foreach( $labels as $key => $label ):
		if ( ! is_array( $columns ) || $key == 'name' || in_array( $key, $columns ) )
		$output .= '<th class="data-' . $key . '">'. $label . '</th>';
	endforeach;

	$output .= '</tr>' . '</thead>' . '<tbody>';

	$i = 0;

	if ( intval( $number ) > 0 )
		$limit = $number;

	foreach( $data as $player_id => $row ): if ( empty( $group->term_id ) || has_term( $group->term_id, 'sp_position', $player_id ) ):

		if ( isset( $limit ) && $i >= $limit ) continue;

		$name = sp_array_value( $row, 'name', null );
		if ( ! $name ) continue;

		$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

		// Rank or number
		if ( isset( $orderby ) && $orderby != 'number' ):
			$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';
		else:
			$output .= '<td class="data-number">' . sp_array_value( $row, 'number', '&nbsp;' ) . '</td>';
		endif;

		if ( $link_posts ):
			$permalink = get_post_permalink( $player_id );
			$name = '<a href="' . $permalink . '">' . $name . '</a>';
		endif;

		$output .= '<td class="data-name">' . $name . '</td>';
		
		if ( array_key_exists( 'team', $labels ) ):
			$teams = get_post_meta( $player_id, 'sp_current_team' );
			$team_names = array();
			foreach ( $teams as $team ):
				$team_name = get_the_title( $team );
				if ( $link_teams ):
					$team_name = '<a href="' . get_post_permalink( $team ) . '">' . $team_name . '</a>';
				endif;
				$team_names[] = $team_name;
			endforeach;
			$output .= '<td class="data-team">' . implode( ', ', $team_names ) . '</td>';
		endif;

		foreach( $labels as $key => $value ):
			if ( in_array( $key, array( 'name', 'team' ) ) )
				continue;
			if ( ! is_array( $columns ) || in_array( $key, $columns ) )
			$output .= '<td class="data-' . $key . '">' . sp_array_value( $row, $key, '&mdash;' ) . '</td>';
		endforeach;

		$output .= '</tr>';

		$i++;

	endif; endforeach;

	$output .= '</tbody>' . '</table>' . '</div>';
endforeach;

if ( $show_all_players_link )
	$output .= '<a class="sp-player-list-link sp-view-all-link" href="' . get_permalink( $id ) . '">' . __( 'View all players', 'sportspress' ) . '</a>';

echo apply_filters( 'sportspress_player_list',  $output );
