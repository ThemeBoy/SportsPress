<?php
function sportspress_define_formats_global() {
	global $sportspress_formats;

	$sportspress_formats = array( 'event' => array(), 'list' => array() );

	$sportspress_formats['event']['league'] = __( 'League', 'sportspress' );
	$sportspress_formats['event']['friendly'] = __( 'Friendly', 'sportspress' );

	$sportspress_formats['list']['list'] = __( 'List', 'sportspress' );
	$sportspress_formats['list']['gallery'] = __( 'Gallery', 'sportspress' );
}
add_action( 'init', 'sportspress_define_formats_global', 10 );
