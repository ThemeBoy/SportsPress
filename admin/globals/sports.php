<?php
function sportspress_define_sports_global() {
	global $sportspress_sports;

	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/soccer.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/football.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/footy.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/baseball.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/basketball.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/gaming.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/cricket.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/golf.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/handball.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/hockey.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/racing.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/rugby.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/swimming.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/tennis.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/presets/volleyball.php';

	uasort( $sportspress_sports, 'sportspress_sort_sports' );
}
add_action( 'init', 'sportspress_define_sports_global' );
