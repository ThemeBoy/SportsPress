<?php
/**
 * SportsPress Conditional Functions
 *
 * Functions for determining the current query/page.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version		2.5.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * is_sportspress - Returns true if on a page which uses SportsPress templates
 *
 * @access public
 * @return bool
 */
function is_sportspress() {
	return apply_filters( 'is_sportspress', ( is_singular( sp_post_types() ) ) ? true : false );
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
 * sp_primary_post_types - Returns array of SP primary post types
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_primary_post_types' ) ) {
	function sp_primary_post_types() {
		return apply_filters( 'sportspress_primary_post_types',  array( 'sp_event', 'sp_team', 'sp_player', 'sp_staff' ) );
	}
}

/**
 * sp_secondary_post_types - Returns array of SP secondary post types
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_secondary_post_types' ) ) {
	function sp_secondary_post_types() {
		return apply_filters( 'sportspress_secondary_post_types', array_diff( sp_post_types(), sp_primary_post_types() ) );
	}
}

/**
 * sp_importable_post_types - Returns array of SP post types with importers
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_importable_post_types' ) ) {
	function sp_importable_post_types() {
		return apply_filters( 'sportspress_importable_post_types',  array( 'sp_event', 'sp_team', 'sp_player', 'sp_staff' ) );
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
 * sp_taxonomies - Returns array of SP taxonomies
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_taxonomies' ) ) {
	function sp_taxonomies() {
		return apply_filters( 'sportspress_taxonomies', array( 'sp_league', 'sp_season', 'sp_venue', 'sp_position', 'sp_role' ) );
	}
}

/**
 * sp_post_type_hierarchy - Returns array of SP primary post types
 *
 * @access public
 * @return array
 */
if ( ! function_exists( 'sp_post_type_hierarchy' ) ) {
	function sp_post_type_hierarchy() {
		return apply_filters(
			'sportspress_post_type_hierarchy',
			array(
				'sp_event' => array( 'sp_calendar' ),
				'sp_team' => array( 'sp_table' ),
				'sp_player' => array( 'sp_list' ),
				'sp_staff' => array()
			)
		);
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

/**
 * is_sp_taxonomy - Returns true if post is SportsPress taxonomy
 *
 * @access public
 * @return bool
 */
if ( ! function_exists( 'is_sp_taxonomy' ) ) {
	function is_sp_taxonomy( $typenow = null ) {
		if ( $typenow == null ) global $typenow;
		
		$taxonomies = sp_taxonomies();

		if ( in_array( $typenow, $taxonomies ) )
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