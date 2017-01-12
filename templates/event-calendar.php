<?php
/**
 * Event Calendar
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb, $m, $monthnum, $year, $wp_locale;

$defaults = array(
	'id' => null,
	'status' => 'default',
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'day' => 'default',
	'league' => null,
	'season' => null,
	'venue' => null,
	'team' => null,
	'player' => null,
	'initial' => true,
	'caption_tag' => 'caption',
	'show_all_events_link' => false,
	'override_global_date' => false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );
if ( $status != 'default' )
	$calendar->status = $status;
if ( $date != 'default' )
	$calendar->date = $date;
if ( $date_from != 'default' )
	$calendar->from = $date_from;
if ( $date_to != 'default' )
	$calendar->to = $date_to;
if ( $day != 'default' )
	$calendar->day = $day;
if ( $league )
	$calendar->league = $league;
if ( $season )
	$calendar->season = $season;
if ( $venue )
	$calendar->venue = $venue;
if ( $team )
	$calendar->team = $team;
if ( $player )
	$calendar->player = $player;
if ($override_global_date) {
	$year = gmdate('Y', current_time('timestamp'));
	$monthnum = gmdate('m', current_time('timestamp'));
}
$events = $calendar->data();

if ( empty( $events ) ) {
	$in = 'AND 1 = 0'; // False logic to prevent SQL error
} else {
	$event_ids = wp_list_pluck( $events, 'ID' );
	$in = 'AND ID IN (' . implode( ', ', $event_ids ) . ')';
}

// week_begins = 0 stands for Sunday
$week_begins = intval(get_option('start_of_week'));

// Get year and month from query vars
$year = isset( $_GET['sp_year'] ) ? $_GET['sp_year'] : $year;
$monthnum =  isset( $_GET['sp_month'] ) ? $_GET['sp_month'] : $monthnum;

// Let's figure out when we are
if ( !empty($monthnum) && !empty($year) ) {
	$thismonth = ''.zeroise(intval($monthnum), 2);
	$thisyear = ''.intval($year);
} elseif ( !empty($w) ) {
	// We need to get the month from MySQL
	$thisyear = ''.intval(substr($m, 0, 4));
	$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
	$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
} elseif ( !empty($m) ) {
	$thisyear = ''.intval(substr($m, 0, 4));
	if ( strlen($m) < 6 )
			$thismonth = '01';
	else
			$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
} else {
	$thisyear = gmdate('Y', current_time('timestamp'));
	$thismonth = gmdate('m', current_time('timestamp'));
}

$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
$last_day = date('t', $unixmonth);

// Get the next and previous month and year with at least one post
$previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
	FROM $wpdb->posts
	WHERE post_date < '$thisyear-$thismonth-01'
	AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
	$in
		ORDER BY post_date DESC
		LIMIT 1");
$next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
	FROM $wpdb->posts
	WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
	AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
	$in
		ORDER BY post_date ASC
		LIMIT 1");

/* translators: Calendar caption: 1: month name, 2: 4-digit year */
$calendar_caption = _x('%1$s %2$s', 'calendar caption', 'sportspress');
$calendar_output = '
<div class="sp-calendar-wrapper">
<table id="wp-calendar" class="sp-calendar sp-event-calendar sp-data-table">
<caption class="sp-table-caption">' . ( $caption_tag == 'caption' ? '' : '<' . $caption_tag . '>' ) . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . ( $caption_tag == 'caption' ? '' : '</' . $caption_tag . '>' ) . '</caption>
<thead>
<tr>';

$myweek = array();

for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
	$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
}

foreach ( $myweek as $wd ) {
	$day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
	$wd = esc_attr($wd);
	$calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
}

$calendar_output .= '
</tr>
</thead>

<tfoot>
<tr>';

if ( $previous ) {
	$calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="sp-previous-month"><a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-right" href="' . add_query_arg( array( 'sp_year' => $previous->year, 'sp_month' => $previous->month ) ) . '" title="' . esc_attr( sprintf(_x('%1$s %2$s', 'calendar caption', 'sportspress'), $wp_locale->get_month($previous->month), date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year)))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
} else {
	$calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
}

$calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';

if ( $next ) {
	$calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="sp-next-month"><a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-left" href="' . add_query_arg( array( 'sp_year' => $next->year, 'sp_month' => $next->month ) ) . '" title="' . esc_attr( sprintf(_x('%1$s %2$s', 'calendar caption', 'sportspress'), $wp_locale->get_month($next->month), date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) ) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
} else {
	$calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
}

$calendar_output .= '
</tr>
</tfoot>

<tbody>
<tr>';

// Get days with posts
$dayswithposts = $wpdb->get_results("SELECT DAYOFMONTH(post_date), ID
	FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
	AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
	$in
	AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N);
if ( $dayswithposts ) {
	foreach ( (array) $dayswithposts as $daywith ) {
		$daywithpost[ $daywith[0] ][] = $daywith[1];
	}
} else {
	$daywithpost = array();
}

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'camino') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false)
	$ak_title_separator = "\n";
else
	$ak_title_separator = ', ';

$ak_titles_for_day = array();
$ak_post_titles = $wpdb->get_results("SELECT ID, post_title, post_date, DAYOFMONTH(post_date) as dom "
	."FROM $wpdb->posts "
	."WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' "
	."AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59' "
	."AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' ) "
	."$in"
);
if ( $ak_post_titles ) {
	foreach ( (array) $ak_post_titles as $ak_post_title ) {

			/** This filter is documented in wp-includes/post-template.php */
			$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) . ' @ ' . apply_filters( 'sportspress_event_time', date_i18n( get_option( 'time_format' ), strtotime( $ak_post_title->post_date ) ), $ak_post_title->ID ) );

			if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
				$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
			if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
				$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
			else
				$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
	}
}

// See how much we should pad in the beginning
$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
if ( 0 != $pad )
	$calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

$daysinmonth = intval(date('t', $unixmonth));
for ( $day = 1; $day <= $daysinmonth; ++$day ) {
	if ( isset($newrow) && $newrow )
		$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
	$newrow = false;

	if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
		$calendar_output .= '<td id="today" class="sp-highlight">';
	else
		$calendar_output .= '<td>';

	if ( array_key_exists($day, $daywithpost) ) // any posts today?
		$calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . ( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . '" title="' . sprintf( '%s events', ( sizeof( $daywithpost[ $day ] ) ) ) : get_post_permalink( $daywithpost[ $day ][0], false, true ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) . "\">$day</a>";
	else
		$calendar_output .= $day;
	$calendar_output .= '</td>';

	if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
		$newrow = true;
}

$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
if ( $pad != 0 && $pad != 7 )
	$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>\n\t</div>";

if ( $id && $show_all_events_link )
	$calendar_output .= '<div class="sp-calendar-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a></div>';
?>
<div class="sp-template sp-template-event-calendar">
	<?php echo $calendar_output; ?>
</div>
