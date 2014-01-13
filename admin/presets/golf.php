<?php
global $sportspress_sports;

$sportspress_sports['golf'] = array(
	'name' => __( 'Golf', 'sportspress' ),
	'posts' => array(
		// Table Columns
		'sp_column' => array(
		),
		// Statistics
		'sp_statistic' => array(
			array(
				'post_title' => 'Events',
				'post_name' => 'events',
				'meta'       => array(
					'sp_equation'     => '$eventsplayed',
				),
			),
			array(
				'post_title' => 'Avg',
				'post_name' => 'avg',
				'meta'       => array(
					'sp_equation'     => '$ptsfor / $eventsplayed',
				),
			),
			array(
				'post_title' => 'Total',
				'post_name' => 'total',
				'meta'       => array(
					'sp_equation'     => '$ptsfor',
				),
			),
			array(
				'post_title' => 'PL',
				'post_name' => 'lost',
				'meta'       => array(
					'sp_equation'     => '$ptsagainst',
				),
			),
			array(
				'post_title' => 'PG',
				'post_name' => 'gained',
				'meta'       => array(
					'sp_equation'     => '$ptsfor',
				),
			),
		),
		// Results
		'sp_result' => array(
		),
		// Outcomes
		'sp_outcome' => array(
		),
	),
);
