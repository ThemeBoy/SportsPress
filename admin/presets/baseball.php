<?php
global $sportspress_sports;

$sportspress_sports['baseball'] = array(
	'name' => __( 'Baseball', 'sportspress' ),
	'posts' => array(
		// Columns
		'sp_column' => array(
			array(
				'post_title' => __( 'Wins', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$win',
					'sp_abbreviation' => __( 'W', 'sportspress' ),
					'sp_priority'     => 1,
					'sp_order'        => 'DESC'
				)
			),
			array(
				'post_title' => __( 'Losses', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$loss',
					'sp_abbreviation' => __( 'L', 'sportspress' ),
					'sp_priority'     => 2,
					'sp_order'        => 'ASC'
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
				'post_title' => __( 'Games Back', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '( $winmax + $loss - $win - $lossmax ) / 2',
					'sp_abbreviation' => __( 'GB', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Runs Scored', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$rfor',
					'sp_abbreviation' => __( 'RS', 'sportspress' ),
					'sp_priority'     => 3,
					'sp_order'        => 'DESC'
				)
			),
			array(
				'post_title' => __( 'Runs Against', 'sportspress' ),
				'meta'       => array(
					'sp_equation'     => '$ragainst',
					'sp_abbreviation' => __( 'RA', 'sportspress' )
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
					'sp_equation' => '$eventsplayed'
				)
			),
			array(
				'post_title' => __( 'Goals', 'sportspress' ),
				'meta'       => array( 'sp_equation' => '' )
			),
			array(
				'post_title' => __( 'Assists', 'sportspress' ),
				'meta'       => array( 'sp_equation' => '' )
			),
			array(
				'post_title' => __( 'Yellow Cards', 'sportspress' ),
				'meta'       => array( 'sp_equation' => '' )
			),
			array(
				'post_title' => __( 'Red Cards', 'sportspress' ),
				'meta'       => array( 'sp_equation' => '' )
			)
		),
		// Results
		'sp_result' => array(
			array(
				'post_title' => __( '1st Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '1', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '2nd Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '2', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '3rd Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '3', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '4th Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '4', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '5th Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '5', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '6th Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '6', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '7th Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '7', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '8th Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '8', 'sportspress' )
				)
			),
			array(
				'post_title' => __( '9th Inning', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '9', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Extra Innings', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( '&nbsp;', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Runs', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( 'R', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Hits', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( 'H', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Errors', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( 'E', 'sportspress' )
				)
			)
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => __( 'Win', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( 'W', 'sportspress' )
				)
			),
			array(
				'post_title' => __( 'Loss', 'sportspress' ),
				'meta'       => array(
					'sp_abbreviation' => __( 'L', 'sportspress' )
				)
			)
		)
	)
);