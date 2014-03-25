<?php
function sportspress_define_globals() {

	// Options
	global $sportspress_options;
	
	$sportspress_options = (array)get_option( 'sportspress', array() );

	// Text
	global $sportspress_text_options;
	
	$sportspress_text_options = array(
		__( 'Article', 'sportspress' ),
		__( 'Current Team', 'sportspress' ),
		__( 'Date', 'sportspress' ),
		__( 'Details', 'sportspress' ),
		__( 'days', 'sportspress' ),
		__( 'Event', 'sportspress' ),
		__( 'Friendly', 'sportspress' ),
		__( 'hrs', 'sportspress' ),
		__( 'League', 'sportspress' ),
		__( 'mins', 'sportspress' ),
		__( 'Nationality', 'sportspress' ),
		__( 'Past Teams', 'sportspress' ),
		__( 'Player', 'sportspress' ),
		__( 'Position', 'sportspress' ),
		__( 'Pos', 'sportspress' ),
		__( 'Preview', 'sportspress' ),
		__( 'Rank', 'sportspress' ),
		__( 'Recap', 'sportspress' ),
		__( 'Results', 'sportspress' ),
		__( 'Season', 'sportspress' ),
		__( 'secs', 'sportspress' ),
		__( 'Staff', 'sportspress' ),
		__( 'Substitute', 'sportspress' ),
		__( 'Team', 'sportspress' ),
		__( 'Teams', 'sportspress' ),
		__( 'Time', 'sportspress' ),
		__( 'Total', 'sportspress' ),
		__( 'Venue', 'sportspress' ),
		__( 'View all players', 'sportspress' ),
		__( 'View all events', 'sportspress' ),
		__( 'View full table', 'sportspress' ),
	);

	sort( $sportspress_text_options );

	// Formats
	global $sportspress_formats;

	$sportspress_formats = array( 'event' => array(), 'list' => array() );

	$sportspress_formats['event']['league'] = __( 'League', 'sportspress' );
	$sportspress_formats['event']['friendly'] = __( 'Friendly', 'sportspress' );

	$sportspress_formats['calendar']['calendar'] = __( 'Calendar', 'sportspress' );
	$sportspress_formats['calendar']['list'] = __( 'List', 'sportspress' );

	$sportspress_formats['list']['list'] = __( 'List', 'sportspress' );
	$sportspress_formats['list']['gallery'] = __( 'Gallery', 'sportspress' );

	// Sports
	global $sportspress_sports;

	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/soccer.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/football.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/footy.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/baseball.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/basketball.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/gaming.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/cricket.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/golf.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/handball.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/hockey.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/racing.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/rugby.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/swimming.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/tennis.php';
	include_once dirname( SP_PLUGIN_FILE ) . '/presets/sports/volleyball.php';

	uasort( $sportspress_sports, 'sportspress_sort_sports' );
}
add_action( 'init', 'sportspress_define_globals' );
