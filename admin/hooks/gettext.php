<?php
function sportspress_gettext( $translated_text, $untranslated_text, $domain ) {
	global $typenow;

	$texts = array(
		'sp_team' => array(
			'Enter title here' => 'Team',
			'Set featured image' => 'Select Logo',
			'Set Featured Image' => 'Select Logo',
			'Remove featured image' => 'Remove Logo',
		),
		'sp_event' => array(
			'Enter title here' => '(no title)',
			'Scheduled for: <b>%1$s</b>' => 'Date/Time: <b>%1$s</b>',
			'Published on: <b>%1$s</b>' => 'Date/Time: <b>%1$s</b>',
			'Publish <b>immediately</b>' => 'Date/Time: <b>%1$s</b>',
		),
		'sp_player' => array(
			'Enter title here' => 'Name',
			'Set featured image' => 'Select Photo',
			'Set Featured Image' => 'Select Photo',
			'Remove featured image' => 'Remove Photo',
			'Scheduled for: <b>%1$s</b>' => 'Joined: <b>%1$s</b>',
			'Published on: <b>%1$s</b>' => 'Joined: <b>%1$s</b>',
			'Publish <b>immediately</b>' => 'Joined: <b>%1$s</b>',
		),
		'sp_staff' => array(
			'Enter title here' => 'Name',
			'Set featured image' => 'Select Photo',
			'Set Featured Image' => 'Select Photo',
			'Remove featured image' => 'Remove Photo',
			'Scheduled for: <b>%1$s</b>' => 'Joined: <b>%1$s</b>',
			'Published on: <b>%1$s</b>' => 'Joined: <b>%1$s</b>',
			'Publish <b>immediately</b>' => 'Joined: <b>%1$s</b>',
		),
	);

	if ( is_admin() && array_key_exists( $typenow, $texts ) && array_key_exists( $untranslated_text, $texts[ $typenow ] ) )
		return __( $texts[ $typenow ][ $untranslated_text ], 'sportspress' );
	else
		return $translated_text;
}
add_filter( 'gettext', 'sportspress_gettext', 20, 3 );
