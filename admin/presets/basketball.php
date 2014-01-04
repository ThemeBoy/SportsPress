<?php
global $sportspress_sports;

$sportspress_sports['baseball'] = array(
	'name' => __( 'Baseball', 'sportspress' ),
	'posts' => array(
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => __( 'Wins', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$win',
					'sp_abbreviation' => __( 'W', 'sportspress' )
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
				'post_title' => __( 'Win Percentage', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$win / $eventsplayed',
					'sp_abbreviation' => __( 'Pct', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Games Behind', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '( $winmax + $loss - $win - $lossmax ) / 2',
					'sp_abbreviation' => __( 'GB', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Streak', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$streak',
					'sp_abbreviation' => __( 'Strk', 'sportspress' )
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
					'sp_equation'     => ''
				)
			),
			array(
				'post_title' => __( 'Assists', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => ''
				)
			),
			array(
				'post_title' => __( 'Yellow Cards', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => ''
				)
			),
			array(
				'post_title' => __( 'Red Cards', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => ''
				)
			)
		),
		// Results
		'sp_result' => array(
			array(
				'post_title' => __( '1st Quarter', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '1', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '2nd Quarter', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '2', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '3rd Quarter', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '3', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '4th Quarter', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '4', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Overtime', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '&nbsp;', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Total', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( 'T', 'sportspress' )
				)
			)
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => __( 'Win', 'sportspress' )
			),
			array(
				'post_title' => __( 'Loss', 'sportspress' )
			)
		)
	)
);