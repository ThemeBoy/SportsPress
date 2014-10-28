<?php
/**
 * SportsPress API Functions
 *
 * API functions for admin and front-end templates.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * General functions
 */

function sp_get_time( $post = 0 ) {
	return get_post_time( get_option( 'time_format' ), false, $post, true );
}

function sp_the_time( $post = 0 ) {
	echo sp_get_time( $post );
}

/*
 * Event functions
 */

function sp_get_teams( $post = 0 ) {
	return get_post_meta( $post, 'sp_team' );
}

function sp_get_main_results( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->main_results();
}

function sp_the_main_results( $post = 0, $delimiter = '-' ) {
	$results = sp_get_main_results( $post );
	echo implode( $delimiter, $results );
}

function sp_get_main_results_or_time( $post = 0 ) {
	$results = sp_get_main_results( $post );
	if ( sizeof( $results ) ) {
		return $results;
	} else {
		return array( sp_get_time( $post ) );
	}
}

function sp_the_main_results_or_time( $post = 0, $delimiter = '-' ) {
	echo implode( $delimiter, sp_get_main_results_or_time( $post ) );
}

/*
 * Team functions
 */

function sp_has_logo( $post = 0 ) {
	return has_post_thumbnail ( $post );
}

function sp_get_logo( $post = 0, $size = 'icon', $attr = array() ) {
	return get_the_post_thumbnail( $post, 'sportspress-fit-' . $size, $attr );
}

function sp_the_logo( $post = 0, $size = 'icon', $attr = array() ) {
	echo sp_get_logo( $post, $size, $attr );
}
