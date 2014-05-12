<?php
/**
 * SportsPress Conditional Functions
 *
 * Functions for determining the current query/page.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     0.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * is_sportspress - Returns true if on a page which uses SportsPress templates
 *
 * @access public
 * @return bool
 */
function is_sportspress() {
	return apply_filters( 'is_sportspress', ( is_singular( array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ) ? true : false );
}

/**
 * sp_post_types - Returns array of SP post types
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_post_types' ) ) {
	function sp_post_types() {
		return apply_filters( 'sportspress_post_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) );
	}
}

/**
 * sp_config_types - Returns array of SP config types
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_config_types' ) ) {
	function sp_config_types() {
		return apply_filters( 'sportspress_config_types', array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance', 'sp_metric', 'sp_statistic' ) );
	}
}

/**
 * is_sp_post_type - Returns true if post is SportsPress post type
 *
 * @access public
 * @return bool
 */
if ( ! function_exists( 'is_sp_post_type' ) ) {
	function is_sp_post_type( $typenow = null ) {
		if ( $typenow == null ) global $typenow;
		
		$post_types = sp_post_types();

		if ( in_array( $typenow, $post_types ) )
			return true;
		return false;
	}
}

/**
 * is_sp_config_type - Returns true if post is SportsPress config type
 *
 * @access public
 * @return bool
 */
if ( ! function_exists( 'is_sp_config_type' ) ) {
	function is_sp_config_type( $typenow = null ) {
		if ( $typenow == null ) global $typenow;
		
		$post_types = sp_config_types();

		if ( in_array( $typenow, $post_types ) )
			return true;
		return false;
	}
}

if ( ! function_exists( 'is_ajax' ) ) {

	/**
	 * is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @access public
	 * @return bool
	 */
	function is_ajax() {
		return defined( 'DOING_AJAX' );
	}
}