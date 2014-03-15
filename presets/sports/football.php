<?php
global $sportspress_sports;

$sportspress_sports['football'] = array(
	'name' => __( 'American Football', 'sportspress' ),
	'terms' => array(
		// Positions
		'sp_position' => array(
			array(
				'name' => 'Quarterback',
				'slug' => 'quarterback',
			),
			array(
				'name' => 'Running Back',
				'slug' => 'runningback',
			),
			array(
				'name' => 'Wide Receiver',
				'slug' => 'widereceiver',
			),
			array(
				'name' => 'Tight End',
				'slug' => 'tightend',
			),
			array(
				'name' => 'Defensive Lineman',
				'slug' => 'defensivelineman',
			),
			array(
				'name' => 'Linebacker',
				'slug' => 'linebacker',
			),
			array(
				'name' => 'Defensive Back',
				'slug' => 'defensiveback',
			),
			array(
				'name' => 'Kickoff Kicker',
				'slug' => 'kickoffkicker',
			),
			array(
				'name' => 'Kick Returner',
				'slug' => 'kickreturner',
			),
			array(
				'name' => 'Punter',
				'slug' => 'punter',
			),
			array(
				'name' => 'Punt Returner',
				'slug' => 'puntreturner',
			),
			array(
				'name' => 'Field Goal Kicker',
				'slug' => 'fieldgoalkicker',
			),
		),
	),
	'posts' => array(
		// Results
		'sp_result' => array(
			array(
				'post_title' => '1',
				'post_name'  => 'one',
			),
			array(
				'post_title' => '2',
				'post_name'  => 'two',
			),
			array(
				'post_title' => '3',
				'post_name'  => 'three',
			),
			array(
				'post_title' => '4',
				'post_name'  => 'four',
			),
			array(
				'post_title' => 'TD',
				'post_name'  => 'td',
			),
			array(
				'post_title' => 'T',
				'post_name'  => 't',
			),
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => 'Win',
				'post_name'  => 'w',
			),
			array(
				'post_title' => 'Loss',
				'post_name'  => 'l',
			),
			array(
				'post_title' => 'Tie',
				'post_name'  => 't',
			),
		),
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'W',
				'post_name' => 'w',
				'meta' => array(
					'sp_equation'     => '$w',
				),
			),
			array(
				'post_title' => 'L',
				'post_name' => 'l',
				'meta' => array(
					'sp_equation'     => '$l',
				),
			),
			array(
				'post_title' => 'T',
				'post_name' => 't',
				'meta' => array(
					'sp_equation'     => '$t',
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'meta' => array(
					'sp_equation'     => '$w / $eventsplayed',
				),
			),
			array(
				'post_title' => 'PF',
				'post_name' => 'pf',
				'meta' => array(
					'sp_equation'     => '$tfor',
				),
			),
			array(
				'post_title' => 'PA',
				'post_name' => 'pa',
				'meta' => array(
					'sp_equation'     => '$tagainst',
				),
			),
			array(
				'post_title' => 'Net Pts',
				'post_name' => 'netpts',
				'meta' => array(
					'sp_equation'     => '$tfor - $tagainst',
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'td',
				'meta' => array(
					'sp_equation'     => '$td',
				),
			),
			array(
				'post_title' => 'Strk',
				'post_name' => 'strk',
				'meta' => array(
					'sp_equation'     => '$streak',
				),
			),
			array(
				'post_title' => 'Last 5',
				'post_name' => 'last5',
				'meta' => array(
					'sp_equation'     => '$last5',
				),
			),
		),
		// Player Metrics
		'sp_metric' => array(
			array(
				'post_title' => 'Height',
				'post_name'  => 'height',
			),
			array(
				'post_title' => 'Weight',
				'post_name'  => 'weight',
			),
		),
		// Player Statistics
		'sp_statistic' => array(
			array(
				'post_title' => 'Comp',
				'post_name' => 'comp',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
					),
				),
			),
			array(
				'post_title' => 'Att',
				'post_name' => 'att',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
					),
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'meta' => array(
					'sp_calculate' => 'average',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'kickoffkicker',
					),
				),
			),
			array(
				'post_title' => 'Att/G',
				'post_name' => 'attg',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
					),
				),
			),
			array(
				'post_title' => 'Rec',
				'post_name' => 'rec',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'widereceiver',
						'tightend',
					),
				),
			),
			array(
				'post_title' => 'Comb',
				'post_name' => 'comb',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'Total',
				'post_name' => 'total',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'Ast',
				'post_name' => 'ast',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'Sck',
				'post_name' => 'scktackles',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'SFTY',
				'post_name' => 'sfty',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'PDef',
				'post_name' => 'pdef',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'TDs',
				'post_name' => 'tds',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'KO',
				'post_name' => 'ko',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
			),
			array(
				'post_title' => 'Ret',
				'post_name' => 'ret',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Punts',
				'post_name' => 'punts',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'Yds',
				'post_name' => 'yds',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'defensivelineman',
						'linebacker',
						'defensiveback',
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Net Yds',
				'post_name' => 'netyds',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'Avg',
				'post_name' => 'avg',
				'meta' => array(
					'sp_calculate' => 'average',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Net Avg',
				'post_name' => 'netavg',
				'meta' => array(
					'sp_calculate' => 'average',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'Blk',
				'post_name' => 'blk',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'OOB',
				'post_name' => 'oob',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'punter',
					),
				),
			),
			array(
				'post_title' => 'Dn',
				'post_name' => 'dn',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'IN 20',
				'post_name' => 'in20',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'TB',
				'post_name' => 'tb',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
			),
			array(
				'post_title' => 'FC',
				'post_name' => 'fc',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Ret',
				'post_name' => 'retpunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'RetY',
				'post_name' => 'rety',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Yds/G',
				'post_name' => 'ydsg',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
					),
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'TD',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'defensivelineman',
						'linebacker',
						'defensiveback',
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Int',
				'post_name' => 'int',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => '1st',
				'post_name' => 'first',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
					),
				),
			),
			array(
				'post_title' => '1st%',
				'post_name' => 'firstpct',
				'meta' => array(
					'sp_calculate' => 'average',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
					),
				),
			),
			array(
				'post_title' => 'Lng',
				'post_name' => 'lng',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'defensivelineman',
						'linebacker',
						'defensiveback',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => '20+',
				'post_name' => 'twentyplus',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => '40+',
				'post_name' => 'fourtyplus',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Sck',
				'post_name' => 'sck',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
					),
				),
			),
			array(
				'post_title' => 'Rate',
				'post_name' => 'rate',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
					),
				),
			),
			array(
				'post_title' => 'FUM',
				'post_name' => 'fum',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'runningback',
						'widereceiver',
						'tightend',
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'FF',
				'post_name' => 'ff',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'Rec',
				'post_name' => 'recfum',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'tdfum',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
			),
			array(
				'post_title' => 'Avg',
				'post_name' => 'avgpunt',
				'meta' => array(
					'sp_calculate' => 'average',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'Lng',
				'post_name' => 'lngpunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'tdpunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => '20+',
				'post_name' => 'twentypluspunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => '40+',
				'post_name' => 'fourtypluspunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'FC',
				'post_name' => 'fcpunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'FUM',
				'post_name' => 'fumpunt',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
			),
			array(
				'post_title' => 'OSK',
				'post_name' => 'osk',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
			),
			array(
				'post_title' => 'OSKR',
				'post_name' => 'oskr',
				'meta' => array(
					'sp_calculate' => 'total',
				),
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
			),
		),
	),
);
