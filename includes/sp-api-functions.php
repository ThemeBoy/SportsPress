<?php
/**
 * SportsPress API Functions
 *
 * API functions for admin and front-end templates.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * General functions
 */
function sp_get_time( $post = null ) {
	return get_post_time( get_option( 'time_format' ), false, $post, true );
}

function sp_time( $post = null ) {
	echo sp_get_time( $post );
}

/*
 * Event functions
 */
function sp_get_main_results( $post = null ) {
	$event = new SP_Event( $post );
	return $event->main_results();
}

function sp_main_results( $post = null ) {
	$results = sp_get_main_results( $post );
	echo implode( ' - ', $results );
}