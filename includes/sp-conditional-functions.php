<?php
/**
 * SportsPress Conditional Functions
 *
 * Functions for determining the current query/page.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     0.8
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
 * is_sp_config_type - Returns true if post is SportsPress config type
 *
 * @access public
 * @return bool
 */
if ( ! function_exists( 'is_sp_config_type' ) ) {
	function is_sp_config_type( $typenow = null ) {
		if ( $typenow == null ) global $typenow;
		
		$post_types = array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance', 'sp_metric', 'sp_statistic' );

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