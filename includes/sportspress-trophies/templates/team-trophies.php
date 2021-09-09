<?php
/**
 * Team Trophies
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'show_title' => get_option( 'sportspress_trophy_show_title', 'yes' ) == 'yes' ? true : false,
	'show_team_logo' => get_option( 'sportspress_trophy_show_logos', 'yes' ) == 'yes' ? true : false,
	'link_trophies' => get_option( 'sportspress_link_trophies', 'no' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'no' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_trophy_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_trophy_rows', 10 ),
);

extract( $defaults, EXTR_SKIP );

$args = array(
	'post_type' => 'sp_trophy',
	'numberposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'meta_query' => array(
		array(
			'key'     => 'sp_teams',
			'value'   => '"' . $id . '"',
			'compare' => 'LIKE',
			),
		),
	);
$trophies = get_posts( $args );

if ( ! $trophies ) return;

$output = '';
$output .= '<h4 class="sp-table-caption">' . __( 'Trophies', 'sportspress' ) . '</h4>';
$output .= '<div class="sp-table-wrapper">';
$output .= '<table class="sp-trophy-data sp-data-table' . ( $sortable ? ' sp-sortable-table' : '' ) . ( $responsive ? ' sp-responsive-table '.$identifier : '' ). ( $scrollable ? ' sp-scrollable-table' : '' ) . ( $paginated ? ' sp-paginated-table' : '' ) . '" data-sp-rows="' . $rows . '">' . '<thead>' . '<tr>';
$output .= '<th class="data-name">' . __( 'Trophy', 'sportspress' ) . '</th>';
$output .= '<th>' . __( 'Seasons', 'sportspress' ) . '</th>';
$output .= '</tr>' . '</thead>' . '<tbody>';
foreach ( $trophies as $trophy ) {
	$trophy_data = get_post_meta( $trophy->ID, 'sp_winners', true );

	if ( isset( $trophy_data[ $id ] ) ) {
		$team_trophies = $trophy_data[ $id ];
		$trophy_name = $trophy->post_title;
		if ( $link_trophies ) {
			$trophy_permalink = get_permalink( $trophy->ID );
			$trophy_name = '<a href="' . $trophy_permalink . '">' . $trophy_name . '</a>';
		}
		$winnings = array();
		foreach ( $team_trophies as $team_trophy) {
			$winning = $team_trophy['season_name'];
			if ( isset( $team_trophy['table_id'] ) && $team_trophy['table_id'] != -1 ) {
				$league_table_permalink = get_permalink( $team_trophy['table_id'] );
				$winning = '<a href="' . $league_table_permalink . '">' . $winning . '</a>';
			}elseif ( isset( $team_trophy['calendar_id'] ) && $team_trophy['calendar_id'] != -1 ) {
				$calendar_permalink = get_permalink( $team_trophy['calendar_id'] );
				$winning = '<a href="' . $calendar_permalink . '">' . $winning . '</a>';
			}
			$winnings[] = $winning;
		}
		$winnings = implode( ', ', $winnings );
		$output .= '<tr> <td class="data-name">' . $trophy_name . '</td> <td>' . $winnings . '</td> </tr>';
	}
}
$output .= '</tbody>' . '</table>';
$output .= '</div>';

echo $output;