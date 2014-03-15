<?php
global $sportspress_sports;

$sportspress_sports['hockey'] = array(
	'name' => __( 'Hockey', 'sportspress' ),
	'posts' => array(
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'GP',
				'post_name'  => 'gp',
				'meta'       => array(
					'sp_equation'     => '$eventsplayed',
				),
			),
			array(
				'post_title' => 'W',
				'post_name'  => 'w',
				'meta'       => array(
					'sp_equation'     => '$w',
				),
			),
			array(
				'post_title' => 'L',
				'post_name'  => 'l',
				'meta'       => array(
					'sp_equation'     => '$l',
				),
			),
			array(
				'post_title' => 'OT',
				'post_name'  => 'ot',
				'meta'       => array(
					'sp_equation'     => '$ot',
				),
			),
			array(
				'post_title' => 'P',
				'post_name'  => 'p',
				'meta'       => array(
					'sp_equation'     => '$w * 2 + $ot',
				),
			),
			array(
				'post_title' => 'GF',
				'post_name'  => 'gf',
				'meta'       => array(
					'sp_equation'     => '$gfor',
				),
			),
			array(
				'post_title' => 'GA',
				'post_name'  => 'ga',
				'meta'       => array(
					'sp_equation'     => '$gagainst',
				),
			),
			array(
				'post_title' => 'Strk',
				'post_name'  => 'strk',
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
			array(
				'post_title' => 'Win',
				'post_name'  => 'w'
			),
			array(
				'post_title' => 'Loss',
				'post_name'  => 'l'
			),
			array(
				'post_title' => 'Overtime',
				'post_name'  => 'ot'
			),
		),
	),
);
