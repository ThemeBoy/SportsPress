<?php
function sportspress_define_globals() {

	// Options
	global $sportspress_options;
	
	$sportspress_options = (array)get_option( 'sportspress', array() );

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
