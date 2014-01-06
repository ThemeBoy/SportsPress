<?php
global $sportspress_sports;

$sportspress_sports['basketball'] = array(
	'name' => 'Basketball',
	'posts' => array(
		// Table Columns
		'sp_column' => array(
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
				'post_title' => 'Pct',
				'post_name'  => 'pct',
				'meta'       => array(
					'sp_equation'     => '$w / $eventsplayed',
				),
			),
			array(
				'post_title' => 'GB',
				'post_name'  => 'gb',
				'meta'       => array(
					'sp_equation'     => '( $wmax + $l - $w - $lmax ) / 2',
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
			array(
				'post_title' => '1',
				'post_name'  => 'first',
			),
			array(
				'post_title' => '2',
				'post_name'  => 'second',
			),
			array(
				'post_title' => '3',
				'post_name'  => 'third',
			),
			array(
				'post_title' => '4',
				'post_name'  => 'fourth',
			),
			array(
				'post_title' => 'OT',
				'post_name'  => 'ot',
			),
			array(
				'post_title' => 'T',
				'post_name'  => 't',
			),
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => 'W',
				'post_name'  => 'w'
			),
			array(
				'post_title' => 'L',
				'post_name'  => 'l'
			),
		),
	),
);