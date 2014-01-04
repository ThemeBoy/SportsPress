<?php
global $sportspress_sports;

$sportspress_sports['football'] = array(
	'name' => __( 'American Football', 'sportspress' ),
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
				'post_title' => __( 'Touchdowns', 'sportspress' ),
				'post_name'  => 'touchdowns'
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
				'post_title' => __( 'W', 'sportspress' ),
				'post_name'  => 'w',
				'meta'       => array( 'sp_equation' => '$win' )
			),
			array(
				'post_title' => __( 'L', 'sportspress' ),
				'post_name'  => 'l',
				'meta'       => array( 'sp_equation' => '$loss' )
			),
			array(
				'post_title' => __( 'T', 'sportspress' ),
				'post_name'  => 'd',
				'meta'       => array( 'sp_equation' => '$draw' )
			),
			array(
				'post_title' => __( 'PCT', 'sportspress' ),
				'post_name'  => 'pct',
				'meta'       => array( 'sp_equation' => '$win / $eventsplayed', 'sp_priority' => '1', 'sp_order' => 'DESC' )
			),
			array(
				'post_title' => __( 'PF', 'sportspress' ),
				'post_name'  => 'pf',
				'meta'       => array( 'sp_equation' => '$pointsfor', 'sp_priority' => '2', 'sp_order' => 'DESC' )
			),
			array(
				'post_title' => __( 'PA', 'sportspress' ),
				'post_name'  => 'pa',
				'meta'       => array( 'sp_equation' => '$pointsagainst' )
			),
			array(
				'post_title' => __( 'STRK', 'sportspress' ),
				'post_name'  => 'strk',
				'meta'       => array( 'sp_equation' => '$streak' )
			)
		)
	)
);