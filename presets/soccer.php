<?php
global $sportspress_sports;

$sportspress_sports['soccer'] = array(
	'name' => __( 'Association Football (Soccer)', 'sportspress' ),
	'posts' => array(
		// Statistics
		'sp_statistic' => array(
			array(
				'post_title' => __( 'Appearances', 'sportspress' ),
				'post_name'  => 'appearances',
				'meta'       => array( 'sp_equation' => '$eventsplayed' )
			),
			array(
				'post_title' => __( 'Goals', 'sportspress' ),
				'post_name'  => 'goals',
				'meta'       => array( 'sp_equation' => '' )
			),
			array(
				'post_title' => __( 'Assists', 'sportspress' ),
				'post_name'  => 'assists',
				'meta'       => array( 'sp_equation' => '' )
			),
			array(
				'post_title' => __( 'Yellow Cards', 'sportspress' ),
				'post_name'  => 'yellowcards',
				'meta'       => array( 'sp_equation' => '' )
			),
			array(
				'post_title' => __( 'Red Cards', 'sportspress' ),
				'post_name'  => 'redcards',
				'meta'       => array( 'sp_equation' => '' )
			)
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => __( 'Win', 'sportspress' ),
				'post_name'  => 'win'
			),
			array(
				'post_title' => __( 'Draw', 'sportspress' ),
				'post_name'  => 'draw'
			),
			array(
				'post_title' => __( 'Loss', 'sportspress' ),
				'post_name'  => 'loss'
			)
		),
		// Results
		'sp_result' => array(
			array(
				'post_title' => __( 'Goals', 'sportspress' ),
				'post_name'  => 'goals'
			),
			array(
				'post_title' => __( '1st Half', 'sportspress' ),
				'post_name'  => 'firsthalf'
			),
			array(
				'post_title' => __( '2nd Half', 'sportspress' ),
				'post_name'  => 'secondhalf'
			)
		),
		// Columns
		'sp_column' => array(
			array(
				'post_title' => __( 'P', 'sportspress' ),
				'post_name'  => 'p',
				'meta'       => array( 'sp_equation' => '$eventsplayed' )
			),
			array(
				'post_title' => __( 'W', 'sportspress' ),
				'post_name'  => 'w',
				'meta'       => array( 'sp_equation' => '$win' )
			),
			array(
				'post_title' => __( 'D', 'sportspress' ),
				'post_name'  => 'd',
				'meta'       => array( 'sp_equation' => '$draw' )
			),
			array(
				'post_title' => __( 'L', 'sportspress' ),
				'post_name'  => 'l',
				'meta'       => array( 'sp_equation' => '$loss' )
			),
			array(
				'post_title' => __( 'F', 'sportspress' ),
				'post_name'  => 'f',
				'meta'       => array( 'sp_equation' => '$goalsfor', 'sp_priority' => '3', 'sp_order' => 'DESC' )
			),
			array(
				'post_title' => __( 'A', 'sportspress' ),
				'post_name'  => 'a',
				'meta'       => array( 'sp_equation' => '$goalsagainst' )
			),
			array(
				'post_title' => __( 'GD', 'sportspress' ),
				'post_name'  => 'gd',
				'meta'       => array( 'sp_equation' => '$goalsfor - $goalsagainst', 'sp_priority' => '2', 'sp_order' => 'DESC' )
			),
			array(
				'post_title' => __( 'PTS', 'sportspress' ),
				'post_name'  => 'pts',
				'meta'       => array( 'sp_equation' => '$win * 3 + $draw', 'sp_priority' => '1', 'sp_order' => 'DESC' )
			)
		)
	)
);