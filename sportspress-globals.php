<?php
// Sports array to be populated with presets
$sportspress_sports = array();

// Localize sport names
__( 'Association Football (Soccer)', 'sportspress' );
__( 'American Football', 'sportspress' );
__( 'Australian Rules Football', 'sportspress' );
__( 'Baseball', 'sportspress' );
__( 'Basketball', 'sportspress' );
__( 'Competitive Gaming', 'sportspress' );
__( 'Cricket', 'sportspress' );
__( 'Golf', 'sportspress' );
__( 'Handball', 'sportspress' );
__( 'Hockey', 'sportspress' );
__( 'Racing', 'sportspress' );
__( 'Rugby', 'sportspress' );
__( 'Swimming', 'sportspress' );
__( 'Tennis', 'sportspress' );
__( 'Volleyball', 'sportspress' );

// Localize post titles
__( 'Appearances', 'sportspress' );
__( 'Goals', 'sportspress' );
__( 'Assists', 'sportspress' );
__( 'Yellow Cards', 'sportspress' );
__( 'Red Cards', 'sportspress' );
__( 'Height', 'sportspress' );
__( 'Weight', 'sportspress' );

// Localize post context texts
__( 'Select Logo', 'sportspress' );
__( 'Remove Logo', 'sportspress' );
__( 'Kick-off: <b>%1$s</b>', 'sportspress' );
__( 'Joined: <b>%1$s</b>', 'sportspress' );
__( 'Select Photo', 'sportspress' );
__( 'Remove Photo', 'sportspress' );

// Localize configuration formats
__( 'Integer', 'sportspress' );
__( 'Decimal', 'sportspress' );
__( 'Time', 'sportspress' );
__( 'Custom Field', 'sportspress' );

$sportspress_texts = array(
	'sp_team' => array(
		'Enter title here' => 'Team',
		'Set featured image' => 'Select Logo',
		'Set Featured Image' => 'Select Logo',
		'Remove featured image' => 'Remove Logo',
	),
	'sp_event' => array(
		'Enter title here' => '(no title)',
		'Scheduled for: <b>%1$s</b>' => 'Kick-off: <b>%1$s</b>',
		'Published on: <b>%1$s</b>' => 'Kick-off: <b>%1$s</b>',
		'Publish <b>immediately</b>' => 'Kick-off: <b>%1$s</b>',
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

$sportspress_thumbnail_texts = array(
	'sp_team' => array(
		'Set featured image' => 'Select Logo',
		'Remove featured image' => 'Remove Logo',
	),
	'sp_player' => array(
		'Set featured image' => 'Select Photo',
		'Remove featured image' => 'Remove Photo',
	),
	'sp_staff' => array(
		'Set featured image' => 'Select Photo',
		'Remove featured image' => 'Remove Photo',
	),
);

$sportspress_config_formats = array(
	'integer' => 'Integer',
	'decimal' => 'Decimal',
	'time' => 'Time',
	'custom' => 'Custom Field',
);
?>