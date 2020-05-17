<?php
/**
 * iCal Feed
 *
 * @author 		ThemeBoy
 * @category 	Feeds
 * @package 	SportsPress/Feeds
 * @version     2.6.15
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

// Get the URL
$url = add_query_arg( 'feed', 'sp-ical', get_post_permalink( $post ) );
$url = wordwrap( $url , 60, "\r\n\t", true );

$output =
"BEGIN:VCALENDAR\r\n" .
"VERSION:2.0\r\n" .
"PRODID:-//ThemeBoy//SportsPress//" . strtoupper( $locale ) . "\r\n" .
"CALSCALE:GREGORIAN\r\n" .
"METHOD:PUBLISH\r\n" .
"URL:" . $url . "\r\n" .
"X-FROM-URL:" . $url . "\r\n" .
"NAME:" . $post->post_title . "\r\n" .
"X-WR-CALNAME:" . $post->post_title . "\r\n" .
"DESCRIPTION:" . $post->post_title . "\r\n" .
"X-WR-CALDESC:" . $post->post_title . "\r\n" .
"REFRESH-INTERVAL;VALUE=DURATION:PT2M\r\n" .
"X-PUBLISHED-TTL:PT2M\r\n" .
"TZID:" . $timezone . "\r\n" .
"X-WR-TIMEZONE:" . $timezone . "\r\n";

// Loop through each event
foreach ( $events as $event):

	// Define date format
	$date_format = 'Ymd\THis';

	// Get description
	$description = preg_replace( '/([\,;])/','\\\$1', $event->post_content );
	$description = wordwrap( $description , 60, "\n\t" );

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
	$location = wordwrap( $location , 60, "\r\n\t" );

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
	
	//Convert &#[0-9]+ entities to UTF-8
	$summary = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $summary);
	
	// Append to output string
	$output .=
	"BEGIN:VEVENT\r\n" .
	"SUMMARY:" . preg_replace( '/([\,;])/','\\\$1', $summary ) . "\r\n" .
	"UID:$event->ID\r\n" .
	"STATUS:CONFIRMED\r\n" .
	"DTSTAMP:19700101T000000\r\n".
	"DTSTART:" . mysql2date( $date_format, $event->post_date ) . "\r\n" .
	"DTEND:" . $end->format( $date_format ) . "\r\n" .
	"LAST-MODIFIED:" . mysql2date( $date_format, $event->post_modified_gmt ) . "\r\n";

	if ( $description ) {
		$output .= "DESCRIPTION:" . $description . "\r\n";
	}

	if ( $location ) {
		$output .= "LOCATION:" . $location . "\r\n";
	}

	if ( $geo ) {
		$output .= "GEO:" . $geo . "\r\n";
	}

	$output .= "END:VEVENT\r\n";
endforeach;

// End output
$output .= "END:VCALENDAR";

// Print headers
header('Content-type: text/calendar; charset=utf-8');

// The E-Tag is not being changed when the output file is generated – Some Webdav clients do not like this and
// do not then 'see' that the file has changed – updates to the calendars are then not displayed to the user.
// Props @garygomm https://wordpress.org/support/topic/calendar-feed-issue-not-updating-after-change/
$etag = md5($output);
header('Etag:' . '"'.$etag.'"');

header('Content-Disposition: inline; filename=' . $post->post_name . '.ics');

// Print content
echo $output;
