<?php
$sportspress_sports = array();
include_once dirname( __FILE__ ) . '/presets/football.php';
include_once dirname( __FILE__ ) . '/presets/footy.php';
include_once dirname( __FILE__ ) . '/presets/baseball.php';
include_once dirname( __FILE__ ) . '/presets/basketball.php';
include_once dirname( __FILE__ ) . '/presets/gaming.php';
include_once dirname( __FILE__ ) . '/presets/cricket.php';
include_once dirname( __FILE__ ) . '/presets/golf.php';
include_once dirname( __FILE__ ) . '/presets/handball.php';
include_once dirname( __FILE__ ) . '/presets/hockey.php';
include_once dirname( __FILE__ ) . '/presets/racing.php';
include_once dirname( __FILE__ ) . '/presets/rugby.php';
include_once dirname( __FILE__ ) . '/presets/soccer.php';
include_once dirname( __FILE__ ) . '/presets/swimming.php';
include_once dirname( __FILE__ ) . '/presets/tennis.php';
include_once dirname( __FILE__ ) . '/presets/volleyball.php';

$sportspress_texts = array(
	'sp_team' => array(
		'Enter title here' => __( 'Team', 'sportspress' ),
		'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
		'Set Featured Image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
		'Parent' => sprintf( __( 'Parent %s', 'sportspress' ), __( 'Team', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Logo', 'sportspress' ) )
	),
	'sp_event' => array(
		'Enter title here' => '',
		'Scheduled for: <b>%1$s</b>' => __( 'Kick-off', 'sportspress' ) . ': <b>%1$s</b>',
		'Published on: <b>%1$s</b>' => __( 'Kick-off', 'sportspress' ) . ': <b>%1$s</b>',
		'Publish <b>immediately</b>' => __( 'Kick-off', 'sportspress' ) . ': <b>%1$s</b>'
	),
	'sp_player' => array(
		'Enter title here' => __( 'Name', 'sportspress' ),
		'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Set Featured Image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Scheduled for: <b>%1$s</b>' => __( 'Joined', 'sportspress' ) . ': <b>%1$s</b>',
		'Published on: <b>%1$s</b>' => __( 'Joined', 'sportspress' ) . ': <b>%1$s</b>',
		'Publish <b>immediately</b>' => __( 'Joined', 'sportspress' ) . ': <b>%1$s</b>'
	),
	'sp_staff' => array(
		'Enter title here' => __( 'Name', 'sportspress' ),
		'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Set Featured Image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Scheduled for: <b>%1$s</b>' => __( 'Joined', 'sportspress' ) . ': <b>%1$s</b>',
		'Published on: <b>%1$s</b>' => __( 'Joined', 'sportspress' ) . ': <b>%1$s</b>',
		'Publish <b>immediately</b>' => __( 'Joined', 'sportspress' ) . ': <b>%1$s</b>'
	),
	'sp_table' => array(
		'Enter title here' => ''
	)
);

$sportspress_thumbnail_texts = array(
	'sp_team' => array(
		'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Logo', 'sportspress' ) )
	),
	'sp_player' => array(
		'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) )
	),
	'sp_staff' => array(
		'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) )
	)
);
?>