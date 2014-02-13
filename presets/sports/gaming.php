<?php
global $sportspress_sports;

$sportspress_sports['gaming'] = array(
	'name' => __( 'Competitive Gaming', 'sportspress' ),
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
				'post_title' => 'Strk',
				'post_name' => 'strk',
				'meta'       => array(
					'sp_equation'     => '$strk',
				),
			),
			array(
				'post_title' => 'XP',
				'post_name' => 'xp',
				'meta'       => array(
					'sp_equation'     => '$xp',
				),
			),
			array(
				'post_title' => 'Rep',
				'post_name' => 'rep',
				'meta'       => array(
					'sp_equation'     => '$rep / $eventsplayed',
				),
			),
			array(
				'post_title' => 'Ping',
				'post_name' => 'ping',
				'meta'       => array(
					'sp_equation'     => '$ping / $eventsplayed',
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
