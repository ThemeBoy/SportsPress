<?php
global $sportspress_sports;

$sportspress_sports['football'] = array(
	'name' => 'American Football',
	'posts' => array(
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'W',
				'post_name' => 'w',
				'meta'       => array(
					'sp_equation'     => '$w',
				),
			),
			array(
				'post_title' => 'L',
				'post_name' => 'l',
				'meta'       => array(
					'sp_equation'     => '$l',
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'meta'       => array(
					'sp_equation'     => '$w / $eventsplayed',
				),
			),
			array(
				'post_title' => 'PF',
				'post_name' => 'pf',
				'meta'       => array(
					'sp_equation'     => '$ptsfor',
				),
			),
			array(
				'post_title' => 'PA',
				'post_name' => 'pa',
				'meta'       => array(
					'sp_equation'     => '$ptsagainst',
				),
			),
			array(
				'post_title' => 'Str',
				'post_name' => 'strk',
				'meta'       => array(
					'sp_equation'     => '$streak',
				),
			),
		),
		// Statistics
		'sp_statistic' => array(
		),
		// Results
		'sp_result' => array(
		),
		// Outcomes
		'sp_outcome' => array(
		),
	),
);
