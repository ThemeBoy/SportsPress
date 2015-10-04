<?php
/**
 * SportsPress Template
 *
 * Functions for the templating system.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     1.9.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Output generator tag to aid debugging.
 *
 * @access public
 * @return void
 */
function sp_generator_tag( $gen, $type ) {
	switch ( $type ) {
		case 'html':
			$gen .= "\n" . '<meta name="generator" content="SportsPress ' . esc_attr( SP_VERSION ) . '">';
			break;
		case 'xhtml':
			$gen .= "\n" . '<meta name="generator" content="SportsPress ' . esc_attr( SP_VERSION ) . '" />';
			break;
	}
	return $gen;
}

/**
 * Add body classes for SP pages
 *
 * @param  array $classes
 * @return array
 */
function sp_body_class( $classes ) {
	$classes = (array) $classes;

	if ( is_sportspress() ) {
		$classes[] = 'sportspress';
		$classes[] = 'sportspress-page';
	}

	$post_type = get_post_type();

	if ( 'sp_event' == $post_type ) {
		$id = get_the_ID();
		if ( get_option( 'sportspress_event_show_maps', 'yes' ) == 'yes' && get_the_terms( $id, 'sp_venue' ) ) {
			$classes[] = 'sp-has-venue';
		}
		if ( 'results' == sp_get_status( $id ) ) {
			$classes[] = 'sp-has-results';
		}
	} elseif ( 'sp_team' == $post_type && 'yes' == get_option( 'sportspress_team_show_logo', 'yes' ) ) {
		$classes[] = 'sp-show-image';
	} elseif ( 'sp_player' == $post_type && 'yes' == get_option( 'sportspress_player_show_photo', 'yes' ) ) {
		$classes[] = 'sp-show-image';
	} elseif ( 'sp_staff' == $post_type && 'yes' == get_option( 'sportspress_staff_show_photo', 'yes' ) ) {
		$classes[] = 'sp-show-image';
	}

	return array_unique( $classes );
}

/** Template pages ********************************************************/

if ( ! function_exists( 'sportspress_taxonomy_archive_description' ) ) {

	/**
	 * Show an archive description on taxonomy archives
	 *
	 * @access public
	 * @subpackage	Archives
	 * @return void
	 */
	function sportspress_taxonomy_archive_description() {
		if ( is_tax( array( 'sp_season', 'sp_league', 'sp_venue', 'sp_position' ) ) && get_query_var( 'paged' ) == 0 ) {
			$description = apply_filters( 'the_content', term_description() );
			if ( $description ) {
				echo '<div class="term-description">' . $description . '</div>';
			}
		}
	}
}

/** Single Post ********************************************************/

if ( ! function_exists( 'sportspress_output_post_excerpt' ) ) {

	/**
	 * Output the post excerpt.
	 *
	 * @access public
	 * @subpackage	Excerpt
	 * @return void
	 */
	function sportspress_output_post_excerpt() {
		sp_get_template( 'post-excerpt.php' );
	}
}

/** Single Event ********************************************************/

if ( ! function_exists( 'sportspress_output_event_logos' ) ) {

	/**
	 * Output the event logos.
	 *
	 * @access public
	 * @subpackage	Event/Logos
	 * @return void
	 */
	function sportspress_output_event_logos() {
		sp_get_template( 'event-logos.php' );
	}
}

if ( ! function_exists( 'sportspress_output_event_video' ) ) {

	/**
	 * Output the event video.
	 *
	 * @access public
	 * @subpackage	Event/Video
	 * @return void
	 */
	function sportspress_output_event_video() {
		sp_get_template( 'event-video.php' );
	}
}
if ( ! function_exists( 'sportspress_output_event_results' ) ) {

	/**
	 * Output the event results.
	 *
	 * @access public
	 * @subpackage	Event/Results
	 * @return void
	 */
	function sportspress_output_event_results() {
		sp_get_template( 'event-results.php' );
	}
}
if ( ! function_exists( 'sportspress_output_event_details' ) ) {

	/**
	 * Output the event details.
	 *
	 * @access public
	 * @subpackage	Event/Details
	 * @return void
	 */
	function sportspress_output_event_details() {
		sp_get_template( 'event-details.php' );
	}
}
if ( ! function_exists( 'sportspress_output_event_overview' ) ) {

	/**
	 * Output the event details, venue, and results.
	 *
	 * @access public
	 * @subpackage	Event/Overview
	 * @return void
	 */
	function sportspress_output_event_overview() {
		sp_get_template( 'event-overview.php' );
	}
}
if ( ! function_exists( 'sportspress_output_event_venue' ) ) {

	/**
	 * Output the event venue.
	 *
	 * @access public
	 * @subpackage	Event/Venue
	 * @return void
	 */
	function sportspress_output_event_venue() {
		sp_get_template( 'event-venue.php' );
	}
}
if ( ! function_exists( 'sportspress_output_event_performance' ) ) {

	/**
	 * Output the event performance.
	 *
	 * @access public
	 * @subpackage	Event/Performance
	 * @return void
	 */
	function sportspress_output_event_performance() {
		sp_get_template( 'event-performance.php' );
	}
}

/** Single Calendar ********************************************************/

if ( ! function_exists( 'sportspress_output_calendar' ) ) {

	/**
	 * Output the calendar.
	 *
	 * @access public
	 * @subpackage	Calendar
	 * @return void
	 */
	function sportspress_output_calendar() {
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        if ( array_key_exists( $format, SP()->formats->calendar ) )
			sp_get_template( 'event-' . $format . '.php', array( 'id' => $id ) );
        else
			sp_get_template( 'event-calendar.php', array( 'id' => $id ) );
	}
}

/** Single Team ********************************************************/

if ( ! function_exists( 'sportspress_output_team_link' ) ) {

	/**
	 * Output the team link.
	 *
	 * @access public
	 * @subpackage	Team/Link
	 * @return void
	 */
	function sportspress_output_team_link() {
		sp_get_template( 'team-link.php' );
	}
}
if ( ! function_exists( 'sportspress_output_team_logo' ) ) {

	/**
	 * Output the team logo.
	 *
	 * @access public
	 * @subpackage	Team/Logo
	 * @return void
	 */
	function sportspress_output_team_logo() {
		sp_get_template( 'team-logo.php' );
	}
}
if ( ! function_exists( 'sportspress_output_team_details' ) ) {

	/**
	 * Output the team details.
	 *
	 * @access public
	 * @subpackage	Team/Details
	 * @return void
	 */
	function sportspress_output_team_details() {
		sp_get_template( 'team-details.php' );
	}
}
if ( ! function_exists( 'sportspress_output_team_tables' ) ) {

	/**
	 * Output the team tables.
	 *
	 * @access public
	 * @subpackage	Team/Tables
	 * @return void
	 */
	function sportspress_output_team_tables() {
		sp_get_template( 'team-tables.php' );
	}
}
if ( ! function_exists( 'sportspress_output_team_lists' ) ) {

	/**
	 * Output the team lists.
	 *
	 * @access public
	 * @subpackage	Team/Lists
	 * @return void
	 */
	function sportspress_output_team_lists() {
		sp_get_template( 'team-lists.php' );
	}
}

/** Single League Table ********************************************************/

if ( ! function_exists( 'sportspress_output_league_table' ) ) {

	/**
	 * Output the team columns.
	 *
	 * @access public
	 * @subpackage	Table
	 * @return void
	 */
	function sportspress_output_league_table() {
		sp_get_template( 'league-table.php' );
	}
}

/** Single Player ********************************************************/

if ( ! function_exists( 'sportspress_output_player_photo' ) ) {

	/**
	 * Output the player photo.
	 *
	 * @access public
	 * @subpackage	Player/Photo
	 * @return void
	 */
	function sportspress_output_player_photo() {
		sp_get_template( 'player-photo.php' );
	}
}
if ( ! function_exists( 'sportspress_output_player_details' ) ) {

	/**
	 * Output the player details.
	 *
	 * @access public
	 * @subpackage	Player/Details
	 * @return void
	 */
	function sportspress_output_player_details() {
		sp_get_template( 'player-details.php' );
	}
}
if ( ! function_exists( 'sportspress_output_player_statistics' ) ) {

	/**
	 * Output the player statistics.
	 *
	 * @access public
	 * @subpackage	Player/Statistics
	 * @return void
	 */
	function sportspress_output_player_statistics() {
		sp_get_template( 'player-statistics.php' );
	}
}

/** Single Player List ********************************************************/

if ( ! function_exists( 'sportspress_output_player_list' ) ) {

	/**
	 * Output the player list.
	 *
	 * @access public
	 * @subpackage	List
	 * @return void
	 */
	function sportspress_output_player_list() {
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        if ( array_key_exists( $format, SP()->formats->list ) )
			sp_get_template( 'player-' . $format . '.php', array( 'id' => $id ) );
        else
			sp_get_template( 'player-list.php', array( 'id' => $id ) );
	}
}

/** Single Staff ********************************************************/

if ( ! function_exists( 'sportspress_output_staff_photo' ) ) {

	/**
	 * Output the staff photo.
	 *
	 * @access public
	 * @subpackage	Staff/Photo
	 * @return void
	 */
	function sportspress_output_staff_photo() {
		sp_get_template( 'staff-photo.php' );
	}
}
if ( ! function_exists( 'sportspress_output_staff_details' ) ) {

	/**
	 * Output the staff details.
	 *
	 * @access public
	 * @subpackage	Staff/Details
	 * @return void
	 */
	function sportspress_output_staff_details() {
		sp_get_template( 'staff-details.php' );
	}
}

/** Venue Archive ********************************************************/

function sportspress_output_venue_map( $query ) {
    if ( ! is_tax( 'sp_venue' ) )
        return;

    $slug = sp_array_value( $query->query, 'sp_venue', null );

    if ( ! $slug )
        return;

    $venue = get_term_by( 'slug', $slug, 'sp_venue' );
    $t_id = $venue->term_id;
    $meta = get_option( "taxonomy_$t_id" );
	sp_get_template( 'venue-map.php', array( 'meta' => $meta ) );
}

/** Misc ********************************************************/

function sportspress_output_br_tag() {
	?>
	<br>
	<?php
}
