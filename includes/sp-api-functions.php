<?php
/**
 * SportsPress API Functions
 *
 * API functions for admin and front-end templates.
 *
 * @author      ThemeBoy
 * @category    Core
 * @package     SportsPress/Functions
 * @version     2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * General functions
 */

function sp_post_exists( $post = 0 ) {
	return is_string( get_post_status( $post ) );
}

function sp_get_time( $post = 0, $format = null ) {
	if ( null == $format ) {
		$format = get_option( 'time_format' );
	}
	return get_post_time( $format, false, $post, true );
}

function sp_the_time( $post = 0, $format = null ) {
	echo wp_kses_post( sp_get_time( $post, $format ) );
}

function sp_get_date( $post = 0, $format = null ) {
	if ( null == $format ) {
		$format = get_option( 'date_format' );
	}
	return get_post_time( $format, false, $post, true );
}

function sp_the_date( $post = 0, $format = null ) {
	echo wp_kses_post( sp_get_date( $post, $format ) );
}

function sp_get_posts( $post_type = 'post', $args = array() ) {
	$args = array_merge(
		array(
			'post_type'      => $post_type,
			'numberposts'    => -1,
			'posts_per_page' => -1,
		),
		$args
	);
	return get_posts( $args );
}

function sp_get_leagues( $post = 0, $ids = true ) {
	$terms = get_the_terms( $post, 'sp_league' );
	if ( $terms && $ids ) {
		$terms = wp_list_pluck( $terms, 'term_id' );
	}
	return $terms;
}

function sp_get_seasons( $post = 0, $ids = true ) {
	$terms = get_the_terms( $post, 'sp_season' );
	if ( $terms && $ids ) {
		$terms = wp_list_pluck( $terms, 'term_id' );
	}
	return $terms;
}

function sp_the_leagues( $post = 0, $delimiter = ', ' ) {
	$terms = sp_get_leagues( $post, false );
	$arr   = array();
	if ( $terms ) {
		foreach ( $terms as $term ) :
			$arr[] = $term->name;
		endforeach;
	}
	echo wp_kses_post( implode( $delimiter, $arr ) );
}

function sp_the_seasons( $post = 0, $delimiter = ', ' ) {
	$terms = sp_get_seasons( $post, false );
	$arr   = array();
	if ( $terms ) {
		foreach ( $terms as $term ) :
			$arr[] = $term->name;
		endforeach;
	}
	echo wp_kses_post( implode( $delimiter, $arr ) );
}

/**
 * Retrieve a post object by exact title for any post type.
 *
 * This function replaces the deprecated get_page_by_title() call by running a WP_Query
 * that looks for an exact match on post_title within the specified post type(s).
 *
 * @param string          $title       The exact post title to search for.
 * @param string|string[] $post_types  Post type slug (or array of slugs) to search in.
 * @param string|string[] $post_status Post status or array of statuses to include. Default 'publish'.
 * @return WP_Post|null   WP_Post object if found; null otherwise.
 */
function sp_get_post_by_title( $title, $post_types, $post_status = 'publish' ) {
	// If the input $title came from a source that applied magic‐quotes, reverse it.
	$post_title = wp_unslash( $title );

	// Force $post_types and $post_status into arrays so WP_Query accepts them.
	$post_type_arg   = (array) $post_types;
	$post_status_arg = (array) $post_status;

	// Build query args. We're matching post_title exactly via 'title' (available since WP 4.4).
	$args = array(
		'post_type'              => $post_type_arg,       // Query one or more post types.
		'post_status'            => $post_status_arg,     // Query one or more statuses.
		'posts_per_page'         => 1,                    // Only need one match.
		'no_found_rows'          => true,                 // Skip pagination count for performance.
		'ignore_sticky_posts'    => true,                 // Not relevant, but good practice.
		'update_post_term_cache' => false,                // Skip term cache—unneeded here.
		'update_post_meta_cache' => false,                // Skip meta cache—unneeded here.
		'title'                  => $post_title,          // Exact title match.
		'orderby'                => 'post_date ID',       // Ensure deterministic ordering.
		'order'                  => 'ASC',
	);

	// Execute the query.
	$query = new WP_Query( $args );

	// If a post is found, grab it; otherwise $query->posts will be empty.
	if ( $query->have_posts() ) {
		$post_obj = $query->posts[0];
		wp_reset_postdata(); // Reset global $post if it was changed by WP_Query.
		return $post_obj;
	}

	// No match—reset global state and return null.
	wp_reset_postdata();
	return null;
}

/*
 * Event functions
 */

function sp_get_status( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->status();
}

function sp_get_results( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->results();
}

function sp_get_teams( $post = 0 ) {
	return get_post_meta( $post, 'sp_team' );
}

function sp_get_main_result_option() {
	$main_result = get_option( 'sportspress_primary_result', null );
	if ( $main_result ) {
		return $main_result;
	}
	$results = get_posts(
		array(
			'post_type'      => 'sp_result',
			'posts_per_page' => 1,
			'orderby'        => 'menu_order',
			'order'          => 'DESC',
		)
	);
	if ( ! $results ) {
		return null;
	}
	$result = reset( $results );
	$slug   = $result->post_name;
	return $slug;
}

function sp_get_main_results( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->main_results();
}

function sp_the_main_results( $post = 0, $delimiter = '-' ) {
	$results = sp_get_main_results( $post );
	echo wp_kses_post( implode( $delimiter, $results ) );
}

function sp_update_main_results( $post = 0, $results = array() ) {
	$event = new SP_Event( $post );
	return $event->update_main_results( $results );
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
	echo wp_kses_post( implode( $delimiter, sp_get_main_results_or_time( $post ) ) );
}

function sp_get_main_results_or_date( $post = 0, $format = null ) {
	$results = sp_get_main_results( $post );
	if ( sizeof( $results ) ) {
		return $results;
	} else {
		return array( sp_get_date( $post, $format ) );
	}
}

function sp_get_outcome( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->outcome( true );
}

function sp_get_outcomes( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->outcome( false );
}

function sp_get_winner( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->winner();
}

function sp_get_main_performance_option() {
	$main_performance = get_option( 'sportspress_primary_performance', null );
	if ( $main_performance ) {
		return $main_performance;
	}
	$options = get_posts(
		array(
			'post_type'      => 'sp_performance',
			'posts_per_page' => 1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);
	if ( ! $options ) {
		return null;
	}
	$performance = reset( $options );
	$slug        = $performance->post_name;
	return $slug;
}

function sp_get_performance( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->performance();
}

function sp_get_singular_name( $post = 0 ) {
	$singular = get_post_meta( $post, 'sp_singular', true );
	if ( $singular && '' !== $singular ) {
		return $singular;
	} else {
		return get_the_title( $post );
	}
}

function sp_event_logos( $post = 0 ) {
	sp_get_template( 'event-logos.php', array( 'id' => $post ) );
}

function sp_event_video( $post = 0 ) {
	sp_get_template( 'event-video.php', array( 'id' => $post ) );
}

function sp_event_results( $post = 0 ) {
	sp_get_template( 'event-results.php', array( 'id' => $post ) );
}

function sp_event_details( $post = 0 ) {
	sp_get_template( 'event-details.php', array( 'id' => $post ) );
}

function sp_event_venue( $post = 0 ) {
	sp_get_template( 'event-venue.php', array( 'id' => $post ) );
}

function sp_event_staff( $post = 0 ) {
	sp_get_template( 'event-staff.php', array( 'id' => $post ) );
}

function sp_event_performance( $post = 0 ) {
	sp_get_template( 'event-performance.php', array( 'id' => $post ) );
}

/*
 * Calendar functions
 */

function sp_get_calendar( $post = 0 ) {
	$calendar = new SP_Calendar( $post );
	return $calendar->data();
}

function sp_event_calendar( $post = 0 ) {
	sp_get_template( 'event-calendar.php', array( 'id' => $post ) );
}

function sp_event_list( $post = 0 ) {
	sp_get_template( 'event-list.php', array( 'id' => $post ) );
}

function sp_event_blocks( $post = 0 ) {
	sp_get_template( 'event-blocks.php', array( 'id' => $post ) );
}

/*
 * Team functions
 */

function sp_has_logo( $post = 0 ) {
	return has_post_thumbnail( $post );
}

function sp_get_logo( $post = 0, $size = 'icon', $attr = array() ) {
	return get_the_post_thumbnail( $post, 'sportspress-fit-' . $size, $attr );
}

function sp_get_logo_url( $post = 0, $size = 'icon' ) {
	$thumbnail_id = get_post_thumbnail_id( $post );
	$src          = wp_get_attachment_image_src( $thumbnail_id, $size, false );
	return $src[0];
}

function sp_get_abbreviation( $post = 0 ) {
	return get_post_meta( $post, 'sp_abbreviation', true );
}

function sp_get_venues( $post = 0, $ids = true ) {
	$terms = get_the_terms( $post, 'sp_venue' );
	if ( $terms && $ids ) {
		$terms = wp_list_pluck( $terms, 'term_id' );
	}
	return $terms;
}

function sp_the_venues( $post = 0, $delimiter = ', ' ) {
	$terms = sp_get_venues( $post, false );
	$arr   = array();
	if ( $terms ) {
		foreach ( $terms as $term ) :
			$arr[] = $term->name;
		endforeach;
	}
	echo wp_kses_post( implode( $delimiter, $arr ) );
}

function sp_is_home_venue( $post = 0, $event = 0 ) {
	$pv = sp_get_venues( $post );
	$ev = sp_get_venues( $event );
	if ( is_array( $pv ) && is_array( $ev ) && sizeof( array_intersect( $pv, $ev ) ) ) {
		return true;
	} else {
		return false;
	}
}

function sp_the_logo( $post = 0, $size = 'icon', $attr = array() ) {
	echo wp_kses_post( sp_get_logo( $post, $size, $attr ) );
}

function sp_team_logo( $post = 0 ) {
	sp_get_template( 'team-logo.php', array( 'id' => $post ) );
}

function sp_team_abbreviation( $post = 0, $forced = false ) {
	$abbreviation = get_post_meta( $post, 'sp_abbreviation', true );
	if ( $abbreviation ) {
		return $abbreviation;
	} else {
		return $forced ? sp_substr( sp_strtoupper( sp_team_short_name( $post ) ), 0, 3 ) : sp_team_short_name( $post );
	}
}

function sp_the_abbreviation( $post = 0, $forced = false ) {
	echo wp_kses_post( sp_team_abbreviation( $post, $forced ) );
}

function sp_team_short_name( $post = 0 ) {
	$short_name = get_post_meta( $post, 'sp_short_name', true );
	if ( $short_name ) {
		return $short_name;
	} else {
		return get_the_title( $post );
	}
}

function sp_the_short_name( $post = 0 ) {
	echo wp_kses_post( sp_team_short_name( $post ) );
}

function sp_team_name( $post = 0, $length = 'full' ) {
	if ( 'abbreviation' == $length ) {
		return sp_team_abbreviation( $post );
	} elseif ( 'short' == $length ) {
		return sp_team_short_name( $post );
	} else {
		return get_the_title( $post );
	}
}

function sp_team_details( $post = 0 ) {
	sp_get_template( 'team-details.php', array( 'id' => $post ) );
}

function sp_team_link( $post = 0 ) {
	sp_get_template( 'team-link.php', array( 'id' => $post ) );
}

function sp_team_lists( $post = 0 ) {
	sp_get_template( 'team-lists.php', array( 'id' => $post ) );
}

function sp_team_tables( $post = 0 ) {
	sp_get_template( 'team-tables.php', array( 'id' => $post ) );
}

/*
 * League Table functions
 */

function sp_get_table( $post = 0 ) {
	$table = new SP_League_Table( $post );
	return $table->data();
}

function sp_league_table( $post = 0 ) {
	sp_get_template( 'league-table.php', array( 'id' => $post ) );
}

/*
 * Player functions
 */

function sp_get_player_number( $post = 0 ) {
	return get_post_meta( $post, 'sp_number', true );
}

function sp_get_player_number_in_event( $player_id, $team_id, $event_id ) {
	$event_players = get_post_meta( $event_id, 'sp_players', true );
	if ( ! array_key_exists( $team_id, $event_players ) ) {
		return;
	}
	if ( ! array_key_exists( $player_id, $event_players[ $team_id ] ) ) {
		return;
	}
	return $event_players[ $team_id ][ $player_id ]['number'];
}

function sp_get_player_number_in_event_or_profile( $player_id, $team_id, $event_id ) {
	$number = sp_get_player_number_in_event( $player_id, $team_id, $event_id );
	if ( is_null( $number ) ) {
		$number = sp_get_player_number( $player_id );
	}
	return $number;
}

function sp_get_player_name( $post = 0 ) {
	return apply_filters( 'sportspress_player_name', get_the_title( $post ), $post );
}

function sp_get_player_name_with_number( $post = 0, $prepend = '', $append = '. ' ) {
	$name   = sp_get_player_name( $post );
	$number = sp_get_player_number( $post );
	if ( isset( $number ) && '' !== $number ) {
		return apply_filters( 'sportspress_player_name_with_number', $prepend . $number . $append . $name, $post );
	} else {
		return $name;
	}
}

function sp_get_player_name_then_number( $post = 0, $prepend = ' (', $append = ')' ) {
	$name   = sp_get_player_name( $post );
	$number = sp_get_player_number( $post );
	if ( isset( $number ) && '' !== $number ) {
		return apply_filters( 'sportspress_player_name_then_number', $name . $prepend . $number . $append, $post );
	} else {
		return $name;
	}
}

function sp_player_details( $post = 0 ) {
	sp_get_template( 'player-details.php', array( 'id' => $post ) );
}

function sp_player_photo( $post = 0 ) {
	sp_get_template( 'player-photo.php', array( 'id' => $post ) );
}

function sp_player_statistics( $post = 0 ) {
	sp_get_template( 'player-statistics.php', array( 'id' => $post ) );
}

/*
 * Player List functions
 */

function sp_get_list( $post = 0 ) {
	$list = new SP_Player_List( $post );
	return $list->data();
}

function sp_player_list( $post = 0 ) {
	sp_get_template( 'player-list.php', array( 'id' => $post ) );
}

/*
 * Staff functions
 */

function sp_staff_details( $post = 0 ) {
	sp_get_template( 'staff-details.php', array( 'id' => $post ) );
}

function sp_staff_photo( $post = 0 ) {
	sp_get_template( 'staff-photo.php', array( 'id' => $post ) );
}

/*
 * Venue functions
 */

function sp_venue_map( $term = 0 ) {
	$meta = get_option( "taxonomy_$term" );
	sp_get_template( 'venue-map.php', array( 'meta' => $meta ) );
}



function sp_get_position_caption( $term = 0 ) {
	$meta    = get_option( "taxonomy_$term" );
	$caption = sp_array_value( $meta, 'sp_caption', '' );
	if ( $caption ) {
		return $caption;
	} else {
		$term = get_term( $term, 'sp_position' );
		return $term->name;
	}

}
