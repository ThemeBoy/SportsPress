<?php
global $sportspress_sports;

$sportspress_sports['baseball'] = array(
	'name' => __( 'Baseball', 'sportspress' ),
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
				'post_title' => __( 'Loss', 'sportspress' ),
				'post_name'  => 'loss'
			)
		),
		// Results
		'sp_result' => array(
			array(
				'post_title' => __( '1', 'sportspress' ),
				'post_name'  => 'one'
			),
			array(
				'post_title' => __( '2', 'sportspress' ),
				'post_name'  => 'two'
			),
			array(
				'post_title' => __( '3', 'sportspress' ),
				'post_name'  => 'three'
			),
			array(
				'post_title' => __( '4', 'sportspress' ),
				'post_name'  => 'four'
			),
			array(
				'post_title' => __( '5', 'sportspress' ),
				'post_name'  => 'five'
			),
			array(
				'post_title' => __( '6', 'sportspress' ),
				'post_name'  => 'six'
			),
			array(
				'post_title' => __( '7', 'sportspress' ),
				'post_name'  => 'seven'
			),
			array(
				'post_title' => __( '8', 'sportspress' ),
				'post_name'  => 'eight'
			),
			array(
				'post_title' => __( '9', 'sportspress' ),
				'post_name'  => 'nine'
			),
			array(
				'post_title' => __( '&nbsp;', 'sportspress' ),
				'post_name'  => 'nbsp'
			),
			array(
				'post_title' => __( 'R', 'sportspress' ),
				'post_name'  => 'r'
			),
			array(
				'post_title' => __( 'H', 'sportspress' ),
				'post_name'  => 'h'
			),
			array(
				'post_title' => __( 'E', 'sportspress' ),
				'post_name'  => 'e'
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
				'post_title' => __( 'PCT', 'sportspress' ),
				'post_name'  => 'pct',
				'meta'       => array( 'sp_equation' => '$win / $eventsplayed' )
			),
			array(
				'post_title' => __( 'GB', 'sportspress' ),
				'post_name'  => 'gb',
				'meta'       => array( 'sp_equation' => '( $winmax + $loss - $win - $lossmax ) / 2' )
			),
			array(
				'post_title' => __( 'STRK', 'sportspress' ),
				'post_name'  => 'strk',
				'meta'       => array( 'sp_equation' => '$streak' )
			)
		)
	)
);