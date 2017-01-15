<?php
/**
 * SportsPress API Functions
 *
 * API functions for admin and front-end templates.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     2.2.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * General functions
 */

function sp_post_exists( $post = 0 ) {
	return is_string( get_post_status( $post ) );
}

function sp_get_time( $post = 0, $format = null ) {
	if ( null == $format ) $format = get_option( 'time_format' );
	return get_post_time( $format, false, $post, true );
}

function sp_the_time( $post = 0, $format = null ) {
	echo sp_get_time( $post, $format );
}

function sp_get_date( $post = 0, $format = null ) {
	if ( null == $format ) $format = get_option( 'date_format' );
	return get_post_time( $format, false, $post, true );
}

function sp_the_date( $post = 0, $format = null ) {
	echo sp_get_date( $post, $format );
}

function sp_get_posts( $post_type = 'post', $args = array() ) {
	$args = array_merge( array(
		'post_type' => $post_type,
		'numberposts' => -1,
		'posts_per_page' => -1,
	), $args );
	return get_posts( $args );
}

function sp_get_leagues( $post = 0, $ids = true ) {
	$terms = get_the_terms( $post, 'sp_league' );
	if ( $terms && $ids ) $terms = wp_list_pluck( $terms, 'term_id' );
	return $terms;
}

function sp_get_seasons( $post = 0, $ids = true ) {
	$terms = get_the_terms( $post, 'sp_season' );
	if ( $terms && $ids ) $terms = wp_list_pluck( $terms, 'term_id' );
	return $terms;
}

function sp_the_leagues( $post = 0, $delimiter = ', ' ) {
	$terms = sp_get_leagues( $post, false );
	$arr = array();
	if ( $terms ) {
		foreach ( $terms as $term ):
			$arr[] = $term->name;
		endforeach;
	}
	echo implode( $delimiter, $arr ); 
}

function sp_the_seasons( $post = 0, $delimiter = ', ' ) {
	$terms = sp_get_seasons( $post, false );
	$arr = array();
	if ( $terms ) {
		foreach ( $terms as $term ):
			$arr[] = $term->name;
		endforeach;
	}
	echo implode( $delimiter, $arr ); 
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
	if ( $main_result ) return $main_result;
	$results = get_posts( array( 'post_type' => 'sp_result', 'posts_per_page' => 1, 'orderby' => 'menu_order', 'order' => 'DESC' ) );
	if ( ! $results ) return null;
	$result = reset( $results );
	$slug = $result->post_name;
	return $slug;
}

function sp_get_main_results( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->main_results();
}

function sp_the_main_results( $post = 0, $delimiter = '-' ) {
	$results = sp_get_main_results( $post );
	echo implode( $delimiter, $results );
}

function sp_update_main_results( $post = 0, $results = array() ) {
	$event = new SP_Event( $post );
	return $event->update_main_results ( $results );
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
	if ( $main_performance ) return $main_performance;
	$options = get_posts( array( 'post_type' => 'sp_performance', 'posts_per_page' => 1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	if ( ! $options ) return null;
	$performance = reset( $options );
	$slug = $performance->post_name;
	return $slug;
}

function sp_get_performance( $post = 0 ) {
	$event = new SP_Event( $post );
	return $event->performance();
}

function sp_get_singular_name( $post = 0 ) {
	$singular = get_post_meta( $post, 'sp_singular', true );
	if ( '' !== $singular ) {
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
	return has_post_thumbnail ( $post );
}

function sp_get_logo( $post = 0, $size = 'icon', $attr = array() ) {
	return get_the_post_thumbnail( $post, 'sportspress-fit-' . $size, $attr );
}

function sp_get_logo_url( $post = 0, $size = 'icon' ) {
	$thumbnail_id = get_post_thumbnail_id( $post );
	$src = wp_get_attachment_image_src( $thumbnail_id, $size, false );
	return $src[0];
}

function sp_get_abbreviation( $post = 0 ) {
	return get_post_meta( $post, 'sp_abbreviation', true );
}

function sp_get_venues( $post = 0, $ids = true ) {
	$terms = get_the_terms( $post, 'sp_venue' );
	if ( $terms && $ids ) $terms = wp_list_pluck( $terms, 'term_id' );
	return $terms;
}

function sp_the_venues( $post = 0, $delimiter = ', ' ) {
	$terms = sp_get_venues( $post, false );
	$arr = array();
	if ( $terms ) {
		foreach ( $terms as $term ):
			$arr[] = $term->name;
		endforeach;
	}
	echo implode( $delimiter, $arr ); 
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

function sp_the_abbreviation( $post = 0 ) {
	echo sp_get_abbreviation( $post );
}

function sp_the_logo( $post = 0, $size = 'icon', $attr = array() ) {
	echo sp_get_logo( $post, $size, $attr );
}

function sp_team_logo( $post = 0 ) {
	sp_get_template( 'team-logo.php', array( 'id' => $post ) );
}

function sp_get_short_name( $post = 0 ) {
	$abbreviation = sp_get_abbreviation( $post, 'sp_abbreviation', true );
	if ( $abbreviation ) {
		return $abbreviation;
	} else {
		return get_the_title( $post );
	}
}

function sp_short_name( $post = 0 ) {
	echo sp_get_short_name( $post );
}

function sp_get_team_name( $post = 0, $short = true ) {
	if ( $short ) {
		return sp_get_short_name( $post );
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

function sp_get_player_name_with_number( $post = 0, $prepend = '', $append = '. ' ) {
	$number = sp_get_player_number( $post );
	if ( isset( $number ) && '' !== $number ) {
		return $prepend . $number . $append . get_the_title( $post );
	} else {
		return get_the_title( $post );
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

/*
 *
 */

function sp_get_position_caption( $term = 0 ) {
    $meta = get_option( "taxonomy_$term" );
	$caption = sp_array_value( $meta, 'sp_caption', '' );
	if ( $caption ) {
		return $caption;
	} else {
		$term = get_term( $term, 'sp_position' );
		return $term->name;
	}

}