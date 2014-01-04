<?php
global $sportspress_sports;

$sportspress_sports['soccer'] = array(
	'name' => __( 'Association Football (Soccer)', 'sportspress' ),
	'posts' => array(
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => __( 'Games Played', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$eventsplayed',
					'sp_abbreviation' => __( 'GP', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Wins', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$win',
					'sp_abbreviation' => __( 'W', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Draws', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$draw',
					'sp_abbreviation' => __( 'D', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Losses', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$loss',
					'sp_abbreviation' => __( 'L', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Goals For', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$goalsfor',
					'sp_priority'     => '3',
					'sp_order'        => 'DESC',
					'sp_abbreviation' => __( 'GF', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Goals Against', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$goalsagainst',
					'sp_abbreviation' => __( 'GA', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Goal Difference', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$goalsfor - $goalsagainst',
					'sp_priority'     => '2',
					'sp_order'        => 'DESC',
					'sp_abbreviation' => __( 'GD', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Points', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$win * 3 + $draw',
					'sp_priority'     => '1',
					'sp_order'        => 'DESC',
					'sp_abbreviation' => __( 'Pts', 'sportspress' )
				)
			)
		),
		// Statistics
		'sp_statistic' => array(
			array(
				'post_title' => __( 'Appearances', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$eventsplayed'
				)
			),
			array(
				'post_title' => __( 'Goals', 'sportspress' ),
				'meta'       => array(
					'sp_priority'     => '1',
					'sp_order'        => 'DESC'
				)
			),
			array(
				'post_title' => __( 'Assists', 'sportspress' )
			),
			array(
				'post_title' => __( 'Yellow Cards', 'sportspress' )
			),
			array(
				'post_title' => __( 'Red Cards', 'sportspress' )
			)
		),
		// Results
		'sp_result' => array(
			array(
				'post_title' => __( 'Goals', 'sportspress' )
			),
			array(
				'post_title' => __( '1st Half', 'sportspress' )
			),
			array(
				'post_title' => __( '2nd Half', 'sportspress' )
			)
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => __( 'Win', 'sportspress' )
			),
			array(
				'post_title' => __( 'Draw', 'sportspress' )
			),
			array(
				'post_title' => __( 'Loss', 'sportspress' )
			)
		)
	)
);