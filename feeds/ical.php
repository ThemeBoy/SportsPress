<?php
/**
 * iCal Feed
 *
 * @author 		ThemeBoy
 * @category 	Feeds
 * @package 	SportsPress/Feeds
 * @version     1.8.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( 'sp_calendar' !== get_post_type( $post ) ) {
	wp_die( __( 'ERROR: This is not a valid feed template.', 'sportspress' ), '', array( 'response' => 404 ) );
}

// Get events in calendar
$calendar = new SP_Calendar( $post );
$events = $calendar->data();

// Get blog locale
$locale = substr( get_locale(), 0, 2 );

// Get main result setting
$main_result = get_option( 'sportspress_primary_result', null );

// Get the timezone setting
$timezone = sanitize_option( 'timezone_string', get_option( 'timezone_string' ) );

// Initialize output. Max line length is 75 chars.
$output =
"BEGIN:VCALENDAR\n" .
"VERSION:2.0\n" .
"PRODID:-//ThemeBoy//SportsPress//" . strtoupper( $locale ) . "\n" .
"CALSCALE:GREGORIAN\n" .
"METHOD:PUBLISH\n" .
"URL:" . add_query_arg( 'feed', 'sp-calendar-ical', get_post_permalink( $post ) ) . "\n" .
"X-FROM-URL:" . add_query_arg( 'feed', 'sp-calendar-ical', get_post_permalink( $post ) ) . "\n" .
"NAME:" . $post->post_title . "\n" .
"X-WR-CALNAME:" . $post->post_title . "\n" .
"DESCRIPTION:" . $post->post_title . "\n" .
"X-WR-CALDESC:" . $post->post_title . "\n" .
"REFRESH-INTERVAL;VALUE=DURATION:PT2M\n" .
"X-PUBLISHED-TTL:PT2M\n" .
"TZID:" . $timezone . "\n" .
"X-WR-TIMEZONE:" . $timezone . "\n";

// Loop through each event
foreach ( $events as $event):

	// Define date format
	$date_format = 'Ymd\THis';

	// Initialize end time	
	$end = new DateTime( $event->post_date );

	// Get full time minutes
	$minutes = get_post_meta( $event->ID, 'sp_minutes', true );
	if ( '' === $minutes ) $minutes = get_option( 'sportspress_event_minutes', 90 );

	// Add full time minutes to end time
	$end->add( new DateInterval( 'PT' . $minutes . 'M' ) );

	// Initialize location
	$location = '';

	// Get venue information
	$venues = get_the_terms( $event->ID, 'sp_venue' );
	if ( $venues ) {
		$venue = reset( $venues );
		$location .= $venue->name;

		// Get venue term meta
		$t_id = $venue->term_id;
		$meta = get_option( "taxonomy_$t_id" );

		// Add details to location
		$address = sp_array_value( $meta, 'sp_address', false );
		if ( false !== $address ) {
			$location = $venue->name . '\, ' . preg_replace('/([\,;])/','\\\$1', $address);
		}

		// Generate geo tag
		$latitude = sp_array_value( $meta, 'sp_latitude', false );
		$longitude = sp_array_value( $meta, 'sp_longitude', false );
		if ( false !== $latitude && false !== $longitude ) {
			$geo = $latitude . ';' . $longitude;
		} else {
			$geo = false;
		}
	}

	// Get title or write summary based on scores
	$results = array();
	$teams = (array)get_post_meta( $event->ID, 'sp_team', false );
	$teams = array_filter( $teams );
	$teams = array_unique( $teams );
	if ( ! empty( $teams ) ) {
		$event_results = get_post_meta( $event->ID, 'sp_results', true );
		foreach( $teams as $team_id ) {
			if ( ! $team_id ) continue;
			$team = get_post( $team_id );

			if ( $team ) {
				$team_results = sportspress_array_value( $event_results, $team_id, null );

				if ( $main_result ) {
					$team_result = sportspress_array_value( $team_results, $main_result, null );
				} else {
					if ( is_array( $team_results ) ) {
						end( $team_results );
						$team_result = prev( $team_results );
					} else {
						$team_result = null;
					}
				}

				if ( $team_result != null ) {
					$results[] = get_the_title( $team_id ) . ' ' . $team_result;
				}
			}
		}
	}
	if ( sizeof( $results ) ) {
		$summary = implode( ' ', $results );
	} else {
		$summary = $event->post_title;
	}

	// Append to output string
	$output .=
	"BEGIN:VEVENT\n" .
	"SUMMARY:" . preg_replace('/([\,;])/','\\\$1', $summary) . "\n" .
	"DESCRIPTION:" . preg_replace('/([\,;])/','\\\$1', $event->post_content) . "\n" .
	"UID:$event->ID\n" .
	"STATUS:CONFIRMED\n" .
	"DTSTART:" . mysql2date( $date_format, $event->post_date ) . "\n" .
	"DTEND:" . $end->format( $date_format ) . "\n" .
	"LAST-MODIFIED:" . mysql2date( $date_format, $event->post_modified_gmt ) . "\n";

	if ( $location ) {
		$output .= "LOCATION:" . $location . "\n";
	}

	if ( $geo ) {
		$output .= "GEO:" . $geo . "\n";
	}

	$output .= "END:VEVENT\n";
endforeach;

// End output
$output .= "END:VCALENDAR";

// Print headers
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=' . $post->post_name . '.ics');

// Print content
echo $output;
