<?php
/**
 * Trophy Data
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'title' => false,
	'show_title' => get_option( 'sportspress_trophy_show_title', 'yes' ) == 'yes' ? true : false,
	'show_team_logo' => get_option( 'sportspress_trophy_show_logos', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'no' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_trophy_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_trophy_rows', 10 ),
);

extract( $defaults, EXTR_SKIP );

if ( $show_title && false === $title && $id ):
	$caption = get_post_meta( $id, 'sp_caption', true );
	if ( $caption )
		$title = $caption;
	else
		$title = get_the_title( $id );
endif;

$layout = get_option( 'sportspress_trophy_format', 'seasons' );
$order = get_option( 'sportspress_trophy_order', 'desc' );

if ( $layout === 'teams' ) {
	$trophy_data = get_post_meta( $id, 'sp_winners', true );
	uasort( $trophy_data, 'sp_sort_by_count' );
}else{
	//Get all the winners of the specific trophy
	$trophy_data = get_post_meta( $id, 'sp_trophies', true );
	if ( $order === 'asc' )
		$trophy_data = array_reverse( $trophy_data );
}

sp_get_template( 'trophy-data-' . $layout . '.php', array(
	'id' => $id,
	'title' => $title,
	'show_title' => $show_title,
	'show_team_logo' => $show_team_logo,
	'link_teams' => $link_teams,
	'responsive' => $responsive,
	'sortable' => $sortable,
	'scrollable' => $scrollable,
	'paginated' => $paginated,
	'rows' => $rows,
	'trophy_data' => $trophy_data,
	'order' => $order,
), '', SP_TROPHIES_DIR . 'templates/' );