<?php
global $sportspress_sports;

$sportspress_sports['football'] = array(
	'name' => __( 'American Football', 'sportspress' ),
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
				'post_title' => 'T',
				'post_name' => 't',
				'meta'       => array(
					'sp_equation'     => '$t',
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
					'sp_equation'     => '$pointsfor',
				),
			),
			array(
				'post_title' => 'PA',
				'post_name' => 'pa',
				'meta'       => array(
					'sp_equation'     => '$pointsagainst',
				),
			),
			array(
				'post_title' => 'Net Pts',
				'post_name' => 'netpts',
				'meta'       => array(
					'sp_equation'     => '$pointsfor - $pointsagainst',
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'td',
				'meta'       => array(
					'sp_equation'     => '$touchdown',
				),
			),
			array(
				'post_title' => 'Strk',
				'post_name' => 'strk',
				'meta'       => array(
					'sp_equation'     => '$streak',
				),
			),
			array(
				'post_title' => 'Last 5',
				'post_name' => 'last5',
				'meta'       => array(
					'sp_equation'     => '$last5',
				),
			),
		),
		// Statistics
		'sp_statistic' => array(
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp', // QB
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
			),
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
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
